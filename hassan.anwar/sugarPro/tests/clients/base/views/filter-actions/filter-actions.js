describe('Base.View.FilterActions', function() {

    var view, app, parentLayout;

    beforeEach(function() {
        parentLayout = new Backbone.View();
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('filter-actions', 'view', 'base');
        SugarTest.testMetadata.set();
        view = SugarTest.createView('base', 'Accounts', 'filter-actions', {}, false, false, parentLayout);
        view.layout = parentLayout;
        view.initialize(view.options);
        view.render();
        app = SUGAR.App;
    });

    afterEach(function() {
        sinon.collection.restore();
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        SugarTest.testMetadata.dispose();
        view.dispose();
        view = null;
    });

    it('should call set filter name on filter:create:open', function() {
        var name = 'test';
        view.model.set({'name': name});
        var viewSetFilterStub = sinon.collection.stub(view, 'setFilterName');
        parentLayout.trigger('filter:create:open', view.model);
        expect(viewSetFilterStub).toHaveBeenCalled();
        expect(viewSetFilterStub.getCall(0).args).toEqual([name]);
    });

    it('should call toggleSave on filter:toggle:savestate', function() {
        var stub = sinon.collection.stub(view, 'toggleSave');
        view.initialize(view.options);
        parentLayout.trigger('filter:toggle:savestate');
        expect(stub).toHaveBeenCalled();
    });

    it('should call toggle on filter:create:open', function() {
        var stub = sinon.collection.stub(view, 'toggle');
        view.initialize(view.options);
        parentLayout.trigger('filter:create:open', new Backbone.Model());
        expect(stub).toHaveBeenCalled();
    });

    it('should call setFilterName on filter:set:name', function() {
        var stub = sinon.collection.stub(view, 'setFilterName');
        view.initialize(view.options);
        parentLayout.trigger('filter:set:name');
        expect(stub).toHaveBeenCalled();
    });

    it('should trigger save', function(){
       var spy = sinon.spy();
        view.context.off();
        view.context.on('filter:create:save', spy);
        view.triggerSave();
        expect(spy).toHaveBeenCalled();
        view.context.off();
    });

    it('should trigger delete', function() {
        var spy = sinon.spy();
        parentLayout.off();
        parentLayout.on('filter:create:delete', spy);
        view.triggerDelete();
        expect(spy).toHaveBeenCalled();
    });

    describe('filterNameChanged', function() {
        var layoutTriggerStub, component, saveFilterEditStateStub;

        beforeEach(function() {
            layoutTriggerStub = sinon.collection.stub(view.layout, 'trigger');
            component = {
                saveFilterEditState: $.noop
            };
            view.layout.getComponent = function() {
                return component;
            };
            saveFilterEditStateStub = sinon.collection.stub(component, 'saveFilterEditState');
            view.context.editingFilter = new Backbone.Model({id: 'my_filter', filter_definition: [{$owner: ''}]});
        });
        afterEach(function() {
            layoutTriggerStub.restore();
        });

        it('should trigger validate', function() {
            view.filterNameChanged();
            expect(layoutTriggerStub).toHaveBeenCalledWith('filter:toggle:savestate');
        });

        it('should save edit state when filter definition is valid', function() {
            view.filterNameChanged();
            expect(saveFilterEditStateStub).toHaveBeenCalled();
        });

        it('should not continue if editingFilter does not exist', function() {
            view.context.editingFilter = null;
            view.filterNameChanged();
            expect(layoutTriggerStub).not.toHaveBeenCalled();
        });
    });

    describe('triggerClose', function() {
        var component, filterLayoutTriggerStub, layoutTriggerStub;

        beforeEach(function() {
            component = {
                clearFilterEditState: $.noop,
                clearLastFilter: $.noop,
                trigger: $.noop
            };
            sinon.collection.stub(app.BeanCollection.prototype, 'fetch', function(options) {
                options.success();
            });
            SugarTest.declareData('base', 'Filters');
            component.filters = app.data.createBeanCollection('Filters');
            component.filters.setModuleName('Accounts');
            component.filters.load();
            component.filters.collection.defaultFilterFromMeta = 'my_metadata_default_filter';
            view.layout.getComponent = function() {
                return component;
            };
            filterLayoutTriggerStub = sinon.collection.stub(component, 'trigger');
            layoutTriggerStub = sinon.collection.stub(view.layout, 'trigger');
        });

        it('should revert changes and trigger events to refresh the data and close the form', function() {
            view.context.editingFilter = app.data.createBean('Filters', {id: 'my_filter', filter_definition: [
                {$owner: ''}
            ]});
            view.context.editingFilter.setSyncedAttributes({id: 'my_filter', filter_definition: [
                {$favorite: ''}
            ]});
            view.triggerClose();
            expect(layoutTriggerStub).toHaveBeenCalled();
            expect(layoutTriggerStub).toHaveBeenCalledWith('filter:apply', null, [
                {$favorite: ''}
            ]);
            expect(layoutTriggerStub).toHaveBeenCalledWith('filter:create:close');
        });

        it('should switch back to the default filter if the filter was new', function() {
            view.context.editingFilter = app.data.createBean('Filters', {filter_definition: [
                {$owner: ''}
            ]});
            view.triggerClose();
            expect(filterLayoutTriggerStub).toHaveBeenCalled();
            expect(filterLayoutTriggerStub).toHaveBeenCalledWith('filter:select:filter', 'my_metadata_default_filter');
        });
    });

    describe('toggle', function() {
        using('template filter', [true, false], function(value) {
            it('should show or hide the view', function() {
                var filter = new Backbone.Model({is_template: value});
                view.toggle(filter);
                expect(view.$el.hasClass('hide')).toBe(value);
            });
        });
    });

    describe('getFilterName validation', function() {
        using('valid values', [
            {str: 'a', expected: 'a'},
            {str: ' a', expected: 'a'},
            {str: 'a ', expected: 'a'},
            {str: '  a  ', expected: 'a'},
            {str: '', expected: ''},
            {str: '   ', expected: ''}
        ], function(data) {
            it('should trim filtername properly', function() {
                view.$('input').val(data.str);
                expect(view.getFilterName()).toBe(data.expected);
            });
        });
    });
});
