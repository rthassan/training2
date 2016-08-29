describe('Base.View.FilteredListView', function() {

    var view, app, parentLayout;

    beforeEach(function() {
        parentLayout = new Backbone.View();
        SugarTest.loadComponent('base', 'view', 'list');
        view = SugarTest.createView('base', 'Accounts', 'filtered-list', {}, false, false, parentLayout);
        view.collection = new Backbone.Collection();
        app = SUGAR.App;
    });

    afterEach(function() {
        sinon.collection.restore();
        view.dispose();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        view = null;
        parentLayout = null;
    });

    it('should initiate the searchable fields', function() {
        var fields = {
            'field_name': {
                name: 'field_name',
                filter: 'startsWith'
            },
            'date_created': {
                name: 'date_created',
                filter: 'endsWith'
            },
            'name': {
                name: 'name',
                filter: 'contains'
            },
            'no_filter': {
                name: 'no_filter'
            }
        };
        sinon.collection.stub(view, 'getFields', function() {
            return fields;
        });
        view._initFilter();
        var actual = view._filter,
            expected = ['field_name', 'date_created', 'name'];
        expect(_.size(actual)).toBe(_.size(expected));
        _.each(actual, function(field, index) {
            expect(field.name).toBe(expected[index]);
        }, this);
    });

    describe('Search filter', function() {
        beforeEach(function() {
            var collection = [
                {
                    'id': '1',
                    'field_name': '123 abc',
                    'value': '11 112 34a bc'
                },
                {
                    'id': '2',
                    'field_name': ' 12',
                    'value': '1234'
                },
                {
                    'id': '3',
                    'field_name': '23ab12',
                    'value': 'ab1234as'
                },
                {
                    'id': '4',
                    'field_name': 'tab',
                    'value': 'Foo boo'
                }
            ];
            view.collection = new Backbone.Collection(collection);
        });
        using('Available filters', [
            {
                fields: {
                    'field_name': {
                        name: 'field_name',
                        filter: 'startsWith'
                    }
                },
                term: '12',
                expected: ['1']
            },
            {
                fields: {
                    'field_name': {
                        name: 'value',
                        filter: 'contains'
                    }
                },
                term: '123',
                expected: ['2', '3']
            },
            {
                fields: {
                    'field_name': {
                        name: 'field_name',
                        filter: 'endsWith'
                    }
                },
                term: 'ab',
                expected: ['4']
            }
        ], function(value) {
            it('should filter the collection that matches search term', function() {

                sinon.collection.stub(view, 'getFields', function() {
                    return value.fields;
                });
                view._initFilter();
                view.searchTerm = value.term;
                view.filterCollection();

                var actual = view.filteredCollection;
                expect(_.size(actual)).toBe(_.size(value.expected));
                _.each(value.expected, function(expectedValue) {
                    var filteredModel = _.find(actual, function(model) {return model.id == expectedValue;});
                    expect(filteredModel).toBeDefined();
                }, this);
            });
        });
    });
});
