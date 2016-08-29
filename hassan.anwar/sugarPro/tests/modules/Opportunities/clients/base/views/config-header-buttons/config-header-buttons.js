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
describe('Opportunities.View.ConfigHeaderButtons', function() {
    var app,
        view,
        options,
        meta,
        context;

    beforeEach(function() {
        app = SugarTest.app;
        context = app.context.getContext();

        var cfgModel = new Backbone.Model({
            opps_view_by: 'Opportunities'
        });

        context.set({
            model: cfgModel,
            module: 'Opportunities'
        });

        meta = {
            label: 'testLabel',
            panels: [{
                fields: []
            }]
        };

        options = {
            meta: meta,
            context: context
        };

        sinon.collection.stub(app.metadata, 'getModule', function() {
            return {
                is_setup: false
            }
        });

        sinon.collection.stub(app.template, 'getView', function(view) {
            return function() {
                return view;
            };
        });

        // load the parent config-panel view
        SugarTest.loadComponent('base', 'view', 'config-header-buttons');
        view = SugarTest.createView('base', 'Opportunities', 'config-header-buttons', meta, null, true);
    });

    afterEach(function() {
        sinon.collection.restore();
        view = null;
    });

    describe('initialize()', function() {
        beforeEach(function() {
            sinon.collection.stub(Object, 'defineProperty').returns();
        });
        it('should set currentOppsViewBySetting', function() {
            view.initialize(options);
            expect(view.currentOppsViewBySetting).toEqual('Opportunities');
        });

        it('should set isForecastsSetup', function() {
            view.initialize(options);
            expect(view.isForecastsSetup).toBeFalsy();
        });
    });

    describe('_beforeSaveConfig', function() {
        beforeEach(function() {
            sinon.collection.stub(app.alert, 'show');
        });

        it('should call app.alert.show', function() {
            view._beforeSaveConfig();
            expect(app.alert.show).toHaveBeenCalledWith('opp.config.save');
        });
    });

    describe('showSavedConfirmation', function() {
        beforeEach(function() {
            sinon.collection.stub(app.alert, 'dismiss');
        });

        it('should call app.alert.dismiss', function() {
            view.showSavedConfirmation();
            expect(app.alert.dismiss).toHaveBeenCalledWith('opp.config.save');
        });
    });

    describe('displayWarningAlert', function() {
        beforeEach(function() {
            sinon.collection.stub(app.alert, 'show');
        });

        it('should call app.alert.show', function() {
            view.displayWarningAlert();
            expect(app.alert.show).toHaveBeenCalledWith('forecast-warning');
        });
    });

    describe('saveConfig()', function() {
        beforeEach(function() {
            sinon.collection.stub(view, '_super');
            view.model.set('opps_view_by', 'Opportunities');
        });
        it('should call displayWarningAlert on Save if isForecastsSetup and change is different than current', function() {
            sinon.collection.stub(view, 'displayWarningAlert', function() {});
            view.isForecastsSetup = true;
            view.currentOppsViewBySetting = 'RevenueLineItems';
            view.saveConfig();
            expect(view.displayWarningAlert).toHaveBeenCalled();
            expect(view._super).not.toHaveBeenCalledWith('saveConfig');
        });

        it('should not call displayWarningAlert on Save if isForecastsSetup == false', function() {
            sinon.collection.stub(view, 'displayWarningAlert', function() {});
            view.isForecastsSetup = false;
            view.currentOppsViewBySetting = 'Opportunities';
            view.saveConfig();
            expect(view.displayWarningAlert).not.toHaveBeenCalled();
            expect(view._super).toHaveBeenCalledWith('saveConfig');
        });

        it('should not call displayWarningAlert on Save if changing to the same as current', function() {
            sinon.collection.stub(view, 'displayWarningAlert', function() {});
            view.isForecastsSetup = true;
            view.currentOppsViewBySetting = 'Opportunities';
            view.saveConfig();
            expect(view.displayWarningAlert).not.toHaveBeenCalled();
            expect(view._super).toHaveBeenCalledWith('saveConfig');
        });
    });
});
