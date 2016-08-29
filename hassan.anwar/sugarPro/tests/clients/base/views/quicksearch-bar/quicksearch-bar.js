describe('View.Views.Base.QuicksearchBarView', function() {
    var viewName = 'quicksearch-bar',
        view, layout;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', viewName);
        SugarTest.testMetadata.set();
        sinon.collection.stub(SugarTest.app.metadata, 'getModules', function() {
            var fakeModuleList = {
                Accounts: {ftsEnabled: true, globalSearchEnabled: true},
                Contacts: {ftsEnabled: true, globalSearchEnabled: true},
                ftsDisabled: {ftsEnabled: false, globalSearchEnabled: true},
                ftsNotSet: {},
                NoAccess: {ftsEnabled: true}
            };
            return fakeModuleList;
        });
        sinon.collection.stub(SugarTest.app.acl, 'hasAccess', function(action, module) {
            return module !== 'NoAccess';
        });
        sinon.collection.stub(SugarTest.app.api, 'isAuthenticated').returns(true);

        layout = SugarTest.app.view.createLayout({});
        view = SugarTest.createView('base', null, viewName, null, null, null, layout);
        // Required to define `this.$input` value.
        view._renderHtml();
    });

    afterEach(function() {
        SugarTest.testMetadata.dispose();
        sinon.collection.restore();
        layout.dispose();
        layout = null;
        view = null;
    });

    describe('navigation', function() {
        var keyDisposeStub, triggerBeforeStub, triggerStub;
        beforeEach(function() {
            keyDisposeStub = sinon.collection.stub(view, 'disposeKeyEvents');
            triggerBeforeStub = sinon.collection.stub(view.layout, 'triggerBefore', function() {
                return true;
            });
            triggerStub = sinon.collection.stub(view.layout, 'trigger');
        });

        describe('moveForward', function() {
            it('should run the appropriate functions and fire the appropriate events when moving forward', function() {
                view.moveForward();
                expect(triggerBeforeStub).toHaveBeenCalledOnce();
                expect(triggerBeforeStub).toHaveBeenCalledWith('navigate:next:component');
                expect(keyDisposeStub).toHaveBeenCalledOnce();
                expect(triggerStub).toHaveBeenCalledOnce();
                expect(triggerStub).toHaveBeenCalledWith('navigate:next:component');
            });
        });

        describe('moveBackward', function() {
            it('should run the appropriate functions and fire the appropriate events when moving backward', function() {
                view.moveBackward();
                expect(triggerBeforeStub).toHaveBeenCalledOnce();
                expect(triggerBeforeStub).toHaveBeenCalledWith('navigate:previous:component');
                expect(keyDisposeStub).toHaveBeenCalledOnce();
                expect(triggerStub).toHaveBeenCalledOnce();
                expect(triggerStub).toHaveBeenCalledWith('navigate:previous:component');
            });
        });

        describe('requestFocus', function() {
            it('should trigger navigate:to:component on the layout', function() {
                view.requestFocus();
                expect(triggerStub).toHaveBeenCalledOnce();
                expect(triggerStub).toHaveBeenCalledWith('navigate:to:component', viewName);
            });
        });

        describe('navigate:focus:lost', function() {
            it('should disposeKeyEvents', function() {
                view.trigger('navigate:focus:lost');
                //expect(keyDisposeStub).toHaveBeenCalledOnce();
                expect(keyDisposeStub).toHaveBeenCalled();
            });
        });
    });
});
