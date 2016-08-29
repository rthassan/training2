describe('Base.Field.Sidebartoggle', function() {
    var defaultLayout, field, app;

    beforeEach(function() {
        app = SugarTest.app;
        var def = {
            'components': [
                {'layout': {'span': 4}},
                {'layout': {'span': 8}}
            ]};
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'field', 'sidebartoggle');
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();
        defaultLayout = new Backbone.View();
        defaultLayout.isSidePaneVisible = $.noop;
        field = SugarTest.createField('base', null, 'sidebartoggle', 'record', def);
        sinon.collection.stub(field, 'closestComponent').withArgs('sidebar').returns(defaultLayout);
    });
    afterEach(function() {
        sinon.collection.restore();
        field.dispose();
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
    });

    it('should call isSidePaneVisible on default layout to get the current open/close state', function() {
        var isSidePaneVisibleStub = sinon.collection.stub(defaultLayout, 'isSidePaneVisible');
        field.initialize(field.options);
        expect(isSidePaneVisibleStub).toHaveBeenCalled();
    });

    describe('listeners', function() {
        var toggleStateStub;

        beforeEach(function() {
            toggleStateStub = sinon.collection.stub(field, 'toggleState');
            field.initialize(field.options);
        });

        it('should listen for "sidebar:state:changed" event', function() {
            defaultLayout.trigger('sidebar:state:changed');
            expect(toggleStateStub).toHaveBeenCalled();
        });
    });

    describe('toggle', function() {
        it('should trigger "sidebar:toggle" event', function() {
            var triggerStub = sinon.collection.stub(defaultLayout, 'trigger');
            field.toggle();
            expect(triggerStub).toHaveBeenCalledWith('sidebar:toggle');
        });
    });

    describe('toggleState', function() {
        it('should call stay open if called with open', function() {
            field._state = 'open';
            field.toggleState('open');
            expect(field._state).toEqual('open');
        });

        it('should stay close if called with close', function() {
            field._state = 'close';
            field.toggleState('close');
            expect(field._state).toEqual('close');
        });

        it('should become open if currently close', function() {
            field._state = 'close';
            field.toggleState();
            expect(field._state).toEqual('open');
        });

        it('should become close if currently open', function() {
            field._state = 'open';
            field.toggleState();
            expect(field._state).toEqual('close');
        });
    });
});
