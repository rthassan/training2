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

initmySugarCharts = function() {

    SUGAR.mySugar.sugarCharts = function() {

        var activeTab = activePage,
            charts = {};

        return {
            loadSugarCharts: function(activeTab) {
                var chartFound = false;

                for (var id in charts[activeTab]) {
                    if (id !== 'undefined') {
                        chartFound = true;
                        loadSugarChart(
                            charts[activeTab][id]['chartId'], charts[activeTab][id]['jsonFilename'], charts[activeTab][id]['css'], charts[activeTab][id]['chartConfig']);
                    }
                }
                //clear charts array
                charts = {};
            },

            addToChartsArrayJson: function(json, activeTab) {
                for (var id in json) {
                    if (json[id]['supported'] === 'true') {
                        SUGAR.mySugar.sugarCharts.addToChartsArray(
                            json[id]['chartId'],
                            json[id]['filename'],
                            json[id]['css'],
                            json[id]['chartConfig'],
                            activeTab
                        );
                    }
                }
            },

            addToChartsArray: function(chartId, jsonFilename, css, chartConfig, activeTab) {
                if (!charts[activeTab]) {
                    charts[activeTab] = {};
                }
                charts[activeTab][chartId] = {};
                charts[activeTab][chartId]['chartId'] = chartId;
                charts[activeTab][chartId]['jsonFilename'] = jsonFilename;
                charts[activeTab][chartId]['css'] = css;
                charts[activeTab][chartId]['chartConfig'] = chartConfig;
            }
        };
    }();
};
