describe('Base.Field.Relate', function() {

    var app, field, fieldDef;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('relate', 'field', 'base', 'overwrite-confirmation');
        SugarTest.testMetadata.set();
        app = SugarTest.app;
        SugarTest.declareData('base', 'Filters');

        fieldDef = {
            "name": "account_name",
            "rname": "name",
            "id_name": "account_id",
            "vname": "LBL_ACCOUNT_NAME",
            "type": "relate",
            "link": "accounts",
            "table": "accounts",
            "join_name": "accounts",
            "isnull": "true",
            "module": "Accounts",
            "dbType": "varchar",
            "len": 100,
            "source": "non-db",
            "unified_search": true,
            "comment": "The name of the account represented by the account_id field",
            "required": true,
            "importable": "required"
        };

        sinon.collection.stub(app.BeanCollection.prototype, 'fetch', function(options) {
            if (options.success) {
                var args = [].splice.call(arguments, 0);
                options.success.call(this, args);
            }
        });
    });

    afterEach(function() {
        sinon.collection.restore();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        fieldDef = null;
    });

    describe('getSearchModule', function() {
        beforeEach(function() {
            // For testing, reset the module and link name on the field def
            fieldDef.module = '';
            fieldDef.link = 'test1_link';

            // Create the demo field setup
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);

            // Create the model as a bean with the test link field defined
            app.data.declareModel('Accounts', {fields: {'test1_link': {}}});
            field.model = app.data.createBean('Accounts');

            // Stub out getRelatedModule to make sure we get something we expect
            // but only when working on test args that are what we expect
            sinon.collection.stub(app.data, 'getRelatedModule').withArgs('Accounts','test1_link').returns("Test3");
        });

        afterEach(function() {
            field.dispose();
        });

        using('varying field and link def module values',
            [
                // Test 1 should get the module from the field def module
                {
                    fieldDefModule: 'Test1',
                    linkDefModule: '',
                    expect: 'Test1'
                },
                // Test 2 should get the module from the link field def module
                {
                    fieldDefModule: '',
                    linkDefModule: 'Test2',
                    expect: 'Test2'
                },
                // Test 3 should get the def from getRelatedModule in metadata
                // manager since both the field def and link field def module are
                // empty
                {
                    fieldDefModule: '',
                    linkDefModule: '',
                    expect: 'Test3'
                }
            ],
            function (values) {
                it('should get the proper module name from known def values', function() {
                    // Set our test expectations
                    field.def.module = values.fieldDefModule;
                    field.model.fields.test1_link.module = values.linkDefModule;

                    expect(field.getSearchModule()).toEqual(values.expect);
                });
            }
        );
    });

    describe("SetValue", function () {
        beforeEach(function () {
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});
        });

        afterEach(function() {
            field.dispose();
        });

        it("should set value correctly", function () {
            var expected_id = '0987',
                expected_name = 'blahblah';

            field.setValue({id: expected_id, value: expected_name});
            var actual_id = field.model.get(field.def.id_name),
                actual_name = field.model.get(field.def.name);

            expect(actual_id).toEqual(expected_id);
            expect(actual_name).toEqual(expected_name);
        });
    });

    describe('custom rname', function() {

        beforeEach(function() {
            fieldDef.rname = 'account_type';
            field = SugarTest.createField('base', 'account_name', 'relate', 'edit', fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: '1234', account_name: 'bob'});
        });

        afterEach(function() {
            field.dispose();
        });

        it('should set the displaying name by rname property value', function() {
            var expectedId = '0987',
                expectedName = 'blahblah',
                unexpectedName = 'oh~no';

            field.setValue({id: expectedId, value: unexpectedName, account_type: expectedName});
            var actualId = field.model.get(field.def.id_name),
               actualName = field.model.get(field.def.name);

            expect(actualId).toEqual(expectedId);
            expect(actualName).toEqual(expectedName);
            expect(actualName).not.toEqual(unexpectedName);
        });
    });

    describe("Populate related fields", function () {

        it("should warn the wrong metadata fields that populates unmatched fields", function () {
            sinon.collection.stub(app.metadata, 'getModule', function() {
                return {
                    fields: {
                        'field1': false
                    }
                };
            });
            var loggerStub = sinon.collection.stub(app.logger, 'error');
            fieldDef.populate_list = {
                "field1": "foo",
                "billing_office": "boo"
            };
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});

            expect(loggerStub).toHaveBeenCalled();

            field.dispose();
        });

        it("should populate related variables when the user confirms the changes", function () {
            fieldDef.populate_list = {
                "billing_office": "primary_address_1"
            };
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});
            field.model.fields = {
                'primary_address_1': {
                    label: ''
                }
            };

            //Before confirmed the dialog
            var expected_id = '0987',
                expected_name = 'blahblah',
                expected_primary_address_1 = 'should be undefined';

            field.setValue({
                id: expected_id,
                value: expected_name,
                boo: 'should not be in',
                billing_office: expected_primary_address_1
            });
            var actual_id = field.model.get(field.def.id_name),
                actual_name = field.model.get(field.def.name),
                actual_primary_address_1 = field.model.get("primary_address_1");
            expect(actual_id).toEqual(expected_id);
            expect(actual_name).toEqual(expected_name);
            expect(actual_primary_address_1).toBeUndefined();
            expect(field.model.get("boo")).toBeUndefined();

            //After the user confirms the dialog
            sinon.collection.stub(app.alert, 'show', function(msg, param) {
                param.onConfirm();
            });
            expected_primary_address_1 = '1234 blahblah st.';

            field.setValue({
                id: expected_id,
                value: expected_name,
                boo: 'should not be in',
                billing_office: expected_primary_address_1
            });
            actual_id = field.model.get(field.def.id_name);
            actual_name = field.model.get(field.def.name);
            actual_primary_address_1 = field.model.get("primary_address_1");
            expect(actual_id).toEqual(expected_id);
            expect(actual_name).toEqual(expected_name);
            expect(actual_primary_address_1).toEqual(expected_primary_address_1);
            expect(field.model.get("boo")).toBeUndefined();

            field.dispose();
        });
        it("should not populate related variables which does NOT have acl controls", function () {
            fieldDef.populate_list = {
                "billing_office": "primary_address_1",
                "billing_phone": "primary_phone_number"
            };
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});
            field.model.fields = {
                'primary_address_1': {
                    label: ''
                },
                'primary_phone_number': {
                    label: ''
                }
            };
            var aclMapping = {
                primary_address_1: false,
                primary_phone_number: true
            };
            sinon.collection.stub(app.alert, 'show', function(msg, param) {
                param.onConfirm();
            });
            sinon.collection.stub(app.acl, 'hasAccessToModel', function(action, model, field) {
                return aclMapping[field];
            });
            var expected_id = '0987',
                expected_name = 'blahblah',
                expected_primary_address_1 = 'should be undefined',
                expected_primary_phone_number = '999)111-2222';

            field.setValue({
                id: expected_id,
                value: expected_name,
                boo: 'should not be in',
                billing_office: expected_primary_address_1,
                billing_phone: expected_primary_phone_number
            });
            var actual_id = field.model.get(field.def.id_name),
                actual_name = field.model.get(field.def.name),
                actual_primary_address_1 = field.model.get("primary_address_1"),
                actual_primary_phone_number = field.model.get("primary_phone_number");
            expect(actual_id).toEqual(expected_id);
            expect(actual_name).toEqual(expected_name);
            expect(actual_primary_address_1).toBeUndefined();
            expect(field.model.get("boo")).toBeUndefined();
            expect(actual_primary_phone_number).toBe(expected_primary_phone_number);

            field.dispose();
        });
        it("should build route using check access", function () {
            var aclHasAccessStub = sinon.collection.stub(app.acl, 'hasAccess').returns(false);
            var field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);

            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});
            field.buildRoute('Users', '1');

            expect(aclHasAccessStub).toHaveBeenCalled();
            expect(field.href).toBeUndefined();

            aclHasAccessStub.restore();
            aclHasAccessStub = sinon.collection.stub(app.acl, 'hasAccess').returns(true);

            field.buildRoute('Users', '1');
            expect(aclHasAccessStub).toHaveBeenCalled();
            expect(field.href).toBeDefined();

            field.dispose();
        });
    });

    describe("alert message", function () {
        var alertShowStub;
        beforeEach(function () {
            fieldDef.populate_list = {
                "populate_field1": "populate_field_dist1",
                "populate_field2": "populate_field_dist2"
            };
            field = SugarTest.createField("base", "account_name", "relate", "edit", fieldDef);
            field.module = 'Accounts';
            field.model = new Backbone.Model({account_id: "1234", account_name: "bob"});
            field.model.fields = {
                'populate_field_dist1': {
                    label: ''
                },
                'populate_field_dist2': {
                    label: ''
                }
            };
            alertShowStub = sinon.collection.stub(app.alert, 'show');
        });

        afterEach(function() {
            field.dispose();
        });

        it("should call app.alert.show() if auto_populate is not defined", function () {
            var expected_id = '0987',
                expected_name = 'blahblah';

            field.setValue({
                id: expected_id,
                value: expected_name,
                populate_field1: 'new value 1',
                populate_field2: 'new value 2'
            });
            expect(app.alert.show).toHaveBeenCalled();
        });

        it("should call app.alert.show() if auto_populate is not true", function () {
            var expected_id = '0987',
                expected_name = 'blahblah';

            field.def.auto_populate = false;

            field.setValue({
                id: expected_id,
                value: expected_name,
                populate_field1: 'new value 1',
                populate_field2: 'new value 2'
            });
            expect(app.alert.show).toHaveBeenCalled();
        });

        it("should not call app.alert.show() if auto_populate is true", function () {
            var expected_id = '0987',
                expected_name = 'blahblah';

            field.def.auto_populate = true;

            field.setValue({
                id: expected_id,
                value: expected_name,
                populate_field1: 'new value 1',
                populate_field2: 'new value 2'
            });
            expect(app.alert.show).not.toHaveBeenCalled();
        });
    });

    describe('render', function() {
        beforeEach(function() {
            field = SugarTest.createField('base', 'account_name', 'relate', 'edit', fieldDef);
            sinon.collection.stub(field, 'getSearchModule');
            sinon.collection.stub(field, '_renderEditableDropdown');
            sinon.collection.stub(field, '_renderDisabledDropdown');
            field.filters = {};
        });

        afterEach(function() {
            field.dispose();
        });

        using('different search modules', [
            {
                module: undefined,
                renderEditable: true,
                renderDisabled: false
            },
            {
                module: 'invalidModule',
                renderEditable: false,
                renderDisabled: true
            },
            {
                module: 'Cases',
                renderEditable: true,
                renderDisabled: false
            }
        ], function(options) {

            it('should render the dropdown disabled if the search module is defined but invalid', function() {
                field.getSearchModule.returns(options.module);
                field.render();

                expect(field._renderEditableDropdown.called).toBe(options.renderEditable);
                expect(field._renderDisabledDropdown.called).toBe(options.renderDisabled);
            });
        });
    });

    describe('openSelectDrawer', function() {
        var openStub;

        beforeEach(function() {
            app.drawer = app.drawer || {};
            app.drawer.open = app.drawer.open || $.noop;
            field = SugarTest.createField('base', 'account_name', 'relate', 'edit', fieldDef);
            openStub = sinon.collection.stub(app.drawer, 'open');

            field.model.fields = {
                account_id: {
                    name: 'account_id'
                },
                account_name: {
                    name: 'account_name',
                    id_name: 'account_id'
                },
                contact_id: {
                    name: 'contact_id'
                },
                contact_name: {
                    name: 'contact_name',
                    id_name: 'contact_id'
                }
            };
        });

        afterEach(function() {
            field.dispose();
        });

        it('should open the drawer with no filter options', function() {
            field.openSelectDrawer();
            expect(openStub).toHaveBeenCalled();
            var arguments = openStub.firstCall.args,
                filterOptions = arguments[0].context.filterOptions;
            expect(filterOptions).toBeUndefined();
        });

        using('different definitions', [
            {
                def: {
                    filter_relate: {
                        'account_id': 'account_id'
                    }
                },
                expected: {
                    label: 'The related Account',
                    filter_populate: {
                        'account_id': {$in: ['1234-5678']}
                    }
                }
            },
            {
                def: {
                    filter_relate: {
                        'contact_id': 'id'
                    }
                },
                expected: {
                    label: 'The related Contact',
                    filter_populate: {
                        'id': {$in: ['abcd-efgh']}
                    }
                }
            }
        ], function(option) {

            beforeEach(function() {
                field.model.set('account_id', '1234-5678');
                field.model.set('account_name', 'The related Account');
                field.model.set('contact_id', 'abcd-efgh');
                field.model.set('contact_name', 'The related Contact');
            });

            it('should open the drawer with filter options', function() {
                field.def.filter_relate = option.def.filter_relate;
                field.openSelectDrawer();
                expect(openStub).toHaveBeenCalled();
                var arguments = openStub.firstCall.args,
                    filterOptions = arguments[0].context.filterOptions;
                expect(filterOptions).toBeDefined();
                expect(filterOptions.initial_filter).toEqual('$relate');
                expect(filterOptions.initial_filter_label).toEqual(option.expected.label);
                expect(filterOptions.filter_populate).toEqual(option.expected.filter_populate);
                expect(filterOptions.stickiness).toEqual(false);
            });
        });
    });

    describe('search', function() {
        it('should call `buildFilterDefinition`', function() {
            field = SugarTest.createField('base', 'account_name', 'relate', 'edit', fieldDef);
            var buildFilterDefinitionStub = sinon.collection.stub(field, 'buildFilterDefinition'),
                queryObj = {
                    term: 'asdf',
                    context: {}
                };

            field.search(queryObj);
            expect(buildFilterDefinitionStub).toHaveBeenCalled();
            expect(field.searchCollection.fetch).toHaveBeenCalled();
            field.dispose();
        });
    });

    describe('buildFilterDefinition', function() {

        beforeEach(function() {
            sinon.collection.stub(app.metadata, 'getModule').returns({fields: {}});
            field = SugarTest.createField('base', 'account_name', 'relate', 'edit');

            sinon.collection.stub(field, 'getSearchModule').returns('Contacts');
            field.initialize(field.options);
            field.render();

            sinon.collection.stub(app.data.getBeanClass('Filters').prototype, 'buildSearchTermFilter',
                function(module, term) {
                    if (!term) {
                        return;
                    }
                    return { 'test': {$starts: term}};
                }
            );
        });

        afterEach(function() {
            field.dispose();
        });

        using('different field defs', [
            {
                def: {
                    filter_relate: {
                        'account_name': 'account_name'
                    }
                },
                term: 'asdf',
                defined: true
            },
            {
                def: {
                    filter_relate: {
                        'account_name': 'account_name'
                    }
                },
                term: undefined,
                defined: true
            },
            {
                def: {
                    filter_relate: {
                        'account_name': 'account_name'
                    }
                },
                term: 'asdf',
                defined: true
            },
            {
                def: {},
                term: undefined,
                defined: false
            }
        ], function(value) {
            it('should return an appropriate filter definition', function() {
                field.def.filter_relate = value.def.filter_relate;
                field.filters.setFilterOptions(field.getFilterOptions());
                field.filters.load();

                expect(_.isEmpty(field.buildFilterDefinition(value.term))).toBe(!value.defined);
            });
        });
    });
});
