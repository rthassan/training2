describe("PortalFileField", function() {

    var app, field, model, _oConfigSiteUrl;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.loadComponent('base', 'field', 'file');
        SugarTest.loadComponent('portal', 'field', 'file');
        field = SugarTest.createField("portal","testfile", "file", "detail", {});
        model = field.model;

        _oConfigSiteUrl = app.config.siteUrl;
        app.config.siteUrl = '/the/site/url';
    });

    afterEach(function() {
        app.config.siteUrl = _oConfigSiteUrl;
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        model = null;
        field = null;
    });

    describe("file", function() {

        it("should prepend the site url to the uri", function() {
            var inputValue = [
                {name:'filename1.jpg', 'uri': 'path/to/rest'},
                {name:'filename2.jpg', 'uri': 'path/to/rest'},
                {name:'filename3.jpg', 'uri': 'path/to/rest'}
            ];
            var expectedValue = [
                {name:'filename1.jpg', 'url': '/the/site/url/path/to/rest'},
                {name:'filename2.jpg', 'url': '/the/site/url/path/to/rest'},
                {name:'filename3.jpg', 'url': '/the/site/url/path/to/rest'}
            ];
            var formattedValue = field.format(inputValue);
            expect(formattedValue).toEqual(expectedValue);
        });
    });
});
