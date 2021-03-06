/*
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
({
    plugins: ['Prettify'],
    className: 'row-fluid',

    initialize: function(options) {
        var self = this,
            request = {
                file: '',
                keys: [],
                page: {},
                page_data: {},
                parent_link: '',
                section: {},
                section_page: false
            },
            main;

        this._super('initialize', [options]);

        // trigger initial close of side bar
        app.events.trigger('app:dashletPreview:close');

        // load up the styleguide css if not already loaded
        //TODO: cleanup styleguide.css and add to main file
        if ($('head #styleguide_css').length === 0) {
            $('<link>')
                .attr({
                    rel: 'stylesheet',
                    href: 'styleguide/assets/css/styleguide.css',
                    id: 'styleguide_css'
                })
                .appendTo('head');
        }

        // load page_data index from metadata (defined in layout/docs.php)
        request.page_data = app.metadata.getLayout(this.module, 'docs').page_data;
        // page_name defined in router
        request.file = this.context.get('page_name');
        if (!_.isUndefined(request.file) && !_.isEmpty(request.file)) {
            request.keys = request.file.split('-');
        }
        if (request.keys.length) {
            // get page content variables from page_data
            if (request.keys[0] === 'index') {
                if (request.keys.length > 1) {
                    // this is a section index call
                    request.section = request.page_data[request.keys[1]];
                } else {
                    // this is the home index call
                    request.section = request.page_data[request.keys[0]];
                }
                request.section_page = true;
                request.file = 'index';
            } else if (request.keys.length > 1) {
                // this is a section page call
                request.section = request.page_data[request.keys[0]];
                request.page = request.section.pages[request.keys[1]];
                request.parent_link = '-' + request.keys[0];
                window.prettyPrint && prettyPrint();
            } else {
                // this is a general page call
                request.section = request.page_data[request.keys[0]];
            }
        }

        // load up the page view into the component array
        main = this.getComponent('main-pane');
        main._addComponentsFromDef([{
            view: 'docs-' + request.file,
            context: {
                module: 'Styleguide',
                skipFetch: true,
                request: request
            }
        }]);

        this.render();
    },

    _placeComponent: function(component) {
        if (component.meta.name) {
            this.$("." + component.meta.name).append(component.$el);
        }
    }
})
