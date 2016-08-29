describe('Sugar7 sync alerts', function() {
    var moduleName = 'Cases',
        app,
        context,
        model,
        alertStubs = {},
        originalBean, originalBeanCollection;


    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();
        app = SugarTest.app;
        app.drawer = {
            open: $.noop,
            close: $.noop,
            reset: $.noop
        };
        alertStubs.show = sinon.stub(app.alert, 'show');
        alertStubs.dismiss = sinon.stub(app.alert, 'dismiss');

        context = app.context.getContext();
        model = app.data.createBean(moduleName);

        originalBean = app.Bean;
        originalBeanCollection = app.BeanCollection;

        app.Bean = Backbone.Model;
        app.BeanCollection = Backbone.Collection;

    });

    afterEach(function() {
        context.clear({silent: true});
        SugarTest.testMetadata.dispose();
        SugarTest.app.view.reset();
        delete app.drawer;
        alertStubs.show.restore();
        alertStubs.dismiss.restore();

        app.Bean = originalBean;
        app.BeanCollection = originalBeanCollection;
    });

    describe('overriding primary context', function() {
        it('should fetch the model with showAlerts = true', function() {
            context.set('modelId', 1);
            context.prepare();
            var beanStub = sinon.stub(context.get('model'), 'fetch');
            context.loadData();
            expect(beanStub).toHaveBeenCalled();
            expect(beanStub.args[0][0]).toBeDefined();
            expect(beanStub.args[0][0].showAlerts).toBeTruthy();
            beanStub.restore();
        });

        it('should fetch the collection with showAlerts = true', function() {
            context.prepare();
            var beanStub = sinon.stub(context.get('collection'), 'fetch');
            context.loadData();
            expect(beanStub).toHaveBeenCalled();
            expect(beanStub.args[0][0]).toBeDefined();
            expect(beanStub.args[0][0].showAlerts).toBeTruthy();
            beanStub.restore();
        });

        it('should not touch show alerts for child context', function() {
            context.set('modelId', 1);
            context.prepare();
            var beanStub = sinon.stub(context.get('model'), 'fetch');
            //Fake a parent context
            context.parent = {};
            context.loadData();
            expect(beanStub).toHaveBeenCalled();
            expect(beanStub.args[0][0]).toBeDefined();
            expect(beanStub.args[0][0].showAlerts).toBeUndefined();
            beanStub.restore();
        });
    });

    describe('process alerts for app.sync()', function() {
        it('should display an alert on app:sync', function() {
            app.events.trigger('app:sync', 'read', model, {});
            expect(alertStubs.show).toHaveBeenCalled();
        });

        it('should hide the alert on app:sync:complete', function() {
            //app.router is not defined so it will throw an error
            var routerFake = app.router;
            app.router = {
                start: sinon.stub()
            };
            app.events.trigger('app:sync:complete', 'read', model, {});
            expect(alertStubs.dismiss).toHaveBeenCalled();
            //restore what was defined as app.router
            app.router = routerFake;
        });

        it('should hide the alert on app:sync:error', function() {
            app.events.trigger('app:sync:error', 'read', model, {});
            expect(alertStubs.dismiss).toHaveBeenCalled();
        });
    });

    describe('process alerts for Backbone.sync()', function() {
        it('should display an alert on data:sync:start if options.showAlerts = true', function() {
            var options = { showAlerts: true };
            app.events.trigger('data:sync:start', 'read', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
            app.events.trigger('data:sync:complete', 'read', model, options);
        });

        it('should display an alert on data:sync:start if options.showAlerts.process = true', function() {
            var options = { showAlerts: { process: true } };
            app.events.trigger('data:sync:start', 'read', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
            app.events.trigger('data:sync:complete', 'read', model, options);
        });

        it('should not display an alert on data:sync:start by default', function() {
            var options = {};
            app.events.trigger('data:sync:start', 'read', model, options);
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should not display an alert if options.showAlerts.process = false', function() {
            var options = { showAlerts: { process: false } };
            app.events.trigger('data:sync:start', 'read', model, options);
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should hide the alert on data:sync:complete', function() {
            var options = { showAlerts: { process: true } };
            app.events.trigger('data:sync:complete', 'read', model, options);
            expect(alertStubs.dismiss).toHaveBeenCalled();
        });

        it('should dismiss the alert only on the last data:sync:complete', function() {
            var options = { showAlerts: { process: true } };
            app.events.trigger('data:sync:start', 'read', model, options);
            app.events.trigger('data:sync:start', 'read', model, options);
            app.events.trigger('data:sync:start', 'read', model, options);
            app.events.trigger('data:sync:complete', 'read', model, options);
            expect(alertStubs.dismiss).not.toHaveBeenCalled();
            app.events.trigger('data:sync:complete', 'read', model, options);
            expect(alertStubs.dismiss).not.toHaveBeenCalled();
            app.events.trigger('data:sync:complete', 'read', model, options);
            expect(alertStubs.dismiss).toHaveBeenCalled();
        });


        it('should allow you to override alert options', function() {
            app.events.trigger('data:sync:start', 'read', model, {showAlerts: { process: { title: 'Loading the test'} }});
            expect(alertStubs.show).toHaveBeenCalled();
            expect(alertStubs.show.args[0][0]).toBe('data:sync:process');
            expect(alertStubs.show.args[0][1].title).toEqual('Loading the test');
        });
    });

    describe('success alerts for Backbone.sync()', function() {
        it('should display an alert on data:sync:success if options.showAlerts = true', function() {
            var options = { showAlerts: true };
            app.events.trigger('data:sync:success', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
        });

        it('should display an alert on data:sync:success if options.showAlerts.success = true', function() {
            var options = { showAlerts: { success: true } };
            app.events.trigger('data:sync:success', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
        });

        it('should not display an alert for read method', function() {
            app.events.trigger('data:sync:success', 'read', model, {});
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should not display an alert if options.showAlerts.success = false', function() {
            var options = { showAlerts: { success: false } };
            app.events.trigger('data:sync:success', 'create', model, options);
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should allow you to override alert options', function() {
            var options = {
                showAlerts: {
                    success: {
                        title: 'Success', messages: 'Tests are green'
                    }
                }
            };
            app.events.trigger('data:sync:success', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
            expect(alertStubs.show.args[0][0]).toBe('data:sync:success');
            expect(alertStubs.show.args[0][1].title).toEqual('Success');
            expect(alertStubs.show.args[0][1].messages).toEqual('Tests are green');
        });
    });

    describe('error alerts for Backbone.sync()', function() {
        it('should display an alert on data:sync:error if options.showAlerts = true', function() {
            var options = { showAlerts: true };
            app.events.trigger('data:sync:error', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
        });

        it('should display an alert on data:sync:error if options.showAlerts.error = true', function() {
            var options = { showAlerts: { error: true } };
            app.events.trigger('data:sync:error', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
        });

        it('should not display an alert for read method', function() {
            app.events.trigger('data:sync:error', 'read', model, {});
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should not display an alert if options.showAlerts.error = false', function() {
            var options = { showAlerts: { error: false } };
            app.events.trigger('data:sync:error', 'create', model, options);
            expect(alertStubs.show).not.toHaveBeenCalled();
        });

        it('should allow you to override alert options', function() {
            var options = {
                showAlerts: {
                    error: {
                        title: 'Error', messages: 'Tests are green'
                    }
                }
            };
            app.events.trigger('data:sync:error', 'create', model, options);
            expect(alertStubs.show).toHaveBeenCalled();
            expect(alertStubs.show.args[0][0]).toBe('data:sync:error');
            expect(alertStubs.show.args[0][1].title).toEqual('Error');
            expect(alertStubs.show.args[0][1].messages).toEqual('Tests are green');
        });
    });

});
