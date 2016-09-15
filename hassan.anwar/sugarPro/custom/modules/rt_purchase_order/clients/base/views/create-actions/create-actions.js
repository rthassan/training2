/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

({
    extendsFrom: 'CreateActionsView',
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    getCustomSaveOptions: function (options) {
        var self = this;

        // make copy of original function we are extending
        var origSuccess = options.success;
        // return extended success function with added alert
        return {
            success: _.bind(function () {
                if (_.isFunction(origSuccess)) {
                    origSuccess.apply(this, arguments);
                }
                var total = 0;
                var discountless = 0.0;
                var tax = 0.0;
                var finaltotal = 0.0;

                for (var i = 0; i < lineitems.length; i++) {
                    var myModel = lineitems.models[i];
                    var unitprice = myModel.get('quantity') * myModel.get('unit_price');
                    total = total + unitprice;

                    if (parseInt(myModel.get('discount_less')))
                    {
                        discountless = discountless + parseInt(myModel.get('discount_less'));
                    }
                    if (myModel.get('tax_class') == 'Taxable')
                    {
                        tax = tax + ((taxPercent / 100) * unitprice);
                        console.log(tax);
                    }
                    
                    myModel.set('purchase_order_id', self.model.get('id'));
                    myModel.save({});
                }
                
                finaltotal = total - discountless + tax;
                self.model.set('total_price', total);
                self.model.set('total_final', finaltotal);
                self.model.set('total_discount', discountless);
                self.model.set('tax_value', tax);
               
                self.model.save();
            }, this)
        };
    },

})

