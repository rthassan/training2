describe("Preview Header View", function() {

    var app, view, layout;

    beforeEach(function() {
        app = SugarTest.app;
        var context = app.context.getContext();
        layout = app.view.createLayout({
            name: 'records',
            context: context
        });

        view = SugarTest.createView("base","Accounts", "preview-header", null, context, null, layout);
        view.model = new Backbone.Model();
        app.drawer = {
                isActive:function() {
                    
                }
        };
    });
    
    afterEach(function() {
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        view.model = null;
        view = null;
        layout = null;
        delete app.drawer;
    });

    it("should trigger preview:close on preview close", function() {
        var spy = sinon.spy();

        app.events.off('preview:close');
        app.events.on('preview:close', spy);
        view.triggerClose();
        expect(spy).toHaveBeenCalled();
    });
});
