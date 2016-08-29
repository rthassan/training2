describe('Portal.Field.Html', function() {
	var app, field, _oConfigSiteUrl;

    beforeEach(function() {
        app = SugarTest.app;
        _oConfigSiteUrl = app.config.siteUrl;
        app.config.siteUrl = '/the/site/url';
        SugarTest.loadComponent('portal', 'field', 'html');
    });

    afterEach(function() {
        app.config.siteUrl = _oConfigSiteUrl;
        app.cache.cutAll();
        app.view.reset();
    });

    describe('format', function() {

        beforeEach(function() {
            field = SugarTest.createField('portal', 'testfield', 'html', 'detail', {});
        });

        it("should prepend the site url to the embeded images", function() {
            var inputValue = '<img src="path/to/local/filename.jpg"><img src="path/to/local/filename1.jpg">';
            var expectedValue = '<img src="' + app.config.siteUrl + '/path/to/local/filename.jpg"><img src="' + app.config.siteUrl + '/path/to/local/filename1.jpg">';
            var formattedValue = field.format(inputValue);
            expect(formattedValue).toEqual(expectedValue);
        });

        it("should not prepend the site url to the external images", function() {
            var externalLink = '<img src="http://example.com/external/filename.jpg">';
            var formattedValue = field.format(externalLink);
            expect(formattedValue).toEqual(externalLink);
        });

        it('Should not prepend the HTTPS site url to the external images.', function() {
            var secureExternalLink = '<img src="https://example.com/external/filename.jpg">';
            var formattedValue = field.format(secureExternalLink);
            expect(formattedValue).toEqual(secureExternalLink);
        });
    });

});
