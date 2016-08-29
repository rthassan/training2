describe("Base.Field.QuickCreate", function() {
    var app, field, drawerBefore, event, alertShowStub, alertConfirm, mockDrawerCount, collection, spyOnLoad;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.loadComponent('base', 'field', 'quickcreate');
        field = SugarTest.createField("base","quickcreate", "quickcreate", "quickcreate");
        alertConfirm = false;
        mockDrawerCount = 0;

        alertShowStub = sinon.stub(app.alert, 'show', function(name, options) {
            if (alertConfirm) options.onConfirm();
        });

        drawerBefore = app.drawer;
        app.drawer = {
            count: function() {
                return mockDrawerCount;
            },
            reset: sinon.stub(),
            open: sinon.stub()
        };

        event = {
            currentTarget: '<a data-module="Foo" data-layout="Bar"></a>'
        };

        collection = new app.BeanCollection();
        collection.module = "Foo";
        collection.fetch = function(){};
        spyOnLoad = sinon.spy(app.Context.prototype, 'loadData');
    });

    afterEach(function() {
        alertShowStub.restore();
        spyOnLoad.restore();
        app.drawer = drawerBefore;
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        field = null;
    });

    it('should open the drawer without confirm if no drawers open', function() {
        var drawerOptions;
        field._handleActionLink(event);
        drawerOptions = _.first(app.drawer.open.lastCall.args);

        expect(alertShowStub.callCount).toBe(0);
        expect(drawerOptions.context.module).toEqual('Foo');
        expect(drawerOptions.layout).toEqual('Bar');
    });

    it('should show confirmation when drawers are open and not open drawer if not confirmed', function() {
        alertConfirm = false;
        mockDrawerCount = 1;
        field.createHasChanges = true;
        field._handleActionLink(event);

        expect(alertShowStub.callCount).toBe(1);
        expect(app.drawer.reset.callCount).toBe(0);
        expect(app.drawer.open.callCount).toBe(0);
    });

    it('should NOT show confirmation when drawers are open and but create view does NOT have changes', function() {
        alertConfirm = false;
        mockDrawerCount = 1;
        field.createHasChanges = false;
        field._handleActionLink(event);

        expect(alertShowStub.callCount).toBe(0);
        expect(app.drawer.open.callCount).toBe(1);
    });

    it('should reset drawers and open new drawer if confirmed', function() {
        alertConfirm = true;
        mockDrawerCount = 2;
        field.createHasChanges = true;
        field._handleActionLink(event);

        expect(alertShowStub.callCount).toBe(1);
        expect(app.drawer.reset.callCount).toBe(1);
        expect(app.drawer.open.callCount).toBe(1);
    });

    it('should refresh collection for current app context if it is same module', function() {
        alertConfirm = true;
        mockDrawerCount = 1;
        app.drawer.open = function(options, callback){ callback(true); };

        app.controller.context.set("collection", collection);
        field._handleActionLink(event);
        expect(spyOnLoad).toHaveBeenCalled();
        app.controller.context.unset("collection");
    });

    it('should refresh collection(s) for child contexts if it is same module', function() {
        alertConfirm = true;
        mockDrawerCount = 1;
        app.drawer.open = function(options, callback){ callback(true); };
        var child = new app.Context();

        child.set("collection", collection);
        app.controller.context.children = [child];
        field._handleActionLink(event);
        expect(spyOnLoad).toHaveBeenCalled();
        app.controller.context.children = [];
    });
    it('Should create a regular bean if parent model isn\'t populated.', function() {
        var parentModel = app.data.createBean('Test'),
            getRelatedModuleStub = sinon.stub(app.data, 'getRelatedModule', function() {
                return 'Accounts';
            }),
            origParent = field.context.parent;

        parentModel.dataFetched = false;
        field.context.parent = {isCreate:function(){return true}};

        var newModel = field.createLinkModel(parentModel, 'test');
        getRelatedModuleStub.restore();
        field.context.parent = origParent;
        expect(newModel.link).not.toBeDefined();
    });

    describe('createLinkModel', function() {
        var parentModel, sandbox = sinon.sandbox.create();

        beforeEach(function() {
            parentModel = new Backbone.Model({
                id: '101-model-id',
                name: 'parent product name',
                account_id: 'abc-111-2222',
                account_name: 'parent account name',
                assigned_user_name: 'admin'
            });
            sandbox.stub(app.data, 'createRelatedBean', function() {
                return new Backbone.Model();
            });
            sandbox.stub(app.data, 'getRelateFields', function() {
                return [
                    {
                        name: 'product_template_name',
                        rname: 'name',
                        id_name: 'product_template_id',
                        populate_list: {
                            assigned_user_name: 'user_name'
                        }
                    }
                ];
            });
            sandbox.stub(app.metadata, 'getModule', function() {
                return {
                    fields: {
                        cases: {
                            populate_list: [
                                'account_id',
                                'account_name'
                            ]
                        }
                    }
                };
            });
        });
        afterEach(function() {
            parentModel = null;
            sandbox.restore();
        });

        it('newModel will contain account_id and account_name from link populate_list', function() {
            var newModel = field.createLinkModel(parentModel, 'cases');
            expect(newModel.get('account_id')).toBe(parentModel.get('account_id'));
            expect(newModel.get('account_name')).toBe(parentModel.get('account_name'));
            expect(newModel.get('assigned_user_name')).toBe(parentModel.get('user_name'));

        });
    });
});
