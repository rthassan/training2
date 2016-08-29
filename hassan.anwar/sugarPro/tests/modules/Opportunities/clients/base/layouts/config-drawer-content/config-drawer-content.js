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
describe('Opportunities.Layout.ConfigDrawerContent', function() {
    var app,
        layout;

    beforeEach(function() {
        app = SugarTest.app;
        layout = SugarTest.createLayout('base', 'Opportunities', 'config-drawer-content', null, null, true);

        sinon.collection.stub(app.metadata, 'getModule', function() {
            return {
                forecast_by: 'RevenueLineItems'
            }
        });

        sinon.collection.stub(app.template, 'getView', function(view) {
            return function() {
                return view;
            };
        });
    });

    afterEach(function() {
        sinon.collection.restore();
        layout = null;
    });

    describe('_initHowTo()', function() {
        it('should set all Opportunities howto text properly', function() {
            layout._initHowTo();
            expect(layout.viewOppsByTitle).toEqual('LBL_OPPS_CONFIG_VIEW_BY_LABEL');
            expect(layout.viewOppsByOppsTpl).toEqual('help-dashlet');
            expect(layout.viewOppsByRLIsTpl).toEqual('help-dashlet');
        });
    });

    describe('_switchHowToData()', function() {
        beforeEach(function() {
            layout._initHowTo();
            layout.currentHowToData = {};
        });
        it('should set currentHowToData properly for OppsViewBy', function() {
            layout._switchHowToData('config-opps-view-by');
            expect(layout.viewOppsByOppsTpl).toEqual('help-dashlet');
            expect(layout.viewOppsByRLIsTpl).toEqual('help-dashlet');
        });
    });

    describe('_getText()', function() {
        beforeEach(function() {
            layout.viewOppsByOppsTpl = 'oppsTpl'
            layout.viewOppsByRLIsTpl = 'rlisTpl';
        });

        it('should get OppsTpl when OppsViewBy == Opportunities', function() {
            expect(layout._getText('Opportunities')).toEqual('oppsTpl');
        });

        it('should get RLIsTpl when OppsViewBy == RevenueLineItems', function() {
            expect(layout._getText('RevenueLineItems')).toEqual('rlisTpl');
        });
    });
});
