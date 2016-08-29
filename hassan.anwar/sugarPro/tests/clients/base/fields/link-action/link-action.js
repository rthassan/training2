describe('View.Fields.LinkAction', function() {

    var app, field, sandbox, relatedFields, moduleName = 'Contacts';

    beforeEach(function() {
        app = SugarTest.app;

        SugarTest.loadComponent('base', 'field', 'button');
        SugarTest.loadComponent('base', 'field', 'rowaction');
        SugarTest.loadComponent('base', 'field', 'sticky-rowaction');
        SugarTest.loadComponent('base', 'field', 'link-action');
        field = SugarTest.createField("base", "link-action", "link-action", "edit", {
            'type':'rowaction',
            'tooltip':'Link'
        }, moduleName);

        sandbox = sinon.sandbox.create();
        sandbox.stub(app.data, "getRelateFields", function(){
            return relatedFields;
        });
        relatedFields = [{required: false}];
    });

    afterEach(function() {
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        field = null;
        sandbox.restore();
    });

    it('should disable action if the user does not have access', function() {
        field.model = app.data.createBean(moduleName);
        var aclStub = sinon.stub(app.acl, "hasAccessToModel", function() {
            return false;
        });
        field.render();
        expect(field.def.css_class).toEqual("disabled");
        aclStub.restore();
    });

    it('should disable action if any related field is required', function() {
        field.model = app.data.createBean(moduleName);
        relatedFields = [{required: true}];
        field.render();
        expect(field.def.css_class).toEqual("disabled");
        delete field.def.css_class; //cleanup

        relatedFields = [{required: false}, {required: true}];
        field.render();
        expect(field.def.css_class).toEqual("disabled");
        delete field.def.css_class; //cleanup

        relatedFields = [{required: false}, {required: false}];
        field.render();
        expect(field.def.css_class).toBeUndefined();
    });

});
