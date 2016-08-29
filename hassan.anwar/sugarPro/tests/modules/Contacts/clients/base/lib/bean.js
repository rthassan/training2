describe("ContactsBean", function() {
    var app, bean, _oRouter;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        app = SugarTest.app;
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();
        _oRouter = app.router;
        app.router = {};
        app.router.start = function(){};

        SugarTest.loadFile('../modules/Contacts/clients/base/lib', 'bean', 'js', function(d) {
            eval(d);
            app.events.trigger('app:sync:complete');
        });
    });

    afterEach(function() {
        app.router = _oRouter;
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
    });

    it("should not extend bean class if Contacts bean does not exist (gonna create infinite loop)", function(){
        app.data.resetModel();
        app.events.trigger('app:sync:complete');
        expect(app.data.getBeanClass("Contacts").prototype._doValidatePortalName).toBeUndefined();
    });

    describe("isValid", function(){
        beforeEach(function() {
            bean = app.data.createBean("Contacts");
            SugarTest.clock.restore();
        });

        it("should perform uniqueness check only if portal_name attribute has changed", function(){
            bean.fields = {portal_name: {required: true, name: 'portal_name'}, field: {required: true, name: 'field'}};
            var stub = sinon.stub(app.api, 'records'),
                stub2 = sinon.stub(bean, "trigger");

            bean.set("field", "abc");
            runs(function(){
                bean._doValidatePortalName(bean.fields, {}, function() {});
            });
            waitsFor(function() {
                return stub2.calledTwice;
            }, 'trigger should have been called but timeout expired', 1000);
            runs(function(){
                expect(stub.called).toBe(false);

                stub2.reset();

                bean.set("portal_name", "abc");
                bean._doValidatePortalName(bean.fields, {}, function() {});
            });
            waitsFor(function() {
                return stub2.calledTwice;
            }, 'trigger should have been called but timeout expired', 1000);
            runs(function(){
                expect(stub.called).toBe(true);
                stub.restore();
                stub2.restore();
            });
        });

        it("should return an error on portal_name field when there is another Contact with the same portal_name", function(){
            bean.fields = {portal_name: {name: 'portal_name'}};
            var stub = sinon.stub(app.api, 'records', function(method, module, data, params, callbacks){
                // Fake having 1 match for "abc" as portal_name
                expect(params.filter[0].portal_name).toEqual("abc");
                callbacks.success({records:[{id: "123", portal_name: "abc"}]});
                callbacks.complete();
            });
            var stub2 = sinon.stub(bean, "trigger");
            var callbackSpy = sinon.spy();

            bean.set("portal_name", "abc");

            runs(function(){
                bean._doValidatePortalName(bean.fields, {}, callbackSpy);
            });
            waitsFor(function() {
                return stub2.callCount > 0;
            }, 'trigger should have been called but timeout expired', 1000);
            runs(function(){
                expect(stub.called).toBe(true);
                expect(callbackSpy.called).toBe(true);
                expect(callbackSpy.lastCall.args[2]["portal_name"]).toBeDefined();

                stub.restore();
                stub2.restore();
            });
        });
    });

});
