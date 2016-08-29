describe('Base.View.Attachments', function() {
    var app, view, moduleName = 'Contacts', viewName = 'attachments', layout;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'layout', 'dashboard');
        SugarTest.loadHandlebarsTemplate(viewName, 'view', 'base');
        SugarTest.loadComponent('base', 'view', viewName);
        SugarTest.testMetadata.set();
        app = SugarTest.app;
        layout = SugarTest.createLayout('base', moduleName, 'dashboard');
        view = SugarTest.createView('base', moduleName, viewName, {}, null, null, layout);
    });

    afterEach(function() {
        view.dispose();
        app.view.reset();
        sinon.collection.restore();
        SugarTest.testMetadata.dispose();
    });

    describe('dispose safe', function() {
        it('should dispose interval safe', function() {
            view.timerId = 'fakeID';
            var intervalStub = sinon.collection.stub(window, 'clearInterval');
            expect(intervalStub).not.toHaveBeenCalled();

            view.dispose();
            expect(intervalStub).toHaveBeenCalled();
        });
    });
});
