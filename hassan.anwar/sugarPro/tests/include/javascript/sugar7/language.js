describe('Sugar7 Language', function() {
    var app;

    beforeEach(function() {
        SugarTest.seedMetadata(true);
        app = SugarTest.app;
    });

    afterEach(function() {
        sinon.collection.restore();
    });

    describe('direction change', function() {
        it('should toggle rtl class based on language direction', function() {
            sinon.collection.stub(app.lang, 'setLanguage', function(lang) {
                app.lang.direction = lang === 'he_IL' ? 'rtl' : 'ltr';
                app.events.trigger('lang:direction:change');
            });
            app.lang.setLanguage('en_us');
            expect($('html').hasClass('rtl')).toBeFalsy();

            //Only enable the rtl class when the direction is `rtl`
            app.lang.setLanguage('he_IL');
            expect($('html').hasClass('rtl')).toBeTruthy();

            $('html').removeClass('rtl');
        });
    });

    describe('moduleIconLabel', function() {
        using('different values', [
            {
                // Exists in app_list_strings['moduleIconList']
                module: 'Accounts',
                expected: 'Ac'
            },
            {
                // Doesn't exist in app_list_strings['moduleIconList']
                // Has LBL_MODULE_NAME_SINGULAR defined.
                module: 'Contacts',
                expected: 'Co'
            },
            {
                // Doesn't exist in app_list_strings['moduleIconList']
                // Doesn't have LBL_MODULE_NAME_SINGULAR defined.
                // Has LBL_MODULE_NAME defined.
                module: 'Leads',
                expected: 'Le'
            },
            {
                // Doesn't exist in app_list_strings['moduleIconList']
                // Has LBL_MODULE_NAME_SINGULAR defined.
                // Is a multi-word module.
                // Note: Product Templates maps to Product Catalog, hence 'PC'.
                module: 'ProductTemplates',
                expected: 'PC'
            },
            {
                // Doesn't exist in app_list_strings['moduleIconList']
                // Has no LBL_MODULE_NAME labels defined in mod strings.
                module: 'FakeModule',
                expected: 'Fa'
            }
        ], function(options) {
            it('should return an appropriate 2-letter icon label', function() {
                expect(app.lang.getModuleIconLabel(options.module)).toBe(options.expected);
            });
        });
    });
});
