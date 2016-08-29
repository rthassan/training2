describe("Base.View.FlexList", function () {
    var view, layout, app;

    beforeEach(function () {
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', 'list');
        SugarTest.loadComponent('base', 'view', 'flex-list');
        SugarTest.testMetadata.addViewDefinition("list", {
            "panels":[
                {
                    "name":"panel_header",
                    "header":true,
                    "fields":["name", "case_number", "type", "created_by", "date_entered", "date_modified", "modified_user_id"]
                }
            ],
            last_state: {
                id: 'record-list'
            }
        }, "Cases");
        SugarTest.testMetadata.set();
        view = SugarTest.createView("base", "Cases", "flex-list", null, null);
        layout = SugarTest.createLayout('base', "Cases", "list", null, null);
        view.layout = layout;
        app = SUGAR.App;
    });

    afterEach(function () {
        layout.dispose();
        view.dispose();
        sinon.collection.restore();
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        view = null;
    });

    describe('adding actions to list view', function () {

        it('should add single selection', function () {
            view.meta = {
                selection:{
                    type:'single',
                    label:'LBL_LINK_SELECT'
                }
            };
            view.addActions();
            expect(view.leftColumns[0]).toEqual({
                type:'selection',
                name:'Cases_select',
                sortable:false,
                label:view.meta.selection.label
            });
        });

        it('should add multi selection', function () {
            view.meta = {
                selection:{
                    type:'multi',
                    actions:[
                        {
                            name:'edit_button',
                            type:'button'
                        },
                        {
                            name:'delete_button',
                            type:'button'
                        }
                    ]
                },
                rowactions:{
                    'css_class':'pull-right',
                    'actions':[
                        {
                            type:'rowaction',
                            'event':'list:preview:fire'
                        },
                        {
                            type:'rowaction',
                            'event':'list:editrow:fire'
                        },
                        {
                            type:'rowaction',
                            'event':'list:deleterow:fire'
                        }
                    ]
                }
            };

            view.addActions();

            expect(view.leftColumns[0]).toEqual({
                type:'fieldset',
                fields:[
                    {
                        type:'actionmenu',
                        buttons:view.meta.selection.actions,
                        disable_select_all_alert: false
                    }
                ],
                value:false,
                sortable:false
            });
        });

        it('should add multi selection with the select-all feature disabled', function () {
            var expected;
            view.meta = {
                selection: {
                    type: 'multi',
                    actions: [{
                        name: 'edit_button',
                        type: 'button'
                    }],
                    disable_select_all_alert: true
                }
            };
            view.addActions();
            expected = {
                type: 'fieldset',
                fields: [{
                    type: 'actionmenu',
                    buttons: view.meta.selection.actions,
                    disable_select_all_alert: true
                }],
                value: false,
                sortable: false
            };
            expect(view.leftColumns[0]).toEqual(expected);
        });

        it('should add row actions', function () {
            view.meta = {
                rowactions:{
                    'css_class':'pull-right',
                    'actions':[
                        { type:'rowaction', 'event':'list:preview:fire'},
                        { type:'rowaction', 'event':'list:editrow:fire' },
                        { type:'rowaction', 'event':'list:deleterow:fire' }
                    ]
                }
            };

            view.addActions();

            expect(view.rightColumns[0]).toEqual({
                type:'fieldset',
                fields:[
                    {
                        type:'rowactions',
                        label:'',
                        css_class:'pull-right',
                        buttons:view.meta.rowactions.actions
                    }
                ],
                value:false,
                sortable:false
            });
        });
    });


    describe('parseFields', function() {

        beforeEach(function() {
            view.meta.panels = [
                {
                    fields: [
                        { 'name': 'test1', 'default': false },
                        { 'name': 'test2', 'default': false }
                    ]
                },
                {
                    fields: [
                        { 'name': 'test3', 'default': true },
                        { 'name': 'test4', 'default': false }
                    ]
                }
            ];
        });

        it('use default fields as visible when user last state empty', function() {
            view._fields = view.parseFields();

            expect(view._fields.visible).toEqual([
                {
                    'name': 'test3',
                    'default': true,
                    'position': 3,
                    'selected': true
                }
            ]);
            expect(view._fields.all).toEqual([
                {
                    'name': 'test1',
                    'default': false,
                    'position': 1,
                    'selected': false
                },
                {
                    'name': 'test2',
                    'default': false,
                    'position': 2,
                    'selected': false
                },
                {
                    'name': 'test3',
                    'default': true,
                    'position': 3,
                    'selected': true
                },
                {
                    'name': 'test4',
                    'default': false,
                    'position': 4,
                    'selected': false
                }
            ]);
        });

        it('should retrieve the last state of fields stored into cache', function() {
            var lastStateGetStub = sinon.collection.stub(app.user.lastState, 'get', function(key) {
                return ['test2'];
            });
            view._fields = view.parseFields();

            expect(lastStateGetStub).toHaveBeenCalled();
            expect(view._fields.visible).toEqual([
                {
                    'name': 'test2',
                    'default': false,
                    'position': 2,
                    'selected': true
                }
            ]);
        });
    });

    describe('reorderCatalog', function() {
        var catalog, order1, order2;

        beforeEach(function() {
            catalog = {
                '_byId': {
                    'visible1': { 'name': 'visible1', 'selected': true },
                    'visible2': { 'name': 'visible2', 'selected': true },
                    'visible3': { 'name': 'visible3', 'selected': true },
                    'available1': { 'name': 'available1', 'selected': false },
                    'available2': { 'name': 'available2', 'selected': false },
                    'available3': { 'name': 'available3', 'selected': false }
                },
                'visible': [
                    { 'name': 'visible1' },
                    { 'name': 'visible2' },
                    { 'name': 'visible3' }
                ],
                'all': [
                    { 'name': 'visible1', 'selected': true },
                    { 'name': 'visible2', 'selected': true },
                    { 'name': 'visible3', 'selected': true },
                    { 'name': 'available1', 'selected': false },
                    { 'name': 'available2', 'selected': false },
                    { 'name': 'available3', 'selected': false }
                ]
            };
        });

        it('should sort the catalog based on order1', function() {
            var order1 = [
                'visible1',
                'available1',
                'visible3',
                'visible2',
                'available3',
                'available2'
            ];
            var sortedCatalog = view.reorderCatalog(catalog, order1);
            expect(_.pluck(sortedCatalog.all, 'name')).toEqual(order1);
            expect(_.pluck(sortedCatalog.visible, 'name')).toEqual(['visible1', 'visible3', 'visible2']);
        });

        it('should sort the catalog based on order2', function() {
            var order2 = [
                'available3',
                'available2',
                'available1',
                'visible3',
                'visible2',
                'visible1'
            ];
            var sortedCatalog = view.reorderCatalog(catalog, order2);
            expect(_.pluck(sortedCatalog.all, 'name')).toEqual(order2);
            expect(_.pluck(sortedCatalog.visible, 'name')).toEqual(['visible3', 'visible2', 'visible1']);
        });
    });

    describe('saveCurrentState', function() {

        beforeEach(function() {
            view._fields = {
                'visible': [
                    { 'name': 'visible1' },
                    { 'name': 'visible2' },
                    { 'name': 'visible3' }
                ],
                'all': [
                    { 'name': 'visible1', 'selected': true },
                    { 'name': 'visible3', 'selected': true },
                    { 'name': 'available1', 'selected': false },
                    { 'name': 'visible2', 'selected': true },
                    { 'name': 'available2', 'selected': false },
                    { 'name': 'available3', 'selected': false }
                ]
            };
        });

        it('should save all the fields, sorted, and their visible state', function() {
            var lastStateSetStub = sinon.collection.stub(app.user.lastState, 'set');
            var _encodeStub = sinon.collection.stub(view, '_encodeCacheData');
            view.saveCurrentState();
            expect(_encodeStub).toHaveBeenCalled();
            expect(lastStateSetStub).toHaveBeenCalled();
            expect(_encodeStub.firstCall.args[0]).toEqual(
                {
                    visible: ['visible1', 'visible2', 'visible3'],
                    hidden: ['available1', 'available2', 'available3'],
                    position: ['visible1', 'visible3', 'available1', 'visible2', 'available2', 'available3']
                }
            );
        });
    });

    describe('_decodeCacheData', function() {

        beforeEach(function() {
            sinon.collection.stub(view, '_appendFieldsToAllListViewsFieldList', function() {
                return ['field1', 'field2', 'field3', 'field4', 'field5',
                    'field6', 'field7', 'field8', 'field9', 'field10'];
            });
        });

        it('should build a readable object', function() {
            var encoded = [
                0,
                [0, 5],
                [1, 2],
                [0, 1],
                [1, 4],
                0,
                [0, 6],
                0,
                [0, 3]
            ];
            var decoded = view._decodeCacheData(encoded);
            expect(decoded).toEqual({
                visible: ['field3', 'field5'],
                hidden: ['field2', 'field4', 'field7', 'field9'],
                position: ['field4', 'field3', 'field9', 'field5', 'field2', 'field7']
            });
        });
    });

    describe('_encodeCacheData', function() {

        beforeEach(function() {
            sinon.collection.stub(view, '_appendFieldsToAllListViewsFieldList', function() {
                return ['field1', 'field2', 'field3', 'field4', 'field5',
                    'field6', 'field7', 'field8', 'field9', 'field10'];
            });
        });

        it('should build a minimized array', function() {
            var decoded = {
                visible: ['field3', 'field5'],
                hidden: ['field2', 'field4', 'field7', 'field9'],
                position: ['field4', 'field3', 'field9', 'field5', 'field2', 'field7']
            };
            var encoded = view._encodeCacheData(decoded);
            expect(encoded).toEqual([
                0,
                [0, 5],
                [1, 2],
                [0, 1],
                [1, 4],
                0,
                [0, 6],
                0,
                [0, 3],
                0
            ]);
        });
    });

    describe('_convertFromOldFormat', function() {
        var _encodeStub, setLastStateStub;

        beforeEach(function() {
            _encodeStub = sinon.collection.stub(view, '_encodeCacheData');
            setLastStateStub = sinon.collection.stub(app.user.lastState, 'set');
        });

        it('should convert the old cache data to the new format', function() {
            var oldFormat = ['case_number', 'type', 'created_by', 'date_entered'];
            var newFormat = view._convertFromOldFormat(oldFormat);
            expect(newFormat).toEqual({
                visible: ['case_number', 'type', 'created_by', 'date_entered'],
                hidden: ['name', 'date_modified', 'modified_user_id'],
                position: ['name', 'case_number', 'type', 'created_by', 'date_entered', 'date_modified', 'modified_user_id']
            });
            expect(_encodeStub).toHaveBeenCalled();
            expect(setLastStateStub).toHaveBeenCalled();
        });
    });

    describe('_appendFieldsToAllListViewsFieldList', function() {
        var setAllFieldsStub;

        beforeEach(function() {
            sinon.collection.stub(app.user.lastState, 'get', function() {
                return ['created_by', 'date_entered', 'field1', 'name', 'field2'];
            });
            setAllFieldsStub = sinon.collection.stub(app.user.lastState, 'set');
        });

        it('should append missing fields that are defined in the metadata', function() {
            view._appendFieldsToAllListViewsFieldList();
            expect(setAllFieldsStub.firstCall.args[1]).toEqual([
                'created_by', 'date_entered', 'field1', 'name', 'field2',
                'case_number', 'type', 'date_modified', 'modified_user_id'
            ]);
        });
    });
});
