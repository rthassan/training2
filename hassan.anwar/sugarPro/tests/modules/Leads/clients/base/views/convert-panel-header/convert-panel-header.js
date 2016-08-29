describe("Leads.Base.View.ConvertPanelHeader", function() {
    var app, view,
        dupeCheckResults = '.dupecheck-results',
        associateButton = '[name="associate_button"]';

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('convert-panel-header', 'view', 'base', null, 'Leads');
        SugarTest.loadHandlebarsTemplate('convert-panel-header', 'view', 'base', 'title', 'Leads');
        SugarTest.loadHandlebarsTemplate('convert-panel-header', 'view', 'base', 'dupecheck-pending', 'Leads');
        SugarTest.loadHandlebarsTemplate('convert-panel-header', 'view', 'base', 'dupecheck-results', 'Leads');
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();

        view = SugarTest.createView('base', 'Leads', 'convert-panel-header', {
            module: 'Foos',
            moduleSingular: 'Foo',
            required: false
        }, null, true, createMockLayout());
        view.render();

        this.addMatchers({
            toBeHidden: function() {
                this.message = function () {
                    return "Expected " + this.actual + (this.isNot ? " not" : "") + " to be hidden";
                }
                return this.actual.css('display') === 'none';
            }
        });
    });

    afterEach(function() {
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        SugarTest.testMetadata.dispose();
    });

    it("should change the title when toggling between subviews", function() {
        var createNewLabel = 'CREATE_NEW';

        toggleSubView('create');
        expect(view.$('.title').text()).toContain(createNewLabel);

        toggleSubView('dupecheck');
        expect(view.$('.title').text()).not.toContain(createNewLabel);
    });

    it("should toggle the dupe check results when toggling between subviews", function() {
        expect(view.$(dupeCheckResults)).not.toBeHidden();
        toggleSubView('create');
        expect(view.$(dupeCheckResults)).toBeHidden();
        toggleSubView('dupecheck');
        expect(view.$(dupeCheckResults)).not.toBeHidden();
    });

    it("should show the appropriate toggle link when toggling between subviews", function() {
        var createToggle = '.subview-toggle .create',
            dupeCheckToggle = '.subview-toggle .dupecheck';

        toggleSubView('create');
        expect(view.$(createToggle)).not.toBeHidden();
        expect(view.$(dupeCheckToggle)).toBeHidden();
        toggleSubView('dupecheck');
        expect(view.$(dupeCheckToggle)).not.toBeHidden();
        expect(view.$(createToggle)).toBeHidden();
    });

    it("should set the appropriate associate button state when toggling between subviews and the panel is active", function() {
        view.handlePanelShown();

        //not active if dupe not selected yet
        view.layout.currentState.dupeSelected = false;
        toggleSubView('dupecheck');
        expect(view.$(associateButton)).toHaveClass('disabled');

        //always active for create
        toggleSubView('create');
        expect(view.$(associateButton)).not.toHaveClass('disabled');

        //active if dupe selected
        view.layout.currentState.dupeSelected = true;
        toggleSubView('dupecheck');
        expect(view.$(associateButton)).not.toHaveClass('disabled');
    });

    it("should always keep associate button disabled when panel is hidden", function() {
        view.handlePanelHidden();
        view.layout.currentState.dupeSelected = true;

        toggleSubView('dupecheck');
        expect(view.$(associateButton)).toHaveClass('disabled');

        toggleSubView('create');
        expect(view.$(associateButton)).toHaveClass('disabled');
    });

    it("should toggle the header active state when panel hidden/shown", function() {
        view.handlePanelHidden();
        expect(view.$('.accordion-heading')).not.toHaveClass('active');

        view.handlePanelShown();
        expect(view.$('.accordion-heading')).toHaveClass('active');
    });

    it("should hide/show the subpanel toggle when panel hidden/shown", function() {
        view.handlePanelHidden();
        expect(view.$('.subview-toggle')).toHaveClass('hide');

        view.handlePanelShown();
        expect(view.$('.subview-toggle')).not.toHaveClass('hide');
    });

    it("should not show subpanel toggle when panel is complete", function() {
        view.layout.currentState.complete = true;
        view.handlePanelShown();
        expect(view.$('.subview-toggle')).toHaveClass('hide');
    });

    it("should disable associate button when panel is hidden and re-enable when panel is shown (if previously enabled)", function() {
        view.handlePanelShown();
        toggleSubView('create'); //create always has button enabled
        expect(view.$(associateButton)).not.toHaveClass('disabled');

        view.handlePanelHidden();
        expect(view.$(associateButton)).toHaveClass('disabled');

        view.handlePanelShown();
        expect(view.$(associateButton)).not.toHaveClass('disabled');
    });

    it("should not re-enable associate button when panel is shown if not previously enabled", function() {
        view.handlePanelShown();
        view.layout.currentState.dupeSelected = false;
        toggleSubView('dupecheck');
        expect(view.$(associateButton)).toHaveClass('disabled');

        view.handlePanelHidden();
        expect(view.$(associateButton)).toHaveClass('disabled');

        view.handlePanelShown();
        expect(view.$(associateButton)).toHaveClass('disabled');
    });

    it("should set active indicator button appropriately when hidden/shown", function() {
        view.handlePanelShown();
        expect(view.$('.active-indicator i')).toHaveClass('fa-chevron-up');
        view.handlePanelHidden();
        expect(view.$('.active-indicator i')).toHaveClass('fa-chevron-down');
    });

    it("should set step circle appropriately when complete/reset", function() {
        completePanel();
        expect(view.$('.step-circle')).toHaveClass('complete');
        resetPanel();
        expect(view.$('.step-circle')).not.toHaveClass('complete');
    });

    it("should render title appropriately when complete/reset", function() {
        var selectedModuleLabel = 'SELECTED_MODULE',
            newModuleLabel = 'CREATED_MODULE';

        toggleSubView('create');
        completePanel();
        expect(view.$('.title').text()).toContain(newModuleLabel);
        expect(view.$('.title').text()).not.toContain(selectedModuleLabel);

        resetPanel();
        expect(view.$('.title').text()).not.toContain(selectedModuleLabel);
        expect(view.$('.title').text()).not.toContain(newModuleLabel);

        toggleSubView('dupecheck');
        completePanel();
        expect(view.$('.title').text()).toContain(selectedModuleLabel);
        expect(view.$('.title').text()).not.toContain(newModuleLabel);
    });

    it("should hide/show dupe results when complete/reset", function() {
        completePanel();
        expect(view.$(dupeCheckResults)).toBeHidden();
        resetPanel();
        expect(view.$(dupeCheckResults)).not.toBeHidden();
    });

    it("should switch associate/reset buttons when complete/reset", function() {
        expect(view.getField('associate_button').$el).not.toBeHidden();
        completePanel();
        expect(view.getField('associate_button').$el).toBeHidden();
        expect(view.getField('reset_button').$el).not.toBeHidden();
        resetPanel();
        expect(view.getField('associate_button').$el).not.toBeHidden();
        expect(view.getField('reset_button').$el).toBeHidden();
    });

    it("should switch subview toggle labels if no duplicates found", function() {
        var dupecheckLabelBefore = view.$('.toggle-link.dupecheck').text(),
            createLabelBefore = view.$('.toggle-link.create').text();
        view.setDupeCheckResults(0);
        expect(view.$('.toggle-link.dupecheck').text()).not.toEqual(dupecheckLabelBefore);
        expect(view.$('.toggle-link.create').text()).not.toEqual(createLabelBefore);
    });

    var toggleSubView = function(toggle) {
        view.layout.currentToggle = toggle;
        view.handleToggleChange(toggle);
    };

    var completePanel = function() {
        view.layout.currentState.complete = true;
        view.handlePanelComplete();
    };

    var resetPanel = function() {
        view.layout.currentState.complete = false;
        view.handlePanelReset();
    };

    var createMockLayout = function() {
        var MockLayout = Backbone.View.extend({
            TOGGLE_CREATE: 'create',
            TOGGLE_DUPECHECK: 'dupecheck',

            currentState: {
                complete: false,
                dupeSelected: false
            },
            currentToggle: 'dupecheck'
        });
        return new MockLayout();
    };
});
