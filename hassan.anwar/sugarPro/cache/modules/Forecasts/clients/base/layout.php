<?php
$clientCache['Forecasts']['base']['layout'] = array (
  'records' => 
  array (
    'meta' => 
    array (
      'components' => 
      array (
        0 => 
        array (
          'layout' => 
          array (
            'components' => 
            array (
              0 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'view' => 'list-headerpane',
                    ),
                    1 => 
                    array (
                      'view' => 'info',
                    ),
                    2 => 
                    array (
                      'layout' => 'list',
                      'context' => 
                      array (
                        'module' => 'ForecastManagerWorksheets',
                      ),
                    ),
                    3 => 
                    array (
                      'layout' => 'list',
                      'context' => 
                      array (
                        'module' => 'ForecastWorksheets',
                      ),
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'main-pane',
                  'span' => 8,
                ),
              ),
              1 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'layout' => 'list-sidebar',
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'side-pane',
                  'span' => 4,
                ),
              ),
              2 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'layout' => 
                      array (
                        'type' => 'dashboard',
                        'last_state' => 
                        array (
                          'id' => 'last-visit',
                        ),
                      ),
                      'context' => 
                      array (
                        'forceNew' => true,
                        'module' => 'Home',
                      ),
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'dashboard-pane',
                  'span' => 4,
                ),
              ),
              3 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'layout' => 'preview',
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'preview-pane',
                  'span' => 8,
                ),
              ),
            ),
            'type' => 'default',
            'name' => 'sidebar',
            'span' => 12,
          ),
        ),
      ),
      'type' => 'records',
      'name' => 'base',
      'span' => 12,
    ),
    'controller' => 
    array (
      'base' => '/*
     * Your installation or use of this SugarCRM file is subject to the applicable
     * terms available at
     * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
     * If you do not agree to all of the applicable terms or do not have the
     * authority to bind the entity as an authorized representative, then do not
     * install or use this SugarCRM file.
     *
     * Copyright (C) SugarCRM Inc. All rights reserved.
     *//*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * Forecasts Records Layout
 *
 * @class View.Layouts.Base.Forecasts.RecordsLayout
 * @alias SUGAR.App.view.layouts.BaseForecastsRecordsLayout
 * @extends View.Layouts.Base.RecordsLayout
 *
 * Events
 *
 * forecasts:worksheet:committed
 *  on: this.context
 *  by: commitForecast
 *  when: after a successful Forecast Commit
 */
({
    /**
     * bool to store if a child worksheet is dirty
     */
    isDirty: false,
    
    /**
     * worksheet type
     */
    worksheetType: \'\',
    
    /**
     * the forecast navigation message
     */
    navigationMessage: "",
    
    /**
     * The options from the initialize call
     */
    initOptions: undefined,

    /**
     * Overrides the Layout.initialize function and does not call the parent so we can defer initialization
     * until _onceInitSelectedUser is called
     *
     * @override
     */
    initialize: function(options) {
        // the parent is not called here so we make sure that nothing else renders until after we init the
        // the forecast module
        this.initOptions = options;

        var acls = app.user.getAcls().Forecasts,
            hasAccess = (!_.has(acls, \'access\') || acls.access == \'yes\');
        if(hasAccess) {
            // Check to make sure users have proper values in their sales_stage_won/_lost cfg values
            if(app.utils.checkForecastConfig()) {
                // correct config exists, continue with syncInitData
                this.syncInitData();
            } else {
                // codeblock this sucka
                this.codeBlockForecasts(\'LBL_FORECASTS_MISSING_STAGE_TITLE\', \'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES\');
            }
        } else {
            this.codeBlockForecasts(\'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE\', \'LBL_FORECASTS_ACLS_NO_ACCESS_MSG\');
        }
    },

    /**
     * Blocks forecasts from continuing to load
     */
    codeBlockForecasts: function(title, msg) {
        var alert = app.alert.show(\'no_access_to_forecasts\', {
            level: \'error\',
            title: app.lang.get(title, \'Forecasts\') + \':\',
            messages: [app.lang.get(msg, \'Forecasts\')]
        });

        var $close = alert.getCloseSelector();
        $close.on(\'click\', function() {
            $close.off();
            app.router.navigate(\'#Home\', {trigger: true});
        });
        app.accessibility.run($close, \'click\');
    },

    /**
     * Overrides loadData to defer it running until we call it in _onceInitSelectedUser
     *
     * @override
     */
    loadData: function() {
    },

    /**
     * {@inheritdoc}
     */
    bindDataChange: function() {
        // we need this here to track when the selectedTimeperiod changes and then also move it up to the context
        // so the recordlists can listen for it.
        if (!_.isUndefined(this.model)) {
            this.collection.on(\'reset\', function() {
                // get the first model and set the last commit date
                var lastCommit = _.first(this.collection.models);
                var commitDate = undefined;
                if (lastCommit instanceof Backbone.Model && lastCommit.has(\'date_modified\')) {
                    commitDate = lastCommit.get(\'date_modified\');
                }
                this.context.set({\'currentForecastCommitDate\': commitDate});
            }, this);
            // since the selected user change on the context, update the model
            this.context.on(\'change:selectedUser\', function(model, changed) {
                var update = {
                    \'selectedUserId\': changed.id,
                    \'forecastType\': app.utils.getForecastType(changed.is_manager, changed.showOpps)
                }
                this.model.set(update);
            }, this);

            // if the model changes, run a fetch
            this.model.on(\'change\', function() {
                // clear this out as something on the model changed,
                // this will be set once the collection resets
                // set the value to null since it can be undefined
                this.context.set({\'currentForecastCommitDate\' : null}, {silent: true});
                this.collection.fetch();
            }, this);

            this.context.on(\'change:selectedTimePeriod\', function() {
                // clear this out if the timeperiod changed on the context,
                // this will be set once the collection resets
                // set the value to null since it can be undefined
                this.context.set({\'currentForecastCommitDate\' : null}, {silent: true});
                this.collection.fetch();
            }, this);

            // listen on the context for a commit trigger
            this.context.on(\'forecasts:worksheet:commit\', function(user, worksheet_type, forecast_totals) {
                this.commitForecast(user, worksheet_type, forecast_totals);
            }, this);
            
            //listen for the worksheets to be dirty/clean
            this.context.on("forecasts:worksheet:dirty", function(type, isDirty){
                this.isDirty = isDirty;
                this.worksheetType = type;
            }, this);
            
            //listen for the worksheet navigation messages
            this.context.on("forecasts:worksheet:navigationMessage", function(message){
                this.navigationMessage = message;
            }, this);
            
            //listen for the user to change
            this.context.on("forecasts:user:changed", function(selectedUser, context){
                if(this.isDirty){
                    app.alert.show(\'leave_confirmation\', {
                        level: \'confirmation\',
                        messages: app.lang.get(this.navigationMessage, \'Forecasts\').split(\'<br>\'),
                        onConfirm: _.bind(function() {
                            app.utils.getSelectedUsersReportees(selectedUser, context);
                        }, this),
                        onCancel: _.bind(function() {
                            this.context.trigger(\'forecasts:user:canceled\');
                        }, this)
                    });
                } else {
                    app.utils.getSelectedUsersReportees(selectedUser, context);
                }
            }, this);
            
            //handle timeperiod change events
            this.context.on(\'forecasts:timeperiod:changed\', function(model, startEndDates) {
                // create an anonymous function to combine the two calls where this is used
                var onSuccess = _.bind(function() {
                    this.context.set(\'selectedTimePeriod\', model.get(\'selectedTimePeriod\'));
                    this._saveTimePeriodStatEndDates(startEndDates[\'start\'], startEndDates[\'end\']);
                }, this);

                if (this.isDirty) {
                    app.alert.show(\'leave_confirmation\', {
                        level: \'confirmation\',
                        messages: app.lang.get(this.navigationMessage, \'Forecasts\').split(\'<br>\'),
                        onConfirm: onSuccess,
                        onCancel: _.bind(function() {
                            this.context.trigger(\'forecasts:timeperiod:canceled\');
                        }, this)
                    });
                } else {
                    // call the on success handler
                    onSuccess();
                }
            }, this);
        }
    },

    /**
     * Utility Method to handle saving of the timeperiod start and end dates so we can use them in other parts
     * of the forecast application
     *
     * @param {String} startDate        Start Date
     * @param {String} endDate          End Date
     * @param {Boolean} [doSilent]      When saving to the context, should this be silent to supress events
     * @return {Object} The object that is saved to the context if the context is there.
     * @private
     */
    _saveTimePeriodStatEndDates: function(startDate, endDate, doSilent)
    {
        // if do silent is not passed in or it\'s not a boolean, then just default it to false, so the events will fire
        if (_.isUndefined(doSilent) || !_.isBoolean(doSilent)) {
            doSilent = false;
        }
        var userPref = app.date.convertFormat(app.user.getPreference(\'datepref\')),
            systemPref = \'YYYY-MM-DD\',
            dateObj = {
                start: app.date(startDate, [userPref, systemPref]).format(systemPref),
                end: app.date(endDate, [userPref, systemPref]).format(systemPref)
            };

        if (!_.isUndefined(this.context)) {
            this.context.set(
                \'selectedTimePeriodStartEnd\',
                dateObj,
                {silent: doSilent}
            );
        }

        return dateObj;
    },

    /**
     * Opens the Forecasts Config drawer
     */
    openConfigDrawer: function() {
        // if there is no drawer open, then we need to open the drawer.
        if(app.drawer._components.length == 0) {
            // trigger the forecast config by going to the config route, while replacing what
            // is currently there so when we use app.route.goBack() from the cancel button
            app.router.navigate(\'Forecasts/config\', {replace: true, trigger: true});
        }
    },

    /**
     * Get the Forecast Init Data from the server
     *
     * @param {Object} options
     */
    syncInitData: function(options) {
        var callbacks,
            url;

        options = options || {};
        // custom success handler
        options.success = _.bind(function(model, data, options) {
            // Add Forecasts-specific stuff to the app.user object
            app.user.set(data.initData.userData);
            if (data.initData.forecasts_setup === 0) {
                // Immediately open the config drawer so user can set up config
                this.openConfigDrawer();
            } else {
                this.initForecastsModule(data, options);
            }
        }, this);

        // since we have not initialized the view yet, pull the model from the initOptions.context
        var model = this.initOptions.context.get(\'model\');
        callbacks = app.data.getSyncCallbacks(\'read\', model, options);
        this.trigger("data:sync:start", \'read\', model, options);

        url = app.api.buildURL("Forecasts/init", null, null, options.params);
        app.api.call("read", url, null, callbacks);
    },

    /**
     * Process the Forecast Data
     *
     * @param {Object} data contains the data passed back from Forecasts/init endpoint
     * @param {Object} options
     */
    initForecastsModule: function(data, options) {
        var ctx = this.initOptions.context;
        // we watch for the first selectedUser change to actually init the Forecast Module case then we know we have
        // a proper selected user
        ctx.once(\'change:selectedUser\', this._onceInitSelectedUser, this);

        // lets see if the user has ranges selected, so lets generate the key from the filters
        var ranges_key = app.user.lastState.buildKey(\'worksheet-filter\', \'filter\', \'ForecastWorksheets\'),
            default_selection = app.user.lastState.get(ranges_key) || data.defaultSelections.ranges;

        // set items on the context from the initData payload
        ctx.set({
            // set the value to null since it can be undefined
            currentForecastCommitDate: null,
            selectedTimePeriod: data.defaultSelections.timeperiod_id.id,
            selectedRanges: default_selection,
            selectedTimePeriodStartEnd: this._saveTimePeriodStatEndDates(
                data.defaultSelections.timeperiod_id.start,
                data.defaultSelections.timeperiod_id.end,
                true
            )
        }, {silent: true});

        ctx.get(\'model\').set({\'selectedTimePeriod\': data.defaultSelections.timeperiod_id.id}, {silent: true});

        // set the selected user to the context
        app.utils.getSelectedUsersReportees(app.user.toJSON(), ctx);
    },

    /**
     * Event handler for change:selectedUser
     * Triggered once when the user is set for the first time.  After setting user it calls
     * the init of the records layout
     *
     * @param {Backbone.Model} model the model from the change event
     * @param {String} change the updated selectedUser value from the change event
     * @private
     */
    _onceInitSelectedUser: function(model, change) {
        // init the recordlist view
        app.view.Layout.prototype.initialize.call(this, this.initOptions);

        // set the selected user and forecast type on the model
        this.model.set(\'selectedUserId\', change.id, {silent: true});
        this.model.set(\'forecastType\', app.utils.getForecastType(change.is_manager, change.showOpps));
        // bind the collection sync to our custom sync
        this.collection.sync = _.bind(this.sync, this);

        // load the data
        app.view.Layout.prototype.loadData.call(this);
        // bind the data change
        this.bindDataChange();
        // render everything
        if (!this.disposed) this.render();
    },

    /**
     * Custom sync method used by this.collection
     *
     * @param {String} method
     * @param {Backbone.Model} model
     * @param {Object} options
     */
    sync: function(method, model, options) {
        var callbacks,
            url;

        options = options || {};

        options.params = options.params || {};

        var args_filter = [],
            filter = null;
        if (this.context.has(\'selectedTimePeriod\')) {
            args_filter.push({"timeperiod_id": this.context.get(\'selectedTimePeriod\')});
        }
        if (this.model.has(\'selectedUserId\')) {
            args_filter.push({"user_id": this.model.get(\'selectedUserId\')});
            args_filter.push({"forecast_type": this.model.get(\'forecastType\')});
        }

        if (!_.isEmpty(args_filter)) {
            filter = {"filter": args_filter};
        }

        options.params.order_by = \'date_entered:DESC\'
        options = app.data.parseOptionsForSync(method, model, options);

        // custom success handler
        options.success = _.bind(function(model, data, options) {
            if(!this.disposed) {
                this.collection.reset(data);
            }
        }, this);

        callbacks = app.data.getSyncCallbacks(method, model, options);
        this.collection.trigger("data:sync:start", method, model, options);

        url = app.api.buildURL("Forecasts/filter", null, null, options.params);
        app.api.call("create", url, filter, callbacks);
    },

    /**
     * Commit A Forecast
     *
     * @fires forecasts:worksheet:committed
     * @param {Object} user
     * @param {String} worksheet_type
     * @param {Object} forecast_totals
     */
    commitForecast: function(user, worksheet_type, forecast_totals) {
        var forecast = new this.collection.model(),
            forecastType = app.utils.getForecastType(user.is_manager, user.showOpps),
            forecastData = {};


        // we need a commit_type so we know what to do on the back end.
        forecastData.commit_type = worksheet_type;
        forecastData.timeperiod_id = forecast_totals.timeperiod_id || this.context.get(\'selectedTimePeriod\');
        forecastData.forecast_type = forecastType;

        forecast.save(forecastData, { success: _.bind(function(model, response) {
            // we need to make sure we are not disposed, this handles any errors that could come from the router and window
            // alert events
            if (!this.disposed) {
                // Call sync again so commitLog has the full collection
                // method gets overridden and options just needs an
                this.collection.fetch();
                this.context.trigger("forecasts:worksheet:committed", worksheet_type, response);
                var msg, managerName;
                if (worksheet_type === \'sales_rep\') {
                    if (user.is_manager) {
                        // as manager, use own name
                        managerName = user.full_name;
                    } else {
                        // as sales rep, use manager name
                        managerName = user.reports_to_name;
                    }
                } else {
                    if (user.reports_to_id) {
                        // if manager has a manager, use reports to name
                        managerName = user.reports_to_name;
                    }
                }
                if (managerName) {
                    msg = Handlebars.compile(app.lang.get(\'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO\', \'Forecasts\'))(
                        {
                            manager: managerName
                        }
                    );
                } else {
                    // user does not report to anyone, don\'t use any name
                    msg = Handlebars.compile(app.lang.get(\'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS\', \'Forecasts\'))();
                }

                app.alert.show(\'success\', {
                    level: \'success\',
                    autoClose: true,
                    autoCloseDelay: 10000,
                    title: app.lang.get(\'LBL_FORECASTS_WIZARD_SUCCESS_TITLE\', \'Forecasts\') + \':\',
                    messages: [msg]
                });
            }
        }, this), silent: true, alerts: { \'success\': false }});
    }
})
',
    ),
  ),
  'list-dashboard' => 
  array (
    'meta' => 
    array (
      'metadata' => 
      array (
        'components' => 
        array (
          0 => 
          array (
            'rows' => 
            array (
              0 => 
              array (
                0 => 
                array (
                  'view' => 
                  array (
                    'type' => 'forecastdetails',
                    'label' => 'LBL_DASHLET_FORECAST_NAME',
                  ),
                  'context' => 
                  array (
                    'module' => 'Forecasts',
                  ),
                  'width' => 12,
                ),
              ),
              1 => 
              array (
                0 => 
                array (
                  'view' => 
                  array (
                    'type' => 'forecasts-chart',
                    'label' => 'LBL_DASHLET_FORECASTS_CHART_NAME',
                  ),
                  'context' => 
                  array (
                    'module' => 'Forecasts',
                  ),
                  'width' => 12,
                ),
              ),
            ),
            'width' => 12,
          ),
        ),
      ),
      'name' => 'LBL_DEFAULT_DASHBOARD_TITLE',
    ),
  ),
  'config-drawer-content' => 
  array (
    'meta' => 
    array (
      'type' => 'config-drawer-content',
      'name' => 'config-drawer-content',
      'components' => 
      array (
        0 => 
        array (
          'view' => 'config-timeperiods',
        ),
        1 => 
        array (
          'view' => 'config-ranges',
        ),
        2 => 
        array (
          'view' => 'config-scenarios',
        ),
        3 => 
        array (
          'view' => 'config-worksheet-columns',
        ),
      ),
    ),
    'controller' => 
    array (
      'base' => '/*
     * Your installation or use of this SugarCRM file is subject to the applicable
     * terms available at
     * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
     * If you do not agree to all of the applicable terms or do not have the
     * authority to bind the entity as an authorized representative, then do not
     * install or use this SugarCRM file.
     *
     * Copyright (C) SugarCRM Inc. All rights reserved.
     *//*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * @class View.Layouts.Base.ForecastsConfigDrawerContentLayout
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigDrawerContentLayout
 * @extends View.Layouts.Base.ConfigDrawerContentLayout
 */
({
    extendsFrom: \'ConfigDrawerContentLayout\',

    timeperiodsTitle: undefined,
    timeperiodsText: undefined,
    scenariosTitle: undefined,
    scenariosText: undefined,
    rangesTitle: undefined,
    rangesText: undefined,
    forecastByTitle: undefined,
    forecastByText: undefined,
    wkstColumnsTitle: undefined,
    wkstColumnsText: undefined,

    /**
     * @inheritdoc
     * @override
     */
    _initHowTo: function() {
        var appLang = app.lang,
            forecastBy = app.metadata.getModule(\'Forecasts\', \'config\').forecast_by,
            forecastByLabels = {
                forecastByModule: appLang.getAppListStrings(\'moduleList\')[forecastBy],
                forecastByModuleSingular: appLang.getAppListStrings(\'moduleListSingular\')[forecastBy]
            };

        this.timeperiodsTitle = appLang.get(\'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS\', \'Forecasts\');
        this.timeperiodsText = appLang.get(\'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS\', \'Forecasts\');
        this.scenariosTitle = appLang.get(\'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS\', \'Forecasts\');
        this.scenariosText = appLang.get(\'LBL_FORECASTS_CONFIG_HELP_SCENARIOS\', \'Forecasts\', forecastByLabels);
        this.rangesTitle = appLang.get(\'LBL_FORECASTS_CONFIG_TITLE_RANGES\', \'Forecasts\');
        this.rangesText = appLang.get(\'LBL_FORECASTS_CONFIG_HELP_RANGES\', \'Forecasts\', forecastByLabels);
        this.forecastByTitle = appLang.get(\'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY\', \'Forecasts\');
        this.forecastByText = appLang.get(\'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY\', \'Forecasts\');
        this.wkstColumnsTitle = appLang.get(\'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS\', \'Forecasts\');
        this.wkstColumnsText = appLang.get(\'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS\', \'Forecasts\');
    },

    /**
     * @inheritdoc
     * @override
     */
    _switchHowToData: function(helpId) {
        switch(helpId) {
            case \'config-timeperiods\':
                this.currentHowToData.title = this.timeperiodsTitle;
                this.currentHowToData.text = this.timeperiodsText;
                break;

            case \'config-ranges\':
                this.currentHowToData.title = this.rangesTitle;
                this.currentHowToData.text = this.rangesText;
                break;

            case \'config-scenarios\':
                this.currentHowToData.title = this.scenariosTitle;
                this.currentHowToData.text = this.scenariosText;
                break;

            case \'config-forecast-by\':
                this.currentHowToData.title = this.forecastByTitle;
                this.currentHowToData.text = this.forecastByText;
                break;

            case \'config-worksheet-columns\':
                this.currentHowToData.title = this.wkstColumnsTitle;
                this.currentHowToData.text = this.wkstColumnsText;
                break;
        }
    }
})
',
    ),
  ),
  'config-drawer' => 
  array (
    'meta' => 
    array (
      'type' => 'config-drawer',
      'components' => 
      array (
        0 => 
        array (
          'layout' => 
          array (
            'components' => 
            array (
              0 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'view' => 'config-header-buttons',
                    ),
                    1 => 
                    array (
                      'layout' => 'config-drawer-content',
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'main-pane',
                  'span' => 8,
                ),
              ),
              1 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                    0 => 
                    array (
                      'view' => 'config-drawer-howto',
                    ),
                  ),
                  'type' => 'simple',
                  'name' => 'side-pane',
                  'span' => 4,
                ),
              ),
              2 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                  ),
                  'type' => 'simple',
                  'name' => 'dashboard-pane',
                  'span' => 4,
                ),
              ),
              3 => 
              array (
                'layout' => 
                array (
                  'components' => 
                  array (
                  ),
                  'type' => 'simple',
                  'name' => 'preview-pane',
                  'span' => 8,
                ),
              ),
            ),
            'type' => 'default',
            'name' => 'sidebar',
            'span' => 12,
          ),
        ),
      ),
      'name' => 'base',
      'span' => 12,
    ),
    'controller' => 
    array (
      'base' => '/*
     * Your installation or use of this SugarCRM file is subject to the applicable
     * terms available at
     * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
     * If you do not agree to all of the applicable terms or do not have the
     * authority to bind the entity as an authorized representative, then do not
     * install or use this SugarCRM file.
     *
     * Copyright (C) SugarCRM Inc. All rights reserved.
     *//*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * @class View.Layouts.Base.ForecastsConfigDrawerLayout
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigDrawerLayout
 * @extends View.Layouts.Base.ConfigDrawerLayout
 */
({
    extendsFrom: \'ConfigDrawerLayout\',

    /**
     * @inheritdoc
     *
     * Checks Forecasts ACLs to see if the User is a system admin
     * or if the user has a developer role for the Forecasts module
     *
     * @override
     */
    _checkModuleAccess: function() {
        var acls = app.user.getAcls().Forecasts,
            isSysAdmin = (app.user.get(\'type\') == \'admin\'),
            isDev = (!_.has(acls, \'developer\'));

        return (isSysAdmin || isDev);
    },

    /**
     * @inheritdoc
     *
     * Checks Forecasts config metadata to see if the correct Sales Stage Won/Lost settings are present
     *
     * @override
     */
    _checkModuleConfig: function() {
        return app.utils.checkForecastConfig();
    }
})
',
    ),
  ),
  '_hash' => 'bf8217c54b042a295a87585108ba6c79',
);

