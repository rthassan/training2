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
/*global AdamShape, jCore, $, AdamActivityContainerBehavior, AdamActivityResizeBehavior,
 Action, translate, AdamMarker, CommandDefaultFlow,
 AdamShapeMarkerCommand, AdamShapeLayerCommand, RestProxy, SUGAR_URL, Window, PMSE_DESIGNER_FORM_TRANSLATIONS,
 Form, ItemMatrixField, HiddenField, LabelField, TextField, ComboboxField, adamUID,
 CheckboxField, CommandAdam, ItemUpdaterField, PROJECT_MODULE, FieldOption, MessagePanel, RestClient,
 NumberField, CheckboxGroup
 */

/**
 * @class AdamActivity
 * Handle BPMN Activities (Tasks)
 * @extend AdamShape
 *
 * @constructor
 * Create a new Activity Object
 * @param {Object} options
 */
var AdamActivity = function (options) {
    AdamShape.call(this, options);
    /**
     * Activity Alphanumeric unique identifier
     * @type {String}
     */
    this.act_uid = null;
    /**
     * Activity Type
     * Values accepted for SugarCRM Module: TASK
     * @type {String}
     */
    this.act_type = null;
    /**
     * Define if the task is for compensation (BPMN)
     * @type {Boolean}
     */
    this.act_is_for_compensation = null;
    /**
     * Define the quantity needed to start the activity
     * @type {Number}
     */
    this.act_start_quantity = null;
    /**
     * Define the quantity needed to complete the activity
     * @type {Number}
     */
    this.act_completion_quantity = null;
    /**
     * Define the task type.
     * For SugarCRM module only support: USERTASK
     * @type {String}
     */
    this.act_task_type = null;
    /**
     * Define the activity implementation
     * @type {String}
     */
    this.act_implementation = null;
    /**
     * Define the instatiation status
     * @type {Boolean}
     */
    this.act_instantiate = null;
    /**
     * Define the script type supported
     * @type {String}
     */
    this.act_script_type = null;
    /**
     * Define the script
     * @type {String}
     */
    this.act_script = null;
    /**
     * Defines the loop type accepted
     * @type {String}
     */
    this.act_loop_type = null;
    /**
     * Define if the test to complete the loop would be executed before o later
     * @type {Boolean}
     */
    this.act_test_before = null;
    /**
     * Defines the maximum value of loops allowed
     * @type {Number}
     */
    this.act_loop_maximum = null;
    /**
     * Defines the loop condition
     * @type {String}
     */
    this.act_loop_condition = null;
    /**
     * Defines the loop cardinality
     * @type {String}
     */
    this.act_loop_cardinality = null;
    /**
     * Defines the loop behavior
     * @type {String}
     */
    this.act_loop_behavior = null;
    /**
     * Define if the activity has an adhoc behavior
     * @type {Boolean}
     */
    this.act_is_adhoc = null;
    /**
     * Defines if the activity is collapsed
     * @type {Boolean}
     */
    this.act_is_collapsed = null;
    /**
     * Defines the condition needed to complete the activity
     * @type {String}
     */
    this.act_completion_condition = null;
    /**
     * Define the order to be executed when exists several task in parallel mode
     * @type {Number}
     */
    this.act_ordering = null;
    /**
     * Defines if into a loop all instances would be cancelled
     * @type {Boolean}
     */
    this.act_cancel_remaining_instances = null;
    /**
     * Defines the protocol used for the transaction activities
     * @type {String}
     */
    this.act_protocol = null;
    /**
     * Define the method to be used when activity consume/execute a web service
     * @type {String}
     */
    this.act_method = null;
    /**
     * Define the scope of the activity
     * @type {Boolean}
     */
    this.act_is_global = null;
    /**
     * Define the referer to another object (Process, Participant or Another Activity)
     * @type {String}
     */
    this.act_referer = null;
    /**
     * Defines the default flow when activity is related to two or more flows
     * @type {String}
     */
    this.act_default_flow = null;
    /**
     * Defines the diagram related when activity plays as subprocess
     * @type {String}
     */
    this.act_master_diagram = null;
    /**
     * Array of Boundary places created to receive boundary events
     * @type {Array}
     */
    this.boundaryPlaces = new jCore.ArrayList();
    /**
     * Array of Boundary events attached to this activity
     * @type {Array}
     */
    this.boundaryArray = new jCore.ArrayList();

    AdamActivity.prototype.initObject.call(this, options);
};

/**
 * Point the prototype to the AdamShape Object
 * @type {AdamShape}
 */
AdamActivity.prototype = new AdamShape();

/**
 * Define the Object Type
 * @type {String}
 */
AdamActivity.prototype.type = 'AdamActivity';
/**
 * Points to container behavior object
 * @type {Object}
 */
AdamActivity.prototype.activityContainerBehavior = null;
/**
 * Points to the resize behavior object
 * @type {Object}
 */
AdamActivity.prototype.activityResizeBehavior = null;

/**
 * Initialize object with default values
 * @param options
 */
AdamActivity.prototype.initObject = function (options) {
    var defaults = {
        act_type: 'TASK',
        act_loop_type: 'NONE',
        act_is_for_compensation: false,
        act_task_type: 'EMPTY',
        act_is_collapsed: false,
        act_is_global: false,
        act_loop_cardinality: 0,
        act_loop_maximum: 0,
        act_start_quantity: 1,
        act_is_adhoc: false,
        act_cancel_remaining_instances: true,
        act_instantiate: false,
        act_completion_quantity: 0,
        act_implementation: '',
        act_script: '',
        act_script_type: '',
        act_default_flow: 0,
        minHeight: 50,
        minWidth: 100,
        maxHeight: 500,
        maxWidth: 600
    };
    $.extend(true, defaults, options);
    this.setActivityUid(defaults.act_uid)
        .setActivityType(defaults.act_type)
        .setLoopType(defaults.act_loop_type)
        .setIsForCompensation(defaults.act_is_for_compensation)
        .setTaskType(defaults.act_task_type)
        .setIsCollapsed(defaults.act_is_collapsed)
        .setIsGlobal(defaults.act_is_global)
        .setLoopCardinality(defaults.act_loop_cardinality)
        .setLoopMaximun(defaults.act_loop_maximum)
        .setStartQuantity(defaults.act_start_quantity)
        .setIsAdhoc(defaults.act_is_adhoc)
        .setCancelRemainingInstances(defaults.act_cancel_remaining_instances)
        .setInstantiate(defaults.act_instantiate)
        .setImplementation(defaults.act_implementation)
        .setCompletionQuantity(defaults.act_completion_quantity)
        .setScript(defaults.act_script)
        .setScriptType(defaults.act_script_type)
        .setDefaultFlow(defaults.act_default_flow)
        .setMinHeight(defaults.minHeight)
        .setMinWidth(defaults.minWidth)
        .setMaxHeight(defaults.maxHeight)
        .setMaxWidth(defaults.maxWidth);
    if (defaults.act_name) {
        this.setName(defaults.act_name);
    }
    if (defaults.markers) {
        this.addMarkers(defaults.markers, this);
    }
};

/**
 * Returns the activity type property
 * @return {String}
 */
AdamActivity.prototype.getActivityType = function () {
    return this.act_type;
};

/**
 * Returns the is for compensation property
 * @return {Boolean}
 */
AdamActivity.prototype.getIsForCompensation = function () {
    return this.act_is_for_compensation;
};

/**
 * Returns if the activity cancel remaining instances when is cancelled
 * @return {Boolean}
 */
AdamActivity.prototype.getCancelRemainingInstances = function () {
    return this.act_cancel_remaining_instances;
};

/**
 * Returns the quantity needed to complete an activity
 * @return {Number}
 */
AdamActivity.prototype.getCompletionQuantity = function () {
    return this.act_completion_quantity;
};

/**
 * Set is the activity is global (scope)
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.getIsGlobal = function () {
    return this.act_is_global;
};

/**
 * Returns the start quantity needed to start an activity
 * @return  {Number}
 */
AdamActivity.prototype.getStartQuantity = function () {
    return this.act_start_quantity;
};

/**
 * Returns if the instance is active
 * @return {Boolean}
 */
AdamActivity.prototype.getInstantiate = function () {
    return this.act_instantiate;
};

/**
 * Returns the implementation property
 * @return {String}
 */
AdamActivity.prototype.getImplementation = function () {
    return this.act_implementation;
};

/**
 * Return the Script property
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.getScript = function () {
    return this.act_script;
};

/**
 * Return the Script Type property
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.getScriptType = function () {
    return this.act_script_type;
};

/**
 * Return the minimun height of an activity
 * @return {*}
 */
AdamActivity.prototype.getMinHeight = function () {
    return this.minHeight;
};

/**
 * Return the minimun width of an activity
 * @return {*}
 */
AdamActivity.prototype.getMinWidth = function () {
    return this.minWidth;
};
/**
 * Return the maximun height of an activity
 * @return {*}
 */
AdamActivity.prototype.getMaxHeight = function () {
    return this.maxHeight;
};

/**
 * Return the maximun width of an activity
 * @return {*}
 */
AdamActivity.prototype.getMaxWidth = function () {
    return this.maxWidth;
};
/**
 * Sets the act_uid property
 * @param {String} value
 * @return {*}
 */
AdamActivity.prototype.setActivityUid = function (value) {
    this.act_uid = value;
    return this;
};

/**
 * Sets the activity type property
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.setActivityType = function (type) {
    this.act_type = type;
    return this;
};

/**
 * Sets the implementation property
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.setImplementation = function (type) {
    this.act_implementation = type;
    return this;
};

/**
 * Set the loop type property
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.setLoopType = function (type) {
    this.act_loop_type = type;
    return this;
};

/**
 * Sets the collapsed property
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setIsCollapsed = function (value) {
    if (_.isBoolean(value)) {
        this.act_is_collapsed = value;
    }
    return this;
};

/**
 * Sets the is for compensation property
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setIsForCompensation = function (value) {
    if (_.isBoolean(value)) {
        this.act_is_for_compensation = value;
    }
    return this;
};

/**
 * Sets the activity task type
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.setTaskType = function (type) {
    this.act_task_type = type;
    return this;
};

/**
 * Set is the activity is global (scope)
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setIsGlobal = function (value) {
    if (_.isBoolean(value)) {
        this.act_is_global = value;
    }
    return this;
};

/**
 * Set the loop cardinality of the activity
 * @param {String} value
 * @return {*}
 */
AdamActivity.prototype.setLoopCardinality = function (value) {
    this.act_loop_cardinality = value;
    return this;
};

/**
 * Sets the loop maximun value
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setLoopMaximun = function (value) {
    this.act_loop_maximum = value;
    return this;
};

/**
 * Sets the start quantity needed to start an activity
 * @param  {Number} value
 * @return {*}
 */
AdamActivity.prototype.setStartQuantity = function (value) {
    this.act_start_quantity = value;
    return this;
};

/**
 * Sets if the activity has an adhoc behavior
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setIsAdhoc = function (value) {
    if (_.isBoolean(value)) {
        this.act_is_adhoc = value;
    }
    return this;
};

/**
 * Sets if the activity cancel remaining instances when is cancelled
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setCancelRemainingInstances = function (value) {
    if (_.isBoolean(value)) {
        this.act_cancel_remaining_instances = value;
    }
    return this;
};

/**
 * Sets if the instance is active
 * @param {Boolean} value
 * @return {*}
 */
AdamActivity.prototype.setInstantiate = function (value) {
    if (_.isBoolean(value)) {
        this.act_instantiate = value;
    }
    return this;
};

/**
 * Sets the quantity needed to complete an activity
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setCompletionQuantity = function (value) {
    this.act_completion_quantity = value;
    return this;
};

/**
 * Sets the Script property
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setScript = function (value) {
    this.act_script = value;
    return this;
};

/**
 * Sets the Script Type property
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setScriptType = function (value) {
    this.act_script_type = value;

    return this;
};

/**
 * Sets te default_flow property
 * @param value
 * @return {*}
 */
AdamActivity.prototype.setDefaultFlow = function (value) {
    if (this.html) {
        AdamShape.prototype.setDefaultFlow.call(this, value);
        this.canvas.triggerCommandAdam(this, ['act_default_flow'], [this.act_default_flow], [value]);
    }
    this.act_default_flow = value;
    return this;
};
/**
 * Sets the minimun height
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setMinHeight = function (value) {
    this.minHeight = value;
    return this;
};

/**
 * Sets the minimun with
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setMinWidth = function (value) {
    this.minWidth = value;

    return this;
};
/**
 * Sets the maximun height
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setMaxHeight = function (value) {
    this.maxHeight = value;
    return this;
};

/**
 * Sets the maximun with
 * @param {Number} value
 * @return {*}
 */
AdamActivity.prototype.setMaxWidth = function (value) {
    this.maxWidth = value;

    return this;
};
/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
AdamActivity.prototype.getDBObject = function () {
    var name = this.getName();
    return {
        act_uid: this.act_uid,
        act_name: name,
        act_type: this.act_type,
        act_task_type: this.act_task_type,
        act_is_for_compensation: this.act_is_for_compensation,
        act_start_quantity: this.act_start_quantity,
        act_completion_quantity: this.act_completion_quantity,
        act_implementation: this.act_implementation,
        act_instantiate: this.act_instantiate,
        act_script_type: this.act_script_type,
        act_script: this.act_script,
        act_loop_type: this.act_loop_type,
        act_test_before: this.act_test_before,
        act_loop_maximum: this.act_loop_maximum,
        act_loop_condition: this.act_loop_condition,
        act_loop_cardinality: this.act_loop_cardinality,
        act_loop_behavior: this.act_loop_behavior,
        act_is_adhoc: this.act_is_adhoc,
        act_is_collapsed: this.act_is_collapsed,
        act_completion_condition: this.act_completion_condition,
        act_ordering: this.act_ordering,
        act_cancel_remaining_instances: this.act_cancel_remaining_instances,
        act_protocol: this.act_protocol,
        act_method: this.act_method,
        act_is_global: this.act_is_global,
        act_referer: this.act_referer,
        act_default_flow: this.act_default_flow,
        act_master_diagram: this.act_master_diagram,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: 'bpmnDiagram',
        element_id: this.canvas.dia_id
    };
};

AdamActivity.prototype.getMarkers = function () {
    return this.markersArray;
};

/**
 * Factory function to handle several container behavior elements
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.containerBehaviorFactory = function (type) {
    var out;
    if (type === 'activity') {
        if (!this.activityContainerBehavior) {
            this.activityContainerBehavior = new AdamActivityContainerBehavior();
        }
        out = this.activityContainerBehavior;
    } else {
        out = AdamShape.prototype.containerBehaviorFactory.call(this, type);
    }
    return out;
};

/**
 * Factory function to handle several resize behavior elements
 * @param {String} type
 * @return {*}
 */
AdamActivity.prototype.resizeBehaviorFactory = function (type) {
    var out;
    if (type === 'activityResize') {
        if (!this.activityResizeBehavior) {
            this.activityResizeBehavior = new AdamActivityResizeBehavior();
        }
        out = this.activityResizeBehavior;
    } else {
        out = AdamShape.prototype.resizeBehaviorFactory.call(this, type);
    }
    return out;
};

/**
 * Add adam custom css classes to the HTML
 * @return {*}
 */
AdamActivity.prototype.createHTML = function () {
    jCore.CustomShape.prototype.createHTML.call(this);
    this.style.addClasses(['adam_activity', "adam_droppable"]);
    return this.html;
};

/**
 * Create/Initialize the boundary places array
 * @return {*}
 */
AdamActivity.prototype.makeBoundaryPlaces = function () {
    var bouX,
        bouY,
        factor = 3,
        space,
        number = 0,
        shape = this.boundaryArray.getFirst(),
        numBottom = 0,
        numLeft = 0,
        numTop = 0,
        numRight = 0;

    //BOTTON
    bouY = shape.parent.getHeight() - shape.getHeight() / 2; // Y is Constant
    bouX = shape.parent.getWidth() - (numBottom + 1) * (shape.getWidth() + factor);
    while (bouX + shape.getWidth() / 2 > 0) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'BOTTOM';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numBottom += 1;
        bouX = shape.parent.getWidth() - (numBottom + 1) * (shape.getWidth() + factor);
    }

    //LEFT
    bouY = shape.parent.getHeight() - (numLeft + 1) * (shape.getHeight() + factor);
    bouX = -shape.getHeight() / 2;   // X is Constant
    while (bouY + shape.getHeight() / 2 > 0) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'LEFT';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numLeft += 1;
        bouY = shape.parent.getHeight() - (numLeft + 1) * (shape.getHeight() + factor);
    }

    //TOP
    bouY = -shape.getWidth() / 2; // X is Constant
    bouX = numTop * (shape.getWidth() + factor);
    while (bouX + shape.getWidth() / 2 < shape.parent.getWidth()) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'TOP';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numTop += 1;
        bouX = numTop * (shape.getWidth() + factor);
    }

    //RIGHT
    bouY = numRight * (shape.getHeight() + factor);
    bouX = shape.parent.getWidth() - shape.getWidth() / 2; // Y is Constant
    while (bouY + shape.getHeight() / 2 < shape.parent.getHeight()) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'RIGHT';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numRight += 1;
        bouY = numRight * (shape.getHeight() + factor);
    }
    return this;
};

/**
 * Sets the boundary element to a selected boundary place
 * @param {AdamEvent} shape
 * @param {Number} number
 * @return {*}
 */
AdamActivity.prototype.setBoundary = function (shape, number) {
    var bouPlace = this.boundaryPlaces.get(number);
    bouPlace.available = false;
    shape.setPosition(bouPlace.x, bouPlace.y);
    return this;
};

/**
 * Returns the current place available to attach boundary events.
 * Retuns false if there's not place available
 * @return {Number/Boolean}
 */
AdamActivity.prototype.getAvailableBoundaryPlace = function () {
    var place = 0,
        bouPlace,
        sw = true,
        i;
    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        bouPlace = this.boundaryPlaces.get(i);
        if (bouPlace.available && sw) {
            place = bouPlace.number;
            sw = false;
        }
    }
    if (sw) {
        place = false;
    }
    return place;
};

/**
 * Update Boundary Places Array
 * @return {*}
 */
AdamActivity.prototype.updateBoundaryPlaces = function () {
    var i,
        aux,
        k = 0;
    aux =  new jCore.ArrayList();
    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        aux.insert(this.boundaryPlaces.get(i));
    }

    this.boundaryPlaces.clear();
    this.makeBoundaryPlaces();

    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        if (k < aux.getSize()) {
            this.boundaryPlaces.get(i).available = aux.get(k).available;
            k += 1;
        }
    }
    return this;
};

/**
 * Returns the number of boundary events attached to this activity
 * @return {Number}
 */
AdamActivity.prototype.getNumberOfBoundaries = function () {
    var child,
        i,
        bouNum = 0;

    for (i = 0; i < this.getChildren().getSize(); i += 1) {
        child = this.getChildren().get(i);
        if (child.getType() === 'AdamEvent' && child.evn_type === 'BOUNDARY') {
            bouNum = bouNum + 1;
        }
    }
    return bouNum;
};

/**
 * Update boundary positions when exists a change into the boundary array
 * @param {Boolean} createIntersections
 */
AdamActivity.prototype.updateBoundaryPositions = function (createIntersections) {
    var child,
        port,
        i,
        j;

    if (this.getNumberOfBoundaries() > 0) {

        this.updateBoundaryPlaces();
        for (i = 0; i < this.getChildren().getSize(); i += 1) {
            child = this.getChildren().get(i);
            if (child.getType() === 'AdamEvent'
                && child.evn_type === 'BOUNDARY') {
                child.setPosition(this.boundaryPlaces.get(child.numberRelativeToActivity).x,
                    this.boundaryPlaces.get(child.numberRelativeToActivity).y
                );
                for (j = 0; j < child.ports.getSize(); j += 1) {
                    port = child.ports.get(j);
                    port.setPosition(port.x, port.y);
                    port.connection.disconnect().connect();
                    if (createIntersections) {
                        port.connection.setSegmentMoveHandlers();
                        port.connection.checkAndCreateIntersectionsWithAll();
                    }
                }
            }
        }
    }
};

/**
 * Adds markers to the arrayMarker property
 * @param {Array} markers
 * @param {AdamShape} parent
 * @return {*}
 */
AdamActivity.prototype.addMarkers = function (markers, parent) {
    var newMarker, i, factoryMarker;
    if (_.isArray(markers)) {
        for (i = 0; i < markers.length; i += 1) {
            factoryMarker = markers[i];
            factoryMarker.parent = parent;
            factoryMarker.canvas = parent.canvas;
            newMarker = new AdamMarker(factoryMarker);
            this.markersArray.insert(newMarker);
        }
    }
    return this;
};

/**
 * Paint the shape
 */
AdamActivity.prototype.paint = function () {
    var m, marker;
    AdamShape.prototype.paint.call(this);
    for (m = 0; m < this.markersArray.getSize(); m += 1) {
        marker = this.markersArray.get(m);
        marker.paint();
    }
};


AdamActivity.prototype.getActivityType = function () {
    return this.act_type;
};

AdamActivity.prototype.getContextMenu = function () {
    var self = this,
        deleteAction,
        usertaskAction,
        scriptAction,
        configureAction,
        assignUsersAction,
        elements = this.getDestElements(),
        defaultflowActive = (elements.length > 1) ? false : true,
        defaultflownoneAction,
        defaultflowItems = [],
        name,
        items,
        i,
        shape,
        handle,
        port,
        connection,
        actionItems = [],
        noneAction,
        assignUserAction,
        assignTeamAction,
        changeFieldAction,
        addRelatedRecordAction,
        businessRuleAction,
        defaultflowAction;
    deleteAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_DELETE'),
        cssStyle: 'adam-menu-icon-delete',
        handler: function () {
            var shape;
            shape = self.canvas.customShapes.find('id', self.id);
            if (shape) {
                shape.canvas.emptyCurrentSelection();
                shape.canvas.addToSelection(shape);
                shape.canvas.removeElements();
            }
        }
    });

    noneAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_UNASSIGNED'),
        cssStyle: 'adam-menu-script-none',
        handler: function () {
            self.updateScriptType('NONE');
        },
        disabled: (this.act_script_type === 'NONE')
    });

    assignUserAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_ASSIGN_USER'),
        cssStyle: 'adam-menu-script-assign_user',
        handler: function () {
            self.updateScriptType('ASSIGN_USER');
        },
        disabled: (this.act_script_type === 'ASSIGN_USER')
    });

    assignTeamAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_ASSIGN_TEAM'),
        cssStyle: 'adam-menu-script-assign_team',
        handler: function () {
            self.updateScriptType('ASSIGN_TEAM');
        },
        disabled: (this.act_script_type === 'ASSIGN_TEAM')
    });

    changeFieldAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_CHANGE_FIELD'),
        cssStyle: 'adam-menu-script-change_field',
        handler: function () {
            self.updateScriptType('CHANGE_FIELD');
        },
        disabled: (this.act_script_type === 'CHANGE_FIELD')
    });

    addRelatedRecordAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_ADD_RELATED_RECORD'),
        cssStyle: 'adam-menu-script-add_related_record',
        handler: function () {
            self.updateScriptType('ADD_RELATED_RECORD');
        },
        disabled: (this.act_script_type === 'ADD_RELATED_RECORD')
    });

    businessRuleAction = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_BUSINESS_RULE'),
        cssStyle: 'adam-menu-script-business_rule',
        handler: function () {
            self.updateScriptType('BUSINESS_RULE');
        },
        disabled: (this.act_script_type === 'BUSINESS_RULE')
    });

    if (this.act_task_type === 'USERTASK') {
        configureAction = this.createConfigurateAction();
    } else {
        configureAction = this.actionFactory(this.act_script_type);
    }
    assignUsersAction = this.createAssignUsersAction();

    if (elements.length > 1) {
        handle  = function (id) {
            return function () {
                var cmd = new CommandDefaultFlow(self, id);
                cmd.execute();
                self.canvas.commandStack.add(cmd);
            };
        };
        defaultflownoneAction = new Action({
            text: translate('LBL_PMSE_CONTEXT_MENU_NONE'),
            cssStyle : 'adam-menu-icon-none',
            handler: handle(""),
            disabled: (self.act_default_flow !== 0) ? false : true
        });

        defaultflowItems.push(defaultflownoneAction);

        for (i = 0; i < this.getPorts().getSize(); i += 1) {
            port = this.getPorts().get(i);
            connection = port.connection;
            if (connection.srcPort.parent.getID() === this.getID()) {
                shape = connection.destPort.parent;

                switch (shape.getType()) {
                    case 'AdamActivity':
                        name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_TASK');
                        break;
                    case 'AdamEvent':
                        name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_EVENT');
                        break;
                    case 'AdamGateway':
                        name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_GATEWAY');
                        break;
                }
                defaultflowItems.push(
                    new Action({
                        text: name,
                        cssStyle : self.getCanvas().getTreeItem(shape).icon,
                        handler: handle(connection.getID()),
                        disabled: (self.act_default_flow === connection.getID()) ? true : false
                    })
                );

            }
        }

        defaultflowAction = {
            label: translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_FLOW'),
            icon: 'adam-menu-icon-default-flow',
            disabled: defaultflowActive,
            menu: {
                items: defaultflowItems
            }
        };
    }

    items = [configureAction];
    if (this.act_task_type === 'USERTASK') {
        items.push({jtype: 'separator'}, assignUsersAction);
    }
    items.push({jtype: 'separator'});
    if (this.act_task_type === 'SCRIPTTASK') {
        actionItems.push(noneAction);
        actionItems.push(businessRuleAction);
        actionItems.push(assignUserAction, assignTeamAction, changeFieldAction, addRelatedRecordAction);
        items.push(
            {
                label: translate('LBL_PMSE_CONTEXT_MENU_ACTION_TYPE'),
                icon : 'adam-menu-icon-convert',
                menu: {
                    items: actionItems
                }
            },
            {
                jtype: 'separator'
            }
        );
    }

    if (elements.length > 1  && this.act_task_type === 'USERTASK') {
        items.push(
            defaultflowAction,
            {
                jtype: 'separator'
            },
            deleteAction
        );
    } else {
        items.push(
            deleteAction
        );
    }

    return {
        items: items
    };
};

AdamActivity.prototype.updateDefaultFlow = function (destID) {
    this.act_default_flow = destID;
    return this;
};

AdamActivity.prototype.updateTaskType = function (newType) {
    var updateCommand, marker;

    marker = this.getMarkers().get(0);

    updateCommand = new AdamShapeMarkerCommand(
        this,
        {
            markers: [marker],
            type: 'changeactivitymarker',
            changes: {
                taskType: newType
            }
        }
    );

    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

AdamActivity.prototype.updateScriptType = function (newType) {

    var layer,
        updateCommand;

    layer = this.getLayers().get(1);
    updateCommand = new AdamShapeLayerCommand(
        this,
        {
            layers: [layer],
            type: 'changescripttypeactivity',
            changes: newType
        }
    );
    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

/**
 *  Extend applyZoom of CustomShape for apply Zoom into Markers
 *  @return {*}
 */
AdamActivity.prototype.applyZoom = function () {
    var i, marker;
    AdamShape.prototype.applyZoom.call(this);
    for (i = 0; i < this.markersArray.getSize(); i += 1) {
        marker = this.markersArray.get(i);
        marker.applyZoom();
    }
    return this;
};

AdamActivity.prototype.createConfigurateAction = function () {
    var action, disabled = false, w, f, f2, root = this, proxy, wWidth = 510, wHeight = 150, items,
        callback, self = this, actionName = translate('LBL_PMSE_CONTEXT_MENU_FORMS'), formsField, actionCSS, responseButtons,
        assignTypeField, assignTeamField, labelAssigment, radioNone, radioReassigment, radioAdhoc,
        combo_teams, combo_teams_1, combo_type, reassignCheck, adhocCheck, itemMatrix, requiredFields, requiredForm, relatedForm,
        updateExpectedTime,
        expectedTimeField,
        expTimeDuration,
        expTimeCombo,
        itemsF3,
        f3,
        reassignmentFn,
        forms,
        teams,
        cancelInformation;
    cancelInformation =  new MessagePanel({
        title: "Confirm",
        wtype: 'Confirm',
        message: translate('LBL_PMSE_MESSAGE_CANCEL_CONFIRM')
    });
    w = new Window({
        width: wWidth,
        height: this.act_task_type === 'USERTASK' ? 340 : wHeight,
        modal: true,
        title: translate('LBL_PMSE_FORM_TITLE_ACTIVITY') + ': ' + this.getName()
    });

    if (this.act_task_type === 'USERTASK') {
        actionCSS = 'adam-menu-icon-form';
        proxy = new SugarProxy({
            url: 'pmse_Project/ActivityDefinition/' + this.id,
            uid: this.id,
            callback: null,
            data2: {'hola':'hola'}
        });

        itemMatrix = new ItemMatrixField({
            jtype: 'itemmatrix',
            label: translate('LBL_PMSE_FORM_LABEL_READ_ONLY_FIELDS'),
            name: 'act_readonly_fields',
            submit: true,
            fieldWidth: 350,
            fieldHeight: 200,
            visualStyle : 'table',
            nColumns: 2
        });

        f2 = new Form({
            items: [ itemMatrix ],
            closeContainerOnSubmit: true,
            labelWidth: '16%',
            buttons: [
                {
                    jtype: 'submit',
                    caption: translate('LBL_PMSE_BUTTON_SAVE'),
                    cssClasses: ['btn', 'btn-primary']
                },
                {
                    jtype: 'normal',
                    caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                    handler: function () {
                        if (f2.isDirty()) {
                            cancelInformation.setButtons([
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        cancelInformation.hide();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.hide();
                                    }
                                }
                            ]);
                            cancelInformation.show();
                        } else {
                            w.close();
                        }
                    },
                    cssClasses: ['btn btn-invisible btn-link']
                }
            ],
            language: PMSE_DESIGNER_FORM_TRANSLATIONS
        });

        requiredFields = new ItemMatrixField({
            jtype: 'itemmatrix',
            label: translate('LBL_PMSE_FORM_LABEL_REQUIRED_FIELDS'),
            name: 'act_required_fields',
            submit: true,
            fieldWidth: 350,
            fieldHeight: 200,
            visualStyle : 'table',
            nColumns: 2
        });
        requiredForm = new Form({
            items: [ requiredFields ],
            closeContainerOnSubmit: true,
            labelWidth: '16%',
            buttons: [
                {
                    jtype: 'submit',
                    caption: translate('LBL_PMSE_BUTTON_SAVE'),
                    cssClasses: ['btn btn-primary']
                },
                {
                    jtype: 'normal',
                    caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                    handler: function () {
                        if (f2.isDirty()) {
                            cancelInformation.setButtons([
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        cancelInformation.hide();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.hide();
                                    }
                                }
                            ]);
                            cancelInformation.show();
                        } else {
                            w.close();
                        }
                    },
                    cssClasses: ['btn btn-invisible btn-link']
                }
            ]
        });

        relatedForm = new Form({
            closeContainerOnSubmit: true,
            labelWidth: '100%',
            buttons: [
                {
                    jtype: 'submit',
                    caption: translate('LBL_PMSE_BUTTON_SAVE'),
                    cssClasses: ['btn btn-primary']
                },
                {
                    jtype: 'normal',
                    caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                    handler: function () {
                        if (f2.isDirty()) {
                            cancelInformation.setButtons([
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        cancelInformation.hide();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.hide();
                                    }
                                }
                            ]);
                            cancelInformation.show();
                        } else {
                            w.close();
                        }
                    },
                    cssClasses: ['btn btn-invisible btn-link']
                }
            ]
        });

        expectedTimeField = new HiddenField({
            name: 'act_expected_time'
        });

        updateExpectedTime = function () {
            var out = {
                time: '',
                unit: ''
            };
            out.time = expTimeDuration.value;
            out.unit = expTimeCombo.value;
            expectedTimeField.setValue(out);
        };

        expTimeDuration = new NumberField(
            {
                name: 'evn_criteria',
                label: translate('LBL_PMSE_FORM_LABEL_DURATION'),
                helpTooltip: {
                    message: translate('LBL_PMSE_FORM_TOOLTIP_DURATION')
                },
                fieldWidth: '50px',
                submit: false,
                change: updateExpectedTime
            }
        );

        expTimeCombo = new ComboboxField({
            name: 'evn_params',
            label: translate('LBL_PMSE_FORM_LABEL_UNIT'),
            options: [
                { text: translate('LBL_PMSE_FORM_OPTION_DAYS'), value: 'day'},
                { text: translate('LBL_PMSE_FORM_OPTION_HOURS'), value: 'hour'},
                { text: translate('LBL_PMSE_FORM_OPTION_MINUTES'), value: 'minute'}
            ],
            initialValue: 'hour',
            submit: false,
            change: updateExpectedTime
        });

        itemsF3 = [
            expectedTimeField,
            expTimeDuration,
            expTimeCombo
        ];

        f3 = new Form({
            items: itemsF3,
            closeContainerOnSubmit: true,
            buttons: [
                {
                    jtype: 'submit',
                    caption: translate('LBL_PMSE_BUTTON_SAVE'),
                    cssClasses: ['btn btn-primary']
                },
                {
                    jtype: 'normal',
                    caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                    handler: function () {
                        if (f3.isDirty()) {
                            cancelInformation.setButtons([
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        cancelInformation.hide();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.hide();
                                    }
                                }
                            ]);
                            cancelInformation.show();
                        } else {
                            w.close();
                        }
                    },
                    cssClasses: ['btn btn-invisible btn-link']
                }
            ],
            language: PMSE_DESIGNER_FORM_TRANSLATIONS
        });

        reassignmentFn = function () {
            switch (this.name) {
                case 'combo_teams':
                    assignTeamField.setValue(combo_teams.value);
                    break;
                case 'combo_teams_1':
                    assignTeamField.setValue(combo_teams_1.value);
                    break;
            }
        };

        formsField = new ComboboxField({
            name: 'act_type',
            label: translate('LBL_PMSE_FORM_LABEL_FORM_TYPE'),
            required: false,
            proxy: new SugarProxy({
                url: 'pmse_Project/CrmData/dynaforms/' + adamUID,
                uid: adamUID,
                callback: null
            })
        });

        responseButtons = new ComboboxField({
            name: 'act_response_buttons',
            label: translate('LBL_PMSE_FORM_LABEL_RESPONSE_BUTTONS'),
            required : false
        });

        labelAssigment  = new LabelField({
            name: 'lblAssigment',
            label: translate('LBL_PMSE_FORM_LABEL_OTHER_DERIVATION_OPTIONS'),
            options: {
                marginLeft : 35
            }
        });

        reassignCheck = new CheckboxField({
            name: 'act_reassign',
            label: translate('LBL_PMSE_FORM_LABEL_RECORD_OWNERSHIP'),
            required: false,
            value: false,
            options: {
                labelAlign: 'right',
                marginLeft: 80
            },
            change : function () {
                if ($(reassignCheck.html).children('input').is(':checked')) {
                    combo_teams.setReadOnly(false);
                } else {
                    combo_teams.setReadOnly(true);
                }
            }
        });

        combo_teams = new ComboboxField({
            name: 'act_reassign_team',
            label: translate('LBL_PMSE_FORM_LABEL_TEAM'),
            required: false,
            readOnly: true,
            change: reassignmentFn,
            proxy: new SugarProxy({
                url: 'pmse_Project/CrmData/teams/reassign',
                uid: 'reassign',
                callback: null
            })
        });

        adhocCheck = new CheckboxField({
            name: 'act_adhoc',
            label: translate('LBL_PMSE_FORM_LABEL_REASSIGN'),
            required: false,
            value: false,
            options: {
                labelAlign: 'right',
                marginLeft: 80
            },
            change : function () {
                if ($(adhocCheck.html).children('input').is(':checked')) {
                    combo_teams_1.setReadOnly(false);
                } else {
                    combo_teams_1.setReadOnly(true);
                }
            }
        });

        combo_teams_1 = new ComboboxField({
            name: 'act_adhoc_team',
            label: translate('LBL_PMSE_FORM_LABEL_TEAM'),
            required: false,
            readOnly: true,
            change: reassignmentFn
        });

        combo_type = new ComboboxField({
            name: 'act_adhoc_behavior',
            label: translate('LBL_PMSE_FORM_LABEL_TYPE'),
            required: false,
            readOnly: true
        });

        assignTeamField = new HiddenField({
            name: 'act_adhoc_reassign_team'
        });

        assignTypeField = new HiddenField({
            name: 'act_reassignment_type'
        });

        actTypeField = new HiddenField({
            name: 'act_type'
        });

        items = [/*formsField,*/ responseButtons,
            labelAssigment,
            reassignCheck, combo_teams,
            adhocCheck, combo_teams_1,
            actTypeField
        ];

        callback = {
            'submit': function (data) {
                var f2Data = f2.getData(), f1Data = f.getData(), f3Data = f3.getData(), requiredData = requiredForm.getData(), relatedData = relatedForm.getData();
                f2Data.act_readonly_fields = JSON.parse(f2Data.act_readonly_fields);
                requiredData.act_required_fields = JSON.parse(requiredData.act_required_fields);

                $.extend(true, f1Data, f2Data);
                $.extend(true, f1Data, f3Data);
                $.extend(true, f1Data, requiredData);

                proxy.sendData(f1Data);
            },
            'loaded': function (data) {


                var aForms = [/*{text: translate('LBL_PMSE_FORM_OPTION_MODULE_ORIGINAL_DETAIL_VIEW'), value: 'DetailView'}, {text: translate('LBL_PMSE_FORM_OPTION_MODULE_ORIGINAL_EDIT_VIEW'), value: 'EditView'}*/],
                    rButtons = [{text: translate('LBL_PMSE_FORM_OPTION_APPROVE_REJECT'), value: 'APPROVE'}, {text: translate('LBL_PMSE_FORM_OPTION_ROUTE'), value: 'ROUTE'}],
                    aType = [{text: translate('LBL_PMSE_FORM_OPTION_ONE_WAY'), value: 'ONE_WAY'}, {text: translate('LBL_PMSE_FORM_OPTION_ROUND_TRIP'), value: 'ROUND_TRIP'}],
                    readOnlyFieldsMatrix = f2.items[0],
                    requiredFieldsMatrix = requiredForm.items[0],
                    i,
                    readOnlyFields = [],
                    requiredFields = [],
                    allTheFields = [],
                    allTheReqFields = [],
                    related,
                    item,
                    relatedItems;

                proxy.getData({'module': PROJECT_MODULE}, {
                    success: function(data) {
                        root.canvas.emptyCurrentSelection();
                        for (i = 0; i < data.act_readonly_fields.length; i += 1) {
                            allTheFields.push({
                                text: data.act_readonly_fields[i].label,
                                value: data.act_readonly_fields[i].name
                            });
                            if (data.act_readonly_fields[i].readonly) {
                                readOnlyFields.push(data.act_readonly_fields[i].name);
                            }
                        }
                        readOnlyFieldsMatrix.getHTML();
                        readOnlyFieldsMatrix.setList(allTheFields, readOnlyFields);
                        // set required fields to form as a list
                        for (i = 0; i < data.act_required_fields.length; i += 1) {
                            allTheReqFields.push({
                                text: data.act_required_fields[i].label,
                                value: data.act_required_fields[i].name
                            });
                            if (data.act_required_fields[i].required) {
                                //readOnlyFields.push(data.act_readonly_fields[i].name);
                                requiredFields.push(data.act_required_fields[i].name);
                            }
                        }
                        requiredFieldsMatrix.getHTML();
                        requiredFieldsMatrix.setList(allTheReqFields, requiredFields);

                        formsField.proxy.getData(null, {
                            success: function(forms) {
                                aForms = aForms.concat(forms.result);
                                formsField.setOptions(aForms);
                            }
                        });

                        combo_teams.proxy.getData(null, {
                            success: function(teams) {
                                combo_teams.setOptions(teams.result);
                                combo_teams_1.setOptions(teams.result);
                                App.alert.dismiss('upload');
                                w.html.style.display = 'inline';
                            }
                        });

                        responseButtons.setOptions(rButtons);
                        combo_type.setOptions(aType);

                        reassignCheck.setValue(false);
                        adhocCheck.setValue(false);
                        if (data) {
                            if (data.act_expected_time) {
                                expTimeDuration.setValue(data.act_expected_time.time);
                                expTimeCombo.setValue(data.act_expected_time.unit);
                                updateExpectedTime();
                            }

                            if (data.act_type) {
                                formsField.setValue(data.act_type);
                                actTypeField.setValue(data.act_type);
                            }
                            if (data.act_response_buttons) {
                                responseButtons.setValue(data.act_response_buttons);
                            }
                            if (data.act_reassign) {
                                if (App.utils.isTruthy(data.act_reassign)) {
                                    reassignCheck.setValue(true);
                                    $(reassignCheck.html).children('input').prop('checked', true);
                                    combo_teams.setReadOnly(false);
                                    if (data.act_reassign_team) {
                                        combo_teams.setValue(data.act_reassign_team);
                                        $(combo_teams.html).children('select').val(data.act_reassign_team);
                                    }

                                }
                            }
                            if (data.act_adhoc) {
                                if (App.utils.isTruthy(data.act_adhoc)) {
                                    adhocCheck.setValue(true);
                                    $(adhocCheck.html).children('input').prop('checked', true);
                                    combo_teams_1.setReadOnly(false);
                                    if (data.act_adhoc_team) {
                                        combo_teams_1.setValue(data.act_adhoc_team);
                                        $(combo_teams_1.html).children('select').val(data.act_adhoc_team);
                                    }
                                }
                            }

                            f.proxy = null;
                        }
                    }
                })
            }
        };

        f2.setCallback({submit: callback.submit});
        f3.setCallback({submit: callback.submit});
        requiredForm.setCallback({submit: callback.submit});
        relatedForm.setCallback({submit: callback.submit});

    } else {
        //TODO REVIEW THIS ELSE
        actionCSS = 'adam-menu-icon-configure';
        proxy = null;
        actionName = 'Configuration...';

        items = [
            {
                jtype: 'textarea',
                required: false,
                fieldWidth: '250px',
                fieldHeight: '100px',
                label: translate('LBL_PMSE_FORM_LABEL_SCRIPT'),
                name: 'act_script',
                helpTooltip: {
                    message: 'Enter the PHP code script'
                }
            }
        ];

        callback = {
            submit: function (data) {
                if (self.act_script !== data.act_script) {
                    self.updateScript(data.act_script);
                }
            },
            loaded: function () {
                root.canvas.emptyCurrentSelection();
                var data = {};
                data.act_script = self.act_script;
                f.data = data;
                f.applyData(true);
            }
        };

    }

    f = new Form({
        items: items,
        closeContainerOnSubmit: true,
        buttons: [
            {
                jtype: 'submit',
                caption: translate('LBL_PMSE_BUTTON_SAVE'),
                cssClasses: ['btn btn-primary']
            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                handler: function () {
                    if (f.isDirty()) {
                        cancelInformation.setButtons([
                            {
                                jtype: 'normal',
                                caption: translate('LBL_PMSE_BUTTON_YES'),
                                handler: function () {
                                    cancelInformation.hide();
                                    w.close();
                                }
                            },
                            {
                                jtype: 'normal',
                                caption: translate('LBL_PMSE_BUTTON_NO'),
                                handler: function () {
                                    cancelInformation.hide();
                                }
                            }
                        ]);
                        cancelInformation.show();
                    } else {
                        w.close();
                    }
                },
                cssClasses: ['btn btn-invisible btn-link']
            }
        ],
        callback: callback,
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });

    w.addPanel({
        title: translate('LBL_PMSE_FORM_LABEL_GENERAL_SETTINGS'),
        panel: f
    });

    if (f2) {
        w.addPanel({
            title: translate('LBL_PMSE_FORM_LABEL_READ_ONLY_FIELDS'),
            panel: f2
        });
    }
    if (requiredForm) {
        w.addPanel({
            title: translate('LBL_PMSE_FORM_LABEL_REQUIRED_FIELDS'),
            panel: requiredForm
        });
    }

    if (f3) {
        w.addPanel({
            title: translate('LBL_PMSE_FORM_LABEL_EXPECTED_TIME'),
            panel: f3
        });
    }

    action = new Action({
        text: actionName,
        cssStyle : actionCSS,
        handler: function () {
            root.canvas.project.save();
            w.show();
            w.html.style.display = 'none';
            App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        },
        disabled: disabled
    });
    return action;
};

AdamActivity.prototype.updateScript = function (script) {
    var updateCommand;

    updateCommand = new CommandAdam(this, ['act_script'], [script]);
    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

AdamActivity.prototype.createAssignUsersAction = function () {
    var action,
        root = this,
        w,
        f,
        items,
        assignUserField,
        assignTeamField,
        combo_users,
        combo_teams,
        combo_method,
        hiddenFn,
        hiddenUpdateFn,
        callback,
        proxy,
        teams,
        users,
        cancelInformation,
        self = this;
    cancelInformation =  new MessagePanel({
        title: "Confirm",
        wtype: 'Confirm',
        message: translate('LBL_PMSE_MESSAGE_CANCEL_CONFIRM')
    });
    proxy = new SugarProxy({
        url: 'pmse_Project/ActivityDefinition/' + this.id,
        uid: this.id,
        callback: null
    });



    hiddenFn = function () {
        if (combo_method.value === 'static') {
            combo_users.setReadOnly(false);
            combo_teams.setReadOnly(true);
        } else {
            combo_users.setReadOnly(true);
            combo_teams.setReadOnly(false);
        }
    };

    hiddenUpdateFn = function () {
        switch (this.name) {
            case 'combo_teams':
                assignTeamField.setValue(combo_teams.value);
                assignUserField.setValue(null);
                break;
            case 'combo_users':
                assignTeamField.setValue(null);
                assignUserField.setValue(combo_users.value);
                break;
        }
    };

    assignTeamField = new HiddenField({
        name: 'act_assign_team'
    });

    assignUserField = new HiddenField({
        name: 'act_assign_user'
    });

    combo_users = new ComboboxField({
        jtype: 'combobox',
        label: translate('LBL_PA_FORM_LABEL_ASSIGN_TO_USER'),
        name: 'combo_users',
        submit: false,
        change: hiddenUpdateFn,
        proxy: new SugarProxy({
            url: 'pmse_Project/CrmData/users',
            uid: null,
            callback: null
        })
    });

    combo_teams = new ComboboxField({
        jtype: 'combobox',
        label: translate('LBL_PA_FORM_LABEL_ASSIGN_TO_TEAM'),
        name: 'combo_teams',
        submit: false,
        change: hiddenUpdateFn,
        proxy: new SugarProxy({
            url: 'pmse_Project/CrmData/teams/public',
            uid: 'public',
            callback: null
        })
    });

    combo_method = new ComboboxField({
        jtype: 'combobox',
        name: 'act_assignment_method',
        label: translate('LBL_PMSE_FORM_LABEL_ASSIGNMENT_METHOD'),
        change: hiddenFn,
        options: [
            {text: translate('LBL_PMSE_FORM_OPTION_ROUND_ROBIN'), value: 'balanced'},
            {text: translate('LBL_PMSE_FORM_OPTION_SELF_SERVICE'), value: 'selfservice'},
            {text: translate('LBL_PMSE_FORM_OPTION_STATIC_ASSIGNMENT'), value: 'static'}

        ],
        initialValue: 'balanced',
        required: true
    });

    callback = {

        'submit': function (data) {
            fData = f.getData();
            proxy.sendData(fData);
        },
        'loaded' : function (data) {

            proxy.getData({'module': PROJECT_MODULE}, {
                success: function(data) {
                    var aUsers = [
                        {'text': translate('LBL_PMSE_FORM_OPTION_CURRENT_USER'), 'value': 'currentuser'},
                        {'text': translate('LBL_PMSE_FORM_OPTION_RECORD_OWNER'), 'value': 'owner'},
                        {'text': translate('LBL_PMSE_FORM_OPTION_SUPERVISOR'), 'value': 'supervisor'}
                    ];
                    root.canvas.emptyCurrentSelection();
                    combo_teams.proxy.getData(null,{
                        success: function(teams) {
                            combo_teams.setOptions(teams.result);
                            assignTeamField.setValue(data.act_assign_team || teams.result[0].value);
                        }
                    });
                    combo_users.proxy.getData(null, {
                        success: function(users) {
                            aUsers = aUsers.concat(users.result);
                            combo_users.setOptions(aUsers);
                            App.alert.dismiss('upload');
                            w.html.style.display = 'inline';
                            assignUserField.setValue(data.act_assign_user || aUsers[0].value);
                        }
                    });
                    if (data) {
                        combo_method.setValue(data.act_assignment_method);

                        if (data.act_assignment_method === 'static') {
                            combo_users.setValue(data.act_assign_user);
                            combo_users.setReadOnly(false);
                            combo_teams.setReadOnly(true);
                        } else {
                            combo_teams.setValue(data.act_assign_team);
                            combo_users.setReadOnly(true);
                            combo_teams.setReadOnly(false);
                        }
                    }
                    f.proxy = null;

                }
            });


        }
    };

    f = new Form({
        items: [combo_method, combo_teams, combo_users, assignUserField, assignTeamField],
        closeContainerOnSubmit: true,
        buttons: [
            {
                jtype: 'submit',
                caption: translate('LBL_PMSE_BUTTON_SAVE'),
                cssClasses: ['btn btn-primary']
            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                handler: function () {
                    if (f.isDirty()) {
                        cancelInformation.setButtons([
                            {
                                jtype: 'normal',
                                caption: translate('LBL_PMSE_BUTTON_YES'),
                                handler: function () {
                                    cancelInformation.hide();
                                    w.close();
                                }
                            },
                            {
                                jtype: 'normal',
                                caption: translate('LBL_PMSE_BUTTON_NO'),
                                handler: function () {
                                    cancelInformation.hide();
                                }
                            }
                        ]);
                        cancelInformation.show();
                    } else {
                        w.close();
                    }
                },
                cssClasses: ['btn btn-invisible btn-link']
            }
        ],
        callback: callback,
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });
    w = new Window({
        width: 500,
        height: 350,
        title: translate('LBL_PMSE_FORM_TITLE_USER_DEFINITION') + ': ' + this.getName(),
        modal: true
    });
    w.addPanel(f);

    action = new Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_USERS'),
        cssStyle : 'adam-menu-icon-user',
        handler: function () {
            root.canvas.project.save();
            w.show();
            w.html.style.display = 'none';
            App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        },
        disabled: false
    });

    return action;
};

AdamActivity.prototype.actionFactory = function (type) {
    var action, actionText, actionCSS, root = this, w, f, proxy = null, items = [], callback = {},
        disabled = false, windowTitle = '', wWidth, wHeight, labelWidth = '30%', messageMap, fields,
        combo_users,
        combo_teams,
        combo_method,
        navigableData,
        changeFieldsFn,
        combo_modules,
        data,
        restored_fields,
        fields_updater,
        initialModule,
        combo_business,
        i,
        hidden_module,
        cancelInformation,
        hidden_method,
        updater_field,
        updateRecordOwner;
    cancelInformation =  new MessagePanel({
        title: "Confirm",
        wtype: 'Confirm',
        message: translate('LBL_PMSE_MESSAGE_CANCEL_CONFIRM')
    });
    switch (type) {
        case 'NONE':
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            disabled = true;
            actionCSS = 'adam-menu-icon-configure';
            break;
        case 'ASSIGN_USER':
            combo_users = new ComboboxField({
                jtype: 'combobox',
                label: translate('LBL_PA_FORM_LABEL_ASSIGN_TO_USER'),
                name: 'act_assign_user',
                submit: true,
                //change: hiddenUpdateFn,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/users',
                    uid: null,
                    callback: null
                }),
                required: true
            });
            //here add checkbox
            updateRecordOwner = new CheckboxField({
                name: 'act_update_record_owner',
                label: translate('LBL_PA_FORM_LABEL_UPDATE_RECORD_OWNER'),
                required: false,
                value: false,
                options: {

                    labelAlign: 'right',
                    marginLeft: 80

                }
            });
            proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                uid: this.id,
                callback: null
            });
            items = [combo_users, updateRecordOwner];
            wWidth = 550;
            wHeight = 160;
            labelWidth = '40%';
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            actionCSS = 'adam-menu-icon-configure';
            callback = {
                'loaded': function (data) {
                    var users, aUsers = [{'text': 'Select...', 'value': ''}];
                    root.canvas.emptyCurrentSelection();
                    users = combo_users.proxy.getData(null, {
                        success: function(users) {
                            aUsers = aUsers.concat(users.result);
                            combo_users.setOptions(aUsers);
                            if (data) {
                                combo_users.setValue(data.act_assign_user || aUsers[0].value);
                                nValue = false;
                                if (data.act_update_record_owner && data.act_update_record_owner == 1) {
                                    nValue = true;
                                }
                                updateRecordOwner.setValue(nValue);
                                $(updateRecordOwner.html).children('input').prop('checked', nValue);
                            }
                            App.alert.dismiss('upload');
                            w.html.style.display = 'inline';
                        }

                    });

                }
            };
            windowTitle = translate('LBL_PMSE_FORM_TITLE_ASSIGN_USER') + ': ' + this.getName();
            break;
        case 'ASSIGN_TEAM':
            combo_teams = new ComboboxField({
                jtype: 'combobox',
                label: translate('LBL_PA_FORM_LABEL_ASSIGN_TO_TEAM'),
                name: 'act_assign_team',
                submit: true,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/teams/public',
                    uid: 'public',
                    callback: null

                })
            });
            //here add checkbox
            updateRecordOwner = new CheckboxField({
                name: 'act_update_record_owner',
                label: translate('LBL_PA_FORM_LABEL_UPDATE_RECORD_OWNER'),
                required: false,
                value: false,
                options: {
                    labelAlign: 'right',
                    marginLeft: 200
                }
            });
            combo_method = new ComboboxField({
                jtype: 'combobox',
                name: 'act_assignment_method',
                label: translate('LBL_PMSE_FORM_LABEL_ASSIGNMENT_METHOD'),
                options: [
                    {text: translate('LBL_PMSE_FORM_OPTION_ROUND_ROBIN'), value: 'balanced'},
                    {text: translate('LBL_PMSE_FORM_OPTION_SELF_SERVICE'), value: 'selfservice'}
                ],
                initialValue: 'balanced',
                editable: false,
                readOnly: true
            });
            hidden_method = new HiddenField({
                name: 'act_assignment_method',
                initialValue: 'balanced'
            });
            proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                uid: this.id,
                callback: null
            });

            items = [combo_teams, updateRecordOwner, hidden_method];
            wWidth = 550;
            wHeight = 160;
            labelWidth = '40%';
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            actionCSS = 'adam-menu-icon-configure';
            callback = {
                'loaded': function (data) {
                    var teams;
                    root.canvas.emptyCurrentSelection();
                    teams = combo_teams.proxy.getData(null,{
                        success: function(teams) {
                            combo_teams.setOptions(teams.result);
                            if (data) {
                                combo_teams.setValue(data.act_assign_team || teams.result[0].value);
                                nValue = false;
                                if (data.act_update_record_owner && data.act_update_record_owner == 1){
                                    nValue = true;
                                }
                                updateRecordOwner.setValue(nValue);
                                $(updateRecordOwner.html).children('input').prop('checked', nValue);
                            }
                            App.alert.dismiss('upload');
                            w.html.style.display = 'inline';
                        }
                    });
                }
            };
            windowTitle = translate('LBL_PMSE_FORM_TITLE_ASSIGN_TEAM') + ': ' + this.getName();
            break;
        case 'CHANGE_FIELD':
            labelWidth = '20%';
            navigableData = { 'edit': true };
            changeFieldsFn = function () {
                App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});

                updater_field.proxy.url = 'pmse_Project/CrmData/relatedfields/'+ combo_modules.value;
                data = updater_field.proxy.getData({call_type:'AC', base_module: PROJECT_MODULE}, {
                    success: function(data) {
                        App.alert.dismiss('upload');
                        if (data) {
                            updater_field.setOptions(data.result, true);
                        }

                    }
                });

            };
            combo_modules = new ComboboxField({
                label: translate('LBL_PMSE_FORM_LABEL_MODULE'),
                name: 'act_field_module',
                submit: true,
                change: changeFieldsFn,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/related/' + PROJECT_MODULE,
                    uid: PROJECT_MODULE,
                    callback: null
                })
            });
            updater_field = new UpdaterField({
                label: translate('LBL_PMSE_FORM_LABEL_FIELDS'),
                name: 'act_fields',
                submit: true,
                decimalSeparator: SUGAR.App.config.defaultDecimalSeparator,
                numberGroupingSeparator: SUGAR.App.config.defaultNumberGroupingSeparator,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/fields/' + PROJECT_MODULE,
                    uid: null,
                    callback: null
                }),
                fieldWidth: 470,
                fieldHeight: 260,
                hasCheckbox: true
            });

            wWidth = 670;
            wHeight = 400;
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            actionCSS = 'adam-menu-icon-configure';
            items = [combo_modules, updater_field];
            proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                //restClient: this.canvas.project.restClient,
                uid: this.id,
                callback: null
            });
            windowTitle = translate('LBL_PMSE_FORM_TITLE_CHANGE_FIELDS') + ': ' + this.getName();
            callback = {
                'loaded' : function (data) {
                    var modules, opt = [], listProxy;
                    root.canvas.emptyCurrentSelection();

                    combo_modules.proxy.getData({
                        cardinality: 'one'
                    }, {
                        success: function(modules) {
                            if (modules && modules.success) {
                                combo_modules.setOptions(modules.result);
                                initialModule = data.act_field_module || modules.result[0].value;
                                project.addMetadata('projectModuleFields', {
                                    dataURL: 'pmse_Project/CrmData/fields/'+ PROJECT_MODULE,
                                    dataRoot: 'result',
                                    success: function (data) {
                                        updater_field.setVariables(data);
                                    }
                                });
                                updater_field.proxy.uid = PROJECT_MODULE;
                                updater_field.proxy.url = 'pmse_Project/CrmData/relatedfields/' + initialModule;
                                updater_field.proxy.getData({call_type:'AC', base_module: PROJECT_MODULE}, {
                                    success: function(fields) {
                                        if (fields) {
                                            updater_field.setOptions(fields.result, true);
                                            updater_field.setValue(data.act_fields || null);
                                            App.alert.dismiss('upload');
                                            w.html.style.display = 'inline';
                                        }

                                    }
                                });
                            }
                        }
                    });
                }
            };
            break;
        case 'ADD_RELATED_RECORD':
            labelWidth = '20%';
            navigableData = { 'edit': true };
            changeFieldsFn = function () {
                App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
                updater_field.proxy.uid = combo_modules.value;
                updater_field.proxy.url = 'pmse_Project/CrmData/addRelatedRecord/' + combo_modules.value;
                updater_field.proxy.getData({base_module: PROJECT_MODULE}, {
                    success: function(data) {
                        App.alert.dismiss('upload');
                        if (data) {
                            updater_field.setOptions(data.result, true);
                        }
                    }
                });

            };
            combo_modules = new ComboboxField({
                jtype: 'combobox',
                label: translate('LBL_PMSE_FORM_LABEL_RELATED_MODULE'),
                name: 'act_field_module',
                submit: true,
                change: changeFieldsFn,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/related/' + PROJECT_MODULE,
                    uid: PROJECT_MODULE,
                    callback: null
                })
            });
            updater_field = new UpdaterField({
                label: translate('LBL_PMSE_FORM_LABEL_FIELDS'),
                name: 'act_fields',
                submit: true,
                decimalSeparator: SUGAR.App.config.defaultDecimalSeparator,
                numberGroupingSeparator: SUGAR.App.config.defaultNumberGroupingSeparator,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/addRelatedRecord/'+ PROJECT_MODULE,
                    uid: null,
                    callback: null
                }),
                fieldWidth: 470,
                fieldHeight: 260
            });
            wWidth = 680;
            wHeight = 420;
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            actionCSS = 'adam-menu-icon-configure';
            items = [combo_modules, updater_field];
            proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                uid: this.id,
                callback: null
            });
            windowTitle = translate('LBL_PMSE_FORM_TITLE_ADD_RELATED_RECORD') + ': ' + this.getName();
            callback = {
                'loaded' : function (data) {
                    var modules, opt = [], listProxy;
                    root.canvas.emptyCurrentSelection();
                    combo_modules.proxy.getData({cardinality: 'one-to-many'}, {
                       success: function(modules) {
                           if (modules && modules.success && modules.result && modules.result.length > 1) {
                                modules.result = modules.result.splice(1);
                               combo_modules.setOptions(modules.result);
                               initialModule = data.act_field_module || modules.result[0].value;
                               updater_field.proxy.uid = PROJECT_MODULE;
                               updater_field.proxy.url = 'pmse_Project/CrmData/addRelatedRecord/' + initialModule;
                               project.addMetadata('projectModuleFieldsRelated', {
                                    dataURL: 'pmse_Project/CrmData/addRelatedRecord/'+ PROJECT_MODULE,
                                    dataRoot: 'result',
                                    success: function (data) {
                                        updater_field.setVariables(data);
                                    }
                                });
                               updater_field.proxy.getData({base_module: PROJECT_MODULE},{
                                   success: function(fields) {
                                       updater_field.setOptions(fields.result);
                                       updater_field.setValue(data.act_fields || null);
                                       App.alert.dismiss('upload');
                                       w.html.style.display = 'inline';
                                   }
                               });
                           }
                           else {
                               App.alert.dismiss('upload');
                               w.hide();
                               App.alert.show('upload', {
                                   level: 'warning',
                                   messages: SUGAR.App.lang.get('LBL_PMSE_CANNOT_CONFIGURE_ADD_RELATED_RECORD', 'pmse_Project'),
                                   autoClose: false
                               });
                           }
                       }
                    });

                }
            };
            break;
        case 'BUSINESS_RULE':
            combo_business = new ComboboxField({
                label: translate('LBL_PMSE_LABEL_RULE'),
                name: 'act_fields',
                submit: true,
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/rulesets/' + adamUID,
                    uid: adamUID,
                    callback: null
                })
            });
            hidden_module = new HiddenField({
                name: 'act_field_module',
                initialValue: PROJECT_MODULE
            });
            wWidth = 500;
            wHeight = 140;
            actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            actionCSS = 'adam-menu-icon-configure';
            items = [combo_business, hidden_module];
            proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                uid: this.id,
                callback: null
            });
            windowTitle = translate('LBL_PMSE_FORM_TITLE_BUSINESS_RULE') + ': ' + this.getName();
            callback = {
                'loaded': function (data) {
                    var rules, aRules = [{'text': translate('LBL_PMSE_FORM_OPTION_SELECT'), 'value': '', 'selected': true}];
                    root.canvas.emptyCurrentSelection();
                    //rules = combo_business.proxy.getData();
                    combo_business.proxy.getData(null,{
                        success: function(rules) {
                            if (rules && rules.success) {
                                aRules = aRules.concat(rules.result);
                                combo_business.setOptions(aRules);
                                if (data) {
                                    combo_business.setValue(data.act_fields || '');
                                }
                            }
                            App.alert.dismiss('upload');
                            w.html.style.display = 'inline';

                        }
                    });

                }
            };
            break;
    }

    f = new Form({
        proxy: proxy,
        items: items,
        closeContainerOnSubmit: true,
        footerAlign: 'right',
        buttons: [
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_SAVE'),
                handler: function () {
                    if (fields_updater && fields_updater.multiplePanel) {
                        fields_updater.multiplePanel.close();
                    }
                    f.submit();
                },
                cssClasses: ['btn btn-primary']

            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                handler: function () {
                    if (fields_updater && fields_updater.multiplePanel) {
                        fields_updater.multiplePanel.close();
                    }

                    if (f.isDirty()) {
                        cancelInformation.setButtons(
                            [
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        cancelInformation.hide();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.hide();
                                    }
                                }
                            ]
                        );
                        cancelInformation.show();
                    } else {
                        w.close();
                    }
                },
                cssClasses: ['btn btn-invisible btn-link']

            }
        ],
        labelWidth: labelWidth,
        callback: callback,
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });

    w = new Window({
        width: wWidth,
        height: wHeight,
        title: windowTitle,
        modal: true
    });
    w.addPanel(f);

    action = new Action({
        text: actionText,
        cssStyle: actionCSS,
        handler: function () {
            root.canvas.project.save();
            w.show();
            w.html.style.display = 'none';
            App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        },
        disabled: disabled
    });
    return action;
};
