/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

({
    extendsFrom: 'RecordView',
    _render: function () {
        this._super('_render');

        if (_.last(window.location.href.split('/')) != 'edit')
        {
            $("#new-item-add").hide();
        } else
        {
            $("#new-item-add").show();
        }


    },
    events: {
        "click #new-item-add": "addItemRow",
        "click .remove_item": "removeFromCollection",
    },
    delegateButtonEvents: function () {
        this.context.on('button:edit_button:click', this.editClicked, this);
        this.context.on('button:save_button:click', this.saveClicked, this);
    },
    initialize: function (options) {
        this._super('initialize', [options]);

        this.lineitems = app.data.createBeanCollection("rt_purchase_line_item");
        this.backupLineItems = app.data.createBeanCollection("rt_purchase_line_item");
        this.removeitems = app.data.createBeanCollection("rt_purchase_line_item");

        this.collection = this.model.getRelatedCollection('purchase_line_items');
        this.showTotal();
        this.populateTax();
        var self = this;
        this.collection.fetch({
            relate: true,
            success: function () {
                for (var i = 0; i < self.collection.length; i++) {
                    self.lineitems.add(self.collection.models[i]);
                    self.backupLineItems.add(self.collection.models[i]);
                }
                self._render();
            }
        });

    },
    showTotal: function () {
        this.totalBean = app.data.createBean('rt_purchase_order', {id: this.model.id});
        var self = this;
        this.totalBean = this.totalBean.fetch({
            success: function (data)
            {
                self.total = data.get('total_price');
                self.finaltotal = data.get('total_final');
                self.discountless = data.get('total_discount');
                self.tax = data.get('tax_value');
                self._render();
            }
        });


    },
    addItemRow: function () {
        var new_lineitem = app.data.createBean('rt_purchase_line_item', {id: null});
        this.lineitems.add(new_lineitem);
        this.collection.add(new_lineitem);
        this._super('_render');
    },
    editClicked: function () {
        this._super('editClicked');
        $("#new-item-add").show();
    },
    saveClicked: function () {
        //this._super('saveClicked');
        var self = this;

        this.successIndex = -1;
        this.errorIndex = -1;
        this.indexCheckCall = 0;
        this.total = 0.0;
        this.discountless = 0.0;
        this.tax = 0.0;
        this.finaltotal = 0.0;

        console.log(this.taxPercent);
        for (var i = 0; i < this.lineitems.length; i++) {
            var myModel = this.lineitems.models[i];
            var unitprice=myModel.get('quantity') * myModel.get('unit_price');
            this.total = this.total + unitprice;
            
            if (parseInt(myModel.get('discount_less')))
            {
                this.discountless = this.discountless + parseInt(myModel.get('discount_less'));
            }
            if(myModel.get('tax_class')=='Taxable')
            {
                this.tax=this.tax+( (this.taxPercent/100)*unitprice );
                console.log(this.tax);
            }
            
            myModel.set('purchase_order_id', this.model.id);
            myModel.save({}, {
                success: function () {
                    self.successIndex++;
                    self.indexCheckCall++;
                    self.indexCheck();
                },
                error: function () {
                    self.errorIndex++;
                    self.indexCheckCall++;
                    self.indexCheck();
                }
            });

        }

    },
    removeFromCollection: function (e, cell) {
        var idName = e.currentTarget.id;
        var id = idName.split('_');
        this.removeitems.add(this.lineitems.models[id[1]]);
        this.lineitems.remove(this.lineitems.models[id[1]]);
        this._super('_render');

    },
    indexCheck: function ()
    {
        var self = this;
        if (this.indexCheckCall == this.lineitems.length && this.errorIndex != -1)
        {
            this.revertChange();

        } else if (this.indexCheckCall == this.lineitems.length)
        {
            this.finaltotal = this.total - this.discountless + this.tax;
            this.model.set('total_price', this.total);
            this.model.set('total_final', this.finaltotal);
            this.model.set('total_discount', this.discountless);
            this.model.set('tax_value', this.tax);
            for (var i = 0; i < this.removeitems.length; i++) {
                var model = this.removeitems.models[i];
                this.removeitems.remove(this.removeitems.models[i]);
                model.destroy();
            }

            this.model.save({}, {
                success: function () {
                    self.total = self.model.get('total_price');
                    self.finaltotal = self.model.get('total_final');
                    self.discountless = self.model.get('total_discount');
                    self.tax = self.model.get('tax_value');
                    self._super('_render');
                    $("#new-item-add").hide();
                },
            });
        }

    },
    revertChange: function ()
    {
        for (var i = 0; i <= this.lineitems.length; i++) {
            var model = this.lineitems.models[i];
            this.lineitems.remove(this.lineitems.models[i]);
            model.destroy();
        }

        this.total = 0;
        for (var i = 0; i < this.backupLineItems.length; i++) {
            var myModel = this.backupLineItems.models[i];
            this.total = this.total + (myModel.get('quantity') * myModel.get('unit_price'));
            myModel.set('purchase_order_id', this.model.id);
            myModel.save({});
        }
        this.model.set('total_price', this.total);
    },
    populateTax: function ()
    {
        this.model.on('change:tax_rate', this.setTaxValue, this);
    },
    setTaxValue: function ()
    {
        var self = this;

        if (!_.isEmpty(this.model.get('tax_rate')))
        {
            app.api.call('GET', app.api.buildURL('TaxRates/' + this.model.get('tax_rate')), null, {
                success: _.bind(function (response) {
                    // here is your success code  
                    self.taxPercent = response.value;
                }, this)

            });

        }
        else
        {
            this.taxPercent=0;
        }




    }
// Recursive Method
//    saveLineItems: function (index, total) {
//        console.log(index + '   ' + total + '   ' + this.lineitems.length);
//        if (index == this.lineitems.length)
//        {
//            this.model.set('total_price', total);
//            this.total = this.model.get('total_price');
//            return true;
//        }
//        console.log(index + '   ' + total);
//        var self = this;
//        var myModel = this.lineitems.models[index];
//        total = total + (myModel.get('quantity') * myModel.get('unit_price'));
//        myModel.set('purchase_order_id', this.model.id);
//        myModel.save({}, {
//            success: function () {
//                console.log('Total before Success: ' + total);
//                return self.saveLineItems(index + 1, total);
//            },
//            error: function () {
//                return false;
//            }
//        });
//
//    }

})