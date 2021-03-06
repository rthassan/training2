/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/*global FieldOption, Field, Element, OptionTextField, $, document, OptionSelectField,
 getRelativePosition, OptionCheckBoxField, OptionDateField, replaceExpression, editorWindow,
 translate, MultipleItemPanel, PROJECT_MODULE, CriteriaField, PMSE_DECIMAL_SEPARATOR, TextAreaUpdaterItem, OptionNumberField
 */

/**
 * @class UpdaterField
 * Creates an object that can in order to illustrate a group of fields,
 * checkboxes or select items in the HTML it can be inside a form
 *
 *             //i.e.
 *             var updater_field = new UpdaterField({
 *                 //message that the label will display
 *                  label: "This is a label",
 *                  //name that the field has managed
 *                  name: 'the_name',
 *                  //if the field will be submited
 *                  submit: true,
 *                  //proxy to drive the all options sended from to server
 *                  proxy: proxy
 *                  //width of the field object not the text
 *                  fieldWidth: 470,
 *                  //height of the field object not the text
 *                  fieldHeight: 260
 *              });
 *
 * @extends Field
 *
 * @param {Object} options configuration options for the field object
 * @param {Object} parent
 * @constructor
 */
var UpdaterField = function (options, parent) {
    Field.call(this, options, parent);
    this.fields = [];
    this.options = [];
    this.fieldHeight = null;
    this.visualObject = null;
    this.language = {};
    this._variables = [];
    this._datePanel = null;
    this._variablesList = null;
    this._attachedListeners = false;
    this._decimalSeparator = null;
    this._numberGroupingSeparator = null;
    UpdaterField.prototype.initObject.call(this, options);
};

UpdaterField.prototype = new Field();

/**
 * Type of all updater field instances
 * @property {String}
 */
UpdaterField.prototype.type = 'UpdaterField';

/**
 * Initializer of the object will all the given configuration options
 * @param {Object} options
 */
UpdaterField.prototype.initObject = function (options) {
    var defaults = {
        fields: [],
        fieldHeight: null,
        language: {
            LBL_ERROR_ON_FIELDS: 'Please, correct the fields with errors'
        },
        hasCheckbox : false,
        decimalSeparator: ".",
        numberGroupingSeparator: ","
    };
    $.extend(true, defaults, options);
    this.language = defaults.language;
    this.setFields(defaults.fields);
    this.hasCheckbox = defaults.hasCheckbox;
    this._decimalSeparator = defaults.decimalSeparator;
    this._numberGroupingSeparator = defaults.numberGroupingSeparator;
    //this.hasCheckbox
        //.setFieldHeight(defaults.fieldHeight);
};

/**
 * Sets all option fiels into updater field container
 * @param {Array} items
 * @chainable
 */
UpdaterField.prototype.setFields = function (items) {
    var i, aItems = [], newItem;
    for (i = 0; i < items.length; i += 1) {
        if (items[i].type === 'FieldUpdater') {
            items[i].setParent(this);
            aItems.push(items[i]);
        } else {
            aItems.push(newItem);
        }
    }
    this.fields = aItems;
    return this;
};


//UpdaterField.prototype.setFieldHeight = function (value) {
//    this.fieldHeight = value;
//    return this;
//};

/**
 * Gets an object with all option fields values (label, name, type and values), to send the server
 * @return {Object}
 */
UpdaterField.prototype.getObjectValue = function () {
    var f, auxValue = [];

    for (f = 0; f < this.options.length; f += 1) {
        if (!this.options[f].isDisabled()) {
            auxValue.push(this.options[f].getData());
        }
    }
    this.value = JSON.stringify(auxValue);
    return Field.prototype.getObjectValue.call(this);
};

UpdaterField.prototype._parseSettings = function (settings) {
    var map = {
        value: "name",
        text: "label",
        type: "fieldType",
        optionItem: "options",
        required: "required"
    }, parsedSettings = {}, key;
    for (key in settings) {
        if (settings.hasOwnProperty(key) && map[key]) {
            parsedSettings[map[key]] = settings[key];
        }
    }
    return parsedSettings;
};

/**
 * Sets child option fiels into updater container
 * @param {Array} settings
 * @chainable
 */
UpdaterField.prototype.setOptions = function (settings) {
    var i,
        options = [],
        newOption,
        aUsers = [],
        customUsers = {},
        currentSetting;
    this.list = settings;
    for (i = 0; i < settings.length; i += 1) {
        /*CREATE INPUT FIELD*/
        currentSetting = this._parseSettings(settings[i]);
        currentSetting.parent = this;
        currentSetting.allowDisabling = this.hasCheckbox;
        currentSetting.disabled = this.hasCheckbox;
        switch (currentSetting.fieldType) {
        case 'TextField':
            newOption =  new TextUpdaterItem(currentSetting);
            break;
        case 'TextArea':
            newOption =  new TextAreaUpdaterItem(currentSetting);
            break;
        case 'Date':
        case 'Datetime':
            newOption =  new DateUpdaterItem(currentSetting);
            break;
        case 'DropDown':
            aUsers = [];
            if (currentSetting.options instanceof Array) {
                if (currentSetting.value === 'assigned_user_id') {
                    aUsers = [
                        {'text': translate('LBL_PMSE_FORM_OPTION_CURRENT_USER'), 'value': 'currentuser'},
                        {'text': translate('LBL_PMSE_FORM_OPTION_RECORD_OWNER'), 'value': 'owner'},
                        {'text': translate('LBL_PMSE_FORM_OPTION_SUPERVISOR'), 'value': 'supervisor'}
                    ];
                    customUsers = aUsers.concat(currentSetting.options);
                    currentSetting.options = customUsers;
                }
            } else {
                if (currentSetting.options) {
                    $.each(currentSetting.options, function (key, value) {
                        aUsers.push({value: key, text: value});
                    });
                }
                currentSetting.options = aUsers;

            }
            newOption =  new DropdownUpdaterItem(currentSetting);
            break;
        case 'Checkbox':
            newOption =  new CheckboxUpdaterItem(currentSetting);
            break;
        case 'Integer':
        case 'Currency':
        case 'Decimal':
        case 'Float':
            newOption =  new NumberUpdaterItem(currentSetting);
            break;
        default:
            newOption =  new TextUpdaterItem(currentSetting);
            break;
        }

        options.push(newOption);
    }
    this.options = options;
    this.setOptionsHTML();
    return this;
};

/**
 * Sets html content for each type of option field
 * @chainable
 */
UpdaterField.prototype.setOptionsHTML = function () {
    var i, insert;
    if (this.html) {
        this.visualObject.innerHTML = '';
        for (i = 0; i < this.options.length; i += 1) {
            insert = this.options[i].getHTML();
            if (i % 2 === 0) {
                insert.className += ' updater-inverse';
            }
            this.visualObject.appendChild(insert);
        }
    }
    return this;
};

UpdaterField.prototype.closePanels = function () {
    if (this._datePanel) {
        this._datePanel.close();
    }
    if (this._variablesList) {
        this._variablesList.close();
    }
    return this;
};

UpdaterField.prototype.attachListeners = function () {
    var that = this;
    if (this.html && !this._attachedListeners) {
        jQuery(this.visualObject).on('scroll', function () {
            jQuery(this.parent.body).trigger('scroll');
        });
        jQuery(this.parent.body).on('scroll', function () {
            that.closePanels();
        });
        this._attachedListeners = true;
    }
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
UpdaterField.prototype.createHTML = function () {
    var fieldLabel, required = '', criteriaContainer, insert, i, style;
    Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    fieldLabel.style.verticalAlign = 'top';
    this.html.appendChild(fieldLabel);

    criteriaContainer = this.createHTMLElement('div');
    criteriaContainer.className = 'adam-item-updater table';
    criteriaContainer.id = this.id;

    if (this.fieldWidth || this.fieldHeight) {
        style = document.createAttribute('style');
        if (this.fieldWidth) {
            style.value += 'width: ' + this.fieldWidth + 'px; ';
        }
        if (this.fieldHeight) {
            style.value += 'height: ' + this.fieldHeight + 'px; ';
        }
        style.value += 'display: inline-block; margin: 0; overflow: auto; padding: 3px;';
        criteriaContainer.setAttributeNode(style);
    }

    for (i = 0; i < this.options.length; i += 1) {
        insert = this.options[i].getHTML();
        //console.log( i % 2, 'aa');
        if (i % 2 === 0) {
            insert.className = insert.className + ' updater-inverse';
        }
        criteriaContainer.appendChild(insert);
    }

    this.html.appendChild(criteriaContainer);

    if (this.errorTooltip) {
        this.html.appendChild(this.errorTooltip.getHTML());
    }
    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }

    this.visualObject = criteriaContainer;

    return this.html;
};

/**
 * Sets values of every option field into an updater Field container,
 * determining the option field type
 * @param {Array} value
 * @chainable
 */
UpdaterField.prototype.setValue = function (value) {
    this.value = value;
    if (this.options && this.options.length > 0) {
        try {
            var fields, i, j;
            fields = JSON.parse(value);
            if (fields && fields.length > 0) {
                for (i = 0; i < fields.length; i += 1) {
                    for (j = 0; j < this.options.length; j += 1) {
                        if (fields[i].field === this.options[j].getName()) {
                            this.options[j].enable();
                            /*if (this.hasCheckbox) {
                                this.options[j].checkboxControl.checked = true;
                            }*/
                            //this.options[j].control.disabled = false;
                            this.options[j].setValue(fields[i].value); //this.options[j].value = fields[i].value;
                            //this.options[j].value = fields[i].value;
//                            if (this.options[j].fieldType === 'date') {
//                                $(this.options[j].textControl)
//                                    .datepicker("option", {disabled: false});
//                            } else if (this.options[j].fieldType === 'datetime') {
//                                $(this.options[j].textControl)
//                                    .datetimepicker("option", {disabled: false});
//                            }
                            /*if (this.options[j].type === 'OptionCheckBoxField') {
                                //this.options[j].control.checked = ((fields[i].value === 'on') ? true : false);
                                this.options[j].control.checked = fields[i].value;
                            }*/
                            /*if (this.options[j].type === 'OptionDateField' || this.options[j].type === 'OptionNumberCriteriaField') {
                                //for (k = 0; k < fields[i].value)
                                this.options[j].addCriteriaItems(fields[i].value);
                                this.options[j].timerCriteria.enable();
                                this.options[j].disabled = false;

                            }*/
                            //this.options[j].control.value = fields[i].value;
                            //
                            break;
                        }
                    }
                }
            }
        } catch (e) {}
    }
    return this;
};

/**
 * Determines whether a field is valid checking if required
 * and the value corresponds to the type of data the shows an visual warning
 * @return {Boolean}
 */
UpdaterField.prototype.isValid = function () {
    var i, valid = true, current, field;
    for (i = 0; i < this.options.length; i += 1) {
        field = this.options[i];
        //valid = valid && field.isValid();
        if (field.isRequired()) {
            switch (field.type) {
            case 'CheckboxUpdaterItem':
                valid = field.getValue();
                break;
            case 'DateUpdaterItem':
            case 'NumberUpdaterItem':
                valid = !!field.getValue().length;
                break;
            default:
                if (field.getValue() === '') {
                    valid = false;
                }
                break;
            }

        }
        //TODO: create validation for expressions built with expressionControl.
        /*if (field.type === 'DateUpdaterItem' && !field.timerCriteria.isValid()) {
            valid = false;
        }*/
        if (field._parent.hasCheckbox && field.isDisabled()) {
            valid = true;
        }

        if (!valid) {
            break;
        }
    }

    if (valid) {
        $(this.errorTooltip.html).removeClass('adam-tooltip-error-on');
        $(this.errorTooltip.html).addClass('adam-tooltip-error-off');
        valid = valid && Field.prototype.isValid.call(this);
    } else {
        this.visualObject.scrollTop += getRelativePosition(field.getHTML(), this.visualObject).top;
        this.errorTooltip.setMessage(this.language.LBL_ERROR_ON_FIELDS);
        $(this.errorTooltip.html).removeClass('adam-tooltip-error-off');
        $(this.errorTooltip.html).addClass('adam-tooltip-error-on');
    }
    return valid;
};

/**
 * Obtains and creates the variable string according to the format established
 * for handling variables in sugar
 * @param {String} module
 */
UpdaterField.prototype._onValueGenerationHandler = function (module) {
    var  that = this;
    return function () {
        var newExpression, field = that.currentField, control, i, currentValue = field.getValue(), aux, aux2,
            panel, list;

        control = field._control;
        if (this instanceof ExpressionControl) {
            panel = arguments[0];
            newExpression = panel.getValueObject();
        } else {
            panel = arguments[0];
            list = arguments[1];
            newExpression = "{::" + module + "::" + arguments[2].value  + "::}";
            i = control.selectionStart;
            i = i || 0;
            aux = currentValue.substr(0, i);
            aux2 = currentValue.substr(i);
            newExpression = aux + newExpression + aux2;
        }

        field.setValue(newExpression);
        if (!(panel instanceof ExpressionControl)) {
            panel.close();
        }
    //Previous version
        /*var input, currentValue, i, newExpression = "{::" + module + "::" + value + "::}", aux, aux2, field = that.currentField;
        if (this.parent.belongsTo.tagName.toLowerCase() === "input") {
            input = $(field.control).get(0);
            currentValue = input.value;
            i = input.selectionStart;
        } else if (this.parent.belongsTo.tagName.toLowerCase() === "textarea") {
            input = $(field.control).get(0);
            currentValue = input.value;
            i = input.selectionStart;
        } else if (this.parent.belongsTo.tagName.toLowerCase() === "div") {
            input = $('#plain_email_body').get(0);
            currentValue = input.value;
            i = input.selectionStart;
        } else {
            input = $(this.parent.belongsTo).data("textNode");
            currentValue = input.nodeValue;
            i = editorWindow.getSelection().anchorOffset;
        }
        //var input = $('#email_subject').get(0), i = input.selectionStart, aux, aux2, newExpression = "{::" + module + "::" + value + "::}";
        if (i) {
            if (currentValue.charAt(i - 1) === "{") {
                aux = currentValue.substr(i - 1);
                aux2 = replaceExpression(aux, newExpression);
                aux = aux2 === aux ? aux.replace(/\{/, newExpression) : aux2;
            } else if (i > 1 && currentValue.charAt(i - 1) === ":" && currentValue.charAt(i - 2) === "{") {
                aux = currentValue.substr(i - 2);
                aux2 = replaceExpression(aux, newExpression);
                aux = aux2 === aux ? aux.replace(/\{\:/, newExpression) : aux2;
                i -= 1;
            } else if (i > 2 && currentValue.charAt(i - 1) === ":" && currentValue.charAt(i - 2) === ":" && currentValue.charAt(i - 3) === "{") {
                aux = currentValue.substr(i - 3);
                aux2 = replaceExpression(aux, newExpression);
                aux = aux2 === aux ? aux.replace(/\{\:\:/, newExpression) : aux2;
                i -= 2;
            }
            if (aux2) {
                value = currentValue.substr(0, i - 1) + aux;
            } else {
                value = currentValue.substr(0, i) + newExpression + currentValue.substr(i);
            }
        } else {
            i = 0;
            value = newExpression + currentValue;
        }
        if (this.parent.belongsTo.tagName.toLowerCase() === 'input' || this.parent.belongsTo.tagName.toLowerCase() === 'div' || this.parent.belongsTo.tagName.toLowerCase() === 'textarea') {
            input.value = value;
            //input.selectionStart = input.selectionEnd = i + newExpression.length;
        } else {
            input.nodeValue = value;
            //editorWindow.getSelection().anchorOffset = 8;
        }
        that.multiplePanel.close();*/
    };
};

/**
 * Displays and create the control panel with filled with the possibilities
 * of the sugar variables, change the panel z-index to show correctly,
 * finally add a windows close event for close the control panel
 * @param {Object} field
 */
UpdaterField.prototype.openPanelOnItem = function (field) {
    var that = this, settings, inputPos, textSize, subjectInput, i,
        variablesDataSource = project.getMetadata("targetModuleFieldsDataSource"), currentFilters, list, targetPanel,
        currentOwner, fieldType = field.getFieldType(), constantPanelCfg;

    if (!(field instanceof DateUpdaterItem || field instanceof NumberUpdaterItem)) {
        if (!this._variablesList) {
            this._variablesList = new FieldPanel({
                className: "updateritem-panel",
                //height: "auto",
                items: [
                    {
                        type: "list",
                        bodyHeight: 100,
                        collapsed: false,
                        itemsContent: "{{text}}",
                        fieldToFilter: "type",
                        title: translate('LBL_PMSE_UPDATERFIELD_VARIABLES_LIST_TITLE').replace(/%MODULE%/g, PROJECT_MODULE)
                    }
                ],
                onItemValueAction: this._onValueGenerationHandler(PROJECT_MODULE),
                onOpen: function () {
                    jQuery(that.currentField.html).addClass("opened");
                },
                onClose: function () {
                    jQuery(that.currentField.html).removeClass("opened");
                }
            });
        }
        if (this._datePanel && this._datePanel.isOpen()) {
            this._datePanel.close();
        }
        targetPanel = this._variablesList;
        list = this._variablesList.getItems()[0];
        currentFilters = list.getFilter();
        //We check if the variables list has the same filter than the one we need right now,
        //if it do then we don't need to apply the data filtering for a new criteria
        if (fieldType === 'TextField' || fieldType === 'TextArea' || fieldType === 'Name') {
            if (list.getFilterMode() === 'inclusive') {
                list.setFilterMode('exclusive')
                    .setDataItems(this._variables, "type", ["Checkbox", "DropDown"]);
            }
        } else if (!(currentFilters.length === 1 && currentFilters.indexOf(field._fieldType) > 0)) {
            list.setFilterMode('inclusive')
                .setDataItems(this._variables, "type", field._fieldType);
        }
        this.currentField = field;
    } else {
        if (!this._datePanel) {
            this._datePanel = new ExpressionControl({
                className: "updateritem-panel",
                onChange: this._onValueGenerationHandler(PROJECT_MODULE),
                appendTo: (this.parent && this.parent.parent && this.parent.parent.html) || null,
                decimalSeparator: this._decimalSeparator,
                numberGroupingSeparator: this._numberGroupingSeparator,
                dateFormat: App.date.getUserDateFormat(),
                timeFormat: SUGAR.App.user.getPreference("timepref"),
                onOpen: function () {
                    jQuery(that.currentField.html).addClass("opened");
                },
                onClose: function () {
                    jQuery(that.currentField.html).removeClass("opened");
                }
            });
        }
        //Check if the panel is already configured for the current field's type
        //in order to do it, we verify if the current field class is the same that the previous field's.
        if (!this.currentField || this.currentField !== field) {
            if (field instanceof DateUpdaterItem) {
                if (fieldType === 'Date') {
                    constantPanelCfg = {
                        date: true,
                        timespan: true
                    };
                } else {
                    constantPanelCfg = {
                        datetime: true,
                        timespan: true
                    };
                }
                this._datePanel.setOperators({
                    arithmetic: ["+", "-"]
                }).setConstantPanel(constantPanelCfg);
            } else {
                this._datePanel.setOperators({
                    arithmetic: true
                }).setConstantPanel({
                    basic: {
                        number: true
                    }
                });
            }
            this._datePanel.setVariablePanel({
                data: [{
                    name: PROJECT_MODULE,
                    value: PROJECT_MODULE,
                    items: this._variables
                }],
                dataFormat: "hierarchical",
                typeField: "type",
                typeFilter: field instanceof DateUpdaterItem ? ["Date", "Datetime"] : field._fieldType,
                textField: "text",
                valueField: "value",
                dataChildRoot: "items",
                moduleTextField: "name",
                moduleValueField: "value"
            });
        }
        this.currentField = field;
        this._datePanel.setValue(field.getValue());
        if (this._variablesList && this._variablesList.isOpen()) {
            this._variablesList.close();
        }
        targetPanel = this._datePanel;
    }

    /*if (!this.multiplePanel) {
        this.multiplePanel = new ExpressionControl({
            onChange: this._onValueGenerationHandler(PROJECT_MODULE),
            matchParentWidth: false,
            expressionVisualizer: false,
            width: 200
        });

        if (field.fieldType !== 'date' && field.fieldType !== 'datetime') {


            this.multiplePanel.addSubpanel({
                title: translate('LBL_PMSE_ADAM_UI_TITLE_MODULE_FIELDS', 'pmse_Project', translate('LBL_PMSE_LABEL_TARGETMODULE')),
                collapsable: true,
                items: this.panelList,
                //onOpen: this.getOnOpenHandler(PROJECT_MODULE),
                onItemSelect: this.getAddVariableHandler(PROJECT_MODULE)
            }, "list");
            document.body.appendChild(this.multiplePanel.getHTML());
        }
    } else {
        //this.multiplePanel.close();
    }*/


    subjectInput = field._control;
    currentOwner = targetPanel.getOwner();
    if (currentOwner !== subjectInput) {
        targetPanel.close();
        targetPanel.setOwner(subjectInput);
        targetPanel.open();
    } else {
        if (targetPanel.isOpen()) {
            targetPanel.close();
        } else {
            targetPanel.open();
        }
    }

    /*this.multiplePanel.open();
    if (this.multiplePanel.subpanels[0]) {
        this.multiplePanel.subpanels[0].open();
    }
    this.multiplePanel.getHTML().style.zIndex = '1034';

    $('.adam-window-close').on('click', function (e) {
        if (that.multiplePanel) {
            that.multiplePanel.close();
        }
    });
    $('.adam-panel-body').scroll(function(){
        if (that.multiplePanel) {
            that.multiplePanel.close();
        }
    });*/

    return this;
};
UpdaterField.prototype.setVariables = function (variables) {
    this._variables = variables;
    return this;
};

//UpdaterItem
    var UpdaterItem = function (settings) {
        Element.call(this, settings);
        this._parent = null;
        this._name = null;
        this._label = null;
        this._required = null;
        this._dom = {};
        this._activationControl = null;
        this._control = null;
        this._disabled = null;
        this._value = null;
        this._fieldType = null;
        this._configButton = null;
        this._attachedListeners = false;
        this._dirty = false;
        this._allowDisabling = true;
        UpdaterItem.prototype.init.call(this, settings);
    };

    UpdaterItem.prototype = new Element();
    UpdaterItem.prototype.constructor = UpdaterItem;
    UpdaterItem.prototype.type = "UpdaterItem";

    UpdaterItem.prototype.init = function(settings) {
        var defaults = {
            parent: null,
            name: this.id,
            label: "[updater item]",
            required: false,
            disabled: true,
            allowDisabling: true,
            value: "",
            fieldType: null
        };

        jQuery.extend(true, defaults, settings);

        this.setParent(defaults.parent)
            .setName(defaults.name)
            .setLabel(defaults.label)
            .setRequired(defaults.required)
            .setValue(defaults.value)
            .setFieldType(defaults.fieldType);

        if (defaults.disabled) {
            this.disable();
        } else {
            this.enable();
        }
        if (defaults.allowDisabling) {
            this.allowDisabling();
        } else {
            this.disallowDisabling();
        }
    };

    UpdaterItem.prototype.allowDisabling = function () {
        this._allowDisabling = true;
        if (this._activationControl) {
            this._activationControl.style.display = "";
        }
        return this;
    };

    UpdaterItem.prototype.disallowDisabling = function () {
        this._allowDisabling = false;
        if (this._activationControl) {
            this._activationControl.style.display = "none";
        }
    };

    UpdaterItem.prototype.setParent = function (parent) {
        if (!(parent === null || parent instanceof UpdaterField)) {
            throw new Error("setParent(): The parameter must be an instance of UpdaterField or null.");
        }
        this._parent = parent;
        return this;
    };

    UpdaterItem.prototype.setName = function (name) {
        if (!(typeof name === 'string' && name)) {
            throw new Error("setName(): The parameter must be a non empty string.");
        }
        this._name = name;
        return this;
    };

    UpdaterItem.prototype.getName = function () {
        return this._name;
    };

    UpdaterItem.prototype.setLabel = function (label) {
        if (typeof label !== 'string') {
            throw new Error("setLabel(): The parameter must be a string.");
        }
        this._label = label;
        if (this._dom.labelText) {
            this._dom.labelText.textContent = label;
        }
        return this;
    };

    UpdaterItem.prototype.setRequired = function (required) {
        var requireContent = "*";
        this._required = !!required;
        if (this._dom.requiredContainer) {
            if (!this._required) {
                requireContent = "";
            }
            this._dom.requiredContainer.textContent = requireContent;
        }
        return this;
    };

    UpdaterItem.prototype.isRequired = function () {
        return this._required;
    };

    UpdaterItem.prototype.isValid = function () {
        return !!(this._required && this._value);
    };

    UpdaterItem.prototype.clear = function () {
        if (this._control) {
            this._control.value = "";
        }
        this._value = "";
        return this;
    };

    UpdaterItem.prototype.disable = function () {
        if (this._activationControl) {
            this._activationControl.checked = false;
            this._disableControl();
        }
        this.clear();
        this._disabled = true;
        return this;
    };

    UpdaterItem.prototype.enable = function () {
        if (this._activationControl) {
            this._activationControl.checked = true;
            this._enableControl();
        }
        this._disabled = false;
        return this;
    };

    UpdaterItem.prototype.isDisabled = function () {
        return this._disabled;
    };

    UpdaterItem.prototype._setValueToControl = function (value) {
        this._control.value = value;
        return this;
    };

    UpdaterItem.prototype._getValueFromControl = function () {
        return this._control.value;
    };

    UpdaterItem.prototype.setValue = function (value) {
        if (typeof value !== 'string') {
            throw new Error("setValue(): The parameter must be a string.");
        }
        if (this._control) {
            this._setValueToControl(value);
            this._value = this._getValueFromControl();
        } else {
            this._value = value;
        }
        return this;
    };

    UpdaterItem.prototype.getValue = function () {
        return this._value;
    };

    UpdaterItem.prototype.setFieldType = function (fieldType) {
        if (!(fieldType === null || typeof fieldType === "string")) {
            throw new Error("setFieldType(): The parameter must be a string or null.");
        }
        this._fieldType = fieldType;
        return this;
    };

    UpdaterItem.prototype.getFieldType = function () {
        return this._fieldType;
    };

    UpdaterItem.prototype._createControl = function () {
        if (!this._control) {
            throw new Error("_createControl(): This method must be called from anUpdaterItem's subclass.");
        }
        jQuery(this._control).addClass("updateritem-control");
        return this._control;
    };

    UpdaterItem.prototype._createConfigButton = function () {
        var button = this.createHTMLElement("a");
        button.href = "#";
        button.className = "adam-itemupdater-cfg fa fa-cog";
        this._configButton = button;
        return this._configButton;
    };

    UpdaterItem.prototype._disableControl = function () {
        this._control.disabled = true;
        return this;
    };

    UpdaterItem.prototype._enableControl = function () {
        this._control.disabled = false;
        return this;
    };

    UpdaterField.prototype.isDirty = function () {
        return this._dirty;
    };

    UpdaterItem.prototype._onChange = function () {
        var that = this;
        return function (e) {
            var currValue = that._value;
            that._value = that._getValueFromControl();
            if (that._value !== currValue) {
                that._dirty = true;
            }
        };
    };

    UpdaterItem.prototype.getData = function () {
        return {
            name: this._label,
            field: this._name,
            value: this._value,
            type: this._fieldType
        };
    };

    UpdaterItem.prototype.attachListeners = function () {
        var that = this;
        if (this.html && !this._attachedListeners) {
            jQuery(this._activationControl).on("change", function (e) {
                if (e.target.checked) {
                    that.enable();
                } else {
                    that.disable();
                }
            });
            jQuery(this._configButton).on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (that._parent && !that._disabled) {
                    that._parent.openPanelOnItem(that);
                }
            });
            jQuery(this._control).on("change", this._onChange());
        }
        return this;
    };

    UpdaterItem.prototype.createHTML = function () {
        var label,
            controlContainer,
            activationControl,
            labelContent,
            labelText,
            requiredContainer,
            messageContainer,
            configButton,
            messageContainer;

        if (!this.html) {
            Element.prototype.createHTML.call(this);
            jQuery(this.html).addClass("updaterfield-item");
            this.style.removeProperties(['width', 'height', 'position', 'top', 'left', 'z-index']);

            label = this.createHTMLElement('label');
            label.className = 'adam-itemupdater-label';

            controlContainer = this.createHTMLElement("div");
            controlContainer.className = "adam-itemupdater-controlcontainer";

            activationControl = this.createHTMLElement("input");
            activationControl.type = "checkbox";
            activationControl.className = "adam-itemupdater-activation";

            labelContent = this.createHTMLElement("span");
            labelContent.className = "adam-itemupdater-labelcontent";

            labelText = this.createHTMLElement("span");
            labelText.className = "adam-itemupdater-labeltext";

            requiredContainer = this.createHTMLElement("span");
            requiredContainer.className = "adam-itemupdater-required required noshadow";

            messageContainer = this.createHTMLElement("div");
            messageContainer.className = "adam-itemupdater-message";

            labelContent.appendChild(labelText);
            labelContent.appendChild(requiredContainer);

            label.appendChild(activationControl);
            label.appendChild(labelContent);

            controlContainer.appendChild(this._createControl());
            this._createConfigButton();
            if (this._configButton) {
                controlContainer.appendChild(this._configButton);
            }

            this._dom.labelText = labelText;
            this._dom.requiredContainer = requiredContainer;

            this._activationControl = activationControl;
            this.html.appendChild(label);
            this.html.appendChild(controlContainer);
            this.html.appendChild(messageContainer);

            this.setLabel(this._label)
                .setRequired(this._required);
            if (this._disabled) {
                this.disable();
            } else {
                this.enable();
            }
            if (this._allowDisabling) {
                this.allowDisabling();
            } else {
                this.disallowDisabling();
            }
            this.attachListeners();
            this.setValue(this._value);
        }
        return this.html;
    };
//TextUpdaterItem
    var TextUpdaterItem = function (settings) {
        UpdaterItem.call(this, settings);
        this._maxLength = null;
        TextUpdaterItem.prototype.init.call(this, settings);
    };

    TextUpdaterItem.prototype = new UpdaterItem();
    TextUpdaterItem.prototype.constructor = TextUpdaterItem;
    TextUpdaterItem.prototype.type = "TextUpdaterItem";

    TextUpdaterItem.prototype.init = function (settings) {
        var defaults = {
            maxLength: 0
        };

        jQuery.extend(true, defaults, settings);

        this.setMaxLength(defaults.maxLength);
    };

    TextUpdaterItem.prototype.setMaxLength = function (maxLength) {
        if (typeof maxLength === 'string' && /\d+/.test(maxLength)) {
            maxLength = parseInt(maxLength, 10);
        }
        if (typeof maxLength !== 'number') {
            throw new Error("setMaxLength(): The parameter must be a number.");
        }
        this._maxLength = maxLength;
        if (this._control) {
            if (maxLength) {
                this._control.maxLength = maxLength;
            } else {
                this._control.removeAttribute("maxlength");
            }

        }
        return this;
    };

    TextUpdaterItem.prototype._createControl = function () {
        var control = this.createHTMLElement("input");
        control.type = "text";
        this._control = control;
        this.setMaxLength(this._maxLength);
        return UpdaterItem.prototype._createControl.call(this);
    };
//DateUpdaterItem
    var DateUpdaterItem = function (settings) {
        UpdaterItem.call(this, settings);
        DateUpdaterItem.prototype.init.call(this, settings);
    };

    DateUpdaterItem.prototype = new UpdaterItem();
    DateUpdaterItem.prototype.constructor = DateUpdaterItem;
    DateUpdaterItem.prototype.type = "DateUpdaterItem";

    DateUpdaterItem.prototype.init = function (settings) {
        var defaults = {
            value: "[]"
        };

        jQuery.extend(true, defaults, settings);

        this.setValue(defaults.value);
    };

    DateUpdaterItem.prototype._setValueToControl = function (value) {
        var friendlyValue = "", i, dateFormat, timeFormat;
        value.forEach(function(value, index, arr) {
            if (value && value.expType === 'CONSTANT') {
                if (!dateFormat) {
                    dateFormat = SUGAR.App.date.convertFormat(SUGAR.App.user.getPreference("datepref"));
                }
                if (value.expSubtype === "datetime") {
                    if (!timeFormat) {
                        timeFormat = SUGAR.App.date.convertFormat(SUGAR.App.user.getPreference("timepref"));
                    }
                    aux = App.date(value.expValue);
                    value.expLabel = aux.format(dateFormat + " " + timeFormat);
                } else if (value.expSubtype === "date") {
                    aux = App.date(value.expValue);
                    value.expLabel = aux.format(dateFormat);
                }
            }
            friendlyValue += " " + value.expLabel;
        });
        this._control.value = friendlyValue;
        return this;
    };

    DateUpdaterItem.prototype.setValue = function (value) {
        if (typeof value === 'string') {
            value = value || "[]";
            value = JSON.parse(value);
        }
        if (this._control) {
            this._setValueToControl(value);
        }
        this._value = value;
        return this;
    };

    DateUpdaterItem.prototype.clear = function () {
        UpdaterItem.prototype.clear.call(this);
        this._value = "[]";
        return this;
    };

    DateUpdaterItem.prototype._createControl = function () {
        var control = this.createHTMLElement("input");
        control.type = "text";
        control.readOnly = true;
        this._control = control;
        return UpdaterItem.prototype._createControl.call(this);
    };

    DateUpdaterItem.prototype._createConfigButton = function () {
        return null;
    };

    DateUpdaterItem.prototype.attachListeners = function () {
        var that = this;
        if (this.html && !this._attachedListeners) {
            UpdaterItem.prototype.attachListeners.call(this);
            jQuery(this._control).on("focus", function () {
                if (that._parent && !this._disabled) {
                    that._parent.openPanelOnItem(that);
                }
            });
            this._attachedListeners = true;
        }
    };
//CheckboxUpdaterItem
    var CheckboxUpdaterItem = function (settings) {
        UpdaterItem.call(this, settings);
    };

    CheckboxUpdaterItem.prototype = new UpdaterItem();
    CheckboxUpdaterItem.prototype.constructor = CheckboxUpdaterItem;
    CheckboxUpdaterItem.prototype.type = "CheckboxUpdaterItem";

    CheckboxUpdaterItem.prototype.setValue = function (value) {
        if (this._control) {
            this._setValueToControl(value);
            this._value = this._getValueFromControl();
        } else {
            this._value = !!value;
        }
        return this;
    };

    CheckboxUpdaterItem.prototype._createControl = function () {
        var control = this.createHTMLElement('input');
        control.type = "checkbox";
        this._control = control;
        return UpdaterItem.prototype._createControl.call(this);
    };

    CheckboxUpdaterItem.prototype._createConfigButton = function () {
        return null;
    };

    CheckboxUpdaterItem.prototype.clear = function () {
        if (this._control) {
            this._control.checked = false;
        }
        this._value = false;
        return this;
    };

    CheckboxUpdaterItem.prototype._setValueToControl = function (value) {
        this._control.checked = !!value;
        return this;
    };

    CheckboxUpdaterItem.prototype._getValueFromControl = function () {
        return this._control.checked;
    };

    CheckboxUpdaterItem.prototype._onChange = function () {
        var that = this;
        return function (e) {
            var currValue = that._value;
            that._value = that._getValueFromControl();
            if (that._value !== currValue) {
                that._dirty = true;
            }
        };
    };
//TextAreaUpdaterItem
    var TextAreaUpdaterItem = function (settings) {
        TextUpdaterItem.call(this, settings);
    };

    TextAreaUpdaterItem.prototype = new TextUpdaterItem();
    TextAreaUpdaterItem.prototype.constructor = TextAreaUpdaterItem;
    TextAreaUpdaterItem.prototype.type = "TextAreaUpdaterItem";

    TextAreaUpdaterItem.prototype._createControl = function () {
        var control = this.createHTMLElement('textarea');
        this._control = control;
        return UpdaterItem.prototype._createControl.call(this);
    };
//NumberUpdaterItem
    var NumberUpdaterItem = function (settings) {
        UpdaterItem.call(this, settings);
        NumberUpdaterItem.prototype.init.call(this, settings);
    };

    NumberUpdaterItem.prototype = new UpdaterItem();
    NumberUpdaterItem.prototype.constructor = NumberUpdaterItem;
    NumberUpdaterItem.prototype.type = "NumberUpdaterItem";

    NumberUpdaterItem.prototype.init = function (settings) {
        var defaults = {
            value: "[]"
        };
        jQuery.extend(true, defaults, settings);
        this.setValue(defaults.value);
    };

    NumberUpdaterItem.prototype._setValueToControl = function (value) {
        var friendlyValue = "", i;
        value.forEach(function(value, index, arr) {
            friendlyValue += " " + value.expLabel;
        });
        this._control.value = friendlyValue;
        return this;
    };


    NumberUpdaterItem.prototype.setValue = function (value) {
        if (typeof value === 'string') {
            value = value || "[]";
            value = JSON.parse(value);
        }
        if (this._control) {
            this._setValueToControl(value);
        }
        this._value = value;
        return this;
    };

    NumberUpdaterItem.prototype._createControl = function () {
        var control = this.createHTMLElement("input");
        control.type = "text";
        control.readOnly = true;
        this._control = control;
        return UpdaterItem.prototype._createControl.call(this);
    };
//DropdownUpdaterItem
    var DropdownUpdaterItem = function (settings) {
        UpdaterItem.call(this, settings);
        this._options = [];
        this._massiveAction = false;
        this._initialized = false;
        DropdownUpdaterItem.prototype.init.call(this, settings);
    };

    DropdownUpdaterItem.prototype = new UpdaterItem();
    DropdownUpdaterItem.prototype.constructor = DropdownUpdaterItem;
    DropdownUpdaterItem.prototype.type = "DropdownUpdaterItem";

    DropdownUpdaterItem.prototype.init = function (settings) {
        var defaults = {
            options: [],
            value: ""
        };

        jQuery.extend(true, defaults, settings);

        this.setOptions(defaults.options)
            .setValue(defaults.value);

        this._initialized = true;
    };

    DropdownUpdaterItem.prototype._existsValueInOptions = function (value) {
        var i;
        for (i = 0; i < this._options.length; i += 1) {
            if (this._options[i].value === value) {
                return true;
            }
        }
        return false;
    };

    DropdownUpdaterItem.prototype._getFirstAvailabelValue = function () {
        return (this._options[0] && this._options[0].value) || "";
    };

    DropdownUpdaterItem.prototype.setValue = function (value) {
        if (this._options) {
            if (!(typeof value === 'string' || typeof value === 'number')) {
                throw new Error("setValue(): The parameter must be a string.");
            }
            if (isInDOM(this._control)) {
                this._setValueToControl(value);
                this._value = this._getValueFromControl();
            } else {
                if (this._existsValueInOptions(value)) {
                    this._value = value;
                } else {
                    this._value = this._getFirstAvailabelValue();
                }
            }
        }
        return this;
    };

    DropdownUpdaterItem.prototype._paintItem = function (option) {
        var optionHTML;
        optionHTML = this.createHTMLElement('option');
        optionHTML.textContent = optionHTML.label = option.text;
        optionHTML.value = option.value;
        this._control.appendChild(optionHTML);
        return this;
    };

    DropdownUpdaterItem.prototype._paintItems = function () {
        var i;
        if (this._control) {
            jQuery(this._control).empty();
            for (i = 0; i < this._options.length; i += 1) {
                this._paintItem(this._options[i]);
            }
        }
        return this;
    };

    DropdownUpdaterItem.prototype.addOption = function (option) {
        var newOption;
        if (typeof option === 'string' || typeof option === 'number') {
            newOption = {
                text: option,
                value: option
            };
        } else {
            newOption = {
                text: option.text || option.value,
                value: option.value || option.text
            };
        }
        this._options.push(newOption);
        if (!this._massiveAction && this.html) {
            this._paintItem(newOption);
        }
        return this;
    };

    DropdownUpdaterItem.prototype.clearOptions = function () {
        this._options = [];
        if (this._control) {
            jQuery(this._control).empty();
        }
        return this;
    };

    DropdownUpdaterItem.prototype.setOptions = function (options) {
        var i;
        if (!jQuery.isArray(options)) {
            throw new Error("setOptions(): The parameter must be an array.");
        }
        this._massiveAction = true;
        this.clearOptions();
        for (i = 0; i < options.length; i += 1) {
            this.addOption(options[i]);
        }
        this._massiveAction = false;
        this._paintItems();
        if (this._initialized) {
            this.setValue(this._value);
        }
        return this;
    };

    DropdownUpdaterItem.prototype._createConfigButton = function () {
        return null;
    };

    DropdownUpdaterItem.prototype._createControl = function () {
        if (!this._control) {
            this._control = this.createHTMLElement('select');
        }
        return UpdaterItem.prototype._createControl.call(this);
    };

    DropdownUpdaterItem.prototype.createHTML = function () {
        if (!this.html) {
            UpdaterItem.prototype.createHTML.call(this);
            this._paintItems();
            this.setValue(this._value);
        }
        return this.html;
    };
