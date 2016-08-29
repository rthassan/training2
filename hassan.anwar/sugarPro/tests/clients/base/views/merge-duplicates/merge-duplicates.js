describe('View.Views.BaseMergeDuplicatesView', function() {

    var view, layout, app;

    beforeEach(function() {
        var module = 'Contacts',
            context = null;
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', 'merge-duplicates');
        SugarTest.loadComponent('base', 'layout', 'list');
        SugarTest.testMetadata.addViewDefinition('record', {
            panels: [
                {
                    fields: ['test']
                }
            ]
        }, module);
        SugarTest.testMetadata.set();

        layout = SugarTest.createLayout('base', module, 'list');
        layout.context.set({
            selectedDuplicates: [new Backbone.Model(), new Backbone.Model()]
        });
        view = SugarTest.createView('base', module, 'merge-duplicates', null, layout.context, null, layout);
    });

    afterEach(function() {
        layout.dispose();
        app.view.reset();
        SugarTest.testMetadata.dispose();
    });

    using('fields on merge view', [
        {
            field: {
                type: 'datetime',
                source: 'db',
                name: 'customDate'
            },
            expectedResult: true
        },
        {
            field: {
                type: 'datetime',
                source: 'db',
                name: 'date_modified'
            },
            expectedResult: false
        },
        {
            field: {
                type: 'text',
                source: 'non-db',
                name: 'relateText'
            },
            expectedResult: false
        },
        {
            field: null,
            expectedResult: false
        },
        {
            field: {
                type: 'enum',
                source: 'db',
                duplicate_merge: 'disabled'
            },
            expectedResult: false
        },
        {
            field: {
                type: 'int',
                source: 'db',
                name: 'age',
                auto_increment: true
            },
            expectedResult: false
        },
        {
            field: {
                type: 'datetimecombo',
                source: 'db',
                name: 'birth'
            },
            expectedResult: true
        },
        {
            field: {
                type: 'currency',
                source: 'db',
                name: 'amount'
            },
            expectedResult: true
        }
    ], function(data) {
        it('should show as editable on merge view', function() {
            expect(view.validMergeField(data.field)).toBe(data.expectedResult);
        });
    });

    describe('getFieldNames', function() {
        beforeEach(function() {
            view.mergeFields = [
                {
                    type: 'text',
                    name: 'name'
                },
                {
                    type: 'currency',
                    name: 'amount',
                    related_fields: [
                        'currency_id',
                        'base_rate'
                    ]
                }
            ];
            sinon.stub(app.metadata, 'getModule', function() {
                return {
                    fields: {
                        name: {
                            type: 'text',
                            name: 'name'
                        },
                        amount: {
                            type: 'currency',
                            name: 'amount',
                            related_fields: [
                                'currency_id',
                                'base_rate'
                            ]
                        }
                    }
                }
            });
        });

        afterEach(function() {
            view.mergeFields = undefined;
            app.metadata.getModule.restore();
        });

        it('should contain related_fields', function() {
            var fields = view.getFieldNames();
            expect(fields.length).toEqual(4);
        });

        it('should contain related_fields from getModule', function() {
            delete view.mergeFields[1].related_fields;
            var fields = view.getFieldNames();
            expect(fields.length).toEqual(4);
        });
    });

    describe('Test for View.Views.BaseMergeDuplicatesView#flattenFieldsets', function() {
        beforeEach(function() {
            var module = 'Contacts',
                context = null,
                fieldDef = {
                    test1: {
                        label: 'test1',
                        name: 'test1',
                        type: 'datetime',
                        source: 'db'
                    },
                    test2: {
                        label: 'test2',
                        name: 'test2',
                        type: 'fieldset',
                        fields: [
                            {
                                label: 'sub1Test',
                                name: 'sub1Test',
                                type: 'varchar',
                                source: 'db'
                            },
                            {
                                label: 'sub2Test',
                                name: 'sub2Test',
                                type: 'varchar',
                                source: 'db'
                            }
                        ]
                    },
                    test3: {
                        label: 'test3',
                        name: 'test3',
                        type: 'varchar',
                        source: 'db'
                    }
                },
                meta = _.extend(fieldDef,
                    {
                        sub1Test: {
                            label: 'sub1Test',
                            name: 'sub1Test',
                            type: 'varchar',
                            source: 'db'
                        },
                        sub2Test: {
                            label: 'sub2Test',
                            name: 'sub2Test',
                            type: 'varchar',
                            source: 'db'
                        }
                    }
                );
            meta = _.extend(app.metadata.getModule(module), {fields: meta});
            SugarTest.testMetadata.updateModuleMetadata(module, meta);
            SugarTest.testMetadata.addViewDefinition('record', {
                panels: [
                    {
                        fields: [fieldDef.test1, fieldDef.test2, fieldDef.test3]
                    }
                ]
            }, module);
            SugarTest.testMetadata.set();

            layout = SugarTest.createLayout('base', module, 'list');
            layout.context.set({
                selectedDuplicates: [new Backbone.Model(), new Backbone.Model()]
            });
            view = SugarTest.createView('base', module, 'merge-duplicates', null, layout.context, null, layout);
        });

        afterEach(function() {
            layout.dispose();
            app.view.reset();
            SugarTest.testMetadata.dispose();
        });

        it('should flatten fieldsets properly', function() {
            var result = _.pluck(view.mergeFields, 'name');
            expect(result).toEqual(['test1', 'sub1Test', 'sub2Test', 'test3']);
        });
    });
});
