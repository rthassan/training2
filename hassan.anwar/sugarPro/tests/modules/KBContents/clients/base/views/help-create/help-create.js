describe('KBContents.Base.Views.HelpCreate', function() {

    var app, view, sandbox, context, moduleName = 'KBContents';

    beforeEach(function() {
        app = SugarTest.app;
        sandbox = sinon.sandbox.create();
        context = app.context.getContext({
            module: moduleName
        });
        SugarTest.loadComponent('base', 'view', 'help-create', moduleName);
        SugarTest.loadHandlebarsTemplate(
            'help-create',
            'view',
            'base',
            null,
            moduleName
        );
        view = SugarTest.createView(
            'base',
            moduleName,
            'help-create',
            null,
            context,
            moduleName
        );
    });

    afterEach(function() {
        sandbox.restore();
        app.cache.cutAll();
        app.view.reset();
        view.dispose();
        Handlebars.templates = {};
        view = null;
    });

    describe('Create more help link', function() {

        var getServerInfoStub, getLanguageStub;

        beforeEach(function() {
            getServerInfoStub = sandbox.stub(app.metadata,
                'getServerInfo',
                function() {
                    return {
                        flavor: 'TEST_FLAVOR',
                        version: 'TEST_VERSION'
                    };
                }
            );
            getLanguageStub = sandbox.stub(app.lang,
                'getLanguage',
                function() {
                    return 'TEST_LANGUAGE';
                }
            );
        });

        it('The more help link: getServerInfo called', function() {
            view.createMoreHelpLink();
            expect(getServerInfoStub).toHaveBeenCalled();
        });

        it('The more help link: getLanguage called', function() {
            view.createMoreHelpLink();
            expect(getLanguageStub).toHaveBeenCalled();
        });

        it('The more help link to be defined', function() {
            var link = view.createMoreHelpLink();
            expect(link).toBeDefined();
        });

        it('The more help link has edition', function() {
            var link = view.createMoreHelpLink();
            expect(link).toMatch('edition=TEST_FLAVOR');
        });

        it('The more help link has version', function() {
            var link = view.createMoreHelpLink();
            expect(link).toMatch('version=TEST_VERSION');
        });

        it('The more help link has lang', function() {
            var link = view.createMoreHelpLink();
            expect(link).toMatch('lang=TEST_LANG');
        });

        it('The more help link has route', function() {
            var link = view.createMoreHelpLink();
            expect(link).toMatch('route=create');
        });
    });

    describe('Create more help link', function() {
        var createMoreHelpLinkStub, helpGetStub;
        var url = 'http://www.sugarcrm.com/crm/product_doc.php?' +
            'edition=TEST_FLAVOR&version=TEST_VERSION' +
            '&lang=TEST_LANGUAGE&module=undefined&route=create';

        beforeEach(function() {
            createMoreHelpLinkStub = sandbox.stub(view,
                'createMoreHelpLink',
                function() {
                    return '<a href="' + url + '" target="_blank">';
                }
            );
            helpGetStub = sandbox.stub(app.help,
                'get',
                function(module, action, helpUrl) {
                    var more_info_url = helpUrl.more_info_url +
                        'TEST_URL' + helpUrl.more_info_url_close;

                    return {
                        module: module,
                        action: action,
                        more_info: more_info_url
                    };
                }
            );
        });

        it('should call help.get when view render', function() {
            view.render();
            expect(helpGetStub).toHaveBeenCalledWith(moduleName, 'create', {
                more_info_url: '<a href="' + url + '" target="_blank">',
                more_info_url_close: '</a>'
            });
        });

        it('should call createMoreHelpLink when view render', function() {
            view.render();
            expect(createMoreHelpLinkStub).toHaveBeenCalled();
        });
    });
});
