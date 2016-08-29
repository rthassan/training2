describe('Base.Field.Rowaction', function() {

    var app, field, view, moduleName = 'Contacts';

    beforeEach(function() {
        app = SugarTest.app;

        SugarTest.loadComponent('base', 'field', 'button');
        SugarTest.loadComponent('base', 'field', 'rowaction');
        field = SugarTest.createField("base","rowaction", "rowaction", "edit", {
            'type':'rowaction',
            'css_class':'btn',
            'tooltip':'LBL_PREVIEW',
            'event':'list:preview:fire',
            'icon':'fa fa-eye',
            'acl_action':'view'
        }, moduleName);
        field.view = {trigger: function(){}};
        field.layout = {trigger: function(){}};
    });

    afterEach(function() {
        field.view = null;
        field.layout = null;
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        field = null;
    });

    it('should hide action if the user doesn\'t have acls', function() {
        field.model = app.data.createBean(moduleName);
        var aclStub = sinon.stub(app.acl, "hasAccessToModel", function() {
            return false;
        });
        field.render();
        expect(field.isHidden).toBeTruthy();
        aclStub.restore();
    });

    describe('triggering the event', function() {
        var e, sandbox;

        beforeEach(function() {
            e = $.Event('click');
            e.currentTarget = field.$el.get(0);

            field.view.context = {trigger: $.noop};
            sandbox = sinon.sandbox.create();
        });

        afterEach(function() {
            sandbox.restore();
        });

        it('should trigger the event defined in metadata', function() {
            var spy = sandbox.spy(field.view.context, 'trigger');
            field.rowActionSelect(e);
            expect(spy).toHaveBeenCalledWith(field.def.event);
        });

        it('should trigger the event defined in the data-event attribute', function() {
            var spy = sandbox.spy(field.view.context, 'trigger');
            field.def.event = undefined;
            $(e.currentTarget).data('event', 'foo');
            field.rowActionSelect(e);
            expect(spy).toHaveBeenCalledWith('foo');
        });

        it('should not trigger an event', function() {
            var spy = sandbox.spy(field.view.context, 'trigger');
            field.def.event = undefined;
            field.rowActionSelect(e);
            expect(spy).not.toHaveBeenCalled();
        });

        using('event names', [undefined, 'context', 'foo'], function(eventName) {
            it('should return the context as the target on which to trigger the event', function() {
                field.view.context.name = 'context';
                field.def.target = eventName;
                expect(field.getTarget().name).toEqual(field.view.context.name);
            });
        });

        it('should return the view as the target on which to trigger the event', function() {
            field.view.name = 'view';
            field.def.target = 'view';
            expect(field.getTarget().name).toEqual(field.view.name);
        });

        it('should return the layout as the target on which to trigger the event', function() {
            field.view = {layout: {name: 'layout'}};
            field.def.target = 'layout';
            expect(field.getTarget().name).toEqual(field.view.layout.name);
        });
    });
});
