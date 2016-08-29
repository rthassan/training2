/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */

/**
 * @class a YAHOO.util.DDProxy implementation. During the drag over event, the
 * dragged element is inserted before the dragged-over element.
 *
 * @extends YAHOO.util.DDProxy
 * @constructor
 * @param {String} id the id of the linked element
 * @param {String} sGroup the group of related DragDrop objects
 */
function ygDDList(id, sGroup) {

	if (id) {
		this.init(id, sGroup);
		this.initFrame();
	}

	var s = this.getDragEl().style;
	s.borderColor = "transparent";
	s.backgroundColor = "#f6f5e5";
	s.opacity = 0.76;
	s.filter = "alpha(opacity=76)";
}

ygDDList.prototype = new YAHOO.util.DDProxy();

ygDDList.prototype.borderDiv = null;
ygDDList.prototype.originalDisplayProperties = Array();

// Bug #47097 : Dashlets not displayed after moving them
ygDDList.prototype.dashletID = null;
ygDDList.prototype.needsReloadAfterDrop = false;

ygDDList.prototype.startDrag = function(x, y) {
	//this.logger.debug(this.id + " startDrag");

	var dragEl = this.getDragEl();
	var clickEl = this.getEl();

    // Bug #47097 : Dashlets not displayed after moving them
    this.needsReloadAfterDrop = false;
    var chartContainer = YAHOO.util.Dom.getElementsByClassName('chartContainer', 'div', clickEl);
    if ( chartContainer.length != 0 ) {
        // try to find OBJECT tab in canvas if success - SWF used and it needs to be reloaded
        var cee_canvas = YAHOO.util.Dom.get(this.dashletID+'-canvas');
        if ( typeof cee_canvas != 'undefined' && cee_canvas ) {
            // if there is object tag
            var canvas_objects = YAHOO.util.Dom.getElementsBy(function(el){ return true;}, 'OBJECT', cee_canvas);
            if ( canvas_objects.length != 0 ) {
                this.needsReloadAfterDrop = true;
            }
        }
        chartContainer.innerHTML = '';
    }
    
	dragEl.innerHTML = clickEl.innerHTML;
	dragElObjects = dragEl.getElementsByTagName('object');

	
	dragEl.className = clickEl.className;
	dragEl.style.color = clickEl.style.color;
	dragEl.style.border = "1px solid #aaa";

	// save the style of the object 
	clickElRegion = YAHOO.util.Dom.getRegion(clickEl);
	
	this.borderDiv = document.createElement('div'); // create a div to display border
	this.borderDiv.style.height = (clickElRegion.bottom - clickElRegion.top) + 'px';
	this.borderDiv.style.border = '2px dashed #cccccc';
	
	for(i in clickEl.childNodes) { // hide contents of the target elements contents
		if(typeof clickEl.childNodes[i].style != 'undefined') {
			this.originalDisplayProperties[i] = clickEl.childNodes[i].style.display;
			clickEl.childNodes[i].style.display = 'none';
		}

	}
	clickEl.appendChild(this.borderDiv);
};

ygDDList.prototype.endDrag = function(e) {
	// disable moving the linked element
	var clickEl = this.getEl();

	clickEl.removeChild(this.borderDiv); // remove border div
	
	for(i in clickEl.childNodes) { // show target elements contents
		if(typeof clickEl.childNodes[i].style != 'undefined') {
			clickEl.childNodes[i].style.display = this.originalDisplayProperties[i];
		}
	}
	
	if(this.clickHeight) 
	    clickEl.style.height = this.clickHeight;
	else 
		clickEl.style.height = '';
	
	if(this.clickBorder) 
	    clickEl.style.border = this.clickBorder;
	else 
		clickEl.style.border = '';
		
	dragEl = this.getDragEl();
	dragEl.innerHTML = '';

	this.afterEndDrag(e);
    
    // Bug #47097 : Dashlets not displayed after moving them
    if ( this.needsReloadAfterDrop && this.dashletID ) {
        SUGAR.mySugar.retrieveDashlet(this.dashletID); //"predefined_chart"
    }
};

ygDDList.prototype.afterEndDrag = function(e) {

}

ygDDList.prototype.onDrag = function(e, id) {
    
};

ygDDList.prototype.onDragOver = function(e, id) {
	// this.logger.debug(this.id.toString() + " onDragOver " + id);
	var el;
        
    if ("string" == typeof id) {
        el = YAHOO.util.DDM.getElement(id);
    } else { 
        el = YAHOO.util.DDM.getBestMatch(id).getEl();
    }
    
	dragEl = this.getDragEl();
	elRegion = YAHOO.util.Dom.getRegion(el);
	    
//    this.logger.debug('id: ' + el.id);
//    this.logger.debug('size: ' + (elRegion.bottom - elRegion.top));
//    this.logger.debug('getPosY: ' + YAHOO.util.DDM.getPosY(el));
	var mid = YAHOO.util.DDM.getPosY(el) + (Math.floor((elRegion.bottom - elRegion.top) / 2));
//    this.logger.debug('mid: ' + mid);
    	
//    this.logger.debug(YAHOO.util.DDM.getPosY(dragEl) + " <  " + mid);
//    this.logger.debug("Y: " + YAHOO.util.Event.getPageY(e));
	
	if (YAHOO.util.DDM.getPosY(dragEl) < mid ) { // insert on top triggering item
		var el2 = this.getEl();
		var p = el.parentNode;
		p.insertBefore(el2, el);
	}
	if (YAHOO.util.DDM.getPosY(dragEl) >= mid ) { // insert below triggered item
		var el2 = this.getEl();
		var p = el.parentNode;
		p.insertBefore(el2, el.nextSibling);
	}
};

ygDDList.prototype.onDragEnter = function(e, id) {
	// this.logger.debug(this.id.toString() + " onDragEnter " + id);
	// this.getDragEl().style.border = "1px solid #449629";
};

ygDDList.prototype.onDragOut = function(e, id) {
    // I need to know when we are over nothing
	// this.getDragEl().style.border = "1px solid #964428";
}

/////////////////////////////////////////////////////////////////////////////

function ygDDListBoundary(id, sGroup) {
	if (id) {
		this.init(id, sGroup);
		//this.logger = new ygLogger("ygDDListBoundary");
		this.isBoundary = true;
	}
}

ygDDListBoundary.prototype = new YAHOO.util.DDTarget();

/* End of File include/javascript/yui/ygDDList.js */

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
(function() {
	//Do not double define
	if (SUGAR.EmailAddressWidget) return;
	
	var Dom = YAHOO.util.Dom;
	
	SUGAR.EmailAddressWidget = function(module) {
		if (!SUGAR.EmailAddressWidget.count[module]) SUGAR.EmailAddressWidget.count[module] = 0;
		this.count = SUGAR.EmailAddressWidget.count[module];
		SUGAR.EmailAddressWidget.count[module]++;
		this.module = module;
		this.id = this.module + this.count;
		if (document.getElementById(module+'_email_widget_id'))
		    document.getElementById(module+'_email_widget_id').value = this.id;
		SUGAR.EmailAddressWidget.instances[this.id] = this;
	}
	
	SUGAR.EmailAddressWidget.instances = {};
	SUGAR.EmailAddressWidget.count = {};
	
	SUGAR.EmailAddressWidget.prototype = {
	    emailTemplate : '<tr id="emailAddressRow">' + 
		'<td nowrap="NOWRAP"><input type="text" title="email address 0" name="emailAddress{$index}" id="emailAddress0" size="30"/></td>' +
		'<td><span>&nbsp;</span><img id="removeButton0" name="0" src="index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName=delete_inline.gif"/></td>' +
		'<td align="center"><input type="radio" name="emailAddressPrimaryFlag" id="emailAddressPrimaryFlag0" value="emailAddress0" enabled="true" checked="true"/></td>' +
		'<td align="center"><input type="checkbox" name="emailAddressOptOutFlag[]" id="emailAddressOptOutFlag0" value="emailAddress0" enabled="true"/></td>' + 
		'<td align="center"><input type="checkbox" name="emailAddressInvalidFlag[]" id="emailAddressInvalidFlag0" value="emailAddress0" enabled="true"/></td>' + 
		'<td><input type="hidden" name="emailAddressVerifiedFlag0" id="emailAddressVerifiedFlag0" value="true"/></td>' + 
		'<td><input type="hidden" name="emailAddressVerifiedValue0" id="emailAddressVerifiedValue0" value=""/></td></tr>',
		
		numberEmailAddresses : 0,
        replyToFlagObject : new Object(),
        verifying : false,
        enterPressed : false,
		tabPressed : false,
        emailView:"",
		emailIsRequired: false,
		tabIndex: -1,
		
		prefillEmailAddresses: function(tableId, o){
			for (i = 0; i < o.length; i++) {
				o[i].email_address = o[i].email_address.replace('&#039;', "'");
				this.addEmailAddress(tableId, o[i].email_address, o[i].primary_address, o[i].reply_to_address, o[i].opt_out, o[i].invalid_email, o[i].email_address_id);
			}
		},
		
		retrieveEmailAddress: function (event) {
            var callbackFunction = function success(data) {
	            var vals = YAHOO.lang.JSON.parse(data.responseText);
	            var target = vals.target;
		        event = this.getEvent(event);
				
				if(vals.email) {
		           var email = vals.email;
		           if(email != '' && /\d+$/.test(target)) {
		               var matches = target.match(/\d+$/);
		               var targetNumber = matches[0];
		               var optOutEl = Dom.get(this.id + 'emailAddressOptOutFlag' + targetNumber);
		               if(optOutEl) {
		                   optOutEl.checked = email['opt_out'] == 1 ? true : false;
		               }
		               var invalidEl = Dom.get(this.id + 'emailAddressInvalidFlag' + targetNumber);
		               if(invalidEl) {
		                   invalidEl.checked = email['invalid_email'] == 1 ? true : false;
		               }
		           }
		        }
		        //Set the verified flag to true
		        var index = /[a-z]*\d?emailAddress(\d+)/i.exec(target)[1];
		        
				var verifyElementFlag = Dom.get(this.id + 'emailAddressVerifiedFlag' + index);
		        
		        if(verifyElementFlag.parentNode.childNodes.length > 1) {
		           verifyElementFlag.parentNode.removeChild(verifyElementFlag.parentNode.lastChild);
		        }
		        
		        var verifiedTextNode = document.createElement('span');
		        verifiedTextNode.innerHTML = '';
		        verifyElementFlag.parentNode.appendChild(verifiedTextNode);
		        verifyElementFlag.value = "true";
		        this.verifyElementValue = Dom.get(this.id +'emailAddressVerifiedValue' + index);
		        this.verifyElementValue.value = Dom.get(this.id +'emailAddress' + index).value;
		        this.verifying = false;
		        
		        // If Enter key or Save button was pressed then we proceed to attempt a form submission
		        var savePressed = false;
		        if(event) {
		           var elm = document.activeElement || event.explicitOriginalTarget;
		           if(typeof elm.type != 'undefined' && /submit|button/.test(elm.type.toLowerCase())) {
                        //if we are in here, then the element has been recognized as a button or submit type, so check the id
                        //to make sure it is related to a submit button that should lead to a form submit

                        //note that the document.activeElement and explicitOriginalTarget calls do not work consistantly across
                        // all browsers, so we have to include this check after we are sure that the calls returned something as opposed to in the coindition above.
                        // Also, since this function is called on blur of the email widget, we can't rely on a third object as a flag (a var or hidden form input)
                        // since this function will fire off before the click event from a button is executed, which means the 3rd object will not get updated prior to this function running.
                        if(/save|full|cancel|change/.test(elm.value.toLowerCase())){
                           //this is coming from either a save, full form, cancel, or view change log button, we should set savePressed = true;
                            savePressed = true;
                        }
                   }
		        }

		        
		        if(savePressed || this.enterPressed) {
		           setTimeout("SUGAR.EmailAddressWidget.instances." + this.id + ".forceSubmit()", 2100);
		        } else if(this.tabPressed) {
		           Dom.get(this.id + 'emailAddressPrimaryFlag' + index).focus();
		        }
		    }

		    var event = this.getEvent(event);
		    var targetEl = this.getEventElement(event);
		    var index = /[a-z]*\d?emailAddress(\d+)/i.exec(targetEl.id)[1];
			var verifyElementFlag = Dom.get(this.id + 'emailAddressVerifiedFlag' + index);

            if(this.verifyElementValue == null || typeof(this.verifyElementValue)=='undefined'){
                //we can't do anything without this value, so just return
                return false;
            }

            this.verifyElementValue = Dom.get(this.id + 'emailAddressVerifiedValue' + index);
		    verifyElementFlag.value = (trim(targetEl.value) == '' || targetEl.value == this.verifyElementValue.value) ? "true" : "false"
		    
		    //Remove the span element if it is present
		    if(verifyElementFlag.parentNode.childNodes.length > 1) {
		       verifyElementFlag.parentNode.removeChild(verifyElementFlag.parentNode.lastChild);
		    }
		    
		    if(/emailAddress\d+$/.test(targetEl.id) && isValidEmail(targetEl.value) && !this.verifying && verifyElementFlag.value == "false") {
		        verifiedTextNode = document.createElement('span');
		        verifyElementFlag.parentNode.appendChild(verifiedTextNode);
		        verifiedTextNode.innerHTML = SUGAR.language.get('app_strings', 'LBL_VERIFY_EMAIL_ADDRESS');
		        this.verifying = true;
		        var cObj = YAHOO.util.Connect.asyncRequest(
				    'GET', 
					'index.php?module=Contacts&action=RetrieveEmail&target=' + targetEl.id + '&email=' + targetEl.value, 
					{success: callbackFunction, failure: callbackFunction, scope: this}
				);
		    }
	    },

        handleKeyDown: function (event) {
		    var e = this.getEvent(event);
		    var eL = this.getEventElement(e);
		    if ((kc = e["keyCode"])) {
		        this.enterPressed = (kc == 13) ? true : false;
		        this.tabPressed = (kc == 9) ? true : false;
		        
		        if(this.enterPressed || this.tabPressed) {
		           this.retrieveEmailAddress(e);
		           if (this.enterPressed)
		               this.freezeEvent(e);
		        }
		    }
		}, //handleKeyDown()
		
		getEvent :function (event) {
		    return (event ? event : window.event);
		},//getEvent
		
		getEventElement : function (e) {
		    return (e.srcElement ? e.srcElement: (e.target ? e.target : e.currentTarget));
		},//getEventElement
		
		freezeEvent : function (e) {
		    if (e.preventDefault) e.preventDefault();
		    e.returnValue = false;
		    e.cancelBubble = true;
		    if (e.stopPropagation) e.stopPropagation();
		    return false;
		},//freezeEvent
		
		addEmailAddress : function (tableId, address, primaryFlag, replyToFlag, optOutFlag, invalidFlag, emailId) {
			if (this.addInProgress)
			    return;
			this.addInProgress = true;
		    if (!address)
                address = "";
			var insertInto = Dom.get(tableId);
		    var parentObj = insertInto.parentNode;
		    var newContent = document.createElement("input");
		    var nav = new String(navigator.appVersion);

		    var newContentRecordId = document.createElement("input");
		    var newContentPrimaryFlag = document.createElement("input");
		    var newContentReplyToFlag = document.createElement("input");
		    var newContentOptOutFlag = document.createElement("input");
		    var newContentInvalidFlag = document.createElement("input");
		    var newContentVerifiedFlag = document.createElement("input");
		    var newContentVerifiedValue = document.createElement("input");
		    var removeButton = document.createElement("button");
            var removeButtonImg = document.createElement('img');
		    var tbody = document.createElement("tbody");
		    var tr = document.createElement("tr");
		    var td1 = document.createElement("td");
		    var td2 = document.createElement("td");
		    var td3 = document.createElement("td");
		    var td4 = document.createElement("td");
		    var td5 = document.createElement("td");
		    var td6 = document.createElement("td");
		    var td7 = document.createElement("td");
		    var td8 = document.createElement("td");

            //use the value if the tabindex value for email has been passed in from metadata (defined in include/EditView/EditView.tpl
            //else default to 0 
            var tabIndexCount = 0;
            if(typeof(SUGAR.TabFields) !='undefined' && typeof(SUGAR.TabFields['email1']) != 'undefined'){
                tabIndexCount = SUGAR.TabFields['email1'];
            }
		    // set input field attributes
		    newContent.setAttribute("type", "text");
		    newContent.setAttribute("name", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContent.setAttribute("id", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContent.setAttribute("tabindex", tabIndexCount);
		    newContent.setAttribute("size", "30");
            newContent.setAttribute("title", SUGAR.language.get('app_strings', 'LBL_EMAIL_TITLE'));
		
		    if(address != '') {
		        newContent.setAttribute("value", address);
		    }

            // inner structure of remove button
            removeButtonImg.setAttribute('src', "index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=id-ff-remove-nobg.png");
		    
		    // remove button
		    removeButton.setAttribute("id", this.id + "removeButton" + this.numberEmailAddresses);
			removeButton.setAttribute("class", "id-ff-remove");
		    removeButton.setAttribute("name", this.numberEmailAddresses);
		    removeButton.setAttribute("type", "button");
            removeButton.setAttribute("tabindex", tabIndexCount);
            removeButton.onclick = (function(eaw) {
                return function() {
                    eaw.removeEmailAddress(this.name);
                }
            })(this);
            removeButton.appendChild(removeButtonImg);
		    
		    // set record id
		    newContentRecordId.setAttribute("type", "hidden");
		    newContentRecordId.setAttribute("name", this.id + "emailAddressId" + this.numberEmailAddresses);
		    newContentRecordId.setAttribute("id", this.id + "emailAddressId" + this.numberEmailAddresses);
		    newContentRecordId.setAttribute("value", typeof(emailId) != 'undefined' ? emailId : '');
		    newContentRecordId.setAttribute("enabled", "true");

		    // set primary flag
		    newContentPrimaryFlag.setAttribute("type", "radio");
		    newContentPrimaryFlag.setAttribute("name", this.id + "emailAddressPrimaryFlag");
		    newContentPrimaryFlag.setAttribute("id", this.id + "emailAddressPrimaryFlag" + this.numberEmailAddresses);
		    newContentPrimaryFlag.setAttribute("value", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContentPrimaryFlag.setAttribute("enabled", "true");
            newContentPrimaryFlag.setAttribute("tabindex", tabIndexCount);

		    // set reply-to flag
		    newContentReplyToFlag.setAttribute("type", "radio");
		    newContentReplyToFlag.setAttribute("name", this.id + "emailAddressReplyToFlag");
		    newContentReplyToFlag.setAttribute("id", this.id + "emailAddressReplyToFlag" + this.numberEmailAddresses);
		    newContentReplyToFlag.setAttribute("value", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContentReplyToFlag.setAttribute("enabled", "true");
            newContentReplyToFlag.setAttribute("tabindex", tabIndexCount);
		    newContentReplyToFlag.eaw = this;
		    newContentReplyToFlag['onclick']= function() {
		    	var form = document.forms[this.eaw.emailView];
		        if (!form) {
		            form = document.forms['editContactForm'];
		        }
		        var nav = new String(navigator.appVersion);
		
		        if(nav.match(/MSIE/gim)) {
		            for(i=0; i<form.elements.length; i++) {
		                var id = new String(form.elements[i].id);
		                if(id.match(/emailAddressReplyToFlag/gim) && form.elements[i].type == 'radio' && id != this.eaw.id) {
		                    form.elements[i].checked = false;
		                }
		            }           
		        }
		        for(i=0; i<form.elements.length; i++) {
		            var id = new String(form.elements[i].id);
		            if(id.match(/emailAddressReplyToFlag/gim) && form.elements[i].type == 'radio' && id != this.eaw.id) {
		                this.eaw.replyToFlagObject[this.eaw.id] = false;
		            }
		        } // for        
		        if (this.eaw.replyToFlagObject[this.id]) {
		            this.eaw.replyToFlagObject[this.id] = false;
		            this.checked = false;
		        } else {
		            this.eaw.replyToFlagObject[this.id] = true;
		            this.checked = true;
		        } // else
		    }

		    // set opt-out flag
		    newContentOptOutFlag.setAttribute("type", "checkbox");
		    newContentOptOutFlag.setAttribute("name", this.id + "emailAddressOptOutFlag[]");
		    newContentOptOutFlag.setAttribute("id", this.id + "emailAddressOptOutFlag" + this.numberEmailAddresses);
		    newContentOptOutFlag.setAttribute("value", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContentOptOutFlag.setAttribute("enabled", "true");
			newContentOptOutFlag.eaw = this;
            newContentOptOutFlag.setAttribute("tabindex", tabIndexCount);
		    newContentOptOutFlag['onClick'] = function(){this.eaw.toggleCheckbox(this)};
	
		    // set invalid flag
		    newContentInvalidFlag.setAttribute("type", "checkbox");
		    newContentInvalidFlag.setAttribute("name", this.id + "emailAddressInvalidFlag[]");
		    newContentInvalidFlag.setAttribute("id", this.id + "emailAddressInvalidFlag" + this.numberEmailAddresses);
		    newContentInvalidFlag.setAttribute("value", this.id + "emailAddress" + this.numberEmailAddresses);
		    newContentInvalidFlag.setAttribute("enabled", "true");
			newContentInvalidFlag.eaw = this;
            newContentInvalidFlag.setAttribute("tabindex", tabIndexCount);
		    newContentInvalidFlag['onClick']= function(){this.eaw.toggleCheckbox(this);};
		    
		    // set the verified flag and verified email value
		    newContentVerifiedFlag.setAttribute("type", "hidden");
		    newContentVerifiedFlag.setAttribute("name", this.id + "emailAddressVerifiedFlag" + this.numberEmailAddresses);
		    newContentVerifiedFlag.setAttribute("id", this.id + "emailAddressVerifiedFlag" + this.numberEmailAddresses);
		    newContentVerifiedFlag.setAttribute("value", "true");
		
		    newContentVerifiedValue.setAttribute("type", "hidden");
		    newContentVerifiedValue.setAttribute("name", this.id + "emailAddressVerifiedValue" + this.numberEmailAddresses);
		    newContentVerifiedValue.setAttribute("id", this.id + "emailAddressVerifiedValue" + this.numberEmailAddresses);
		    newContentVerifiedValue.setAttribute("value", address);
            newContentVerifiedValue.setAttribute("tabindex", tabIndexCount);

		    //Add to validation
		    this.emailView = (this.emailView == '') ? 'EditView' : this.emailView;
		    addToValidateVerified(this.emailView, this.id + "emailAddressVerifiedFlag" + this.numberEmailAddresses, 'bool', false, SUGAR.language.get('app_strings', 'LBL_VERIFY_EMAIL_ADDRESS'));
		    
		    tr.setAttribute("id", this.id + "emailAddressRow" + this.numberEmailAddresses);
		    
		    td1.setAttribute("nowrap", "NOWRAP");
		    td3.setAttribute("align", "center");
		    td4.setAttribute("align", "center");
		    td5.setAttribute("align", "center");
		    td6.setAttribute("align", "center");

		    td1.appendChild(newContent);
		    td1.appendChild(newContentRecordId);
		    td1.appendChild(document.createTextNode(" "));
		    spanNode = document.createElement('span');
		    spanNode.innerHTML = '&nbsp;';
		    td2.appendChild(spanNode);
		    if (this.numberEmailAddresses != 0 || typeof (this.emailIsRequired) == "undefined" || !this.emailIsRequired)
		       td2.appendChild(removeButton);
		    td3.appendChild(newContentPrimaryFlag);
		    td4.appendChild(newContentReplyToFlag);
		    td5.appendChild(newContentOptOutFlag);
		    td6.appendChild(newContentInvalidFlag);
		    td7.appendChild(newContentVerifiedFlag);
		    td8.appendChild(newContentVerifiedValue);
		    
		    tr.appendChild(td1);
		    tr.appendChild(td2);
		    tr.appendChild(td3);
		
		    if(typeof(this.module) != 'undefined' && this.module == 'Users') {
		        tr.appendChild(td4);
		    } else {
		        tr.appendChild(td5);
		        tr.appendChild(td6);
		    }
		    
		    tr.appendChild(td7);
		    tr.appendChild(td8);
		    
		    tbody.appendChild(tr);
		
		    

		    insertInto.appendChild(tbody);
		    
		    // insert the new div->input into the DOM
		    parentObj.insertBefore(Dom.get('targetBody'), insertInto);
		    
		    // CL Fix for 17651 (added OR condition check to see if this is the first email added)
		    if(primaryFlag == '1' || (this.numberEmailAddresses == 0)) {
		        newContentPrimaryFlag.setAttribute("checked", 'true');
                newContent.setAttribute("title", SUGAR.language.get('app_strings', 'LBL_EMAIL_PRIM_TITLE'));
		    }
		    
		    if(replyToFlag == '1') {
		        newContentReplyToFlag.setAttribute("checked", "true");
		    }
		    
		    if (replyToFlag == '1') {
		        this.replyToFlagObject[newContentReplyToFlag.id] = true;
		    } else {
		        this.replyToFlagObject[newContentReplyToFlag.id] = false;
		    }
		    
		    if(optOutFlag == '1') {
		        newContentOptOutFlag.setAttribute("checked", 'true');
                newContent.setAttribute("title", SUGAR.language.get('app_strings', 'LBL_EMAIL_OPT_TITLE'));
		    }
		    
		    if(invalidFlag == '1') {
		        newContentInvalidFlag.setAttribute("checked", "true");
                newContent.setAttribute("title", SUGAR.language.get('app_strings', 'LBL_EMAIL_INV_TITLE'));
		    }
		    newContent.eaw = this;
		    newContent.onblur = function(e){this.eaw.retrieveEmailAddress(e)};
		    newContent.onkeydown = function(e){this.eaw.handleKeyDown(e)};
            if (YAHOO.env.ua.ie > 0) {
                // IE doesn't bubble up "change" events through the DOM.
                // So we need to fire onChange events on the parent span when the input changes
                var emailcontainer = Dom.getAncestorByTagName(insertInto,'span');
                YAHOO.util.Event.addListener(newContent, "change",
                        function(ev, el){SUGAR.util.callOnChangeListers(el);}, emailcontainer
                );
            }
		    
		    // Add validation to field
            this.EmailAddressValidation(this.emailView, this.id+ 'emailAddress' + this.numberEmailAddresses,this.emailIsRequired, SUGAR.language.get('app_strings', 'LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR'));
            var form = Dom.getAncestorByTagName(insertInto, "form");
            if (SUGAR.forms.AssignmentHandler.VARIABLE_MAP[form.name])
                SUGAR.forms.AssignmentHandler.registerForm(form.name, form);
            this.numberEmailAddresses++;
			this.addInProgress = false;
		}, //addEmailAddress

        EmailAddressValidation : function(ev,fn,r,stR) {
            YAHOO.util.Event.onContentReady(fn,
    function () { addToValidate(ev, fn, 'email', r, stR);});
        },

		removeEmailAddress : function(index) {
			removeFromValidate(this.emailView, this.id + 'emailAddress' + index);
            var oNodeToRemove = Dom.get(this.id +  'emailAddressRow' + index);
            var form = Dom.getAncestorByTagName(oNodeToRemove, "form");
            oNodeToRemove.parentNode.removeChild(oNodeToRemove);

            var removedIndex = parseInt(index);
            //If we are not deleting the last email address, we need to shift the numbering to fill the gap
            if(this.numberEmailAddresses != removedIndex) {
               for(var x = removedIndex + 1; x < this.numberEmailAddresses; x++) {
                   Dom.get(this.id + 'emailAddress' + x).setAttribute("name", this.id +"emailAddress" + (x-1));
                   Dom.get(this.id + 'emailAddress' + x).setAttribute("id", this.id +"emailAddress" + (x-1));
                   
                   if(Dom.get(this.id + 'emailAddressInvalidFlag' + x)) {
                       Dom.get(this.id + 'emailAddressInvalidFlag' + x).setAttribute("value", this.id + "emailAddress" + (x-1));
                       Dom.get(this.id + 'emailAddressInvalidFlag' + x).setAttribute("id", this.id + "emailAddressInvalidFlag" + (x-1));
                   }
                   
                   if(Dom.get(this.id + 'emailAddressOptOutFlag' + x)){
                       Dom.get(this.id + 'emailAddressOptOutFlag' + x).setAttribute("value", this.id + "emailAddress" + (x-1));
                       Dom.get(this.id + 'emailAddressOptOutFlag' + x).setAttribute("id", this.id + "emailAddressOptOutFlag" + (x-1));
                   }
                   
                   if(Dom.get(this.id + 'emailAddressPrimaryFlag' + x)) {
                       Dom.get(this.id + 'emailAddressPrimaryFlag' + x).setAttribute("id", this.id + "emailAddressPrimaryFlag" + (x-1));
                   }
                   
                   Dom.get(this.id + 'emailAddressVerifiedValue' + x).setAttribute("id", this.id + "emailAddressVerifiedValue" + (x-1));
                   Dom.get(this.id + 'emailAddressVerifiedFlag' + x).setAttribute("id", this.id + "emailAddressVerifiedFlag" + (x-1));
                   
                   var rButton = Dom.get(this.id + 'removeButton' + x);
                   rButton.setAttribute("name", (x-1));
                   rButton.setAttribute("id", this.id + "removeButton" + (x-1));
                   Dom.get(this.id + 'emailAddressRow' + x).setAttribute("id", this.id + 'emailAddressRow' + (x-1));
               }
            }
			
			this.numberEmailAddresses--;
            
            
            // CL Fix for 17651
            if(this.numberEmailAddresses == 0) {
               return;
            }
            
            var primaryFound = false;
            for(x=0; x < this.numberEmailAddresses; x++) {
                if(Dom.get(this.id + 'emailAddressPrimaryFlag' + x).checked) {
                   primaryFound = true;
                }
            }
            
            if(!primaryFound) {
               Dom.get(this.id + 'emailAddressPrimaryFlag0').checked = true;
               Dom.get(this.id + 'emailAddressPrimaryFlag0').value = this.id + 'emailAddress0';
            }

            //if the form has already been registered, re-register it with the new element
            if (SUGAR.forms.AssignmentHandler.VARIABLE_MAP[form.name])
                SUGAR.forms.AssignmentHandler.registerForm(form.name, form);
        },
		
		toggleCheckbox : function (el)
		{
			var form = document.forms[this.emailView];
            if (!form) {
                form = document.forms['editContactForm'];
            }
            
            if(YAHOO.env.ua.ie) {
                for(i=0; i<form.elements.length; i++) {
                   var id = new String(form.elements[i].id);
                    if(id.match(/emailAddressInvalidFlag/gim) && form.elements[i].type == 'checkbox' && id != el.id) {
                        form.elements[i].checked = false;
                    }
                }
                
                el.checked = true;
            }
		},
		
		forceSubmit : function () {
		    var theForm = Dom.get(this.emailView);
            if(theForm) {
               theForm.action.value = 'Save';
               if(!check_form(this.emailView)) {
                  return false;
               }
               if(this.emailView == 'EditView') {
                   //this is coming from regular edit view form
                  theForm.submit();
               } else if (this.emailView.indexOf('DCQuickCreate')>0){
                   //this is coming from the DC Quick Create Tool Bar, so call save on form
                  DCMenu.save(theForm.id);
               } else if(this.emailView.indexOf('QuickCreate')>=0) {
                   //this is a subpanel create or edit form
                  SUGAR.subpanelUtils.inlineSave(theForm.id, theForm.module.value+'_subpanel_save_button');
               }
            }
        } //forceSubmit
    };
    emailAddressWidgetLoaded = true;
})();

/* End of File include/SugarEmailAddress/SugarEmailAddress.js */

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

if(typeof(SUGAR.collection) == "undefined") {
    SUGAR.collection = function(form_name, field_name, module, popupData){

    	/*
         * boolean variable to handle expand/collapse views
         * false if the collection field is collapsed and true if the rows are expanded.
         */
        this.more_status = false;
        
        /*
         * Store the form name containing this field.  Example: EditView
         */
        this.form = form_name;
        
        /*
         * Store the name of the collection field. Example: account_name 
         */
        this.field = field_name;
        
        
        /*
         * Store the unique form + field name that uses the combination of form and field
         */
        this.field_element_name = this.form + '_' + this.field;
        
        /*
         * Store the name of the module from where come the field. Example: Accounts 
         */
        this.module = module;
        
        /*
         * Number of secondaries linked records (total of linked records - 1). 
         */
        this.fields_count = 0;
        
        /*
         * Number of extra fields. 
         */
        this.extra_fields_count = 0;
        
        /*
         * Set to true if it is the initialization. 
         */
        this.first = true;
        
        /*
         * Name of the primary field. Example: "accounts_collection_0" 
         */
        this.primary_field = "";
        
        /*
         * Store the row cloned in key "0" and the context cloned in key "1". 
         */
        this.cloneField = new Array();
        
        /*
         * Store the sqs_objects for the cloned row encoded in JSON. 
         */
        this.sqs_clone = "";
        
        /*
         * Store the name and the id of all the secondaries linked records. this is used to create the secondary rows. 
         */
        this.secondaries_values = new Array();
        
        /*
         * Store all the extra fields which has been updated in the collection field to save on save of the main record. 
         */
        this.update_fields = new Object();
        
        /*
         * boolean variable indicating whether or not to show the expand/collapse arrow
         */
        this.show_more_image = true;

    };
    
    SUGAR.collection.prototype = {
        /*
         * Remove the row designated by the passed 'id' or clear the row if there is only one row. 
         */
        remove: function(num){
            // if there is only one record, clear it instead of removing it
    	    // this is determined by the visibility of the drop down arrow element
    	    var radio_els = this.get_radios();
            var div_el;
            if(radio_els.length == 1) {
                div_el = document.getElementById(this.field_element_name + '_input_div_' + num);
                var input_els = div_el.getElementsByTagName('input');
                //Clear text field
                input_els[0].value = '';
                
                //Clear hidden field
                input_els[1].value = '';
                
                if(this.primary_field) {
                   div_el = document.getElementById(this.field_element_name + '_radio_div_' + num);
                   radio_els = div_el.getElementsByTagName('input');
                   //Clear the radio field
                   radio_els[0].checked = false;
                }
            } else {	
            	div_el = document.getElementById(this.field_element_name + '_input_div_' + num);
            	if (!div_el)
                    div_el = document.getElementById(this.field_element_name + '_radio_div_' + num);
                var tr_to_remove = document.getElementById('lineFields_' + this.field_element_name + '_' + num);
            	div_el.parentNode.parentNode.parentNode.removeChild(tr_to_remove);
            	
                var div_id = 'lineFields_' + this.field_element_name + '_' + num;
                if (typeof sqs_objects[div_id.replace("_field_", "_")] != 'undefined') {
                    delete (sqs_objects[div_id.replace("_field_", "_")]);
                }
                var checked=false;
                for(var k=0; k< radio_els.length; k++){
                    if(radio_els[k].checked){
                        checked=true;
                    }
                }
                // If we remove an entry marked as the primary, set another record as the primary 
                var primary_checked = document.forms[this.form].elements[this.field+"_allowed_to_check"];
                var allowed_to_check = true;
                if(primary_checked && primary_checked.value == 'false'){
                	allowed_to_check = false;
                }
                if(/EditView/.test(this.form) && !checked && typeof radio_els[0] != 'undefined' && allowed_to_check) {
                    radio_els[0].checked = true;
                    this.changePrimary(true);
                    this.js_more();
                    this.js_more();
                }
                // if there is now only one record, hide the "more..." link
                if(radio_els.length == 1){
                    this.more_status = false;
					if (document.getElementById('more_'+this.field_element_name) && document.getElementById('more_'+this.field_element_name).style.display != 'none') {
	                    document.getElementById('more_'+this.field_element_name).style.display='none';			
					}

                    this.show_arrow_label(false);
                    this.js_more();
                }else{
                	this.js_more();
                    this.js_more();
                }
            }
        },
        
        get_radios: function() {
        	return YAHOO.util.Selector.query('input[name^=primary]', document.getElementById(this.field_element_name+'_table'));
        },
        
        /*
         * Add a new empty row.
         */
        add: function(values){
            this.fields_count++;
            var Field0 = this.init_clone(values);
            this.cloneField[1].appendChild(Field0);
            //Enable quicksearch for this field
            enableQS(true);
            this.changePrimary(false);
                      
            //If the arrow field and label are collapsed, un-collapse it
            if(document.getElementById('more_'+this.field_element_name) && document.getElementById('more_'+this.field_element_name).style.display == 'none'){
               document.getElementById('more_'+this.field_element_name).style.display='';
            }
            
            if(!this.is_expanded()) {
               this.js_more();
               this.show_arrow_label(true);
            }
        },
        
        /*
         * Add the secondaries rows on load of the page.
         */
        add_secondaries: function(){
            var clone_id = this.form + '_' + this.field + '_collection_0';
            YAHOO.util.Event.onContentReady(clone_id, function(c){
                c.create_clone();
                enableQS();
                c.changePrimary(true);
                for(key in c.secondaries_values){
                    if (isInteger(key)) {
                        c.add(c.secondaries_values[key]);
                    }
                }
                c.js_more();

                if (!_.isUndefined(window.parent.SUGAR) && !_.isUndefined(window.parent.SUGAR.App.view)) {
                    window.parent.SUGAR.App.controller.layout.getComponent('bwc').revertBwcModel();
                }
            }, this);
        },
        /*
         * Create the new row from a cloned row. 
         */
        init_clone: function(values){
        	
        	//Safety check, this means that the clone field was not created yet
        	if(typeof this.cloneField[0] == 'undefined') {
        	   return;
        	}
        	
            if (typeof values == "undefined") {
                values = new Array();
                values['name'] = "";
                values['id'] = "";
            }
            
            var count = this.fields_count;
            
            //Clone the table element containing the fields for each row, use safe_clone uder IE to prevent events from being cloned
            var Field0 = SUGAR.isIE ? 
            	SUGAR.collection.safe_clone(this.cloneField[0], true) : 
            	this.cloneField[0].cloneNode(true);

            Field0.id = "lineFields_"+this.field_element_name+"_"+count;
                        
            for ( var ii = 0; ii < Field0.childNodes.length; ii++ ){
                if(typeof(Field0.childNodes[ii].tagName) != 'undefined' && Field0.childNodes[ii].tagName == "TD") {      	
                    for (var jj = 0; jj < Field0.childNodes[ii].childNodes.length; jj++) {
                    	currentNode = Field0.childNodes[ii].childNodes[jj];
                    	this.process_node(Field0.childNodes[ii], currentNode, values);
                    } //for
                } //if
            } //for
            return Field0;
        },
        /**
         * process_node
         * 
         * method to process cloning of nodes, moved out of init_clone so that
         * this may be recursively called
         */
        process_node: function(parentNode, currentNode, values) {
            if(parentNode.className == 'td_extra_field'){
                // If this is an extra field
                if(parentNode.id){
                    parentNode.id='';
                }
                var toreplace = this.field + "_collection_extra_0";
                var re = new RegExp(toreplace, 'g');
                parentNode.innerHTML = parentNode.innerHTML.replace(re, this.field + "_collection_extra_" + this.fields_count);
            } else if (currentNode.tagName && currentNode.tagName == 'SPAN') { 
                //If it is our div element, recursively find all input elements to process
                currentNode.id = /_input/.test(currentNode.id) ? this.field_element_name + '_input_div_' + this.fields_count :  this.field_element_name + '_radio_div_' + this.fields_count;         	
				if (/_input/.test(currentNode.id)) {
					currentNode.name = 'teamset_div';
				}
            	
            	var input_els = currentNode.getElementsByTagName('input');
            	for ( var x = 0; x < input_els.length; x++ ){

                    //if the input tag id is blank (IE bug), then set it equal to that of the parent span id
                    if(typeof(input_els[x].id) == 'undefined' || input_els[x].id == '') {
                        input_els[x].id = currentNode.id;
                    }

                	if(input_els[x].tagName && input_els[x].tagName == 'INPUT') {
                	   this.process_node(parentNode, input_els[x], values);
                	}
                }
            } else if (currentNode.name) {
                // If this is a standard field
                var toreplace = this.field + "_collection_0";
                var re = new RegExp(toreplace, 'g');
                var name = currentNode.name;                
                var new_name = name.replace(re, this.field + "_collection_" + this.fields_count);
                var new_id = currentNode.id.replace(re, this.field + "_collection_" + this.fields_count);

                switch (name) {
                    case toreplace:
                    	var sqs_id = this.form + '_' + new_name;
                        if (typeof this.sqs_clone != 'undefined') {
                        	var sqs_clone = YAHOO.lang.JSON.stringify(this.sqs_clone);
                            eval('sqs_objects[sqs_id]=' + sqs_clone);
                            
                            for (var pop_field in sqs_objects[sqs_id]['populate_list']) {
                                if (typeof sqs_objects[sqs_id]['populate_list'][pop_field] == 'string') {
                                    sqs_objects[sqs_id]['populate_list'][pop_field] = sqs_objects[sqs_id]['populate_list'][pop_field].replace(RegExp('_0', 'g'), "_" + this.fields_count);
                                }
                            }
                            for (var req_field in sqs_objects[sqs_id]['required_list']) {
                                if (typeof sqs_objects[sqs_id]['required_list'][req_field] == 'string') {
                                    sqs_objects[sqs_id]['required_list'][req_field] = sqs_objects[sqs_id]['required_list'][req_field].replace(RegExp('_0', 'g'), "_" + this.fields_count);
                                }
                            }
                        }
                        
                        currentNode.name = new_name;
                        currentNode.id = new_id;
                        currentNode.value = values['name'];
                        break;
                    case "id_" + toreplace:
                        currentNode.name = new_name.replace(RegExp('_0', 'g'), "_" + this.fields_count);
                        currentNode.id = new_id.replace(RegExp('_0', 'g'), "_" + this.fields_count);
                        currentNode.value = values['id'];
                        break;
                    case "btn_" + toreplace:
                        currentNode.name = new_name;
                        currentNode.attributes['onclick'].value = currentNode.attributes['onclick'].value.replace(re, this.field + "_collection_" + this.fields_count);
                        currentNode.attributes['onclick'].value = currentNode.attributes['onclick'].value.replace(RegExp(this.field + "_collection_extra_0", 'g'), this.field + "_collection_extra_" + this.fields_count);
                        break;
                    case "allow_new_value_" + toreplace:
                        currentNode.name = new_name;
                        currentNode.id = new_id;
                        break;
                    case "remove_" + toreplace:
                        currentNode.name = new_name;
                        currentNode.id = new_id;
                        currentNode.setAttribute('collection_id', this.field_element_name);
                        currentNode.setAttribute('remove_id', this.fields_count);
                        currentNode.onclick = function() { 
                               collection[this.getAttribute('collection_id')].remove(this.getAttribute('remove_id'));
                        };
                        break;
                    case "primary_" + this.field + "_collection":
                        currentNode.id = new_id;
                        currentNode.value = this.fields_count;
                        currentNode.checked = false; //Firefox
                        currentNode.setAttribute('defaultChecked', '');
                        break;
                    default:
                        alert(toreplace + '|' + currentNode.name + '|' + name + '|' + new_name);
                        break;
                } //switch
            } //if-else
        	
        },
        
        /*
         * Collapse or expand the rows to show for the editview(depending of the this.more_status attribute).
         */
        js_more: function(val){
            if(this.show_more_image){
	            var more_ = document.getElementById('more_img_'+this.field_element_name);
	            var arrow = document.getElementById('arrow_'+this.field);
	            var radios = this.get_radios();
	            // if we want to collapse
	            if(this.more_status == false){
	                more_.src = "index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=advanced_search.gif";
	                this.more_status = true;
	                // show the primary only and hidde the other one.
	                var hidden_count = 0;
	                for(var k=0; k< radios.length; k++){
	                    if (radios[k].type && radios[k].type == 'radio') {
	                        if (radios[k].checked) {
	                            radios[k].parentNode.parentNode.parentNode.style.display = '';
	                        } else {
	                            radios[k].parentNode.parentNode.parentNode.style.display = 'none';
	                            hidden_count++;
	                        }
	                    }
	                }
	                //rrs - add code to not remove the first field if non if the fields are selected as primary
	                if(hidden_count == radios.length){
	                	radios[0].parentNode.parentNode.parentNode.style.display = '';
	                }
	                
	                arrow.value = 'hide';
	            }else{
	                more_.src = "index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=basic_search.gif";
	                this.more_status=false;
	                // display all the records
	                for(var k=0; k< radios.length; k++){
	                    if(isInteger(k)){
	                        radios[k].parentNode.parentNode.parentNode.style.display='';
	                    }
	                }

	                arrow.value = 'show';
	            }
                if (!_.isUndefined(window.parent.SUGAR) && !_.isUndefined(window.parent.SUGAR.App.view)) {
                    var model = window.parent.SUGAR.App.controller.layout.getComponent('bwc').bwcModel;
                    model.set($(arrow).attr('name'), arrow.value);
                }

	            var more_div = document.getElementById('more_div_'+this.field_element_name);
	            if(more_div) {
	              more_div.innerHTML = arrow.value == 'show' ? SUGAR.language.get('app_strings','LBL_HIDE') : SUGAR.language.get('app_strings','LBL_SHOW');
	            }
	            
	        }
        },
        /*
         * Create the clone on load of the page and store it in this.cloneField
         */
        create_clone: function() {
            var oneField = document.getElementById('lineFields_'+this.field_element_name+'_0');
            this.cloneField[0] = SUGAR.isIE ?
                SUGAR.collection.safe_clone(oneField, true) :
                oneField.cloneNode(true);
            this.cloneField[1] = oneField.parentNode;
            //fixing bug @48829: Team field shows fully expanded multiple teams instead of hiding multiple teams
            //this.more_status = true;
            var clone_id = this.form + '_' + this.field + '_collection_0';

            if (typeof sqs_objects != 'undefined' && typeof sqs_objects[clone_id] != 'undefined') {
                var clone = YAHOO.lang.JSON.stringify(sqs_objects[clone_id]);
                eval('this.sqs_clone=' + clone);
            }
        },
        /**
         * Validates team set to check if the primary team id has been set or not
         */
		validateTemSet : function(formname, fieldname) {
			var table_element_id = formname + '_' + fieldname + '_table';
			if(document.getElementById(table_element_id)) {
			   var input_elements = YAHOO.util.Selector.query('input[type=radio]', document.getElementById(table_element_id));
			   var has_primary = false;
			   var primary_field_id = fieldname + '_collection_0';
			   for(t in input_elements) {
				    primary_field_id = fieldname + '_collection_' + input_elements[t].value;
			        if(input_elements[t].type && input_elements[t].type == 'radio' && input_elements[t].checked == true) {
			           if(document.forms[formname].elements[primary_field_id].value != '') {
			        	  has_primary = true;
			           }
			           break;
			        }								   
			   }	   
			   if(!has_primary) {
			   		return false;
			   }
			   return true;
			}
			return true;
		}, 
        /**
         * return an array of teamids for a team field
         */
		getTeamIdsfromUI: function(formname, fieldname) {
			var team_ids = new Array();
			var table_element_id = formname + '_' + fieldname + '_table';
			if(document.getElementById(table_element_id)) {
			   input_elements = YAHOO.util.Selector.query('input[type=hidden]', document.getElementById(table_element_id));
			   for(t = 0; t < input_elements.length; t++) {
			     if (input_elements[t].id.match( fieldname + "_collection_") != null) {
			     	team_ids.push(input_elements[t].value);
			     } // if
			   } // for	   	   
			} // if
		    return team_ids;
		},
        /**
         * return a primary team id
         */
		getPrimaryTeamidsFromUI: function(formname, fieldname) {
			var table_element_id = formname + '_' + fieldname + '_table';
			if(document.getElementById(table_element_id)) {
		   		var input_elements = YAHOO.util.Selector.query('input[type=radio]', document.getElementById(table_element_id));
			    	for(t in input_elements) {
			    		var primary_field_id = 'id_' + document.forms[formname][fieldname].name + '_collection_' + input_elements[t].value;
			    		if(input_elements[t].type && input_elements[t].type == 'radio' && input_elements[t].checked == true) {
				           if(document.forms[formname].elements[primary_field_id].value != '') {
				        	   return document.forms[formname].elements[primary_field_id].value;
				           } // if
			    		} // if
			    	} // for
			} // if
			return '';	
		},
        /*
         * Change the primary row onchange of the radio button.
         */
        changePrimary: function(noAdd){
            var old_primary = this.primary_field;
            var radios=this.get_radios();
            for (var k = 0; k < radios.length; k++) {
                var qs_id = radios[k].id.replace('primary_','');
                if (radios[k].checked) {
                    this.primary_field = qs_id;
                } else {
                	qs_id = qs_id + '_' + k;
                }

                qs_id = this.form + '_' + qs_id;

                if(typeof sqs_objects != 'undefined' && typeof sqs_objects[qs_id] != 'undefined' && sqs_objects[qs_id]['primary_field_list']){
                    for (var ii = 0; ii < sqs_objects[qs_id]['primary_field_list'].length; ii++) {
                        if (radios[k].checked && qs_id != old_primary) {
                            sqs_objects[qs_id]['field_list'].push(sqs_objects[qs_id]['primary_field_list'][ii]);
                            sqs_objects[qs_id]['populate_list'].push(sqs_objects[qs_id]['primary_populate_list'][ii]);
                        }else if(old_primary == qs_id && !radios[k].checked){
                            sqs_objects[qs_id]['field_list'].pop();
                            sqs_objects[qs_id]['populate_list'].pop();
                        }
                    }
                }
            }
            
            if (noAdd) {
            	enableQS(false);
            }
            this.first = false;
        },
        /*
         * Collapse or expand the rows to show for the detailview. 
         */
        js_more_detail: function(id){
            var more_img = document.getElementById('more_img_'+id);
            if(more_img.style.display == 'inline'){
                more_img.src = "index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=advanced_search.gif";
            }else{
                more_img.src = "index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=basic_search.gif";
            }
        },
        /*
         * Replace the first field with the specified values
         */
        replace_first: function(values){
        	for (var i = 0; i <= this.fields_count; i++) {
        		var div_el = document.getElementById(this.field_element_name + '_input_div_' + i);
                if(div_el) { 
		        	var name_field = document.getElementById(this.field_element_name+"_collection_" + i);
					var id_field = document.getElementById("id_"+this.field_element_name+"_collection_" + i);
		        	name_field.value = values['name'];
		        	id_field.value = values['id'];
		        	break;
                }
        	}
        },
        /*
         * Remove all empty fields from the widget.
         */
        clean_up: function(){
            //clean up any rows that have been added but do not contain any data
            var divsToClean = new Array();
            var isFirstFieldEmpty = false;
            var divCount = 0;
            for (var i = 0; i <= this.fields_count; i++) {
                var div_el = document.getElementById(this.field_element_name + '_input_div_' + i);
                if(div_el) {        
	                input_els = div_el.getElementsByTagName('input');
	                for ( var x = 0; x < input_els.length; x++ ){
	                	if(input_els[x].id && input_els[x].name == (this.field + '_collection_' + i) && trim(input_els[x].value) == '') {
	                		if(divCount == 0){
	                			isFirstFieldEmpty = true;
	                		} else {
	                			divsToClean.push(i);
	                		}
	                	}
	                	
	                }
	                divCount++;
                }
            }

            for(var j = 0; j < divsToClean.length; j++){
            	this.remove(divsToClean[j]);
            }
            return isFirstFieldEmpty;
        },
        
        show_arrow_label: function(show) {
            var more_div = document.getElementById('more_div_'+this.field_element_name);
            if(more_div) {
               more_div.style.display = show ? '' : 'none';
            }        	
        },
        
        /**
         * is_expanded
         * helper function to determine whether or not the widget is expanded (all teams are shown)
         */
        is_expanded: function() {
            var more_div = document.getElementById('more_div_'+this.field_element_name);
            if(more_div) {
               return more_div.style.display == '';
            }
            return false;
        }
    };

	SUGAR.collection.safe_clone = function(e, recursive)
	{
		if (e.nodeName == "#text")
		{
			return document.createTextNode(e.data);
		}
		if(!e.tagName) return false;
		
		var newNode = document.createElement(e.tagName);
		if (!newNode) return false;

        var properties = [ 'id', 'class', 'style', 'name', 'type', 'valign', 'border', 'width', 'height', 'top', 'bottom', 'left', 'right', 'scope', 'row', 'columns', 'src', 'href', 'className', 'align', 'nowrap'];

        //clee. - Bug: 44976 - IE7 just does not calculate height properties correctly for input elements
        if(SUGAR.isIE7 && e.tagName.toLowerCase() == 'input')
        {
            var properties = [ 'id', 'class', 'style', 'name', 'type', 'valign', 'border', 'width', 'top', 'bottom', 'left', 'right', 'scope', 'row', 'columns', 'src', 'href', 'className', 'align', 'nowrap'];
        }
		
		for (var i in properties)
		{
			if (e[properties[i]])
			{
                //There are two groups of conditional checks here:
                //The first group is to ignore the style and type attributes for IE browsers
                //The second group is to ensure that only <a> and <iframe> tags have href attribute
                if ((properties[i] != 'style' || !SUGAR.isIE) &&
                    //Only <a> and <iframe> tags can have hrefs
                    (properties[i] != 'href'  || e.tagName == 'a' || e.tagName == 'iframe')) {
                        if(properties[i] == "type") {
                            newNode.setAttribute(properties[i], e[properties[i]]);
                        } else {
                            newNode[properties[i]] = e[properties[i]];
                        }
                }
			}
		}
		if(recursive)
		{
			for (var i in e.childNodes)
			{
				if(e.childNodes[i].nodeName && (!e.className || e.className != "yui-ac-container"))
				{
					var child = SUGAR.collection.safe_clone(e.childNodes[i], true);
					if (child) newNode.appendChild(child);
				}
			}
		}
		return newNode;
	}
}

/* End of File include/SugarFields/Fields/Collection/SugarFieldCollection.js */

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
/*
if(!SUGAR) {
	SUGAR = new Object();
}

// lazy-load YUI XHR lib
if(!YAHOO.util.Connect) {
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'include/javascript/yui/connection.js';
	document.getElementsByTagName('head')[0].appendChild(script);
}
*/



/**
 * Core SugarRouting UI and XHR class
 */

SUGAR.routing = {
	actions : new Object(),
	rules : new Object(),
	strings : new Object(),
	
	/**
	 * Retrieves a saved rule and displays it
	 */
	editRule : function(id) {
		this.xhr.startRequest(callback.editRule, this.xhr.urlStandard + "&routingAction=getRule&rule_id=" + id + "&bean=InboundEmail");
	},
	
	/**
	 * Deletes a rule from a user's saved prefs
	 */
	removeRule : function(id) {
		alert('implement SUGAR.routing.removeRule() pls.');
	},
	
	/**
	 * retrieves relevant strings for this lib
	 */
	getStrings : function() {
		this.xhr.startRequest(callback.strings, this.xhr.urlStandard + "&routingAction=getStrings");
	},
	
	/**
	 * Retrieves dependent dropdowns relevant to the module in view
	 */
	getDependentDropdowns : function() {
		this.xhr.startRequest(callback.dd, this.xhr.urlStandard + "&routingAction=getActions");
	}
}

SUGAR.routing.handleDependentDropdown = function(el, focusDD) {
	if(SUGAR.dependentDropdown.debugMode) SUGAR.dependentDropdown.utils.debugStack('handleDependentDropdown');
	
	/*
	 * el.id example:
	 * "criteriaGroup::0:::0:-:crit0id"
	 * [grouping from metadata]::[index]:::[elementIndex]:-:[assignedID from metadata]
	 * index is row-number
	 * elementIndex is the index of the current element in this row
	 */
	var index = el.id.slice(el.id.indexOf("::") + 2, el.id.indexOf(":::"));
	var actionIndex = index / 100;
//	var elementRow = el.boxObject.parentBox;
	var elementRow = el.parentNode;
	var elementIndex = el.id.slice(el.id.indexOf(":::") + 3, el.id.indexOf(":-:"));

	// set currentAction to keep track of which criteria/action we're dealing with BEFORE incrementing elementIndex.
	SUGAR.dependentDropdown.currentAction = SUGAR.routing.rules.InboundEmail[SUGAR.routing.currentRuleId][focusDD][actionIndex];

	elementIndex++;
	var elementKey = "element" + elementIndex;
	var focusElement = SUGAR.dependentDropdown.dropdowns[focusDD].elements[elementKey];

	
	if(focusElement) {
		if(focusElement.handlers) {
			try {
				focusElement = focusElement.handlers[el.value];
			} catch(e) {
				if(SUGAR.dependentDropdown.dropdowns.debugMode) {
					debugger;
				}
			}
		}
		SUGAR.dependentDropdown.generateElement(focusElement, elementRow, index, elementIndex);
	} else {
	}
}

SUGAR.routing.utils = {
	/**
	 * Removes all child nodes from the passed DOM element
	 */
	removeChildren : function(el) {
		for(i=el.childNodes.length - 1; i >= 0; i--) {
			if(el.childNodes[i]) {
				el.removeChild(el.childNodes[i]);
			}
		}
	}
}
/**
 * XMLHTTPRequest Object Implementation for SugarRouting
 */
SUGAR.routing.xhr = {
	currentRequestObject : null,
	timeout : 30000, // 30 second timeout default
	forceAbort : false,
	trail : new Array(),
	urlStandard : 'sugar_body_only=true&to_pdf=true&module=Emails&action=SugarRoutingAsync',
	/**
	 */
	_reset : function() {
		this.timeout = 30000;
		this.forceAbort = false;
	},

	// makes the XHRequest
	startRequest : function(callback, args, forceAbort) {
		if(this.currentRequestObject != null) {
			if(this.forceAbort == true) {
				YAHOO.util.Connect.abort(this.currentRequestObject, null, false);
			}
		}
		this.currentRequestObject = YAHOO.util.Connect.asyncRequest('POST', "./index.php", callback, args);
		this._reset();
	},

	/**
	 */
    handleFailure : function(o) {
		// Failure handler
		SUGAR.showMessageBox('Exception occurred...', o.statusText);
		debugger;
		//document.getElementById(this.target).innerHTML = o.statusText;
	},

	/**
	 * Sets this lib's strings
	 */
	fillStrings : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.routing.strings = ret.strings;
		SUGAR.routing.matchDom = ret.matchDom;
		SUGAR.routing.matchTypeDom = ret.matchTypeDom;
		SUGAR.routing.actions = ret.actions;
	},

	/**
	 * Displays a fetched rule, applying user's saved values overlayed on a series of dependent dropdowns
	 */
	displayFetchedRule : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		
		SUGAR.routing.ui.displayRule(ret);
	},
	
	/**
	 * Lazy-loader for dependent dropdown key/value pairs
	 */
	fillDependentDropdowns : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.dependentDropdown.dropdowns = ret;
	},

	/**
	 * Makes an async call to save rule values
	 */
	saveRule : function(formId) {
		if(SUGAR.routing.ui.checkRule(formId)) {
			SUGAR.showMessageBox(SUGAR.routing.strings.LBL_ROUTING_SAVING_RULE, SUGAR.routing.strings.LBL_ROUTING_ONE_MOMENT);
			var values = YAHOO.util.Connect.setForm(document.getElementById(formId));
			this.startRequest(callback.saveRule, SUGAR.routing.urlStandard);
		}
	},
	
	/**
	 * Makes the call to delete a rule
	 */
	deleteRule : function(rule_id) {
		this.startRequest(callback.saveRule, this.urlStandard + "&routingAction=deleteRule&rule_id=" + rule_id);
	},
	
	/**
	 * Async call to get specifically available email_templates
	 */
	getComposeCache : function() {
		this.startRequest(callback.fillComposeCache, 'sugar_body_only=true&to_pdf=true&module=Emails&action=EmailUIAjax&emailUIAction=fillComposeCache');
	},
	
	/**
	 * takes response and fills in local variables for email templates
	 */
	fillComposeCache : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		
		SUGAR.email2.composeLayout.emailTemplates = ret.emailTemplates;
		SUGAR.email2.composeLayout.signatures = ret.signatures;
		
		return SUGAR.email2.composeLayout.emailTemplates;
	},
	
	setRuleStatusCleanup : function(o) {
		// stub for future functionality
	}
}


SUGAR.routing.ui = {
	/**
	 * Makes async call to delete a rule
	 */
	deleteRule : function(rule_id) {
		if(confirm(SUGAR.routing.strings.LBL_ROUTING_CONFIRM_DELETE)) {
			SUGAR.routing.xhr.deleteRule(rule_id);
		}
	},
	
	/**
	 * Constructs the Rules form for 1 rule
	 */
	displayRule : function(ret) {
		if(!SUGAR.routing.rules[ret.bean]) {
			SUGAR.routing.rules[ret.bean] = new Object();
		}
		
		if(!SUGAR.routing.rules[ret.bean][ret.rule.id]) {
			SUGAR.routing.rules[ret.bean][ret.rule.id] = new Object();
		}
		
		// rule now cached
		SUGAR.routing.rules[ret.bean][ret.rule.id] = ret.rule;
		
		SUGAR.routing.currentRuleId = ret.rule.id;
	
		var d = document.getElementById("rulesDetailsCell");
		
		SUGAR.routing.utils.removeChildren(d);
		
		d.style.padding = '5px';
		
		// overall form
		YAHOO.ext.DomHelper.append(d, {
			tag:'form',
			name:'routingForm' + ret.rule.id,
			id:'routingRule' + ret.rule.id,
			method: 'post'
		});
		var form = document.getElementById('routingRule' + ret.rule.id);
		// bean field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'bean',
			value : ret.bean
		});
		// sugar_body_only field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'sugar_body_only',
			value : 'true'
		});
		// to_pdf field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'to_pdf',
			value : 'true'
		});
		// module field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'module',
			value : 'Emails'
		});
		// action field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'action',
			value : 'SugarRoutingAsync'
		});
		// routingAction field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'routingAction',
			value : 'saveRule'
		});
		// hidden ID field
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'hidden',
			name : 'id',
			value : ret.rule.id
		});
		
		// buttons
		var save = "SUGAR.routing.xhr.saveRule('" + "routingRule" + ret.rule.id + "');";
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'button',
			cls : 'button',
			onclick : save,
			value : "   " + SUGAR.routing.strings.LBL_ROUTING_BUTTON_SAVE + "   "
		});
		form.innerHTML += "&nbsp;";
		YAHOO.ext.DomHelper.append(form, {
			tag : 'input',
			type : 'button',
			cls : 'button',
			onclick : 'SUGAR.routing.ui.cancelRule();',
			value : "   " + SUGAR.routing.strings.LBL_ROUTING_BUTTON_CANCEL + "   "
		});
		form.innerHTML += "<br />&nbsp;<br />";
		
		// name div
		var tmp = YAHOO.ext.DomHelper.append(form, {
			tag : 'div',
			cls : 'routingInputField',
			html : SUGAR.routing.strings.LBL_ROUTING_NAME + ": &nbsp;"
		}, true);
		var nameDiv = tmp.dom;
		
		// name field
		YAHOO.ext.DomHelper.append(nameDiv, {
			tag:'input',
			type:'text',
			name:'name',
			id : 'ruleName',
			value:ret.rule.name,
			size:'32',
			cls: 'input'
		});
		// br
		YAHOO.ext.DomHelper.append(form, {
			tag:"br"
		});
		// match type div
		var tmp = YAHOO.ext.DomHelper.append(form, {
			tag:'div',
			cls:'routingInputField'
		},true);
		var matchDiv = tmp.dom;
		// match text
		matchDiv.innerHTML += SUGAR.routing.strings.LBL_ROUTING_MATCH + "&nbsp;";
		// match dd
		var tmp = YAHOO.ext.DomHelper.append(matchDiv, {
			tag:'select',
			name:'all',
			cls: 'select'
		}, true);
		var matchDropDown = tmp.dom;
		// match options
		matchDropDown.options[0] = new Option(SUGAR.routing.strings.LBL_ROUTING_ANY, 0);
		matchDropDown.options[1] = new Option(SUGAR.routing.strings.LBL_ROUTING_ALL, 1, ret.rule.all);
		// match text
		matchDiv.innerHTML += "&nbsp;" + SUGAR.routing.strings.LBL_ROUTING_MATCH_2 + "&nbsp;";
		// br
		YAHOO.ext.DomHelper.append(form, {
			tag:"br"
		});
		
		
		// rule criteria
		var tmp = YAHOO.ext.DomHelper.append(form, {
			tag:'div',
			id:'routingCriteria'
		}, true);
		
		for(var i=0; i<ret.rule.criteria.length; i++) {
			var index = i * 100;
			SUGAR.routing.ui.generateRuleCondition(tmp.dom, ret.rule.criteria[i], index);
		}
		
		// actions
		form.innerHTML += "<br />";
		var tmp = YAHOO.ext.DomHelper.append(form, {
			tag : 'div',
			style : 'padding-top:10px;'
		}, true);
		var actionDiv = tmp.dom;
		
		actionDiv.innerHTML += SUGAR.routing.strings.LBL_ROUTING_ACTIONS_PEFORM + ":<br />&nbsp;<br />";
		
		for(var i=0; i<ret.rule.actions.length; i++) {
			var index = i * 100;
			SUGAR.routing.ui.generateRuleAction(actionDiv, ret.rule.actions[i], index);
		}
	},

	/**
	 * Lazy-loads values in an associative array for SugarDependentDropdowns
	 * @param string type
	 */
	getElementValues : function(type) {
		switch(type) {
			case "move_mail":
				// not lazy-loading to prevent having to refresh to apply changes to Remote server connctions & folders
				return SUGAR.email2.folders.getAvailableFoldersObject();
			break;
			
			case "email_templates":
				if(!SUGAR.email2.composeLayout.emailTemplates) {
					SUGAR.routing.xhr.getComposeCache();
					
					setTimeout("SUGAR.routing.ui.getElementValues('email_templates');", 2000);
				} else {
					return SUGAR.email2.composeLayout.emailTemplates;
				}
			break;
		}
	},

	/**
	 * appends a series of dependent drop-downs and related fields for a rule's action
	 * @param HTMLElementObject
	 * @param Object criteria in associative array
	 * @param int
	 */
	generateRuleAction : function(container, criteria, index) {
		this.generateRuleRow(container, criteria, index, 'Action');
	},


	/**
	 * appends a series of dependent drop-downs to the passed form element reference
	 * @param HTMLElementObject
	 * @param Object criteria in associative array
	 * @param int
	 */
	generateRuleCondition : function(container, criteria, index) {
		this.generateRuleRow(container, criteria, index, 'Rule');
	},
	
	/**
	 * Generates a rule row (action or criteria)
	 */
	generateRuleRow : function(container, criteria, index, type) {
		var elementIndex = 0;
		var theme = 'Sugar';
		
		/* decide whether to append to the row container or before the next */
		if(container.id.match(/Row/i)) {
			/* inserting criteria b/t rows */
			var tmp = YAHOO.ext.DomHelper.insertAfter(container, {
				tag : 'div',
				cls : 'routing' + type,
				style : 'padding:2px;',
				id : 'routing' + type + 'Row' + index
			}, true);
		} else {
			/* standard code-path */
			var tmp = YAHOO.ext.DomHelper.append(container, {
				tag : 'div',
				cls : 'routing' + type,
				style : 'padding:2px;',
				id : 'routing' + type + 'Row' + index
			}, true);
		}
		var ruleRow = tmp.dom;

		// remove & add icons
		var remove = "javascript:SUGAR.routing.ui.removeCriteria('routing" + type + "Row" + index + "');";
		var minus = YAHOO.ext.DomHelper.append(ruleRow, {
			tag : 'a',
			href : remove
		}, true);
		YAHOO.ext.DomHelper.append(minus.dom, {
			tag : 'img',
			src : 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=minus.gif',
			border : 0,
			cls : 'img'
		}, false);
		
		ruleRow.innerHTML += "&nbsp;";
		var insert = "javascript:SUGAR.routing.ui.insertCriteria('routing" + type + "Row" + index + "');";
		var plus = YAHOO.ext.DomHelper.append(ruleRow, {
			tag : 'a',
			href : insert
		}, true);
		YAHOO.ext.DomHelper.append(plus.dom, {
			tag : 'img',
			src : 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=plus.gif',
			border : 0,
			cls : 'img'
		}, false);
		ruleRow.innerHTML += "&nbsp;";
		
		// generate rule row from metadata
		SUGAR.dependentDropdown.currentAction = criteria;

		var ddType = (type == 'Rule') ? 'criteria' : 'actions' ;
		
		for(var elementKey in SUGAR.dependentDropdown.dropdowns[ddType].elements) {
			var focusElement = SUGAR.dependentDropdown.dropdowns[ddType].elements[elementKey];
			
			// generate actions for actionRow		
			SUGAR.dependentDropdown.generateElement(focusElement, ruleRow, index, elementIndex);
			
			if(!focusElement.force_render || focusElement.force_render == '') {
				// continue rendering next elemetn
				return;
			} else {
				elementIndex++;
			}
		}
	},
	
	/**
	 * Inserts a row in either the criteria or actions groups
	 * @param string clickElement ID of clicked "+" sign
	 */
	insertCriteria : function(clickElement) {
		var row = document.getElementById(clickElement);
		var focusRowIndex = parseFloat(clickElement.substr(clickElement.search(/\d/)));
			
		if(row.nextSibling) {
			var nextRow = row.nextSibling;
	
			var nextRowIndex = parseFloat(nextRow.id.substr(nextRow.id.search(/\d/)));
			//var insertIndex = Math.floor(((nextRowIndex - focusRowIndex) / 2) + focusRowIndex);
			var insertIndex = ((nextRowIndex - focusRowIndex) / 2) + focusRowIndex;
		} else {
			var insertIndex = focusRowIndex + 100;
		}
		
		if(row.id.match(/Action/)) {
			this.generateRuleAction(row, {}, insertIndex);
		} else {
			this.generateRuleCondition(row, {}, insertIndex);
		}
	},
	
	/**
	 * cancels editing a rule (new or saved)
	 */
	cancelRule : function() {
		SUGAR.routing.utils.removeChildren(document.getElementById("rulesDetailsCell"));
	},
	
	/**
	 * generates a blank rule when adding a new one
	 */
	addRule : function() {
		ret = {
			bean : 'InboundEmail',
			rule : {
				id : '',
				name : '',
				criteria : [
					{
						action : {
							'crit0' : 'name',
							'crit1' : 'match',
							'crit2' : ''
						}
					}
				],
				actions : [
					{
						action : {
							'action0' : 'move_mail',
							'action1' : ''
						}
					}
				]
			}
		};
		SUGAR.routing.ui.displayRule(ret);
	},

	/**
	 * Visually removes a criteria row from the Rules Wizard
	 */
	removeCriteria : function(id) {
		var bucket = document.getElementById(id);
		SUGAR.routing.utils.removeChildren(bucket);
		
		bucket.parentNode.removeChild(bucket);
	},

	/**
	 * Starts async call to en/disable a rule.
	 * @param HTMLInputElement Checkbox that was just clicked
	 */
	setRuleStatus : function(cb) {
		var status = (cb.checked) ? 'enable' : 'disable';
		var id = cb.value;
		
		SUGAR.routing.xhr.startRequest(callback.setRuleStatus, SUGAR.routing.xhr.urlStandard + "&routingAction=setRuleStatus&rule_id=" + id + "&status=" + status);
	}
}


/**
 * Verifies that valid information is provided when saving a routing rule
 */
SUGAR.routing.ui.checkRule = function(formId) {
	// empty errors cache
	SUGAR.routing.ui.errors = new Array();
	
	var ret = true;
	var form = document.getElementById(formId);
	var el = null;
	var v = null;
	
	for(var i=0; i<form.elements.length; i++) {
		el = form.elements[i];
		
		if(el.type != 'hidden' && el.type != 'button') {
			// input fields
			if(el.type == 'text') {
				if(el.value == "") {
					SUGAR.routing.ui.errors.push(el.id);
					ret = false;
				}
			} else if(el.type == 'select' || el.type == 'select-one') {
				var v = el.options[el.selectedIndex].value;
				if(v == '-1' || v == '-2' || v == '_break' || v.match(/spacer/ig)) {
					SUGAR.routing.ui.errors.push(el.id);
					ret = false;
				}
			}

			if(el.style.border && (el.style.border == '1px solid rgb(255, 0, 0)' || el.style.border == '#f00 1px solid')) {
				el.style.border = '1px solid #090';
				el.className = 'input';
			}
		}
	}
	
	if(!ret) {
		SUGAR.showMessageBox(SUGAR.routing.strings.LBL_ROUTING_CHECK_RULE, SUGAR.routing.strings.LBL_ROUTING_CHECK_RULE_DESC);

		for(var j=0; j<SUGAR.routing.ui.errors.length; j++) {
			var focusEl = document.getElementById(SUGAR.routing.ui.errors[j]);
			if(focusEl)
				focusEl.style.border = "1px solid #f00";
		}
	}
	
	return ret;
}


//////////////////////////////////////////////////////////////////////////////
////	CALLBACK OBJECTS
callback = {
	failure : SUGAR.routing.xhr.handleFailure,
	timeout : SUGAR.routing.xhr.timeout,
	scope	: SUGAR.routing.xhr,
	
	// define callbacks below
	editRule : {
		success : SUGAR.routing.xhr.displayFetchedRule,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	strings : {
		success : SUGAR.routing.xhr.fillStrings,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	example : {
		success : SUGAR.routing.xhr.handleSuccess,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	dd : {
		success : SUGAR.routing.xhr.fillDependentDropdowns,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	saveRule : {
		success : function() {
			SUGAR.hideMessageBox();
			SUGAR.routing.ui.cancelRule(); // hide rule form
			AjaxObject.startRequest(callbackLoadRules, urlStandard + "&emailUIAction=loadRulesForSettings");
		},
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	fillComposeCache : {
		success : SUGAR.routing.xhr.fillComposeCache,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	},
	setRuleStatus : {
		success : SUGAR.routing.xhr.setRuleStatusCleanup,
		failure : this.failure,
		timeout : this.timeout,
		scope : this.scope
	}
}

/* End of File include/SugarRouting/javascript/SugarRouting.js */

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
SUGAR.dependentDropdown = {
	/*
	 * Container for "action" metadata - allows DD to parse saved choices and apply them at display time
	 */
	currentAction : null,
	/*
	 * Flag to turn on debug mode.
	 * Current debug output:
	 * SUGAR.dependentDropdown._stack - simple list of this class' called methods
	 */
	debugMode : false
}

/**
 * Handle drop-down dependencies
 * @param object HTML form element object
 */
SUGAR.dependentDropdown.handleDependentDropdown = function(el) {
	/**
	 * 
	 * 
	 * PROTOTYPE THIS METHOD TO CUSTOMIZE RESPONSES FOR YOUR DEPENDENT DROPDOWNS
	 * 
	 * 
	 * 
	 */
	/**
	if(SUGAR.dependentDropdown.debugMode) SUGAR.dependentDropdown.utils.debugStack('handleDependentDropdown');
	
	/*
	 * el.id example:
	 * "criteriaGroup::0:::0:-:crit0id"
	 * [grouping from metadata]::[index]:::[elementIndex]:-:[assignedID from metadata]
	 * index is row-number
	 * elementIndex is the index of the current element in this row
	var index = el.id.slice(el.id.indexOf("::") + 2, el.id.indexOf(":::"));
	var elementRow = el.boxObject.parentBox;
	var elementIndex = el.id.slice(el.id.indexOf(":::") + 3, el.id.indexOf(":-:"));

	elementIndex++;
	var elementKey = "element" + elementIndex;
	var focusElement = SUGAR.dependentDropdown.dropdowns[focusDD].elements[elementKey];
	
	if(focusElement) {
		if(focusElement.handlers) {
			try {
				focusElement = focusElement.handlers[el.value];
			} catch(e) {
				if(SUGAR.dependentDropdown.dropdowns.debugMode) {
					debugger;
				}
			}
		}
		SUGAR.dependentDropdown.generateElement(focusElement, elementRow, index, elementIndex);
	} else {
	}
	*/
}






SUGAR.dependentDropdown.generateElement = function(focusElement, elementRow, index, elementIndex) {
	if(SUGAR.dependentDropdown.debugMode) SUGAR.dependentDropdown.utils.debugStack('generateElement');
	
	var tmp = null;
	
	if(focusElement) {
		/* get sandbox to play in */
		var sandbox = SUGAR.dependentDropdown.utils.generateElementContainer(focusElement, elementRow, index, elementIndex);
		
		/* handle labels that appear 'left' or 'top' */
		if(focusElement.label) {
			focusLabel = {
				tag : 'span',
				cls : 'routingLabel',
				html : "&nbsp;" + focusElement.label + "&nbsp;"
			}
			
			switch(focusElement.label_pos) {
				case "top":
					focusLabel.html = focusElement.label + "<br />";
				break;
				
				case "bottom": 
					focusLabel.html = "<br />" + focusElement.label;
				break;
			}
			
			if(focusElement.label_pos == 'left' || focusElement.label_pos == 'top') {
				YAHOO.ext.DomHelper.append(sandbox, focusLabel);
			}
		}

		/**********************************************************************
		 * FUN PART BELOW
		 */
		switch(focusElement.type) {
			case 'input':
				/*
				 * focusElement.values can be lazy-loaded via JS call
				 */
				if(typeof(focusElement.values) == 'string') {
					focusElement.values = eval(focusElement.values);
				}
				
				/* Define the key-value that is to be used to pre-select a value in the dropdown */	
				var preselect = SUGAR.dependentDropdown.utils.getPreselectKey(focusElement.name);

				if(preselect.match(/::/))
					preselect = '';

				tmp = YAHOO.ext.DomHelper.append(sandbox, {
					tag			: 'input',
					id			: focusElement.grouping + "::" + index + ":::" + elementIndex + ":-:" + focusElement.id,
					name		: focusElement.grouping + "::" + index + "::" + focusElement.name,
					cls			: 'input',
					onchange	: focusElement.onchange,
					value		: preselect
				}, true);
				var newElement = tmp.dom;
			break;


			case 'select':
				tmp = YAHOO.ext.DomHelper.append(sandbox, {
					tag			: 'select',
					id			: focusElement.grouping + "::" + index + ":::" + elementIndex + ":-:" + focusElement.id,
					name		: focusElement.grouping + "::" + index + "::" + focusElement.name,
					cls			: 'input',
					onchange	: focusElement.onchange
				}, true);
				var newElement = tmp.dom;
				
				/*
				 * focusElement.values can be lazy-loaded via JS call
				 */
				if(typeof(focusElement.values) == 'string') {
					focusElement.values = eval(focusElement.values);
				}
				
				/* Define the key-value that is to be used to pre-select a value in the dropdown */
				var preselect = SUGAR.dependentDropdown.utils.getPreselectKey(focusElement.name);
				
				// Loop through the values (passed or generated) and preselect as needed
				var i = 0;
				for(var key in focusElement.values) {
					var selected = (preselect == key) ? true : false;
					newElement.options[i] = new Option(focusElement.values[key], key, selected);

					// ie6/7 workaround
					if(selected) {
						newElement.options[i].selected = true;
					}
					i++;
				}
			break;
			
			case 'none':
			break;
			
			case 'checkbox':
				alert('implement checkbox pls');
			break;
			case 'multiple':
				alert('implement multiple pls');
			break;
			
			default:
				if(SUGAR.dependentDropdown.dropdowns.debugMode) {
					alert("Improper type defined: [ " + focusElement.type + "]");
				}
				return;
			break;
		}

		/* handle label placement *after* or *below* the drop-down */
		if(focusElement.label) {
			if(focusElement.label_pos == 'right' || focusElement.label_pos == 'bottom') {
				YAHOO.ext.DomHelper.append(sandbox, focusLabel);
			}
		}

		/* trigger dependent dropdown action to cascade dependencies */
		try {
			newElement.onchange();
			//eval(focusElement.onchange); "this" has no reference
		} catch(e) {
			if(SUGAR.dependentDropdown.dropdowns.debugMode) {
				debugger;
			}
		}

	} else {
	}
}



///////////////////////////////////////////////////////////////////////////////
////	UTILS
SUGAR.dependentDropdown.utils = {
	/**
	 * creates a DIV container for a given element
	 * @param object focusElement Element in focus' metadata
	 * @param object elementRow Parent DIV container's DOM object
	 * @param int index Index of current elementRow
	 * @param int elementIndex Index of the element in focus relative to others in the definition
	 * @return obj Reference DOM object generated
	 */
	generateElementContainer : function(focusElement, elementRow, index, elementIndex) {
		/* clear out existing element if exists */
		var oldElement = document.getElementById('elementContainer' + focusElement.grouping + "::" + index + ":::" + elementIndex);
	
		if(oldElement) {
			SUGAR.dependentDropdown.utils.removeChildren(oldElement);
		}
		
		/* create sandbox to ease removal */
		var tmp = YAHOO.ext.DomHelper.append(elementRow, {
			tag : 'span',
			id	: 'elementContainer' + focusElement.grouping + "::" + index + ":::" + elementIndex
		}, true);
		
		return tmp.dom;
	},
	/**
	 * Finds the preselect key from the User's saved (loaded into memory) metadata
	 * @param string elementName Name of form element - functions as key to user's saved value
	 */
	getPreselectKey : function(elementName) {
		try {
			if(SUGAR.dependentDropdown.currentAction.action[elementName]) {
				return SUGAR.dependentDropdown.currentAction.action[elementName];
			} else {
				return '';
			}
		} catch(e) {
			if(SUGAR.dependentDropdown.dropdowns.debugMode) {
				//debugger;
			}
			return '';
		}
	},
	
	/**
	 * provides a list of methods called in order when debugging
	 * @param object
	 */
	debugStack : function(func) {
		if(!SUGAR.dependentDropdown._stack) {
			SUGAR.dependentDropdown._stack = new Array();
		}
		
		SUGAR.dependentDropdown._stack.push(func);
	},
	
	/**
	 * Removes all child nodes from the passed DOM element
	 */
	removeChildren : function(el) {
		for(i=el.childNodes.length - 1; i >= 0; i--) {
			if(el.childNodes[i]) {
				el.removeChild(el.childNodes[i]);
			}
		}
	}
}

/* End of File include/SugarDependentDropdown/javascript/SugarDependentDropdown.js */

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

SUGAR.inboundEmail = { };

// $Id: InboundEmail.js 54045 2010-01-26 20:25:05Z roger $
Rot13 = {
    map: null,

    convert: function(a) {
        Rot13.init();

        var s = "";
        for (i=0; i < a.length; i++) {
            var b = a.charAt(i);
            s += ((b>='A' && b<='Z') || (b>='a' && b<='z') ? Rot13.map[b] : b);
        }
        return s;
    },

    init: function() {
        if (Rot13.map != null)
            return;

        var map = new Array();
        var s   = "abcdefghijklmnopqrstuvwxyz";

        for (i=0; i<s.length; i++)
            map[s.charAt(i)] = s.charAt((i+13)%26);
        for (i=0; i<s.length; i++)
            map[s.charAt(i).toUpperCase()] = s.charAt((i+13)%26).toUpperCase();

        Rot13.map = map;
    },

    write: function(a) {
        return Rot13.convert(a);
    }
}


function getEncryptedPassword(login, password, mailbox) {
	var words = new Array(login, password, mailbox);
	for(i=0; i<3; i++) {
		word = words[i];
		if(word.indexOf('&') > 0) {
			fragment1 = word.substr(0, word.indexOf('&'));
			fragment2 = word.substr(word.indexOf('&') + 1, word.length);

			newWord = fragment1 + '::amp::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
		if(word.indexOf('+') > 0) {
			fragment1 = word.substr(0, word.indexOf('+'));
			fragment2 = word.substr(word.indexOf('+') + 1, word.length);

			newWord = fragment1 + '::plus::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
		if(word.indexOf('%') > 0) {
			fragment1 = word.substr(0, word.indexOf('%'));
			fragment2 = word.substr(word.indexOf('%') + 1, word.length);

			newWord = fragment1 + '::percent::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
	} // for

	return words;
} // fn

function close_ie_test_popup() {
    SUGAR.inboundEmail.ie_test_popup_dialog.hide();
}

function ie_test_open_popup_with_submit(module_name, action, pageTarget, width, height, mail_server, protocol, port, login, password, mailbox, ssl, personal, formName, ie_id)
{
	if (!formName) formName = "testSettingsView";
	var words = getEncryptedPassword(login, password, mailbox);
	var isPersonal = (personal) ? 'true' : 'false';

	if (!isDataValid(formName, true)) {
		return;
	}

    if(typeof(ie_id) == 'undefined' || ie_id == '')
    	ie_id = (typeof document.getElementById(formName).ie_id != 'undefined') ? document.getElementById(formName).ie_id.value : '';

	// launch the popup
	URL = 'index.php?'
		+ 'module=' + module_name
		+ '&to_pdf=1'
		+ '&action=' + action
		+ '&target=' + pageTarget
		+ '&target1=' + pageTarget
		+ '&server_url=' + mail_server
		+ '&email_user=' + words[0]
		+ '&protocol=' + protocol
		+ '&port=' + port
		+ '&email_password=' + encodeURIComponent(words[1])
		+ '&mailbox=' + words[2]
		+ '&ssl=' + ssl
		+ '&ie_id=' + ie_id
		+ '&personal=' + isPersonal;

	var SI = SUGAR.inboundEmail;
	if (!SI.testDlg) {
		SI.testDlg = new YAHOO.widget.SimpleDialog("testSettingsDiv", {
	        width: width + "px",
	        draggable: true,
	        dragOnly: true,
	        close: true,
	        constraintoviewport: true,
			modal: true,
			loadingText: SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING")
	    });
		SI.testDlg._updateContent = function (o) {
	        var w = this.cfg.config.width.value + "px";
	        this.setBody(o.responseText);
	        if (this.evalJS)
	          SUGAR.util.evalScript(o.responseText);
	        if (!SUGAR.isIE)
	            this.body.style.width = w

            SUGAR.inboundEmail.ie_test_popup_dialog = this;
            window.setTimeout(function(){close_ie_test_popup()},3000);
	    }
	}
	var title = SUGAR.language.get('Emails', 'LBL_TEST_SETTINGS');
	if (typeof(title) == "undefined" || title == "undefined")
	   title = SUGAR.language.get('InboundEmail', 'LBL_TEST_SETTINGS');
	SI.testDlg.setHeader(title);
	SI.testDlg.setBody(SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING"));

    SI.testDlg.render(document.body);
	var Connect = YAHOO.util.Connect;
	if (Connect.url) URL = Connect.url + "&" +  url;
    Connect.asyncRequest("POST", URL, {success:SI.testDlg._updateContent, failure:SI.testDlg.hide, scope:SI.testDlg});
    SI.testDlg.show();

}

function isDataValid(formName, validateMonitoredFolder) {
	var formObject = document.getElementById(formName);
    var errors = new Array();
    var out = new String();

    if(trim(formObject.server_url.value) == "") {
        errors.push(SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_SERVER'));
    }
    if(trim(formObject.email_user.value) == "") {
        errors.push(SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_USER'));
    }
    if(formObject.protocol.protocol == "") {
        errors.push(SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_PROTOCOL'));
    }
    if (formObject.protocol.value == 'imap' && validateMonitoredFolder) {
    	if (trim(formObject.mailbox.value) == "") {
    		errors.push(SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_MONITORED_FOLDER'));
    	} // if
    }
    if(formObject.port.value == "") {
        errors.push(SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_PORT'));
    }

    if(errors.length > 0) {
        out = SUGAR.language.get('app_strings', 'LBL_EMAIL_ERROR_DESC');
        for(i=0; i<errors.length; i++) {
            if(out != "") {
                out += "\n";
            }
            out += errors[i];
        }

        alert(out);
        return false;
    } else {
        return true;
    }

} // fn

function getFoldersListForInboundAccount(module_name, action, pageTarget, width, height, mail_server, protocol, port, login, password, mailbox, ssl, personal, searchFieldValue, formName) {
	if (!formName) formName = "testSettingsView";

	var words = getEncryptedPassword(login, password, mailbox);
	var isPersonal = (personal) ? 'true' : 'false';

	// launch the popup
	URL = 'index.php?'
        + 'module=' + module_name
        + '&to_pdf=1'
        + '&action=' + action
        + '&target=' + pageTarget
        + '&target1=' + pageTarget
        + '&server_url=' + mail_server
        + '&email_user=' + words[0]
        + '&protocol=' + protocol
        + '&port=' + port
        + '&email_password=' + encodeURIComponent(words[1])
        + '&mailbox=' + words[2]
        + '&ssl=' + ssl
        + '&personal=' + isPersonal
		+ '&searchField='+ searchFieldValue;

	var SI = SUGAR.inboundEmail;
    if (!SI.listDlg) {
        SI.listDlg = new YAHOO.widget.SimpleDialog("selectFoldersDiv", {
            width: width + "px",
            draggable: true,
            dragOnly: true,
            close: true,
            constraintoviewport: true,
            modal: true,
            loadingText: SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING")
        });
        SI.listDlg._updateContent = function (o) {
            var w = this.cfg.config.width.value + "px";
            this.setBody(o.responseText);
            SUGAR.util.evalScript(o.responseText);
            if (!SUGAR.isIE)
                this.body.style.width = w
        }
    }
    SI.listDlg.setHeader(SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING"));
    SI.listDlg.setBody('');

    SI.listDlg.render(document.body);
    var Connect = YAHOO.util.Connect;
    if (Connect.url) URL = Connect.url + "&" +  url;
    Connect.asyncRequest("POST", URL, {success:SI.listDlg._updateContent, failure:SI.listDlg.hide, scope:SI.listDlg});
    SI.listDlg.show();

} // fn

function setPortDefault() {
	var prot	= document.getElementById('protocol');
	var ssl		= document.getElementById('ssl');
	var port	= document.getElementById('port');
	var stdPorts= new Array("110", "143", "993", "995");
	var stdBool	= new Boolean(false);

	if(port.value == '') {
		stdBool.value = true;
	} else {
		for(i=0; i<stdPorts.length; i++) {
			if(stdPorts[i] == port.value) {
				stdBool.value = true;
			}
		}
	}

	if(stdBool.value == true) {
		if(prot.value == 'imap' && ssl.checked == false) { // IMAP
			port.value = "143";
		} else if(prot.value == 'imap' && ssl.checked == true) { // IMAP-SSL
			port.value = '993';
		} else if(prot.value == 'pop3' && ssl.checked == false) { // POP3
			port.value = '110';
		} else if(prot.value == 'pop3' && ssl.checked == true) { // POP3-SSL
			port.value = '995';
		}
	}
}

function toggle_monitored_folder(field) {

	var field1=document.getElementById('protocol');
	//var target=document.getElementById('pop3_warn');
	//var mark_read = document.getElementById('mark_read');
	var mailbox = document.getElementById('mailbox');
	//var inbox = document.getElementById('inbox');
	var label_inbox = document.getElementById('label_inbox');
	var subscribeFolderButton = document.getElementById('subscribeFolderButton');
	var trashFolderRow = document.getElementById('trashFolderRow');
	var trashFolderRow1 = document.getElementById('trashFolderRow1');
	var sentFolderRow = document.getElementById('sentFolderRow');

	if (field1.value == 'imap') {
		//target.style.display="none";
		mailbox.disabled=false;
        // This is not supported in IE
        try {
          mailbox.style.display = '';
		  trashFolderRow.style.display = '';
		  sentFolderRow.style.display = '';
		  trashFolderRow1.style.display = '';
		  //mailbox.type='text';
          subscribeFolderButton.style.display = '';
        } catch(e) {};
		//inbox.style.display='';
		label_inbox.style.display='';
	}
	else {
		//target.style.display="";
		mailbox.value = "INBOX";
        mailbox.disabled=false; // cannot disable, else the value is not passed
        // This is not supported in IE
        try {
		  mailbox.style.display = "none";
          trashFolderRow.style.display = "none";
		  sentFolderRow.style.display = "none";
		  trashFolderRow1.style.display = "none";
          subscribeFolderButton.style.display = "none";

		  //mailbox.type='hidden';
        } catch(e) {};

		//inbox.style.display = "";
		label_inbox.style.display = "none";
	}
}

/* End of File modules/InboundEmail/InboundEmail.js */

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
SUGAR.email2 = {
    cache : new Object(),
    o : null, // holder for reference to AjaxObject's return object (used in composeDraft())
    reGUID : new RegExp(/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i),
    templates : {},
    tinyInstances : {
        currentHtmleditor : ''
    },

    /**
     * preserves hits from email server
     */ 
    _setDetailCache : function(ret) {
        if(ret.meta) {
            var compKey = ret.meta.mbox + ret.meta.uid;

            if(!SUGAR.email2.cache[compKey]) {
                SUGAR.email2.cache[compKey] = ret;
            }
        }
    },

    autoSetLayout : function() {
    	var c = document.getElementById('container');
        var tHeight = YAHOO.util.Dom.getViewportHeight() - YAHOO.util.Dom.getY(c) - 35;
        //Ensure a minimum height.
        tHeight = Math.max(tHeight, 550);
        c.style.height = tHeight + "px";
        SUGAR.email2.complexLayout.set('height', tHeight);
        SUGAR.email2.complexLayout.set('width', YAHOO.util.Dom.getViewportWidth() - 40);
        SUGAR.email2.complexLayout.render();
        SUGAR.email2.listViewLayout.resizePreview();        
    }
};


/**
 * Shows overlay progress message
 */

//overlayModal
SUGAR.showMessageBoxModal = function(title, body) {
    SUGAR.showMessageBox(title, body);
}

//overlay
SUGAR.showMessageBox = function(reqtitle, body, type, additconfig) {
    var config = { };
    if (typeof(additconfig) == "object") {
        var config = additconfig;
    }
    config.type = type;
    config.title = reqtitle;
    config.msg = body;
    YAHOO.SUGAR.MessageBox.show(config);
}

//hideOverlay
SUGAR.hideMessageBox = function() {
	YAHOO.SUGAR.MessageBox.hide();
};

/* End of File modules/Emails/javascript/EmailUIShared.js */

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

(function() {
	var sw = YAHOO.SUGAR,
		Event = YAHOO.util.Event,
		Connect = YAHOO.util.Connect,
	    Dom = YAHOO.util.Dom
	    SE = SUGAR.email2;

///////////////////////////////////////////////////////////////////////////////
////    EMAIL ACCOUNTS
SE.accounts = {
    outboundDialog : null,
    inboundAccountEditDialog : null,
    inboundAccountsSettingsTable : null,
    outboundAccountsSettingsTable : null,
    testOutboundDialog : null,
    errorStyle : 'input-error',
    normalStyle : '',
    newAddedOutboundId : '',

    /**
     * makes async call to retrieve an outbound instance for editting
     */
     //EXT111
    editOutbound : function(obi) {

            AjaxObject.startRequest(AjaxObject.accounts.callbackEditOutbound, urlStandard + "&emailUIAction=editOutbound&outbound_email=" + obi);

    },
    deleteOutbound : function(obi) {

        if(obi.match(/^(add|line|sendmail)+/)) {
            alert(mod_strings.LBL_INVALID_OPS);
        } else {
        	SUGAR.showMessageBox(app_strings.LBL_EMAIL_DELETING_OUTBOUND, app_strings.LBL_EMAIL_ONE_MOMENT);
            AjaxObject.startRequest(AjaxObject.accounts.callbackDeleteOutbound, urlStandard + "&emailUIAction=deleteOutbound&outbound_email=" + obi);
        }
    },
    //EXT111
    getReplyAddress : function() {
        var primary = '';

        for(var i=0; i<SE.userPrefs.current_user.emailAddresses.length; i++) {
            var addy = SE.userPrefs.current_user.emailAddresses[i];

            if(addy.primary_address == "1") {
                primary = addy.email_address;
            }

            if(addy.reply_to == "1") {
                return addy.email_address;
            }
        }

        return primary;
    },

    /**
     * Called on "Accounts" tab activation event
     */
    lazyLoad : function() {

    	this._setupInboundAccountTable();
    	this._setupOutboundAccountTable();

    },

    _setupInboundAccountTable: function()
    {
    	//Setup the inbound mail settings
    	if(!this.inboundAccountsSettingsTable)
    	{
    		  this.customImageFormatter = function(elLiner, oRecord, oColumn, oData) {
   					var clckEvent = oColumn.key;
   					var imgSrc = "";
   					var is_group = oRecord.getData("is_group");
   					if(!is_group)
   					{
    		  			if(oColumn.key == 'edit')
    		  			{
    		  				clckEvent = "SUGAR.email2.accounts.getIeAccount('"+ oRecord.getData('id') +"')";
    		  				imgSrc = 'index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName='+oColumn.key+'_inline.gif';
    		  			}
    		  			else if(oColumn.key == 'delete')
    		  			{
    		  				clckEvent = "SUGAR.email2.accounts.deleteIeAccount('"+ oRecord.getData('id') +"','" + oRecord.getData('group_id') +"')";
    		  				imgSrc = 'index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName='+oColumn.key+'_inline.gif';
    		  			}
	            		elLiner.innerHTML = '<img onclick="'+clckEvent+'" src="'+imgSrc+'" align="absmiddle" border="0"/>';
   					}
	           };

	           this.showBoolean = function(el, oRecord, oColumn, oData)
	           {
	               var is_group = oRecord.getData("is_group");
	               var bChecked = oData;
	               bChecked = (bChecked) ? " checked" : "";
	               if(!is_group)
	               {
    	               el.innerHTML = "<input type=\"radio\"" + bChecked +
    	                   " name=\"col" + oColumn.getId() + "-radio\"" +
    	                   " class=\"yui-dt-radio\">";
	               }
	           };


	        YAHOO.widget.DataTable.Formatter.customImage = this.customImageFormatter;
	        YAHOO.widget.DataTable.Formatter.showBoolean = this.showBoolean;

	        var typeHoverHelp = '&nbsp;<div id="rollover"><a href="#" class="rollover">'+
	                            '<img border="0" src="index.php?entryPoint=getImage&amp;imageName=helpInline.png">' +
	                            '<div style="text-align:left"><span>' + mod_strings.LBL_EMAIL_INBOUND_TYPE_HELP + '</span></div></a></div>';


    		this.ieColumnDefs = [{key:'name',label:app_strings.LBL_EMAIL_SETTINGS_NAME }, {key:'server_url',label:ie_mod_strings.LBL_SERVER_URL},
    		                      {key:'is_active',label:ie_mod_strings.LBL_STATUS_ACTIVE,formatter:"checkbox",className:'yui-cstm-cntrd-liner'},
    		                      {key:'is_default',label:app_strings.LBL_EMAIL_ACCOUNTS_SMTPDEFAULT,formatter:"showBoolean",className:'yui-cstm-cntrd-liner'},
    		                      {key:'type',label:mod_strings.LBL_LIST_TYPE + typeHoverHelp },
    		                      {key:'edit',label:mod_strings.LBL_BUTTON_EDIT,formatter:"customImage",className:'yui-cstm-cntrd-liner'},
    		                      {key:'delete',label:app_strings.LBL_EMAIL_DELETE,formatter:"customImage",className:'yui-cstm-cntrd-liner'}];
    		var query = "index.php?module=Emails&action=EmailUIAjax&to_pdf=true&emailUIAction=rebuildShowAccount";
    		this.ieDataSource = new YAHOO.util.DataSource(query);
			this.ieDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
			this.ieDataSource.responseSchema = {
				resultsList: "account_list",
				fields: [{key:'id'},{key:'name'},'is_active',{key:'server_url'},'is_group','group_id','is_default','has_groupfolder','type']
			};
    		this.inboundAccountsSettingsTable = new YAHOO.widget.DataTable("inboundAccountsTable", this.ieColumnDefs, this.ieDataSource);
			this.inboundAccountsSettingsTable.subscribe("checkboxClickEvent", function(oArgs){

	            var elCheckbox = oArgs.target;
	            var oColumn = this.getColumn(elCheckbox);
	          	if(oColumn.key == 'is_active')
	          	{
	          		var oRecord = this.getRecord(elCheckbox);
	          		oRecord.setData("is_active",elCheckbox.checked);
	          		var t_id = oRecord.getData('id');
	            	var isGroupFolder = oRecord.getData('has_groupfolder');

	            	if(isGroupFolder)
	            	    SUGAR.email2.folders.updateSubscriptions();
	            	else
    	            	SUGAR.email2.folders.setFolderSelection();

	          	}
	        });
			var lastDefaultSelectedId = "";
    		this.inboundAccountsSettingsTable.subscribe("radioClickEvent", function(oArgs){

	            var elRadio = oArgs.target;
	            var oColumn = this.getColumn(elRadio);
	          	if(oColumn.key == 'is_default')
	          	{
	          		var oRecord = this.getRecord(elRadio);
	          		var t_id = oRecord.getData('id');
	          		var t_isGroup = oRecord.getData('is_group');
	          		if(t_id != lastDefaultSelectedId && !t_isGroup)
	          		{
						SUGAR.default_inbound_accnt_id = t_id; //Set in the global space for access during compose
	          			lastDefaultSelectedId = t_id;
	          			AjaxObject.startRequest(callbackDefaultOutboundSave, urlStandard + "&emailUIAction=saveDefaultOutbound&id="+ t_id);
	          		}
	          		else if(t_isGroup)
	          		   YAHOO.util.Event.preventDefault(oArgs.event); //Do not allow users to select group mailboxes as a default.

	          	}
	        });

			this.inboundAccountsSettingsTable.subscribe("rowMouseoverEvent", this.inboundAccountsSettingsTable.onEventHighlightRow);
			this.inboundAccountsSettingsTable.subscribe("rowMouseoutEvent", this.inboundAccountsSettingsTable.onEventUnhighlightRow);
        }
    },
     _setupOutboundAccountTable: function()
    {
    	if(!this.outboundAccountsSettingsTable)
    	{
	        this.obImageFormatter = function(elLiner, oRecord, oColumn, oData) {
   					var clckEvent = oColumn.key;
   					var imgSrc = "";
   					var isEditable = oRecord.getData("is_editable");
   					var type = oRecord.getData("type");
   					if(isEditable)
   					{
    		  			if(oColumn.key == 'edit')
    		  			{
    		  				clckEvent = "SUGAR.email2.accounts.editOutbound('"+ oRecord.getData('id') +"')";
    		  				imgSrc = 'index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName='+oColumn.key+'_inline.gif';
    		  			}
    		  			else if(oColumn.key == 'delete' && type == 'user')
    		  			{
    		  				clckEvent = "SUGAR.email2.accounts.deleteOutbound('"+ oRecord.getData('id')+"')";
    		  				imgSrc = 'index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName='+oColumn.key+'_inline.gif';
    		  			}
    		  			if(imgSrc != '')
	            		    elLiner.innerHTML = '<img onclick="'+clckEvent+'" src="'+imgSrc+'" align="absmiddle" border="0"/>';
   					}
	        };

	        //Custom formatter to display any error messages.
			this.messageDisplay = function(elLiner, oRecord, oColumn, oData) {

                    if(SUGAR.email2.composeLayout.outboundAccountErrors == null)
    		  			    SUGAR.email2.composeLayout.outboundAccountErrors = {};

    		        var id = oRecord.getData('id');
   					var message = oRecord.getData("errors");
   					if(message != '')
   					{
    		  		    elLiner.innerHTML = '<span class="required">' + message + '</span>';
    		  			//Add the id and message for all outbound accounts.
    		  			SUGAR.email2.composeLayout.outboundAccountErrors[id] = message;
   					}
   					else
   					{
   					    if(typeof(SUGAR.email2.composeLayout.outboundAccountErrors[id]) != 'undefined' )
    		  			    delete SUGAR.email2.composeLayout.outboundAccountErrors[id];
   					}
	        };
	        YAHOO.widget.DataTable.Formatter.actionsImage = this.obImageFormatter;
	        YAHOO.widget.DataTable.Formatter.messageDisplay = this.messageDisplay;

    		this.obAccntsColumnDefs = [{key:'name',label:app_strings.LBL_EMAIL_ACCOUNTS_NAME }, {key:'mail_smtpserver',label:app_strings.LBL_EMAIL_ACCOUNTS_SMTPSERVER},
    								   {key:'edit',label:mod_strings.LBL_BUTTON_EDIT,formatter:"actionsImage",className:'yui-cstm-cntrd-liner'},
    								   {key:'delete', label:app_strings.LBL_EMAIL_DELETE,formatter:"actionsImage",className:'yui-cstm-cntrd-liner'},
    								   {key:'messages',label:'', formatter:"messageDisplay",className:'yui-cstm-cntrd-liner'}];

    		var query = "index.php?module=Emails&action=EmailUIAjax&to_pdf=true&emailUIAction=retrieveAllOutbound";
    		this.obDataSource = new YAHOO.util.DataSource(query);
			this.obDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
			this.obDataSource.responseSchema = {

				resultsList: "outbound_account_list",
				fields: ['id','name','is_editable','mail_smtpserver','type','errors']
			};

    		this.outboundAccountsSettingsTable = new YAHOO.widget.DataTable("outboundAccountsTable", this.obAccntsColumnDefs, this.obDataSource);


			this.outboundAccountsSettingsTable.subscribe("rowMouseoverEvent", this.outboundAccountsSettingsTable.onEventHighlightRow);
			this.outboundAccountsSettingsTable.subscribe("rowMouseoutEvent", this.outboundAccountsSettingsTable.onEventUnhighlightRow);
    		this.outboundAccountsSettingsTable.subscribe("postRenderEvent",this.rebuildMailerOptions);
    	}
    },
    /**
     * Displays a modal diaglogue to edit outbound account settings
     */
    showEditInboundAccountDialogue : function(clear) {

        if(!this.inboundAccountEditDialog) {
        	var EAD = this.inboundAccountEditDialog = new YAHOO.widget.Dialog("editAccountDialogue", {
                modal:true,
				visible:true,
            	fixedcenter:true,
            	constraintoviewport: true,
                width	: "600px",
                shadow	: true
            });
			EAD.showEvent.subscribe(function() {
                var el = this.element,
                    header = this.header;

                setTimeout(function() {
                    // Window heights aren't accurate until the DOM reflow has
                    // completed. For that reason, we wait until the stack has
                    // been cleared before checking for the heights.
                    var viewH = YAHOO.util.Dom.getViewportHeight() - 50,
                        height = parent ? window.innerHeight : viewH;
                        if (header && el && height < el.clientHeight) {
                        var body = header.nextElementSibling;
                        body.style.overflow = "auto";
                        body.style.height = "460px";
                    }
                }, 0);
            }, EAD);
            EAD.setHeader(mod_strings.LBL_EMAIL_ACCOUNTS_INBOUND);
			Dom.removeClass("editAccountDialogue", "yui-hidden");

        } // end lazy load

        if(clear == undefined || clear == true)
        {
	        SE.accounts.clearInboundAccountEditScreen();
	        //Set default protocol to IMAP when creating new records
	        document.forms['ieAccount'].elements['protocol'].value = "imap";
        	SE.accounts.setPortDefault();
        }

        //Check if we should display username/password fields for outbound account if errors were detected.
        this.checkOutBoundSelection();

        this.inboundAccountEditDialog.render();
        this.inboundAccountEditDialog.show();
        SUGAR.util.setEmailPasswordDisplay('email_password', clear == false);
    },

    /**
    *  Set all fields on the outbound edit form to either enabled/disabled
    *  except for the username/password.
    *
    */
    toggleOutboundAccountDisabledFields: function(disable)
    {
        var fields = ['mail_name', 'mail_smtpserver','mail_smtpport','mail_smtpauth_req'];
        for(var i=0;i<fields.length;i++)
        {
            document.getElementById(fields[i]).disabled = disable;
        }
        if(disable)
            Dom.addClass("mail_smtpssl_row", "yui-hidden");
        else
            Dom.removeClass('mail_smtpssl_row', "yui-hidden");

    },
    /**
    * Refresh the inbound accounts table.
    */
    refreshInboundAccountTable : function()
    {
	    this.inboundAccountsSettingsTable.getDataSource().sendRequest('',
	    	{
	    		success: this.inboundAccountsSettingsTable.onDataReturnInitializeTable,
				scope: this.inboundAccountsSettingsTable }
			);
    },
    /**
    * Refresh the outbound accounts table.
    */
    refreshOuboundAccountTable : function()
    {
	    this.outboundAccountsSettingsTable.getDataSource().sendRequest('',
	    	{
	    		success: this.outboundAccountsSettingsTable.onDataReturnInitializeTable,
				scope: this.outboundAccountsSettingsTable }
			);
    },
    /**
     * Displays a modal diaglogue to add a SMTP server
     */
    showAddSmtp : function() {
        // lazy load dialogue
        if(!this.outboundDialog) {
        	this.outboundDialog = new YAHOO.widget.Dialog("outboundDialog", {
                modal:true,
				visible:true,
            	fixedcenter:true,
            	constraintoviewport: true,
                width	: "750px",
                shadow	: true
            });
            this.outboundDialog.setHeader(app_strings.LBL_EMAIL_ACCOUNTS_OUTBOUND);
            this.outboundDialog.hideEvent.subscribe(function(){
            	//If add was used to bring this dialog up, and we are hiding without creating one, then set it back to the first option
            	var out = Dom.get("outbound_email");
                if (out && out.value == "SYSTEM_ADD")
                {
                	out.value = out.options[0].value;
                }
                //Check if we should display username/password for system account.
                SE.accounts.checkOutBoundSelection();
                return true;
            });

            Dom.removeClass("outboundDialog", "yui-hidden");
        } // end lazy load

        // clear out form
        var form = document.getElementById('outboundEmailForm');
        for(i=0; i<form.elements.length; i++) {
            if(form.elements[i].name == 'mail_smtpport') {
                form.elements[i].value = 25;
            } else if(form.elements[i].type != 'button' && form.elements[i].type != 'checkbox') {
                form.elements[i].value = '';
            } else if(form.elements[i].type == 'checkbox') {
                form.elements[i].checked = false;
            }
        }
        //Render the SMTP buttons
        if ( !SUGAR.smtpButtonGroup ) {
            SUGAR.smtpButtonGroup = new YAHOO.widget.ButtonGroup("smtpButtonGroup");
            SUGAR.smtpButtonGroup.subscribe('checkedButtonChange', function(e)
            {
                SUGAR.email2.accounts.changeEmailScreenDisplay(e.newValue.get('value'));
                document.getElementById('smtp_settings').style.display = '';
                form.mail_smtptype.value = e.newValue.get('value');
            });
            YAHOO.widget.Button.addHiddenFieldsToForm(form);
        }
        //Hide Username/Password
        SUGAR.email2.accounts.smtp_authenticate_field_display();
        //Unset readonly fields
        SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(false);
        SUGAR.email2.accounts.changeEmailScreenDisplay('other');
		this.outboundDialog.render();
        this.outboundDialog.show();
    },

    /**
     * Accounts' Advanced Settings view toggle
     */
    toggleAdv : function() {
        var adv = document.getElementById("ie_adv");
        if(adv.style.display == 'none') {
            adv.style.display = "";
        } else {
            adv.style.display = 'none';
        }
    },

	smtp_authenticate_field_display : function() {
		var smtpauth_req = document.getElementById("mail_smtpauth_req");
		document.getElementById("smtp_auth1").style.display = smtpauth_req.checked ? "" : "none";
		document.getElementById("smtp_auth2").style.display = smtpauth_req.checked ? "" : "none";
	},

	smtp_setDefaultSMTPPort : function() {
		useSSLPort = !document.getElementById("mail_smtpssl").options[0].selected;

        if ( useSSLPort && document.getElementById("mail_smtpport").value == '25' ) {
            document.getElementById("mail_smtpport").value = '465';
        }
        if ( !useSSLPort && document.getElementById("mail_smtpport").value == '465' ) {
            document.getElementById("mail_smtpport").value = '25';
        }
	},

    /**
     * Changes the display used in the outbound email SMTP dialog to match the
     */
    changeEmailScreenDisplay : function(smtptype, isSystemAccount)
    {
        document.getElementById("smtpButtonGroupTD").style.display = '';
        document.getElementById("chooseEmailProviderTD").style.display = '';
        document.getElementById("mailsettings1").style.display = '';
        document.getElementById("mailsettings2").style.display = '';
        document.getElementById("mail_smtppass_label").innerHTML = mod_strings.LBL_MAIL_SMTPPASS;
        document.getElementById("mail_smtpport_label").innerHTML = mod_strings.LBL_MAIL_SMTPPORT;
        document.getElementById("mail_smtpserver_label").innerHTML = mod_strings.LBL_MAIL_SMTPSERVER;
        document.getElementById("mail_smtpuser_label").innerHTML = mod_strings.LBL_MAIL_SMTPUSER;

        switch (smtptype) {
        case "yahoomail":
            document.getElementById("mail_smtpserver").value = 'plus.smtp.mail.yahoo.com';
            document.getElementById("mail_smtpport").value = '465';
            document.getElementById("mail_smtpauth_req").checked = true;
            var ssl = document.getElementById("mail_smtpssl");
            for(var j=0;j<ssl.options.length;j++) {
                if(ssl.options[j].text == 'SSL') {
                    ssl.options[j].selected = true;
                    break;
                }
            }
            document.getElementById("mailsettings1").style.display = 'none';
            document.getElementById("mailsettings2").style.display = 'none';
            document.getElementById("mail_smtppass_label").innerHTML =
            document.getElementById("mail_smtppass_label").innerHTML = mod_strings.LBL_YAHOOMAIL_SMTPPASS;
            document.getElementById("mail_smtpuser_label").innerHTML = mod_strings.LBL_YAHOOMAIL_SMTPUSER;
            break;
        case "gmail":
            if(document.getElementById("mail_smtpserver").value == "" || document.getElementById("mail_smtpserver").value == 'plus.smtp.mail.yahoo.com') {
                document.getElementById("mail_smtpserver").value = 'smtp.gmail.com';
                document.getElementById("mail_smtpport").value = '587';
                document.getElementById("mail_smtpauth_req").checked = true;
                var ssl = document.getElementById("mail_smtpssl");
                for(var j=0;j<ssl.options.length;j++) {
                    if(ssl.options[j].text == 'TLS') {
                        ssl.options[j].selected = true;
                        break;
                    }
                }
            }
            //document.getElementById("mailsettings1").style.display = 'none';
            //document.getElementById("mailsettings2").style.display = 'none';
            document.getElementById("mail_smtppass_label").innerHTML = mod_strings.LBL_GMAIL_SMTPPASS;
            document.getElementById("mail_smtpuser_label").innerHTML = mod_strings.LBL_GMAIL_SMTPUSER;
            break;
        case "exchange":
            if ( document.getElementById("mail_smtpserver").value == 'plus.smtp.mail.yahoo.com'
                    || document.getElementById("mail_smtpserver").value == 'smtp.gmail.com' ) {
                document.getElementById("mail_smtpserver").value = '';
            }
            document.getElementById("mail_smtpport").value = '25';
            document.getElementById("mail_smtpauth_req").checked = true;
            document.getElementById("mailsettings1").style.display = '';
            document.getElementById("mailsettings2").style.display = '';
            document.getElementById("mail_smtppass_label").innerHTML = mod_strings.LBL_EXCHANGE_SMTPPASS;
            document.getElementById("mail_smtpport_label").innerHTML = mod_strings.LBL_EXCHANGE_SMTPPORT;
            document.getElementById("mail_smtpserver_label").innerHTML = mod_strings.LBL_EXCHANGE_SMTPSERVER;
            document.getElementById("mail_smtpuser_label").innerHTML = mod_strings.LBL_EXCHANGE_SMTPUSER;
            break;
        }
        if ( (typeof isSystemAccount != 'undefined') && isSystemAccount )
        {
            document.getElementById("smtpButtonGroupTD").style.display = 'none';
            document.getElementById("chooseEmailProviderTD").style.display = 'none';
            document.getElementById("mailsettings2").style.display = 'none';
        }

        SUGAR.email2.accounts.smtp_authenticate_field_display();
        SUGAR.email2.accounts.smtp_setDefaultSMTPPort()
    },

    /**
    * Fill the gmail default values for inbound accounts.
    */
    fillInboundGmailDefaults: function () {

        document.forms['ieAccount'].elements['server_url'].value = "imap.gmail.com";
        document.forms['ieAccount'].elements['ssl'].checked = true;
        document.forms['ieAccount'].elements['protocol'].value = "imap";
        SUGAR.email2.accounts.setPortDefault();
        SUGAR.util.setEmailPasswordDisplay('email_password', false);
    },
    /**
     * Sets Port field to selected protocol and SSL settings defaults
     */
    setPortDefault : function() {
        var prot = document.getElementById('protocol');
        var ssl  = document.getElementById('ssl');
        var port = document.getElementById('port');
        var stdPorts= new Array("110", "143", "993", "995");
        var stdBool    = new Boolean(false);
        var mailboxdiv = document.getElementById("mailboxdiv");
        var trashFolderdiv = document.getElementById("trashFolderdiv");
        var sentFolderdiv = document.getElementById("sentFolderdiv");
		var monitoredFolder = document.getElementById("subscribeFolderButton");
        if(port.value == '') {
            stdBool.value = true;
        } else {
            for(i=0; i<stdPorts.length; i++) {
                if(stdPorts[i] == port.value) {
                    stdBool.value = true;
                }
            }
        }

        if(stdBool.value == true) {
            if(prot.value == 'imap' && ssl.checked == false) { // IMAP
                port.value = "143";
            } else if(prot.value == 'imap' && ssl.checked == true) { // IMAP-SSL
                port.value = '993';
            } else if(prot.value == 'pop3' && ssl.checked == false) { // POP3
                port.value = '110';
            } else if(prot.value == 'pop3' && ssl.checked == true) { // POP3-SSL
                port.value = '995';
            }
        }

        if (prot.value == 'imap') {
        	mailboxdiv.style.display = "";
        	trashFolderdiv.style.display = "";
        	sentFolderdiv.style.display = "";
        	monitoredFolder.style.display = "";
        	if (document.getElementById('mailbox').value == "") {
        		document.getElementById('mailbox').value = "INBOX";
        	}
        } else {
        	mailboxdiv.style.display = "none";
        	trashFolderdiv.style.display = "none";
        	sentFolderdiv.style.display = "none";
			monitoredFolder.style.display = "none";
        	document.getElementById('mailbox').value = "";
        } // else
    },

    /**
     * Draws/removes red boxes around required fields.
     */
    ieAccountError : function(style) {
        document.getElementById('server_url').className = style;
        document.getElementById('email_user').className = style;
        document.getElementById('email_password').className = style;
        document.getElementById('protocol').className = style;
        document.getElementById('port').className = style;
    },

    checkOutBoundSelection: function() {
    	var select = Dom.get('outbound_email');
    	if (!select || select.selectedIndex == -1) { return; }

    	var v = select.options[select.selectedIndex].value;

    	if(v == '')
    	{
    		select.options[select.selectedIndex].selected = false;
    		v = select.options[0].value;
    	}
    	else if (v == 'SYSTEM_ADD')
    		SUGAR.email2.accounts.showAddSmtp();

    	var foundError = false;
    	var errorAccounts = SUGAR.email2.composeLayout.outboundAccountErrors;
		for(i in errorAccounts)
		{
		    if(v == i)
		    {
		        foundError = true;
		        break;
		    }
		}

		//Should username/password fields for outbound account.
		if(foundError)
		    this.toggleInboundOutboundFields(true);
		else
		    this.toggleInboundOutboundFields(false);



    },
    toggleInboundOutboundFields : function (display)
    {
        if(display)
        {
            Dom.removeClass("inboundAccountRequiredUsername", "yui-hidden");
		    Dom.removeClass("inboundAccountRequiredPassword", "yui-hidden");
        }
        else
        {
            Dom.addClass("inboundAccountRequiredUsername", "yui-hidden");
		    Dom.addClass("inboundAccountRequiredPassword", "yui-hidden");
        }
    },
    /**
     * rebuilds the select options for mailer options
     */
    rebuildMailerOptions : function() {
        var select = document.forms['ieAccount'].elements['outbound_email'];
        SE.util.emptySelectOptions(select);

        //Get the available sugar mailers
        var a_outbound = SE.accounts.outboundAccountsSettingsTable.getRecordSet().getRecords();

    	for(i=0;i<a_outbound.length;i++)
    	{
    		var t_record = a_outbound[i];
    		var key = t_record.getData('id');
    		var display = t_record.getData('name') + ' - ' + t_record.getData('mail_smtpserver');

    		var opt = new Option(display, key);
    		select.options.add(opt);
    		if (key == SE.accounts.newAddedOutboundId) {
    			select.options.selectedIndex = i;
    		}
    	}

    	select.options.add(new Option('',''));
    	select.options.add(new Option(mod_strings.LBL_ADD_OUTBOUND_ACCOUNT,'SYSTEM_ADD'));
    	//Hide/Show username password fields if necessary.
    	SE.accounts.checkOutBoundSelection();
    },
    /**
     * Empties all the fields in the accounts edit view
     */
    clearInboundAccountEditScreen:function() {

        document.getElementById('ie_id').value = '';
        document.getElementById('ie_name').value = '';
        document.getElementById('ie_from_name').value = SE.userPrefs.current_user.full_name;
        document.getElementById('ie_from_addr').value = this.getReplyAddress();
        document.getElementById('reply_to_addr').value = '';
        document.getElementById('server_url').value = '';
        document.getElementById('email_user').value = '';
        document.getElementById('email_password').value = '';
        document.getElementById('port').value = '';
        document.getElementById('inbound_mail_smtpuser').value = '';
        document.getElementById('inbound_mail_smtppass').value = '';
        document.ieAccount.protocol.options[0].selected = true;
        // handle SSL
        document.getElementById('ssl').checked = false;
        SUGAR.util.setEmailPasswordDisplay('email_password', false);
    },

    /**
     * Populates an account's fields in Settings->Accounts
     */
    fillIeAccount:function(jsonstr) {
        var o = YAHOO.lang.JSON.parse(jsonstr);

        document.getElementById('ie_id').value = o.id;
        document.getElementById('ie_name').value = o.name;
        if (o.stored_options != null) {
        	document.getElementById('ie_from_name').value = o.stored_options.from_name == 'undefined' ? '' : o.stored_options.from_name;
        	document.getElementById('ie_from_addr').value = o.stored_options.from_addr == 'undefined' ? '' : o.stored_options.from_addr;
        	document.getElementById('reply_to_addr').value = typeof(o.stored_options.reply_to_addr) == 'undefined' ? '' : o.stored_options.reply_to_addr;
        	if (o.stored_options.trashFolder != null) {
        		document.getElementById('trashFolder').value = o.stored_options.trashFolder;
        	}
        	if (o.stored_options.sentFolder != null) {
        		document.getElementById('sentFolder').value = o.stored_options.sentFolder;
        	}
        }
        document.getElementById('server_url').value = o.server_url;
        document.getElementById('email_user').value = o.email_user;
        document.getElementById('port').value = o.port;
        document.getElementById('group_id').value = o.group_id;
        document.getElementById('mailbox').value = o.mailbox;


        var i = 0;

        // handle SSL
        if(typeof(o.service[2]) != 'undefined') {
            document.getElementById('ssl').checked = true;
        }

        // handle protocol
        if(document.getElementById('protocol').value != o.protocol) {
            var prot = document.getElementById('protocol');
            for(i=0; i<prot.options.length; i++) {
                if(prot.options[i].value == o.service[3]) {
                    prot.options[i].selected = true;
                    this.setPortDefault();
                }
            }
        }
 // handle SMTP selection
        if(o.stored_options != null && typeof(o.stored_options.outbound_email) != 'undefined') {
            var opts = document.getElementById('outbound_email').options;
            for(i=0; i<opts.length; i++) {
                if(opts[i].value == o.stored_options.outbound_email) {
                    opts[i].selected = true;
                }
            }
        }
    },

    deleteIeAccount : function(IeAccountID,IeGroupID) {
        if(confirm(app_strings.LBL_EMAIL_IE_DELETE_CONFIRM))
        {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_IE_DELETE, app_strings.LBL_EMAIL_ONE_MOMENT);

            AjaxObject.target = 'frameFlex';
            AjaxObject.startRequest(callbackAccountDelete, urlStandard + '&emailUIAction=deleteIeAccount&ie_id='+IeAccountID+'&group_id='+IeGroupID);
            SUGAR.email2.accounts.refreshInboundAccountTable();
         }
     },

       // Null check for Outbound Settings.
    checkOutboundSettings: function() {
        var errorMessage = '';
        var isError = false;
        if (typeof document.forms['outboundEmailForm'] != 'undefined') {
            var mailName = document.getElementById('mail_name').value;
            var smtpServer = document.getElementById('mail_smtpserver').value;
            var smtpPort = document.getElementById('mail_smtpport').value;

            var mailsmtpauthreq = document.getElementById('mail_smtpauth_req');
            if(trim(mailName) == '') {
                isError = true;
                errorMessage += app_strings.LBL_EMAIL_ACCOUNTS_NAME + "<br/>";
            }
            if(trim(smtpServer) == '') {
                isError = true;
                errorMessage += app_strings.LBL_EMAIL_ACCOUNTS_SMTPSERVER + "<br/>";
            }
            if(trim(smtpPort) == '') {
                isError = true;
                errorMessage += app_strings.LBL_EMAIL_ACCOUNTS_SMTPPORT + "<br/>";
            }
            if(mailsmtpauthreq.checked) {
                if(trim(document.getElementById('mail_smtpuser').value) == '') {
                    isError = true;
                    errorMessage += app_strings.LBL_EMAIL_ACCOUNTS_SMTPUSER + "<br/>";
                }
            }
        }
        if(isError) {
            SUGAR.showMessageBox(mod_strings.ERR_MISSING_REQUIRED_FIELDS, errorMessage, 'alert');
            return false;
        } else {
            return true;
        }
    },

    testOutboundSettings: function() {
        var errorMessage = '';
        var isError = false;
    	var fromAddress = document.getElementById("outboundtest_from_address").value;
    	if (trim(fromAddress) == "") {
            errorMessage += app_strings.LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR + "<br/>";
            SUGAR.showMessageBox(mod_strings.ERR_MISSING_REQUIRED_FIELDS, errorMessage, 'alert');
            return false;

    	}
        else if (!isValidEmail(fromAddress)) {
            errorMessage += app_strings.LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR + "<br/>";
            SUGAR.showMessageBox(mod_strings.ERR_INVALID_REQUIRED_FIELDS, errorMessage, 'alert');
            return false;
        }

        //Hide the dialogue and show an in progress indicator.
        SE.accounts.testOutboundDialog.hide();
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_PERFORMING_TASK, app_strings.LBL_EMAIL_ONE_MOMENT, 'plain');

        //If the outbound mail type is a system override we need to re-enable the post fields otherwise
        //nothing is sent in the request.
        var outboundType = document.forms['outboundEmailForm'].elements['type'].value;
        SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(false);

        YAHOO.util.Connect.setForm(document.getElementById("outboundEmailForm"));
        if(outboundType == 'system-override')
            SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(true);

        var data = "&emailUIAction=testOutbound&outboundtest_from_address=" +  fromAddress;
        AjaxObject.startRequest(callbackOutboundTest, urlStandard + data);

    },

    testOutboundSettingsDialog: function() {
        //Ensure that all settings are correct before proceeding to send test email.
        if(!SE.accounts.checkOutboundSettings())
            return;

        // lazy load dialogue
        if(!SE.accounts.testOutboundDialog) {
        	SE.accounts.testOutboundDialog = new YAHOO.widget.Dialog("testOutboundDialog", {
                modal:true,
				visible:true,
            	fixedcenter:true,
            	constraintoviewport: true,
                width	: 600,
                shadow	: true
            });
            SE.accounts.testOutboundDialog.setHeader(app_strings.LBL_EMAIL_TEST_OUTBOUND_SETTINGS);
            Dom.removeClass("testOutboundDialog", "yui-hidden");
        } // end lazy load
        SE.accounts.testOutboundDialog.render();
        SE.accounts.testOutboundDialog.show();
    },

    /**
     * Saves Outbound email settings
     */
    saveOutboundSettings : function() {
        if(SE.accounts.checkOutboundSettings()) {
            //Enable the form fields for the post.
            SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(false);
            YAHOO.util.Connect.setForm(document.getElementById("outboundEmailForm"));
            AjaxObject.startRequest(callbackOutboundSave, urlStandard + "&emailUIAction=saveOutbound");
        } else {
            return false;
        }
    },

    saveIeAccount : function() {

        //Before saving check if there are any error messages associated with the outbound account.
        var outboundID = document.getElementById('outbound_email').value;

        if( SE.accounts.checkIeCreds({'valiateTrash': true,'validateFromAddr': true,'validateOutbound' :true,
            'validateSMTPCreds':true}) )
        {
            document.getElementById('saveButton').disabled = true;

            SUGAR.showMessageBox(app_strings.LBL_EMAIL_IE_SAVE, app_strings.LBL_EMAIL_ONE_MOMENT);

            var formObject = document.getElementById('ieAccount');
            YAHOO.util.Connect.setForm(formObject);

            AjaxObject._reset();
            AjaxObject.target = 'frameFlex';
            AjaxObject.startRequest(callbackAccount, urlStandard + '&emailUIAction=saveIeAccount');
        }
    },

    testSettings : function() {
        form = document.getElementById('ieAccount');

        if(SE.accounts.checkIeCreds()) {
            ie_test_open_popup_with_submit("InboundEmail", "Popup", "Popup", 400, 300, trim(form.server_url.value), form.protocol.value, trim(form.port.value), trim(form.email_user.value), Rot13.write(form.email_password.value), trim(form.mailbox.value), form.ssl.checked, true, "ieAccount", form.ie_id.value);
        }
    },

    getFoldersListForInboundAccountForEmail2 : function() {
        form = document.getElementById('ieAccount');
        if(SE.accounts.checkIeCreds()) {
        	var mailBoxValue = form.mailbox.value;
        	if (form.searchField.value.length > 0) {
        		mailBoxValue = "";
        	} // if
            getFoldersListForInboundAccount("InboundEmail", "ShowInboundFoldersList", "Popup", 400, 300, form.server_url.value, form.protocol.value, form.port.value, form.email_user.value, Rot13.write(form.email_password.value), mailBoxValue, form.ssl.checked, true, form.searchField.value );
        } // if

    },

    checkIeCreds : function(validateRules) {
        if(typeof(validateRules) == 'undefined')
            validateRules = {};

        var errors = new Array();
        var out = new String();

        var ie_name = Dom.get('ie_name').value;
        var fromAddress = Dom.get('ie_from_addr').value;
        var server_url = Dom.get('server_url').value;
        var email_user = Dom.get('email_user').value;
        var email_password = Dom.get('email_password').value;
        var protocol = Dom.get('protocol').value;
        var port = Dom.get('port').value;
        var oe = Dom.get('outbound_email');

        // Bug 44392: IE9 and possibly previous versions have a quirk where selectedIndex is -1 if you have nothing selected vs 0 for
        // other browsers. And if you check options[-1] it returns "unknown" instead of undefined. Also other options out of index
        // return null instead of undefined for other browsers, thus we need to check for all the possible outcomes.
        var oe_value = (typeof(oe.options[oe.selectedIndex]) === 'undefined' || typeof(oe.options[oe.selectedIndex]) === 'unknown' || typeof(oe.options[oe.selectedIndex]) === null) ? "" : oe.options[oe.selectedIndex].value;

        var outboundUserName = Dom.get('inbound_mail_smtpuser').value;
        var outboundPass = Dom.get('inbound_mail_smtppass').value;

        //If the username and password were provided then ignore the error messge

        var outboundCredentialsFound = false;

        if(outboundUserName != "" && outboundPass != "")
            outboundCredentialsFound = true;

        var validateSMTPCreds = (typeof(validateRules.validateSMTPCreds) != 'undefined' && validateRules.validateSMTPCreds);

        if ( SE.composeLayout.outboundAccountErrors != null && SE.composeLayout.outboundAccountErrors[oe_value] != null
             && validateSMTPCreds)
        {
            if(trim(outboundUserName) == "") {
                errors.push(app_strings.LBL_EMAIL_ACCOUNTS_SMTPUSER);
            }
            if(trim(outboundPass) == "") {
                errors.push(app_strings.LBL_EMAIL_ACCOUNTS_SMTPPASS);
            }
        }

        if(trim(ie_name) == "") {
            errors.push(app_strings.LBL_EMAIL_ERROR_NAME);
        }

        if ( typeof(validateRules.validateFromAddr) != 'undefined' && validateRules.validateFromAddr)
        {
            if(trim(fromAddress) == "" || !isValidEmail(fromAddress) ) {
                errors.push(app_strings.LBL_EMAIL_ERROR_FROM_ADDRESS);
            }
        }


        if( (typeof(validateRules.validateOutbound) != 'undefined' && validateRules.validateOutbound) && ( trim(oe_value) == ""
             || trim(oe_value) == "SYSTEM_ADD") ) {
            errors.push(app_strings.LBL_EMAIL_ERROR_NO_OUTBOUND);
        }
        if(trim(server_url) == "") {
            errors.push(app_strings.LBL_EMAIL_ERROR_SERVER);
        }
        if(trim(email_user) == "") {
            errors.push(app_strings.LBL_EMAIL_ERROR_USER);
        }
        if(protocol == "") {
            errors.push(app_strings.LBL_EMAIL_ERROR_PROTOCOL);
        }
        if (protocol == 'imap') {
        	var mailbox = document.getElementById('mailbox').value;
        	if (trim(mailbox) == "") {
        		errors.push(app_strings.LBL_EMAIL_ERROR_MONITORED_FOLDER);
        	} // if
        	if (typeof(validateRules.valiateTrash) != 'undefined' && validateRules.valiateTrash) {
	        	var trashFolder = document.getElementById('trashFolder').value;
	        	if (trim(trashFolder) == "") {
	        		errors.push(app_strings.LBL_EMAIL_ERROR_TRASH_FOLDER);
	        	} // if
			} // if
        }
        if(port == "") {
            errors.push(app_strings.LBL_EMAIL_ERROR_PORT);
        }

        if(errors.length > 0) {
            out = app_strings.LBL_EMAIL_ERROR_DESC;
            for(i=0; i<errors.length; i++) {
                if(out != "") {
                    out += "\n";
                }
                out += errors[i];
            }

            alert(out);
            return false;
        } else {

            return true;
        }
    },

    getIeAccount : function(ieId) {
        if(ieId == '')
            return;

        SUGAR.showMessageBox(app_strings.LBL_EMAIL_SETTINGS_RETRIEVING_ACCOUNT, app_strings.LBL_EMAIL_ONE_MOMENT);
		var query = "&emailUIAction=getIeAccount&ieId=" + ieId;

        AjaxObject.startRequest(callbackIeAccountRetrieve, urlStandard + query);
    },

    /**
     * Iterates through TreeView nodes to apply styles dependent nature of node
     */
    renderTree:function() {
        SE.util.cascadeNodes(SE.tree.getRoot(), SE.accounts.setNodeStyle);
        SE.tree.render();
    },

    //Sets the style for any nodes that need it.
    setNodeStyle : function(node) {
    	if (!node.data.origText) {
            node.data.origText = node.data.text;
    	}
    	//Set unread
    	if (typeof(node.data.unseen) != 'undefined') {
    		if (node.data.unseen > 0) {
				node.setUpLabel(node.data.origText + '(' + node.data.unseen + ')');
                // Add bold style to label, kinda hacky
                node.labelStyle += " ygtvlabelbold";
			}
			else {
				node.setUpLabel(node.data.origText);
			}
    	} else {
    		node.setUpLabel(node.data.origText);
    	}
    	SE.accounts.setupDDTarget(node);
    },

    setupDDTarget : function(node) {
    	if (node.ddTarget) {
    		node.ddTarget.removeFromGroup();
    		delete node.ddTarget;
        }
    	var id = node.getElId();
    	var num = id.substring(4);
    	if (node.data.origText != SUGAR.language.get("Emails", "LNK_MY_INBOX") &&
			node.data.origText != SUGAR.language.get("Emails", "LNK_MY_DRAFTS") &&
			node.data.origText != SUGAR.language.get("Emails", "LNK_SENT_EMAIL_LIST")) {

    		node.ddTarget = new SUGAR.email2.folders.folderDD("ygtvcontentel" + num);
    	}
    	else if (node.data.origText == SUGAR.language.get("Emails", "LNK_MY_INBOX")){
    		node.ddTarget = new YAHOO.util.DDTarget("ygtvcontentel" + num);
    	}
    },

    /**
     * Async call to rebuild the folder list.  After a folder delete or account delete
     */
    rebuildFolderList : function() {
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_REBUILDING_FOLDERS, app_strings.LBL_EMAIL_ONE_MOMENT);
        AjaxObject.startRequest(callbackFolders, urlStandard + '&emailUIAction=rebuildFolders');
    },

    /**
     * Returns the number of remote accounts the user has active.
     */
    getAccountCount : function() {
        var tree = SE.tree;
        var count = 0;
        for(i=0; i<tree._nodes.length; i++) {
            var node = tree._nodes[i];

            if(typeof(node) != 'undefined' && node.data.ieId) {
                count++;
            }
        }
        return count;
    }
};
////    END ACCOUNTS
///////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////
////    CONTEXT MENU CALLS
SE.contextMenus = {

    assignToDialogue : null,
    /**
     * Archives from context menu
     * @param Array uids
     * @param string ieId
     * @param string folder
     */
    _archiveToSugar : function(uids, ieId, folder) {
        var ser = '';

        for(var i=0; i<uids.length; i++) { // using 1 index b/c getSelectedRowIds doubles the first row's id
            if(ser != "") ser += app_strings.LBL_EMAIL_DELIMITER;
            ser += uids[i];
        }
        AjaxObject.startRequest(callbackImportOneEmail, urlStandard + '&emailUIAction=getImportForm&uid=' + ser + "&ieId=" + ieId + "&mbox=" + folder);
    },

    /**
     * Archives from context menu
     */
    archiveToSugar : function(menuItem) {
        SE.contextMenus.emailListContextMenu.hide();

        var rows = SE.grid.getSelectedRows();
        var uids = [];
        /* iterate through available rows JIC a row is deleted - use first available */
        for(var i=0; i<rows.length; i++) {
            uids[i] = SE.grid.getRecord(rows[0]).getData().uid;
        }
        var data = SE.grid.getRecord(rows[0]).getData();
        SE.contextMenus._archiveToSugar(uids, data.ieId, data.mbox);
    },

    /**
     * Popup the printable version and start system's print function.
     */
    viewPrintable : function(menuItem) {
    	var rows = SE.grid.getSelectedRows();
    	var data = SE.grid.getRecord(rows[0]).getData();
    	SE.detailView.viewPrintable(data.ieId, data.uid, data.mbox);
    },

    /**
     * Marks email flagged on mail server
     */
    markRead : function(type, contextMenuId) {
        SE.contextMenus.markEmail('read');
    },

    /**
     * Assign this emails to people based on assignment rules
     */
    assignEmailsTo : function(type, contextMenuId) {
        if(!SE.contextMenus.assignToDialogue)
        {
         	SE.contextMenus.assignToDialogue = new YAHOO.widget.Dialog("assignToDiv", {
                modal:true,
				visible:false,
            	fixedcenter:true,
            	constraintoviewport: true,
                width	: "600px",
                shadow	: true
            });
            SE.contextMenus.assignToDialogue.setHeader(app_strings.LBL_EMAIL_ASSIGN_TO);
            enableQS(true);
        }

        Dom.removeClass("assignToDiv", "yui-hidden");
        SE.contextMenus.assignToDialogue.render();
        SE.contextMenus.assignToDialogue.show();
    },

    /**
     * Marks email flagged on mail server
     */
    markFlagged : function(contextMenuId) {
        SE.contextMenus.markEmail('flagged');
    },

    /**
     * Marks email unflagged on mail server
     */
    markUnflagged : function(contextMenuId) {
        SE.contextMenus.markEmail('unflagged');
    },

    /**
     * Marks email unread on mail server
     */
    markUnread : function() {
        SE.contextMenus.markEmail('unread');
    },

    /**
     * Deletes an email from context menu
     */
    markDeleted : function() {
    	if(confirm(app_strings.LBL_EMAIL_DELETE_CONFIRM)) {
        	document.getElementById('_blank').innerHTML = "";
        	SE.contextMenus.markEmail('deleted');
    	}
    },

    /**
     * generic call API to apply a flag to emails on the server and on sugar
     * @param string type "read" | "unread" | "flagged" | "deleted"
     */
    markEmail : function(type) {
        SE.contextMenus.emailListContextMenu.hide();

        //var dm = SE.grid.getStore();
        //var uids = SE.grid.getSelectedRowIds();
        //var indexes = SE.grid.getSelectedRowIndexes();
        var rows = SE.grid.getSelectedRows();
        if (rows.length == 0)
        	rows = [SE.contextMenus.currentRow];
        var ser = [ ];

        for(var i=0; i<rows.length; i++) {
            ser.push(SE.grid.getRecord(rows[i]).getData().uid);
        }

        ser = YAHOO.lang.JSON.stringify(ser);

        var ieId = SE.grid.getRecord(rows[0]).getData().ieId;
        var folder = SE.grid.getRecord(rows[0]).getData().mbox;


        var count = 0;


        if(type == 'read' || type == 'deleted') {
            // mark read
            for(var j=0; j<rows.length; j++) {
                if(SE.grid.getRecord(rows[j]).getData().seen == '0') {
                    count = count + 1;
                    SE.grid.getRecord(rows[j]).setData("seen", "1");
                }
            }
           //bug# 40257 - adding if condition to check the ieId (Id of a sugar mail box) , which would be null for search email results
            if(ieId){
            var node = SE.folders.getNodeFromIeIdAndMailbox(ieId, folder);
            var unseenCount = node.data.unseen;
            if (isNaN(unseenCount)) {
            	unseenCount = 0;
            }
            var finalCount = parseInt(unseenCount) - count;
            node.data.unseen = finalCount;

            SE.accounts.renderTree();
            }
        } else if(type == 'unread') {
            // mark unread
            for(var j=0; j<rows.length; j++) {
                if(SE.grid.getRecord(rows[j]).getData().seen == '1') { // index [9] is the seen flag
                    count = count + 1;
                }
            }

            var node = SE.folders.getNodeFromIeIdAndMailbox(ieId, folder);
            var unseenCount = node.data.unseen;
            if (isNaN(unseenCount)) {
            	unseenCount = 0;
            }
            var finalCount = parseInt(unseenCount) + count;
            node.data.unseen = finalCount;
            SE.accounts.renderTree();
        }

        if (type == 'unread') {
	        for(var i=0; i<rows.length; i++) {
	            SE.cache[folder + SE.grid.getRecord(rows[i]).getData().uid] = null;
	        } // for
        }

		SUGAR.showMessageBox(app_strings.LBL_EMAIL_PERFORMING_TASK, app_strings.LBL_EMAIL_ONE_MOMENT);
        AjaxObject.startRequest(callbackContextmenus.markUnread, urlStandard + '&emailUIAction=markEmail&type=' + type + '&uids=' + ser + "&ieId=" + ieId + "&folder=" + folder);
    },

    /**
     * refreshes the ListView to show changes to cache
     */
    markEmailCleanup : function() {
        SE.accounts.renderTree();
        SUGAR.hideMessageBox();
        SE.listView.refreshGrid();
    },

	showAssignmentDialog : function() {
		if (SE.contextMenus.assignmentDialog == null) {
			AjaxObject.startRequest(callbackAssignmentDialog, urlStandard + '&emailUIAction=getAssignmentDialogContent');
		} else {
			SE.contextMenus.assignmentDialog.show();
		} // else
	},

	/**
     * shows the import dialog with only relate visible.
     */
    relateTo : function() {
        SE.contextMenus.emailListContextMenu.hide();

        var rows = SE.grid.getSelectedRows();
        var data = SE.grid.getRecord(rows[0]).getData();
        var ieId = data.ieId;
        var folder = data.mbox;
        var uids = [];
        /* iterate through available rows JIC a row is deleted - use first available */
        for(var i=0; i<rows.length; i++) {
            uids[i] = SE.grid.getRecord(rows[i]).getData().uid;
        }
        var ser = YAHOO.lang.JSON.stringify(uids);

        AjaxObject.startRequest(callbackRelateEmail, urlStandard + '&emailUIAction=getRelateForm&uid=' + ser + "&ieId=" + ieId + "&mbox=" + folder);
    },

	/**
     * shows the email detail view
     */
    showDetailView : function() {
        SE.contextMenus.emailListContextMenu.hide();
        var rows = SE.grid.getSelectedRows();
        if (rows.length > 1) {
        	alert(app_strings.LBL_EMAIL_SELECT_ONE_RECORD);
        	return;
        }
        var ieId = SE.grid.getRecord(rows[0]).getData().ieId;
        var folder = SE.grid.getRecord(rows[0]).getData().mbox;
        /* iterate through available rows JIC a row is deleted - use first available */
        var uid = SE.grid.getRecord(rows[0]).getData().uid;
        SE.contextMenus.showEmailDetailViewInFullPage(uid);
    },

    /**
     * Open the email detail view in a full page view
     */
    showEmailDetailViewInFullPage: function(id) {
        var app = parent.SUGAR.App,
            route = app.router.buildRoute('Emails', id);

        app.router.navigate(route, {trigger: true});
    },

    /**
     *
     */
    showEmailDetailViewInPopup : function(ieId,uid, folder) {
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_RETRIEVING_RECORD, app_strings.LBL_EMAIL_ONE_MOMENT);
        AjaxObject.startRequest(callbackEmailDetailView, urlStandard + '&emailUIAction=getEmail2DetailView&uid=' + uid + "&ieId=" + ieId + "&mbox=" + folder + "&record=" + uid);
    },

    /**
     * Opens multiple messages from ListView context click
     */
    openMultiple : function() {
        SE.contextMenus.emailListContextMenu.hide();

        var rows = SE.grid.getSelectedRows();
        var uids = SE.listView.getUidsFromSelection();

        if(uids.length > 0) {
            var mbox = SE.grid.getRecord(rows[0]).getData().mbox;
            var ieId = SE.grid.getRecord(rows[0]).getData().ieId;
            SE.detailView.populateDetailViewMultiple(uids, mbox, ieId, true);
        }
    },

    /**
     * Replies/forwards email
     */
    replyForwardEmailContext : function() {
        SE.contextMenus.emailListContextMenu.hide();

        var indexes = SE.grid.getSelectedRows();
        //var dm = SE.grid.getDataModel();
        var type = this.id;

        for(var i=0; i<indexes.length; i++) {
            var row = SE.grid.getRecord(indexes[i]).getData();
            SE.composeLayout.c0_replyForwardEmail(row.ieId, row.uid, row.mbox, type);
        }
    },

    //show menu functions
    showEmailsListMenu : function(grid, row) {

       var data = row.getData();
       var draft = (data.type == "draft");
       var menu = SE.contextMenus.emailListContextMenu;
       var folderNode;

       if (SE.tree)
       {
	       if (data.mbox == 'sugar::Emails')
	           folderNode = SE.folders.getNodeFromIeIdAndMailbox('folder', data.ieId);
	       else
	           folderNode = SE.folders.getNodeFromIeIdAndMailbox(data.ieId, data.mbox);

	       if (  folderNode != null && typeof(folderNode) != "undefined"  && typeof(folderNode.data) != "undefined"
	           && ((folderNode.data.is_group != null) && (folderNode.data.is_group == 'true'))
	          ||  (folderNode != null && folderNode.data.isGroup != null && folderNode.data.isGroup == "true"))
	               menu.getItem(menu.itemsMapping.assignTo).cfg.setProperty("disabled", false); //Assign emails item
	       else
	           menu.getItem(menu.itemsMapping.assignTo).cfg.setProperty("disabled", true); //Assign emails item
       }
       else
           menu.getItem(menu.itemsMapping.assignTo).cfg.setProperty("disabled", true);

       menu.getItem(menu.itemsMapping.archive).cfg.setProperty("disabled", draft);
       menu.getItem(menu.itemsMapping.reply).cfg.setProperty("disabled", draft);
       menu.getItem(menu.itemsMapping.replyAll).cfg.setProperty("disabled", draft);
       menu.getItem(menu.itemsMapping.forward).cfg.setProperty("disabled", draft);
       menu.getItem(menu.itemsMapping.mark).cfg.setProperty("disabled", draft);


       if (data.mbox == "sugar::Emails")
       {
           //Allow users to reassign imported emails
           menu.getItem(menu.itemsMapping.assignTo).cfg.setProperty("disabled", false);
       	   menu.getItem(menu.itemsMapping.archive).cfg.setProperty("disabled", true);
       	   menu.getItem(menu.itemsMapping.viewRelationships).cfg.setProperty("disabled", false);
       	   menu.getItem(menu.itemsMapping.relateTo).cfg.setProperty("disabled", false);
       }
       else
       {
       	   menu.getItem(menu.itemsMapping.viewRelationships).cfg.setProperty("disabled", true);
       	   menu.getItem(menu.itemsMapping.relateTo).cfg.setProperty("disabled", true);
       }
    },

    showFolderMenu : function(grid, rowIndex, event) {
       event.stopEvent();
       var coords = event.getXY();
       SE.contextMenus.emailListContextMenu.showAt([coords[0], coords[1]]);
    }
};

SE.contextMenus.dv = {
    archiveToSugar : function(contextMenuId) {

        SE.contextMenus._archiveToSugar(uids, ieId, folder);
    },

    replyForwardEmailContext : function(all) {
        SE.contextMenus.detailViewContextMenu.hide();
    }

};





////    END SE.contextMenus
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
////    DETAIL VIEW
SE.detailView = {
    consumeMetaDetail: function(ret) {
        // handling if the Email drafts
        if (ret.type === 'draft') {
            SE.composeLayout.c0_composeDraft();
            return;
        }

        // cache contents browser-side
        SE._setDetailCache(ret);

        var displayTemplate = new YAHOO.SUGAR.Template(SE.templates['displayOneEmail']);
        // 2 below must be in global context
        meta = ret.meta;
        meta['panelId'] = SE.util.getPanelId();

        email = ret.meta.email;
        var out = displayTemplate.exec({
            'app_strings' : app_strings,
            'theme' : theme,
            'idx' : targetDiv.id,
            'meta' : meta,
            'email' : meta.email,
            'linkBeans' : linkBeans
        });
        var tabLabel = meta.email.name;
        if (tabLabel != null && tabLabel.length > 25) {
            tabLabel = tabLabel.substring(0, 25) + "...";
        } // if
        targetDiv.set("label", tabLabel);
        targetDiv.set("content", out);

        var displayEmailFrameDiv = document.getElementById('displayEmailFrameDiv' + targetDiv.id);

        if (SUGAR.email2.util.isIe()) {
            displayEmailFrameDiv.style.height = "390px";
        } else {
            displayEmailFrameDiv.style.height = "410px";
        }

        var displayFrame = document.getElementById('displayEmailFrame' + targetDiv.id);
        displayFrame.contentWindow.document.write(email.description);
        displayFrame.contentWindow.document.close();

        displayFrame.contentWindow.setTargetAttributeForUrls = function() {
            if (!this.document.body) {
                return setTimeout(setTargetAttributeForUrls, 1);
            }
            var elements = this.document.body.getElementsByTagName('a');
            for (var i = 0; i < elements.length; i++) {
                elements[i].setAttribute('target', '_blank');
            }
        };

        var script = displayFrame.contentWindow.document.createElement('script');
        script.type = 'text/javascript';
        script.text = 'setTargetAttributeForUrls.call(window);';
        displayFrame.contentWindow.document.body.appendChild(script);

        // hide archive links
        if (ret.meta.is_sugarEmail) {
            document.getElementById("archiveEmail" + targetDiv.id).style.display = "none";
            document.getElementById("btnEmailView" + targetDiv.id).style.display = "none";
        } else if (document.getElementById("showDeialViewForEmail" + targetDiv.id)) {
            document.getElementById("showDeialViewForEmail" + targetDiv.id).style.display = "none";
        } // else

    },

    consumeMetaPreview: function(ret) {
        // cache contents browser-side
        SE._setDetailCache(ret);

        var currrow = SE.grid.getLastSelectedRecord();
        currrow = SE.grid.getRecord(currrow);
        if (!currrow) {
            document.getElementById('_blank').innerHTML = '';
            return;
        }
        // handling if the Email drafts
        if (ret.type === 'draft') {
            if (currrow.getData().uid == ret.uid) {
                SE.composeLayout.c0_composeDraft();
            }
            return;
        }

        if (currrow.getData().uid != ret.meta.uid) {
           return;
        }

        // remove loading sprite
        document.getElementById('_blank').innerHTML = '<iframe id="displayEmailFramePreview"/>';
        var displayTemplate = new YAHOO.SUGAR.Template(SE.templates['displayOneEmail']);
        // 2 below must be in global context
        meta = ret.meta;
        meta['panelId'] = SE.util.getPanelId();

        email = ret.meta.email;
        document.getElementById('_blank').innerHTML = displayTemplate.exec({
            'app_strings' : app_strings,
            'theme' : theme,
            'idx' : 'Preview',
            'meta' : meta,
            'email' : meta.email,
            'linkBeans' : linkBeans
        });

        var displayFrame = document.getElementById('displayEmailFramePreview');
        displayFrame.contentWindow.document.write(email.description);
        displayFrame.contentWindow.document.close();

        displayFrame.contentWindow.setTargetAttributeForUrls = function() {
            if (!this.document.body) {
                return setTimeout(setTargetAttributeForUrls, 1);
            }
            var elements = this.document.body.getElementsByTagName('a');
            for (var i = 0; i < elements.length; i++) {
                elements[i].setAttribute('target', '_blank');
            }
        };

        var script = displayFrame.contentWindow.document.createElement('script');
        script.type = 'text/javascript';
        script.text = 'setTargetAttributeForUrls.call(window);';
        displayFrame.contentWindow.document.body.appendChild(script);

        SE.listViewLayout.resizePreview();

        // hide archive links
        if (ret.meta.is_sugarEmail) {
            document.getElementById("archiveEmailPreview").innerHTML = "&nbsp;";
            document.getElementById("btnEmailViewPreview").style.display = "none";
            document.getElementById("archiveEmail" + meta['panelId']).style.display = "none";
        } else {
            //hide view relationship link
            document.getElementById("showDeialViewForEmail" + meta['panelId']).style.display = "none";
        }
    },

     /**
     * wraps emailDelete() for single messages, comes from preview or tab
     */
    emailDeleteSingle : function(ieId, uid, mbox) {
        if(confirm(app_strings.LBL_EMAIL_DELETE_CONFIRM)) {
            // find active panel and close if the user double clicked the email to view.
            var activeTabId = SE.util.getPanelId();
            if(activeTabId != 'Preview')
                SE.innerLayout.get("activeTab").close();

            document.getElementById('_blank').innerHTML = "";
	        var ser = [ ];
			ser.push(uid);
	        uid = YAHOO.lang.JSON.stringify(ser);
            this.emailDelete(ieId, uid, mbox);
        }
    },

    /**
     * Sends async call to delete a given message
     * @param
     */
    emailDelete : function(ieId, uid, mbox) {
       SUGAR.showMessageBox(app_strings.LBL_EMAIL_DELETING_MESSAGE, app_strings.LBL_EMAIL_ONE_MOMENT);
       AjaxObject.startRequest(callbackContextmenus.markUnread, urlStandard + '&emailUIAction=markEmail&type=deleted&uids=' +
           uid + "&ieId=" + ieId + "&folder=" + mbox);
    },

    /**
     * retrieves one email to display in the preview pane.
     */
    getEmailPreview : function() {
    	var row = SUGAR.email2.listView.currentRow;
    	var data = row.getData();
	    if (data && !(SUGAR.email2.contextMenus.emailListContextMenu.cfg.getProperty("visible") && data.type =='draft')) {
	       var setRead = (data['seen'] == 0) ? true : false;
		   SUGAR.email2.listView.markRead(SUGAR.email2.listView.currentRowIndex, row);
		   SUGAR.email2.detailView.populateDetailView(data['uid'], data['mbox'], data['ieId'], setRead, SUGAR.email2.previewLayout);
	    }
    },

    /**
     * Imports one email into Sugar
     */
    importEmail : function(ieId, uid, mbox) {
        SE.util.clearHiddenFieldValues('emailUIForm');

        SUGAR.showMessageBox(app_strings.LBL_EMAIL_IMPORTING_EMAIL, app_strings.LBL_EMAIL_ONE_MOMENT);

        var vars = "&ieId=" + ieId + "&uid=" + uid + "&mbox=" + mbox;
        AjaxObject.target = '';
        AjaxObject.startRequest(callbackImportOneEmail, urlStandard + '&emailUIAction=getImportForm' + vars);
    },

    /**
     * Populates the frameFlex div with the contents of an email
     */
    populateDetailView : function(uid, mbox, ieId, setRead, destination) {
    	SUGAR.email2.util.clearHiddenFieldValues('emailUIForm');

        var mboxStr = new String(mbox);
        var compKey = mbox + uid;

        if(setRead == true) {
        	SE.listView.boldUnreadRows()
        	SE.folders.decrementUnreadCount(ieId, mbox, 1);
        }

        if(destination == SE.innerLayout) {
        	/*
             * loading email into a tab, peer with ListView
             * targetDiv must remain in the global namespace as it is used by AjaxObject
             */
        	//Check if we already have a tab of the email open
        	var tabs = SE.innerLayout.get("tabs");
        	for (var t in tabs) {
        		if (tabs[t].id && tabs[t].id == uid) {
        			SE.innerLayout.set("activeTab", tabs[t]);
        			return;
        		}
        	}

        	targetDiv = new YAHOO.SUGAR.ClosableTab({
	        		label: loadingSprite,
					scroll : true,
					content : "",
					active : true
	        }, SE.innerLayout);
        	targetDiv.id = uid;
        	SE.innerLayout.addTab(targetDiv);

            // use cache if available
            if(SE.cache[compKey]) {
            	SE.detailView.consumeMetaDetail(SE.cache[compKey]);
            } else {
            	// open email as peer-tab to listView
            	SE.detailView.requestEmailContents(mboxStr, uid, mbox, ieId, AjaxObject.detailView.callback.emailDetail);
               // AjaxObject.startRequest(AjaxObject.detailView.callback.emailDetail, null);
            }
        } else {
            // loading email into preview pane
            document.getElementById('_blank').innerHTML = loadingSprite;

            // use cache if available
            if(SE.cache[compKey]) {
                SE.detailView.consumeMetaPreview(SE.cache[compKey]);
            } else {
                AjaxObject.forceAbort = true;
                // open in preview window
                SE.detailView.requestEmailContents(mboxStr, uid, mbox, ieId, AjaxObject.detailView.callback.emailPreview);
               // AjaxObject.startRequest(AjaxObject.detailView.callback.emailPreview, null);
            }
        }
    },

    requestEmailContents : function(mboxStr, uid, mbox, ieId, callback)
    {
    	if(mboxStr.substring(0,7) == 'sugar::') {
            // display an email from Sugar
            document.getElementById('emailUIAction').value = 'getSingleMessageFromSugar';
        } else {
            // display an email from an email server
            document.getElementById('emailUIAction').value = 'getSingleMessage';
        }
        document.getElementById('mbox').value = mbox;
        document.getElementById('ieId').value = ieId;
        document.getElementById('uid').value = uid;

        YAHOO.util.Connect.setForm(document.getElementById('emailUIForm'));

        AjaxObject.forceAbort = true;
        AjaxObject.target = '_blank';
        AjaxObject.startRequest(callback, null);
    },

    /**
     * Retrieves multiple emails for DetailView
     */
    populateDetailViewMultiple : function(uids, mbox, ieId, setRead) {
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_RETRIEVING_MESSAGE, app_strings.LBL_EMAIL_ONE_MOMENT);
        SE.util.clearHiddenFieldValues('emailUIForm');

        var mboxStr = new String(mbox);

        uids = SE.util.cleanUids(uids);

        if(mboxStr.substring(0,7) == 'sugar::') {
            // display an email from Sugar
            document.getElementById('emailUIAction').value = 'getMultipleMessagesFromSugar';
            document.getElementById('uid').value = uids;
        } else {
            // display an email from an email server
            document.getElementById('emailUIAction').value = 'getMultipleMessages';
            document.getElementById('mbox').value = mbox;
            document.getElementById('ieId').value = ieId;
            document.getElementById('uid').value = uids;
        }

        var formObject = document.getElementById('emailUIForm');
        YAHOO.util.Connect.setForm(formObject);

        AjaxObject.target = 'frameFlex';
        AjaxObject.startRequest(callbackEmailDetailMultiple, null);

        if(setRead == true) {
            var c = uids.split(",");
            SE.folders.decrementUnreadCount(ieId, mbox, c.length);
        }
    },

    /**
     * Makes async call to get QuickCreate form
     * Renders a modal edit view for a given module
     */
    quickCreate : function(module, ieId, uid, mailbox) {
        var get = "&qc_module=" + module + "&ieId=" + ieId + "&uid=" + uid + "&mailbox=" + mailbox;

        if(ieId == null || ieId == "null" || mailbox == 'sugar::Emails') {
            get += "&sugarEmail=true";
        }

        AjaxObject.startRequest(callbackQuickCreate, urlStandard + '&emailUIAction=getQuickCreateForm' + get);
    },

    /**
     * Makes async call to save a quick create
     * @param bool
     */
    saveQuickCreate : function(action) {
        var qcd = SE.detailView.quickCreateDialog;
        if (check_form('form_EmailQCView_' + qcd.qcmodule)) {
	        var formObject = document.getElementById('form_EmailQCView_' + qcd.qcmodule);
	        var theCallback = callbackQuickCreateSave;
	        var accountType = '&sugarEmail=true';
	        if (qcd.ieId != 'null' && qcd.mbox != 'sugar::Emails') {
	           accountType = '&ieId=' + qcd.ieId;
	        }

            if (action == 'reply') {
	           theCallback = callbackQuickCreateSaveAndReply;
	        } else if (action == true) {
	            theCallback = callbackQuickCreateSaveAndAddToAddressBook;
	        }
	        formObject.action.value = 'EmailUIAjax';
	        YAHOO.util.Connect.setForm(formObject);
	        SUGAR.showMessageBox('Saving', app_strings.LBL_EMAIL_ONE_MOMENT);
	        AjaxObject.startRequest(theCallback, "to_pdf=true&emailUIAction=saveQuickCreate&qcmodule=" + qcd.qcmodule + '&uid=' + qcd.uid +
	                               accountType + '&mbox=' + qcd.mbox);
        }
    },

    /**
     * Code to show/hide long list of email address in DetailView
     */
    showCroppedEmailList : function(el) {
        el.style.display = 'none';
        el.previousSibling.style.display = 'inline'
    },
    showFullEmailList : function(el) {
        el.style.display = 'none';
        el.nextSibling.style.display = 'inline';
    },

    /**
     * Shows the QuickCreate overlay
     * @param string ieId
     * @param string uid
     * @param string mailbox
     */
    showQuickCreate : function(ieId, uid, mailbox) {
        var panelId = SE.util.getPanelId();
        var context = document.getElementById("quickCreateSpan" + panelId);

        if (!SE.detailView.cqMenus)
        	SE.detailView.cqMenus = {};

        if (SE.detailView.cqMenus[context])
        	SE.detailView.cqMenus[context].destroy();

	    var menu = SE.detailView.cqMenus[context] = new YAHOO.widget.Menu("qcMenuDiv" + panelId, {
    		lazyload:true,
    		context: ["quickCreateSpan" + panelId, "tr","br", ["beforeShow", "windowResize"]]
        });

	    for (var i=0; i < this.qcmodules.length; i++) {
            var module = this.qcmodules[i];
            menu.addItem({
                text:   app_strings['LBL_EMAIL_QC_' + module.toUpperCase()],
                modulename: module,
                value: module,
                onclick: { fn: function() {
            			SE.detailView.quickCreate(this.value, ieId, uid, mailbox);
            		}
            	}
            });
        }

		menu.render(document.body);
		menu.show();
    },

    /**
     * Displays the "View" submenu in the detailView
     * @param string ieId
     * @param string uid
     * @param string mailbox
     */
    showViewMenu : function(ieId, uid, mailbox) {
        var panelId = SE.util.getPanelId();
        var context = "btnEmailView" + panelId;
        if (!SE.detailView.viewMenus)
        	SE.detailView.viewMenus = {};

        if (SE.detailView.viewMenus[context])
        	SE.detailView.viewMenus[context].destroy();

	    var menu = SE.detailView.viewMenus[context] = new YAHOO.widget.Menu("menuDiv" + panelId, {
    		lazyload:true,
    		context: ["btnEmailView" + panelId, "tl","bl", ["beforeShow", "windowResize"]],
    		clicktohide: true
        });
		menu.addItems(
				(ieId == 'null' || ieId == null) ?
			//No ieId - Sugar Email
			[{
				text: app_strings.LBL_EMAIL_VIEW_RAW,
				onclick: { fn: function() {SE.detailView.viewRaw(ieId, uid, mailbox);} }
            }]
			:
			//IeID exists, on a remote server
			[{
                text: app_strings.LBL_EMAIL_VIEW_HEADERS,
                onclick: { fn: function() {SE.detailView.viewHeaders(ieId, uid, mailbox);}}
            },{
                text: app_strings.LBL_EMAIL_VIEW_RAW,
                onclick: { fn: function() {SE.detailView.viewRaw(ieId, uid, mailbox);}}
            }]
        );
		menu.render(document.body);
		menu.show();


        /*
        //#23108 jchi@07/17/2008
        menu.render('quickCreateSpan'+ panelId);*/
        //this.viewMenu = menu;
        //this.viewMenu.show(context);
    },
    /**
     * Makes async call to get an email's headers
     */
    viewHeaders : function(ieId, uid, mailbox) {
        var get = "&type=headers&ieId=" + ieId + "&uid=" + uid + "&mailbox=" + mailbox;
        AjaxObject.startRequest(AjaxObject.detailView.callback.viewRaw, urlStandard + "&emailUIAction=displayView" + get);
    },

    /**
     * Makes async call to get a printable version
     */
    viewPrintable : function(ieId, uid, mailbox) {
    	if(mailbox == 'sugar::Emails') {
            // display an email from Sugar
            var emailUIAction = '&emailUIAction=getSingleMessageFromSugar';
        } else {
            // display an email from an email server
            var emailUIAction = '&emailUIAction=getSingleMessage';
        }

        var get = "&type=printable&ieId=" + ieId + "&uid=" + uid + "&mbox=" + mailbox;
        AjaxObject.startRequest(AjaxObject.detailView.callback.viewPrint, urlStandard + emailUIAction + get);
    },

    /**
     * Makes async call to get an email's raw source
     */
    viewRaw : function(ieId, uid, mailbox) {
    	var get = "&type=raw&ieId=" + ieId + "&uid=" + uid + "&mailbox=" + mailbox;
        AjaxObject.startRequest(AjaxObject.detailView.callback.viewRaw, urlStandard + "&emailUIAction=displayView" + get);
    },

    /**
     * Display all email addresses in detailview.
     */
    displayAllAddrs : function(el) {
    	el.style.display = 'none';
    	Dom.getNextSibling(el).style.display = 'inline';
    }
};
////    END SE.detailView
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
////    SE.folders
SE.folders = {
    contextMenuFocus : new Object(),

    /**
     * Generates a standardized identifier that allows reconstruction of I-E ID-folder strings or
     * SugarFolder ID - folder strings
     */
    _createFolderId : function(node) {
        var ret = '';

        if(!node.data.id)
            return ret;

        if(node.data.ieId) {
            /* we have a local Sugar folder */
            if(node.data.ieId == 'folder') {
                ret = "sugar::" + node.data.id; // FYI: folder_id is also stored in mbox field
            } else if(node.data.ieId.match(SE.reGUID)) {
                ret = "remote::" + node.data.ieId + "::" + node.data.mbox.substr(node.data.mbox.indexOf("INBOX"), node.data.mbox.length);
            }
        } else {
            ret = node.data.id;
        }

        return ret;
    },

    addChildNode : function(parentNode, childNode) {
        var is_group = (childNode.properties.is_group == 'true') ? 1 : 0;
        var is_dynamic = (childNode.properties.is_dynamic == 'true') ? 1 : 0;
        var node = this.buildTreeViewNode(childNode.label, childNode.properties.id, is_group, is_dynamic, childNode.properties.unseen, parentNode, childNode.expanded);

        if(childNode.nodes) {
            if(childNode.nodes.length > 0) {
                for(j=0; j<childNode.nodes.length; j++) {
                    var newChildNode = childNode.nodes[j];
                    this.addChildNode(node, newChildNode);
                }
            }
        }
    },

    /**
     * Builds and returns a new TreeView Node
     * @param string name
     * @param string id
     * @param int is_group
     * @return object
     */
    buildTreeViewNode : function(name, id, is_group, is_dynamic, unseen, parentNode, expanded) {
        var node = new YAHOO.widget.TextNode(name, parentNode, true);

        //node.href = " SE.listView.populateListFrameSugarFolder(YAHOO.namespace('frameFolders').selectednode, '" + id + "', 'false');";
        node.expanded = expanded;
        node.data = new Object;
        node.data['id'] = id;
        node.data['mbox'] = id; // to support DD imports into BRAND NEW folders
        node.data['label'] = name;
        node.data['ieId'] = 'folder';
        node.data['isGroup'] = (is_group == 1) ? 'true' : 'false';
        node.data['isDynamic'] = (is_dynamic == 1) ? 'true' : 'false';
        node.data['unseen'] = unseen;
        return node;
    },

    /**
     * ensures that a new folder has a valid name
     */
    checkFolderName : function(name) {
        if(name == "")
            return false;

        this.folderAdd(name);
    },

    /**
     * Pings email servers for new email - forces refresh of folder pane
     */
    checkEmailAccounts : function() {
        this.checkEmailAccountsSilent(true);
    },

    checkEmailAccountsSilent : function(showOverlay) {
        if(typeof(SE.folders.checkingMail)) {
            clearTimeout(SE.folders.checkingMail);
        }

        // don't stomp an on-going request
        if(AjaxObject.currentRequestObject.conn == null) {
            if(showOverlay) {
                SUGAR.showMessageBox(app_strings.LBL_EMAIL_CHECKING_NEW,
                      app_strings.LBL_EMAIL_ONE_MOMENT + "<br>&nbsp;<br><i>" + app_strings.LBL_EMAIL_CHECKING_DESC + "</i>");
            }
            AjaxObject.startRequest(AjaxObject.folders.callback.checkMail, urlStandard + '&emailUIAction=checkEmail&all=true');
        } else {
            // wait 5 secs before trying again.
            SE.folders.checkingMail = setTimeout("SE.folders.checkEmailAccountsSilent(false);", 5000);
        }
    },

    /**
     * Starts check of all email Accounts using a loading bar for large POP accounts
     */
    startEmailAccountCheck : function() {
        // don't do two checks at the same time
       if(!AjaxObject.requestInProgress()) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_ONE_MOMENT, app_strings.LBL_EMAIL_CHECKING_NEW, 'progress');
            SE.accounts.ieIds = SE.folders.getIeIds();
            if (SE.accounts.ieIds.length > 0) {
            	AjaxObject.startRequest(AjaxObject.accounts.callbackCheckMailProgress, urlStandard +
                                '&emailUIAction=checkEmailProgress&ieId=' + SE.accounts.ieIds[0] + "&currentCount=0");
            } else {
               SUGAR.hideMessageBox();
            }
        } else {
            // wait 5 secs before trying again.
            SE.folders.checkingMail = setTimeout("SE.folders.startEmailAccountCheck();", 5000);
        }
    },

    /**
     * Checks a single Account check based on passed ieId
     */
     startEmailCheckOneAccount : function(ieId, synch) {
            if (synch) {
                synch = true;
            } else {
                synch = false;
            }
            var mbox = "";
            var node = SE.clickedFolderNode;
            if (node && !synch) {
            	mbox = node.data.mbox;
            } // if
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_CHECKING_NEW, app_strings.LBL_EMAIL_CHECKING_DESC, 'progress');
            SE.accounts.ieIds = [ieId];
            AjaxObject.startRequest(AjaxObject.accounts.callbackCheckMailProgress, urlStandard +
                                '&emailUIAction=checkEmailProgress&mbox=' + mbox + '&ieId=' + ieId + "&currentCount=0&synch=" + synch);
      },


    /**
     * Empties trash for subscribed accounts
     */
    emptyTrash : function() {
        SE.contextMenus.frameFoldersContextMenu.hide();
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_EMPTYING_TRASH, app_strings.LBL_EMAIL_ONE_MOMENT);
        AjaxObject.startRequest(callbackEmptyTrash, urlStandard + '&emailUIAction=emptyTrash');
    },

    /**
     * Clears Cache files of the inboundemail account
     */
    clearCacheFiles : function(ieId) {
        SE.contextMenus.frameFoldersContextMenu.hide();
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_CLEARING_CACHE_FILES, app_strings.LBL_EMAIL_ONE_MOMENT);
        AjaxObject.startRequest(callbackClearCacheFiles, urlStandard + '&ieId=' + ieId + '&emailUIAction=clearInboundAccountCache');
    },


    /**
     * Returns an array of all the active accounts in the folder view
     */
    getIeIds : function() {
         var ieIds = [];
         var root = SE.tree.getRoot();
         for(i=0; i < root.children.length; i++) {
           if ((root.children[i].data.cls == "ieFolder" && root.children[i].children.length > 0) ||
           		(root.children[i].data.isGroup != null && root.children[i].data.isGroup == "true" && root.children[i].children.length > 0)) {
               ieIds.push(root.children[i].children[0].data.ieId);
           }
         }
         return ieIds;
     },

    /**
     * loads folder lists in Settings->Folders
     */
    lazyLoadSettings : function() {
        AjaxObject.timeout = 300000; // 5 min timeout for long checks
        AjaxObject.startRequest(callbackSettingsFolderRefresh, urlStandard + '&emailUIAction=getFoldersForSettings');
    },

    /**
     * After the add new folder is done via folders tab on seetings, this function should get called
     * It will refresh the folder list after inserting an entry on the UI to update the new folder list
     */
    loadSettingFolder : function() {
        AjaxObject.timeout = 300000; // 5 min timeout for long checks
        AjaxObject.startRequest(callbackLoadSettingFolder, urlStandard + '&emailUIAction=getFoldersForSettings');
    },

    /**
     * Recursively removes nodes from the TreeView of type Sugar (data.ieId = 'folder')
     */
    removeSugarFolders : function() {
        var tree = SE.tree;
        var root = tree.getRoot();
        var folder = SE.util.findChildNode(root, "ieId", "folder");
        while(folder) {
            tree.removeNode(folder);
            folder = SE.util.findChildNode(root, "ieId", "folder");
        }
        if (!root.childrenRendered) {
        	root.childrenRendered = true;
        }
    },

    rebuildFolders : function(silent) {
      if (!silent) SUGAR.showMessageBox(app_strings.LBL_EMAIL_REBUILDING_FOLDERS, app_strings.LBL_EMAIL_ONE_MOMENT);
       AjaxObject.startRequest(callbackFolders, urlStandard + '&emailUIAction=getAllFoldersTree');
    },


	retrieveTeamInfo : function() {
       AjaxObject.startRequest(callbackTeamInfoForSettings, urlStandard + '&emailUIAction=retrieveTeamsInfoForSettings');
	},

    /**
     * Updates TreeView with Sugar Folders
     */
    setSugarFolders : function(delay) {
        this.removeSugarFolders();
		//AjaxObject.forceAbort = true;
		AjaxObject.startRequest(callbackRefreshSugarFolders, urlStandard + "&emailUIAction=refreshSugarFolders");
    },

    /**
     * Takes async data object and creates the sugar folders in TreeView
     */
    setSugarFoldersEnd : function(o) {
        var root = SE.tree.getRoot();
        addChildNodes(root, {nodes: o});
        SE.accounts.renderTree();
        //If nothing is loaded in the grid, load "My Inbox"
        if (SE.grid.params.ieId == "undefined") {
        	SE.listView.populateListFrameSugarFolder({data: o[0]}, o[0].id, false);
        }
    },

    startCheckTimer : function() {
        if(SE.userPrefs.emailSettings.emailCheckInterval && SE.userPrefs.emailSettings.emailCheckInterval != -1) {
            var ms = SE.userPrefs.emailSettings.emailCheckInterval * 60 * 1000;

            if(typeof(SE.folders.checkTimer) != 'undefined') {
                clearTimeout(SE.folders.checkTimer);
            }

            SE.folders.checkTimer = setTimeout("SE.folders.checkEmailAccountsSilent(false);", ms);
            if (!SE.userPrefs.emailSettings.firstAutoCheck)
            {
            	SE.userPrefs.emailSettings.firstAutoCheck = true;
            	SE.folders.checkEmailAccountsSilent(false);
            }
        }
    },

    /**
     * makes an async call to save user preference and refresh folder view
     * @param object SELECT list object
     */
    setFolderSelection : function() {
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_REBUILDING_FOLDERS, app_strings.LBL_EMAIL_ONE_MOMENT);

    	var a_rs = SE.accounts.inboundAccountsSettingsTable.getRecordSet().getRecords();
    	var a_active_accnts = "";
    	for(i=0;i<a_rs.length;i++)
    	{
    		var t_record = a_rs[i];
    		var is_active = t_record.getData('is_active');
    		if(is_active)
    			a_active_accnts += ("&ieIdShow[]=" + t_record.getData('id'));
    	}

    	if(a_active_accnts == "")
    		a_active_accnts = "&ieIdShow[]=";

        var query = "&emailUIAction=setFolderViewSelection" + a_active_accnts;

        AjaxObject.startRequest(callbackFolders, urlStandard + query);
    },

    /**
     * makes async call to save user preference for a given node's open state
     * @param object node YUI TextNode object
     */
    setOpenState : function(node) {
        SE.util.clearHiddenFieldValues('emailUIForm');
        var nodePath = node.data.id;
        var nodeParent = node.parent;

        while(nodeParent != null) {
            // root node has no ID param
            if(nodeParent.data != null) {
                nodePath = nodeParent.data.id + "::" + nodePath;
            }

            var nodeParent = nodeParent.parent;
        }

        document.getElementById('emailUIAction').value = 'setFolderOpenState';
        document.getElementById('focusFolder').value = nodePath;

        if(node.expanded == true) {
            document.getElementById('focusFolderOpen').value = 'open';
        } else {
            document.getElementById('focusFolderOpen').value = 'closed';
        }

        var formObject = document.getElementById('emailUIForm');
        YAHOO.util.Connect.setForm(formObject);

        AjaxObject.startRequest(null, null);
    },

    getNodeFromMboxPath : function(path) {
        var tree = YAHOO.widget.TreeView.getTree('frameFolders');
        var a = YAHOO.lang.JSON.parse(path);

        var node = tree.getRoot();

        var i = 0;
        while(i < a.length) {
            node = this.getChildNodeFromLabel(node, a[i]);
            i++;
        }

        return node;
    },

    getChildNodeFromLabel : function(node, nodeLabel) {
        for(i=0; i<node.children.length; i++) {
            if(node.children[i].data.id == nodeLabel) {
                return node.children[i];
            }
        }
    },

    /**
     * returns the node that presumably under the user's right-click
     */
    getNodeFromContextMenuFocus : function() {
        //// get the target(parent) node
        var tree = YAHOO.widget.TreeView.trees.frameFolders;
        var index = -1;
        var target = SE.contextMenus.frameFoldersContextMenu.contextEventTarget;

        // filter local folders
        if(target.className == 'localFolder' || target.className == 'groupInbox') {
            while(target && (target.className == 'localFolder' || target.className == 'groupInbox')) {
                if(target.id == '') {
                    target = target.parentNode;
                } else {
                    break;
                }
            }
        }

        var targetNode = document.getElementById(target.id);
        re = new RegExp(/ygtv[a-z]*(\d+)/i);

        try {
            var matches = re.exec(targetNode.id);
        } catch(ex) {
            return document.getElementById(ygtvlabelel1);
        }

        if(matches) {
            index = matches[1];
        } else {
            // usually parent node
            matches = re.exec(targetNode.parentNode.id);

            if(matches) {
                index = matches[1];
            }
        }

        var parentNode = (index == -1) ? tree.getNodeByProperty('id', 'Home') : tree.getNodeByIndex(index);
        parentNode.expand();

        return parentNode;
    },

    /**
     * Decrements the Unread Email count in folder text
     * @param string ieId ID to look for
     * @param string mailbox name
     * @param count how many to decrement
     */
    decrementUnreadCount : function(ieId, mbox, count) {

        if(mbox == null)
            return;

        if(mbox.indexOf("sugar::") === 0) {
            var node = this.getNodeFromId(ieId);
        } else {
            var node = this.getNodeFromIeIdAndMailbox(ieId, mbox);
        }
        if(node) {
            var unseen = node.data.unseen;
            if(unseen > 0) {
                var check = unseen - count;
                var finalCount = (check >= 0) ? check : 0;
                node.data.unseen = finalCount;
            }
            SE.accounts.renderTree();
        }
    },

    /**
     * gets the TreeView node with a given ID/ieId
     * @param string id ID to look for
     * @return object Node
     */
    getNodeFromId : function(id) {
        SE.folders.focusNode = null;
        SE.util.cascadeNodes(SE.tree.getRoot(), function(ieId) {
            if ((this.data.id && this.data.id == ieId) || (this.data.ieId && this.data.ieId == ieId)) {
                SE.folders.focusNode = this;
                return false;
            }
        }, null, [id]);
        return SE.folders.focusNode;
    },

    /**
     * Uses ieId and mailbox to try to find a node in the tree
     */
    getNodeFromIeIdAndMailbox : function(id, mbox) {
		SE.folders.focusNode = null;
        if (mbox == "sugar::Emails") {
        	mbox = id;
        	id = "folder";
        } // if
    	SE.util.cascadeNodes(SE.tree.getRoot(), function(varsarray) {
    		if (varsarray instanceof Array) {
            if (this.data.ieId && this.data.ieId == varsarray[0]
                    && this.data.mbox == varsarray[1]) {
                SE.folders.focusNode = this;
                return false;
            } }
    		else {
    			if (this.data.ieId && this.data.ieId == varsarray) {
    				SE.folders.focusNode = this;
                    return false;
    			}
    		}
        }, null, [id, mbox]);
        return SE.folders.focusNode;
    },

    unhighliteAll : function() {
    	SE.util.cascadeNodes(SE.tree.getRoot(), function(){this.unhighlight()});
    },

    /**
     * Displays a short form
     */
    folderAdd : function() {
        SE.contextMenus.frameFoldersContextMenu.hide();

        var node = SE.clickedFolderNode;

        if(node != null && node.data) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_FOLDERS_ADD_DIALOG_TITLE,
                    app_strings.LBL_EMAIL_FOLDERS_NEW_FOLDER,
                    'prompt', {fn:SE.folders.folderAddXmlCall, beforeShow: SE.folders.folderAddRegisterEnter, beforeHide: SE.folders.folderRemoveRegisterEnter});
        } else {
            alert(app_strings.LBL_EMAIL_FOLDERS_NO_VALID_NODE);
        }
    },
    
    folderAddRegisterEnter : function() {
    	this.enterKeyListener = new YAHOO.util.KeyListener(YAHOO.util.Dom.get("sugar-message-prompt"),
    															{keys: YAHOO.util.KeyListener.KEY.ENTER},
    															this.buttons[1].handler);

		this.enterKeyListener.enable();
    },

    folderRemoveRegisterEnter : function() {
    	this.enterKeyListener.disable();
    },

    folderAddXmlCall : function(name) {
        if (trim(name) == "") {
        	alert(mod_strings.LBL_ENTER_FOLDER_NAME);
        	return false;
        }
        name = escape(name);
    	var post = '';
        var type = 'sugar';

        var parentNode = SE.clickedFolderNode;

        this.contextMenuFocus = parentNode;

        if(parentNode.data.ieId) {
            if(parentNode.data.ieId != 'folder' && parentNode.data.ieId.match(SE.reGUID)) {
                type = 'imap';
            }
        }
        if(type == 'imap') {
        	// make an IMAP folder
            post = "&newFolderName=" + name + "&mbox=" + parentNode.data.mbox + "&ieId=" + parentNode.data.ieId;
            AjaxObject.startRequest(callbackFolderRename, urlStandard + '&emailUIAction=saveNewFolder&folderType=imap' + post);
        } else if(type == 'sugar') {
            // make a Sugar folder
            if(SE.folders.isUniqueFolderName(name)) {
                post = "&parentId=" + parentNode.data.id + "&nodeLabel=" + name;
                AjaxObject.startRequest(callbackFolderSave, urlStandard + '&emailUIAction=saveNewFolder&folderType=sugar&' + post);
            } else {
                alert(app_strings.LBL_EMAIL_ERROR_DUPE_FOLDER_NAME);
                SE.folders.folderAdd();
                return;
            }
        } else {
            alert(app_strings.LBL_EMAIL_ERROR_CANNOT_FIND_NODE);
        }

        // hide add-folder diaglogue
        SE.e2overlay.hide();
    },

    /**
     * Removes either an IMAP folder or a Sugar Folder
     */
    folderDelete : function() {
        SE.contextMenus.frameFoldersContextMenu.hide();

        if(confirm(app_strings.LBL_EMAIL_FOLDERS_DELETE_CONFIRM)) {
            var post = '';
            var parentNode = SE.clickedFolderNode;

            if(parentNode != null && parentNode.data) {
                if(parentNode.data.mbox == 'INBOX' || parentNode.data.id == 'Home') {
                    SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_GENERAL_TITLE, app_strings.LBL_EMAIL_FOLDERS_CHANGE_HOME, 'alert');
                    return;
                }

                AjaxObject.target = 'frameFlex';

                if(parentNode.data.ieId != 'folder') {
                    // delete an IMAP folder
                    post = "&folderType=imap&mbox=" + parentNode.data.mbox + "&ieId=" + parentNode.data.ieId;
                } else {
                    // delete a sugar folder
                    post = "&folderType=sugar&folder_id=" + parentNode.data.id;
                }
                SUGAR.showMessageBox("Deleting folder", app_strings.LBL_EMAIL_ONE_MOMENT);
                AjaxObject.startRequest(callbackFolderDelete, urlStandard + '&emailUIAction=deleteFolder' + post);
            } else {
                alert(app_strings.LBL_EMAIL_ERROR_CANNOT_FIND_NODE);
            }
        }
    },

    /**
     * Rename folder form
     */
     //EXT111
    folderRename : function() {
        SE.contextMenus.frameFoldersContextMenu.hide();
        var node = SE.clickedFolderNode;

        if(node != null) {
            if(node.id == 'Home' || !node.data || node.data.mbox == 'INBOX') {
                SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_GENERAL_TITLE, app_strings.LBL_EMAIL_FOLDERS_CHANGE_HOME, 'alert');
                return;
            }

			SUGAR.showMessageBox(app_strings.LBL_EMAIL_FOLDERS_RENAME_DIALOG_TITLE + " - " + node.data.text,
                    app_strings.LBL_EMAIL_SETTINGS_NAME,
                    'prompt',
                    {fn:SE.folders.submitFolderRename, beforeShow: SE.folders.folderAddRegisterEnter, beforeHide: SE.folders.folderRemoveRegisterEnter});
        } else {
            alert(app_strings.LBL_EMAIL_FOLDERS_NO_VALID_NODE);
        }
    },

    /**
     * fills an Object with key-value pairs of available folders
     */
    getAvailableFoldersObject : function() {
        var ret = new Object();
        var tree = SE.tree.root;

        if(tree.children) {
            for(var i=0; i<tree.children.length; i++) {
                ret = this.getFolderFromChild(ret, tree.children[i], '', app_strings.LBL_EMAIL_SPACER_MAIL_SERVER);
            }
        } else {
            ret['none'] = app_strings.LBL_NONE;
        }

        return ret;
    },

    /**
     * Fills in key-value pairs for dependent dropdowns
     * @param object ret Associative array
     * @param object node TreeView node in focus
     * @param string currentPath Built up path thus far
     * @param string spacer Defined in app_strings, visual separator b/t Sugar and Remote folders
     */
    getFolderFromChild : function(ret, node, currentPath, spacer) {
        if(node.data != null && node.depth > 0) {
            /* handle visual separtors differentiating b/t mailserver and local */
            if(node.data.ieId && node.data.ieId == 'folder') {
                spacer = app_strings.LBL_EMAIL_SPACER_LOCAL_FOLDER;
            }

            if(!ret.spacer0) {
                ret['spacer0'] = spacer;
            } else if(ret.spacer0 != spacer) {
                ret['spacer1'] = spacer
            }

            var theLabel = node.data.label.replace(/<[^>]+[\w\/]+[^=>]*>/gi, '');
            var depthMarker = currentPath;
            var retIndex = SE.folders._createFolderId(node);
            ret[retIndex] = depthMarker + theLabel;
        }

        if(node.children != null) {
            if(theLabel) {
                currentPath += theLabel + "/";
            }

            for(var i=0; i<node.children.length; i++) {
                ret = this.getFolderFromChild(ret, node.children[i], currentPath, spacer);
            }
        }

        return ret;
    },

    /**
     * Wrapper to refresh folders tree
     */
    getFolders : function() {
        SE.accounts.rebuildFolderList();
    },

    /**
     * handles events around folder-rename input field changes
     * @param object YUI event object
     */
    handleEnter : function(e) {
        switch(e.browserEvent.type) {
            case 'click':
                e.preventDefault(); // click in text field
            break;

            case 'blur':
                SE.folders.submitFolderRename(e);
            break;

            case 'keypress':
                var kc = e.browserEvent.keyCode;
                switch(kc) {
                    case 13: // enter
                        e.preventDefault();
                        SE.folders.submitFolderRename(e);
                    break;

                    case 27: // esc
                        e.preventDefault(e);
                        SE.folders.cancelFolderRename(e);
                    break;
                }
            break;
        }
    },
    /**
    * Called when a node is clicked on in the folder tree
    * @param node, The node clicked on
    * @param e, The click event
    */
    handleClick : function(o) {
    	var node = o.node;
        //If the click was on a sugar folder
    	if (node.data.ieId == "folder") {
            SE.listView.populateListFrameSugarFolder(node, node.id, false);
        }
        else {
            SE.listView.populateListFrame(node, node.data.ieId, false);
        }
    },

    /**
    * Called when a node is right-clicked on in the folder tree
    */
    handleRightClick : function(e) {
    	YAHOO.util.Event.preventDefault(e);
		//Get the Tree Node
		var node = SUGAR.email2.tree.getNodeByElement(YAHOO.util.Event.getTarget(e));
		var menu = SUGAR.email2.contextMenus.frameFoldersContextMenu;

		//If the click was on a sugar folder
        SE.clickedFolderNode = node;
        var inbound = (node.data.ieId && node.data.ieId != 'folder');
		var disableNew = (inbound && (typeof(node.data.mbox) == 'undefined'));
		menu.getItem(0).cfg.setProperty("disabled", !inbound);
		menu.getItem(1).cfg.setProperty("disabled", !inbound);
		menu.getItem(2).cfg.setProperty("disabled", disableNew);
		menu.getItem(3).cfg.setProperty("disabled", false);
		menu.getItem(4).cfg.setProperty("disabled", false);
		menu.getItem(5).cfg.setProperty("disabled", false);
		menu.getItem(6).cfg.setProperty("disabled", true);
		//Group folder
		if (inbound && node.data.isGroup != null && node.data.isGroup == "true") {
			menu.getItem(0).cfg.setProperty("disabled", true);
			menu.getItem(1).cfg.setProperty("disabled", true);
			menu.getItem(2).cfg.setProperty("disabled", true);
			menu.getItem(3).cfg.setProperty("disabled", true);
			menu.getItem(4).cfg.setProperty("disabled", true);
		}
        if (node.data.protocol != null) {
        	menu.getItem(6).cfg.setProperty("disabled", false);
        }
		if (node.data.folder_type != null && (node.data.folder_type == "inbound" ||
				node.data.folder_type == "sent" || node.data.folder_type == "draft")) {
			//Sent or Draft folders
			menu.getItem(3).cfg.setProperty("disabled", true);
			menu.getItem(4).cfg.setProperty("disabled", true);
			menu.getItem(5).cfg.setProperty("disabled", true);
		}

		//For group with auto inbound, disable everything.
		if( (typeof(node.data.is_group) != 'undefined') && node.data.is_group == 'true')
		{
		    menu.getItem(0).cfg.setProperty("disabled", true);
    		menu.getItem(1).cfg.setProperty("disabled", true);
    		menu.getItem(2).cfg.setProperty("disabled", true);
    		menu.getItem(3).cfg.setProperty("disabled", true);
    		menu.getItem(4).cfg.setProperty("disabled", true);
    		menu.getItem(5).cfg.setProperty("disabled", true);
    		menu.getItem(6).cfg.setProperty("disabled", true);
		}

		menu.cfg.setProperty("xy", YAHOO.util.Event.getXY(e));
		menu.show();
    },

    /**
    * Called when a row is dropped on a node
    */
    handleDrop : function(rows, targetFolder) {
        var rowData = rows[0].getData();
        if (rowData.mbox != targetFolder.data.mbox) {
            var srcIeId = rowData.ieId;
            var srcFolder = rowData.mbox;
            var destIeId = targetFolder.data.ieId;
            var destFolder = targetFolder.data.mbox;
            var uids = [];
            for(var i=0; i<rows.length; i++) {
                uids[i] = rows[i].getData().uid;
            }
            SE.listView.moveEmails(srcIeId, srcFolder, destIeId, destFolder, uids, rows);
        }
    },

    /**
    * Called when something is dragged over a Folder Node
    */
    dragOver : function(dragObject) {
       return true;
    },

    /**
     * Determines if a folder name is unique to the folder tree
     * @param string name
     */
    isUniqueFolderName : function(name) {
        uniqueFolder = true;
        var root = SE.tree.getRoot();
        SE.util.cascadeNodes(SE.tree.getRoot(), function(name) {
            if (this.attributes && this.attributes.ieId == "folder") {
                if (this.attributes.text == name) {
                    uniqueFolder = false;
                    return false;
                }
            }
        }, null, [name]);
        return uniqueFolder;
    },

    /**
     * Makes async call to rename folder in focus
     * @param object e Event Object
     */
    submitFolderRename : function(newName) {
        if (trim(newName) == "") {
        	alert(mod_strings.LBL_ENTER_FOLDER_NAME);
        	return false;
        }
		newName = escape(newName);
        var node = SE.clickedFolderNode;
        var origName = node.data.text
        //Ignore no change
        if (newName == origName) {
            return true;
        }
        if(SE.folders.isUniqueFolderName(newName)) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_MENU_RENAMING_FOLDER, app_strings.LBL_EMAIL_ONE_MOMENT);
            if (node.data.ieId == "folder") {
                //Sugar Folder
                AjaxObject.startRequest(callbackFolderRename, urlStandard + "&emailUIAction=renameFolder&folderId=" + node.data.id + "&newFolderName=" + newName);
            }
            else {
                //IMAP folder or POP mailbox
                var nodePath = node.data.mbox.substring(0, node.data.mbox.lastIndexOf(".") + 1);
                AjaxObject.startRequest(callbackFolderRename, urlStandard + "&emailUIAction=renameFolder&ieId="
                    + node.data.ieId + "&oldFolderName=" + node.data.mbox + "&newFolderName=" + nodePath + newName);
            }
            return true;
        } else {
            alert(app_strings.LBL_EMAIL_ERROR_DUPE_FOLDER_NAME);
            return false;
        }
    },

    moveFolder : function(folderId, parentFolderId) {
        if (folderId != parentFolderId)
        {
        	AjaxObject.startRequest(callbackFolderRename, urlStandard + "&emailUIAction=moveFolder&folderId="
                    + folderId + "&newParentId=" + parentFolderId);
        }
    },

    /**
     * makes async call to do a full synchronization of all accounts
     */
    synchronizeAccounts : function() {
        if(confirm(app_strings.LBL_EMAIL_SETTINGS_FULL_SYNC_WARN)) {
            SUGAR.showMessageBoxModal(app_strings.LBL_EMAIL_SETTINGS_FULL_SYNC, app_strings.LBL_EMAIL_ONE_MOMENT + "<br>&nbsp;<br>" + app_strings.LBL_EMAIL_COFFEE_BREAK);
            AjaxObject.startRequest(callbackFullSync, urlStandard + '&emailUIAction=synchronizeEmail');
        }
    },

    /**
     * Updates user's folder subscriptsion (Sugar only)
     * @param object SELECT DOM object in focus
     * @param string type of Folder selection
     */
    updateSubscriptions : function() {
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_REBUILDING_FOLDERS, app_strings.LBL_EMAIL_ONE_MOMENT);

        var active = "";

        select = document.getElementById('userFolders');

        for(i=0; i<select.options.length; i++) {
            var opt = select.options[i];
             if(opt.selected && opt.value != "") {
                 if(active != "") {
                     active += "::";
                 }
                 active += opt.value;
             }
        }

        //Add the group folder ids.
        var group_folders = SUGAR.email2.folders.retrieveGroupFolderSubscriptions();
        for(i=0; i<group_folders.length; i++)
        {
            active += ("::" + group_folders[i]);
        }

        AjaxObject.startRequest(callbackFolderSubscriptions, urlStandard + '&emailUIAction=updateSubscriptions&subscriptions=' + active);
    },
    /**
     * Updates user's group folder subscriptsion (Sugar only)
     * @param ieID The group folder to add to the tree view
     */
    retrieveGroupFolderSubscriptions : function() {

        var a_rs = SE.accounts.inboundAccountsSettingsTable.getRecordSet().getRecords();
    	var activeGroupFolders = "";
    	var activeGroupIds = [];
    	for(i=0;i<a_rs.length;i++)
    	{
    		var t_record = a_rs[i];
    		var is_active = t_record.getData('is_active');
    		var isGroupFolder = t_record.getData('has_groupfolder');
    		var ieID = t_record.getData('id');
    		if( isGroupFolder )
    		{
    		    if(is_active)
    		      activeGroupIds.push(ieID);
    		}
        }

        return activeGroupIds;
    }

};

SE.folders.checkEmail2 = function() {
    AjaxObject.startRequest(callbackCheckEmail2, urlStandard + "&emailUIAction=checkEmail2");
}
////    END FOLDERS OBJECT
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////    SE.keys
/**
 * Keypress Event capture and processing
 */
SE.keys = {
    overall : function(e) {
        switch(e.charCode) {
            case 119: // "w"
                if(e.ctrlKey || e.altKey) {
                    var focusRegion = SE.innerLayout.regions.center;
                    if(focusRegion.activePanel.closable == true) {
                        focusRegion.remove(focusRegion.activePanel);
                    }
                }
            break;
        }
    }
};
////    END SE.keys
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////    SE.listView
/**
 * ListView object methods and attributes
 */
SE.listView = {
    currentRowId : -1,

    /**
     * Fills the ListView pane with detected messages.
     */
    populateListFrame : function(node, ieId, forceRefresh) {
        SE.innerLayout.selectTab(0);
		YAHOO.util.Connect.abort(AjaxObject.currentRequestObject, null, false);

        Dom.get('_blank').innerHTML = "";
        SE.grid.params['emailUIAction'] = 'getMessageList';
        SE.grid.params['mbox'] = node.data.mbox;
        SE.grid.params['ieId'] = ieId;
        forcePreview = true; // loads the preview pane with first item in grid
        SE.listView.refreshGrid();
    },

    /**
     * Like populateListFrame(), but specifically for SugarFolders since the API is radically different
     */
    populateListFrameSugarFolder : function(node, folderId, forceRefresh) {
        SE.innerLayout.selectTab(0);
        Dom.get('_blank').innerHTML = "";
        SE.grid.params['emailUIAction'] = 'getMessageListSugarFolders';
        SE.grid.params['ieId'] = node.data.id;
        SE.grid.params['mbox'] = node.data.origText ? node.data.origText : node.data.text;
        SE.listView.refreshGrid();
    },

    /**Mac
     * Sets sort order as user preference
     * @param
     */
    saveListViewSortOrder : function(sortBy, focusFolderPassed, ieIdPassed, ieNamePassed) {
        ieId = ieIdPassed;
        ieName = ieNamePassed;
        focusFolder = focusFolderPassed;

        SE.util.clearHiddenFieldValues('emailUIForm');
        var previousSort = document.getElementById('sortBy').value;

        document.getElementById('sortBy').value = sortBy;
        document.getElementById('emailUIAction').value = 'saveListViewSortOrder';
        document.getElementById('focusFolder').value = focusFolder;
        document.getElementById('ieId').value = ieId;

        if(sortBy == previousSort) {
            document.getElementById('reverse').value = '1';
        }

        var formObject = document.getElementById('emailUIForm');
        YAHOO.util.Connect.setForm(formObject);

        AjaxObject.startRequest(callbackListViewSortOrderChange, null);
    },


    /**
     * Enables click/arrow select of grid items which then populate the preview pane.
     */
    selectFirstRow : function() {
        SE.grid.selModel.selectFirstRow();
    },

    selectLastRow : function() {
        SE.grid.selModel.selectRow(SE.grid.dataSource.data.getCount() - 1);
    },

    setEmailListStyles : function() {
    	SE.listView.boldUnreadRows();
    	return;
        var ds = SE.grid.getStore();
        if (SE.grid.getSelections().length == 0) {
            document.getElementById('_blank').innerHTML = '';
        }

        var acctMbox = '';
        if(typeof(ds.baseParams.mbox) != 'undefined') {
            acctMbox = (ds.baseParams.acct) ? ds.baseParams.acct + " " + ds.baseParams.mbox : ds.baseParams.mbox;
            var cm = SE.grid.getColumnModel();
            if (ds.baseParams.mbox == mod_strings.LBL_LIST_FORM_SENT_TITLE) {
                cm.setColumnHeader(4, mod_strings.LBL_LIST_DATE_SENT);
                //SE.grid.render();
            } else if (cm.config[4].header != app_strings.LBL_EMAIL_DATE_SENT_BY_SENDER){
                cm.setColumnHeader(4, app_strings.LBL_EMAIL_DATE_SENT_BY_SENDER);
                //SE.grid.render();
            }
        }
        var total = (typeof(ds.totalLength) != "undefined") ? " (" + ds.totalLength +" " + app_strings.LBL_EMAIL_MESSAGES +") " : "";
        SE.listViewLayout.setTitle(acctMbox + total);// + toggleRead + manualFit);

        if (ds.reader.xmlData.getElementsByTagName('UnreadCount').length > 0){
            var unread = ds.reader.xmlData.getElementsByTagName('UnreadCount')[0].childNodes[0].data;
            var node = SE.folders.getNodeFromIeIdAndMailbox(ds.baseParams.ieId, ds.baseParams.mbox);
            if (node) node.data.unseen = unread;
        }
        SE.accounts.renderTree();


        // bug 15035 perhaps a heavy handed solution to stopping the loading spinner.
        if(forcePreview && ds.totalCount > 0) {
            SE.detailView.getEmailPreview();
            forcePreview = false;
        }
    },

    /**
     * Removes a row if found via its UID
     */
    removeRowByUid : function(uid) {
        uid = new String(uid);
        uids = uid.split(',');
        var dataTableRecords = SE.grid.getRecordSet().getRecords(0, SE.grid.getRecordSet().getLength());

        for(j=0; j<uids.length; j++) {
            var theUid = uids[j];
            for (k = 0 ; k < SE.grid.getRecordSet().getLength() ; k++) {
            	if (SE.grid.getRecordSet().getRecords()[k].getData().uid == theUid) {
            		SE.grid.deleteRow(SE.grid.getRecordSet().getRecords()[k]);
            	}
            } // for
        }
    },

    displaySelectedEmails : function(rows) {
        var dm = SE.grid.getDataModel();
        var uids = '';

        for(i=0; i<rows.length; i++) {
            var rowIndex = rows[i].rowIndex;
            var metadata = dm.data[rowIndex];

            if(uids != "") {
                uids += ",";
            }
            uids += metadata[5];

            // unbold unseen email
            this.unboldRow(rowIndex);
        }

        SE.detailView.populateDetailViewMultiple(uids, metadata[6], metadata[7], metadata[8], false);
    },

    /**
     * exception handler for data load failures
     */
    loadException : function(dataModel, ex, response) {
    },

    /**
     * Moves email(s) from a folder to another, from IMAP/POP3 to Sugar and vice-versa
     * @param string sourceIeId Email's source I-E id
     * @param string sourceFolder Email's current folder
     * @param destinationIeId Destination I-E id
     * @param destinationFolder Destination folder in format [root::IE::INBOX::etc]
     *
     * @param array emailUids Array of email's UIDs
     */
    moveEmails : function(sourceIeId, sourceFolder, destinationIeId, destinationFolder, emailUids, selectedRows) {
        if(destinationIeId != 'folder' && sourceIeId != destinationIeId) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_MOVE_TITLE, app_strings.LBL_EMAIL_ERROR_MOVE);
        } else {
            SUGAR.showMessageBox("Moving Email(s)", app_strings.LBL_EMAIL_ONE_MOMENT);
            // remove rows from visibility
            for(row in selectedRows) {
                //SE.grid.getStore().remove(row);
            }

            var baseUrl =    '&sourceIeId=' + sourceIeId +
                            '&sourceFolder=' + sourceFolder +
                            '&destinationIeId=' + destinationIeId +
                            '&destinationFolder=' + destinationFolder;
            var uids = '';

            for(i=0; i<emailUids.length; i++) {
                if(uids != '') {
                    uids += app_strings.LBL_EMAIL_DELIMITER;
                }
                uids += emailUids[i];
            }
            if (destinationIeId == 'folder' && sourceFolder != 'sugar::Emails') {
            	AjaxObject.startRequest(callbackImportOneEmail, urlStandard + '&emailUIAction=moveEmails&emailUids=' + uids + baseUrl);
            } else {
            	AjaxObject.startRequest(callbackMoveEmails, urlStandard + '&emailUIAction=moveEmails&emailUids=' + uids + baseUrl);
            }
        }
    },

    /**
     * Unbolds text in the grid view to denote read status
     */
    markRead : function(index, record) {
        // unbold unseen email
        var row = SE.grid.getRecord(record);
    	row.getData().seen = 1;
    	SE.grid.getTrEl(record).style.fontWeight = "normal";
    },

    /**
     * grid row output, bolding unread emails
     */
    boldUnreadRows : function() {
        // bold unread emails
    	var trEl = SE.grid.getFirstTrEl();
    	while(trEl != null) {
    		if(SE.grid.getRecord(trEl).getData().seen == "0")
    			trEl.style.fontWeight = "bold";
    		else
    			trEl.style.fontWeight = "";
    		trEl = SE.grid.getNextTrEl(trEl);
    	}
    },

    /**
     * Show preview for an email if 1 and only 1 is selected
     * ---- all references must be fully qual'd since this gets wrapped by the YUI event handler
     */
    handleRowSelect : function(e) {
        if(e.selectedRows.length == 1) {
            SE.detailView.getEmailPreview();
        }
    },

    handleDrop : function(e, dd, targetId, e2) {
        switch(targetId) {
            case 'htmleditordiv':
                var rows = SE.grid.getSelectedRows();
                if(rows.length > 0) {
                    SE.listView.displaySelectedEmails(rows);
                }
            break;

            default:
                var targetElId = new String(targetId);
                var targetIndex = targetElId.replace('ygtvlabelel',"");
                var targetNode = SE.tree.getNodeByIndex(targetIndex);
                var dm = SE.grid.getDataModel();
                var emailUids = new Array();
                var destinationIeId = targetNode.data.ieId;
                var destinationFolder = SE.util.generateMboxPath(targetNode.data.mbox);


                var rows = SE.grid.getSelectedRows();
                // iterate through dragged rows
                for(i=0; i<rows.length; i++) {
                    //var rowIndex = e.selModel.selectedRows[i].rowIndex;
                    var rowIndex = rows[i].rowIndex;
                    var dataModelRow = dm.data[rowIndex];
                    var sourceIeId = dataModelRow[7];
                    var sourceFolder = dataModelRow[6];
                    emailUids[i] = dataModelRow[5];
                }

                // event wrapped call - need FQ
                SUGAR.showMessageBox(app_strings.LBL_EMAIL_PERFORMING_TASK, app_strings.LBL_EMAIL_ONE_MOMENT);
                SE.listView.moveEmails(sourceIeId, sourceFolder, destinationIeId, destinationFolder, emailUids, e.selModel.selectedRows);
            break;
        }
    },

    /**
     * Hack-around to get double-click and single clicks to work on the grid
     * ---- all references must be fully qual'd since this gets wrapped by the YUI event handler
     */
    handleClick : function(o) {
        SUGAR.email2.grid.clearTextSelection();

        var el = SUGAR.email2.grid.getSelectedRows();

        //Load an email preview only if a single record is selected.  For multiple selections do nothing.
    	if ( el.length == 1)
    	{
    	   var rowId = el[0];
    	   SUGAR.email2.listView.currentRow = SUGAR.email2.grid.getRecord(rowId);
    	   SUGAR.email2.listView.currentRowIndex = rowId;
    	   clearTimeout(SUGAR.email2.detailView.previewTimer);
    	   SUGAR.email2.detailView.previewTimer = setTimeout("SUGAR.email2.detailView.getEmailPreview();", 500);
    	}
    },

    /**
     * Custom handler for double-click/enter
     * ---- all references must be fully qual'd since this gets wrapped by the YUI event handler
     */
    getEmail : function(e) {
        var rows = SE.grid.getSelectedRows();
    	var row = SE.grid.getRecord(rows[0]).getData();

        clearTimeout(SE.detailView.previewTimer);
        document.getElementById("_blank").innerHTML = "";

        if(row.type != "draft") {
            SE.detailView.populateDetailView(row.uid, row.mbox, row.ieId, 'true', SE.innerLayout);
        } else {
            // circumventing yui-ext tab generation, let callback handler build new view
            SE.util.clearHiddenFieldValues('emailUIForm');
            //function(uid, mbox, ieId, setRead, destination) {
            document.getElementById('emailUIAction').value = 'getSingleMessageFromSugar';
            document.getElementById('uid').value = row.uid; // uid;
            document.getElementById('mbox').value = row.mbox; // mbox;
            document.getElementById('ieId').value = row.ieId; // ieId;

            YAHOO.util.Connect.setForm(document.getElementById('emailUIForm'));
            AjaxObject.target = '_blank';
            AjaxObject.startRequest(AjaxObject.detailView.callback.emailDetail, null);
        }
    },

    /**
     * Retrieves a row if found via its UID
     * @param string
     * @return int
     */
    getRowIndexByUid : function(uid) {
        uid = new String(uid);
        uids = uid.split(',');

        for(j=0; j<uids.length; j++) {
            var theUid = uids[j];

            for(i=0; i<SE.grid.getStore().data.length; i++) {
                if(SE.grid.getStore().data[i].id == theUid) {
                    return i;
                }
            }
        }
    },

    /**
     * Returns the UID's of the seleted rows
     *
     */
     getUidsFromSelection : function() {
         var rows = SE.grid.getSelectedRows();
         var uids = [];
         /* iterate through available rows JIC a row is deleted - use first available */
         for(var i=0; i<rows.length; i++) {
        	 uids[i] = SE.grid.getRecord(rows[i]).getData().uid;
         }
         return uids;
     },

    refreshGrid : function() {
        SE.grid.getDataSource().sendRequest(
            SUGAR.util.paramsToUrl(SE.grid.params),
    		SE.grid.onDataReturnInitializeTable,
    		SE.grid
    	);
    }

};
////    END SE.listView
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////    SEARCH
SE.search = {
    /**
     * sends search criteria
     * @param reference element search field
     */
    search : function(el) {
        var searchCriteria = new String(el.value);

        if(searchCriteria == '') {
            alert(app_strings.LBL_EMAIL_ERROR_EMPTY);
            return false;
        }

        var safeCriteria = escape(searchCriteria);

        var accountListSearch = document.getElementById('accountListSearch');

        SE.grid.getStore().baseParams['emailUIAction'] = 'search';
        SE.grid.getStore().baseParams['mbox'] = app_strings.LBL_EMAIL_SEARCH_RESULTS_TITLE;
        SE.grid.getStore().baseParams['subject'] = safeCriteria;
        SE.grid.getStore().baseParams['ieId'] = accountListSearch.options[accountListSearch.selectedIndex].value;
        SE.grid.getStore().load({params:{start:0, limit:SE.userPrefs.emailSettings.showNumInList}});

    },

    /**
     * sends advanced search criteria
     */
    searchAdvanced : function() {
        var formObject = document.getElementById('advancedSearchForm');
        var search = false;

        //Set assigned user id to blank if name is not present.
        if (formObject.elements['assigned_user_name'].value == "")
            formObject.elements['assigned_user_id'].value = "";

        for(i=0; i<formObject.elements.length; i++) {
            if(formObject.elements[i].type != 'button' && formObject.elements[i].value != "") {
                search = true;
            }
            if(formObject.elements[i].type == 'text') {
                SE.grid.params[formObject.elements[i].name] = formObject.elements[i].value;
            }
            if(formObject.elements[i].type == 'hidden') {
                SE.grid.params[formObject.elements[i].name] = formObject.elements[i].value;
            }
             if(formObject.elements[i].type == 'select-one') {
                var el = formObject.elements[i];
                var v = el.options[el.selectedIndex].value;

                if(v != "")
                    SE.grid.params[el.name] = v;
                else
                {
                    //Clear previous search results if necessary
                    if(typeof( SE.grid.params[el.name]) != 'undefined')
                        delete SE.grid.params[el.name]
                }
             }
        }

        if (search)
        {
            if(! this.validateSearchFormInput() )
                return;

        	SE.grid.params['emailUIAction'] = 'searchAdvanced';
        	SE.grid.params['mbox'] = app_strings.LBL_EMAIL_SEARCH_RESULTS_TITLE;
        	var accountListSearch = document.getElementById('accountListSearch');
        	SE.listView.refreshGrid();
        } else {
            alert(app_strings.LBL_EMAIL_ERROR_EMPTY);
        }
    },

    /**
    *  Validates the search form inputs to ensure all parameters are valid
    *  @return bool
    */
    validateSearchFormInput: function()
    {
        addToValidate('advancedSearchForm', 'dateTo', 'date', false, app_strings.LBL_EMAIL_SEARCH_DATE_UNTIL);
        addToValidate('advancedSearchForm', 'dateFrom', 'date', false, app_strings.LBL_EMAIL_SEARCH_DATE_FROM);
        var dateCheck = check_form('advancedSearchForm');

        //If the parent type is selected ensure the user selected a parent_id.
        if( SE.composeLayout.isParentTypeAndNameValid('_search') && dateCheck)
            return true;
        else
            return false;
    },
    /**
    *   Toggles the advanced options, either hidding or showing the selection.
    */
    toggleAdvancedOptions: function()
    {
        var el = YAHOO.util.Dom.getElementsByClassName('toggleClass','tr', 'advancedSearchTable');

        for(var i=0;i<el.length;i++)
        {
            if(Dom.hasClass(el[i],"toggleClass yui-hidden" ))
                Dom.replaceClass(el[i],"toggleClass yui-hidden", "toggleClass visible-search-option" )
            else
                Dom.replaceClass(el[i],"toggleClass visible-search-option","toggleClass yui-hidden" )
        }
    },
    /**
     * clears adv search form fields
     */
    searchClearAdvanced : function() {
        var form = document.getElementById('advancedSearchForm');

        for(i=0; i<form.elements.length; i++) {
            if(form.elements[i].type != 'button') {
                form.elements[i].value = '';
            }
        }
    }
};
////    END SE.search
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
////    SE.settings
SE.settings = {
    /******************************************************************************
     * USER SIGNATURES calls stolen from Users module
     *****************************************************************************/
    createSignature : function(record, the_user_id) {
        var URL = "index.php?module=Users&action=PopupSignature&sugar_body_only=true";
        if(record != "") {
            URL += "&record="+record;
        }
        if(the_user_id != "") {
            URL += "&the_user_id="+the_user_id;
        }
        var windowName = 'email_signature';
        var windowFeatures = 'width=800,height=600,resizable=1,scrollbars=1';

        var win = window.open(URL, windowName, windowFeatures);
        if(win && win.focus) {
            // put the focus on the popup if the browser supports the focus() method
            win.focus();
        }
    },

    deleteSignature : function() {
        if(confirm(app_strings.LBL_EMAIL_CONFIRM_DELETE_SIGNATURE)) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_IE_DELETE_SIGNATURE, app_strings.LBL_EMAIL_ONE_MOMENT);
    		var singature_id = document.getElementById('signature_id').value;
        	AjaxObject.startRequest(callbackDeleteSignature, urlStandard + '&emailUIAction=deleteSignature&id=' + singature_id);
        } // if
    },

    saveOptionsGeneral :  function(displayMessage) {
        var formObject = document.getElementById('formEmailSettingsGeneral');
        if (!SUGAR.collection.prototype.validateTemSet('formEmailSettingsGeneral', 'team_name')) {
        	alert(mod_strings.LBL_EMAILS_NO_PRIMARY_TEAM_SPECIFIED);
        	return false;
        } // if
        YAHOO.util.Connect.setForm(formObject);
        SE.composeLayout.emailTemplates = null;

        AjaxObject.target = 'frameFlex';
        AjaxObject.startRequest(callbackSettings, urlStandard + '&emailUIAction=saveSettingsGeneral');

        if(displayMessage)
            alert(app_strings.LBL_EMAIL_SETTINGS_SAVED+"\n\n"+app_strings.LBL_EMAIL_SETTINGS_MUST_RELOAD);

        SE.settings.settingsDialog.hide();
    },

    /**
     * Shows settings container screen
     */
    showSettings : function() {
        if(!SE.settings.settingsDialog) {
    		var dlg = SE.settings.settingsDialog = new YAHOO.widget.Dialog("settingsDialog", {
            	modal:true,
            	visible:false,
            	fixedcenter:true,
            	draggable: false,
            	width:"800px",
				constraintoviewport: true
            });
			dlg.showEvent.subscribe( function (){
				var el = this.element;
				var viewH = YAHOO.util.Dom.getViewportHeight();
                if (this.header && el && viewH - 50 < el.clientHeight) {
                    var body = this.header.nextElementSibling;
					body.style.overflow = "auto";
                    body.style.height = (viewH - 50) + "px";
                }
            }, dlg);
        	dlg.setHeader(app_strings.LBL_EMAIL_SETTINGS);
        	dlg.setBody('<div id="settingsTabDiv"/>');
        	dlg.beforeRenderEvent.subscribe(function() {
        		var dd = new YAHOO.util.DDProxy(dlg.element);
        		dd.setHandleElId(dlg.header);
        		dd.on('endDragEvent', function() {
        			dlg.show();
        		});
        	}, dlg, true);
        	dlg.render();

        	var tp = SE.settings.settingsTabs = new YAHOO.widget.TabView("settingsTabDiv");
			var tabContent = Dom.get("tab_general");
        	tp.addTab(new YAHOO.widget.Tab({
				label: app_strings.LBL_EMAIL_SETTINGS_GENERAL,
				scroll : true,
				content :  tabContent.innerHTML,
				id : "generalSettings",
				active : true
			}));
        	tabContent.parentNode.removeChild(tabContent);
        	tabContent = Dom.get("tab_accounts");
        	var accountTab = new YAHOO.widget.Tab({
				label: app_strings.LBL_EMAIL_SETTINGS_ACCOUNTS,
				scroll : true,
				content : tabContent.innerHTML,
				id : "accountSettings"
			});
        	tp.addTab(accountTab);
        	tabContent.parentNode.removeChild(tabContent);

			tp.appendTo(dlg.body);
        }

        SE.settings.settingsDialog.show();
        SE.folders.lazyLoadSettings();
        SE.accounts.lazyLoad();
    },

    evalTeamData : function() {
    	if(isAdmin) {
	    	var teamSettingsFolderString = SUGAR.email2.composeLayout.teamsSettingsFolder;
	    	SUGAR.util.evalScript(teamSettingsFolderString);
    	}
	    SE.settings.displayedTeamData = true;
    },

    lazyLoadRules : function() {
        if(false) {
            AjaxObject.startRequest(callbackLoadRules, urlStandard + "&emailUIAction=loadRulesForSettings");
        }

    }
};
////    END SE.settings
///////////////////////////////////////////////////////////////////////////////
})();

/******************************************************************************
 * UTILITIES
 *****************************************************************************/
function removeHiddenNodes(nodes, grid) {
    var el;
	for(var i = nodes.length - 1; i > -1; i--) {
        el = grid ? grid.getTrEl(nodes[i]) : nodes[i];
    	if (YAHOO.util.Dom.hasClass(el, 'rowStylenone')) {
    		nodes.splice(i,1);
       }
    }
}

function strpad(val) {
    return (!isNaN(val) && val.toString().length==1)?"0"+val:val;
};

function refreshTodos() {
    SUGAR.email2.util.clearHiddenFieldValues('emailUIForm');
    AjaxObject.target = 'todo';
    AjaxObject.startRequest(callback, urlStandard + '&emailUIAction=refreshTodos');
};

/******************************************************************************
 * MUST STAY IN GLOBAL NAMESPACE
 *****************************************************************************/
function refresh_signature_list(signature_id, signature_name) {
    var field=document.getElementById('signature_id');
    var bfound=0;
    for (var i=0; i < field.options.length; i++) {
            if (field.options[i].value == signature_id) {
                if (field.options[i].selected==false) {
                    field.options[i].selected=true;
                }
                bfound=1;
            }
    }
    //add item to selection list.
    if (bfound == 0) {
        var newElement=document.createElement('option');
        newElement.text=signature_name;
        newElement.value=signature_id;
        field.options.add(newElement);
        newElement.selected=true;
    }

    //enable the edit button.
    var field1=document.getElementById('edit_sig');
    field1.style.visibility="inherit";
    var deleteButt = document.getElementById('delete_sig');
    deleteButt.style.visibility="inherit";
};

function setDefaultSigId(id) {
    var checkbox = document.getElementById("signature_default");
    var default_sig = document.getElementById("signatureDefault");

    if(checkbox.checked) {
        default_sig.value = id;
    } else {
        default_sig.value = "";
    }
};

function setSigEditButtonVisibility() {
    var field = document.getElementById('signature_id');
    var editButt = document.getElementById('edit_sig');
    var deleteButt = document.getElementById('delete_sig');
    if(field.value != '') {
        editButt.style.visibility = "inherit";
        deleteButt.style.visibility = "inherit";
    } else {
        editButt.style.visibility = "hidden";
        deleteButt.style.visibility = "hidden";
    }
}

/* End of File modules/Emails/javascript/EmailUI.js */

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
 (function() {
	var sw = YAHOO.SUGAR,
		Event = YAHOO.util.Event,
		Connect = YAHOO.util.Connect,
	    Dom = YAHOO.util.Dom
	    SE = SUGAR.email2;

///////////////////////////////////////////////////////////////////////////////
////    ADDRESS BOOK
SE.addressBook = {
    _contactCache : new Array(), // cache of contacts
    _dd : new Array(), // filtered list, same format as _contactCache
    _ddLists : new Array(), // list of Lists
    _dd_mlUsed : new Array(), // contacts in mailing list edit view column1
    _dd_mlAvailable : new Array(), // contacts in mailing list edit view column2
    clickBubble : true, // hack to get around onclick event bubbling
	relatedBeanId : '',
	relatedBeanType : '',
	idx : 0,

    focusMailingList : "", // mailing list that is currently being editted
    focusMailingListUpdated : null, // temp storage until committed via save for above

    itemSpacing : 'white-space:nowrap; padding:2px;',
    reGUID : SE.reGUID,



    /**
    *  YUI bug fix 2527707.  Causes nested datatable's in <tables> to cause 404 errors whens earching.
    */
    initFixForDatatableSort: function () {
        //Workaround for YUI bug 2527707: http://yuilibrary.com/projects/yui2/ticket/913efafad48ce433199f3e72e4847b18, should be removed when YUI 2.8+ is used
        YAHOO.widget.DataTable.prototype.getColumn = function(column) {
            var oColumn = this._oColumnSet.getColumn(column);

            if(!oColumn) {
                // Validate TD element
                var elCell = column.nodeName.toLowerCase() != "th" ? this.getTdEl(column) : false;
                if(elCell) {
                    oColumn = this._oColumnSet.getColumn(elCell.cellIndex);
                }
                // Validate TH element
                else {
                    elCell = this.getThEl(column);
                    if(elCell) {
                        // Find by TH el ID
                        var allColumns = this._oColumnSet.flat;
                        for(var i=0, len=allColumns.length; i<len; i++) {
                            if(allColumns[i].getThEl().id === elCell.id) {
                                oColumn = allColumns[i];
                            }
                        }
                    }
                }
            }

            return oColumn;
        };
    },

    cancelEdit : function() {
        if(this.editContactDialog)
            this.editContactDialog.hide();
        if(this.editMailingListDialog)
            this.editMailingListDialog.hide();
    },

    /**
     * Clears filter form
     */
    clear : function() {
        var t = document.getElementById('contactsFilter');
        t.value = '';
        this.filter(t);
    },

    /**
     * handle context-menu Compose-to call
     * @param string type 'contacts' or 'lists'
     */
    composeTo : function(type, waited) {
        var activePanel = SUGAR.email2.innerLayout.get("activeTab").get("id")
        if (activePanel.substring(0, 10) != "composeTab") {
            SE.composeLayout.c0_composeNewEmail();
            setTimeout("SE.addressBook.composeTo('" + type + "', true);");
	        SE.contextMenus.contactsContextMenu.hide();
			SE.contextMenus.mailingListContextMenu.hide();
            return;
        }
        var idx = activePanel.substring(10);
        var rows = [ ];
        var id = '';
        // determine if we have a selection to work with
        if(type == 'contacts') {
            var ids = SE.contactView.getSelectedRows();
            for (var i in ids) {
            	rows[i] = SE.contactView.getRecord(ids[i]);
            }
            removeHiddenNodes(rows, SE.contactView);
        }
		else if (type =='lists'){
			ids = SE.emailListsView.getSelectedRows();
			for (var i in ids) {
            	rows[i] = SE.emailListsView.getRecord(ids[i]);
            }
			removeHiddenNodes(rows, SE.emailListsView);
        }
		else { return; }

        if(rows.length > 0) {
            SE.composeLayout.handleDrop(
                (type == 'contacts') ? SE.contactView : SE.emailListsView,
                null, rows, 'addressTO' + idx );
        } else {
            alert(app_strings.LBL_EMAIL_MENU_MAKE_SELECTION);
        }
    },

    editContact : function() {
        SE.contextMenus.contactsContextMenu.hide();
        var element = SE.contactView.getSelectedNodes()[0];
        var elementId = "";
        if (element.className.indexOf('address-contact') > -1) {
            elementId = element.id;
        } else if (element.className.indexOf('address-exp-contact') > -1) {
            elementId = element.id.substring(2);
        }
    },


    /**
     * Filters contact entries based on user input
     */
    filter : function(inputEl) {
        var ret = new Object();
        var re = new RegExp(inputEl.value, "gi");

        for(var i in this._contactCache) {
            if(this._contactCache[i].name.match(re)) {
                ret[i] = this._contactCache[i];
            }
        }

        this.buildContactList(ret);
    },

    fullForm : function(id, module) {
        document.location = "index.php?return_module=Emails&return_action=index&module=" + module + "&action=EditView&record=" + id;
    },

    /**
     * returns a formatted email address from the addressBook cache
     */
    getFormattedAddress : function(id) {
        var o = this._contactCache[id];
        var primaryEmail = '';

        for(var i=0; i<o.email.length; i++) {
            var currentEmail = o.email[i].email_address;

            if(o.email[i].primary_address == 1) {
                primaryEmail = o.email[i].email_address;
            }
        }

        var finalEmail = (primaryEmail == "") ? currentEmail : primaryEmail;
        var name = new String(o.name);
        var finalName = name.replace(/(<([^>]+)>)/ig, "").replace(/&#039;/gi,'\'');
        var ret = finalName + " <" + finalEmail.replace(/&#039;/gi,'\'') + ">";

        return ret;
    },

    /**
     * Sets up async call to query for matching contacts, users, etc.
     */
    searchContacts : function() {
        var fn = document.getElementById('input_searchField').value;
        var pe = document.getElementById('input_searchPerson').value;

        var rb = document.getElementById('hasRelatedBean').checked;
        if (rb) {
			var idx = this.idx;
        	var relatedBeanId = document.getElementById('data_parent_id' + idx).value;
        	var relatedBeanType = document.getElementById('data_parent_type' + idx).value;
        	this.addressBookDataModel.params['related_bean_id'] = relatedBeanId;
        	this.addressBookDataModel.params['related_bean_type'] = relatedBeanType;
        } else {
        	this.addressBookDataModel.params['related_bean_id'] = '';
        }

        this.addressBookDataModel.params['search_field'] = fn;
        this.addressBookDataModel.params['person'] = pe;
        this.addressBookDataModel.params['emailUIAction'] = 'getAddressSearchResults';
        this.grid._oDataSource = this.addressBookDataModel;
        this.grid.getDataSource().sendRequest(SUGAR.util.paramsToUrl(this.addressBookDataModel.params),  this.grid.onDataReturnInitializeTable, this.grid);
    },

    /**
     * Clear Search Crieteria For Addressbook
     */
    clearAddressBookSearch : function() {
        document.getElementById('input_searchField').value = "";
        document.getElementById('input_searchPerson').selectedIndex = 0;
    },

    /**
     * Opens modal select window to add contacts to addressbook
     */
    selectContactsDialogue : function(destId) {
        if(!this.contactsDialogue) {
        	var dlg = this.contactsDialogue = new YAHOO.widget.Dialog("contactsDialogue", {
            	modal:true,
            	visible:false,
            	draggable: false,
            	constraintoviewport: true,
                width   : 980,
                buttons : [{text: app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD, isDefault: true, handler: this.populateEmailAddressFieldsFromResultTable},
                           {text: app_strings.LBL_EMAIL_ADDRESS_BOOK_CLEAR, isDefault: true, handler: this.clearAllEmailAddressFieldsFromResultTable} ]
            });
        	dlg.setHeader(app_strings.LBL_EMAIL_ADDRESS_BOOK_SELECT_TITLE);

        	var body = SUGAR.util.getAndRemove("contactsDialogueHTML");
        	dlg.setBody(body.innerHTML);
        	dlg.renderEvent.subscribe(function() {
            	var iev = YAHOO.util.Dom.get("contactsDialogueBody");
            	if (iev && !SUGAR.isIE) {
            		this.body.style.width = "950px";
            	}
            }, dlg);


        	dlg.beforeRenderEvent.subscribe(function() {
        		var dd = new YAHOO.util.DDProxy(dlg.element);
        		dd.setHandleElId(dlg.header);
        		dd.on('endDragEvent', function() {
        			dlg.show();
        		});
        	}, dlg, true);
        	dlg.render();

        	var tp = new YAHOO.widget.TabView("contactsSearchTabs");

        	var tabContent = SUGAR.util.getAndRemove("searchForm");
        	tp.addTab(new YAHOO.widget.Tab({
				label: app_strings.LBL_EMAIL_ADDRESS_BOOK_TITLE,
				scroll : true,
				content : tabContent.innerHTML,
				id : "addressSearchTab",
				active : true
			}));

        	var addListenerFields = ['input_searchPerson','input_searchField' ]
        	YAHOO.util.Event.addListener(addListenerFields,"keydown", function(e){
        		if (e.keyCode == 13) {
        			YAHOO.util.Event.stopEvent(e);
        			SUGAR.email2.addressBook.searchContacts();
        		}
        	});

        	this.contactsDialogue.render();
        	dlg.center();
        }
        //Quick Compose does not have an innerLayout component and will always be referenced with ix 0.
        if (typeof(SUGAR.email2.innerLayout) == 'undefined')
            var idx = 0;
        else
        {
            var activePanel = SUGAR.email2.innerLayout.get("activeTab").get("id");
            var idx = activePanel.substring(10);
        }
        SE.addressBook.idx = idx;

		var relatedBeanId;
        if ((hasRelatedBeanId = document.getElementById('data_parent_id' + idx).value) != '') {
        	document.getElementById('relatedBeanColumn').style.display = '';
        	var relatedBeanName = document.getElementById('data_parent_name' + idx).value;
		   	var relatedBeanType = document.getElementById('data_parent_type' + idx).value;
		   	relatedBeanId = document.getElementById('data_parent_id' + idx).value;
		   	document.getElementById('relatedBeanInfo').innerHTML = ' ' + relatedBeanType + ' <b>' + relatedBeanName + '</b>';
		   	SE.addressBook.relatedBeanType = relatedBeanType;
	    } else {
	    	document.getElementById('relatedBeanColumn').style.display = 'none';
	    	document.getElementById('hasRelatedBean').checked = false;
	    }

	    if (!SE.addressBook.grid)
	    {
	    	if (hasRelatedBeanId) {
	    		document.getElementById('hasRelatedBean').checked = true;
	    	}
	        AddressSearchGridInit();
			SE.addressBook.relatedBeanId = relatedBeanId;
	    }
	    else
	    {
	    	if (typeof(relatedBeanId) != 'undefined' && relatedBeanId != SE.addressBook.relatedBeanId)
	    	{
	    		SE.addressBook.relatedBeanId = relatedBeanId;
	    		document.getElementById('hasRelatedBean').checked = true;
	    	}
	    	if (document.getElementById('hasRelatedBean').checked == true)
	    	{
	    		SE.addressBook.addressBookDataModel.params['related_bean_id'] = relatedBeanId;
	       		SE.addressBook.addressBookDataModel.params['related_bean_type'] = relatedBeanType;
	    	} else {
	    		SE.addressBook.addressBookDataModel.params['related_bean_id'] = '';
	       		SE.addressBook.addressBookDataModel.params['related_bean_type'] = '';
	    	}
	       	SE.addressBook.addressBookDataModel.params['search_field'] = document.getElementById('input_searchField').value;;
			SE.addressBook.addressBookDataModel.params['person'] = document.getElementById('input_searchPerson').value;
    		SE.addressBook.grid.getDataSource().sendRequest(SUGAR.util.paramsToUrl(SE.addressBook.addressBookDataModel.params),  SE.addressBook.grid.onDataReturnInitializeTable, SE.addressBook.grid);
	    }

	    //Remove any lingering rows in the result set table if the module was closed.
	    SE.addressBook.gridResults.deleteRows(0, SUGAR.email2.addressBook.gridResults.getRecordSet().getLength());
	    //Repopulate
	    SE.addressBook.populateResulstTableEmailAddresses();

        this.contactsDialogue.show();
    },
    /**
    *  Clear all email addresses from result table.
    *
    */
    clearAllEmailAddressFieldsFromResultTable: function () {
        SUGAR.email2.addressBook.gridResults.deleteRows(0, SUGAR.email2.addressBook.gridResults.getRecordSet().getLength());
        //Unhighlight any rows currently selected if the emails were cleared.
        SUGAR.email2.addressBook.grid.toggleSelectAll(false);
        SUGAR.email2.addressBook.grid.reSelectRowsOnRender();
    },
    /**
    *  Take all email address listed in the compose tab To|Cc|Bcc fields and re-populates the
    *  results table.  This function is called when the address book is displayed.
    */
    populateResulstTableEmailAddresses: function () {

        var idx = SE.addressBook.idx;
        var emailFields = ['to','cc','bcc'];

        for(var k=0;k<emailFields.length;k++)
        {
            var elKey = 'address' + emailFields[k].toUpperCase() + idx;
            var allEmails = document.getElementById(elKey).value;
            if(allEmails == '')
                continue;

            var formatedEmails = SE.composeLayout._getEmailArrayFromString(allEmails);

    		for (var i=0; i<formatedEmails.length; i++)
    		{
    		    var t_name = formatedEmails[i].name;
    		    var t_emailAddr = formatedEmails[i].email_address;
    		    var displayEmail = t_name + ' <' + t_emailAddr + '>';
    		    if(t_name == '')
    		        t_name = displayEmail = t_emailAddr;

    		    var addressType = SE.addressBook.translateAddresType(emailFields[k],true);
                SUGAR.email2.addressBook.gridResults.addRow({'type':addressType,'name':t_name,'email_address': t_emailAddr,
                    'display_email_address': displayEmail,'bean_id': -1,'idx' : SE.addressBook.idx});
    		}
        }
    },

    /**
    * Checks all entries in the result table against a particular email address, returning true
    * if the email address is found, false otherwise.
    */
    doesEmailAdddressExistInResultTable: function(emailAddress)
    {
        if(trim(emailAddress) == '')
            return false;

        var emailAddressFound = false;
        var contacts = SE.addressBook.gridResults.getRecordSet().getRecords();
        for (var i=0; i < contacts.length; i++)
        {
            var data = SE.addressBook.gridResults.getRecord(contacts[i]).getData();
            //If we are adding to cc or bcc fields, make them visible.
            if(data.email_address == emailAddress)
            {
                emailAddressFound = true;
                break;
            }
        }

        return emailAddressFound;
    },
    /**
    *  Takes all email addresses that the users wishes to add from the address book and populates the To
    *  fields on the compose tab.
    */
    populateEmailAddressFieldsFromResultTable: function()
    {
        //Clear the fields first, all email addresses are stored in the address book
        var idx = SE.addressBook.idx;
        var emailFields = ['to','cc','bcc'];
        for(var k=0;k<emailFields.length;k++)
        {
            var elKey = 'address' + emailFields[k].toUpperCase() + idx;
            document.getElementById(elKey).value = "";
        }

        var contacts = SE.addressBook.gridResults.getRecordSet().getRecords();
        for (var i=0; i < contacts.length; i++)
        {
            var data = SE.addressBook.gridResults.getRecord(contacts[i]).getData();

            var addressTypeKey = SE.addressBook.translateAddresType(data.type,false);
            //If we are adding to cc or bcc fields, make them visible.
            if(addressTypeKey =='cc' || addressTypeKey =='bcc')
                SE.composeLayout.showHiddenAddress(addressTypeKey,data.idx);
            //Construct the target id
            var target_id = 'address' + addressTypeKey.toUpperCase() + data.idx

            var target = document.getElementById(target_id);
            target.value = SE.addressBook.smartAddEmailAddressToComposeField(target.value, data.display_email_address);
        }

        //Delete all rows from the result set table
        SUGAR.email2.addressBook.gridResults.deleteRows(0, SUGAR.email2.addressBook.gridResults.getRecordSet().getLength());

        //Hide the dialogue
        SE.addressBook.contactsDialogue.hide()
    },
    /**
    *  Insert contacts into the result table.
    */
    insertContactToResultTable : function(event,address_type) {

        var contactsDialogue = SE.addressBook.contactsDialogue;
        var contacts = SE.addressBook.grid.getSelectedRows();

        var rows = SUGAR.email2.addressBook.grid.getRecordSet().getRecords();
        for (var i = 0; i < rows.length; i++)
        {
			if (typeof(rows[i]) != "undefined" && rows[i].getData().checked )
			{
			    var recId = SE.addressBook.grid.getRecord(rows[i]).getId();
                SE.addressBook.insertContactRowToResultTable(recId,address_type);
                SUGAR.email2.addressBook.grid.selectRow(rows[i]);
                rows[i].setData("selected",true);
			}
        }
        var checkBoxes = SUGAR.email2.addressBook.grid.get("element").getElementsByTagName('input');
        for (var i = 0; i < checkBoxes.length; i++) {
            checkBoxes[i].checked = false;
        }
    },
    /**
    *
    */
    insertContactRowToResultTable : function(rowId, addressType) {
        var data = SE.addressBook.grid.getRecord(rowId).getData();
        if(SE.addressBook.doesGridResultsEntryExist(data.email) )
                return;
        var name = data.name.replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
        var ea = name + ' <' + data.email.replace(/&#039;/gi,'\'') + '>';
        if(addressType == null)
            addressType = app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_TO.replace(/:$/,''); //Default to To when using the plus icon.
        SUGAR.email2.addressBook.gridResults.addRow({'type':addressType,'name':name,'email_address': data.email,'display_email_address': ea,'bean_id': data.bean_id,'idx' : SE.addressBook.idx});
    },
    /**
    * Remove a row from the gridsResult table.
    */
    removeRowFromGridResults : function(rowId,emailAddress)
    {
        var contacts = SE.addressBook.gridResults.getRecordSet().getRecords();
        for (var i=0; i < contacts.length; i++)
        {
            var rec = SE.addressBook.gridResults.getRecord(contacts[i]);
            var data = rec.getData();
            if(data.email_address == emailAddress)
            {
                SUGAR.email2.addressBook.gridResults.deleteRow(rec.getId());
                break;
            }
        }

       SUGAR.email2.addressBook.toggleSearchRowIcon(rowId,true);
    },
    /**
    * Translates between the addressType To|Cc|Bcc labels/keys.
    */
    translateAddresType: function(addressType,fromKey)
    {
        var displayTo = app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_TO.replace(/:$/,'');
        var displayCc = app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_CC.replace(/:$/,'');
        var displayBcc = app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_BCC.replace(/:$/,'');
        var mappingObject = {};

        if(fromKey)
            mappingObject = {'to':displayTo, 'cc':displayCc, 'bcc':displayBcc};
        else
        {
            mappingObject[displayTo] = 'to'; //Cant use object literal with variable variable.
            mappingObject[displayCc] = 'cc';
            mappingObject[displayBcc] = 'bcc';
        }

        return typeof(mappingObject[addressType]) != 'undefined' ? mappingObject[addressType] : '';

    },
    /**
    *
    */
    toggleSearchRowIcon : function(rowId,show)
    {
        if(show)
        {
            var idToShow = rowId + '_add_img';
            var idToHide = rowId + '_rm_img';
        }
        else
        {
            var idToShow = rowId + '_rm_img';
            var idToHide = rowId + '_add_img';
        }


        Dom.addClass(idToHide, "yui-hidden");
        Dom.removeClass(idToShow, "yui-hidden");
    },
    /**
    * Determine if an entry has already been added to the grid results table to prevent duplicates.
    */
    doesGridResultsEntryExist: function(emailAddrs)
    {

        var contactExists = false;
        var contacts = SE.addressBook.gridResults.getRecordSet().getRecords();
        for (var i=0; i < contacts.length; i++)
        {
            var data = SE.addressBook.gridResults.getRecord(contacts[i]).getData();
            if(data.email_address == emailAddrs)
            {
                contactExists = true;
                break;
            }
        }
        return contactExists;
    },

    /**
     * adds an email address to a string, but first checks if it exists
     * @param string concat The string we are appending email addresses to
     * @param string addr Email address to add
     * @return string
     */
    smartAddEmailAddressToComposeField : function(concat, addr) {
        var re = new RegExp(addr);

        if(!concat.match(re)) {
            if(concat != "") {
                concat += "; " + addr;
            } else {
                concat = addr;
            }
        }

        return concat;
    }
};
////    END ADDRESS BOOK
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
////    AUTOCOMPLETE
/**
 * Auto-complete object
 */
SE.autoComplete = {
    config : {
        delimChar : [";", ","],
        useShadow :    false,
        useIFrame : false,
        typeAhead : true,
        prehighlightClassName : "yui-ac-prehighlight",
        queryDelay : 0
    },
    instances : new Array(),

    /**
     * Parses an addressBook entry looking for primary address.  If not found, it will return the last found address.
     * @param object Contact from AddressBook
     * @return string
     */
    getPrimaryAddress : function(contact) {
        var address = app_strings.LBL_EMAIL_ADDRESS_BOOK_NOT_FOUND;

        for(var eIndex in contact.email) {
            address = contact.email[eIndex].email_address;
            if(contact.email[eIndex].primary_address == 1) {
                return contact.email[eIndex].email_address;
            }
        }
        return address;
    },


    /**
     * initializes autocomplete widgets for a given compose view
     * @param int idx
     */
    init : function(idx) {
        var ds = new YAHOO.widget.DS_JSArray(this.returnDataSource(SE.addressBook._contactCache), {
            "queryMatchContains" : false,
            "queryMatchSubset" : true
        });

        this.instances[idx] = {
            to : null,
            cc : null,
            bcc : null
        };


        // instantiate the autoComplete widgets
        this.instances[idx]['to'] = new YAHOO.widget.AutoComplete('addressTO'+idx, "addressToAC"+idx, ds, this.config);
        this.instances[idx]['cc'] = new YAHOO.widget.AutoComplete('addressCC'+idx, "addressCcAC"+idx, ds, this.config);
        this.instances[idx]['bcc'] = new YAHOO.widget.AutoComplete('addressBCC'+idx, "addressBccAC"+idx, ds, this.config);

        // enable hiding of interfering textareas
        this.instances[idx]['to'].containerExpandEvent.subscribe(SE.autoComplete.toggleTextareaHide);
        this.instances[idx]['cc'].containerExpandEvent.subscribe(SE.autoComplete.toggleTextareaHide);
        this.instances[idx]['bcc'].containerExpandEvent.subscribe(SE.autoComplete.toggleTextareaHide);

        // enable reshowing of hidden textareas
        this.instances[idx]['to'].containerCollapseEvent.subscribe(SE.autoComplete.toggleTextareaShow);
        this.instances[idx]['cc'].containerCollapseEvent.subscribe(SE.autoComplete.toggleTextareaShow);
        this.instances[idx]['bcc'].containerCollapseEvent.subscribe(SE.autoComplete.toggleTextareaShow);

        // enable refreshes of contact lists
        this.instances[idx]['to'].textboxFocusEvent.subscribe(SE.autoComplete.refreshDataSource);
        this.instances[idx]['cc'].textboxFocusEvent.subscribe(SE.autoComplete.refreshDataSource);
        this.instances[idx]['bcc'].textboxFocusEvent.subscribe(SE.autoComplete.refreshDataSource);
    },

    refreshDataSource : function(sType, aArgs) {
        var textBoxId = aArgs[0].getInputEl().id; // "addressTo0"
        var idx;
        var refresh = SE.autoComplete.returnDataSource(SE.addressBook._contactCache);

        if(textBoxId.indexOf("addressTO") > -1 || textBoxId.indexOf("addressCC") > -1) {
            idx = textBoxId.substr(9);
        } else {
            idx = textBoxId.substr(10);
        }

        SE.autoComplete.instances[idx]['to'].dataSource.data = refresh;
        SE.autoComplete.instances[idx]['cc'].dataSource.data = refresh;
        SE.autoComplete.instances[idx]['bcc'].dataSource.data = refresh;
    },

    /**
     * Parses AddressBook entries to return an appropriate DataSource array for YUI.autoComplete
     */
    returnDataSource : function(contacts) {
        var ret = new Array();
        for(var id in contacts) {
            if (contacts[id].name) {
	            var primary = this.getPrimaryAddress(contacts[id]);

	            ret[ret.length] = contacts[id].name.replace(/<[\/]*b>/gi, '') + " <" + primary + ">";
	            //ret[ret.length] = contacts[id].name + " <" + primary + ">";

	            for(var emailIndex in contacts[id].email) {
	                ret[ret.length] = contacts[id].email[emailIndex].email_address;
	            }
            }
        }

        return ret;
    },

    /**
     * Hides address textareas to prevent autocomplete dropdown from being obscured
     */
    toggleTextareaHide : function(sType, aArgs) {
        var textBoxId = aArgs[0]._oTextbox.id; // "addressTo0"
        var type = "";
        var idx = -1;

        if(textBoxId.indexOf("addressTO") > -1) {
            type = "to";
        } else if(textBoxId.indexOf("addressCC") > -1) {
            type = "cc";
        }
        idx = textBoxId.substr(9);

        // follow through if not BCC
        if(type != "") {
            var cc = document.getElementById("addressCC" + idx);
            var bcc = document.getElementById("addressBCC" + idx);

            switch(type) {
                case "to":
                    cc.style.visibility = 'hidden';
                case "cc":
                    bcc.style.visibility = 'hidden';
                break;
            }
        }
    },

    /**
     * Redisplays the textareas after an address is committed.
     */
    toggleTextareaShow : function(sType, aArgs) {
        var textBoxId = aArgs[0]._oTextbox.id; // "addressTo0"
        var type = "";
        var idx = -1;

        if(textBoxId.indexOf("addressTO") > -1) {
            type = "to";
        } else if(textBoxId.indexOf("addressCC") > -1) {
            type = "cc";
        }
        idx = textBoxId.substr(9);

        // follow through if not BCC
        if(type != "") {
            document.getElementById("addressCC" + idx).style.visibility = 'visible';
            document.getElementById("addressBCC" + idx).style.visibility = 'visible';
        }
    }
};

////    END AUTOCOMPLETE
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////    COMPOSE & SEND
/**
 * expands the options sidebar
 */
SE.composeLayout = {
    currentInstanceId : 0,
    ccHidden : true,
    bccHidden : true,
    outboundAccountErrors : null,
    loadedTinyInstances : {}, //Tracks which tinyMCE editors have initalized with html content.
    subjectMaxlen : 255,

    showAddressDetails : function(e) {
    	var linkElement = document.getElementById("More"+e.id);
    	var spanElement = document.getElementById("Detail"+e.id);
    	var emailAddressList = e.value;
    	if(e.value.length > 96)
    	{
        	var resultArray = SE.composeLayout._getEmailArrayFromString(emailAddressList);
            var displayArray = [];
    		for (var i=0; i<resultArray.length; i++)
    		{
    		    var t_name = resultArray[i].name;
    		    var t_emailAddr = resultArray[i].email_address;
    		    if(t_name == '')
    		       displayArray.push('<br/>&lt;' + t_emailAddr + '&gt;');
    		    else
    		       displayArray.push(t_name + '<br/>&lt;' + t_emailAddr + '&gt;');
    		}

            var result = displayArray.join('<br/>');
        	// Display
            linkElement.style.display = "inline";
            linkElement.style.height="10px";
            linkElement.style.overflow="visible";
            spanElement.innerHTML = result;
    	}
    	else
    		linkElement.style.display = "none";

	},

   /**
    *  Given a string of email address, return an array containing the name portion (if available)
    *  and email portion.
    */
    _getEmailArrayFromString : function (emailAddressList){

        var reg = /@.*?;/g;
        while ((results = reg.exec(emailAddressList)) != null)
        {
            orignial = results[0];
            parsedResult = results[0].replace(';', ':::::');
            emailAddressList = emailAddressList.replace (orignial, parsedResult);
        }

        reg = /@.*?,/g;
        while ((results = reg.exec(emailAddressList)) != null)
        {
            orignial = results[0];
            parsedResult = results[0].replace(',', ':::::');
            emailAddressList = emailAddressList.replace (orignial, parsedResult);
        }
        //Administrator <johndoe@som.com>  ;1@somwhe.com;2@somwherecomplex.com,3@somwherecomplex.com;4@somwherecomplex.com,5@somwherecomplex.com,
        var emailArr = emailAddressList.split(":::::");
        var resultsArray = [];
        var newArr = [];
        for (var i=0; i<emailArr.length; i++)
        {
            var rposition = emailArr[i].indexOf('<');
            var lposition = emailArr[i].indexOf('>');

            if(trim(emailArr[i]) != '')
            {
                if(rposition != -1 && lposition != -1)
                {
                    var t_name = emailArr[i].substr(0, rposition-1);
                    var t_emailAddr = emailArr[i].substr(rposition+1, (lposition-1 - rposition) );
                    resultsArray.push({'name':t_name, 'email_address': t_emailAddr});
                }
                else
                {
                    resultsArray.push({'name':'', 'email_address': emailArr[i]});
                }
            }
        }
        return resultsArray;
    },
    ///////////////////////////////////////////////////////////////////////////
    ////    COMPOSE FLOW
    /**
     * Prepare bucket DIV and yui-ext tab panels
     */
    _0_yui : function() {
        var idx = this.currentInstanceId;

        var composeTab = new YAHOO.SUGAR.ClosableTab({
        		label: mod_strings.LNK_NEW_SEND_EMAIL,
				scroll : true,
				content : "<div id='htmleditordiv" + idx + "'/>",
				id : "composeTab" + idx,
				closeMsg: app_strings.LBL_EMAIL_CONFIRM_CLOSE,
				active : true
        }, SE.innerLayout);
        SE.innerLayout.addTab(composeTab);

        // get template engine with template
        if (!SE.composeLayout.composeTemplate) {
        	SE.composeLayout.composeTemplate = new YAHOO.SUGAR.Template(SE.templates['compose']);
        }

        // create Tab inner layout
        var composePanel =  this.getComposeLayout();
        composePanel.getUnitByPosition("right").collapse();
        composePanel.autoSize();

    },
	/**
     * Generate the quick compose layout
	 * @method getQuickComposeLayout
	 * @param {Pannel} parentPanel Parent pannel
	 * @param {Object} o Options
	 * @return {} none
	 **/
    getQuickComposeLayout : function (parentPanel,o) {
    	 var idx = SE.composeLayout.currentInstanceId;

    	 //Before rendering the parent pannel we need to initalize the grid layout
    	 parentPanel.beforeRenderEvent.subscribe(function() {

    	 	YAHOO.util.Event.onAvailable('htmleditordiv' + idx, function() {
    	 		SE.composeLayout._createComposeLayout(idx);
    	 		SE.composeLayout[idx].set('height', 350);
	        	SE.composeLayout[idx].render();
           });
        });

     	 //Wait until the Compose Layout has rendered, then add the
     	 //options tab and perform the tiny initialization.
         parentPanel.renderEvent.subscribe(function() {

    	 	YAHOO.util.Event.onAvailable('htmleditordiv' + idx, function() {
     		SE.composeLayout._initComposeOptionTabs(idx);
     		SE.composeLayout[idx].getUnitByPosition("right").collapse();
     		//Initialize tinyMCE
            SE.composeLayout._1_tiny(false);

     		//Init templates and address book
     		SE.composeLayout._2_final();

            SE.composeLayout.quickCreateComposePackage(o);

    	 	});
    	 });

	    //Check if we have the div override for the shortcut bar
        if(typeof o.menu_id != 'undefined') {
		   parentPanel.render(o.menu_id);
	    } else {
		   parentPanel.render(document.body);
	    }

        return SE.composeLayout[idx];
    },
    /**
     * Fill in all fields into the quick compose layout.
	 * @method quickCreateComposePackage
	 * @param {Object} o Options
	 * @return {} none
	 **/
    quickCreateComposePackage: function(o)
    {
        //If we have a compose package fill in defaults.
        if (typeof(o.composePackage) != 'undefined')
        {
            composePackage = o.composePackage; //Set the compose data object
            //Hijack this method called by composePackage as it's not need for quick creates.
            SE.composeLayout.c0_composeNewEmail = function(){};
            SE.composeLayout.composePackage(); //Fill in defaults.
        }
    },
    getComposeLayout : function() {
        var idx = SE.composeLayout.currentInstanceId;

       	this._createComposeLayout(idx);
        SE.composeLayout[idx].render();
        this._initComposeOptionTabs(idx);

        return SE.composeLayout[idx];
        },

        /**
        *	Create the layout manager for the compose window.
        */
        _createComposeLayout : function(idx)
        {
        	SE.composeLayout[idx] = new YAHOO.widget.Layout('htmleditordiv' + idx, {
        	parent: SE.complexLayout,
        	border:true,
            hideOnLayout: true,
            height: 400,
			units: [{
					position: "center",
	                animate: false,
	                scroll: false,
	                split:true,
	                body:
	                	SE.composeLayout.composeTemplate.exec({
	                        'app_strings':app_strings,
	                        'mod_strings':mod_strings,
	                        'linkbeans_options' : linkBeans,
	                        'idx' : SE.composeLayout.currentInstanceId
	                	})
	            },{
	            	position: "right",
				    scroll:true,
				    collapse: true,
				    collapsed: true,
				    resize: true,
				    border:true,
				    animate: false,
				    width:'230',
				    body: "<div class='composeRightTabs' id='composeRightTabs" + idx + "'/>",
				    titlebar: true,
				    split: true,
				    header: app_strings.LBL_EMAIL_OPTIONS
	            }]
	        });
        },

        /**
        *  Create compose tab which will populate the 'right' container in the compose window.
        */
        _initComposeOptionTabs : function(idx)
        {
	        var cTabs = new YAHOO.widget.TabView("composeRightTabs" + idx);
	        var tab = new YAHOO.widget.Tab({
				label: app_strings.LBL_EMAIL_ATTACHMENT,
				scroll : true,
				content : SUGAR.util.getAndRemove("divAttachments" + idx).innerHTML,
				id : "divAttachments" + idx,
				active : true
			});

	        tab.layout = SE.composeLayout[idx];

     	   tab.on("activeChange", function(o){
        		if (o.newValue) {
        			this.layout.getUnitByPosition("right").set("header", app_strings.LBL_EMAIL_ATTACHMENT);
        		}
       		});

        	cTabs.addTab(tab);

	        tab = new YAHOO.widget.Tab({
				label: app_strings.LBL_EMAIL_OPTIONS,
				scroll : true,
				content : SUGAR.util.getAndRemove("divOptions" + idx).innerHTML,
				id : "divOptions" + idx,
				active : false
			});

	        tab.layout = SE.composeLayout[idx];
	        tab.on("activeChange", function(o){
	        	if (o.newValue) {
	        		this.layout.getUnitByPosition("right").set("header", app_strings.LBL_EMAIL_OPTIONS);
	        	}
	        });
        	cTabs.addTab(tab);

	        SE.composeLayout[idx].autoSize = function() {
	        	var pEl = this.get("element").parentNode.parentNode.parentNode;
	        	this.set("height", pEl.clientHeight-30);
	        	this.render();
	        }

        	SE.composeLayout[idx].rightTabs = cTabs;
    },
    isParentTypeValid : function(idx) {
		var parentTypeValue = document.getElementById('data_parent_type' + idx).value;
		var parentNameValue = document.getElementById('data_parent_name' + idx).value;
		if (trim(parentTypeValue) == ""){
			alert(mod_strings.LBL_ERROR_SELECT_MODULE);
			return false;
		} // if
		return true;
    },

    isParentTypeAndNameValid : function(idx) {
		var parentTypeValue = document.getElementById('data_parent_type' + idx).value;
		var parentNameValue = document.getElementById('data_parent_name' + idx).value;
		var parentIdValue = document.getElementById('data_parent_id' + idx).value;
		if ((trim(parentTypeValue) != "" && trim(parentNameValue) == "") ||
			(trim(parentTypeValue) != "" && trim(parentNameValue) != "" && parentIdValue == "")){
				alert(mod_strings.LBL_ERROR_SELECT_MODULE_SELECT);
			return false;
		} // if
		return true;
    },

    callopenpopupForEmail2 : function(idx,options) {

        var formName = 'emailCompose' + idx;

        if(typeof(options) != 'undefined' && typeof(options.form_name) != 'undefined')
            formName = options.form_name;

		var parentTypeValue = document.getElementById('data_parent_type' + idx).value;
		var parentNameValue = document.getElementById('data_parent_name' + idx).value;
		if (!SE.composeLayout.isParentTypeValid(idx)) {
			return;
		} // if
		open_popup(document.getElementById('data_parent_type' + idx).value,600,400,'&tree=ProductsProd',true,false,
		{
			call_back_function:"SE.composeLayout.popupAddEmail",
			form_name:formName,
			field_to_name_array:{
				id:'data_parent_id' + idx,
				name:'data_parent_name' + idx,
				email1:'email1'}
		});
	},

	popupAddEmail : function(o)
	{
		var nameKey = "data_parent_name" + SE.composeLayout.currentInstanceId;
		var data = o.name_to_value_array;
		if (typeof (data[nameKey]) != "undefined" && data[nameKey] != ""
			&& typeof (data["email1"]) != "undefined" && data["email1"] != "" && data["email1"] != "undefined")
        {
        	var target = Dom.get("addressTO" + SE.composeLayout.currentInstanceId);
        	target.value = SE.addressBook.smartAddEmailAddressToComposeField(target.value, data[nameKey] + "<" + data.email1 + ">");
        }
		set_return(o);
	},
    /**
     * Prepare TinyMCE
     */
    _1_tiny : function(isReplyForward) {
        var idx = SE.composeLayout.currentInstanceId;
        var elId = SE.tinyInstances.currentHtmleditor = 'htmleditor' + idx;
        SE.tinyInstances[elId] = { };
        SE.tinyInstances[elId].ready = false;

        if (!SUGAR.util.isTouchScreen()) {
            var t = tinyMCE.getInstanceById(elId);
        }
        if(typeof(t) == 'undefined')  {
            if (!SUGAR.util.isTouchScreen()) {
                tinyMCE.execCommand('mceAddControl', false, elId);
            }
            YAHOO.util.Event.onAvailable(elId + "_parent", function() {
                SE.composeLayout.resizeEditorSetSignature(idx,!isReplyForward);
                }, this);
        }
    },

    resizeEditorSetSignature : function(idx,setSignature)
    {
    	var instance = SE.util.getTiny(SE.tinyInstances.currentHtmleditor);

        if(typeof(instance) == 'undefined' || (typeof(SE.composeLayout.loadedTinyInstances[idx]) != 'undefined' && SE.composeLayout.loadedTinyInstances[idx] == false)) {
            setTimeout("SE.composeLayout.resizeEditorSetSignature(" + idx + ",'"+setSignature+"');",500);
		    return;
		}

        SE.composeLayout.resizeEditor(idx);
        if(setSignature) {
            setTimeout("SUGAR.email2.composeLayout.setSignature("+idx+");",250);
        }

    },

    resizeEditor : function(idx)
    {
    	var cof = Dom.get('composeOverFrame' + idx);
        var head = Dom.get('composeHeaderTable' + idx);
        var targetHeight = cof.clientHeight - head.clientHeight;
    	var instance = SE.util.getTiny('htmleditor' + idx);

        try {
    	var parentEl = Dom.get(instance.editorId + '_parent');
    	var toolbar = Dom.getElementsByClassName("mceFirst", "tr", parentEl)[0];
    	var contentEl  = instance.contentAreaContainer;
        var iFrame = contentEl.firstChild;
        var tinMceToolbarOffset = 18;
        iFrame.style.height = (targetHeight - toolbar.offsetHeight - tinMceToolbarOffset)  + "px";

        } catch(e) {
            setTimeout("SE.composeLayout.resizeEditor("+idx+");",1000);
        }
    },

    /**
     * Initializes d&d, auto-complete, email templates
     */
    _2_final : function() {
        var idx = SE.composeLayout.currentInstanceId;

        if(this.emailTemplates) {
            this.setComposeOptions(idx);
        } else {
            //populate email template cache
            AjaxObject.target = '';
            AjaxObject.startRequest(callbackComposeCache, urlStandard + "&emailUIAction=fillComposeCache");
        }

        // handle drop targets for addressBook
       var to =  new YAHOO.util.DDTarget('addressTO' +idx, 'addressBookDD', {notifyDrop:this.handleDrop});
       var cc =  new YAHOO.util.DDTarget('addressCC' +idx, 'addressBookDD', {notifyDrop:this.handleDrop});
       var bcc = new YAHOO.util.DDTarget('addressBCC'+idx, 'addressBookDD', {notifyDrop:this.handleDrop});
       to.notifyDrop = cc.notifyDrop = bcc.notifyDrop = this.handleDrop;

        // auto-complete setup
        SE.autoComplete.init(idx);

        // set focus on to:
        document.getElementById("addressTO" + idx).focus();
    },

	/**
     * hide tinyMCE tool bar if send email as plaintext is checked
     */
    renderTinyMCEToolBar : function (idx, hide) {
    	if (hide) {
    		document.getElementById('htmleditor' + idx + '_toolbar1').style.display = 'none';
    	} else {
    		document.getElementById('htmleditor' + idx + '_toolbar1').style.display = '';
    	}
    },

    c1_composeEmail : function(isReplyForward, retry) {
        if (!retry) {
            this._0_yui();
        }
        if  (!SUGAR.util.isTouchScreen() && (typeof(tinyMCE) == 'undefined' || typeof(tinyMCE.settings) == 'undefined')){
            setTimeout("SE.composeLayout.c1_composeEmail(" + isReplyForward + ", true);", 500);
        } else {
	        this._1_tiny(isReplyForward);
	        this._2_final();

	        if(isReplyForward) {
	            this.replyForwardEmailStage2();
	        }
        }
    },

    /**
     * takes draft info and prepopulates
     */
    c0_composeDraft : function() {
        this.getNewInstanceId();
        inCompose = true;
        document.getElementById('_blank').innerHTML = '';
        var idx = SE.composeLayout.currentInstanceId;
		SE.composeLayout.draftObject = new Object();
		SE.composeLayout.draftObject.id = idx;
		SE.composeLayout.draftObject.isDraft = true;
        SE.composeLayout.currentInstanceId = idx;
        SE.tinyInstances.currentHtmleditor = 'htmleditor' + SE.composeLayout.currentInstanceId;
        SE.tinyInstances[SE.tinyInstances.currentHtmleditor] = new Object();
        SE.tinyInstances[SE.tinyInstances.currentHtmleditor].ready = false;

        SE.composeLayout._0_yui();
        SE.composeLayout._1_tiny(true);

        // final touches
        SE.composeLayout._2_final();

        /* Draft-specific final processing. Need a delay to allow Tiny to render before calling setText() */
        setTimeout("AjaxObject.handleReplyForwardForDraft(SE.o);", 1000);
    },

    /**
     * Strip & Prep editor hidden fields
     */
    c0_composeNewEmail : function() {
        this.getNewInstanceId();
        this.c1_composeEmail(false);
    },

    /**
     * Sends async request to get the compose view.
     * Requests come from "reply" or "forwards"
     */
    c0_replyForwardEmail : function(ieId, uid, mbox, type) {
        SE.composeLayout.replyForwardObj = new Object();
        SE.composeLayout.replyForwardObj.ieId = ieId;
        SE.composeLayout.replyForwardObj.uid = uid;
        SE.composeLayout.replyForwardObj.mbox = mbox;
        SE.composeLayout.replyForwardObj.type = type;

        if(mbox == 'sugar::Emails') {
            SE.composeLayout.replyForwardObj.sugarEmail = true;
        }

        SE.composeLayout.getNewInstanceId();
        SE.composeLayout.c1_composeEmail(true);
    },
    ////    END COMPOSE FLOW
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Called when a contact, email, or mailinglist is dropped
     * into one of the compose fields.
     */
    handleDrop : function (source, event, data, target) {
        var nodes;
        if (!target) {
            target = event.getTarget();
            if (data.single) {
                data.nodes = [data.nodes];
            }
            nodes = data.nodes;
        } else {
            target = document.getElementById(target);
            nodes = data;
        }

        //If a mailing list was dropped
        if (source == SE.emailListsView) {
            for(var i in nodes) {
            	var node = nodes[i].getData();
            	var targetMailingList;
            	if (node[1] == 'list-email') {
            		targetMailingList = SE.userPrefs.emailLists[node[0].substring(0, 36)];
            	} else if (node[1] == 'address-list') {
            		targetMailingList = SE.userPrefs.emailLists[node[0]];
            	}
                //var targetMailingList = SE.userPrefs.emailLists[node[0]];
                for(var displayName in targetMailingList) {
                    var listIds = targetMailingList[displayName];
                    if (typeof(listIds) != 'object') continue;
                    for(var j=0; j<listIds.length; j++) {
                        var email = ""
                        if(SE.addressBook._contactCache[listIds[j]]) {
                            email = SE.addressBook.getFormattedAddress(listIds[j]);
                        } else {
							var contact = SE.addressBook.getContactFromEmail(listIds[j]);
							if (contact) {
								email = SE.addressBook.getFormattedAddress(contact.id);
							}
                        }
                        target.value = SE.addressBook.smartAddEmailAddressToComposeField(target.value, email);
                    } // for
                } // for
            } // for
            return;
        }
        if (target.id.indexOf('address') > -1) {
            // dropped onto email to/cc/bcc field
            for(var i in nodes) {
            	var node = nodes[i].getData();
            	var email = "";
                if (node[1].indexOf('contact') > -1) {
                    email = SE.addressBook.getFormattedAddress(node[0]);
                } else if (node[1].indexOf('address-email') > -1){
                    email = node[3].replace(/&nbsp;/gi, '');
                    email = email.replace('&lt;', '<').replace('&gt;', '>');
                    var tr = source.getTrEl(nodes[i]);
                    while (tr && !Dom.hasClass(tr, "address-contact")) {
                    	tr = source.getPreviousTrEl(tr);
                    }
                    var CID = source.getRecord(tr).getData()[0];
                    var o = SE.addressBook._contactCache[CID];
                    var name = new String(o.name);
                    var finalName = name.replace(/(<([^>]+)>)/ig, "");
                    email = finalName + email;
                }
                target.value = SE.addressBook.smartAddEmailAddressToComposeField(target.value, email);
            }
        }
    },


    /////////////////////////////////////////////////////////////////////////////
    ///    EMAIL TEMPLATE CODE
    applyEmailTemplate : function (idx, id) {

        //bug #20680
        var box_title = mod_strings.LBL_EMAILTEMPLATE_MESSAGE_SHOW_TITLE;
		var box_msg = mod_strings.LBL_EMAILTEMPLATE_MESSAGE_SHOW_MSG;
		var box_none_msg = mod_strings.LBL_EMAILTEMPLATE_MESSAGE_CLEAR_MSG;

		//bug #6224
		var to_addr = document.getElementById('addressTO'+idx);
		if (to_addr.value.search(/[^;,]{6,}[;,][^;,]{6,}/) != -1)
		{
			box_title = mod_strings.LBL_EMAILTEMPLATE_MESSAGE_WARNING_TITLE;
			box_msg = mod_strings.LBL_EMAILTEMPLATE_MESSAGE_MULTIPLE_RECIPIENTS + '<br /><br />' + box_msg;
		}

		// id is selected index of email template drop-down
		if(id == '' || id == "0") {
			YAHOO.SUGAR.MessageBox.show({
	           title:box_title,
	           msg: box_none_msg,
	           type: 'confirm',
	           fn: function(btn){
	           		if(btn=='no'){return;};
	           		SUGAR.email2.composeLayout.processNoneResult(idx, id);},
	           modal:true,
	           scope:this
	       });
	       return;
		}

		YAHOO.SUGAR.MessageBox.show({
           title:box_title,
           msg: box_msg,
           type: 'confirm',
           fn: function(btn){
           		if(btn=='no'){return;};
           		SUGAR.email2.composeLayout.processResult(idx, id);},
           modal:true,
           scope:this
       });
    },

    processNoneResult : function(idx, id) {
        var tiny = SE.util.getTiny('htmleditor' + idx);
        var tinyHTML = tiny.getContent();
        var openTag = '<div><span><span>';
        var htmllow = tinyHTML.toLowerCase();
        var start = htmllow.indexOf(openTag);
		if (start > -1) {
	        tinyHTML = tinyHTML.substr(start);
            tiny.setContent(tinyHTML);
		} else {
       	    tiny.setContent('');
		}
        //now that content is set, call method to set signature
        setTimeout("SUGAR.email2.composeLayout.setSignature("+idx+");",500);
    },

	processResult : function(idx , id){
		var post_data = {"module":"EmailTemplates","record":id};
		var global_rpcClient =  new SugarRPCClient();

		result = global_rpcClient.call_method('retrieve', post_data, true);
		if(!result['record']) return;
		json_objects['email_template_object'] = result['record'];
		this.appendEmailTemplateJSON();

        // get attachments if any
        AjaxObject.target = '';
        AjaxObject.startRequest(callbackLoadAttachments, urlStandard + "&emailUIAction=getTemplateAttachments&parent_id=" + id);
    },

    appendEmailTemplateJSON : function() {
        var idx = SE.composeLayout.currentInstanceId; // post increment

        // query based on template, contact_id0,related_to
        //jchi 09/10/2008 refix #7743
        if(json_objects['email_template_object']['fields']['subject'] != '' )
        {
            // cn: bug 7743, don't stomp populated Subject Line
            document.getElementById('emailSubject' + idx).value = decodeURI(encodeURI(json_objects['email_template_object']['fields']['subject']));
        }
        var text = '';
        if(json_objects['email_template_object']['fields']['text_only'] == 1)
        {
        	text = "<p>" + decodeURI(encodeURI(json_objects['email_template_object']['fields']['body'])).replace(/<BR>/ig, '</p><p>').replace(/<br>/gi, "</p><p>").replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"') + "</p>";
        	document.getElementById('setEditor' + idx).checked = true;
        	SUGAR.email2.composeLayout.renderTinyMCEToolBar(idx, 1);
        }
        else
        {
        	text = decodeURI(encodeURI(json_objects['email_template_object']['fields']['body_html'])).replace(/<BR>/ig, '\n').replace(/<br>/gi, "\n").replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
        	document.getElementById('setEditor' + idx).checked = false;
        	SUGAR.email2.composeLayout.renderTinyMCEToolBar(idx, 0);
        }


        var tiny = SE.util.getTiny('htmleditor' + idx);
        var tinyHTML = tiny.getContent();
        var openTag = '<div><span><span>';
        var closeTag = '</span></span></div>';
        var htmllow = tinyHTML.toLowerCase();
        var start = htmllow.indexOf(openTag);
		if (start > -1) {
	        var htmlPart2 = tinyHTML.substr(start);
	        tinyHTML = text + htmlPart2;
	        tiny.setContent(tinyHTML);
		} else {
        	tiny.setContent(text);
		}
		//now that content is set, call method to set signature
        setTimeout("SUGAR.email2.composeLayout.setSignature("+idx+");",500);
    },

    /**
     * Writes out the signature in the email editor
     */
    setSignature : function(idx)
    {
        if (!tinyMCE)
            return false;
        var hide = document.getElementById('setEditor' + idx).checked;
        SE.composeLayout.renderTinyMCEToolBar(idx,hide);
        //wait for signatures to load before trying to set them
        if (!SE.composeLayout.signatures) {
            setTimeout("SE.composeLayout.setSignature(" + idx + ");", 1000);
			return;
        }

        if(idx != null) {
            var sel = document.getElementById('signatures' + idx);
        } else {
            var sel = document.getElementById('signature_id');
            idx = SE.tinyInstances.currentHtmleditor;
        }

        //Ensure that the tinyMCE html has been rendered.
        if(typeof(SE.composeLayout.loadedTinyInstances[idx]) != 'undefined' && SE.composeLayout.loadedTinyInstances[idx] == false) {
            setTimeout("SE.composeLayout.setSignature(" + idx + ");",1000);
		    return;
		}

        var signature = '';

        try {
            signature = sel.options[sel.selectedIndex].value;
        } catch(e) {

        }

        // The tags are used for finding the signature
        var openTag = '<div id="signature-begin"><br />';
        var closeTag = '<span>&nbsp;</span></div>';

        // Get tinyMCE instance
        var t = tinyMCE.getInstanceById('htmleditor' + idx);

        // IE 6 Hack
        if(typeof(t) != 'undefined')
        {
            t.contentDocument = t.contentWindow.document;
            var html = t.getContent();
        }
        else
        {
            var html = '';
        }

        var htmllow = html.toLowerCase();
        var start = htmllow.indexOf(openTag);
        // Start looking for the closeTag where the start tag was found
        var end = htmllow.indexOf(closeTag, start);
        if (end >= 0)
        {
            end += closeTag.length;
        }
        else
        {
            end = htmllow.length;
        }

        // selected "none" - remove signature from email
        if (signature == '')
        {
            if (start > -1)
            {
                var htmlPart1 = html.substr(0, start);
                var htmlPart2 = html.substr(end, html.length);

                html = htmlPart1 + htmlPart2;
                t.setContent(html);
            }
            SE.signatures.lastAttemptedLoad = '';
            return false;
        }

        if (!SE.signatures.lastAttemptedLoad) // lazy load place holder
        {
            SE.signatures.lastAttemptedLoad = '';
        }

        SE.signatures.lastAttemptedLoad = signature;

        if (typeof(SE.signatures[signature]) == 'undefined')
        {
            //lazy load
            SE.signatures.lastAttemptedLoad = ''; // reset this flag for recursion
            SE.signatures.targetInstance = (idx) ? idx : "";
            AjaxObject.target = '';
            AjaxObject.startRequest(callbackLoadSignature, urlStandard + "&emailUIAction=getSignature&id="+signature);
        }
        else
        {
            var newSignature = this.prepareSignature(SE.signatures[signature]);

            // clear out old signature
            if (SE.signatures.lastAttemptedLoad && start > -1)
            {
                var htmlPart1 = html.substr(0, start);
                var htmlPart2 = html.substr(end, html.length);

                html = htmlPart1 + htmlPart2;
            }

            // pre|append
			start = html.indexOf('<div><hr></div>');
            if (SE.userPrefs.signatures.signature_prepend == 'true' && start > -1) // Prepend
            {
				var htmlPart1 = html.substr(0, start);
				var htmlPart2 = html.substr(start, html.length);
                var newHtml = htmlPart1 + openTag + newSignature + closeTag + htmlPart2;
            }
            else if (SUGAR.email2.userPrefs.signatures.signature_prepend == 'true') // Prepend
            {
            	//bug 48285
                var newHtml = html;

                //remove custom spacing
                var spacing = '<span id="spacing"><br /><br /><br /></span>&nbsp;';
                var customSpacingStart = html.indexOf(spacing);

                if (customSpacingStart > -1)
                {
                    var part1 = newHtml.substr(0, customSpacingStart);
                    var part2 = newHtml.substr(customSpacingStart+spacing.length, newHtml.length);
                    newHtml = part1 + part2;
                }

                //append signature
                var bodyStartTag = '<body>';
                var body = newHtml.indexOf(bodyStartTag);

                if (body > -1)
                {
                    var part1 = newHtml.substr(0, body+bodyStartTag.length);
                    var part2 = newHtml.substr(body+bodyStartTag.length, newHtml.length);
                    newHtml = part1 + spacing + openTag + newSignature + closeTag + part2;
                }
                else
                {
                    newHtml = openTag + newSignature + closeTag + newHtml;
                }
                //end bug 48285
            }
            else // Append
            {
            	// On full compose mail has <body> element empty 
            	var bodyStringEmpty = htmllow.indexOf("<body>") > -1 && htmllow.replace(/\s/g, "").match(/<body>.+<\/body>/) == null;
            	// On quick compose a new document has html.length == 0
                if (htmllow.length == 0 || bodyStringEmpty)
                {
                    // Prepend <br /> to openTag because if it's an empty document
                    // or a document with nothing other than whitespace in <body></body>
                	// TinyMCE will pick the id of the div containing the signature
                    // when adding a new row and duplicate it so this might cause
                    // trouble when changing signatures
                    openTag = "<br />" + openTag;
                }

                var body = html.indexOf('</body>');
                if (body > -1)
                {
                    var part1 = html.substr(0, body);
                    var part2 = html.substr(body, html.length);
                    var newHtml = part1 + openTag + newSignature + closeTag + part2;
                }
                else
                {
                    var newHtml = html + openTag + newSignature + closeTag;
                }
            }
            t.setContent(newHtml);
        }
    },

    prepareSignature : function(str) {
        var signature = new String(str);

        signature = signature.replace(/&lt;/gi, '<');
        signature = signature.replace(/&gt;/gi, '>');

        return signature;
    },


    showAttachmentPanel : function(idx) {
    	var east = SE.composeLayout[idx].getUnitByPosition("right");
    	var tabs = SE.composeLayout[idx].rightTabs;
    	east.expand();
        tabs.set("activeTab", tabs.getTab(0));
    },

    /**
     * expands sidebar and displays options panel
     */
    showOptionsPanel : function(idx) {
    	var east = SE.composeLayout[idx].getUnitByPosition("right");
    	var tabs = SE.composeLayout[idx].rightTabs;
    	east.expand();
        tabs.set("activeTab", tabs.getTab(1));
    },

    /**
     * Selects the Contacts tab
     */
    showContactsPanel : function() {
        SE.complexLayout.regions.west.showPanel("contactsTab");
    },

    /**
     * Generates fields for Select Document
     */
    addDocumentField : function(idx) {
        var basket = document.getElementById('addedDocuments' + idx);
        if(basket) {
            var index = (basket.childNodes.length / 7) - 1;
            if(index < 0)
                index = 0;
        } else {
            index = 0;
        }

        var test = document.getElementById('documentId' + idx + index);

        while(test != null) {
            index++;
            test = document.getElementById('documentId' + idx + index);
        }

        var documentCup = document.createElement("div");
        documentCup.id = 'documentCup' + idx + index;
        documentCup.innerHTML = "<input type='hidden' name='document" + idx + index + "' id='document" + idx + index + "' />" +
                // document id field
                "<input type='hidden' name='documentId" + idx + index + "' id='documentId" + idx + index + "' />" +
                // document name field
                "<input value='' size='15' disabled='true' type='text' name='documentName" + idx + index + "' id='documentName" + idx + index + "' />" +
                // select button
                "<button class='button firstChild' type='button' name='documentSelect" + idx + index + "' id='documentSelect" + idx + index + "'" +
                    "onclick='SE.composeLayout.selectDocument(\"" + index + "\");' value='" + app_strings.LBL_EMAIL_SELECT + "'>" +
                "<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=id-ff-select.png' ></button>" +
                // remove button
                "<button class='button lastChild' type='button' name='documentRemove" + idx + index + "' id='documentRemove" + idx + index + "'" +
                    "onclick='SE.composeLayout.deleteDocumentField(\"documentCup" + idx + index + "\");' value='" + app_strings.LBL_EMAIL_REMOVE + "'>" +
                 "<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=id-ff-clear.png' ></button>" +
                "<br/>";

        basket.appendChild(documentCup);
        //basket.innerHTML += out;
        return index;
    },

    /**
     * Makes async call to save a draft of the email
     * @param int Instance index
     */
    saveDraft : function(tinyInstance) {
        SE.tinyInstances.currentHtmleditor = 'htmleditor' + tinyInstance;
        this.sendEmail(tinyInstance, true);
    },

    selectDocument : function(target) {
        URL="index.php?module=Emails&action=PopupDocuments&to_pdf=true&target=" + target;
        windowName = 'selectDocument';
        windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

        win = SUGAR.util.openWindow(URL, windowName, windowFeatures);
        if(window.focus) {
            // put the focus on the popup if the browser supports the focus() method
            win.focus();
        }
    },

    /**
     * Modal popup for file attachment dialogue
     */
    addFileField : function() {
    	if(!SE.addFileDialog){ // lazy initialize the dialog and only create it once
            SE.addFileDialog = new YAHOO.widget.Dialog("addFileDialog", {
            	modal:true,
            	visible:false,
            	fixedcenter:true,
            	constraintoviewport: true,
                scroll: true,
                keylisteners : new YAHOO.util.KeyListener(document, { keys:27 }, {
                	fn:function(){SE.addFileDialog.hide();}
                })
            });
            SE.addFileDialog.setHeader(app_strings.LBL_EMAIL_ATTACHMENTS);
            SE.addFileDialog.render();
           // SE.addFileDialog.addKeyListener(27, , SE.addFileDialog);
        }
    	Dom.removeClass("addFileDialog", "yui-hidden");

        SE.addFileDialog.show();
    },

    /**
     * Async upload of file to temp dir
     */
    uploadAttachment : function() {
        if(document.getElementById('email_attachment').value != "") {
            var formObject = document.getElementById('uploadAttachment');
            YAHOO.util.Connect.setForm(formObject, true, true);
            AjaxObject.target = '';
            AjaxObject.startRequest(callbackUploadAttachment, null);
        } else {
            alert(app_strings.LBL_EMAIL_ERROR_NO_FILE);
        }
    },

    /**
     * Adds a SugarDocument to an outbound email.  Action occurs in a popup window displaying a ListView from the Documents module
     * @param string target in focus compose layout
     */
    setDocument : function(idx, target, documentId, documentName, docRevId) {
        // fields are named/id'd [fieldName][instanceId][index]
        var addedDocs = document.getElementById("addedDocuments" + idx);
        var docId = document.getElementById('documentId' + idx + target);
        var docName = document.getElementById('documentName' + idx + target);
        var docRevisionId = document.getElementById('document' + idx + target);
        docId.value = documentId;
        docName.value = documentName;
        docRevisionId.value = docRevId;
    },

    /**
     * Removes the bucket div containing the document input fields
     */
    deleteDocumentField : function(documentCup) {
        var f0 = document.getElementById(documentCup);
        f0.parentNode.removeChild(f0);
    },

    /**
     * Removes a Template Attachment field
     * @param int
     * @param int
     */
    deleteTemplateAttachmentField : function(idx, index) {
        // create not-in-array values for removal filtering
        var r = document.getElementById("templateAttachmentsRemove" + idx).value;

        if(r != "") {
            r += "::";
        }

        r += document.getElementById('templateAttachmentId' + idx + index).value;
        document.getElementById("templateAttachmentsRemove" + idx).value = r;

        var target = 'templateAttachmentCup' + idx + index;
        d =  document.getElementById(target);
        d.parentNode.removeChild(d);
    },

    /**
     * Async removal of uploaded temp file
     * @param string index Should be a concatenation of idx and index
     * @param string
     */
    deleteUploadAttachment : function(index, file) {
        var d = document.getElementById('email_attachment_bucket' + index);
        d.parentNode.removeChild(d);

        // make async call to delete cached file
        AjaxObject.target = '';
        AjaxObject.startRequest('', urlStandard + "&emailUIAction=removeUploadedAttachment&file="+unescape(file));
    },

    /**
     * Attaches files coming from Email Templates
     */
    addTemplateAttachmentField : function(idx) {
        // expose title
        document.getElementById('templateAttachmentsTitle' + idx).style.display = 'block';

        var basket = document.getElementById('addedTemplateAttachments' + idx);

        if(basket) {
            var index = basket.childNodes.length;
            if(index < 0)
                index = 0;
        } else {
            index = 0;
        }

        var out = "<div id='templateAttachmentCup" + idx + index + "'>" +
				// remove button
				"<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=minus.gif' " +
					"style='cursor:pointer' align='absmiddle' onclick='SUGAR.email2.composeLayout.deleteTemplateAttachmentField(\"" +
					idx + "\",\"" + index + "\");'/>" +
				// file icon
				"<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=attachment.gif' " + "align='absmiddle' />" +
				// templateAttachment field
				"<input type='hidden' value='" + "' name='templateAttachment" + idx + index + "' id='templateAttachment" + idx + index + "' />" +
				// docId field
				"<input type='hidden' value='" + "' name='templateAttachmentId" + idx + index + "' id='templateAttachmentId" + idx + index + "' />" +
				// file name
				"<span id='templateAttachmentName"  + idx + index + "'" + ">&nbsp;</span>" +
				"<br id='br" + index + "></br>" +
				"<br id='brdoc" + index + "></br>" +
			"</div>";
		basket.innerHTML = basket.innerHTML + out;

        return index;
    },

    /**
     * Sends one email via async call
     * @param int idx Editor instance ID
     * @param bool isDraft
     */
    sendEmail : function(idx, isDraft) {

        //If the outbound account has an error message associate with it, alert the user and refuse to continue.
        var obAccountID = document.getElementById('addressFrom' + idx).value;

        if( typeof(SUGAR.email2.composeLayout.outboundAccountErrors[obAccountID]) != 'undefined' && !isDraft )
        {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, SUGAR.email2.composeLayout.outboundAccountErrors[obAccountID], 'alert');
            return false;
        }


        var form = document.getElementById('emailCompose' + idx);
        var composeOptionsFormName = "composeOptionsForm" + idx;
        if (!SUGAR.collection.prototype.validateTemSet(composeOptionsFormName, 'team_name')) {
        	alert(mod_strings.LBL_EMAILS_NO_PRIMARY_TEAM_SPECIFIED);
        	return false;
        } // if


        var t = SE.util.getTiny('htmleditor' + idx);
        if (t != null || typeof(t) != "undefined") {
            var html = t.getContent();
        } else {
            var html = "<p>" + document.getElementById('htmleditor' + idx).value + "</p>";
        }

 	    var subj = document.getElementById('emailSubject' + idx).value;
        var to = trim(document.getElementById('addressTO' + idx).value);
        var cc = trim(document.getElementById('addressCC' + idx).value);
        var bcc = trim(document.getElementById('addressBCC' + idx).value);
        var email_id = document.getElementById('email_id' + idx).value;
        var composeType = document.getElementById('composeType').value;
        var parent_type = document.getElementById("parent_type").value;
        var parent_id = document.getElementById("parent_id").value;

        var el_uid = document.getElementById("uid");
        var uid = (el_uid == null) ? '' : el_uid.value;

      	var el_ieId = document.getElementById("ieId");
        var ieId = (el_ieId == null) ? '' : el_ieId.value;

        var el_mbox = document.getElementById("mbox");
        var mbox = (el_mbox == null) ? '' : el_mbox.value;

        if (!isValidEmail(to) || !isValidEmail(cc) || !isValidEmail(bcc)) {
			alert(app_strings.LBL_EMAIL_COMPOSE_INVALID_ADDRESS);
        	return false;
        }

        if (!SE.composeLayout.isParentTypeAndNameValid(idx)) {
        	return;
        } // if
		var parentTypeValue = document.getElementById('data_parent_type' + idx).value;
		var parentIdValue = document.getElementById('data_parent_id' + idx).value;
        parent_id = parentIdValue;
        parent_type = parentTypeValue;

        var in_draft = (document.getElementById('type' + idx).value == 'draft') ? true : false;
        // baseline viability check

        if(to == "" && cc == '' && bcc == '' && !isDraft) {
            alert(app_strings.LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS);
            return false;
        } else if(subj == '' && !isDraft) {
            if(!confirm(app_strings.LBL_EMAIL_COMPOSE_NO_SUBJECT)) {
                return false;
            } else {
                subj = app_strings.LBL_EMAIL_COMPOSE_NO_SUBJECT_LITERAL;
            }
        } else if(html == '' && !isDraft) {
            if(!confirm(app_strings.LBL_EMAIL_COMPOSE_NO_BODY)) {
                return false;
            }
        }

        SE.util.clearHiddenFieldValues('emailCompose' + idx);
		document.getElementById('data_parent_id' + idx).value = parentIdValue;
		var title = (isDraft) ? app_strings.LBL_EMAIL_SAVE_DRAFT : app_strings.LBL_EMAIL_SENDING_EMAIL;
        SUGAR.showMessageBox(title, app_strings.LBL_EMAIL_ONE_MOMENT);
        html = html.replace(/&lt;/ig, "sugarLessThan");
        html = html.replace(/&gt;/ig, "sugarGreaterThan");

        form.sendDescription.value = html;
        form.sendSubject.value = subj;
        form.sendTo.value = to;
        form.sendCc.value = cc;
        form.sendBcc.value = bcc;
        form.email_id.value = email_id;
        form.composeType.value = composeType;
        form.composeLayoutId.value = 'composeLayout' + idx;
        form.setEditor.value = (document.getElementById('setEditor' + idx).checked == false) ? 1 : 0;
        form.saveToSugar.value = 1;
        form.fromAccount.value = document.getElementById('addressFrom' + idx).value;
        form.parent_type.value = parent_type;
        form.parent_id.value = parent_id;
        form.uid.value = uid;
        form.ieId.value = ieId;
        form.mbox.value = mbox;
		var teamIdsArray = SUGAR.collection.prototype.getTeamIdsfromUI(composeOptionsFormName, 'team_name');
		form.teamIds.value = teamIdsArray.join(",");
        //form.selectedTeam.value = document.getElementById('teamOptions' + idx).value;
        form.primaryteam.value = SUGAR.collection.prototype.getPrimaryTeamidsFromUI(composeOptionsFormName, 'team_name');

        // email attachments
        var addedFiles = document.getElementById('addedFiles' + idx);
        if(addedFiles) {
            for(i=0; i<addedFiles.childNodes.length; i++) {
                var bucket = addedFiles.childNodes[i];

                for(j=0; j<bucket.childNodes.length; j++) {
                    var node = bucket.childNodes[j];
                    var nName = new String(node.name);

                    if(node.type == 'hidden' && nName.match(/email_attachment/)) {
                        if(form.attachments.value != '') {
                            form.attachments.value += "::";
                        }
                        form.attachments.value += unescape(node.value);
                    }
                }
            }
        }

        // sugar documents
        var addedDocs = document.getElementById('addedDocuments' + idx);
        if(addedDocs) {
            for(i=0; i<addedDocs.childNodes.length; i++) {
                var cNode = addedDocs.childNodes[i];
                for(j=0; j<cNode.childNodes.length; j++) {
                    var node = cNode.childNodes[j];
                    var nName = new String(node.name);
                    if(node.type == 'hidden' && nName.match(/documentId/)) {
                        if(form.documents.value != '') {
                            form.documents.value += "::";
                        }
                        form.documents.value += node.value;
                    }
                }
            }
        }

        // template attachments
        var addedTemplateAttachments = document.getElementById('addedTemplateAttachments' + idx);
        if(addedTemplateAttachments) {
            for(i=0; i<addedTemplateAttachments.childNodes.length; i++) {
                var cNode = addedTemplateAttachments.childNodes[i];
                for(j=0; j<cNode.childNodes.length; j++) {
                    var node = cNode.childNodes[j];
                    var nName = new String(node.name);
                    if(node.type == 'hidden' && nName.match(/templateAttachmentId/)) {
                        if(form.templateAttachments.value != "") {
                            form.templateAttachments.value += "::";
                        }
                        form.templateAttachments.value += node.value;
                    }
                }
            }
        }

        // remove attachments
        form.templateAttachmentsRemove.value = document.getElementById("templateAttachmentsRemove" + idx).value;

        YAHOO.util.Connect.setForm(form);

        AjaxObject.target = 'frameFlex';

        // sending a draft email
        if(!isDraft && in_draft) {
            // remove row
            SE.listView.removeRowByUid(email_id);
        }

        var sendCallback = (isDraft) ? AjaxObject.composeLayout.callback.saveDraft : callbackSendEmail;
        var emailUiAction = (isDraft) ? "&emailUIAction=sendEmail&saveDraft=true" : "&emailUIAction=sendEmail";

        AjaxObject.startRequest(sendCallback, urlStandard + emailUiAction);
    },

    /**
     * Handles clicking the email address link from a given view
     */
    composePackage : function() {
        if(composePackage != null) {
            SE.composeLayout.c0_composeNewEmail();


            if(composePackage.to_email_addrs) {
                document.getElementById("addressTO" + SE.composeLayout.currentInstanceId).value = composePackage.to_email_addrs;
            } // if

            if (composePackage.cc_addrs) {
                document.getElementById("addressCC" + SE.composeLayout.currentInstanceId).value = composePackage.cc_addrs;
                SE.composeLayout.showHiddenAddress('cc', SE.composeLayout.currentInstanceId);
            }

            if (composePackage.subject != null && composePackage.subject.length > 0) {
            	document.getElementById("emailSubject" + SE.composeLayout.currentInstanceId).value = composePackage.subject;
            }

            //If no parent fields are set in the composePackage, ensure they are cleared.
            var parentFields = ['parent_type','parent_name','parent_id'];
            for(var i=0;i<parentFields.length;i++)
            {
                if ( typeof(composePackage[parentFields[i]]) == 'undefined' )
                    composePackage[parentFields[i]] = "";
            }

            document.getElementById("parent_type").value = composePackage.parent_type;
            document.getElementById('data_parent_type' + SE.composeLayout.currentInstanceId).value = composePackage.parent_type;
            document.getElementById("parent_id").value = composePackage.parent_id;
            document.getElementById('data_parent_id' + SE.composeLayout.currentInstanceId).value = composePackage.parent_id;
            document.getElementById('data_parent_name' + SE.composeLayout.currentInstanceId).value = composePackage.parent_name;

            if(composePackage.email_id != null && composePackage.email_id.length > 0) {
                document.getElementById("email_id" + SE.composeLayout.currentInstanceId).value = composePackage.email_id;
            } // if
            if (composePackage.body != null && composePackage.body.length > 0) {
		        var tiny = SE.util.getTiny('htmleditor' + SE.composeLayout.currentInstanceId);
		        SE.composeLayout.loadedTinyInstances[SE.composeLayout.currentInstanceId] = false;
        		setTimeout("SE.composeLayout.setContentOnThisTiny();", 3000);
            } // if
            if (composePackage.attachments != null) {
				SE.composeLayout.loadAttachments(composePackage.attachments);
            } // if

            if (composePackage.fromAccounts != null && composePackage.fromAccounts.status) {
				var addressFrom = document.getElementById('addressFrom' + SE.composeLayout.currentInstanceId);
		        SE.util.emptySelectOptions(addressFrom);
		        var fromAccountOpts = composePackage.fromAccounts.data;
		        for(i=0; i<fromAccountOpts.length; i++) {
		              var key = fromAccountOpts[i].value;
		              var display = fromAccountOpts[i].text;
		              var opt = new Option(display, key);
		              if (fromAccountOpts[i].selected) {
		              	opt.selected = true;
		              }
		              addressFrom.options.add(opt);
		        }

            } // if
        } // if
    },

    setContentOnThisTiny : function(recursive) {
    	var tiny = SE.util.getTiny('htmleditor' + SE.composeLayout.currentInstanceId);
        var tinyHTML = tiny.getContent();
        composePackage.body = decodeURI(encodeURI(composePackage.body));
        // cn: bug 14361 - text-only templates don't fill compose screen
        if(composePackage.body == '') {
            composePackage.body = decodeURI(encodeURI(composePackage.body)).replace(/<BR>/ig, '\n').replace(/<br>/gi, "\n").replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
        } // if
        //Flag determines if we should clear the tiny contents or just append
        if (typeof(composePackage.clearBody) != 'undefined' && composePackage.clearBody)
        {
            SE.composeLayout.tinyHTML = '';
        }
        else
        {

            //check to see if tiny is defined, and this is not a recursive call if not, then call self function one more time
            if(typeof tiny == 'undefined'  &&  typeof recursive == 'undefined'){
                //call this same function again, this time setting the recursive flag to true
                setTimeout("SE.composeLayout.setContentOnThisTiny(true);", 3000);
                return;
            }

            //bug 48179
            //check tinyHTML for closing tags
            var body = tinyHTML.lastIndexOf('</body>');
            spacing = '<span id="spacing"><br /><br /><br /></span>&nbsp;';

            if (body > -1)
            {
                var part1 = tinyHTML.substr(0, body);
                var part2 = tinyHTML.substr(body, tinyHTML.length);
                var newHtml = part1 + spacing + composePackage.body + part2;
            }
            else
            {
                var newHtml = tinyHTML + spacing + composePackage.body;
            }
            //end bug 48179

            SE.composeLayout.tinyHTML = newHtml;
        }

         tiny.setContent(SE.composeLayout.tinyHTML);
         //Indicate that the contents has been loaded successfully.
         SE.composeLayout.loadedTinyInstances[SE.composeLayout.currentInstanceId] = true;
    },
    /**
     * Confirms closure of a compose screen if "x" is clicked
     */
    confirmClose : function(panel) {
        if(confirm(app_strings.LBL_EMAIL_CONFIRM_CLOSE)) {
            SE.composeLayout.closeCompose(panel.id);
            return true;
        } else {
            return false;
        }
    },

    /**
     * forces close of a compose screen
     */
    forceCloseCompose : function(id) {
    	SE.composeLayout.closeCompose(id);

    	// handle flow back to originating view
        if(composePackage) {
            // check if it's a module we need to return to
            if(composePackage.return_module && composePackage.return_action && composePackage.return_id) {
                if(confirm(app_strings.LBL_EMAIL_RETURN_TO_VIEW)) {
                    var url = "index.php?module=" + composePackage.return_module + "&action=" + composePackage.return_action + "&record=" + composePackage.return_id;
                    window.location = url;
                }
            }
        }
    },

    /**
     * closes the editor that just sent email
     * @param string id ID of composeLayout tab
     */
    closeCompose : function(id) {
        // destroy tinyMCE instance
        var idx = id.substr(13, id.length);
        var instanceId = "htmleditor" + idx;
        tinyMCE.execCommand('mceRemoveControl', false, instanceId);

        // nullify DOM and namespace values.
        inCompose = false;
        SE.composeLayout[idx] = null;
        SE.tinyInstances[instanceId] = null;
        var tabsArray = SE.innerLayout.get("tabs");
        for (i = 0 ; i < tabsArray.length ; i++) {
        	if (tabsArray[i].get("id") == ('composeTab' + idx)) {
        		tabsArray[i].close();
        		break;
        	}
        }
        //SE.innerLayout.getTab(idx).close();
    },

    /**
    *  Enable the quick search for the compose relate field or search tab
    */
    enableQuickSearchRelate: function(idx,overides){

        if(typeof overides != 'undefined')
        {
            var newModuleID = overides['moduleSelectField']; //data_parent_type_search
            var newModule = document.getElementById(newModuleID).value;
            var formName = overides['formName'];
            var fieldName = overides['fieldName'];
            var fieldId = overides['fieldId'];
            var fullName = formName + "_" + fieldName;
            var postBlurFunction = null;
        }
        else
        {
            var newModule = document.getElementById('data_parent_type'+idx).value;
            var formName = 'emailCompose'+idx;
            var fieldName = 'data_parent_name'+idx;
            var fieldId = 'data_parent_id'+idx;
            var fullName = formName + "_" + fieldName;
            var postBlurFunction = "SE.composeLayout.qsAddAddress";
        }

        if(typeof sqs_objects == 'undefined')
            window['sqs_objects'] = new Array;

        window['sqs_objects'][fullName] = {
            form:formName,
			method:"query",
			modules:[newModule],
			group:"or",
            field_list:["name","id", "email1"],populate_list:[fieldName,fieldId],required_list:[fieldId],
            conditions:[{name:"name",op:"like_custom",end:"%",value:""}],
			post_onblur_function: postBlurFunction,
            order:"name","limit":"30","no_match_text":"No Match"};


        if(typeof QSProcessedFieldsArray != 'undefined')
        	QSProcessedFieldsArray[fullName] = false;
        if (typeof(QSFieldsArray) != 'undefined' && typeof(QSFieldsArray[fullName]) != 'undefined') {
        	QSFieldsArray[fullName].destroy();
        	delete QSFieldsArray[fullName];
        }
        if (Dom.get(fullName + "_results")) {
        	Dom.get(fullName + "_results").parentNode.removeChild(Dom.get(fullName + "_results"));
        }

        enableQS(false);
    },

	qsAddAddress : function(o) {
        if (o.name != "" && o.email1 != "")
        {
        	var target = Dom.get("addressTO" + SE.composeLayout.currentInstanceId);
        	target.value = SE.addressBook.smartAddEmailAddressToComposeField(target.value, o.name + "<" + o.email1 + ">");
        }
    },
    /**
     * Returns a new instance ID, 0-index
     */
    getNewInstanceId : function() {
        this.currentInstanceId = this.currentInstanceId + 1;
        return this.currentInstanceId;
    },

    /**
     * Takes an array of objects that contain the filename and GUID of a Note (attachment or Sugar Document) and applies the values to the compose screen.  Valid use-cases are applying an EmailTemplate or resuming a Draft Email.
     */
    loadAttachments : function(result) {
        var idx = SE.composeLayout.currentInstanceId;

        if(typeof(result) == 'object') {
        	//jchi #20680. Clean the former template attachments;
        	var basket = document.getElementById('addedTemplateAttachments' + idx);
			if(basket.innerHTML != ''){
				confirm(mod_strings.LBL_CHECK_ATTACHMENTS, mod_strings.LBL_HAS_ATTACHMENTS, function(btn){
					if (btn != 'yes'){
						basket.innerHTML = '';
					}
				});
			}
            for(i in result) {
                if(typeof result[i] == 'object') {
                    var index = SE.composeLayout.addTemplateAttachmentField(idx);
                    var bean = result[i];
                    document.getElementById('templateAttachmentId' + idx + index).value = bean['id'];
                    document.getElementById('templateAttachmentName' + idx + index).innerHTML += bean['filename'];
                }
            }
        }
    },

    /**
     * fills drop-down values for email templates and signatures
     */
    setComposeOptions : function(idx) {
        // send from accounts
        var addressFrom = document.getElementById('addressFrom' + idx);

        if (addressFrom.options.length <= 0) {
        	SE.util.emptySelectOptions(addressFrom);
	        var fromAccountOpts = SE.composeLayout.fromAccounts;
	        for (id = 0 ; id < fromAccountOpts.length ; id++) {
	              var key = fromAccountOpts[id].value;
	              var display = fromAccountOpts[id].text;
	              var is_default = false;
	              if(key == SUGAR.default_inbound_accnt_id)
	              	is_default = true;
	              var opt = new Option(display, key);
	              addressFrom.options.add(opt);
	              addressFrom.options[id].selected = is_default; //Safari bug new Option(x,y,true) does not work.
	        }
        }

        // email templates
        var et = document.getElementById('email_template' + idx);
        SE.util.emptySelectOptions(et);

        for(var key in this.emailTemplates) { // iterate through assoc array
            var display = this.emailTemplates[key];
            var opt = new Option(display, key);
            et.options.add(opt);
        }

		var renderTeam = true;
		if (SE.composeLayout.draftObject != null && SE.composeLayout.draftObject.id != null) {
			if (SE.composeLayout.draftObject.id == idx) {
				renderTeam = false;
			} // if
		} // if

		if (renderTeam) {
	        var teamOptions = document.getElementById('teamOptions' + idx);
	        teamOptions.innerHTML = "";

	        var teamOptionsString = this.teams;
	        teamOptionsString = teamOptionsString.replace(/composeEmailForm/gi, ("composeOptionsForm" + idx));
	        teamOptions.innerHTML = teamOptionsString;
	        SUGAR.util.evalScript(teamOptionsString);
		}

        // signatures
        var sigs = document.getElementById('signatures' + idx);
        SE.util.emptySelectOptions(sigs);

        for(var key in this.signatures) { // iterate through assoc array
            var display = this.signatures[key];
            var opt = new Option(display, key);

            if(key == SE.userPrefs.signatures.signature_default) {
                opt.selected = true;
            }

            sigs.options.add(opt);
        }

        // html/plain email?
        var htmlEmail = document.getElementById('setEditor' + idx);
        if(SE.userPrefs.emailSettings.sendPlainText == 1) {
            htmlEmail.checked = true;
        } else {
        	htmlEmail.checked = false;
        }

        SE.tinyInstances[SE.tinyInstances.currentHtmleditor].ready = true;
    },

    /**
     * After compose screen is rendered, async call to get email body from Sugar
     */
    replyForwardEmailStage2 : function() {
        SE.util.clearHiddenFieldValues('emailUIForm');
        SUGAR.showMessageBox(app_strings.LBL_EMAIL_RETRIEVING_MESSAGE, app_strings.LBL_EMAIL_ONE_MOMENT);

        var ieId = SE.composeLayout.replyForwardObj.ieId;
        var uid = SE.composeLayout.replyForwardObj.uid;
        var mbox = SE.composeLayout.replyForwardObj.mbox;
        var type = SE.composeLayout.replyForwardObj.type;
        var idx = SE.composeLayout.currentInstanceId;

        var sugarEmail = (SE.composeLayout.replyForwardObj.sugarEmail) ? '&sugarEmail=true' : "";

        document.getElementById('emailSubject' + idx).value = type;
        document.getElementById('emailUIAction').value = 'composeEmail';
        document.getElementById('composeType').value = type;
        document.getElementById('ieId').value = ieId;
        document.getElementById('uid').value = uid;
        document.getElementById('mbox').value = mbox;
		document.getElementById('setEditor' + idx).checked = SE.userPrefs.emailSettings.sendPlainText == 1 ? true : false;
        var formObject = document.getElementById('emailUIForm');
        YAHOO.util.Connect.setForm(formObject);

        var sendType = type;
        AjaxObject.startRequest(callbackReplyForward, urlStandard + "&composeType=" + type + sugarEmail);
    },

    /**
    *  Show the hidden cc or bcc fields
    */
    showHiddenAddress: function(addrType,idx){

    	Dom.removeClass(addrType+"_tr"+idx, "yui-hidden");
    	Dom.addClass(addrType+"_span"+idx, "yui-hidden");
		Dom.addClass("bcc_cc_sep"+idx, "yui-hidden");
		this[addrType+'Hidden'+idx] = false;

		//After bcc or cc is added, move options below last addr field
		Dom.insertAfter("add_addr_options_tr"+idx, 'bcc_tr'+idx);

		//If both cc and bcc hidden, remove the empty row containing text.
		if( ( typeof(this['ccHidden'+idx]) != 'undefined' && typeof(this['bccHidden'+idx]) != 'undefined')
			   && ( this['ccHidden'+idx]  == false && this['bccHidden'+idx] == false) )
			Dom.addClass("add_addr_options_tr"+idx, "yui-hidden");

		SE.composeLayout.resizeEditor(idx);
    },
    /**
    *  Hide the cc and bcc fields if they were shown.
    */
    hideHiddenAddresses: function(idx){

        var addrTypes = ['cc','bcc'];
        for(var i = 0;i<addrTypes.length;i++)
        {
            Dom.addClass(addrTypes[i] + "_tr"+idx, "yui-hidden");
            Dom.removeClass(addrTypes[i] + "_span"+idx, "yui-hidden");
            this[addrTypes[i] + 'Hidden'+idx] = true
        }

        Dom.removeClass("bcc_cc_sep"+idx, "yui-hidden");
        Dom.removeClass("add_addr_options_tr"+idx, "yui-hidden");
        Dom.insertBefore("add_addr_options_tr"+idx, 'bcc_tr'+idx);
    }
};

////    END SE.composeLayout
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
////    SE.util
SE.util = {
    /**
     * Cleans serialized UID lists of duplicates
     * @param string
     * @return string
     */
    cleanUids : function(str) {
        var seen = new Object();
        var clean = "";
        var arr = new String(str).split(",");

        for(var i=0; i<arr.length; i++) {
            if(seen[arr[i]]) {
                continue;
            }

            clean += (clean != "") ? "," : "";
            clean += arr[i];
            seen[arr[i]] = true;
        }

        return clean;
    },

    /**
     * Clears hidden field values
     * @param string id ID of form element to clear
     */
    clearHiddenFieldValues : function(id) {
        var form = document.getElementById(id);

        for(i=0; i<form.elements.length; i++) {
            if(form.elements[i].type == 'hidden') {
                var e = form.elements[i];
                if(e.name != 'action' && e.name != 'module' && e.name != 'to_pdf') {
                    e.value = '';
                }
            }
        }
    },

    /**
     * Reduces a SELECT drop-down to 0 items to prepare for new ones
     */
    emptySelectOptions : function(el) {
        if(el) {
            for(i=el.childNodes.length - 1; i >= 0; i--) {
                if(el.childNodes[i]) {
                    el.removeChild(el.childNodes[i]);
                }
            }
        }
    },

    /**
     * Returns the MBOX path in the manner php_imap expects:
     * ie: INBOX.DEBUG.test
     * @param string str Current serialized value, Home.personal.test.INBOX.DEBUG.test
     */
    generateMboxPath : function(str) {
        var ex = str.split("::");

        /* we have a serialized MBOX path */
        if(ex.length > 1) {
            var start = false;
            var ret = '';
            for(var i=0; i<ex.length; i++) {
                if(ex[i] == 'INBOX') {
                    start = true;
                }

                if(start == true) {
                    if(ret != "") {
                        ret += ".";
                    }
                    ret += ex[i];
                }
            }
        } else {
            /* we have a Sugar folder GUID - do nothing */
            return str;
        }

        return ret;
    },

    /**
     * returns a SUGAR GUID by navigating the DOM tree a few moves backwards
     * @param HTMLElement el
     * @return string GUID of found element or empty on failure
     */
    getGuidFromElement : function(el) {
        var GUID = '';
        var iterations = 4;
        var passedEl = el;

        // upwards
        for(var i=0; i<iterations; i++) {
            if(el) {
                if(el.id.match(SE.reGUID)) {
                    return el.id;
                } else {
                    el = el.parentNode;
                }
            }
        }

        return GUID;
    },

    /**
     * Returns the ID value for the current in-focus, active panel (in the innerLayout, not complexLayout)
     * @return string
     */
    getPanelId : function() {
        return SE.innerLayout.get("activeTab").id ? SE.innerLayout.get("activeTab").id : "Preview";
    },

    /**
     * wrapper to handle weirdness with IE
     * @param string instanceId
     * @return tinyMCE Controller object
     */
    getTiny : function(instanceId) {
        if(instanceId == '') {
            return null;
        }

        var t = tinyMCE.getInstanceById(instanceId);

        if(this.isIe()) {
            this.sleep(200);
            YAHOO.util.Event.onContentReady(instanceId, function(t) { return t; });
        }
        return t;
    },

    /**
     * Simple check for MSIE browser
     * @return bool
     */
    isIe : function() {
        var nav = new String(navigator.appVersion);
        if(nav.match(/MSIE/)) {
            return true;
        }
        return false;
    },

    /**
     * Recursively removes an element from the DOM
     * @param HTMLElement
     */
    removeElementRecursive : function(el) {
        this.emptySelectOptions(el);
    },

    /**
     * Fakes a sleep
     * @param int
     */
    sleep : function(secs) {
        setTimeout("void(0);", secs);
    },

    /**
     * Converts a <select> element to an Ext.form.combobox
     */
     convertSelect : function(select) {
       alert('in convertSelect');
       if (typeof(select) == "string") {
           select = document.getElementById(select);
       }
     },

     findChildNode : function (parent, property, value) {
    	 for (i in parent.children) {
    		 var child = parent.children[i];
    		 if (child.data[property] && child.data[property] == value || child[property] && child[property] == value)
    			 return child;
    		 var searchChild = SE.util.findChildNode(child, property, value);
    		 if (searchChild)
    			 return searchChild;
    	 }
    	 return false;
     },

     cascadeNodes : function (parent, fn, scope, args) {
    	 for (i in parent.children) {
    		 var child = parent.children[i];
    		 var s = scope ? scope : child;
    		 var a = args ? args : child;
        	 fn.call(s, a);
    		 SE.util.cascadeNodes(child, fn, scope, args);
    	 }
     }
};


////    END UTIL
///////////////////////////////////////////////////////////////////////////////


})();

/* End of File modules/Emails/javascript/EmailUICompose.js */

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

if (typeof console == "undefined")
	console = { log: function(o) {alert(o)} };

var AjaxObject = {
	ret : '',
	currentRequestObject : null,
	//timeout : 30000, // 30 second timeout default
	timeout : 9999999999, // 30 second timeout default
	forceAbort : false,
	trail : new Array(),

	/**
	 */
	_reset : function() {
		this.timeout = 30000;
		this.forceAbort = false;
	},

	folderRenameCleanup : function() {
		SUGAR.email2.folders.setSugarFolders();
	},

	fullSyncCleanup : function(o) {
		this.folders.checkMailCleanup(o);
		SUGAR.email2.settings.settingsDialog.hide();
	},

	/**
	 */
	composeCache : function(o) {
		var idx = SUGAR.email2.composeLayout.currentInstanceId; // post instance increment
		// get email templates and user signatures
		var ret = YAHOO.lang.JSON.parse(o.responseText);

		SUGAR.email2.composeLayout.emailTemplates = ret.emailTemplates;
		SUGAR.email2.composeLayout.signatures = ret.signatures;
		SUGAR.email2.composeLayout.fromAccounts = ret.fromAccounts;

		SUGAR.email2.composeLayout.setComposeOptions(idx);

		//Set the error array so we can notify the user when they try to hit send if any errors
		//are present.  We will also notify them now (after hitting compose button).
		SUGAR.email2.composeLayout.outboundAccountErrors = ret.errorArray;


		//if error element is returning an array, then check the length to make sure we have error messages
		if (typeof(ret.errorArray)=='object' && ret.errorArray instanceof Array && ret.errorArray.length > 0){
			//add error messages for display
			for(i in ret.errorArray)
				SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, ret.errorArray[i], 'alert');
		}else if (typeof(ret.errorArray)=='object' && ret.errorArray!=null && ret.errorArray!='' ) {
			//if error element is returning an object, and the object value is not empty or null, then display error message
			for(i in ret.errorArray)
				SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, ret.errorArray[i], 'alert');
		}

		//YUI bug with IE6 - Wont restore visibility property for nested select elements.
		if(SUGAR.isIE) {
			var overlayPanel = YAHOO.SUGAR.MessageBox.panel;
			if(overlayPanel) {
			  overlayPanel.subscribe('hide',function() { YAHOO.util.Dom.setStyle('addressFrom' + idx,'visibility','');});
			}
		}
	},


	handleDeleteSignature : function(o) {
		SUGAR.hideMessageBox();
        var ret = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.email2.composeLayout.signatures = ret.signatures;
    	var field = document.getElementById('signature_id');
		SUGAR.email2.util.emptySelectOptions(field);

		for(var i in ret.signatures) { // iterate through assoc array
			var opt = new Option(ret.signatures[i], i);
			field.options.add(opt);
		}
		setSigEditButtonVisibility();
	},

	/**
	 */
	handleDeleteReturn : function(o) {
		// force refresh ListView
		SUGAR.hideMessageBox();
		if(document.getElementById('focusEmailMbox')) {
			YAHOO.namespace('frameFolders').selectednode = SUGAR.email2.folders.getNodeFromMboxPath(document.getElementById('focusEmailMbox').innerHTML);
		}

		// need to display success message before calling next async call?
		document.getElementById(this.target).innerHTML = o.responseText;
	},

	/**
	 */
    handleFailure : function(o) {
		// Failure handler
		SUGAR.showMessageBox(mod_strings.LBL_EXCEPTION, o.statusText, 'alert');
		if(document.getElementById('saveButton')) {
			document.getElementById('saveButton').disabled = false;
		}
	},

	handleReplyForward : function(o) {
		var a = YAHOO.lang.JSON.parse(o.responseText);
		globalA = a;
		var idx = SUGAR.email2.composeLayout.currentInstanceId;

		document.getElementById('email_id' + idx).value = a.uid;
		document.getElementById('emailSubject' + idx).value = a.name;
		document.getElementById('addressTO' + idx).value = a.from;
		document.getElementById('uid' + idx).value = a.uid;
		if(a.cc) {
			document.getElementById('addressCC' + idx).value = a.cc;
			SE.composeLayout.showHiddenAddress('cc', idx);
		}

		if(a.type) {
			document.getElementById('type' + idx).value = a.type;
		}

		// apply attachment values
		SUGAR.email2.composeLayout.loadAttachments(a.attachments);

		setTimeout("callbackReplyForward.finish(globalA);", 500);
	},

	handleReplyForwardForDraft : function(o) {
		var a = YAHOO.lang.JSON.parse(o.responseText);
		globalA = a;
		var idx = SUGAR.email2.composeLayout.currentInstanceId;

		document.getElementById('email_id' + idx).value = a.uid;
		document.getElementById('emailSubject' + idx).value = a.name;
		document.getElementById('addressTO' + idx).value = a.to;

		if(a.cc) {
			document.getElementById('addressCC' + idx).value = a.cc;
			SUGAR.email2.composeLayout.showHiddenAddress('cc',idx);
		}

		if(a.bcc) {
			document.getElementById('addressBCC' + idx).value = a.bcc;
			SUGAR.email2.composeLayout.showHiddenAddress('bcc',idx);
		}


		if(a.type) {
			document.getElementById('type' + idx).value = a.type;
		}

        var teamOptions = document.getElementById('teamOptions' + idx);
        teamOptions.innerHTML = "";

        if (a.teamSetCode != null) {
	        var teamOptionsString = a.teamSetCode;
	        teamOptionsString = teamOptionsString.replace(/composeEmailForm/gi, ("composeOptionsForm" + idx));
	        teamOptions.innerHTML = teamOptionsString;
	        SUGAR.util.evalScript(teamOptionsString);
        }


		// apply attachment values
		SUGAR.email2.composeLayout.loadAttachments(a.attachments);

		setTimeout("callbackReplyForward.finish(globalA,0,1);", 500);
	},

	/**
	 */
	handleSuccess : function(o) {
		document.getElementById(this.target).innerHTML = o.responseText;
		SUGAR.hideMessageBox();
	},

	/**
	 */
	ieDeleteSuccess : function(o) {
		SUGAR.hideMessageBox();
		SUGAR.email2.accounts.refreshInboundAccountTable();
		alert(app_strings.LBL_EMAIL_IE_DELETE_SUCCESSFUL);
		SUGAR.email2.accounts.rebuildFolderList();
	},

	/**
	 */
	ieSaveSuccess : function(o) {
		document.getElementById('saveButton').disabled = false;
		var a = YAHOO.lang.JSON.parse(o.responseText);
		if (a) {
			if(a.error) {
				SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, app_strings.LBL_EMAIL_ERROR_CHECK_IE_SETTINGS, 'alert');
				SUGAR.email2.accounts.ieAccountError(SUGAR.email2.accounts.errorStyle);
			} else {
				resp = YAHOO.lang.JSON.parse(o.responseText);
				SUGAR.email2.accounts.refreshInboundAccountTable();
				SUGAR.email2.accounts.refreshOuboundAccountTable();
				SUGAR.email2.folders.startEmailCheckOneAccount(resp.id, true);
				SUGAR.email2.accounts.inboundAccountEditDialog.hide();
			}
		} else {
		     SUGAR.hideMessageBox();
		     SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, app_strings.LBL_EMAIL_ERROR_SAVE_ACCOUNT, 'alert');
		}

	},

	/**
	 */
	loadAttachments : function(o) {
		var result = YAHOO.lang.JSON.parse(o.responseText);

		SUGAR.email2.composeLayout.loadAttachments(result);
	},

	/**
	 */
	loadSignature : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.email2.signatures[ret.id] = ret.signature_html;
		SUGAR.email2.composeLayout.setSignature(SUGAR.email2.signatures.targetInstance);
	},

	/**
	 * Follow up to mark email read|unread|flagged
	 */
	markEmailCleanup : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		if (!ret['status']) {
        	SUGAR.hideMessageBox();
			SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, ret['message'], 'alert');
		} else {
			SUGAR.email2.contextMenus.markEmailCleanup();
		} // else
	},

	/**
	 */
	rebuildShowFolders : function(o) {
		var t = YAHOO.lang.JSON.parse(o.responseText);
		var show = document.getElementById('ieAccountListShow');

		SUGAR.email2.util.emptySelectOptions(show);

		for(i=0; i<t.length; i++) { // iterate through assoc array
			var opt = new Option(t[i].text, t[i].value, t[i].selected);
			opt.selected = t[i].selected;
			show.options.add(opt);
		}

		SUGAR.email2.accounts.renderTree();
	},
	/**
	 */
	saveListViewSortOrderPart2 : function() {
		// create the JSON string the func expects
		focusFolderPath = '[ "Home", "' + ieName + '"';

		var f = new String(focusFolder);
		var fEx = f.split('.');

		for(i=0; i<fEx.length; i++) {
			focusFolderPath += ', "' + fEx[i] +'"'
		}

		focusFolderPath += ']';

		YAHOO.namespace('frameFolders').selectednode = SUGAR.email2.folders.getNodeFromMboxPath(focusFolderPath);
		SUGAR.email2.listView.populateListFrame(YAHOO.namespace('frameFolders').selectednode, ieId, 'true');
	},

	/**
	 *
	 */
	sendEmailCleanUp : function(o) {
        var ret;
        SUGAR.hideMessageBox();

        try {
            ret = YAHOO.lang.JSON.parse(o.responseText);
            SUGAR.email2.composeLayout.forceCloseCompose(ret.composeLayoutId);
            //SUGAR.email2.addressBook.showContactMatches(ret.possibleMatches);
        } catch(err) {
            if (o.responseText) {
                SUGAR.showMessageBox(mod_strings.LBL_SEND_EMAIL_FAIL_TITLE, o.responseText, 'alert');
		    }
            // Else we have an error here.
        }        

		if (typeof(SE.grid) != 'undefined')
			SE.listView.refreshGrid();
		//Disabled while address book is disabled

		//If this call back was initiated by quick compose from a Detail View page, refresh the
		//history subpanel.  If it was initiated by quickcreate from shortcut bar, then
		//close the shortcut bar menu
		if ( (typeof(action_sugar_grp1) != 'undefined')) {
			if(action_sugar_grp1 == 'DetailView') {
				showSubPanel('history',null,true);
		  	} else if(action_sugar_grp1 == 'quickcreate') {
		  		closeEmailOverlay();
		  	}
		}

	},

	ieSendSuccess : function(o) {
		SUGAR.hideMessageBox();
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		if (ret.status == false) {
		    if (ret.errorMessage) {
		        SUGAR.showMessageBox(app_strings.LBL_EMAIL_TEST_OUTBOUND_SETTINGS, ret.errorMessage, 'plain');
		    }
		    else {
		        SUGAR.showMessageBox(app_strings.LBL_EMAIL_TEST_OUTBOUND_SETTINGS, app_strings.LBL_EMAIL_INVALID_SYSTEM_OUTBOUND, 'plain');
		    }
		}
		else {
		    SUGAR.showMessageBox(app_strings.LBL_EMAIL_TEST_OUTBOUND_SETTINGS_SENT, app_strings.LBL_EMAIL_TEST_NOTIFICATION_SENT, 'plain');
		}
	},

	/**
	 */
	settingsFolderRefresh : function(o) {
		//SUGAR.email2.accounts.rebuildFolderList(); // refresh frameFolder
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		var user = document.getElementById('userFolders');

		SUGAR.email2.util.emptySelectOptions(user);

		for(i=0; i<ret.userFolders.length; i++) {
			var display = ret.userFolders[i].name;
			var value = ret.userFolders[i].id;
			var selected = (ret.userFolders[i].selected != "") ? true : false;
			var opt = new Option(display, value, selected);
			opt.selected = selected;
			user.options.add(opt);
		}
	},

	/**
	 */
	startRequest : function(callback, args, forceAbort) {
		if(this.currentRequestObject != null) {
			if(this.forceAbort == true) {
				YAHOO.util.Connect.abort(this.currentRequestObject, null, false);
			}
		}
		this.currentRequestObject = YAHOO.util.Connect.asyncRequest('POST', "./index.php", callback, args);
		this._reset();
	},

	requestInProgress : function() {
		return (YAHOO.util.Connect.isCallInProgress(this.currentRequestObject));
	},

	/**
	 */
	updateFolderSubscriptions : function() {
		SUGAR.email2.folders.lazyLoadSettings(); // refresh view in Settings overlay
		SUGAR.email2.folders.setSugarFolders(1000);// refresh view in TreeView
		SUGAR.hideMessageBox();
	},

	/**
	 */
	updateFrameFolder : function() {
		SUGAR.email2.folders.checkEmailAccounts();
	},

	/**
	 */
	updateUserPrefs : function(o) {
		SUGAR.email2.userPrefs = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.email2.folders.startCheckTimer(); // starts the auto-check interval
	},

	/**
	 */
	uploadAttachmentSuccessful : function(o) {
		// clear out field
		document.getElementById('email_attachment').value = '';
		
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		ret.name = escape(ret.name);
		var idx = SUGAR.email2.composeLayout.currentInstanceId;
		var overall = document.getElementById('addedFiles' + idx);
		var index = overall.childNodes.length;
		var out =
			"<div id='email_attachment_bucket" + idx + index + "'>" +
				// remove button
				"<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=minus.gif' " +
					"style='cursor:pointer' align='absmiddle' onclick='SUGAR.email2.composeLayout.deleteUploadAttachment(\"" +
					idx + index + "\",\"" + ret.guid + ret.name + "\");'/>" +
				// file icon
				"<img src='index.php?entryPoint=getImage&themeName=" + SUGAR.themes.theme_name + "&imageName=attachment.gif' " +
					"id='email_attachmentImage'" + idx + index + "align='absmiddle' />" +
				// hidden id field
				"<input type='hidden' value='" + ret.guid + ret.name + "' name='email_attachment" + index + "' id='email_attachment" + idx + index + "' />" +
				// file name
				((ret.nameForDisplay != null) ? ret.nameForDisplay + "&nbsp;" : ret.name + "&nbsp;") +
				"<br/>" +
			"</div>";
		overall.innerHTML += out;
		if(SUGAR.email2.util.isIe()) {
			document.getElementById('addedFiles' + idx).innerHTML = document.getElementById('addedFiles' + idx).innerHTML;
		}

		// hide popup
		SUGAR.email2.addFileDialog.hide();
		// focus attachments
		SUGAR.email2.composeLayout.showAttachmentPanel(idx);
	}
};


///////////////////////////////////////////////////////////////////////////
////	PER MODULE CALLBACK OBJECTS
AjaxObject.accounts = {
	saveOutboundCleanup : function(o) {
		SUGAR.email2.accounts.refreshOuboundAccountTable();
		SUGAR.email2.accounts.outboundDialog.hide();
		var id = o.responseText;
		SUGAR.email2.accounts.newAddedOutboundId = id;
		},
	saveDefaultOutboundCleanup: function(o){

	},
	callbackEditOutbound : {
		success	: function(o)
		{
			var ret = YAHOO.lang.JSON.parse(o.responseText);
			// show overlay
			SUGAR.email2.accounts.showAddSmtp();

			// fill values
			document.getElementById("mail_id").value = ret.id;
			document.getElementById("type").value = ret.type;
			document.getElementById("mail_sendtype").value = ret.mail_sendtype;
			document.getElementById("mail_name").value = ret.name;
			document.getElementById("mail_smtpserver").value = ret.mail_smtpserver;
			document.getElementById("outboundEmailForm").mail_smtptype.value = ret.mail_smtptype;
			document.getElementById("mail_smtpport").value = ret.mail_smtpport;
			document.getElementById("mail_smtpuser").value = ret.mail_smtpuser;
			document.getElementById("mail_smtpauth_req").checked = (ret.mail_smtpauth_req == 1) ? true : false;
			SUGAR.email2.accounts.smtp_authenticate_field_display();
			document.getElementById("mail_smtpssl").options[ret.mail_smtpssl].selected = true;

            if(ret.type == 'system-override') {
			     SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(true);
			     SUGAR.email2.accounts.changeEmailScreenDisplay(ret.mail_smtptype,true);
            }
            else {
			     SUGAR.email2.accounts.toggleOutboundAccountDisabledFields(false);
			     SUGAR.email2.accounts.changeEmailScreenDisplay(ret.mail_smtptype,false);
            }
            SUGAR.util.setEmailPasswordDisplay('mail_smtppass', ret.has_password);

		},
		failure	: AjaxObject.handleFailure,
		timeout	: AjaxObject.timeout,
		scope	: AjaxObject
	},
	callbackDeleteOutbound : {
		success	: function(o) {
		    var ret = YAHOO.lang.JSON.parse(o.responseText);
		    if(ret.is_error)
		    {
		        if(confirm(ret.error_message))
                {
                    SUGAR.showMessageBox(app_strings.LBL_EMAIL_IE_DELETE, app_strings.LBL_EMAIL_ONE_MOMENT);
                    AjaxObject.startRequest(AjaxObject.accounts.callbackDeleteOutbound, urlStandard + "&emailUIAction=deleteOutbound&confirm=true&outbound_email=" + ret.outbound_email);
                }
                else
                    SUGAR.hideMessageBox();
		    }
		    else
		    {
			     SUGAR.hideMessageBox();
			     SUGAR.email2.accounts.refreshOuboundAccountTable();
		    }
		},

		failure	: AjaxObject.handleFailure,
		timeout	: AjaxObject.timeout,
		scope	: AjaxObject
	},

	callbackCheckMailProgress : {
	   success : function(o) {
	       if (typeof(SUGAR.email2.accounts.totalMsgCount) == "undefined") {
	           SUGAR.email2.accounts.totalMsgCount = -1;
	       }

	       //Check for server timeout / errors
	       var ret = YAHOO.lang.JSON.parse(o.responseText);
	       var done = false;

	       if (typeof(o.responseText) == 'undefined' || o.responseText == "" || ret == false) {
	           SUGAR.hideMessageBox();
	           SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, app_strings.LBL_EMAIL_ERROR_TIMEOUT, 'alert');
	           SUGAR.email2.accounts.totalMsgCount = -1;
               //SUGAR.email2.folders.rebuildFolders();
               done = true;
	       }

	       var currIeId = ret['ieid'];


	       var serverCount = ret.count;

	       if (ret['status'] == 'done') {
	           for(i=0; i < SUGAR.email2.accounts.ieIds.length; i++) {
	               if (i == SUGAR.email2.accounts.ieIds.length - 1) {
	                   //We are all done
	                   done = true;
	                   break;
	               } else if (SUGAR.email2.accounts.ieIds[i] == currIeId) {
	                   //Go to next account
	                   currIeId = SUGAR.email2.accounts.ieIds[i+1];
	                   ret.count = 0;
	                   SUGAR.email2.accounts.totalMsgCount = -1;
	                   break;
	               }
	           }
	       }
	       else if (ret.mbox && ret.totalcount && ret.count) {
	           SUGAR.email2.accounts.totalMsgCount = ret.totalcount;
	           if (ret.count >= ret.totalcount) {
	               serverCount = 0;
	           }
	       } else if (SUGAR.email2.accounts.totalMsgCount < 0 && ret.totalcount) {
	           SUGAR.email2.accounts.totalMsgCount = ret.totalcount;
	       } else {
		       SUGAR.hideMessageBox();
               SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, app_strings.LBL_EMAIL_ERROR_TIMEOUT, 'alert');
               SUGAR.email2.accounts.totalMsgCount = -1;
               done = true;
		   }

	       if (done) {
	           SUGAR.email2.accounts.totalMsgCount = -1;
	           SUGAR.hideMessageBox();
	           SUGAR.email2.folders.rebuildFolders();
	           SE.listView.refreshGrid();
	       } else if (SUGAR.email2.accounts.totalMsgCount < 0) {
               YAHOO.SUGAR.MessageBox.updateProgress(0, mod_strings.LBL_CHECKING_ACCOUNT + ' '+ (i + 2) + ' '+ mod_strings.LBL_OF + ' ' + SUGAR.email2.accounts.ieIds.length);
               AjaxObject.startRequest(AjaxObject.accounts.callbackCheckMailProgress, urlStandard +
                                '&emailUIAction=checkEmailProgress&ieId=' + currIeId + "&currentCount=0&synch=" + ret.synch);
           } else {
               YAHOO.SUGAR.MessageBox.updateProgress((ret.count / SUGAR.email2.accounts.totalMsgCount) * 100,
                   app_strings.LBL_EMAIL_DOWNLOAD_STATUS.replace(/\[\[count\]\]/, ret.count).replace(/\[\[total\]\]/, SUGAR.email2.accounts.totalMsgCount));
	           AjaxObject.startRequest(AjaxObject.accounts.callbackCheckMailProgress, urlStandard +
                   '&emailUIAction=checkEmailProgress&ieId=' + currIeId + "&currentCount=" + serverCount +
                   '&mbox=' + ret.mbox + '&synch=' + ret.synch + '&totalcount=' + SUGAR.email2.accounts.totalMsgCount);
	       }
	   },
	   failure : AjaxObject.handleFailure,
       timeout : AjaxObject.timeout,
       scope   : AjaxObject
	}
};

///////////////////////////////////////////////////////////////////////////////
////	COMPOSE LAYOUT
AjaxObject.composeLayout = {
	/**
	 * Populates the record id
	 */
    saveDraftCleanup : function(o) {
        var ret;
        SUGAR.hideMessageBox();

        try {
            ret = YAHOO.lang.JSON.parse(o.responseText);
            SUGAR.email2.composeLayout.forceCloseCompose(ret.composeLayoutId);
        } catch(err) {
            if (o.responseText) {
                SUGAR.showMessageBox(mod_strings.LBL_ERROR_SAVING_DRAFT, o.responseText, 'alert');
            }
        }
    }
};

AjaxObject.composeLayout.callback = {
	saveDraft : {
		success	: AjaxObject.composeLayout.saveDraftCleanup,
		failure	: AjaxObject.handleFailure,
		timeout	: AjaxObject.timeout,
		scope	: AjaxObject
	}
};

AjaxObject.detailView = {
	/**
	 * Pops-up a printable view of an email
	 */
	displayPrintable : function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		var displayTemplate = new YAHOO.SUGAR.Template(SUGAR.email2.templates['viewPrintable']);
		// 2 below must be in global context
		meta = ret.meta;
		meta['panelId'] = SUGAR.email2.util.getPanelId();
		email = ret.meta.email;
		if (typeof(email.cc) == 'undefined') {
		  email.cc = "";
		}

		var out = displayTemplate.exec({
			'app_strings'	: app_strings,
			'theme'			: theme,
			'idx'			: 'Preview',
			'meta'			: meta,
			'email'			: meta.email
		});

		// open popup window
		var popup = window.open('modules/Emails/templates/_blank.html', 'printwin' ,
		    'scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');

		popup.document.write(out);
		popup.document.close();
	},

	/**
	 * Takes formatted response and creates a modal pop-over containing a title and content
	 */
	displayView : function(o) {
		var SED = SUGAR.email2.detailView;
		var ret = YAHOO.lang.JSON.parse(o.responseText);

		if(!SED.viewDialog) {
			SED.viewDialog = new YAHOO.widget.Dialog("viewDialog", {
				modal:true,
            	visible:true,
            	fixedcenter:true,
            	constraintoviewport: true,
				shadow	: true
			});
            SED.viewDialog.renderEvent.subscribe(function() {
            	var content = this.body.firstChild;
            	var viewH = YAHOO.util.Dom.getViewportHeight();
            	if (content) {
            		this.body.style.overflow = "auto";
            		this.body.style.width = "800px";
            		this.body.style.height = (viewH - 75 > content.clientHeight ? (content.clientHeight) : (viewH - 75)) + "px";
            	}
            }, SED.viewDialog);
		} // end lazy load
		SED.viewDialog.setHeader(ret.title);
		SED.viewDialog.setBody(ret.html);
		SED.viewDialog.render();
		SED.viewDialog.show();
	},

	/**
	 * Generates a modal popup to populate with the contents of bean's full EditView
	 */
	showQuickCreateForm : function(o) {
		var SED = SUGAR.email2.detailView;
		var ret = YAHOO.lang.JSON.parse(o.responseText);

		if(!SED.quickCreateDialog) {
			SED.quickCreateDialog = new YAHOO.widget.Dialog("quickCreateForEmail", {
				modal:true,
				visible:true,
            	fixedcenter:true,
            	constraintoviewport: true,
				shadow	: true
			});

            SED.quickCreateDialog.renderEvent.subscribe(function() {
            	var viewH = YAHOO.util.Dom.getViewportHeight();
            	var contH = 0;
            	for (var i in this.body.childNodes) {
            		if (this.body.childNodes[i].clientHeight) {
            			contH += this.body.childNodes[i].clientHeight;
            		} else if (this.body.childNodes[i].offsetHeight) {
            			contH += this.body.childNodes[i].offsetHeight;
            		} // if
            	}
        		this.body.style.width = "800px";
        		this.body.style.height = (viewH - 75 > contH ? (contH + 10) : (viewH - 75)) + "px";
        		this.body.style.overflow = "auto";
            }, SED.quickCreateDialog);

            // dialog contents may override current drag-n-drop mode
            // so restore it when the dialog is hidden (bug #49330)
            var mode = YAHOO.util.DDM.mode;
            SED.quickCreateDialog.hideEvent.subscribe(function() {
                YAHOO.util.DDM.mode = mode;
            });
            SED.quickCreateDialog.setHeader(app_strings.LBL_EMAIL_QUICK_CREATE);
		} // end lazy load
		if (ret.html) {
			ret.html = ret.html.replace('<script type="text/javascript" src="include/SugarEmailAddress/SugarEmailAddress.js"></script>', "");
		}
		SED.quickCreateDialog.setBody(ret.html ? ret.html : "&nbsp;");
		SED.quickCreateDialog.render();
		SUGAR.util.evalScript(ret.html + '<script language="javascript">enableQS(true);</script>');

		SED.quickCreateDialog.ieId = ret.ieId;
		SED.quickCreateDialog.uid = ret.uid;
        SED.quickCreateDialog.mbox = ret.mbox;
        SED.quickCreateDialog.qcmodule = ret.module;

        SED.quickCreateDialog.show();

		var editForm = document.getElementById('form_EmailQCView_' + ret.module);
		if (editForm) {
		  editForm.module.value = 'Emails';
		  var count = 0;
          if (SUGAR.EmailAddressWidget.count[ret.module]) {
              count = SUGAR.EmailAddressWidget.count[ret.module] - 1;
          }
          var tableId = YAHOO.util.Dom.getElementsByClassName('emailaddresses', 'table', editForm)[0];
          tableId = tableId ? tableId.id : tableId;
		  var instId = ret.module + count;
		  SED.quickCreateEmailsToAdd = ret.emailAddress;
		  SED.quickCreateEmailCallback = function(instId, tableId) {
              //try to fill up the email address if and only if emailwidget is existed in the form
              if(tableId) {
                  var eaw = SUGAR.EmailAddressWidget.instances[instId];
                  if (eaw) {
                      eaw.prefillEmailAddresses(tableId, SUGAR.email2.detailView.quickCreateEmailsToAdd);
                  } else {
                      window.setTimeout(function() {
                          SUGAR.email2.detailView.quickCreateEmailCallback(instId, tableId);
                      }, 100);

                  }
              }
		  }
		  window.setTimeout(function() {
              SUGAR.email2.detailView.quickCreateEmailCallback(instId, tableId);
            }, 100);
		}
	},

	saveQuickCreateForm : function(o) {
	    SUGAR.hideMessageBox();
		SUGAR.email2.detailView.quickCreateDialog.hide();
		validate['EditView'] = [ ];
	},

	saveQuickCreateFormAndReply : function(o) {
        SUGAR.hideMessageBox();
        var ret = YAHOO.lang.JSON.parse(o.responseText);
        SUGAR.email2.detailView.quickCreateDialog.hide();
        var qcd = SUGAR.email2.detailView.quickCreateDialog;
        var type = (qcd.qcmodule == 'Cases') ? 'replyCase' : 'reply';
        if (ret) {
            var emailID = ret.id;
            SUGAR.email2.composeLayout.c0_replyForwardEmail(null, ret.id, 'sugar::Emails', type);
        } else {
            SUGAR.email2.composeLayout.c0_replyForwardEmail(qcd.ieId, qcd.uid, qcd.mbox, type);
        }
        //Cean the validate cache to prevent errors on the next call
        validate['EditView'] = [ ];
    },

	saveQuickCreateFormAndAddToAddressBook : function(o) {
	   SUGAR.hideMessageBox();
		SUGAR.email2.detailView.quickCreateDialog.hide();
		SUGAR.email2.complexLayout.findPanel('contactsTab').show();
		validate['EditView'] = [ ];
	},

	handleAssignmentDialogAssignAction : function() {


	    var assign_user_id = window.document.forms['Distribute'].elements['assigned_user_id'].value;

	    var dist = 'direct';
	    var users = false;
	    var rules = false;
	    var get = "";
	    var found_teams = false;
	    var warning_message = mod_strings.LBL_WARN_NO_USERS;
	    //Ensure a valid team selection.
	    if( typeof validate['Distribute'] == 'undefined' )
	       addToValidate('Distribute', 'team_name', 'teamset_mass', false, 'Team');

	   formValidTeamSet = validate_form('Distribute','');
	   if(!formValidTeamSet)
	       return false;

	    var warning_message = mod_strings.LBL_WARN_NO_USERS_OR_TEAM;
	    var teamIdsArray = SUGAR.collection.prototype.getTeamIdsfromUI('Distribute', 'team_name');
	    if (teamIdsArray != null && teamIdsArray.length > 0)   {
	        var team_ids = teamIdsArray.join(",");
	        var primary_team = SUGAR.collection.prototype.getPrimaryTeamidsFromUI('Distribute', 'team_name');
	        if( primary_team != '' || team_ids.replace(',',"") != '')
	        found_teams = true;

	        get = get + "&team_ids=" + team_ids + "&primary_team_id=" + primary_team;
	    }
	    //Determine what type of team update the user wants to perform.
	    var el = window.document.forms['Distribute'].elements['team_name_type'];
	    var teamUpdateType;
	    for(var j=0;j<el.length;j++)
	    {
	        if(el[j].checked)
	           teamUpdateType = el[j].value;
	    }
	    get = get + "&team_update_type=" + teamUpdateType;
	    if(!found_teams && assign_user_id == '' )
	    {
	        alert(warning_message);
	        return;
	    }

	    var emailUids = SUGAR.email2.listView.getUidsFromSelection();
	    var uids = "";
	    for(i=0; i<emailUids.length; i++) {
	        if(uids != '') {
	            uids += app_strings.LBL_EMAIL_DELIMITER;
	        }
	        uids += emailUids[i];
	    }

	    var row = SUGAR.email2.grid.getSelectedRows()[0];
	    var data = SUGAR.email2.grid.getRecord(row).getData();
	    var ieid = data.ieId;
	    var mbox = data.mbox;
	    AjaxObject.startRequest(callbackAssignmentAction, urlStandard + '&emailUIAction=' + "doAssignmentAssign&uids=" + uids + "&ieId=" + ieid + "&folder=" + mbox + "&distribute_method=" + dist + "&users=" +assign_user_id + get);
	    SUGAR.email2.contextMenus.assignToDialogue.hide();
	    SUGAR.showMessageBox('Assignment', app_strings.LBL_EMAIL_ONE_MOMENT);

	},

	handleAssignmentDialogDeleteAction : function() {
		// TO pass list of UIDS/emailIds
		var uids = SUGAR.email2.listView.getUidsFromSelection();
		var row = SUGAR.email2.grid.getSelections()[0];
		var ieid = row.data.ieId;
	    var mbox = row.data.mbox;
        AjaxObject.startRequest(callbackAssignmentAction, urlStandard + '&emailUIAction=' + "doAssignmentDelete&uids=" + uids + "&ieId=" + ieId + "&folder=" + mbox);
        SUGAR.email2.contextMenus.assignmentDialog.hide();
		SUGAR.showMessageBox(app_strings.LBL_EMAIL_PERFORMING_TASK, app_strings.LBL_EMAIL_ONE_MOMENT);

		// AJAX Call

	},

	showEmailDetailView : function(o) {
        SUGAR.hideMessageBox();
        var SED = SUGAR.email2.detailView;
		var ret = YAHOO.lang.JSON.parse(o.responseText);

		if(!SED.quickCreateDialog) {
			SED.quickCreateDialog = new YAHOO.widget.Dialog("emailDetailDialog", {
				modal:true,
				visible:false,
            	//fixedcenter:true,
            	constraintoviewport: true,
            	draggable: true,
				autofillheight: "body",
				shadow	: true
			});
			SED.quickCreateDialog.renderEvent.subscribe(function() {
            	var viewHeight = YAHOO.util.Dom.getViewportHeight();
            	var contH = 0;
            	for (var i in this.body.childNodes) {
            		if (this.body.childNodes[i].offsetHeight)
            			contH += this.body.childNodes[i].offsetHeight;
            	}
        		this.body.style.overflow = "auto";
        		this.body.style.width = "800px";
        		this.body.style.height = (viewHeight - 75 > contH ? (contH + 10) : (viewHeight - 75)) + "px";
        		this.center();
            }, SED.quickCreateDialog);

            // dialog contents may override current drag-n-drop mode
            // so restore it when the dialog is hidden (bug #49330)
            var mode = YAHOO.util.DDM.mode;
            SED.quickCreateDialog.hideEvent.subscribe(function() {
                YAHOO.util.DDM.mode = mode;
            });
		}
		SED.quickCreateDialog.setHeader(app_strings.LBL_EMAIL_RECORD);
		SED.quickCreateDialog.setBody(ret.html);
		SED.quickCreateDialog.render();
        SUGAR.util.evalScript(ret.html);
        SED.quickCreateDialog.show();
	},

	showAssignmentDialogWithData : function(o) {
        var SEC = SUGAR.email2.contextMenus;
		SUGAR.hideMessageBox();
        var ret = YAHOO.lang.JSON.parse(o.responseText);
        if (!SEC.assignmentDialog) {
	        SEC.assignmentDialog = new YAHOO.widget.Dialog("assignmentDialog", {
	        	visible:false,
            	fixedcenter:true,
            	constraintoviewport: true,
	        	modal   : true
	        });
	        SEC.assignmentDialog.setBody("");
	        SEC.assignmentDialog.setHeader(app_strings.LBL_EMAIL_ASSIGNMENT);
	        SEC.assignmentDialog.renderEvent.subscribe(function() {
            	var iev = YAHOO.util.Dom.get("Distribute");
            	if (iev) {
            		this.body.style.width = "700px";
            	}
            },  SEC.assignmentDialog);
	        SEC.assignmentDialog.render();
        }
        SEC.assignmentDialog.setBody(ret);
        SEC.assignmentDialog.render();
        validate = [];
        SEC.assignmentDialog.show();
        SUGAR.util.evalScript(ret);
	},

	showImportForm : function(o) {
		var SED = SUGAR.email2.detailView;
		var ret = YAHOO.lang.JSON.parse(o.responseText);
        
        document.getElementById('quickCreateContent').innerHTML = "";
        SUGAR.hideMessageBox();
        if (!ret) {
            return false;
        }

        if(!SED.importDialog) {
            SED.importDialog = new YAHOO.widget.Dialog("importDialog", {
            	modal:true,
            	visible:false,
            	fixedcenter:true,
            	constraintoviewport: true,
                buttons : [{
                	text: app_strings.LBL_EMAIL_ARCHIVE_TO_SUGAR, isDefault: true, handler: function(){
                		AjaxObject.detailView.getImportAction(SED.importDialog.ret); }
                }]//,
                //scroll : true
            });
            SED.importDialog.setHeader(app_strings.LBL_EMAIL_IMPORT_SETTINGS);
            SED.importDialog.setBody("");
            SED.importDialog.hideEvent.subscribe(function(){
            	for(var i in QSFieldsArray) {
            		if (QSFieldsArray[i] != null && typeof(QSFieldsArray[i]) == "object") {
	            		QSFieldsArray[i].destroy();
	            		delete QSFieldsArray[i];
            		}
            		if (QSProcessedFieldsArray[i]) {
            			QSProcessedFieldsArray[i] = false;
            		} // if
				}
            });
            SED.importDialog.renderEvent.subscribe(function() {
            	var iev = YAHOO.util.Dom.get("ImportEditView");
            	if (iev) {
            		//this.body.style.height = (iev.clientHeight + 10) + "px";
            		this.body.style.width = "600px";
            	}
            }, SED.importDialog);
            SED.importDialog.render();
        } // end lazy load
        SED.importDialog.setBody(ret.html);
        SED.importDialog.ret = ret;
        SUGAR.util.evalScript(ret.html);
        SED.importDialog.render();
        validate = [];
        SED.importDialog.show();
        SED.importDialog.focusFirstButton();
    },
    getImportAction : function(ret) {
        if (!check_form('ImportEditView')) return false;
        if (!SUGAR.collection.prototype.validateTemSet('ImportEditView', 'team_name')) {
        	alert(mod_strings.LBL_EMAILS_NO_PRIMARY_TEAM_SPECIFIED);
        	return false;
        } // if
		var get = "";
        var editView = document.getElementById('ImportEditView');
        var teamIdsArray = SUGAR.collection.prototype.getTeamIdsfromUI('ImportEditView', 'team_name');
		if (teamIdsArray != null && teamIdsArray.length > 0)   {
            get = get + "&team_ids=" + teamIdsArray.join(",") + "&primary_team_id=" + SUGAR.collection.prototype.getPrimaryTeamidsFromUI('ImportEditView', 'team_name');
            //var team_id = editView.team_id.value;
        }
        if (editView.assigned_user_id != null) {
            get = get + "&user_id=" + editView.assigned_user_id.value
            //var user_id = editView.assigned_user_id.value;
        }
        var parent_id = editView.parent_id.value;
        var parent_type = editView.parent_type.value;
        var row = SUGAR.email2.grid.getSelectedRows()[0];
        row = SUGAR.email2.grid.getRecord(row);
        var data = row.getData();
        var ieId = data.ieId;
        var mbox = data.mbox;
        var serverDelete = editView.serverDelete.checked;
        var emailUids = SUGAR.email2.listView.getUidsFromSelection();
        var uids = "";
        for(i=0; i<emailUids.length; i++) {
            if(uids != '') {
                uids += app_strings.LBL_EMAIL_DELIMITER;
            }
            uids += emailUids[i];
        }

        var action = 'importEmail&uid=';
        if (ret.move) {
            action = 'moveEmails';
            action = action + '&sourceFolder=' + ret['srcFolder'];
            action = action + '&sourceIeId=' + ret['srcIeId'];
            action = action + '&destinationFolder=' + ret['dstFolder'];
            action = action + '&destinationIeId=' + ret['dstIeId'];
            action = action + '&emailUids=';
        }
        if (action.search(/importEmail/) != -1) {
            SUGAR.showMessageBox(app_strings.LBL_EMAIL_IMPORTING_EMAIL, app_strings.LBL_EMAIL_ONE_MOMENT);
        } else {
            SUGAR.showMessageBox("Moving Email(s)", app_strings.LBL_EMAIL_ONE_MOMENT);
        }

        AjaxObject.startRequest(callbackStatusForImport, urlStandard + '&emailUIAction=' + action + uids + "&ieId=" + ieId + "&mbox=" + mbox +
        get + "&parent_id=" + parent_id + "&parent_type=" + parent_type + '&delete=' + serverDelete);
        SUGAR.email2.detailView.importDialog.hide();
        document.getElementById('importDialogContent').innerHTML = "";

    },
    showRelateForm : function(o) {
        var SED = SUGAR.email2.detailView;
    	var ret = YAHOO.lang.JSON.parse(o.responseText);
        document.getElementById('quickCreateContent').innerHTML = "";
        SUGAR.hideMessageBox();
        if (!ret) {
            return false;
        }
        dialog_loaded = true;

        if(!SED.relateDialog) {
            SED.relateDialog = new YAHOO.widget.Dialog('relateDialog', {
				modal:true,
				visible:true,
            	fixedcenter:true,
            	width: '800px',
            	constraintoviewport: true,
				buttons : [{
                	text: app_strings.LBL_EMAIL_RELATE_TO, isDefault: true, handler: function(){
    					if (!check_form('RelateEditView')) return false;
    					var get = "";
    	                var editView = document.getElementById('RelateEditView');
    	                var parent_id = editView.parent_id.value;
    	                var parent_type = editView.parent_type.value;
    	                var row = SUGAR.email2.grid.getSelectedRows()[0];
    	                row  = SUGAR.email2.grid.getRecord(row);
    	                var ieId = row.getData().ieId;
    	                var mbox = row.getData().mbox;
    	                var emailUids = SUGAR.email2.listView.getUidsFromSelection();
    	                var uids = "";
    	                for(i=0; i<emailUids.length; i++) {
    	                    if(uids != '') {
    	                        uids += app_strings.LBL_EMAIL_DELIMITER;
    	                    }
    	                    uids += emailUids[i];
    	                }
    	                SUGAR.showMessageBox(app_strings.LBL_EMAIL_PERFORMING_TASK, app_strings.LBL_EMAIL_ONE_MOMENT);
    	                AjaxObject.startRequest(callbackStatusForImport, urlStandard + '&emailUIAction=relateEmails&uid=' + uids
    	                    + "&ieId=" + ieId + "&mbox=" + mbox + "&parent_id=" + parent_id + "&parent_type=" + parent_type);
    	                SED.relateDialog.hide();
    	                document.getElementById('relateDialogContent').innerHTML = "";
	                }
                }]
			});

            SED.relateDialog.hideEvent.subscribe(function(){
            	if (QSFieldsArray['ImportEditView_parent_name'] != null) {
            		QSFieldsArray['ImportEditView_parent_name'].destroy();
            		delete QSFieldsArray['ImportEditView_parent_name'];
            	} // if
				if (QSProcessedFieldsArray['ImportEditView_parent_name']) {
					QSProcessedFieldsArray['ImportEditView_parent_name'] = false;
				} // if
            });

            SED.relateDialog.renderEvent.subscribe(function() {
            	var viewPortHeight = YAHOO.util.Dom.getViewportHeight();
            	var contH = 0;
            	for (var i in this.body.childNodes) {
            		if (this.body.childNodes[i].clientHeight)
            			contH += this.body.childNodes[i].clientHeight;
            	}
            }, SED.relateDialog);
            SED.relateDialog.setHeader(app_strings.LBL_EMAIL_RELATE_EMAIL);
		} // end lazy load

        SED.relateDialog.setBody(ret.html);
        SED.relateDialog.render();
        SUGAR.util.evalScript(ret.html);
        SED.relateDialog.show();
    }
};
/**
 * DetailView callbacks
 */
AjaxObject.detailView.callback = {
	emailDetail : {
		success	: function(o) {
			SUGAR.email2.o = o;
			var ret = YAHOO.lang.JSON.parse(o.responseText);
			SUGAR.email2.detailView.consumeMetaDetail(ret);
		},
		argument: [targetDiv],
		failure	: AjaxObject.handleFailure,
		timeout	: 0,
		scope	: AjaxObject
	},
	emailPreview : {
		success	: function(o) {
			SUGAR.email2.o = o;
			var ret = YAHOO.lang.JSON.parse(o.responseText);
			SUGAR.email2.detailView.consumeMetaPreview(ret);
		},
		failure	: AjaxObject.handleFailure,
		timeout	: 0,
		scope	: AjaxObject
	},
	viewPrint : {
		success	: AjaxObject.detailView.displayPrintable,
		failure	: AjaxObject.handleFailure,
		timeout	: AjaxObject.timeout,
		scope	: AjaxObject
	},
	viewRaw : {
		success	: AjaxObject.detailView.displayView,
		failure	: AjaxObject.handleFailure,
		timeout	: AjaxObject.timeout,
		scope	: AjaxObject
	}
};





AjaxObject.folders = {
	/**
	 * check-mail post actions
	 */
	checkMailCleanup : function(o) {
		SUGAR.hideMessageBox();
		AjaxObject.folders.rebuildFolders(o); // rebuild TreeView

		// refresh focus ListView
		SE.listView.refreshGrid();
		SUGAR.email2.folders.startCheckTimer(); // resets the timer
	},

	/**
	 */
	rebuildFolders : function(o) {
		SUGAR.hideMessageBox();

		var data = YAHOO.lang.JSON.parse(o.responseText);

		email2treeinit(SUGAR.email2.tree, data.tree_data, 'frameFolders', data.param);
		SUGAR.email2.folders.setSugarFolders();
	}
};
AjaxObject.folders.callback = {
	checkMail : {
		success	: AjaxObject.folders.checkMailCleanup,
		failure	: AjaxObject.handleFailure,
		timeout	: 600000, // 5 mins
		scope	: AjaxObject
	}
}

AjaxObject.rules = {
	loadRulesForSettings : function(o) {
		document.getElementById("rulesListCell").innerHTML = o.responseText;
		// assume we have the class we need
		SUGAR.routing.getStrings();
		SUGAR.routing.getDependentDropdowns();
	}
};
////	END PER MODULE CALLBACK OBJECTS
///////////////////////////////////////////////////////////////////////////


var callback = {
	success	: AjaxObject.handleSuccess,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackAccount = {
	success	: AjaxObject.ieSaveSuccess,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackAccountDelete = {
	success	: AjaxObject.ieDeleteSuccess,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackOutboundTest = {
	success	: AjaxObject.ieSendSuccess,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};



var callbackTeamInfoForSettings = {
success : function (o) {
	var data = YAHOO.lang.JSON.parse(o.responseText);
	document.getElementById('EditViewGroupFolderTeamTD').innerHTML = data.defaultgroupfolder;
},
failure	: AjaxObject.handleFailure,
timeout	: AjaxObject.timeout,
scope	: AjaxObject

};

var callbackStatusForImport = {
success : function (o) {
	SUGAR.hideMessageBox();
	if (o.responseText != "")  {
		var statusString = "";
		var data = YAHOO.lang.JSON.parse(o.responseText);
		for(i=0; i<data.length; i++) {
			statusString = statusString + data[i] + '<br/>';
		}
		SUGAR.showMessageBox(SUGAR.language.get('Emails','LBL_IMPORT_STATUS_TITLE'), statusString, 'alert');
	}
	SE.listView.refreshGrid();

},
failure	: AjaxObject.handleFailure,
timeout	: AjaxObject.timeout,
scope	: AjaxObject

};
var callbackComposeCache = {
	success	: AjaxObject.composeCache,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackDelete = {
	success	: AjaxObject.handleDeleteReturn,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackEmailDetailMultiple = {
	success	: function(o) {
		SUGAR.hideMessageBox();
        var retMulti = YAHOO.lang.JSON.parse(o.responseText);
		var ret = new Object();

		for(var i=0; i<retMulti.length; i++) {
			ret = retMulti[i];

			SUGAR.email2._setDetailCache(ret);
			SUGAR.email2.detailView.populateDetailView(ret.meta.uid, ret.meta.mbox, ret.meta.ieId, true, SUGAR.email2.innerLayout);
		}
	},
	failure	: AjaxObject.handleFailure,
	timeout	: 0,
	scope	: AjaxObject
};
var callbackListViewSortOrderChange = {
	success	: AjaxObject.saveListViewSortOrderPart2,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject,
	argument	: [ieId, ieName, focusFolder]
};
var callbackEmptyTrash = {
	success	: function(o) {
		SUGAR.hideMessageBox();
		AjaxObject.folderRenameCleanup;
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackClearCacheFiles = {
	success	: function(o) {
		SUGAR.hideMessageBox();
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolderRename = {
	success	: function(o) {SUGAR.hideMessageBox();SUGAR.email2.folders.rebuildFolders();},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolderDelete = {
	success	: function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);
		if (ret.status) {
		    if (ret.folder_id) {
		        var node = SUGAR.email2.folders.getNodeFromId(ret.folder_id);
		        if(node)
		        	SUGAR.email2.tree.removeNode(node, true);
		    } else if (ret.ieId && ret.mbox) {
		        var node = SUGAR.email2.folders.getNodeFromIeIdAndMailbox(ret.ieId, ret.mbox);
		        if(node)
		        	SUGAR.email2.tree.removeNode(node, true);
		    }
			SUGAR.hideMessageBox();
			//SUGAR.email2.folders.loadSettingFolder();
		} else {
			SUGAR.hideMessageBox();
			SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, ret.errorMessage, 'alert');
		} // else
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolderSave = {
	success	: function(o) {
		var ret = YAHOO.lang.JSON.parse(o.responseText);

		switch(ret.action) {
			case 'newFolderSave':
				SUGAR.email2.folders.rebuildFolders();
			break;
		}
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolderSubscriptions = {
	success	: AjaxObject.updateFolderSubscriptions,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolderUpdate = {
	success	: AjaxObject.updateFrameFolder,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFolders = {
	success	: AjaxObject.folders.rebuildFolders,
	//success : void(true),
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackFullSync = {
	success	: AjaxObject.fullSyncCleanup,
	failure	: AjaxObject.handleFailure,
	timeout	: 9999999999999,
	scope	: AjaxObject
};
var callbackGeneric = {
	success	: function() {
		SUGAR.hideMessageBox();
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackIeAccountRetrieve = {
	success	: function(o) {
		// return JSON encoding
		SUGAR.hideMessageBox();
		SUGAR.email2.accounts.fillIeAccount(o.responseText);
		SUGAR.email2.accounts.showEditInboundAccountDialogue(false);
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackImportOneEmail = {
	success :  AjaxObject.detailView.showImportForm,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackRelateEmail = {
    success : AjaxObject.detailView.showRelateForm,
    failure : AjaxObject.handleFailure,
    timeout : AjaxObject.timeout,
    scope   : AjaxObject
}
var callbackEmailDetailView = {
    success : AjaxObject.detailView.showEmailDetailView,
    failure : AjaxObject.handleFailure,
    timeout : AjaxObject.timeout,
    scope   : AjaxObject
}
var callbackAssignmentDialog = {
	success :  AjaxObject.detailView.showAssignmentDialogWithData,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackAssignmentAction = {
	success :  function(o) {
		SE.listView.refreshGrid();
		SUGAR.hideMessageBox();
		if(o.responseText != '') {
	       SUGAR.showMessageBox(mod_strings.LBL_ASSIGNMNT_ACT_RESULT, o.responseText, 'alert');
	    } // if
	} ,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackMoveEmails = {
	success :  function(o) {
	    SE.listView.refreshGrid();
		SUGAR.hideMessageBox();
		if(o.responseText != '') {
	       SUGAR.showMessageBox(app_strings.LBL_EMAIL_ERROR_DESC, o.responseText, 'alert');
	    } // if
	} ,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackLoadAttachments = {
	success	: AjaxObject.loadAttachments,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackLoadRules = {
	success	: AjaxObject.rules.loadRulesForSettings,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackLoadSignature = {
	success	: AjaxObject.loadSignature,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackDeleteSignature = {
	success	: AjaxObject.handleDeleteSignature,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
/*var callbackMoveEmails = {
    success : function(o) { SUGAR.email2.listView.moveEmailsCleanup(o) },
    failure : AjaxObject.handleFailure,
    timeout : AjaxObject.timeout,
    scope   : AjaxObject
}*/
var callbackOutboundSave = {
	success	: AjaxObject.accounts.saveOutboundCleanup,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackDefaultOutboundSave = {
	success	: AjaxObject.accounts.saveDefaultOutboundCleanup,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackQuickCreate = {
	success	: AjaxObject.detailView.showQuickCreateForm,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackQuickCreateSave = {
	success	: AjaxObject.detailView.saveQuickCreateForm,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackQuickCreateSaveAndAddToAddressBook = {
	success	: AjaxObject.detailView.saveQuickCreateFormAndAddToAddressBook,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackQuickCreateSaveAndReply = {
    success : AjaxObject.detailView.saveQuickCreateFormAndReply,
    failure : AjaxObject.handleFailure,
    timeout : AjaxObject.timeout,
    scope   : AjaxObject
}
var callbackQuickCreateSaveAndReplyCase = {
    success : AjaxObject.detailView.saveQuickCreateFormAndReplyCase,
    failure : AjaxObject.handleFailure,
    timeout : AjaxObject.timeout,
    scope   : AjaxObject
}
var callbackRebuildShowAccountList = {
	success	: AjaxObject.rebuildShowFolders,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};

var callbackRefreshSugarFolders = {
	success	: function(o) {
		var t = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.email2.folders.setSugarFoldersEnd(t);
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackReplyForward = {
	success	: AjaxObject.handleReplyForward,
	finish : function(a, retryCount,isReOrFwDraft) {
		if (typeof(retryCount) == 'undefined') {
			retryCount = 0;
		} else {
			retryCount++;
		}
		if (typeof(isReOrFwDraft) == 'undefined') {
			isReOrFwDraft = 0;
		}
		var idx = SUGAR.email2.composeLayout.currentInstanceId;
		var t = tinyMCE.getInstanceById('htmleditor' + idx);
        try {
			var html = t.getContent();

            html = "&nbsp;";
            //add hr tag if this is not a reply draft or forward draft
            if(!isReOrFwDraft){
                html += "<div><hr></div>";
            }
            html +=  a.description;

			t.setContent(html);//

            if (a.type != 'draft') {
                // Next step, attach signature
                SUGAR.email2.composeLayout.resizeEditorSetSignature(idx,true);
            }

		} catch(e) {
			if (retryCount < 5) {
				setTimeout("callbackReplyForward.finish(globalA, " + retryCount + ");", 500);
				return;
			}
		}
		var tabArray = SUGAR.email2.innerLayout.get("tabs");
		if (tabArray != null && tabArray.length > 0) {
			for (i = 0 ; i < tabArray.length ; i++) {
				var tabObject = tabArray[i];
				if (tabObject.get("id") == ("composeTab" + idx)) {
					var tabLabel = a.name;
			        if (tabLabel != null && tabLabel.length > 25) {
			        	tabLabel = tabLabel.substring(0, 25) + "...";
			        } // if
					tabObject.get("labelEl").firstChild.data = tabLabel;
					break;
				}
			}
		}

		//SUGAR.email2.innerLayout.regions.center.getPanel('composeLayout' + idx).setTitle(a.name);
		if (a.parent_name != null && a.parent_name != "") {
			document.getElementById('data_parent_name' + idx).value = a.parent_name;
		}
		if (a.parent_type != null && a.parent_type != "") {
			document.getElementById('data_parent_type' + idx).value = a.parent_type;
		}
		if (a.parent_id != null && a.parent_id != "") {
			document.getElementById('data_parent_id' + idx).value = a.parent_id;
		}
		if (a.fromAccounts.status) {
			var addressFrom = document.getElementById('addressFrom' + idx);
	        SUGAR.email2.util.emptySelectOptions(addressFrom);
	        var fromAccountOpts = a.fromAccounts.data;
	        for(i=0; i<fromAccountOpts.length; i++) {
	              var key = fromAccountOpts[i].value;
	              var display = fromAccountOpts[i].text;
	              var opt = new Option(display, key);
	              if (fromAccountOpts[i].selected) {
	              	opt.selected = true;
	              }
	              addressFrom.options.add(opt);
	        }
		} // if
		SUGAR.hideMessageBox();

	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject,
	argument	: [sendType]
};
var callbackSendEmail = {
	success	: AjaxObject.sendEmailCleanUp,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackSettings = {
	success	: AjaxObject.updateUserPrefs,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackSettingsFolderRefresh = {
	success	: AjaxObject.settingsFolderRefresh,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackLoadSettingFolder = {
	success	: function(o) {
		AjaxObject.settingsFolderRefresh(o);
		SUGAR.email2.accounts.rebuildFolderList(); // refresh frameFolder
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject

};
var callbackUploadAttachment = {
	success	: AjaxObject.uploadAttachmentSuccessful,
	upload	: AjaxObject.uploadAttachmentSuccessful,
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};
var callbackUserPrefs = {
	success	: function(o) {
		SUGAR.email2.userPrefs = YAHOO.lang.JSON.parse(o.responseText);
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};

var callbackContextmenus = {
	markUnread : {
		success : AjaxObject.markEmailCleanup,
		failure : AjaxObject.handleFailure,
		timeout : AjaxObject.timeout,
		scope   : AjaxObject
	}
};
var updateMailingLists = {
	success	: function(o) {
		SUGAR.email2.userPrefs.emailLists = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.email2.addressBook.setLists();
	},
	failure	: AjaxObject.handleFailure,
	timeout	: AjaxObject.timeout,
	scope	: AjaxObject
};

var callbackCheckEmail2 = {
	success : function(o) {
        var ret = YAHOO.lang.JSON.parse(o.responseText);
		SUGAR.showMessageBox(app_strings.LBL_EMAIL_CHECKING_NEW, ret.text);


	},
	failure : AjaxObject.handleFailure,
	timeout : AjaxObject.timeout,
	scope	: AjaxObject
}

/* End of File modules/Emails/javascript/ajax.js */

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
function gridInit() {
	if(SUGAR.email2.grid) {
		SUGAR.email2.grid.destroy();
	}

	e2Grid = {
		init : function() {

			var Ck = YAHOO.util.Cookie;
			var widths = [ 10, 10, 150, 250, 175, 125 ];

			if (Ck.get("EmailGridWidths")) {
				for (var i=0; i < widths.length; i++) {
					widths[i] = Ck.getSub("EmailGridWidths", i+ "", Number);
				}
			} else {
				for (var i=0; i < widths.length; i++) {
					Ck.setSub("EmailGridWidths", i + "", widths[i], {expires: SUGAR.email2.nextYear});
				}
			}

			// changes "F" to an icon
			function flaggedIcon(cell, record, column, value) {
				if(value != "") {
					cell.innerHTML = "<span style='color: #f00; font-weight:bold;'>!</span>";
				}
			}
			// changes "A" to replied icon
			function repliedIcon(cell, record, column, value) {
				if(value != "") {
					cell.innerHTML = "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=export.gif' class='image' border='0' width='10' align='absmiddle'>";
				}
			}
	        function attachIcon(cell, record, column, value) {
				if(value == "1") {
					cell.innerHTML = "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=attachment.gif' class='image' border='0' width='10' align='absmiddle'>";
				}
			}

			var colModel =
				[
					{
						label: "<h2><img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=attachment.gif' class='image' border='0' width='10' align='absmiddle'></h2>",
						width: 10,
						sortable: false,
						fixed: true,
						resizeable: true,
						formatter: attachIcon,
						key: 'hasAttach'
					},
				    {
						label: "<span style='color: #f00; font-weight:bold;'>!</span>",
						width: widths[0],
						sortable: true,
						fixed: true,
						resizeable: true,
						formatter: flaggedIcon,
						key: 'flagged'
					},
					{
						label: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=export.gif' class='image' border='0' width='10' align='absmiddle'>",
						width: widths[1],
						sortable: true,
						fixed: true,
						resizeable: true,
						formatter: repliedIcon,
						key: 'status'
					},
					{
						label: app_strings.LBL_EMAIL_FROM,
						width: widths[2],
						sortable: true,
						resizeable: true,
						key: 'from'
					},
					{
						label: app_strings.LBL_EMAIL_SUBJECT,
						width: widths[3],
						sortable: true,
						resizeable: true,
						key: 'subject'
					},
					{
						label: mod_strings.LBL_LIST_DATE,
						width: widths[4],
						sortable: true,
						resizeable: true,
                        key: 'date'
					},
					{
						label: app_strings.LBL_EMAIL_TO,
						width: widths[5],
						sortable: false,
						resizeable: true,
                        key: 'to_addrs'
					},
					{
						label: 'uid',
						hidden: true,
                        key: 'uid'
					},
					{
						label: 'mbox',
						hidden: true,
                        key: 'mbox'
					},
					{
						label: 'ieId',
						hidden: true,
                        key: 'ieId'
					},
					{
						label: 'site_url',
						hidden: true,
                        key: 'site_url'
					},
					{	label: 'seen',
						hidden: true,
                        key: 'seen'
					},
					{	label: 'type',
						hidden: true,
                        key: 'type'
					}
				];

			var dataModel = new YAHOO.util.DataSource(urlBase + "?", {
				responseType: YAHOO.util.DataSource.TYPE_JSON,
				responseSchema: {
				    resultsList: 'Email',
		            fields: ['flagged', 'status', 'from', 'subject', 'date','to_addrs', 'uid', 'mbox', 'ieId', 'site_url', 'seen', 'type', 'AssignedTo','hasAttach'],
		            metaFields: {total: 'TotalCount', unread:"UnreadCount", fromCache: "FromCache"}
				}
		    });
			var params = {
					to_pdf : "true",
					module : "Emails",
					action : "EmailUIAjax",
					emailUIAction : "getMessageList",
					mbox : "INBOX",
					ieId : "",
					forceRefresh : "false"
			};
			if(lazyLoadFolder != null) {
				params['mbox'] = lazyLoadFolder.folder;
				params['ieId'] = lazyLoadFolder.ieId;
				//Check if the folder is a Sugar Folder
				var test = new String(lazyLoadFolder.folder);
				if(test.match(/SUGAR\./)) {
					params['emailUIAction'] = 'getMessageListSugarFolders';
					params['mbox'] = test.substr(6);
				}
			}
			//dataModel.initPaging(urlBase, SUGAR.email2.userPrefs.emailSettings.showNumInList);

			// create the Grid
			var grid = SUGAR.email2.grid = new YAHOO.SUGAR.SelectionGrid('emailGrid', colModel, dataModel, {
				MSG_EMPTY: SUGAR.language.get("Emails", "LBL_EMPTY_FOLDER"),
				dynamicData: true,
				paginator: new YAHOO.widget.Paginator({
					rowsPerPage:parseInt(SUGAR.email2.userPrefs.emailSettings.showNumInList),
					containers : ["dt-pag-nav"],
					template: "<div class='pagination'>{FirstPageLink} {PreviousPageLink} {PageLinks} {NextPageLink} {LastPageLink}</div>",
					firstPageLinkLabel: 	"<button class='button'><div class='paginator-start'/></button>",
					previousPageLinkLabel: 	"<button class='button'><div class='paginator-previous'/></button>",
					nextPageLinkLabel: 		"<button class='button'><div class='paginator-next'/></button>",
					lastPageLinkLabel: 		"<button class='button'><div class='paginator-end'/></button>"
				}),
                initialRequest:SUGAR.util.paramsToUrl(params),
				width:  "800px",
				height: "400px"
			});

			initRowDD();

			//Override Paging request construction
			grid.set("generateRequest", function(oState, oSelf) {
	            oState = oState || {pagination:null, sortedBy:null};
	            var sort = (oState.sortedBy) ? oState.sortedBy.key : oSelf.getColumnSet().keys[5].getKey();
	            var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "asc" : "desc";
	            var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
	            var results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
	            // Build the request
	            var ret =
		            SUGAR.util.paramsToUrl(oSelf.params) +
		            "&sort=" + sort +
	                "&dir=" + dir +
	                "&start=" + startIndex +
	                ((results !== null) ? "&limit=" + results : "");
	            return  ret;
	        });


			grid.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
				oPayload = oPayload || { };

				oPayload.totalRecords = oResponse.meta.total;
				oPayload.unreadRecords = oResponse.meta.unread;

		        var tabObject = SE.innerLayout.get("tabs")[0];
		        var mboxTitle = "";
		        if (this.params.mbox != null) {
		        	mboxTitle = this.params.mbox;
		        }
		        var tabtext = mboxTitle + " (" + oResponse.meta.total + " " + app_strings.LBL_EMAIL_MESSAGES + " )";
		        tabObject.get("labelEl").firstChild.data = tabtext;

		        if (SE.tree) {
			        var node = SE.tree.getNodeByProperty('id', this.params.ieId) || SE.tree.getNodeByProperty('origText', this.params.mbox);
			        if (node) {
				        node.data.unseen = oResponse.meta.unread;
				        SE.accounts.renderTree();
			        }
		        }
				return oPayload;
			}

			var resize = grid.resizeGrid = function () {
				SUGAR.email2.grid.set("width",  SUGAR.email2.grid.get("element").parentNode.clientWidth + "px");
				SUGAR.email2.grid.set("height", (SUGAR.email2.grid.get("element").parentNode.clientHeight - 57) + "px");
			}
			grid.convertDDRows = function() {
				var rowEl = this.getFirstTrEl();
				while (rowEl != null) {
					new this.DDRow(this, this.getRecord(rowEl), rowEl);
					rowEl = this.getNextTrEl(rowEl);
				}
			}


			grid.on("columnResizeEvent", function(o) {
				//Find the index of the column
				var colSet = SUGAR.email2.grid.getColumnSet().flat;
				for (var i=0; i < colSet.length; i++) {
					if (o.column == colSet[i]) {
						//Store it in the cookie
						Ck.setSub("EmailGridWidths", i + "", o.width, {expires: SUGAR.email2.nextYear});
					}
				}
				//this.resizeGrid();
			}, null, grid);

			grid.on("postRenderEvent", function() {this.convertDDRows()}, null, grid);
			grid.on("rowClickEvent", SUGAR.email2.listView.handleClick);
			grid.on("rowDblclickEvent", SUGAR.email2.listView.getEmail);
			grid.render();
			SUGAR.email2.listViewLayout.on("render", resize);
			resize();

			//Setup the default load parameters
			SUGAR.email2.grid.params = params;

			grid.on('postRenderEvent', SUGAR.email2.listView.setEmailListStyles);
			dataModel.subscribe("requestEvent", grid.disable, grid, true);
			dataModel.subscribe("responseParseEvent", grid.undisable, grid, true);
		}
	};
	e2Grid.init();
};


function initRowDD() {
	var sg = SUGAR.email2.grid,
	Dom = YAHOO.util.Dom;
	sg.DDRow = function(oDataTable, oRecord, elTr) {
		if(oDataTable && oRecord && elTr) {
			this.ddtable = oDataTable;
	        this.table = oDataTable.getTableEl();
	        this.row = oRecord;
	        this.rowEl = elTr;
	        this.newIndex = null;
	        this.init(elTr);
	        this.initFrame(); // Needed for DDProxy
	        this.invalidHandleTypes = {};
	    }
	};

	YAHOO.extend(sg.DDRow, YAHOO.util.DDProxy, {
	    _resizeProxy: function() {
	        this.constructor.superclass._resizeProxy.apply(this, arguments);
	        var dragEl = this.getDragEl(),
	            el = this.getEl();
	        var xy = Dom.getXY(el);

	        Dom.setStyle(dragEl, 'height', this.rowEl.offsetHeight + "px");
	        Dom.setStyle(dragEl, 'width', (parseInt(Dom.getStyle(dragEl, 'width'),10) + 4) + 'px');
	        Dom.setXY(dragEl, [xy[0] - 100, xy[1] - 20] );
	        Dom.setStyle(dragEl, 'display', "");
	    },

	    startDrag: function(x, y) {
	    	//Check if we should be dragging a set of rows rather than just the one.
	    	var selectedRows = this.ddtable.getSelectedRows();
	    	var iSelected = false;
	    	for (var i in selectedRows) {
	    		if (this.rowEl.id == selectedRows[i]) {
	    			iSelected = true;
	    			break
	    		}
	    	}
	    	if (iSelected) {
	    		this.rows = [];
	    		for (var i in selectedRows) {
	    			this.rows[i] = this.ddtable.getRecord(selectedRows[i]);
		    	}
	    	} else {
	    		this.rows = [this.row];
	    		this.ddtable.unselectAllRows();
	    		this.ddtable.selectRow(this.row);
	    	}

	    	//Initialize the dragable proxy
	    	var dragEl = this.getDragEl();
	        var clickEl = this.getEl();
	        Dom.setStyle(clickEl, "opacity", "0.25");
	        dragEl.innerHTML = "<table><tr>" + clickEl.innerHTML + "</tr></table>";
	    	Dom.addClass(dragEl, "yui-dt-liner");
	    	Dom.setStyle(dragEl, "opacity", "0.5");
	        Dom.setStyle(dragEl, "height", (clickEl.clientHeight - 2) + "px");
	        Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
	  	    Dom.setStyle(dragEl, "border", "2px solid gray");
	    },

	    clickValidator: function(e) {
	    	if (this.row.getData()[0] == " ")
	    		return false;
	        var target = YAHOO.util.Event.getTarget(e);
	    	return ( this.isValidHandleChild(target) &&
	    			(this.id == this.handleElId || this.DDM.handleWasClicked(target, this.id)) );
	    },
	    /**
	     * This function checks that the target of the drag is a table row in this
	     * DDGroup and simply moves the sourceEL to that location as a preview.
	     */
	    onDragOver: function(ev, id) {
	    	var node = SUGAR.email2.tree.getNodeByElement(Dom.get(id));
	    	if (node && node != this.targetNode) {
	    		this.targetNode = node;
	    		SUGAR.email2.folders.unhighliteAll();
	    		node.highlight();
	    	}
	    },

	    onDragOut: function(e, id) {
	    	if (this.targetNode) {
	    		SUGAR.email2.folders.unhighliteAll();
	    		this.targetNode = false;
	    	}
	    },
	    endDrag: function() {
	    	Dom.setStyle(this.getEl(), "opacity", "");
	    	Dom.setStyle(this.getDragEl(), "display", "none");
	    	if (this.targetNode) {
	    		SUGAR.email2.folders.handleDrop(this.rows, this.targetNode);
	    	}
	    	SUGAR.email2.folders.unhighliteAll();
	    	this.rows = null;
	    }
	});
}

function AddressSearchGridInit() {
    function moduleIcon(elCell, oRecord, oColumn, oData) {
    	elCell.innerHTML = "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=" + oData + ".gif' class='image' border='0' width='16' align='absmiddle'>";
    };
    function selectionCheckBox(elCell, oRecord, oColumn, oData) {
        elCell.innerHTML =  '<input type="checkbox" onclick="SUGAR.email2.addressBook.grid.toggleSelectCheckbox(\'' + oRecord.getId() + '\', this.checked);">';
    };
    var checkHeader = '<input type="checkbox" ';
    if (SUGAR.email2.util.isIe()) {
        checkHeader += 'style="top:-5px" ';
    }
    checkHeader += 'onclick="SUGAR.email2.addressBook.grid.toggleSelectAll(this.checked);">';
    var colModel =
	    [{
	    	label: checkHeader,
            width: 30,
            formatter: selectionCheckBox,
            key: 'bean_id'
        },
	    {
        	label: mod_strings.LBL_LIST_TYPE,
	        width: 25,
	        formatter: moduleIcon,
	        key: 'bean_module'
        },
	    {
        	label: app_strings.LBL_EMAIL_ADDRESS_BOOK_NAME,
	        width: 180,
	        sortable: true,
	        key: 'name'
	    },
	    {
	    	label: app_strings.LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR,
	        width: 300,
	        sortable: true,
	        key: 'email'
	    }];

    var dataModel = new YAHOO.util.DataSource(urlBase + "?", {
		responseType: YAHOO.util.XHRDataSource.TYPE_JSON,
        responseSchema: {
            resultsList: 'Person',
            fields: ['name', 'email', 'bean_id', 'bean_module'],
		    metaFields: {total: 'TotalCount'}
    	},
        //enable sorting on the server accross all data
        remoteSort: true
    });
    dataModel.params = {
		to_pdf		: true,
		module		: "Emails",
		action		: "EmailUIAjax",
		emailUIAction:"getAddressSearchResults"
    }
    var rb = document.getElementById('hasRelatedBean').checked;
	if (rb) {
		var idx = SUGAR.email2.composeLayout.currentInstanceId;
		var relatedBeanId = document.getElementById('data_parent_id' + idx).value;
		var relatedBeanType = document.getElementById('data_parent_type' + idx).value;
		dataModel.params['related_bean_id'] = relatedBeanId;
		dataModel.params['related_bean_type'] = relatedBeanType;
		dataModel.params['person'] = document.getElementById('input_searchPerson').value;
	}
    SUGAR.email2.addressBook.addressBookDataModel = dataModel;

    var grid = SUGAR.email2.addressBook.grid = new YAHOO.widget.ScrollingDataTable("addrSearchGrid", colModel, dataModel, {
    	MSG_EMPTY: "&nbsp;", //SUGAR.language.get("Emails", "LBL_EMPTY_FOLDER"),
		dynamicData: true,
		paginator: new YAHOO.widget.Paginator({
			rowsPerPage: 25,
			containers : ["dt-pag-nav-addressbook"],
			template: "<div class='pagination'>{FirstPageLink} {PreviousPageLink} {PageLinks} {NextPageLink} {LastPageLink}</div>",
					firstPageLinkLabel: 	"<button class='button'><div class='paginator-start'/></button>",
					previousPageLinkLabel: 	"<button class='button'><div class='paginator-previous'/></button>",
					nextPageLinkLabel: 		"<button class='button'><div class='paginator-next'/></button>",
					lastPageLinkLabel: 		"<button class='button'><div class='paginator-end'/></button>"
		}),
		initialRequest:SUGAR.util.paramsToUrl(dataModel.params),
		width:  "560px",
		height: "250px"
    });
	//Override Paging request construction
	grid.set("generateRequest", function(oState, oSelf) {
        oState = oState || {pagination:null, sortedBy:null};
        var sort = (oState.sortedBy) ? oState.sortedBy.key : oSelf.getColumnSet().keys[0].getKey();
        var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
        var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
        var results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
        // Build the request
        var ret =
            SUGAR.util.paramsToUrl(oSelf.getDataSource().params) +
            "&sort=" + sort + "&dir=" + dir + "&start=" + startIndex +
            ((results !== null) ? "&limit=" + results : "");
        return  ret;
    });

	grid.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
		oPayload = oPayload || { };
		oPayload.totalRecords = oResponse.meta.total;
		return oPayload;
	}

	grid.clickToggleSelect= function(args) {
		var isIE = (args.event.target == null);
		var targetElement = isIE ? args.event.srcElement : args.event.target;
		if(targetElement.type == null || targetElement.type != 'checkbox') {
			SUGAR.email2.addressBook.grid.toggleSelect(args.target.id);
		}
	}

	grid.reSelectRowsOnRender = function (){
	    var rows = SUGAR.email2.addressBook.grid.getRecordSet().getRecords();
        for (var i = 0; i < rows.length; i++)
        {
        	var emailAddress = rows[i].getData("email");
            var alreadyAdded = SUGAR.email2.addressBook.doesEmailAdddressExistInResultTable(emailAddress);
            if(alreadyAdded)
            {
                rows[i].setData("selected",  true);
        		SUGAR.email2.addressBook.grid.selectRow(rows[i]);
            }
            else
            {
                rows[i].setData("selected",  false);
                SUGAR.email2.addressBook.grid.unselectRow(rows[i]);
            }
        }
	}
	grid.subscribe("rowMouseoverEvent", grid.onEventHighlightRow);
	grid.subscribe("rowMouseoutEvent", grid.onEventUnhighlightRow);
	grid.subscribe("rowClickEvent", grid.clickToggleSelect);
    grid.subscribe("postRenderEvent", grid.reSelectRowsOnRender);

    grid.render();
    dataModel.subscribe("requestEvent", grid.disable, grid, true);
    dataModel.subscribe("responseParseEvent", grid.undisable, grid, true);

    grid.toggleSelectCheckbox = function(id,checked){
        var row = SUGAR.email2.addressBook.grid.getRecord(id);
        row.setData("checked",checked);
    };
    grid.toggleSelect = function(id, checked) {
        var row = SUGAR.email2.addressBook.grid.getRecord(id);
    	checked = row.getData("selected");
        if (!checked)
        {
            SUGAR.email2.addressBook.grid.selectRow(row);
            SE.addressBook.insertContactRowToResultTable(id,null)
        } else
        {
            SUGAR.email2.addressBook.grid.unselectRow(row);
            SE.addressBook.removeRowFromGridResults(id,row.getData("email"));
        }
        row.setData("selected", !checked);
    };

    grid.toggleSelectAll = function(checked) {
        rows = SUGAR.email2.addressBook.grid.getRecordSet().getRecords();
        for (var i = 0; i < rows.length; i++) {
			if (typeof(rows[i]) != "undefined")
				rows[i].setData("checked",  checked);
        }
        var checkBoxes = SUGAR.email2.addressBook.grid.get("element").getElementsByTagName('input');
        for (var i = 0; i < checkBoxes.length; i++) {
            checkBoxes[i].checked = checked;
        }
    };

    //Initialize the grid result table.
    AddressSearchResultsGridInit();
}



/**
*  Initalize the results table for the address book selection.
*
*/
function AddressSearchResultsGridInit()
{

    /* Full name sort function to compare by last name if available */
    var fullNameSort = function(a, b, desc) {
        // Deal with empty values
        if(!YAHOO.lang.isValue(a))
            return (!YAHOO.lang.isValue(b)) ? 0 : 1;
        else if(!YAHOO.lang.isValue(b))
            return -1;

        var aNames = a.getData("name").split(' ');
        var bNames = b.getData("name").split(' ');

        var aSortField = (aNames.length == 2) ? aNames[1] : a.getData("name");
        var bSortField = (bNames.length == 2) ? bNames[1] : b.getData("name");

        return YAHOO.util.Sort.compare(aSortField,bSortField, desc);

    };

    var typeDdOptions = [app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_TO.replace(/:$/,'') ,
                         app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_CC.replace(/:$/,''),
                         app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_BCC.replace(/:$/,'')];

    var ColumnDefs = [{key:'type',label:app_strings.LBL_EMAIL_ADDRESS_BOOK_ADRRESS_TYPE, width: 60, sortable: true, editor: new YAHOO.widget.RadioCellEditor({radioOptions:typeDdOptions,disableBtns:true})},
                     {key:'name',label:app_strings.LBL_EMAIL_ACCOUNTS_NAME,width: 280,sortable: true, sortOptions:{sortFunction:fullNameSort}}];

     var myDataSource = new YAHOO.util.DataSource([]);
	 myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
	 myDataSource.responseSchema = {
	            fields: ["name","type","email_address","display_email_address","bean_id","idx"]
	        };

	 var gridResults = SUGAR.email2.addressBook.gridResults = new YAHOO.widget.ScrollingDataTable("addrSearchResultGrid", ColumnDefs, myDataSource, {
                        width:  "350px",height: "250px", MSG_EMPTY: "&nbsp;"});

     var highlightEditableCell = function(oArgs) {
            var elCell = oArgs.target;
            if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
                this.highlightCell(elCell);
            }
        };

     gridResults.subscribe("cellMouseoverEvent", highlightEditableCell);
     gridResults.subscribe("cellMouseoutEvent", gridResults.onEventUnhighlightCell);
     gridResults.subscribe("cellClickEvent", gridResults.onEventShowCellEditor);
     gridResults.subscribe("rowMouseoverEvent", gridResults.onEventHighlightRow);
	 gridResults.subscribe("rowMouseoutEvent", gridResults.onEventUnhighlightRow);

     //Setup the context menus
     var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
	     var task = p_aArgs[1];
	     if(task)
	     {
	         var elRow = this.contextEventTarget;
	         elRow = p_myDataTable.getTrEl(elRow);

	         if(elRow)
	         {
	             switch(task.index)
	             {
	                 case 0:
	                     var oRecord = p_myDataTable.getRecord(elRow);
	                     p_myDataTable.deleteRow(elRow);
	                     SUGAR.email2.addressBook.grid.reSelectRowsOnRender();
	             }
	         }
	     }
	 };
     var contextMenu = new YAHOO.widget.ContextMenu("contextmenu",
	                {trigger:gridResults.getTbodyEl()});
	 contextMenu.addItem(app_strings.LBL_EMAIL_DELETE);
	 contextMenu.render("addrSearchResultGrid");
	 contextMenu.clickEvent.subscribe(onContextMenuClick, gridResults);
}

/* End of File modules/Emails/javascript/grid.js */

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

/******************************************************************************
 * Initialize Email 2.0 Application
 */

//Override Sugar Languge so quick creates work properly


function email2init() {

	//Init Tiny MCE
    // var tinyConfig = "code,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull," +
    //             "separator,bullist,numlist,outdent,indent,separator,forecolor,backcolor,fontselect,fontsizeselect";
    if (!SUGAR.util.isTouchScreen()) {
 	 tinyMCE.init({
 		 convert_urls : false,
         theme_advanced_toolbar_align : tinyConfig.theme_advanced_toolbar_align,
         valid_children : tinyConfig.valid_children,
         width: tinyConfig.width,
         theme: tinyConfig.theme,
         theme_advanced_toolbar_location : tinyConfig.theme_advanced_toolbar_location,
         theme_advanced_buttons1 : tinyConfig.theme_advanced_buttons1,
         theme_advanced_buttons2 : tinyConfig.theme_advanced_buttons2,
         theme_advanced_buttons3 : tinyConfig.theme_advanced_buttons3,
         plugins : tinyConfig.plugins,
         elements : tinyConfig.elements,
         language : tinyConfig.language,
         extended_valid_elements : tinyConfig.extended_valid_elements,
         mode: tinyConfig.mode,
         strict_loading_mode : true,
		 force_br_newlines : true,
         forced_root_block : '',
         directionality : (typeof(rtl) == "undefined") ? "ltr" : "rtl"
     });
    }

    // initialze message overlay
    SUGAR.email2.e2overlay = new YAHOO.widget.Dialog("SUGAR.email2.e2overlay", {
            //iframe        : true,
            modal       : false,
            autoTabs    : true,
            width       : 300,
            height      : 120,
            shadow      : true
        }
    );
	// Hide Sugar menu
	if (SUGAR.themes.tempHideLeftCol)
    	SUGAR.themes.tempHideLeftCol();

	// add key listener for kb shortcust - disable backspace nav in mozilla/ie
//	YAHOO.util.Event.addListener(window.document, 'keypress', SUGAR.email2.keys.overall);

	// set defaults for YAHOO.util.DragDropManager
	YAHOO.util.DDM.mode = 0; // point mode, default is point (0)

	SUGAR.email2.nextYear = new Date();
	SUGAR.email2.nextYear.setDate(SUGAR.email2.nextYear.getDate() + 360);

	
    // initialize and display UI framework (complexLayout.js)
    complexLayoutInit();
    
    // initialize and display grid (grid.js)
    gridInit();
    
    // initialize treeview for folders
	//onloadTreeinit();
	SUGAR.email2.folders.rebuildFolders(true);
	
	//SUGAR.email2.folders.retrieveTeamInfo();
	
    //Setup the Message Box overlay
    /*Ext.MessageBox.maxWidth = 350;
    Ext.MessageBox.minProgressWidth = 350;

	///////////////////////////////////////////////////////////////////////////
	////	CONTEXT MENUS
	// detailView array
	SUGAR.email2.contextMenus.detailViewContextMenus = new Object();
*/
	var SEC = SUGAR.email2.contextMenus; 
	
	//Grid menu
	var emailMenu = SEC.emailListContextMenu = new YAHOO.widget.ContextMenu("emailContextMenu", {
		trigger: SUGAR.email2.grid.get("element"),
		lazyload: true
	});
	emailMenu.subscribe("beforeShow", function() {
		var oTarget = this.contextEventTarget;
		if (typeof(oTarget) == "undefined")
		  return;
		var grid = SUGAR.email2.grid;
		var selectedRows = grid.getSelectedRows();
		var multipleSelected = (selectedRows.length > 1) ? true: false;
		if (!multipleSelected)
		{
			grid.unselectAllRows();
			grid.selectRow(oTarget);
			SUGAR.email2.contextMenus.showEmailsListMenu(grid, grid.getRecord(oTarget));	
		}
		else if(multipleSelected)
		{
		    SUGAR.email2.contextMenus.showEmailsListMenu(grid, grid.getRecord(oTarget));
		}
	});
	
	//When we need to access menu items later we can only do so by indexes so we create a mapping to allow
	//us to access individual elements easier by name rather than by index
	emailMenu.itemsMapping = {'viewRelationships':0, 'openMultiple': 1, 'archive' : 2,  'reply' : 3,'replyAll' : 4,'forward' : 5,
	                           'delete' : 6,'print' : 7,'mark' : 8,'assignTo' : 9, 'relateTo' : 10};
	emailMenu.addItems([
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_relate.gif'/>" + app_strings.LBL_EMAIL_VIEW_RELATIONSHIPS,
            id: 'showDetailView',
            onclick: { fn: SEC.showDetailView }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=open_multiple.gif'/>" + app_strings.LBL_EMAIL_OPEN_ALL,
            onclick: { fn: SEC.openMultiple }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_archive.gif'/>" + app_strings.LBL_EMAIL_ARCHIVE_TO_SUGAR,
            onclick: { fn: SEC.archiveToSugar }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_reply.gif'/>"+ app_strings.LBL_EMAIL_REPLY,
            id: 'reply',
            onclick: { fn: SEC.replyForwardEmailContext }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_replyall.gif'/>" + app_strings.LBL_EMAIL_REPLY_ALL,
            id: 'replyAll',
            onclick: { fn: SEC.replyForwardEmailContext }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_forward.gif'/>" + app_strings.LBL_EMAIL_FORWARD,
            id: 'forward',
            onclick: { fn: SEC.replyForwardEmailContext }
        },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_delete.gif'/>" + app_strings.LBL_EMAIL_DELETE,
            id: 'delete',
            onclick: { fn: SEC.markDeleted }
        },
        {
            text: "<img src='themes/default/images/Print_Email.gif'/>" + app_strings.LBL_EMAIL_PRINT,
            id: 'print',
            onclick: { fn: SEC.viewPrintable }
        },                
        // Mark... submenu
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_mark.gif'/>" + app_strings.LBL_EMAIL_MARK,
            submenu: {
        		id: "markEmailMenu",
                itemdata : [
                    {
                        text: app_strings.LBL_EMAIL_MARK + " " + app_strings.LBL_EMAIL_MARK_UNREAD,
                        onclick: { fn: SEC.markUnread }
                    },
                    {
                        text: app_strings.LBL_EMAIL_MARK + " " + app_strings.LBL_EMAIL_MARK_READ,
                        onclick: { fn: SEC.markRead }
                    },
                    {
                        text: app_strings.LBL_EMAIL_MARK + " " + app_strings.LBL_EMAIL_MARK_FLAGGED,
                        onclick: { fn: SEC.markFlagged }
                    },
                    {
                        text: app_strings.LBL_EMAIL_MARK + " " + app_strings.LBL_EMAIL_MARK_UNFLAGGED,
                        onclick: {  fn: SEC.markUnflagged }
                    }
                ]
            }
         },
        {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_assign.gif'/>" + app_strings.LBL_EMAIL_ASSIGN_TO,
        	id: 'assignTo',
        	onclick: { fn: SEC.assignEmailsTo }
         },
         {
            text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_relate.gif'/>" + app_strings.LBL_EMAIL_RELATE_TO,
            id: 'relateTo',
            onclick: { fn: SEC.relateTo }
         }
    ]);
	SEC.emailListContextMenu.render();
	
	//Handle the Tree folder menu trigger ourselves
	YAHOO.util.Event.addListener(YAHOO.util.Dom.get("emailtree"), "contextmenu", SUGAR.email2.folders.handleRightClick)

	
    	//Folder Menu
    SEC.frameFoldersContextMenu = new YAHOO.widget.ContextMenu("folderContextMenu", {
		trigger: "",
		lazyload: true 
	});
    SEC.frameFoldersContextMenu.addItems([
		{   text: "<img src='index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=icon_email_check.gif'/>" + app_strings.LBL_EMAIL_CHECK,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_ADD_FOLDER + "</i>",
			onclick: {  fn: function() {
		        var node = SUGAR.email2.clickedFolderNode;
		        if (node.data.ieId) {
		            SUGAR.email2.folders.startEmailCheckOneAccount(node.data.ieId, false)};
		    }}
		},
		{   text: app_strings.LBL_EMAIL_MENU_SYNCHRONIZE,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_ADD_FOLDER + "</i>",
			onclick: {  fn: function() {
		        var node = SUGAR.email2.clickedFolderNode;
		        if (node.data.ieId) {
		            SUGAR.email2.folders.startEmailCheckOneAccount(node.data.ieId, true)};
		    }}
		},
		{
		    text: app_strings.LBL_EMAIL_MENU_ADD_FOLDER,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_ADD_FOLDER + "</i>",
		    onclick: {  fn: SUGAR.email2.folders.folderAdd }
		},
		{
		    text: app_strings.LBL_EMAIL_MENU_DELETE_FOLDER,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_DELETE_FOLDER + "</i>",
		    onclick: {  fn: SUGAR.email2.folders.folderDelete }
		},
		{
		    text: app_strings.LBL_EMAIL_MENU_RENAME_FOLDER,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_RENAME_FOLDER + "</i>",
		    onclick: {  fn: SUGAR.email2.folders.folderRename }
		 },
		 {
		    text: app_strings.LBL_EMAIL_MENU_EMPTY_TRASH,
		    //helptext: "<i>" + app_strings.LBL_EMAIL_MENU_HELP_EMPTY_TRASH + "</i>",
		    onclick: {  fn: SUGAR.email2.folders.emptyTrash }
		  },
		 {
		    text: app_strings.LBL_EMAIL_MENU_CLEAR_CACHE,
		    onclick: {  fn: function() {
		        var node = SUGAR.email2.clickedFolderNode;
		        if (node.data.ieId) {
		            SUGAR.email2.folders.clearCacheFiles(node.data.ieId)};
		    }}
		  } 
	]);
    SEC.frameFoldersContextMenu.render();
    
    SEC.initContactsMenu = function() {
	// contacts
	SEC.contactsContextMenu = new YAHOO.widget.ContextMenu("contactsMenu", {
		trigger: "contacts",
		lazyload: true
	});
	SEC.contactsContextMenu.addItems([
		{
			text: app_strings.LBL_EMAIL_MENU_REMOVE,
			onclick:{ fn: SUGAR.email2.addressBook.removeContact }
		},
		{
			text: app_strings.LBL_EMAIL_MENU_COMPOSE,
			onclick:{ fn: function() {SUGAR.email2.addressBook.composeTo('contacts')}}
		}
	]);
	SEC.contactsContextMenu.subscribe("beforeShow", function() {
		var oTarget = this.contextEventTarget, grid = SUGAR.email2.contactView;
		if (oTarget && !grid.isSelected(oTarget)) {
			grid.unselectAllRows();
			grid.selectRow(oTarget);
		}
	});
	SEC.contactsContextMenu.render();
	}
	
	SEC.initListsMenu = function() {
	// contact mailing lists
	SEC.mailingListContextMenu = new YAHOO.widget.ContextMenu("listsMenu", {
		trigger: "lists",
		lazyload: true
	});
	
	SEC.mailingListContextMenu.addItems([
		{
			text: app_strings.LBL_EMAIL_MENU_REMOVE,
			onclick:{ fn: SUGAR.email2.addressBook.removeMailingList }
		},
		{
			text: app_strings.LBL_EMAIL_MENU_COMPOSE,
			onclick:{ fn:function() {SUGAR.email2.addressBook.composeTo('lists')} }
		},
        {
            text: app_strings.LBL_EMAIL_MENU_RENAME,
            onclick:{ fn: function() { SUGAR.showMessageBox(app_strings.LBL_EMAIL_LIST_RENAME_TITLE, app_strings.LBL_EMAIL_LIST_RENAME_DESC,
             'prompt', {fn: SUGAR.email2.addressBook.renameMailingList}); } }
        }
	]);
	
	SEC.mailingListContextMenu.subscribe("beforeShow", function() {
		var oTarget = this.contextEventTarget, grid = SUGAR.email2.emailListsView;
		if (oTarget && !grid.isSelected(oTarget)) {
			grid.unselectAllRows();
			grid.selectRow(oTarget);
		}
	});
	
	SEC.mailingListContextMenu.render();
	}
	
	// set auto-check timer
	SUGAR.email2.folders.startCheckTimer();
	// check if we're coming from an email-link click
	setTimeout("SUGAR.email2.composeLayout.composePackage()", 2000);
	
	YAHOO.util.Event.on(window, 'resize', SUGAR.email2.autoSetLayout);
	
	//Init fix for YUI 2.7.0 datatable sort.
	SUGAR.email2.addressBook.initFixForDatatableSort();
}

function createTreePanel(treeData, params) {
	var tree = new YAHOO.widget.TreeView(params.id);
	var root = tree.getRoot();
	
	//if (treeData.nodes && treeData[0].id == "Home")
	//	treeData = treeData[0];
	return tree;
}

function addChildNodes(parentNode, parentData) {
	var Ck = YAHOO.util.Cookie;
	var nextyear = SUGAR.email2.nextYear;
	var nodes = parentData.nodes || parentData.children;
	for (i in nodes) {
		if (typeof(nodes[i]) == 'object') {
			if (nodes[i].data) {
                // See comment about href below.
				// nodes[i].data.href = '#';
				var node = new YAHOO.widget.TextNode(nodes[i].data, parentNode);
				node.action = nodes[i].data.action;
			} else {
				if (nodes[i].id == SUGAR.language.get('app_strings','LBL_EMAIL_HOME_FOLDER')) {
					addChildNodes(parentNode, nodes[i]);
					return;
				}
				nodes[i].expanded = Ck.getSub("EmailTreeLayout", nodes[i].id + "") == "true";
				Ck.setSub("EmailTreeLayout", nodes[i].id + "", nodes[i].expanded ? true : false, {expires: SUGAR.email2.nextYear});
				if (nodes[i].cls) {
					nodes[i].className = nodes[i].cls;
				}
                // Previously, span was added in the label so it was rendering in the tree.
                // Default behavior is to wrap in span if no href property, and since this href
                // doesn't do anything, remove it so that it will be wrapped in spans.
                // nodes[i].href = "#";
				
				// URL Decode the text, so it shows properly
				nodes[i].text = unescape(nodes[i].text);
				
				if (nodes[i].text) nodes[i].label = nodes[i].text;
				//Override YUI child node creation
				if (nodes[i].children) {
					nodes[i].nodes = nodes[i].children;
					nodes[i].children = [ ];
				}
				var node = new YAHOO.widget.TextNode(nodes[i], parentNode);
			}
			
			if (typeof(nodes[i].nodes) == 'object') {
				addChildNodes(node, nodes[i]);
			}
		}
	}
}

/**
 * Custom TreeView initialization sequence to setup DragDrop targets for every tree node
 */
function email2treeinit(tree, treedata, treediv, params) {
	//ensure the tree data is not corrupt
	if (!treedata) {
	   return;
	}
	if (SUGAR.email2.tree) {
		SUGAR.email2.tree.destroy();
		SUGAR.email2.tree = null;
	}
	
	var tree = SUGAR.email2.tree = createTreePanel({nodes : {}}, {
		id: 'emailtree'
	});
	
	tree.subscribe("clickEvent", SUGAR.email2.folders.handleClick);
	tree.subscribe("collapseComplete", function(node){YAHOO.util.Cookie.setSub("EmailTreeLayout", node.data.id + "", false, {expires: SUGAR.email2.nextYear});});
	tree.subscribe("expandComplete", function(node){
		YAHOO.util.Cookie.setSub("EmailTreeLayout", node.data.id + "", true, {expires: SUGAR.email2.nextYear});
		for (var i in node.children) {
			SE.accounts.setupDDTarget(node.children[i]);
		}
	});
	tree.setCollapseAnim("TVSlideOut");
	tree.setExpandAnim("TVSlideIn");
	var root = tree.root;
	while (root.hasChildren()) {
		var node = root.children[0];
		node.destroy();
		tree.removeNode(root.children[0], false);
	}
	addChildNodes(root, treedata);
	tree.render();
	SUGAR.email2.accounts.renderTree();
}

SUGAR.email2.folders.folderDD = function(id, sGroup, config) {
	SUGAR.email2.folders.folderDD.superclass.constructor.call(this, id, sGroup, config);
};


YAHOO.extend(SUGAR.email2.folders.folderDD, YAHOO.util.DDProxy, {    
    startDrag: function(x, y) {
		var Dom = YAHOO.util.Dom;	
		this.dragNode = SUGAR.email2.tree.getNodeByElement(this.getEl());
		
		this.dragId = "";
		var dragEl = this.getDragEl();  
        var clickEl = this.getEl(); 
        Dom.setStyle(clickEl, "color", "#AAA");
        Dom.setStyle(clickEl, "opacity", "0.25"); 
        dragEl.innerHTML = clickEl.innerHTML; 
    	 
        Dom.addClass(dragEl, "ygtvcell");
        Dom.addClass(dragEl, "ygtvcontent");
        Dom.addClass(dragEl, "folderDragProxy");
        Dom.setStyle(dragEl, "height", (clickEl.clientHeight - 5) + "px");
        Dom.setStyle(dragEl, "width", (clickEl.clientWidth - 5) + "px");
        Dom.setStyle(dragEl, "backgroundColor", "#FFF"); 
        Dom.setStyle(dragEl, "opacity", "0.5"); 
  	    Dom.setStyle(dragEl, "border", "1px solid #AAA");
    },
    
    onDragOver: function(ev, id) {
    	var Dom = YAHOO.util.Dom;
    	if (id != this.dragId)
    	{
    		var node = SUGAR.email2.tree.getNodeByElement(YAHOO.util.Dom.get(id));
    		if(node.data.cls != "sugarFolder") {
    			SUGAR.email2.folders.unhighliteAll();
    			return;
    		}
    		this.dragId = id;
    		this.targetNode = node;
    		SUGAR.email2.folders.unhighliteAll();
    		node.highlight();
    	}
    },
    
    onDragOut: function(e, id) {
    	if (this.targetNode) {
    		SUGAR.email2.folders.unhighliteAll();
    		this.targetNode = false;
    		this.dragId = false;
    	}
    },
    
    endDrag: function() { 
    	YAHOO.util.Dom.setStyle(this.getEl(), "opacity", "1.0");
    	if (this.targetNode) {
    		SUGAR.email2.folders.moveFolder(this.dragNode.data.id, this.targetNode.data.id);
    	}
    }
});

/* End of File modules/Emails/javascript/init.js */

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
/**
  Complex layout init
 */
function complexLayoutInit() {
	var se = SUGAR.email2;
	var Dom = YAHOO.util.Dom;
	se.e2Layout = {
    	getInnerLayout : function(rows) {
        	se.listViewLayout = new YAHOO.widget.Layout('listViewDiv', {
            	parent: se.complexLayout,  
	    		border:true,
	            hideOnLayout: true,
	            height: 400,
				units: [{
					position: "center",
				    scroll:false, // grid should autoScroll itself
				    split:true,
				    body: "<div id='emailGrid'></div><div id='dt-pag-nav'></div> "
				},{
					position: "bottom",
				    scroll:true,
				    collapse: false,
				    resize: true,
				    useShim:true,
				    height:'250',
				    body: "<div id='listBottom' />"
				},{
				    position: "right",
				    scroll:true,
				    collapse: false,
				    resize: true,
				    useShim:true,
				    width:'250',
				    body: "<div id='listRight' />",
				    titlebar: false //,header: "right"
				}]
            });
        	se.complexLayout.on("render", function(){
        		var height = SUGAR.email2.innerLayout.get("element").clientHeight - 30;
				SUGAR.email2.innerLayout.get("activeTab").get("contentEl").parentNode.style.height = height + "px";
				SUGAR.email2.listViewLayout.set("height", height);
				SUGAR.email2.listViewLayout.render();
        	});
            se.listViewLayout.render();
            //CSS hack for now
            se.listViewLayout.get("element").parentNode.parentNode.style.padding = "0px"
            var rp = se.listViewLayout.resizePreview = function() {
            	var pre = Dom.get("displayEmailFramePreview");
            	if (pre) {
            		var parent = Dom.getAncestorByClassName(pre, "yui-layout-bd");
            		pre.style.height = (parent.clientHeight - pre.offsetTop) + "px";
            	}
            };
            se.listViewLayout.getUnitByPosition("bottom").on("heightChange", se.autoSetLayout);
            se.listViewLayout.getUnitByPosition("right").on("endResize", se.autoSetLayout);
            se.e2Layout.setPreviewPanel(rows);
            se.previewLayout = se.listViewLayout;
            return se.listViewLayout;
        },
        
        getInnerLayout2Rows : function() {
            return this.getInnerLayout(true);
        },
        getInnerLayout2Columns : function() {
            return this.getInnerLayout(false);
        },
        
        init : function(){
            // initialize state manager, we will use cookies
        	var viewHeight = document.documentElement ? document.documentElement.clientHeight : self.innerHeight;
            var heightOffset = $('#dcmenu').length > 0 ? $('#dcmenu').height() : $('#header').height();
        	se.complexLayout = new YAHOO.widget.Layout("container", {
        		border:true,
                hideOnLayout: true,
                height: Dom.getViewportHeight() - heightOffset - 65,
                width: Dom.getViewportWidth() - 40,
                units: [{
                	position: "center",
                    scroll:false,
                    body: "<div id='emailtabs'></div>"
                },
                {
                	position: "left",
                	scroll: true,
                	body: "<div id='lefttabs'></div>",
                    collapse: true,
                    width: 210,
                    minWidth: 100,
                    resize:true,
                    useShim:true,
                    titlebar: true,
                    header: "&nbsp;"
                },
                {
                    header: Dom.get('footerLinks').innerHTML,
					position: 'bottom',
					id: 'mbfooter',
					height: 22,
					border: false
                }]
            });
        	se.complexLayout.render();
        	var tp = se.innerLayout = new YAHOO.widget.TabView("emailtabs");
			tp.addTab(new YAHOO.widget.Tab({ 
				label: "Inbox",
				scroll : true,
				content : "<div id='listViewDiv'/>",
				id : "center",
				active : true
			}));
        	var centerEl = se.complexLayout.getUnitByPosition('center').get('wrap');
			tp.appendTo(centerEl);
			//CSS hack for now
			tp.get("element").style.borderRight = "1px solid #666"
			
			var listV =  this.getInnerLayout2Rows();
			listV.set("height", tp.get("element").clientHeight - 25);
			listV.render();
            tp.on("activeTabChange", function ()
            {
             	se.complexLayout.resize();
            });

            se.leftTabs = new YAHOO.widget.TabView("lefttabs");
            var foldersImg = '<img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_folder.gif" align=absmiddle border=0 alt="'+app_strings.LBL_EMAIL_FOLDERS+'">';

            var folderTab = new YAHOO.widget.Tab({ 
				label: foldersImg,
				scroll : true,
				content : "<div id='emailtree'/>",
				id : "tree",
				active : true
			});
            folderTab.on("activeChange", function(o){ 
            	if (o.newValue) {
            		se.complexLayout.getUnitByPosition("left").set("header", foldersImg+" "+app_strings.LBL_EMAIL_FOLDERS);
            	}
            });
            se.leftTabs.addTab(folderTab);
            
            var tabContent = SUGAR.util.getAndRemove("searchTab");
            var searchImg = '<img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=Search.gif" align=absmiddle border=0 alt="'+app_strings.LBL_SEARCH+'">';
            var searchTab = new YAHOO.widget.Tab({ 
				label: searchImg,
				scroll : true,
				content : tabContent.innerHTML,
				id : tabContent.id
			});
            searchTab.on("activeChange", function(o){ 
            	if (o.newValue) 
            	{
            		se.complexLayout.getUnitByPosition("left").set("header", searchImg);
            	   //Setup the calendars if needed
	               Calendar.setup ({inputField : "searchDateFrom", ifFormat : calFormat, showsTime : false, button : "searchDateFrom_trigger", singleClick : true, step : 1, weekNumbers:false});
	               Calendar.setup ({inputField : "searchDateTo", ifFormat : calFormat, showsTime : false, button : "searchDateTo_trigger", singleClick : true, step : 1, weekNumbers:false});
                   
	               //Initalize sqs object for assigned user name 
	               se.e2Layout.initSQSObject('advancedSearchForm','assigned_user_name');  
	               
	               //Attach event handler for when the relate module option is selected for the correct sqs object
	               var parentSearchArgs = {'formName':'advancedSearchForm','fieldName':'data_parent_name_search',
	                                        'moduleSelectField':'data_parent_type_search','fieldId':'data_parent_id_search'};
	               YAHOO.util.Event.addListener('data_parent_type_search', 'change',function(){ 
	                   SUGAR.email2.composeLayout.enableQuickSearchRelate(null,parentSearchArgs) });
	               
	               //If enter key is pressed, perform search
	               var  addKeyPressFields = ['searchSubject','searchFrom','searchTo','data_parent_name_search','searchDateTo','searchDateFrom','attachmentsSearch','assigned_user_name'];
	               for(var i=0; i < addKeyPressFields.length;i++)
	               {
    	               YAHOO.util.Event.addListener(window.document.forms['advancedSearchForm'].elements[addKeyPressFields[i]],"keydown", function(e){
                    		if (e.keyCode == 13) {
                    			YAHOO.util.Event.stopEvent(e);
                    			SUGAR.email2.search.searchAdvanced();
                    		}
            	       });
	               }
				   //Initiate quick search for the search tab.  Do this only when the tab is selected rather than onDomLoad for perf. gains.
	               enableQS(true);
	               //Clear parent values if selecting another parent type.
	               YAHOO.util.Event.addListener('data_parent_type_search','change', 
	                   function(){ 
	                       document.getElementById('data_parent_id_search').value =''; 
	                       document.getElementById('data_parent_name_search').value =''; 
	                   });
            	
            	}
            });
            se.leftTabs.addTab(searchTab);
            
            var resizeTabBody = function() {
            	var height = SUGAR.email2.leftTabs.get("element").clientHeight - 30;
				SUGAR.email2.leftTabs.get("activeTab").get("contentEl").parentNode.style.height = height + "px";
            }
            resizeTabBody();
            se.complexLayout.on("render", resizeTabBody);
            se.leftTabs.on("activeTabChange", resizeTabBody);
			//hack to allow left pane scroll bar to fully show
          	var lefttabsDiv = document.getElementById('lefttabs');
			var lefttabsDivParent = Dom.getAncestorBy(lefttabsDiv);
			var lefttabsDivGParent = Dom.getAncestorBy(lefttabsDivParent);
			lefttabsDivParent.style.width = lefttabsDivGParent.offsetWidth - 10 + "px";
          
        },
        initSQSObject: function(formName,fieldName)
        {
            var fullFieldName = formName + '_' + fieldName; //SQS Convention
            var resultName = fullFieldName + '_' + 'results';
            
            if (QSFieldsArray[fullFieldName] != null) 
            {
                QSFieldsArray[fullFieldName].destroy();
                delete QSFieldsArray[fullFieldName];
            }
            if (QSProcessedFieldsArray[fullFieldName])
            QSProcessedFieldsArray[fullFieldName] = false;

            if( Dom.get(resultName) )
            {
                var obj = document.getElementById(resultName);
                obj.parentNode.removeChild(obj);
            }
        },
        setPreviewPanel: function(rows) {
        	if (rows) {
            	SUGAR.email2.listViewLayout.getUnitByPosition("right").set("width", 0);
            	SUGAR.email2.listViewLayout.getUnitByPosition("bottom").set("height", 250);
            	Dom.get("listRight").innerHTML = "";
            	Dom.get("listBottom").innerHTML = "<div id='_blank' />";
            } else {
            	SUGAR.email2.listViewLayout.getUnitByPosition("bottom").set("height", 0);
            	SUGAR.email2.listViewLayout.getUnitByPosition("right").set("width", 250);
            	Dom.get("listBottom").innerHTML = "";
            	Dom.get("listRight").innerHTML = "<div id='_blank' />";
            }
        }
    };
	se.e2Layout.init();
}

var myBufferedListenerObject = new Object();
myBufferedListenerObject.refit = function() {
    if(SUGAR.email2.grid) {
        SUGAR.email2.grid.autoSize();
    }
}

/* End of File modules/Emails/javascript/complexLayout.js */

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
SUGAR.email2.templates['compose'] = '<div id="composeLayout{idx}" class="ylayout-inactive-content"></div>' +
'<div id="composeOverFrame{idx}" style="height:100%;width:100%;position:absolute;top:0px">' +
'	<form id="emailCompose{idx}" name="ComposeEditView{idx}" action="index.php" method="POST">' +
'		<input type="hidden" id="email_id{idx}" name="email_id" value="">' +
'		<input type="hidden" id="uid{idx}" name="uid" value="">' +
'		<input type="hidden" id="ieId{idx}" name="ieId" value="">' +
'		<input type="hidden" id="mbox{idx}" name="mbox" value="">' +
'		<input type="hidden" id="type{idx}" name="type" value="">' +
'		<input type="hidden" id="composeLayoutId" name="composeLayoutId" value="shouldNotSeeMe">' +
'		<input type="hidden" id="composeType" name="composeType">' +
'		<input type="hidden" id="fromAccount" name="fromAccount">' +
'		<input type="hidden" id="sendSubject" name="sendSubject">' +
'		<input type="hidden" id="sendDescription" name="sendDescription">' +
'		<input type="hidden" id="sendTo" name="sendTo">' +
'		<input type="hidden" id="sendBcc" name="sendBcc">' +
'		<input type="hidden" id="sendCc" name="sendCc">' +
'		<input type="hidden" id="setEditor" name="setEditor">' +
'		<input type="hidden" id="selectedTeam" name="selectedTeam">' +
'		<input type="hidden" id="teamIds" name="teamIds">' +
'		<input type="hidden" id="primaryteam" name="primaryteam">' +
'		<input type="hidden" id="saveToSugar" name="saveToSugar">' +
'		<input type="hidden" id="parent_id" name="parent_id">' +
'		<input type="hidden" id="parent_type" name="parent_type">' +
'		<input type="hidden" id="attachments" name="attachments">' +
'		<input type="hidden" id="documents" name="documents">' +
'		<input type="hidden" id="outbound_email{idx}" name="outbound_email">' +
'		<input type="hidden" id="templateAttachments" name="templateAttachments">' +
'		<input type="hidden" id="templateAttachmentsRemove{idx}" name="templateAttachmentsRemove">' +
'		<table id="composeHeaderTable{idx}" cellpadding="0" cellspacing="0" border="0" width="100%" class="list">' +
'			<tr>' +
'				<th><table cellpadding="0" cellspacing="0" border="0"><tbody><tr ><td style="padding: 0px !important;margin:0px; !important" >' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.sendEmail({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_send.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_SEND}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.saveDraft({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_save.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_SAVE_DRAFT}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.showAttachmentPanel({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_attach.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_ATTACHMENT}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.showOptionsPanel({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_options.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_OPTIONS}</button>' +
'</td><td style="padding: 0px !important;margin:0px; !important">&nbsp;&nbsp;{mod_strings.LBL_EMAIL_RELATE}:&nbsp;&nbsp;<select class="select" id="data_parent_type{idx}" onchange="document.getElementById(\'data_parent_name{idx}\').value=\'\';document.getElementById(\'data_parent_id{idx}\').value=\'\'; SUGAR.email2.composeLayout.enableQuickSearchRelate(\'{idx}\');" name="data_parent_type{idx}">{linkbeans_options}</select>' + 
'&nbsp;</td><td style="padding: 0px !important;margin:0px; !important"><input id="data_parent_id{idx}" name="data_parent_id{idx}" type="hidden" value="">' +
'<input class="sqsEnabled" id="data_parent_name{idx}" name="data_parent_name{idx}" type="text" value="">&nbsp;<button type="button" class="button" onclick="SUGAR.email2.composeLayout.callopenpopupForEmail2({idx});"><img src="index.php?entryPoint=getImage&themeName=default&imageName=id-ff-select.png" align="absmiddle" border="0"></button>' +
'			</td></tr></tbody></table></th>'     +
'			</tr>' +
'			<tr>' +
'				<td>' +
'					<div style="margin:5px;">' +
'					<table cellpadding="4" cellspacing="0" border="0" width="100%">' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP >' +
'								<label for="addressFrom{idx}">{app_strings.LBL_EMAIL_FROM}:</label>' +
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div>' +
'									&nbsp;&nbsp;<select style="width: 500px;" class="ac_input" id="addressFrom{idx}" name="addressFrom{idx}"></select>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP>' +
'								<button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressTO{idx}\')">' + 
'                                   {app_strings.LBL_EMAIL_TO}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressTO{idx}" title="{app_strings.LBL_EMAIL_TO}" name="addressTO{idx}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressTO{idx}" href="#" style="display: none;">+<span id="DetailaddressTO{idx}">&nbsp;</span></a> </span>' +
'									<div class="ac_container" id="addressToAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr id="add_addr_options_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>&nbsp;</td><td class="emailUIField" valign="top" NOWRAP>&nbsp;&nbsp;<span id="cc_span{idx}"><a href="#" onclick="SE.composeLayout.showHiddenAddress(\'cc\',\'{idx}\');">{mod_strings.LBL_ADD_CC}</a></span><span id="bcc_cc_sep{idx}">&nbsp;{mod_strings.LBL_ADD_CC_BCC_SEP}&nbsp;</span><span id="bcc_span{idx}"><a href="#" onclick="SE.composeLayout.showHiddenAddress(\'bcc\',\'{idx}\');">{mod_strings.LBL_ADD_BCC}</a></span></td>'+
'						</tr>'+
'						<tr class="yui-hidden" id="cc_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>' +
'                               <button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressCC{idx}\')">' + 
'								{app_strings.LBL_EMAIL_CC}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressCC{idx}" name="addressCC{idx}"   title="{app_strings.LBL_EMAIL_CC}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressCC{idx}" href="#"  style="display: none;">+<span id="DetailaddressCC{idx}">&nbsp;</span></a> </span>' + 
'									<div class="ac_container" id="addressCcAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr class="yui-hidden" id="bcc_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>' +
'                               <button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressBCC{idx}\')">' + 
'                               {app_strings.LBL_EMAIL_BCC}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressBCC{idx}" name="addressBCC{idx}" title="{app_strings.LBL_EMAIL_BCC}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressBCC{idx}" href="#" style="display: none;">+<span id="DetailaddressBCC{idx}">&nbsp;</span></a> </span>' +
'									<div class="ac_container" id="addressBccAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP width="1%">' +
'								<label for="emailSubject{idx}">{app_strings.LBL_EMAIL_SUBJECT}:</label>' +
'							</td>' +
'							<td class="emailUIField" NOWRAP width="99%">' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="emailSubject{idx}" name="subject{idx}" value="" maxlength="'+SUGAR.email2.composeLayout.subjectMaxlen+'">' +
'								</div>' +
'							</td>' +
'						</tr>' +
'					</table>' +
'					</div>' +
'				</td>'	 +
'			</tr>' +
'		</table>' +
'		<textarea id="htmleditor{idx}" name="htmleditor{idx}" style="width:100%; height: 100px;"></textarea>' +
'		<div id="divAttachments{idx}" class="ylayout-inactive-content">' +
'			<div style="padding:5px;">' +
'				<table cellpadding="2" cellspacing="0" border="0">' +
'					<tr>' +
'						<th>' +
'							<b>{app_strings.LBL_EMAIL_ATTACHMENTS}</b>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<input type="button" name="add_file_button" onclick="SUGAR.email2.composeLayout.addFileField();" value="{mod_strings.LBL_ADD_FILE}" class="button" />' +
'							<div id="addedFiles{idx}" name="addedFiles{idx}"></div>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							&nbsp;' +
'							<br />' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<th>' +
'							<b>{app_strings.LBL_EMAIL_ATTACHMENTS2}</b>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<input type="button" name="add_document_button" onclick="SUGAR.email2.composeLayout.addDocumentField({idx});" value="{mod_strings.LBL_ADD_DOCUMENT}" class="button" />' +
'							<div id="addedDocuments{idx}"></div>' + //<input name="document{idx}0" id="document{idx}0" type="hidden" /><input name="documentId{idx}0" id="documentId{idx}0" type="hidden" /><input name="documentName{idx}0" id="documentName{idx}0" disabled size="30" type="text" /><input type="button" id="documentSelect{idx}0" onclick="SUGAR.email2.selectDocument({idx}0, this);" class="button" value="{app_strings.LBL_EMAIL_SELECT}" /><input type="button" id="documentRemove{idx}0" onclick="SUGAR.email2.deleteDocumentField({idx}0, this);" class="button" value="{app_strings.LBL_EMAIL_REMOVE}" /><br /></div>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							&nbsp;' +
'							<br />' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<th>' +
'							<div id="templateAttachmentsTitle{idx}" style="display:none"><b>{app_strings.LBL_EMAIL_ATTACHMENTS3}</b></div>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<div id="addedTemplateAttachments{idx}"></div>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'			</div>' +
'		</div>' +
'	</form>' +
'		<div id="divOptions{idx}" class="ylayout-inactive-content"' +
'             <div style="padding:5px;">' +
'			<form name="composeOptionsForm{idx}" id="composeOptionsForm{idx}">' + 
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_TEMPLATES}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<select name="email_template{idx}" id="email_template{idx}"  onchange="SUGAR.email2.composeLayout.applyEmailTemplate(\'{idx}\', this.options[this.selectedIndex].value);"></select>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_SIGNATURES}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<select name="signatures{idx}" id="signatures{idx}" onchange="SUGAR.email2.composeLayout.setSignature(\'{idx}\');"></select>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_TEAMS}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td id="teamOptions{idx}" NOWRAP style="padding:2px;">' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<input type="checkbox" id="setEditor{idx}" name="setEditor{idx}" value="1" onclick="SUGAR.email2.composeLayout.renderTinyMCEToolBar(\'{idx}\', this.checked);"/>&nbsp;' +
'							<b>{mod_strings.LBL_SEND_IN_PLAIN_TEXT}</b>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'         </form>' +
'			</div> ' +
'		</div>' +
'</div>';

/* End of File modules/Emails/javascript/composeEmailTemplate.js */

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
SUGAR.email2.templates['displayOneEmail'] = 
'<div class="emailDetailTable" style="height:100%">' +
'<div id="viewMenuDiv{idx}"></div>' + 
'<div width="100%" class="displayEmailValue">' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'reply\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_reply.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_REPLY}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'replyAll\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_replyall.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_REPLY_ALL}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'forward\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_forward.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_FORWARD}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.detailView.emailDeleteSingle(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_delete.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_DELETE}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.detailView.viewPrintable(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=Print_Email.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_PRINT}</button>' +
'					<button id="btnEmailView{idx}" type="button" class="button" onclick="SUGAR.email2.detailView.showViewMenu(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_view.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_VIEW} <img src="themes/default/images/more.gif" align="absmiddle" border="0"></button>' +
'					<button id="archiveEmail{idx}" type="button" class="button" onclick="SUGAR.email2.detailView.importEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="themes/default/images/icon_email_archive.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_IMPORT_EMAIL}</button>' +
'					<button id="quickCreateSpan{meta.panelId}" type="button" class="button" onclick="SUGAR.email2.detailView.showQuickCreate(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="themes/default/images/icon_email_create.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_QUICK_CREATE} <img src="themes/default/images/more.gif" align="absmiddle" border="0"></button>' +
'					<button type="button" id="showDeialViewForEmail{meta.panelId}" class="button" onclick="SUGAR.email2.contextMenus.showEmailDetailViewInFullPage(\'{meta.uid}\');"><img src="themes/default/images/icon_email_relate.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_VIEW_RELATIONSHIPS}</button>' +
'</div>' +
'			<table cellpadding="0" cellspacing="0" border="0" width="100%" >' +
'				<tr>' +
'					<td NOWRAP valign="top" width="1%" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_FROM}:' +
'					</td>' +
'					<td width="99%" class="displayEmailValue">' +
'						{email.from_addr}' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_SUBJECT}:' +
'					</td>' +
'					<td NOWRAP valign="top" class="displayEmailValue">' +
'						<b>{email.name}</b>' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_DATE_SENT_BY_SENDER}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.date_start} {email.time_start}' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_TO}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.toaddrs}' +
'					</td>' +
'				</tr>' +
'				<tr>{meta.cc}</tr>' +
'				<tr>{email.attachments}</tr>' +
'			</table>' +
'			<div id="displayEmailFrameDiv{idx}" name="displayEmailFrameDiv{idx}"><iframe id="displayEmailFrame{idx}" src="modules/Emails/templates/_blank.html" width="100%" height="100%" frameborder="0"></iframe></div>' +
//'                           {email.description}' +
'</div>'
;

/* End of File modules/Emails/javascript/displayOneEmailTemplate.js */

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
SUGAR.email2.templates['viewPrintable'] = '<html>' +
'<body onload="javascript:window.print();">' + 
'<style>' + 
'body {' + 
'	margin: 0px;' + 
'	font-family: helvetica, impact, sans-serif;' +
'	font-size : 12pt;' +
'} ' +
'table {' +
'	padding:10px;' +
'}' +
'</style>' +
'<div>' +
'<table cellpadding="0" cellspacing="0" border="0" width="100%">' +
'	<tr>' +
'		<td>' +
'			<table cellpadding="0" cellspacing="0" border="0" width="100%">' +
'				<tr>' +
'					<td NOWRAP valign="top" width="1%" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_FROM}:' +
'					</td>' +
'					<td width="99%" class="displayEmailValue">' +
'						{email.from_name} &lt;{email.from_addr}&gt;' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_SUBJECT}:' +
'					</td>' +
'					<td NOWRAP valign="top" class="displayEmailValue">' +
'						<b>{email.name}</b>' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_DATE_SENT_BY_SENDER}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.date_start} {email.time_start}' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_TO}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.toaddrs}' +
'					</td>' +
'				</tr>' +
'				{email.cc}' +
'				{email.attachments}' +
'			</table>' +
'		</td>' +
'	</tr>' +
'	<tr>' +
'		<td>' +
'			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">' +
'				<tr>' +
'					<td style="border-top: 1px solid #333;">' +
'						<div style="padding:5px;">' +
							'{email.description}' +
'						</div>' +
'					</td>' +
'				</tr>' +
'			</table>' +
'		</td>' +
'	</tr>' +
'</table>' +
'</div>' +
'</body></html>';

/* End of File modules/Emails/javascript/viewPrintable.js */

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



/**
 * browse document for quickSearch fields
 * Compatible ExtJS 1.1.1 and ExtJS 2.0
 * parameter : noReload - if set to true, enableQS will enable only
 *             the new sqsEnabled field on the page. If set to false
 *             it will reload all the sqsEnabled fields.
 */
function enableQS(noReload){
	YAHOO.util.Event.onDOMReady(function(){
    	//Safety check.  If sqs_objects variable is null, we really can't do anything useful
    	if(typeof sqs_objects == 'undefined') {
    	   return;
    	}
        
    	var Dom = YAHOO.util.Dom;
    	
    	//Get all the fields where sqsEnabled is an attribue, these should be the input text fields for quicksearch
        var qsFields = Dom.getElementsByClassName('sqsEnabled');
        
        //Now loop through all these fields and process them
        for(var qsField in qsFields){
        	
        	//Safety checks to skip processing of invalid entries
        	if(typeof qsFields[qsField] == 'function' || typeof qsFields[qsField].id == 'undefined') {
        	   continue;
        	}
        	
        	//Create the index we are using to search for the sqs_objects Array
        	var form_id = qsFields[qsField].form.getAttribute('id');
        	
        	//This is a special case where there is an element with id attribute value of "id"
        	//In this case, we get the real_id attribute (occurs in modules/Import/tpls/step3.tpl only).
        	if(typeof form_id == 'object' && qsFields[qsField].form.getAttribute('real_id')) {
        		form_id = qsFields[qsField].form.getAttribute('real_id');
        	}
        	var qs_index_id = form_id + '_' + qsFields[qsField].name;

        	//Another safety check, if the sqs_objects entry is not defined, we can't do anything useful
        	if(typeof sqs_objects[qs_index_id] == 'undefined') {
        		qs_index_id = qsFields[qsField].name;
        		if(typeof sqs_objects[qs_index_id] == 'undefined') {
        	   		continue;
        	   	}
        	}
        	//Track if this field has already been processed.  The way the enableQS function is called
        	//is a bit problematic in that it lends itself to a lot of duplicate processing
        	if(QSProcessedFieldsArray[qs_index_id]) {
                skipSTR = 'collection_0';
                //the 'collection_0' id is not loaded dynamically, so the first item in the collection will not have an incremental value added to the base id
                //only skip the additional fields so that cases where a form is closed and reopened without refreshing the screen will still work
                if (qs_index_id.lastIndexOf(skipSTR) != (qs_index_id.length - skipSTR.length)){
        	        continue;
                }
        	}      	        	
        	
        	//Store sqs_objects entry as a reference for convenience
        	var qs_obj = sqs_objects[qs_index_id];
        	//The loaded variable will be used to check whether or not the quick search field should be created
            var loaded = false;   

            if (!document.forms[qs_obj.form]) {
        		continue;
        	}
            //Skip quicksearch fields that are readOnly or that are disabled since you can't search on them anyway
            if (!document.forms[qs_obj.form].elements[qsFields[qsField].id].readOnly && qs_obj['disable'] != true) {
            	var combo_id = qs_obj.form + '_' + qsFields[qsField].id;
            	if (Dom.get(combo_id + "_results")) {
            		loaded = true
            	}
                
                // if loaded == false, then we do the heavy lifting to re-create the quicksearch field
                if (!loaded) {
                	QSProcessedFieldsArray[qs_index_id] = true;
                	
                	qsFields[qsField].form_id = form_id;
                    
                    var sqs = sqs_objects[qs_index_id];    
                    
                    //Initialize the result div
                    var resultDiv = document.createElement('div');
                    resultDiv.id = combo_id + "_results";
                    Dom.insertAfter(resultDiv, qsFields[qsField]);
                    
                    //Add the module to the fields so we can read it from the response
                    var fields = qs_obj.field_list.slice();
                    fields[fields.length] = "module";
                    
                    //Create the DataSource for this QS
                    var ds = new YAHOO.util.DataSource("index.php?", {
        				responseType: YAHOO.util.XHRDataSource.TYPE_JSON,
                        responseSchema: {
                    		resultsList: 'fields',
        		            total: 'totalCount', 
        		            fields: fields,
        		            metaNode: "fields",
        		            metaFields: {total: 'totalCount', fields:"fields"}
        				},
        				connMethodPost: true
        		    });
                    
                    // Don't force selection for search fields
                    var forceSelect = !((qsFields[qsField].form && typeof(qsFields[qsField].form) == 'object' && qsFields[qsField].form.name == 'search_form')
							|| qsFields[qsField].className.match('sqsNoAutofill') !=  null);
                    
                    //Finally Declare the Autocomplete
                    var search = new YAHOO.widget.AutoComplete(qsFields[qsField], resultDiv, ds, {
                    	typeAhead: forceSelect,
                		forceSelection : forceSelect,
                    	fields: fields,
                    	sqs : sqs,
						animSpeed : 0.25,
                    	qs_obj: qs_obj,
                    	inputElement: qsFields[qsField],
                    	//YUI requires the data, even POST, to be URL encoded
                    	generateRequest : function(sQuery) {
                            //preprocess values
                            var item_id = this.inputElement.form_id + '_' + this.inputElement.name;
                            if (QSCallbacksArray[item_id]) {
                                QSCallbacksArray[item_id](this.sqs);
                            }
	                    	var out = SUGAR.util.paramsToUrl({
	                    		to_pdf: 'true',
	                            module: 'Home',
	                            action: 'quicksearchQuery',
	                            data: YAHOO.lang.JSON.stringify(this.sqs),
	                            query: sQuery
	                    	});
	                    	return out;
	                    },
	                    //Method to fill in form fields with the returned results. 
	                    //Should be called on select, and must be called from the AC instance scope.
	                    setFields : function (data, filter) {
	                    	this.updateFields(data, filter);	
	                    },
	                    
	                    updateFields: function(data, filter) {
	                    	for(var i in this.fields) {		
	                    		for (var key in this.qs_obj.field_list) {
	                    		   //Check that the field exists and matches the filter
	                	           if (this.fields[i] == this.qs_obj.field_list[key] && 
	                	        	   document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]] &&
	                	        	   this.qs_obj.populate_list[key].match(filter)) {
	                	        	   //bug: 30823 - remove the apostrophe
	                	        	   var displayValue = data[i].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
	                	        	   document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = displayValue;
                                       SUGAR.util.callOnChangeListers(document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]);
	                	           }
	                	       }
	                    	}
                            SUGAR.util.callOnChangeListers(this._elTextbox);
	                    },
	                    clearFields : function() {
	                    	for (var key in this.qs_obj.field_list) {
	                    	    if (document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]){
                	        	    document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = "";
                                    SUGAR.util.callOnChangeListers(document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]);
                	            }
	                    	}
							this.oldValue = "";
	                    }
                    });

                    //C.L. Bug 36575: In event of account_name quicksearch, check to see if we need to warn user
                    //that address fields may change.  This code has similarities to code block in set_return method
                    //of sugar_3.js when building the alert message contents.
                    if(/^(billing_|shipping_)?account_name$/.exec(qsFields[qsField].name))
                    {
                      
                       //C.L. Bug 36106 only clear the name and id fields
                       search.clearFields = function() {
                           for(var i in {name:0, id:1}) {
	                    		for (var key in this.qs_obj.field_list) {
                                    //Check that the field exists
	                	           if (i == this.qs_obj.field_list[key] &&
	                	        	   document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]])
                                   {
                                       document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = "";
                                   }
                                }
                           }
                       };
                    	
                       search.setFields = function(data, filter) 
                       {
                    	    var label_str = '';
	                		var label_data_str = '';
	                		var current_label_data_str = '';        		
	                		var label_data_hash = new Array();
	                    	
	                    	for(var i in this.fields) {
	                    		for (var key in this.qs_obj.field_list) {
	                    		   //Check that the field exists and matches the filter
	                	           if (this.fields[i] == this.qs_obj.field_list[key] && 
	                	        	   document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]] &&
	                	        	   document.getElementById(this.qs_obj.populate_list[key]+'_label') &&
	                	        	   this.qs_obj.populate_list[key].match(filter)) {
	                	        	   
	                	        	    var displayValue = data[i].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
                                        var data_label =  document.getElementById(this.qs_obj.populate_list[key]+'_label').innerHTML.replace(/\n/gi,'').replace(/<\/?[^>]+(>|$)/g, "");
			        					
			        					label_and_data = data_label  + ' ' + displayValue;
			        					
			        					//Append to current_label_data_str only if the label and data are unique
			        					if(document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]] && !label_data_hash[data_label])
			        					{
			        						label_str += data_label + ' \n';
			        						label_data_str += label_and_data + '\n';
			        						label_data_hash[data_label] = true;
				        					current_label_data_str += data_label + ' ' + document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value + '\n';
			        					}			        					
	                	           }
	                	       }
	                    	}
	                    	
	        			    if(label_str != current_label_data_str && current_label_data_str != label_data_str) {    	
	        			    	
	        			    	module_key = (typeof document.forms[form_id].elements['module'] != 'undefined') ? document.forms[form_id].elements['module'].value : 'app_strings';
	        			    	warning_label = SUGAR.language.translate(module_key, 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str;       			    	
	        			    		        			    	
	       			        	if(!confirm(warning_label))
	       						{
	       			        		this.updateFields(data,/account_id/); 
	       						} else {
	       							
	       							if(Dom.get('shipping_checkbox')) 
	       							{
	       							  if(this.inputElement.id == 'shipping_account_name')
	       							  {
	       								  //If the copy address checkbox is checked, update account and office_phone
	       							      filter = Dom.get('shipping_checkbox').checked ? /(account_id|office_phone)/ : filter;
	       							  } else if(this.inputElement.id == 'billing_account_name') {
	       								  //If the copy address is checked, update account, office phone and billing addresses
	       								  filter = Dom.get('shipping_checkbox').checked ? filter : /(account_id|office_phone|billing)/;
	       							  }
	       							} else if(Dom.get('alt_checkbox')) {
	       							  filter = Dom.get('alt_checkbox').checked ? filter : /^(?!alt)/;
	       							}
	       						
	       							this.updateFields(data,filter);
	       						}
	        			    } else {
		                        this.updateFields(data,filter); 	        			    	
	        			    }
                       };
                    }
                    
                    
//                    if ( typeof(SUGAR.config.quicksearch_querydelay) != 'undefined' ) {
//                        search.queryDelay = SUGAR.config.quicksearch_querydelay;
//                    }
                    
                    //fill in the data fields on selection
                    search.itemSelectEvent.subscribe(function(e, args){
                    	var data = args[2];
                    	var fields = this.fields;
                    	this.setFields(data, /\S/);
                        
                        //Handle special case where post_onblur_function is set    
                        if (typeof(this.qs_obj['post_onblur_function']) != 'undefined') {
                            collection_extended = new Array();
                            for (var i in fields) {
                                for (var key in this.qs_obj.field_list) {
                                    if (fields[i] == this.qs_obj.field_list[key]) {
                                        collection_extended[this.qs_obj.field_list[key]] = data[i];
                                    }
                                }
                            }
                            eval(this.qs_obj['post_onblur_function'] + '(collection_extended, this.qs_obj.id)');
                        }
                    });
                                        
					// We will get the old value firstly when the field lose focus.
                    search.textboxFocusEvent.subscribe(function(){
                    	this.oldValue = this.getInputEl().value;
                    });
                    
                    //If there is no change for this qucik search field , the value of it will not be cleared.
                    search.selectionEnforceEvent.subscribe(function(e, args){
	                    if (this.oldValue != args[1]) {
	                   		this.clearFields();
	                    } else {
	                    	this.getInputEl().value = this.oldValue;
	                    }
                    });
					
					search.dataReturnEvent.subscribe(function(e, args){
                        //Selected the first returned value if a tab was done before results were returned
						if (this.getInputEl().value.length == 0 && args[2].length > 0) {
							var data = [];
							for(var key in this.qs_obj.field_list) {
								data[data.length] = args[2][0][this.qs_obj.field_list[key]];
							}
							this.getInputEl().value = data[this.key];
							this.itemSelectEvent.fire(this, "", data);
						}
                    });

					search.typeAheadEvent.subscribe(function (e, args) {
						this.getInputEl().value = this.getInputEl().value.replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');			
					});					
					
                    //For IE browsers below 8 we have to set the z-index to allow the Autocomplete field to appear correctly
                    if(SUGAR.isIE && navigator.appVersion.match(/MSIE (.\..)/)[1] < 8 && /team_name/.test(search.getInputEl().id)) {
	                    search.containerExpandEvent.subscribe(function(e, args) {
	                    	var zVal = 9999;
	                    	div_els = YAHOO.util.Selector.query('div[name=teamset_div]');
	                    	for(x in div_els) {
	                    		Dom.setStyle(div_els[x], 'z-index', zVal--);
	                    		Dom.setStyle(div_els[x], 'position', 'relative'); 
	                    	}    
	                    });   
                    }
                    
                    if (typeof QSFieldsArray[combo_id] == 'undefined' && qsFields[qsField].id) {
                        QSFieldsArray[combo_id] = search;
                    }
                }
            }
        }
	});        
}

function registerSingleSmartInputListener(input) {
    if ((c = input.className) && (c.indexOf("sqsEnabled") != -1)) {
        enableQS(true);
    }
}

if(typeof QSFieldsArray == 'undefined') {
   QSFieldsArray = new Array();
   QSProcessedFieldsArray = new Array();
   QSCallbacksArray = new Array();
}

/* End of File include/javascript/quicksearch.js */

