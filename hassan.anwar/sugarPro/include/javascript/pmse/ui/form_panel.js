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
//FormPanel
	var FormPanel = function(settings) {
		CollapsiblePanel.call(this, settings);
		this._htmlSubmit = null;
		this._submitCaption = null;
		this._htmlFooter = null;
		this._dependencyMap = null;
		this._submitVisible = null;
		this.onSubmit =  null;
		FormPanel.prototype.init.call(this, settings);
	};

	FormPanel.prototype = new CollapsiblePanel();
	FormPanel.prototype.constructor = FormPanel;
	FormPanel.prototype.type = "FormPanel";

	FormPanel.prototype.init = function (settings) {
		var defaults = {
			submitCaption: translate("LBL_PMSE_FORMPANEL_SUBMIT"),
			items: [],
			submitVisible: true,
			onSubmit: null
		};

		jQuery.extend(true, defaults, settings);

		this._dependencyMap = {};
		this._submitVisible = !!defaults.submitVisible;

		this.setItems(defaults.items)
			.setSubmitCaption(defaults.submitCaption)
			.setOnSubmitHandler(defaults.onSubmit);
	};

	FormPanel.prototype.setOnSubmitHandler = function (handler) {
		if (!(handler === null || typeof handler === 'function')) {
			throw new Error("setOnSubmitHandler(): The parameter must be a function or null.");
		}
		this.onSubmit = handler;
		return this;
	};

	FormPanel.prototype.setItems = function (items) {
		if(this._dependencyMap) {
			CollapsiblePanel.prototype.setItems.call(this, items);
		}
		return this;
	};

	FormPanel.prototype._createField = function (settings) {
		var defaults = {
			type: 'text'
		}, field;

		jQuery.extend(true, defaults, settings);

		switch (defaults.type) {
			case 'text':
				field = new FormPanelText(defaults);
				break;
			case 'integer':
				defaults.precision = 0;
				defaults.groupingSeparator = "";
				field = new FormPanelNumber(defaults);
				break;
			case 'number':
				defaults.precision = -1;
				defaults.groupingSeparator = "";
				field = new FormPanelNumber(defaults);
				break;
			case 'currency':
				defaults.precision = 2;
				field = new FormPanelNumber(defaults);
				break;
			case 'dropdown':
				field = new FormPanelDropdown(defaults);
				break;
			case 'date':
				field = new FormPanelDate(defaults);
				break;
			case 'datetime':
				field = new FormPanelDatetime(defaults);
				break;
			case 'radio':
				field = new FormPanelRadio(defaults);
				break;
			case 'hidden':
				field = new FormPanelHidden(defaults);
				break;
			case 'checkbox':
				field = new FormPanelCheckbox(defaults);
				break;
			case 'button':
				field = new FormPanelButton(defaults);
				break;
			default:
				throw new Error("_createField(): Invalid field type.");
		}
		return field;
	};

	FormPanel.prototype.setSubmitCaption = function (caption) {
		if (typeof caption !== 'string') {
			throw new Error("setSubmitCaption(): The parameter must be a string.");
		}
		this._submitCaption = caption;
		if (this._htmlSubmit) {
			this._htmlSubmit.textContent = caption;
		}
		return this;
	};

	FormPanel.prototype.getItem = function (field) {
		if (typeof field === 'string') {
			return this._items.find("_name", field);
		} else if (typeof field === 'number') {
			return this._items.get(field);
		} else if (field instanceof FormPanelItem && this._items.indexOf(field) >= 0) {
			return field;
		}
		return null;
	};

	FormPanel.prototype._paintItem = function (item, index) {
		var itemAtIndex;
		if(this.html) {
			if (typeof index === 'number') {
				itemAtIndex = this._items.get(index + 1);
			}
			if (itemAtIndex) {
				this._htmlBody.insertBefore(item.getHTML(), itemAtIndex.getHTML());
			} else {
				this._htmlBody.insertBefore(item.getHTML(), this._htmlFooter);
			}
		}
		return this;
	};

	FormPanel.prototype.addItem = function (item, index) {
		var itemToAdd, dependency, dependant, i, dependencyField;
		if(item instanceof FormPanelField) {
			itemToAdd = item;
		} else if (typeof item === 'object') {
			itemToAdd = this._createField(item);
		} else {
			throw new Error('addItem(): the parameter must be an object or an instance of FormPanelField.');
		}
		itemToAdd.setForm(this);
		CollapsiblePanel.prototype.addItem.call(this, itemToAdd, index);
		if (itemToAdd instanceof FormPanelField) {
			dependency = this._dependencyMap[itemToAdd.getName()];
			if (dependency) {
				for (i = 0; i < dependency.length; i += 1) {
					if(dependencyField = this.getItem(dependency[i])) {
						dependencyField.fireDependentFields();
					}
				}
			}
			itemToAdd.fireDependentFields();
		}
		return this;
	};

	FormPanel.prototype.replaceItem = function (newItem, itemToBeReplaced) {
		var itemIndex;
		itemToBeReplaced = this.getItem(itemToBeReplaced);
		itemIndex = this._items.indexOf(itemToBeReplaced);
		if (itemIndex >= 0) {
			this.removeItem(itemToBeReplaced);
			this.addItem(newItem, itemIndex);
		}
		return this;
	};

	FormPanel.prototype.isValid = function () {
		var items = this._items.asArray(), i, valid = true;
		for (i = 0; i < items.length; i += 1) {
			if (items[i] instanceof FormPanelField) {
				valid = valid && items[i].isValid();
				if(!valid) {
					return valid;
				}
			}
		}
		return valid;
	};

	FormPanel.prototype._createBody = function () {
		var element = this.createHTMLElement('form');
		element.className = 'form-panel-body';
		return element;
	};

	FormPanel.prototype.getValueObject = function () {
		var i, fields = this._items.asArray(), valueObject = {
		};

		for(i = 0; i < fields.length; i += 1) {
			if (fields[i] instanceof FormPanelField) {
				valueObject[fields[i].getName()] = fields[i].getValue();
			}
		}
		return valueObject;
	};

	FormPanel.prototype.registerSingleDependency = function (target, dependant) {
		if(!(target instanceof FormPanelField && typeof dependant === 'string' || jQuery.isArray(dependant))) {
			throw new Error("registerSingleDependency(): Incorrect parameters.");
		}
		if (!this._dependencyMap[dependant]) {
			this._dependencyMap[dependant] = [];
		}
		if (this._dependencyMap[dependant].indexOf(dependant) === -1) {
			this._dependencyMap[dependant].push(target.getName());
		}
		return this;
	};

	FormPanel.prototype.registerDependency = function (target, dependantFields) {
		var i;
		if(!(target instanceof FormPanelField && jQuery.isArray(dependantFields))) {
			throw new Error("registerDependency(): Incorrect parameters.");
		}
		for(i = 0; i < dependantFields.length; i += 1) {
			this.registerSingleDependency(target, dependantFields[i]);
		}
		return this;
	};

	FormPanel.prototype.showSubmit = function () {
		this._htmlFooter.style.display = '';
		this._submitVisible = true;
		return this;
	};

	FormPanel.prototype.hideSubmit = function () {
		this._htmlFooter.style.display = 'none';
		this._submitVisible = false;
		return this;
	};

	FormPanel.prototype.reset = function () {
		var items = this._items.asArray(), i;

		for (i = 0; i < items.length; i += 1) {
			if (items[i] instanceof FormPanelField) {
				items[i].reset();
			}
		}

		return this;
	};

	FormPanel.prototype.submit = function () {
		if (this.isValid()) {
			$(this._htmlBody).trigger('submit');
		}
		return this;
	};

	FormPanel.prototype._attachListeners = function () {
		var that;
		if(this.html && !this._attachedListeners) {
			that = this;
			CollapsiblePanel.prototype._attachListeners.call(this);
			jQuery(that._htmlBody).on('submit', function (e) {
				var sendForm = true;
				e.preventDefault();
				if(that.isValid()) {
					if (typeof that.onSubmit === 'function') {
						sendForm = !(that.onSubmit(that) === false);
					}
					if (sendForm) {
						that._onValueAction();
					}
				}
			});
			this._attachedListeners = true;
		}

		return this;
	};

	FormPanel.prototype.createHTML = function () {
		var button, footer;
		if (!this.html) {
			CollapsiblePanel.prototype.createHTML.call(this);
			footer = this.createHTMLElement("div");
			footer.className = "adam form-panel-footer";
			button = this.createHTMLElement("button");
			button.className = 'adam form-panel-submit btn btn-mini';
			footer.appendChild(button);
			this._htmlBody.appendChild(footer);
			this._htmlSubmit = button;
			this._htmlFooter = footer;
			this.setSubmitCaption(this._submitCaption);
			this._attachListeners();

			if (this._submitVisible) {
				this.showSubmit();
			} else {
				this.hideSubmit();
			}
		}
		return this.html;
	};

//FormPanelItem
	var FormPanelItem = function (settings) {
		Element.call(this, settings);
		this._name = null;
		this._label = null;
		this._disabled = null;
		this._form = null;
		FormPanelItem.prototype.init.call(this, settings);
	};

	FormPanelItem.prototype = new Element();
	FormPanelItem.prototype.constructor = FormPanelItem;
	FormPanelItem.prototype.type = "FormPanelItem";

	FormPanelItem.prototype.init = function (settings) {
		var defaults = {
			name: this.id,
			form: null,
			label: "[form-item]",
			disabled: false,
			height: "auto"
		};

		jQuery.extend(true, defaults, settings);

		this.setName(defaults.name)
			.setForm(defaults.form)
			.setLabel(defaults.label)
			.setHeight(defaults.height);

		if (defaults.disabled) {
			this.disable();
		} else {
			this.enable();
		}
	};

	FormPanelItem.prototype.setHeight = function (h) {
		if (!(typeof h === 'number' ||
			(typeof h === 'string' && (h === "auto" || /^\d+(\.\d+)?(em|px|pt|%)?$/.test(h))))) {
			throw new Error("setHeight(): invalid parameter.");
		}
		this.height = h;
	    if (this.html) {
	        this.style.addProperties({height: this.height});
	    }
	    return this;
	};

	FormPanelItem.prototype.setName = function (name) {
		if (typeof name !== 'string') {
			throw new Error("setName(): The parameter must be a string.");
		}
		this._name = name;
		return this;
	};

	FormPanelItem.prototype.getName = function () {
		return this._name;
	};

	FormPanelItem.prototype.setWidth = function (width) {
		return FormPanel.prototype.setWidth.call(this, width);
	};

	FormPanelItem.prototype.setForm = function (form) {
		if(!(form === null || form instanceof FormPanel)) {
			throw new Error("setForm(): The parameter must be an instance of FormPanel or null.");
		}
		this._form = form;
		return this;
	};

	FormPanelItem.prototype.getForm = function () {
		return this._form;
	};

	FormPanelItem.prototype.setLabel = function (label) {
		if (typeof label !== 'string') {
			throw  new Error("setLabel(): The parameter must be a string.");
		}
		this._label = label;
		return this;
	};

	FormPanelItem.prototype.getLabel = function () {
		return this._label;
	};

	FormPanelItem.prototype.enable = function () {
		this._disabled = false;
		return this;
	};

	FormPanelItem.prototype.disable = function () {
		this._disabled = true;
		return this;
	};

	FormPanelItem.prototype.isDisabled = function () {
		return this._disabled;
	};

	FormPanelItem.prototype._attachListeners = function () {};

	FormPanelItem.prototype.setVisible = function (value) {
		if (_.isBoolean(value)) {
	        this.visible = value;
	        if (this.html) {
	            if (value) {
	                this.style.removeProperties(["display"]);
	            } else {
	                this.style.addProperties({display: "none"});
	            }
	        }
	    }
	    return this;
	};

	FormPanelItem.prototype._postCreateHTML = function () {
		this._attachListeners();
		this.style.applyStyle();

        this.style.addProperties({
            width: this.width,
            height: this.height
        });

        if (this._disabled) {
			this.disable();
		} else {
			this.enable();
		}

		this.setVisible(this.visible);

		return this;
	};

	FormPanelItem.prototype.createHTML = function () {
		var html;
		if (!this.html) {
			html = this.createHTMLElement("div");
			html.id = this.id;
			html.className = 'adam form-panel-item';
			this.html = html;
			this._postCreateHTML();
		}
		return this.html;
	};

//FormPanelButton
	var FormPanelButton = function (settings) {
		FormPanelItem.call(this, settings);
		this.onClick = null;
		this._htmlButton = null;
		FormPanelButton.prototype.init.call(this, settings);
	};

	FormPanelButton.prototype = new FormPanelItem();
	FormPanelButton.prototype.constructor = FormPanelButton;
	FormPanelButton.prototype.type = "FormPanelButton";

	FormPanelButton.prototype.init = function (settings) {
		var defaults = {
			onClick: null,
			width: "100%"
		};

		jQuery.extend(true, defaults, settings);

		this.setOnClickHandler(defaults.onClick)
			.setWidth(defaults.width);
	};

	FormPanelButton.prototype.setOnClickHandler = function (handler) {
		if (!(handler === null || typeof handler === 'function')) {
			throw new Error("setOnClickHandler(): The parameter must be a function or null.");
		}
		this.onClick = handler;
		return this;
	};

	FormPanelButton.prototype.enable = function() {
		if (this._htmlButton){
			this._htmlButton.disabled = false;
		}
		return FormPanelItem.prototype.enable.call(this);
	};

	FormPanelButton.prototype.disable = function() {
		if (this._htmlButton) {
			this._htmlButton.disabled = true;
		}
		return FormPanelItem.prototype.disable.call(this);
	};

	FormPanelButton.prototype._attachListeners = function () {
		var that = this;
		if (this.html) {
			jQuery(this._htmlButton).on('click', function () {
				if (typeof that.onClick === 'function') {
					that.onClick(that);
				}
			});
		}

		return this;
	};

	FormPanelButton.prototype._postCreateHTML = function() {
		if (this._htmlButton) {
			FormPanelItem.prototype._postCreateHTML.call(this);
		}
		return this;
	};

	FormPanelButton.prototype.createHTML = function () {
		var html, button;
		if (!this.html)	{
			html = FormPanelItem.prototype.createHTML.call(this);
			html.className += " form-panel-button";
			button = this.createHTMLElement("input");
			button.type = 'button';
			button.value = this._label;
			button.className = "btn btn-mini";
			html.appendChild(button);

			this._htmlButton = button;

			this._postCreateHTML();
		}
		return this.html;
	};

//FormPanelField
	var FormPanelField = function (settings) {
		FormPanelItem.call(this, settings);
		this._value = null;
		this.onChange = null;
		this._htmlControl = [];
		this._htmlControlContainer = null;
		this._htmlLabelContainer = null;
		this._dependantFields = [];
		this._dependencyHandler = null;
		this.required = null;
		this._disabled = null;
		this._form = null;
		this._initialValue = null;
		FormPanelField.prototype.init.call(this, settings);
	};

	FormPanelField.prototype =  new FormPanelItem();
	FormPanelField.prototype.constructor = FormPanelField;
	FormPanelField.prototype.type = "FormPanelField";

	FormPanelField.prototype.init = function (settings) {
		var defaults = {
			label: "[field]",
			onChange: null,
			dependantFields: [],
			dependencyHandler: null,
			value: "",
			required: false
		};

		jQuery.extend(true, defaults, settings);

		this.setLabel(defaults.label)
			.setValue(defaults.value)
			.setRequired(defaults.required)
			.setOnChangeHandler(defaults.onChange)
			.setDependantFields(defaults.dependantFields)
			.setDependencyHandler(defaults.dependencyHandler);

		this._initialValue = this._value;
	};

	FormPanelField.prototype.reset = function () {
		this.setValue(this._initialValue);
		return this;
	};

	FormPanelField.prototype.setForm = function (form) {
		FormPanelItem.prototype.setForm.call(this, form);
		if (form) {
			form.registerDependency(this, this._dependantFields);
		}
		return this;
	};

	FormPanelField.prototype.setRequired = function (required) {
		this.required = !!required;
		return this;
	};

	FormPanelField.prototype._evalRequired = function () {
		if(this.required && !this._disabled) {
			return !!this._value;
		}
		return true;
	};

	FormPanelField.prototype._validateField = function () {
		return this;
	};

	FormPanelField.prototype.isValid = function () {
		var isValid = this._evalRequired();

		if (isValid) {
			isValid = this._validateField();
		}

		if(!isValid && this.html) {
			jQuery(this.html).addClass("error");
		} else {
			jQuery(this.html).removeClass("error");
		}
		return isValid;
	};

	FormPanelField.prototype.setDependencyHandler = function (handler) {
		if(!(handler === null || typeof handler === 'function')) {
			throw new Error("setDependencyHandler(): The parameter must be a function or null.");
		}
		this._dependencyHandler = handler;
		return this;
	};

	FormPanelField.prototype._fireDependencyHandler = function (field, value) {
		if (typeof this._dependencyHandler === 'function') {
			this._dependencyHandler(this, field, value);
		}
		return this;
	};

	FormPanelField.prototype.addDependantField = function (field) {
		if (typeof field === 'string') {
			this._dependantFields.push(field);
			if(this._form) {
				this._form.registerSingleDependency(this, field);
			}
		} else {
			throw new Error("addDependantField(): The parameter must be a string (The name of the dependant field).");
		}
		return this;
	};

	FormPanelField.prototype.setDependantFields = function (fields) {
		var i;
		if(!jQuery.isArray(fields)) {
			throw new Error("setDependantFields(): the parameter must be an array.");
		}
		this._dependantFields = [];
		for (i = 0; i < fields.length; i += 1) {
			this.addDependantField(fields[i]);
		}
		return this;
	};

	FormPanelField.prototype.setLabel = function (label) {
		FormPanelItem.prototype.setLabel.call(this, label);
		if(this._htmlLabelContainer) {
			this._htmlLabelContainer.textContent = label;
		}
		return this;
	};

	FormPanelField.prototype.setOnChangeHandler = function (handler) {
		if (!(handler === null || typeof handler === 'function')) {
			throw new Error("setOnChangeHandler(): The parameter must be a function or null.");
		}
		this.onChange = handler;
		return this;
	};

	FormPanelField.prototype._setValueToControl = function(value) {
		if(this._htmlControl[0]) {
			this._htmlControl[0].value = value;
		}
		return this;
	};

	FormPanelField.prototype.setValue = function (value) {
		var preValue = this._value;
		if(typeof value !== 'string') {
			throw new Error("setValue(): The parameter must be a string.");
		}
		this._setValueToControl(value);
		this._value = value;
		if (value !== preValue) {
			this.fireDependentFields();
		}
		return this;
	};

	FormPanelField.prototype.enable = function () {
		var i;
		if (this._htmlControl && this._htmlControl.length) {
			for (i = 0; i < this._htmlControl.length; i += 1) {
				this._htmlControl[i].disabled = false;
			}
		}
		return FormPanelItem.prototype.enable.call(this);
	};

	FormPanelField.prototype.disable = function () {
		var i;
		if (this._htmlControl && this._htmlControl.length) {
			for (i = 0; i < this._htmlControl.length; i += 1) {
				this._htmlControl[0].disabled = true;
			}
		}
		return FormPanelItem.prototype.disable.call(this);
	};

	FormPanelField.prototype.getValue = function () {
		return this._value;
	};

	FormPanelField.prototype._getValueFromControl = function () {
		var value = "", i;

		for (i = 0; i < this._htmlControl.length; i += 1) {
			value += this._htmlControl[i].value;
		}
		return value;
	};

	FormPanelField.prototype.fireDependentFields = function () {
		var dependantField, value = this._value;
		if(this._form) {
			for(i = 0; i < this._dependantFields.length; i++) {
				dependantField = this._form.getItem(this._dependantFields[i]);
				if (dependantField) {
					dependantField._fireDependencyHandler(this, value);
				}
			}
		}
		return this;
	};

	FormPanelField.prototype._onChangeHandler = function () {
		var that = this;
		return function () {
			var currValue = that._value,
				newValue = that._getValueFromControl(),
				valueHasChanged = currValue !== newValue,
				i, dependantField;

			if(valueHasChanged) {
				that._value = newValue;
				if (typeof that.onChange === 'function') {
					that.onChange(that, newValue, currValue);
				}
				that.fireDependentFields();
			}
		};
	};

	FormPanelField.prototype._attachListeners = function () {
		var i, control;
		if (this.html) {
			for (i = 0; i < this._htmlControl.length; i += 1) {
				jQuery(this._htmlControl[i]).on('change', this._onChangeHandler());
			}
		}
		return this;
	};

	FormPanelField.prototype._createControl = function () {
		var control, i;
		if(!this._htmlControl.length) {
			throw new Error("_createControl(): This method shouldn't be called until the field control is created.");
		}
		this._setValueToControl(this._value);
		for (i = 0; i < this._htmlControl.length; i += 1) {
			control = this._htmlControl[i];
			control.className += ' inherit-width adam form-panel-field-control';
			this._htmlControlContainer.appendChild(control);
		}
		this.setValue(this._value);
		return this;
	};

	FormPanelField.prototype._postCreateHTML = function () {
		if (this._htmlControlContainer) {
			FormPanelItem.prototype._postCreateHTML.call(this);
		}

		return this;
	};

	FormPanelField.prototype.createHTML = function () {
		var html, htmlLabelContainer, span, htmlControlContainer;
		if (!this.html) {
			html = FormPanelItem.prototype.createHTML.call(this);
			html.className += ' adam form-panel-field record-cell';
			html.className += '	adam-' + this.type.toLowerCase();
			htmlLabelContainer = this.createHTMLElement("div");
			htmlLabelContainer.className = 'adam form-panel-label record-label';
			span = this.createHTMLElement("span");
			span.className = 'normal index';
			htmlControlContainer =  this.createHTMLElement("span");
			htmlControlContainer.className = "edit";
			span.appendChild(htmlControlContainer);
			html.appendChild(htmlLabelContainer);
			html.appendChild(span);

			this._htmlLabelContainer = htmlLabelContainer;
			this._htmlControlContainer = htmlControlContainer;
			this.html = html;

			this._createControl()
				.setLabel(this._label);

			this._postCreateHTML();
		}
		return this.html;
	};

//HiddenField
	var FormPanelHidden = function (settings) {
		FormPanelField.call(this, settings);
	};

	FormPanelHidden.prototype = new FormPanelField();
	FormPanelHidden.prototype.constructor = FormPanelHidden;
	FormPanelHidden.prototype.type = "FormPanelHidden";

	FormPanelHidden.prototype._createControl = function() {
		if (!this._htmlControl.length) {
			this._htmlControl[0] = this.createHTMLElement("input");
			this._htmlControl[0].name = this._name;
			this._htmlControl[0].type = "hidden";
			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};

	FormPanelHidden.prototype.createHTML = function () {
		FormPanelField.prototype.createHTML.call(this);
		this.html.style.display = "none";
		return this;
	};

//TextField
	var FormPanelText = function (settings) {
		FormPanelField.call(this, settings);
		this._placeholder = null;
		this.onKeyUp = null;
		this._maxLength = null;
		FormPanelText.prototype.init.call(this, settings);
	};

	FormPanelText.prototype = new FormPanelField();
	FormPanelText.prototype.constructor = FormPanelText;
	FormPanelText.prototype.type = "FormPanelText";

	FormPanelText.prototype.init = function(settings) {
		var defaults = {
			placeholder: "",
			onKeyUp: null,
			maxLength: 0
		};
		jQuery.extend(true, defaults, settings);
		this.setPlaceholder(defaults.placeholder)
			.setOnKeyUpHandler(defaults.onKeyUp)
			.setMaxLength(defaults.maxLength);
	};

	FormPanelText.prototype.setMaxLength = function (maxLength) {
		if (!(typeof maxLength === 'number' && maxLength >= 0)) {
			throw new Error("setMaxLength(): The parameter must be a number major than 0.");
		}
		this._maxLength = maxLength;
		if (this._htmlControl[0]) {
			if (!maxLength) {
				this._htmlControl[0].removeAttribute("maxlength");
			} else {
				this._htmlControl[0].maxLength = maxLength;
			}
		}
		return this;
	};

	FormPanelText.prototype.setOnKeyUpHandler = function (handler) {
		if (!(handler === null || typeof handler === 'function')) {
			throw new Error("setOnKeyUpHandler(): The parameter must be a function or null");
		}
		this.onKeyUp = handler;
		return this;
	};

	FormPanelText.prototype.setPlaceholder = function (placeholder) {
		if(typeof placeholder !== 'string') {
			throw new Error("setPlaceholder(): The parameter must be a string.")
		}
		this._placeholder = placeholder;
		if (this._htmlControl[0]) {
			this._htmlControl[0].placeholder = placeholder;
		}
		return this;
	};

	FormPanelText.prototype._onKeyUp = function () {
		var that = this;

		return function (e) {
			if (typeof that.onKeyUp === 'function') {
				that.onKeyUp(that, that._htmlControl[0].value, e.keyCode);
			}
		};
	};

	FormPanelText.prototype._attachListeners = function() {
		if (this.html) {
			FormPanelField.prototype._attachListeners.call(this);
			jQuery(this._htmlControl[0]).on('keyup', this._onKeyUp());
		}
		return this;
	};

	FormPanelText.prototype._createControl = function () {
		if (!this._htmlControl.length) {
			this._htmlControl[0] = this.createHTMLElement("input");
			this._htmlControl[0].name = this._name;
			this._htmlControl[0].type = "text";
			this.setMaxLength(this._maxLength);
			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};

	FormPanelText.prototype.createHTML = function () {
		if(!this.html) {
			FormPanelField.prototype.createHTML.call(this);
			this.setPlaceholder(this._placeholder);
		}
		return this.html;
	};
//FormPanelNumber
	var FormPanelNumber = function (settings) {
		FormPanelField.call(this, settings);
		this._decimalSeparator = null;
		this._groupingSeparator = null;
		this._precision = null;
		this._initialized = false;
		FormPanelNumber.prototype.init.call(this, settings);
	};

	FormPanelNumber.prototype = new FormPanelField();
	FormPanelNumber.prototype.constructor = FormPanelNumber;
	FormPanelNumber.prototype.type = "FormPanelNumber";

	FormPanelNumber.prototype.init = function (settings) {
		var defaults = {
			decimalSeparator: ".",
			groupingSeparator: ",",
			precision: -1,
			value: null
		};

		jQuery.extend(true, defaults, settings);

		this.setDecimalSeparator(defaults.decimalSeparator)
			.setGroupingSeparator(defaults.groupingSeparator)
			.setPrecision(defaults.precision)
			.setValue(defaults.value);

		this._initialized = true;
	};

	FormPanelNumber.prototype._setValueToControl = function (value) {
		var integer, decimal, label = "", aux, power, i, decimalSeparator;
		if (this._htmlControl[0]) {
			this._htmlControl[0].value = this._parseToUserString(value);
		}
		return this;
	};

	FormPanelNumber.prototype._getValueFromControl = function () {
		var groupingSeparatorRegExp, numberParts, value = this._htmlControl[0].value, numericValue;

		if (this._groupingSeparator) {
			groupingSeparatorRegExp = new RegExp((this._isRegExpSpecialChar(this._groupingSeparator) ? "\\" : "") + this._groupingSeparator, "g");
			value = value.replace(groupingSeparatorRegExp, "");
		}
		if ((numberParts = value.split(this._decimalSeparator)).length > 2) {
			return null;
		}
		numberParts[1] = numberParts[1] || "0";
		if (!/^\-?\d+$/.test(numberParts[0]) || !/^\d+$/.test(numberParts[1])) {
			return null;
		}
		numericValue = parseInt(numberParts[0], 10);
		numericValue += (parseInt(numberParts[1], 10) / Math.pow(10, numberParts[1].length));

		this._htmlControl[0].value = this._parseToUserString(numericValue);

		return numericValue;
	};

	FormPanelNumber.prototype.setValue = function (value) {
		var preValue = this._value;
		if (!this._decimalSeparator) {
			return this;
		}
		if (!(value === null || typeof value === 'number')) {
			throw new Error("setValue(): The parameter must be a number.");
		}
		this._setValueToControl(value);
		this._value = value;
		if (value !== preValue) {
			this.fireDependentFields();
		}
		return this;
	};

	FormPanelNumber.prototype.setDecimalSeparator = function (decimalSeparator) {
		if (!(typeof decimalSeparator === 'string' && decimalSeparator.length === 1)) {
			throw new Error("setDecimalSeparator(): The parameter must be a single character.");
		}
		if (!isNaN(decimalSeparator) || ["+", "-", "/", "*"].indexOf(decimalSeparator) >= 0) {
			throw new Error("setDecimalSeparator(): Invalid parameter.");
		}
		if (decimalSeparator === this._groupingSeparator) {
			throw new Error("setDecimalSeparator(): The decimal separator must be different than the "
				+ "grouping separator.");
		}
		this._decimalSeparator = decimalSeparator;
		//we make sure that the object has already been initialized
		if (this._initialized) {
			this.setValue(this._value);
		}
		return this;
	};

	FormPanelNumber.prototype.setGroupingSeparator = function (groupingSeparator) {
		if (!(typeof groupingSeparator === 'string' && groupingSeparator.length <= 1)) {
			throw new Error("setGroupingSeparator(): The parameter must be a single character or empty string.");
		}
		if (!(isNaN(groupingSeparator)
			|| ["+", "-", "/", "*"].indexOf(groupingSeparator) < 0)) {
			throw new Error("setGroupingSeparator(): Invalid parameter.");
		}
		if (groupingSeparator === this._decimalSeparator) {
			throw new Error("setGroupingSeparator(): The grouping separator must be different than the "
				+ "decimal separator.");
		}
		this._groupingSeparator = groupingSeparator;
		//we make sure that the object has already been initialized
		if (this._initialized) {
			this.setValue(this._value);
		}
		return this;
	};

	FormPanelNumber.prototype.setPrecision = function (precision) {
		if (!(typeof precision === 'number' && precision % 1 === 0)) {
			throw new Error("setPrecision(): The parameter must be an integer.");
		}
		this._precision = precision;
		//we make sure that the object has already been initialized
		if (this._initialized) {
			this.setValue(this._value);
		}
		return this;
	};

	FormPanelNumber.prototype._createControl = function () {
		if (!this._htmlControl.length) {
			this._htmlControl[0] = this.createHTMLElement("input");
			this._htmlControl[0].type = "text";
			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};

	FormPanelNumber.prototype._isRegExpSpecialChar = function (c) {
		switch (c) {
		    case "\\":
		    case "^":
		    case "$":
		    case "*":
		    case "+":
		    case "?":
		    case ".":
		    case "(":
		    case ")":
		    case "|":
		    case "{":
		    case "}":
		        return true;
		        break;
	    }
	    return false;
	};

	FormPanelNumber.prototype.isValid = function () {
		var isValid = FormPanelField.prototype.isValid.call(this),
			value = this._htmlControl[0].value;

		if (value && this._value === null) {
			isValid = false;
		}

		if(!isValid && this.html) {
			jQuery(this.html).addClass("error");
		} else {
			jQuery(this.html).removeClass("error");
		}
		return isValid;
	};

	FormPanelNumber.prototype._parseToUserString = function (value) {
		var integer, decimal, label = "", aux, power, i, decimalSeparator;
		if (value === null) {
			label = "";
		} else {
			if (this._precision >= 0) {
				power = Math.pow(10, this._precision);
				value = Math.round(value * power) /power;
			}
			decimalSeparator = this._precision === 0 ? "" : this._decimalSeparator;
			integer = aux = Math.floor(value).toString();

			if (this._precision !== 0) {
				decimal = "";
				decimal = value.toString().split(".")[1] || (this._precision < 0 ? "0" : "");
				for (i = decimal.length; i < this._precision; i += 1) {
					decimal += "0";
				}
			} else {
				decimal = "";
			}

			if (this._groupingSeparator) {
				while (aux.length > 3) {
					label = this._groupingSeparator + aux.substr(-3) + label;
					aux = aux.slice(0, -3);
				}
			}
			label = aux + label + decimalSeparator + decimal;
		}
		return label;
	};

	FormPanelNumber.prototype._onKeyDown = function () {
		var that = this;
		return function (e) {
			if (that._precision === 0 && (e.keyCode < 48 || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode >105)
				&& e.keyCode !== 37 && e.keyCode !== 39 && e.keyCode !== 8 && e.keyCode !== 46) {
				e.preventDefault();
			}
		};
	};

	FormPanelNumber.prototype._attachListeners = function() {
		if (this.html) {
			jQuery(this._htmlControl[0]).on('keydown', this._onKeyDown());
			FormPanelField.prototype._attachListeners.call(this);
		}
		return this;
	};
//FormPanelDate
	var FormPanelDate = function (settings) {
		FormPanelField.call(this, settings);
		this._dom = {};
		this._dateObject = null;
		this._dateFormat = null;
		FormPanelDate.prototype.init.call(this, settings);
	};

	FormPanelDate.prototype = new FormPanelField();
	FormPanelDate.prototype.constructor = FormPanelDate;
	FormPanelDate.prototype.type = "FormPanelDate";

	//Returns a date in the specified format.
	FormPanelDate.format = function (value, format) {
		if (!value) {
			return value;
		}
		value = App.date(value);
		return value.isValid() ? value.format(format) : null;
	};

	FormPanelDate.prototype.init = function (settings) {
		var defaults = {
			dateFormat: "YYYY-MM-DD"
		};

		jQuery.extend(true, defaults, settings);

		this.setDateFormat(defaults.dateFormat);
	};

	FormPanelDate.prototype.open = function () {
		jQuery(this._htmlControl[0]).datepicker('show');
		return this;
	};

	FormPanelDate.prototype.close = function () {
		jQuery(this._htmlControl[0]).datepicker('hide');
		return this;
	};

	FormPanelDate.prototype._validateField = function () {
		var isValid;

		return this._value !== null || this._htmlControl[0].value === "";
	};

	FormPanelDate.prototype._setValueToControl = function (value) {
		return FormPanelField.prototype._setValueToControl.call(this, FormPanelDate.format(value, this._dateFormat));
	};
	FormPanelDate.prototype._getValueFromControl = function () {
		return this._unformat(this._htmlControl[0].value);
	};
	//Returns a date value in ISO format
	FormPanelDate.prototype._unformat = function (value) {
		value = App.date(value, this._dateFormat, true);
		return value.isValid() ? value.format("YYYY-MM-DD") : null;
	};

	FormPanelDate.prototype.getFormattedDate = function () {
		return FormPanelDate.format(this._value, this._dateFormat);
	};

	FormPanelDate.prototype._attachListeners = function() {
		if (this.html) {
			jQuery(this._htmlControl[0]).on('changeDate change', this._onChangeHandler())
				.on("show", function() {
					$('.datepicker').filter(":visible").css("z-index", 1300);
				});
		}
		return this;
	};

	FormPanelDate.prototype.setDateFormat = function (dateFormat) {
		if (typeof dateFormat !== 'string') {
			throw new Error("setFormat(): The parameter must be a string.");
		}
		this._dateFormat = dateFormat;
		if (this._htmlControl[0]) {
			$(this._htmlControl[0]).datepicker({
				format: this._dateFormat.toLowerCase()
			});
		}
		return this;
	};

	FormPanelDate.prototype._createControl = function () {
		if (!this._htmlControl[0]) {
			this._htmlControl[0] = this.createHTMLElement("input");
			this._htmlControl[0].name = this._name;
			this._htmlControl[0].type = "text";
			this.setDateFormat(this._dateFormat);

			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};
//FormPanelDatetime
	var FormPanelDatetime = function (settings) {
		FormPanelDate.call(this, settings);
		this._timeFormat = null;
		FormPanelDatetime.prototype.init.call(this, settings);
	};

	FormPanelDatetime.prototype = new FormPanelDate();
	FormPanelDatetime.prototype.constructor = FormPanelDatetime;
	FormPanelDatetime.prototype.type = "FormPanelDatetime";

	//Returns a date time in the specified format.
	FormPanelDatetime.format = function (value, dateFormat, timeFormat) {
		if (!value) {
			return value;
		}
		value = App.date(value);
		return value.isValid() ? value.format(dateFormat + " " + App.date.convertFormat(timeFormat))
			: null;
	};

	FormPanelDatetime.prototype.init = function (settings) {
		var defaults = {
			timeFormat: 'H:i'
		};

		jQuery.extend(true, defaults, settings);

		this.setTimeFormat(defaults.timeFormat);
	};

	FormPanelDatetime.prototype.openTime = function () {
		jQuery(this._htmlControl[1]).timepicker('show');
		return this;
	};

	FormPanelDatetime.prototype.closeTime = function () {
		jQuery(this._htmlControl[1]).timepicker('hide');
		return this;
	};

	FormPanelDatetime.prototype.closeAll = function() {
		return this.close().closeTime();
	};

	FormPanelDatetime.prototype._setValueToControl = function (value) {
		var date, time;
		if (!this._htmlControl.length) {
			return this;
		}
		if (!value) {
			this._htmlControl[0].value = this._htmlControl[1].value = "";
		} else {
			date = value.split("T");
			time = date[1];
			date = date[0];
			if (this._htmlControl[1]) {
				FormPanelDate.prototype._setValueToControl.call(this, date);
				time = time.split(/[\+\-]/);
				time = time[0];
				jQuery(this._htmlControl[1]).timepicker("setTime", time);
			}
		}
		return this;
	};

    FormPanelDatetime.prototype._unformat = function (value) {
        value = App.date(value, this._dateFormat, true);
        return value.isValid() ? value.format() : null;
    };

	FormPanelDatetime.prototype._getValueFromControl = function () {
		var value = "", date, time, isValid = false, aux;

		if (this._htmlControl.length) {
			date = this._htmlControl[0].value;
			time = this._htmlControl[1].value;
			if (date && time) {
				value = SUGAR.App.date(date + " " + time, this._dateFormat + " " + SUGAR.App.date.convertFormat(this._timeFormat), true);
				isValid = value.isValid();
			}
			if (!isValid) {
				if (date && !FormPanelDate.prototype._unformat.call(this, date)) {
					this._htmlControl[0].value = "";
				}
				//if date has changed then it means that the time was wrong
				if (time) {
					jQuery(this._htmlControl[1]).timepicker("setTime", time);
				}
				value = null;
			} else {
				value = value.format();
			}
		}
		return value;
	};

	FormPanelDatetime.prototype.setValue = function (value) {
		var preValue = this._value, splittedDate, aux, aux2, invalidValueMessage ="setValue(): Invalid value.";
		if (!(value === null || typeof value === 'string')) {
			throw new Error("setValue(): The parameter must be a string.");
		}
		if (!(typeof value === 'string' && (value === "" || (App.date(value)).isValid()))) {
			throw new Error(invalidValueMessage);
		}
		this._setValueToControl(value);
		this._value = value;
		if (value !== preValue) {
			this.fireDependentFields();
		}
		return this;
	};

	FormPanelDatetime.prototype.setTimeFormat = function (timeFormat) {
		var timeControl, formattedTime = "", timeParts, aux, dayPeriod;
		switch (timeFormat) {
			case "H:i":
			case "h:ia":
			case "h:iA":
			case "h:i a":
			case "h:i A":
			case "H.i":
			case "h.ia":
			case "h.iA":
			case "h.i a":
			case "h.i A":
				this._timeFormat = timeFormat;
				break;
			default:
				throw new Error("setTimeFormat(): invalid format.");
		}
		if (timeControl = this._htmlControl[1]) {
			if (this._dateObject) {
				timeParts = timeFormat.split("");
				aux = this._dateObject.getHours();
				aux = (timeParts[0] === "h" && aux > 12) ? aux - 12 : aux;
				aux = aux < 10 ? "0" + aux : aux;
				formattedTime += aux + timeParts[1];

				aux = this._dateObject.getMinutes();
				formattedTime += (aux < 10 ? "0" + aux : aux);

				if (timeParts.length > 3) {
					dayPeriod = this._dateObject.getHours() < 12 ? "am" : "pm";
					if (timeParts[3] === "a") {
						formattedTime += dayPeriod;
					} else if (timeParts[3] === "A") {
						formattedTime += dayPeriod.toUpperCase();
					} else {
						formattedTime += " "
							+ (timeParts[4] === "A" ? dayPeriod.toUpperCase() : (timeParts[4] === "a" ? dayPeriod : ""));
					}
				}
			}
			this._htmlControl[1].value = formattedTime;
			jQuery(this._htmlControl[1]).timepicker({
				timeFormat: timeFormat,
				appendTo: function (a) {
					return a.parent().parent().parent().parent().parent().parent().parent();
				}
			});
		}
		return this;
	};

	FormPanelDatetime.prototype.getFormattedDate = function () {
		return FormPanelDatetime.format(this._value, this._dateFormat, this._timeFormat);
	};

	FormPanelDatetime.prototype._attachListeners = function	() {
		if (this.html) {
			FormPanelDate.prototype._attachListeners.call(this);
			jQuery(this._htmlControl[1]).on('change', this._onChangeHandler());
		}
		return this;
	};

	FormPanelDatetime.prototype._createControl = function () {
		if (!this._htmlControl.length) {
			this._htmlControl[1] = this.createHTMLElement("input");
			this._htmlControl[1].type = "text";
			this.setTimeFormat(this._timeFormat);
			FormPanelDate.prototype._createControl.call(this);
		}
		return this;
	};
//FormPanelDropdown
	var FormPanelDropdown = function (settings) {
		FormPanelField.call(this, settings);
		this._options = null;
		this._proxy = null;
		this._dataURL = null;
		this._dataRoot = null;
		this._massiveAction = false;
		this._labelField = null;
		this._valueField = null;
        this._attributes = null;
		this._optionsFilter = null;

		FormPanelDropdown.prototype.init.call(this, settings);
	};

	FormPanelDropdown.prototype = new FormPanelField();
	FormPanelDropdown.prototype.constructor = FormPanelDropdown;
	FormPanelDropdown.prototype.type = "FormPanelDropdown";

	FormPanelDropdown.prototype.init = function (settings) {
		var defaults = {
			options: [],
			value: "",
			dataURL: null,
			dataRoot: null,
			labelField: "label",
			valueField: "value",
			optionsFilter: null
		};

		jQuery.extend(true, defaults, settings);

		this._proxy = new SugarProxy();

		FormPanelField.prototype.setValue.call(this, defaults.value);
		this._options = new ArrayList();

		this.setOptionsFilter(defaults.optionsFilter)
			.setDataURL(defaults.dataURL)
			.setDataRoot(defaults.dataRoot)
			.setLabelField(defaults.labelField)
			.setValueField(defaults.valueField);

		if (typeof defaults._dataURL === 'string') {
			this.load();
		} else {
			this.setOptions(defaults.options);
		}
	};

	FormPanelDropdown.prototype.setOptionsFilter = function (filter) {
		if (!(filter === null || typeof filter === 'function')) {
			throw new Error('setOptionsFilter(): The parameter must be a function.');
		}
		this._optionsFilter = filter;

		return this;
	};

	FormPanelDropdown.prototype.setLabelField = function (field) {
		if (typeof field !== 'string') {
			throw new Error('setLabelField(): The parameter must be a string.');
		}
		this._labelField = field;
		return this;
	};

	FormPanelDropdown.prototype.getLabelField = function (field) {
		return this._labelField;
	};

	FormPanelDropdown.prototype.setValueField = function (field) {
		if (!(typeof field === 'string' || typeof field === "function")) {
			throw new Error('setValueField(): The parameter must be a string.');
		}
		this._valueField = field;
		return this;
	};

	FormPanelDropdown.prototype.getValueField = function () {
		return this._valueField;
	};

	FormPanelDropdown.prototype._showLoadingMessage = function() {
		var option;
		if (this._htmlControl.length) {
			option = this.createHTMLElement('option');
			option.value = "";
			option.label = option.textContent = 'loading...';
			option.className = 'adam form-apnel-dropdown-loading';
			option.selected = true;
			this.disable();
			this._htmlControl[0].appendChild(option);
		}
		return this;
	};

	FormPanelDropdown.prototype._removeLoadingMessage = function () {
		jQuery(this._htmlControl[0]).find('adam form-apnel-dropdown-loading').remove();
		this.enable();
		return this;
	};

	FormPanelDropdown.prototype._onLoadDataSuccess = function () {
		var that = this;
		return function (data) {
			var items = that._dataRoot ? data[that._dataRoot] : data;
			that._removeLoadingMessage();
			that.setOptions(items);
		};
	};

	FormPanelDropdown.prototype.load = function () {
		if (typeof this._dataURL !== 'string') {
			throw new Error("load(): The dataURL wasn't set properly.");
		}
		this._proxy.url = this._dataURL;
		this.clearOptions();
		this._showLoadingMessage();
		this._proxy.getData(this._attributes, {
			success: this._onLoadDataSuccess()
		});
		return this;
	};

	FormPanelDropdown.prototype.setDataRoot = function (root) {
		if (!(root === null || typeof root === 'string')) {
			throw new Error("setDataRoot(): The parameter must be a string or null.");
		}
		this._dataRoot = root;
		return this;
	};

	FormPanelDropdown.prototype.setDataURL = function (url) {
		if (!(url === null || typeof url === 'string')) {
			throw new Error("setDataURL(): The parameter must be a string or null.");
		}
		this._dataURL = url;
		return this;
	};

    FormPanelDropdown.prototype.setAttributes = function(attributes) {
        if (!(attributes === null || typeof attributes === 'object')) {
            throw new Error("setAttributes(): The parameter must be a object or null.");
        }
        this._attributes = attributes;
        return this;
    };

	FormPanelDropdown.prototype.existsValueInOptions = function (value) {
		var i, options = this._options.asArray();

		if (typeof this._valueField === "string") {
			return !!this._options.find(this._valueField, value);
		} else {
			for (i = 0; i < options[i]; i += 1) {
				if (value === this._valueField(options[i])) {
					return true;
				}
			}
		}
		return false;
	};

	FormPanelDropdown.prototype._getFirstAvailableOption = function () {
		var items, i;
		if(this._options) {
			items = this._options.asArray();
			return items[0] || null;
		}
		return null;
	};

	FormPanelDropdown.prototype.getSelectedText = function () {
		return jQuery(this.html).find("option:selected").text();
	};

	FormPanelDropdown.prototype.getSelectedData = function (key) {
		var data = jQuery(this.html).find("option:selected").data("data");
		if (key !== undefined) {
			if (typeof key !== 'string') {
				throw new Error('getSelectedData(): The parameter is optional, however if it is supplied it must be a '
					+ 'string');
			}
			return data[key];
		} else {
			return data;
		}
	};

	FormPanelDropdown.prototype.setValue = function (value) {
		var firstOption;
		if(this._options) {
			if(this.existsValueInOptions(value)) {
				FormPanelField.prototype.setValue.call(this, value);
			} else {
				firstOption = this._getFirstAvailableOption();
				if (firstOption) {
					firstOption = typeof this._valueField === "function" ? this._valueField(this, firstOption) : firstOption[this._valueField];
				} else {
					firstOption = "";
				}
				FormPanelField.prototype.setValue.call(this, firstOption || "");
			}
		}
		return this;
	};

	FormPanelDropdown.prototype.clearOptions = function () {
		jQuery(this._htmlControl[0]).empty();
		this._options.clear();
		this._value = "";
		return this;
	};

	FormPanelDropdown.prototype._paintOption = function (item) {
		var option = this.createHTMLElement('option');
		option.label = option.textContent = item[this._labelField];
		option.value = typeof this._valueField === 'function' ? this._valueField(this, item) : item[this._valueField];
		jQuery(option).data("data", item);
		this._htmlControl[0].appendChild(option);
		return this;
	};

	FormPanelDropdown.prototype.addOption = function (option) {
		var newOption;
		if(typeof option === 'object') {
			newOption = cloneObject(option);
			if (typeof this._optionsFilter === 'function' && !this._optionsFilter(option)) {
				return this;
			}
			this._options.insert(newOption);
			if(this.html && !this._massiveAction) {
				this._paintOption(newOption);
			}
		}
		return this;
	};

	FormPanelDropdown.prototype._paintOptions = function () {
		var i, options = this._options.asArray();
		if(this.html) {
			jQuery(this._htmlControl[0]).empty();
			for (i = 0; i < options.length; i += 1) {
				this._paintOption(options[i]);
			}
		}
		return this;
	};

	FormPanelDropdown.prototype.setOptions = function (options) {
		var i, value;
		if(!jQuery.isArray(options)) {
			throw new Error("setOptions(): The parameter must be an array.");
		}
		value = this._value;
		this._massiveAction = true;
		this.clearOptions();
		for(i = 0; i < options.length; i += 1) {
			this.addOption(options[i]);
		}
		this._paintOptions();
		this._massiveAction = false;
		this.setValue(value);
		return this.html;
	};

	FormPanelDropdown.prototype.reset = function () {};

	FormPanelDropdown.prototype._createControl = function () {
		if(!this._htmlControl[0]) {
			this._htmlControl[0] = this.createHTMLElement("select");
			this._htmlControl[0].name = this._name;
			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};

	FormPanelDropdown.prototype.createHTML = function () {
		if (!this.html) {
			FormPanelField.prototype.createHTML.call(this);
			this._paintOptions();
			this._setValueToControl(this._value);
			this._value = this._getValueFromControl();
		}
		return this;
	};

//FormPanelRadio
	var FormPanelRadio = function (settings) {
		FormPanelField.call(this, settings);
		this._options = [];
		FormPanelRadio.prototype.init.call(this, settings);
	};

	FormPanelRadio.prototype = new FormPanelField();
	FormPanelRadio.prototype.constructor = FormPanelRadio;
	FormPanelRadio.prototype.type = "FormPanelRadio";

	FormPanelRadio.prototype.init = function(settings) {
		var defaults = {
			options: []
		};

		jQuery.extend(true, defaults, settings);

		this.setOptions(defaults.options)
			.setValue(defaults.value !== undefined ? defaults.value : this._value);
	};

	FormPanelRadio.prototype._setValueToControl = function (value) {
		var i, control;
		for (i = 0; i < this._htmlControl.length; i += 1) {
			control = jQuery(this._htmlControl[i]).find("input").get(0);
			if (control.value === value) {
				control.checked = true;
			} else {
				control.checked = false;
			}
		}
		return this;
	};

	FormPanelRadio.prototype.setValue = function (value) {
		FormPanelField.prototype.setValue.call(this, value);
		if (this._htmlControl.length) {
			this._value = this._getValueFromControl();
		}
		return this;
	};

	FormPanelRadio.prototype.setOptions = function (options) {
		var i;
		if (!jQuery.isArray(options)) {
			throw new Error("setOptions(): The parameter must be an array.");
		}
		for (i = 0; i < options.length; i += 1) {
			if (options[i].selected === true) {
				this._value = options[i].value;
			}
		}
		this._options = options;
		return this;
	};

	FormPanelRadio.prototype._getValueFromControl = function() {
		var $items, i, value = "";

		if (this._htmlControl.length) {
			$items = jQuery(this._htmlControl[0]);

			for (i = 1; i < this._htmlControl.length; i += 1) {
				$items.add(this._htmlControl[i]);
			}

			$items = $items.find(":checked");

			if ($items.length) {
				value = $items.val();
			}
		}

		return value;
	};

	FormPanelRadio.prototype._createControl = function () {
		var i, option, label;
		if (!this._htmlControl.length) {
			for (i = 0; i < this._options.length; i += 1) {
				label = this.createHTMLElement('label');
				option = this.createHTMLElement('input');
				option.type = "radio";
				option.name = this._name;
				option.value = this._options[i].value;
				option.className = "adam formpanel-radio";
				option.checked= !!this._options[i].selected;
				label.appendChild(option);
				label.appendChild(document.createTextNode(this._options[i].label));
				this._htmlControl.push(label);
			}
			FormPanelField.prototype._createControl.call(this);
		}
		return this;
	};

//FormPanelCheckbox
	var FormPanelCheckbox = function (settings) {
		FormPanelField.call(this, settings);
	};

	FormPanelCheckbox.prototype = new FormPanelField();
	FormPanelCheckbox.prototype.constructor = FormPanelCheckbox;
	FormPanelCheckbox.prototype.type = 'FormPanelCheckbox';

	FormPanelCheckbox.prototype._setValueToControl = function (value) {
		if (this._htmlControl[0]) {
			this._htmlControl[0].checked = !!value;
		}
		return this;
	};

	FormPanelCheckbox.prototype._getValueFromControl = function () {
		return this._htmlControl[0].checked;
	};

	FormPanelCheckbox.prototype.setValue = function (value) {
		var preValue = this._value;
		this._setValueToControl(!!value);
		this._value = !!value;
		if (value !== preValue) {
			this.fireDependentFields();
		}
		return this;
	};

	FormPanelCheckbox.prototype._createControl = function () {
		if (!this._htmlControl.length) {
			this._htmlControl[0] =  this.createHTMLElement("input");
			this._htmlControl[0].type = "checkbox";
			this._htmlControl[0].name = this._name;
			FormPanelField.prototype._createControl.call(this);
		}

		return this;
	};
