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

describe("ForecastManagerWorksheets.View.RecordList", function() {

    var app, view, layout, moduleName = 'ForecastManagerWorksheets';

    beforeEach(function() {
        app = SUGAR.App;
        SugarTest.testMetadata.init();
        SugarTest.loadFile("../include/javascript/sugar7", "utils", "js", function(d) {
            app.events.off('app:init');
            eval(d);
            app.events.trigger('app:init');
        });
        SugarTest.loadFile('../include/javascript/sugar7/plugins', 'DirtyCollection', 'js', function(d) {
            app.events.off('app:init');
            eval(d);
            app.events.trigger('app:init');
        });

        app.user.set({'id': 'test_userid', full_name: 'Selected User'});

        SugarTest.loadComponent('base', 'view', 'list');
        SugarTest.loadComponent('base', 'view', 'flex-list');
        SugarTest.loadComponent('base', 'view', 'recordlist');
        SugarTest.loadComponent('base', 'view', 'recordlist', moduleName);
        SugarTest.testMetadata.addViewDefinition("list", {
            "favorite": false,
            "selection": {
                "type": "multi",
                "actions": []
            },
            "rowactions": {
                "actions": []
            },
            "panels": [
                {
                    "name": "panel_header",
                    "header": true,
                    "fields": ["name", "quota", "likely_case", "likely_case_adjusted", "best_case", "best_case_adjusted", "worst_case", "worst_case_adjusted"]
                }
            ]
        }, moduleName);
        SugarTest.testMetadata.set();

        app.data.reset();
        app.data.declareModel(moduleName, SugarTest.app.metadata.getModule(moduleName));

        context = app.context.getContext();
        context.set({
            module: moduleName,
            'selectedUser': app.user.toJSON(),
            'selectedRanges': [],
            'selectedTimePeriod': 'test_timeperiod'
        });
        context.parent = undefined;
        context.prepare();

        layout = SugarTest.createLayout("base", moduleName, "list", null, null);
        view = SugarTest.createView("base", moduleName, "recordlist", null, context, true, layout, true);
    });

    afterEach(function() {
        app.user.unset('id');
        view = null;
        app = null;
    });

    it("should have additional plugins defined", function() {
        expect(_.indexOf(view.plugins, 'CteTabbing')).not.toEqual(-1);
        expect(_.indexOf(view.plugins, 'DirtyCollection')).not.toEqual(-1);
    });

    it('should not have ReorderableColumns plugin', function() {
        expect(_.indexOf(view.plugins, 'ReorderableColumns')).toEqual(-1);
    });
    it('should not have MassCollection plugin', function() {
        expect(_.indexOf(view.plugins, 'MassCollection')).toEqual(-1);
    });

    describe('beforeRenderCallback', function() {
        describe('when layout hidden', function() {
            var layoutVisibleStub;
            beforeEach(function() {
                layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                    return false;
                });
            });
            afterEach(function() {
                layoutVisibleStub.restore();
            });

            it('should return true when user is manager', function() {
                view.selectedUser.is_manager = true;
                var ret = view.beforeRenderCallback();
                expect(ret).toBeTruthy();
            });
            it('should return true when user is manager and showOpps is true and call show', function() {
                view.selectedUser.is_manager = true;
                view.selectedUser.showOpps = true;
                var ret = view.beforeRenderCallback();
                expect(ret).toBeFalsy();
            });
            it('should return false when user is not a manager', function() {
                view.selectedUser.is_manager = false;
                var ret = view.beforeRenderCallback();
                expect(ret).toBeFalsy();
            });
        });

        describe('when layout visible', function() {
            var layoutHideStub, layoutVisibleStub;
            beforeEach(function() {
                layoutHideStub = sinon.stub(view.layout, 'hide', function() {
                });
                layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                    return true;
                });
            });
            afterEach(function() {
                layoutHideStub.restore();
                layoutVisibleStub.restore();
            });

            it('should return false when user is manager and showOpps is true', function() {
                view.selectedUser.is_manager = true;
                view.selectedUser.showOpps = true;
                var ret = view.beforeRenderCallback();
                expect(ret).toBeFalsy();
                expect(layoutHideStub).toHaveBeenCalled();
            });
            it('should return false when user is not a manager', function() {
                view.selectedUser.is_manager = false;
                view.selectedUser.showOpps = false;
                var ret = view.beforeRenderCallback();
                expect(ret).toBeFalsy();
                expect(layoutHideStub).toHaveBeenCalled();
            });
        });
    });

    describe('renderCallback', function() {
        var layoutShowStub, layoutHideStub, layoutHideStub, setCommitLogButtonStatesStub;
        beforeEach(function() {
            layoutShowStub = sinon.stub(view.layout, 'show', function() {
            });
            layoutHideStub = sinon.stub(view.layout, 'hide', function() {
            });
            setCommitLogButtonStatesStub = sinon.stub(view, 'setCommitLogButtonStates', function() {
            });
        });
        afterEach(function() {
            layoutShowStub.restore();
            layoutHideStub.restore();
            layoutVisibleStub.restore();
            setCommitLogButtonStatesStub.restore();
        });

        it('should not run hide when user is a manager and show opps is false', function() {
            layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                return true;
            });

            view.selectedUser.is_manager = true;
            view.selectedUser.showOpps = false;
            view.renderCallback();

            expect(layoutHideStub).not.toHaveBeenCalled();
        });

        it('should run show when user is a manager and show opps is false', function() {
            layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                return false;
            });

            tplViewStub = sinon.stub(app.template, 'getView', function() {
                return function() {
                };
            });

            view.selectedUser.is_manager = true;
            view.selectedUser.showOpps = false;
            view.renderCallback();

            expect(layoutShowStub).toHaveBeenCalled();
            expect(layoutHideStub).not.toHaveBeenCalled();

            tplViewStub.restore();
        });
    });

    describe("parseFields should hide best and worst case", function() {
        beforeEach(function() {
            app.metadata.getModule('Forecasts', 'config').show_worksheet_best = 0;
            app.metadata.getModule('Forecasts', 'config').show_worksheet_worst = 0;
        });

        afterEach(function() {
            app.metadata.getModule('Forecasts', 'config').show_worksheet_best = 1;
            app.metadata.getModule('Forecasts', 'config').show_worksheet_worst = 1;
        });

        it("length of visible fields should equal 4", function() {
            fields = view.parseFields();
            expect(fields.visible.length).toEqual(4);
        });

        it("should return _byId as an Object not an Array", function() {
            fields = view.parseFields();
            var isObject = (!_.isArray(fields._byId));
            expect(isObject).toBeTruthy()
        });
    });

    describe("checkForDraftRows", function() {
        var layoutStub, ctxStub;
        beforeEach(function() {
            // add some models
            var m1 = new Backbone.Model({'name': 'test1', 'date_modified': '2013-05-14 16:20:15'});
            view.collection.add([m1]);

            // set that we can edit
            view.canEdit = true;
            layoutStub = sinon.stub(view.layout, 'isVisible', function() {
                return true;
            });

            context = app.context.getContext();
            context.set({
                module: 'Forecasts'
            });
            context.prepare();

            ctxStub = sinon.stub(context, 'trigger', function() {
            });

            view.context.parent = context;
        });
        afterEach(function() {
            view.collection.reset();
            layoutStub.restore();
            view.context.parent = undefined;
        });

        it("should not trigger event", function() {
            view.checkForDraftRows('2013-05-14 16:21:15');
            expect(ctxStub).not.toHaveBeenCalled();
        });

        it("should trigger event", function() {
            view.checkForDraftRows('2013-05-14 16:19:15');
            expect(ctxStub).toHaveBeenCalled();
        });

    });

    describe('updateSelectedUser', function() {
        var collectionFetchStub;
        beforeEach(function() {
            collectionFetchStub = sinon.stub(view.collection, 'fetch', function() {
            });
        });
        afterEach(function() {
            collectionFetchStub.restore()
            view.canEdit = false;
        });
        it("should change canEdit to be true", function() {
            view.updateSelectedUser({id: 'test_userid'});
            expect(view.canEdit).toBeTruthy();
        });
        it("should change canEdit to be false", function() {
            view.updateSelectedUser({id: 'test_user2'});
            expect(view.canEdit).toBeFalsy();
        });
        it("should call collection.fetch() is_manager is False", function() {
            view.updateSelectedUser({id: 'test_user2', is_manager: false});
            expect(collectionFetchStub).toHaveBeenCalled();
        });
        it("should call collection.fetch() with is_manager is True and showOpps is True", function() {
            view.updateSelectedUser({id: 'test_userid', is_manager: true, showOpps: true});
            expect(collectionFetchStub).toHaveBeenCalled();
        });
    });

    describe('updateTimeperiod', function() {
        var collectionFetchStub, layoutVisibleStub;

        beforeEach(function() {
            collectionFetchStub = sinon.stub(view.collection, 'fetch', function() {
            });
        });
        afterEach(function() {
            collectionFetchStub.restore()
            layoutVisibleStub.restore()
        });

        it('should update selectedTimePeriod and call collection.fetch when layout is visible', function() {
            layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                return true;
            });
            view.updateSelectedTimeperiod({id: 'hello world'});

            expect(view.selectedTimeperiod).toEqual({id: 'hello world'});
            expect(collectionFetchStub).toHaveBeenCalled();
        });

        it('should update selectedTimePeriod and not call collection.fetch when layout is not visible', function() {
            layoutVisibleStub = sinon.stub(view.layout, 'isVisible', function() {
                return false;
            });
            view.updateSelectedTimeperiod({id: 'hello world'});

            expect(view.selectedTimeperiod).toEqual({id: 'hello world'});
            expect(collectionFetchStub).not.toHaveBeenCalled();
        })


    });

    describe('saveWorksheet', function() {
        var m, saveStub;
        beforeEach(function() {
            m = new Backbone.Model({'hello': 'world'});
            saveStub = sinon.stub(m, 'save', function() {
            });
            view.collection.add(m);
        });

        afterEach(function() {
            view.collection.reset();
            saveStub.restore();
            m = undefined;
        });

        it('should return zero with no dirty models', function() {
            expect(view.saveWorksheet()).toEqual(0);
        });

        it('should return 1 when one model is dirty', function() {
            m.set({'hello': 'jon1'});
            expect(view.saveWorksheet()).toEqual(1);
            expect(saveStub).toHaveBeenCalled();
        });

        describe("Forecasts worksheet save dirty models with correct timeperiod after timeperiod changes", function() {
            var m, saveStub, safeFetchStub;
            beforeEach(function() {
                m = new Backbone.Model({'hello': 'world'});
                saveStub = sinon.stub(m, 'save', function() {
                });
                safeFetchStub = sinon.stub(view.collection, 'fetch', function() {
                });
                view.collection.add(m);
            });

            afterEach(function() {
                view.collection.reset();
                saveStub.restore();
                safeFetchStub.restore();
                m = undefined;
            });

            it('model should contain the old timeperiod id', function() {
                m.set({'hello': 'jon1'});
                view.updateSelectedTimeperiod('my_new_timeperiod');
                expect(view.saveWorksheet()).toEqual(1);
                expect(saveStub).toHaveBeenCalled();
                expect(safeFetchStub).toHaveBeenCalled();

                expect(m.get('timeperiod_id')).toEqual('test_timeperiod');
                expect(view.selectedTimeperiod).toEqual('my_new_timeperiod');
                expect(view.dirtyTimeperiod).toEqual(undefined);
            });
        });
    });

    describe("collectionSuccess", function() {
        var collectionResetStub, models = [], sortedModels = [];
        beforeEach(function() {
            collectionResetStub = sinon.stub(view.collection, 'reset', function(models) {
                sortedModels = models;
            });
            view.selectedUser.reportees = [
                {id: 'test1', name: 'Test One'},
                {id: 'asdf', name: 'AS DF'},
                {id: 'ghkl', name: 'GH KL'}
            ];
            models = [
                {user_id: 'test1', best_case: '1234'},
                {user_id: 'asdf', best_case: '5678'},
                {user_id: 'ghkl', best_case: '854'}
            ];
        });
        afterEach(function() {
            collectionResetStub.restore();
            view.selectedUser.reportees = []
        });

        it("should have 4 rows after run", function(){
            view.collectionSuccess(models);
            expect(sortedModels.length).toEqual(4);
        });

        it("first row should be selected user when sorting is not applied", function() {
            view.collectionSuccess(models);
            expect(sortedModels[0].user_id).toEqual(view.selectedUser.id);
        });

        it("selectedUser should contain default values since no model was found", function() {
            view.collectionSuccess(models);
            _.each(view.defaultValues, function(value, key) {
                expect(sortedModels[0][key]).toEqual(value);
            });
        })

        describe("should sort", function() {
            describe("desc correctly", function() {
                afterEach(function() {
                    view.orderBy = undefined;
                    sortedModels = [];
                });
                it("currency_field", function() {
                    view.orderBy = {field: 'best_case', direction: 'desc'};
                    view.collectionSuccess(models);

                    expect(sortedModels[0].best_case).toEqual('5678');
                    expect(sortedModels[1].best_case).toEqual('1234');
                    expect(sortedModels[2].best_case).toEqual('854');
                    expect(sortedModels[3].best_case).toEqual('0');
                });
                it("name_field", function() {
                    view.orderBy = {field: 'name', direction: 'desc'};
                    view.collectionSuccess(models);

                    expect(sortedModels[0].name).toEqual('AS DF');
                    expect(sortedModels[1].name).toEqual('GH KL');
                    expect(sortedModels[2].name).toEqual('Selected User');
                    expect(sortedModels[3].name).toEqual('Test One');
                });
            });

            describe("asc correctly", function() {
                afterEach(function() {
                    view.orderBy = undefined;
                    sortedModels = [];
                });
                it("currency_field", function() {
                    view.orderBy = {field: 'best_case', direction: 'asc'};
                    view.collectionSuccess(models);

                    expect(sortedModels[3].best_case).toEqual('5678');
                    expect(sortedModels[2].best_case).toEqual('1234');
                    expect(sortedModels[1].best_case).toEqual('854');
                    expect(sortedModels[0].best_case).toEqual('0');
                });
                it("name_field", function() {
                    view.orderBy = {field: 'name', direction: 'asc'};
                    view.collectionSuccess(models);

                    expect(sortedModels[3].name).toEqual('AS DF');
                    expect(sortedModels[2].name).toEqual('GH KL');
                    expect(sortedModels[1].name).toEqual('Selected User');
                    expect(sortedModels[0].name).toEqual('Test One');
                });
            });
        });
    });

    describe('refreshData', function() {
        var sbox = sinon.sandbox.create();
        beforeEach(function() {
            sbox.stub(view, 'displayLoadingMessage', function() {
                return true;
            });
            
            sbox.stub(view.collection, 'fetch');
            view.refreshData();
        });

        afterEach(function() {
            sbox.restore();
        });
        it("should have called displayLoadingMessage", function() {
            expect(view.displayLoadingMessage).toHaveBeenCalled();
        });
        it("should should have called fetch on the collection", function() {
            expect(view.collection.fetch).toHaveBeenCalled();
        });
    });

    describe('calculateTotals', function() {
        beforeEach(function() {
            sinon.stub(view, 'getCommitTotals', function() {
                return {
                    'likely_case' : 100,
                    'likely_adjusted' : 150
                }
            });
            sinon.stub(view.context, 'trigger', function() {});
        });

        afterEach(function() {
            view.getCommitTotals.restore();
            view.context.trigger.restore();
        });

        it('should have likely_case_display', function() {
            view.calculateTotals();
            expect(view.totals.likely_case_display).toBeDefined();
        });

        it('should not have worst_case_display', function() {
            delete view._fields.visible[6];
            delete view._fields.visible[7];
            view.calculateTotals();
            expect(view.totals.worst_case_display).not.toBeDefined();
        });
    });

    describe('exportCallback', function() {
        var sandbox = sinon.sandbox.create();
        beforeEach(function() {
            sandbox.stub(view, 'doExport', function() {
            });
            sandbox.stub(app.alert, 'show', function() {
            });
        });

        afterEach(function() {
            sandbox.restore();
            view.canEdit = true;
        });

        it('when is dirty and can edit should show alert', function() {
            sandbox.stub(view, 'isDirty', function() {
                return true;
            });

            view.canEdit = true;

            view.exportCallback();

            expect(view.doExport).not.toHaveBeenCalled();
            expect(app.alert.show).toHaveBeenCalled();
        });

        it('when is not dirty and cant edit', function() {
            sandbox.stub(view, 'isDirty', function() {
                return false;
            });

            view.canEdit = false;

            view.exportCallback();

            expect(view.doExport).toHaveBeenCalled();
            expect(app.alert.show).not.toHaveBeenCalled();
        });
    });
});
