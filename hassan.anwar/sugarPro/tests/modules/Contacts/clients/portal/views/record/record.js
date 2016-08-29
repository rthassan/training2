describe("PortalContacts.Views.Record", function() {
    var app, view;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.seedMetadata(true, './fixtures');
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', 'record');
        SugarTest.loadComponent('portal', 'view', 'record', 'Contacts');
        SugarTest.testMetadata.set();
        view = SugarTest.createView('portal', 'Contacts', 'record', null, null, true, null, true, 'portal');
    });

    afterEach(function() {
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
    });

    describe("_setPreferredLanguage", function(){
        var newLang = "fr_FR",
            oldLang = "en_US",
            langStub;

        beforeEach(function() {
            langStub = sinon.stub(app.lang, "setLanguage", function(lang, callback){
                expect(lang).toEqual(newLang);
                callback();
            });
        });

        afterEach(function() {
            langStub.restore();
        });

        it("should set new app language if the preferred language changed", function() {
            app.lang.setCurrentLanguage(oldLang);
            view.model.set("preferred_language", newLang);
            view._setPreferredLanguage();
            expect(langStub.called).toBe(true);
        });

        it("should not change app language if the preferred language is unchanged", function() {
            app.lang.setCurrentLanguage(oldLang);
            view.model.set("preferred_language", oldLang);
            view._setPreferredLanguage();
            expect(langStub.called).toBe(false);
        });
    });



});
