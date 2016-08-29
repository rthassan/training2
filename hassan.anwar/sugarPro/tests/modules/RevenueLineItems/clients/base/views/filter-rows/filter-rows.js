/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

describe('RevenueLineItems.Base.Views.FilterRows', function() {
    var app, view, options, sandbox;

    beforeEach(function() {
        app = SugarTest.app;

        sandbox = sinon.sandbox.create();

        options = {
            meta: {
                panels: [
                    {
                        fields: [
                            {
                                name: "commit_stage"
                            },
                            {
                                name: "best_case"
                            },
                            {
                                name: "likely_case"
                            },
                            {
                                name: "name"
                            }
                        ]
                    }
                ]
            }
        };

        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', 'filter-rows');
        SugarTest.testMetadata.set();

        SugarTest.seedMetadata(true, './fixtures');
        app.metadata.getModule("Forecasts", "config").is_setup = 1;

        sandbox.stub(app.view.views.BaseFilterRowsView.prototype, 'getFilterableFields', function() {
            return {
                'name': [],
                'commit_stage': [],
                'best_case': [],
                'likely_case': []
            }
        });
        sandbox.stub(app.view.views.BaseFilterRowsView.prototype, 'initialize', function() {
        });

        view = SugarTest.createView('base', 'RevenueLineItems', 'filter-rows', options.meta, null, true);
    });

    afterEach(function() {
        sandbox.restore();
        app.metadata.getModule("Forecasts", "config").is_setup = null;
        app.metadata.getModule("Forecasts", "config").show_worksheet_best = null;
        app = null;
        view = null;
    });

    describe('getFilterableFields', function() {
        it('should delete commit_stage if forecast is not setup', function() {
            app.metadata.getModule("Forecasts", "config").is_setup = 0;
            var fields = view.getFilterableFields('test');
            expect(fields['commit_stage']).toBeUndefined();
        });
        it('should not delete commit_stage if forecast is setup', function() {
            app.metadata.getModule("Forecasts", "config").is_setup = 1;
            var fields = view.getFilterableFields('test');
            expect(fields['commit_stage']).toBeDefined();
        });

        it('should delete base_case', function() {
            app.metadata.getModule("Forecasts", "config").show_worksheet_best = 0;
            var fields = view.getFilterableFields('test');
            expect(fields['best_case']).toBeUndefined();
        });
        it('should not delete base_case', function() {
            app.metadata.getModule("Forecasts", "config").show_worksheet_best = 1;
            var fields = view.getFilterableFields('test');
            expect(fields['best_case']).toBeDefined();
        });
    });

});
