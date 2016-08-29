describe('Base.Layout.Subpanels', function() {
    var layout, app, module = 'Cases';

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'layout', 'subpanels');
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();
        app = SugarTest.app;
    });

    afterEach(function() {
        sinon.collection.restore();
        SugarTest.testMetadata.dispose();
    });

    describe('initialize', function() {
        var testMeta, testLayout;

        beforeEach(function() {
            testMeta = {
                settings: {
                    sortable: false,
                    showAlerts: false
                }
            };
            testLayout = SugarTest.createLayout('base', module, 'subpanels', testMeta);
        });

        afterEach(function() {
            testLayout.dispose();
        });

        it('should initialize settings properly', function() {
            _.each(testMeta.settings, function(value, key) {
                expect(testLayout._settings[key]).toEqual(value);
            });
        });
    });

    describe('render', function() {
        var lastStateGetStub, componentsMeta;

        beforeEach(function() {
            componentsMeta = [{
                layout: 'subpanel',
                context: {link: 'calls'}
            },{
                layout: 'subpanel',
                context: {link: 'meetings'}
            },{
                layout: 'subpanel',
                context: {link: 'tasks'}
            },{
                layout: 'subpanel',
                context: {link: 'notes'}
            }];

            sinon.collection.stub(app.metadata, 'getHiddenSubpanels', function() {
                return {};
            });
            sinon.collection.stub(app.template, 'getLayout')
                .withArgs('subpanel')
                .returns(Handlebars.compile(
                    '<div data-subpanel={{context.attributes.link}}></div>'
                ));
        });

        it('should render based on the metadata order', function() {
            var testMeta = {
                    components: componentsMeta,
                    settings: {
                        sortable: false
                    }
                },
                testLayout = SugarTest.createLayout('base', module, 'subpanels', testMeta);

            testLayout.render();

            var metaComponentOrder = ['calls', 'meetings', 'tasks', 'notes'],
                layoutComponentOrder = _.map(testLayout.$('[data-subpanel]'), function(el) {
                    return $(el).data('subpanel');
                });

            expect(layoutComponentOrder).toEqual(metaComponentOrder);
        });

        it('should support sortable on the metadata order', function() {
            var testMeta = {
                    components: componentsMeta,
                    settings: {
                        sortable: true
                    }
                },
                testOrder = ['meetings', 'tasks', 'notes', 'calls'],
                lastStateGetStub = sinon.collection.stub(app.user.lastState, 'get').returns(testOrder),
                testLayout = SugarTest.createLayout('base', module, 'subpanels', testMeta),
                sortableStub = sinon.collection.stub($.fn, 'sortable');

            testLayout.render();

            var layoutComponentOrder = _.map(testLayout.$('[data-subpanel]'), function(el) {
                return $(el).data('subpanel');
            });

            expect(sortableStub).toHaveBeenCalled();
            expect(layoutComponentOrder).toEqual(testOrder);
        });
    });

    describe('stickiness', function() {
        var layout, appAlertStub, lastStateSetStub, key = module + ':subpanels:order';

        beforeEach(function() {
            layout = SugarTest.createLayout('base', module, 'subpanels', {
                settings: {
                    showAlerts: true
                }
            });
            lastStateSetStub = sinon.collection.stub(app.user.lastState, 'set');
            appAlertStub = sinon.collection.stub(app.alert, 'show');
        });

        afterEach(function() {
            layout.dispose();
        });

        it('should save the order to localstorage', function() {
            var order = ['link1', 'link2', 'link3'];

            layout.trigger('subpanels:reordered', layout, order);
            expect(lastStateSetStub).toHaveBeenCalledWith(key, order);
        });

        it('should display an alert if `showAlerts` is `true`', function() {
            layout.trigger('subpanels:reordered');
            expect(appAlertStub).toHaveBeenCalled();
        });
    });

    describe('sorting', function() {
        var layout;

        beforeEach(function() {
            layout = SugarTest.createLayout('base', module, 'subpanels', {
                settings: {
                    showAlerts: false
                }
            });
        });

        afterEach(function() {
            layout.dispose();
        });

        describe('handleSort', function() {
            var sortableStub;

            beforeEach(function() {
                sortableStub = sinon.collection.stub($.fn, 'sortable');
            });

            it('should trigger `subpanels:reordered` with the new order', function() {
                var order = ['link1', 'link2', 'link3'],
                    triggerStub = sinon.collection.stub(layout, 'trigger');

                sortableStub.returns(order);

                layout.handleSort();

                expect(sortableStub).toHaveBeenCalled();
                expect(triggerStub).toHaveBeenCalledWith('subpanels:reordered', layout, order);
            });
        });

        describe('reorderSubpanels', function() {
            var lastStateGetStub, key = module + ':subpanels:order';

            beforeEach(function() {
                lastStateGetStub = sinon.collection.stub(app.user.lastState, 'get');
            });

            using('different component meta and orders', [
                {
                    // Straight re-order
                    components: [
                        {context: {link: 'calls'}},
                        {context: {link: 'meetings'}},
                        {context: {link: 'tasks'}},
                        {context: {link: 'notes'}}
                    ],
                    order: ['meetings', 'calls', 'notes', 'tasks'],
                    expected: ['meetings', 'calls', 'notes', 'tasks']
                },
                {
                    // Component that no longer exists ('contacts')
                    components: [
                        {context: {link: 'calls'}},
                        {context: {link: 'meetings'}},
                        {context: {link: 'tasks'}},
                        {context: {link: 'notes'}}
                    ],
                    order: ['meetings', 'contacts', 'notes', 'tasks', 'calls'],
                    expected: ['meetings', 'notes', 'tasks', 'calls']
                },
                {
                    // New component that doesn't exist in the order ('contacts')
                    components: [
                        {context: {link: 'calls'}},
                        {context: {link: 'meetings'}},
                        {context: {link: 'contacts'}},
                        {context: {link: 'tasks'}},
                        {context: {link: 'notes'}}
                    ],
                    order: ['meetings', 'calls', 'notes', 'tasks'],
                    expected: ['meetings', 'calls', 'notes', 'tasks', 'contacts']
                },
                {
                    // No order found in localstorage
                    components: [
                        {context: {link: 'calls'}},
                        {context: {link: 'meetings'}},
                        {context: {link: 'tasks'}},
                        {context: {link: 'notes'}}
                    ],
                    order: undefined,
                    expected: ['calls', 'meetings', 'tasks', 'notes']
                }
            ], function(value) {
                it('should sort the subpanel components appropriately', function() {
                    lastStateGetStub.withArgs(key).returns(value.order);

                    var result = layout.reorderSubpanels(value.components);
                    expect(_.pluck(_.pluck(result, 'context'), 'link')).toEqual(value.expected);
                });
            });
        });
    });

    describe('_addComponentsFromDef', function() {
        var layout, hiddenPanelsStub, relationshipStub, reorderStub, lastStateGetStub;

        beforeEach(function() {
            layout = SugarTest.createLayout('base', module, 'subpanels');
            //Mock sidecar calls
            hiddenPanelsStub = sinon.collection.stub(app.metadata, 'getHiddenSubpanels', function() {
                return {0: 'bugs', 1: 'contacts'};
            });
            relationshipStub = sinon.collection.stub(app.data, 'getRelatedModule', function(module, linkName){
                // test1 is a fictional bad link that doesnt return
                if (linkName === 'test1') {
                    return false;
                } else {
                    //return linkName as module name for test
                    return linkName;
                }
            });
            superStub = sinon.collection.stub(layout, '_super');
            reorderStub = sinon.collection.stub(layout, 'reorderSubpanels', function(obj) {
                return obj;
            });
        });

        afterEach(function() {
            layout.dispose();
        });

        it('Should not add subpanel components for modules that are hidden in subpanels', function() {
            var components = [
                {context: {link: 'bugs'}, layout: 'subpanel'},  //Should be hidden
                {context: {link: 'cases'}, layout: 'subpanel'},
                {context: {link: 'accounts'}, layout: 'subpanel'}
            ];
            var hiddenComponent = [
                {context: {link: 'bugs'}, layout: 'subpanel'}
            ];
            var filteredComponents = [
                {context: {link: 'cases'}, layout: 'subpanel'},
                {context: {link: 'accounts'}, layout: 'subpanel'}
            ];
            function reset() {
                hiddenPanelsStub.reset();
                relationshipStub.reset();
                superStub.reset();
            }
            var returnedComponents = layout._pruneHiddenComponents(components);
            expect(returnedComponents).toEqual(filteredComponents);
            reset();
            returnedComponents = layout._pruneHiddenComponents(filteredComponents);
            expect(returnedComponents).toEqual(filteredComponents);
            reset();
            returnedComponents = layout._pruneHiddenComponents(hiddenComponent);
            expect(returnedComponents).toEqual([]);
        });
        it('should prune subapenls for relationships that dont exist', function(){
            var inputComponents = [
                {
                    'context': {
                        'link': 'test1'
                    }
                },
                {
                    'context': {
                        'link': 'test2'
                    }
                }
            ];
            var output = [
                {
                    'context': {
                        'link': 'test2'
                    }
                }
            ];

            var result = layout._pruneHiddenComponents(inputComponents);

            expect(result).toEqual(output);
        });
        it('Should prune subpanels for which user has no access to', function() {
            layout.model = {
                fields: {
                    'good': { module: 'GoodLink'},
                    'bad': { module: 'BadLink'}
                }
            }
            layout.aclToCheck = 'view';
            var hasAccessStub = sinon.collection.stub(app.acl, 'hasAccess', function(acl, link) {
                return link === 'good' ? true : false;
            });
            var components = [
                {context: {link: 'good'}},
                {context: {link: 'bad'}}
            ];
            var actual = layout._pruneNoAccessComponents(components);
            expect(actual.length).toEqual(1);
            expect(actual[0].context.link).toEqual('good');
            layout.model = null;//so we don't try to dispose bogus
        });
        it('Should disable toggle buttons if all subpanels are hidden', function() {
            layout.layout = new Backbone.View({
                trigger: function(){}
            });
            var stub = sinon.collection.stub(layout.layout, 'trigger');
            layout._disableSubpanelToggleButton([1,2,3]);
            expect(stub).not.toHaveBeenCalled();
        });
        it('Should not disable toggle buttons if unless all subpanels are hidden', function() {
            layout.layout = new Backbone.View({
                trigger: function(){}
            });
            var stub = sinon.collection.stub(layout.layout, 'trigger');
            layout._disableSubpanelToggleButton([]);
            expect(stub).toHaveBeenCalled();
        });
        it('Should hide hidden subpanels and also hide ACL forbidden subpanels', function() {
            layout.model = {
                fields: {
                    'cases': { module: 'contacts'}, // ACL Forbidden
                    'bugs': { module: 'bugs'},
                    'accounts': { module: 'accounts'}
                }
            }
            layout.aclToCheck = 'view';
            var hasAccessStub = sinon.collection.stub(app.acl, 'hasAccess', function(acl, link) {
                return link === 'cases' ? false : true;
            });
            var components = [
                {context: {link: 'bugs'}, layout: 'subpanel'},  //Should be hidden
                {context: {link: 'cases'}, layout: 'subpanel'}, //Should be ACL forbidden
                {context: {link: 'accounts'}, layout: 'subpanel'}
            ];
            var hiddenComponent = [
                {context: {link: 'bugs'}, layout: 'subpanel'}
            ];
            var aclForbiddenComponent = [
                {context: {link: 'contacts'}, layout: 'subpanel'}
            ];
            var filteredComponents = [
                {context: {link: 'accounts'}, layout: 'subpanel'}
            ];
            function reset() {
                hiddenPanelsStub.reset();
                relationshipStub.reset();
                superStub.reset();
            }
            layout._addComponentsFromDef(components);
            expect(superStub.called).toBe(true);
            expect(superStub.args[0][1][0]).toEqual(filteredComponents);
            layout.model = null;//so we don't try to dispose bogus
        });

        it('Should mark components as being subpanels', function() {
            var components = [
                {context: {link: 'bugs'}, layout: 'subpanel'},  //Should be hidden
                {context: {link: 'cases'}, layout: 'subpanel'}, //Should be ACL forbidden
                {context: {link: 'accounts'}, layout: 'subpanel'}
            ];
            layout._components = [];
            _.each(components, function(component) {
                layout._components.push(
                    app.view.createView({
                        context: new Backbone.Model(component.context),
                        layout: layout
                    })
                );
            });
            layout._markComponentsAsSubpanels(components);
            expect(_.size(layout._components)).toBeGreaterThan(0);
            _.each(layout._components, function(component) {
                expect(component.context.get('isSubpanel')).toBeTruthy();
            });
        });

    });
});
