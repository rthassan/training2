describe('Base.Fields.Pdfaction', function() {
    var app;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('pdfaction', 'field', 'base', 'detail');
        SugarTest.testMetadata.set();

        var stubAppDataCreateBeanCollection = sinon.collection.stub(app.data, 'createBeanCollection');
        stubAppDataCreateBeanCollection.withArgs('PdfManager').returns(new Backbone.Collection([{"name": "pdfaction"}]));

        sinon.collection.stub(Backbone.Collection.prototype, 'fetch');
    });

    afterEach(function() {
        sinon.collection.restore();
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
    });

    describe('download button', function() {
        var download;

        beforeEach(function() {
            download = SugarTest.createField('base', 'download-pdf', 'pdfaction', 'detail', {
                label: 'LBL_PDF_VIEW',
                action: 'download',
                acl_action: 'view'
            });
        });

        afterEach(function() {
            download.dispose();
        });

        it('should render a download button', function() {
            download.render();
            expect(download.$el.hasClass('hide')).toBe(false);
        });
    });

    describe('email button', function() {
        var email;

        beforeEach(function() {
            email = SugarTest.createField('base', 'email-pdf', 'pdfaction', 'detail', {
                label: 'LBL_PDF_EMAIL',
                action: 'email',
                acl_action: 'view'
            });
        });

        afterEach(function() {
            email.dispose();
        });

        it('should render an email button when the user can use the sugar email client', function() {
            var stubAppUserGetPreference = sinon.collection.stub(app.user, 'getPreference');
            stubAppUserGetPreference.withArgs('email_client_preference').returns({type:'sugar'});
            email.render();
            expect(email.$el.hasClass('hide')).toBe(false);
        });

        it('should not render an email button when the user cannot use the sugar email client', function() {
            var stubAppUserGetPreference = sinon.collection.stub(app.user, 'getPreference');
            stubAppUserGetPreference.withArgs('email_client_preference').returns({type:'mailto'});
            email.render();
            expect(email.$el.hasClass('hide')).toBe(true);
        });
    });

    describe('downloadClicked', function() {

        it('should authenticate in bwc mode before triggering the download', function() {
            var download = SugarTest.createField('base', 'download-pdf', 'pdfaction', 'detail', {
                label: 'LBL_PDF_VIEW',
                action: 'download',
                acl_action: 'view'
            });
            var loginSpy = sinon.collection.spy(app.bwc, 'login');
            var fileDownloadStub = sinon.collection.stub(app.api, 'fileDownload');
            sinon.collection.stub(app.api, 'call', function(method, url, data, callbacks, options) {
                callbacks.success();
            });
            download.downloadClicked({});

            expect(loginSpy).toHaveBeenCalled();
            expect(fileDownloadStub).toHaveBeenCalled();
        });
    });
});
