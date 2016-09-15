/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

({
  
    _render: function () {
        this._super('_render');
    },
    events: {
        "click #new-item-add": "addItemRow",
        "click .remove_item": "removeFromCollection",
    },
   
    initialize: function (options) {
        this._super('initialize',[options]);
        lineitems = app.data.createBeanCollection("rt_purchase_line_item");
        this.lineitems = app.data.createBeanCollection("rt_purchase_line_item");
        this.populateTax();
    },
    addItemRow: function () {
        var new_lineitem = app.data.createBean('rt_purchase_line_item', {id: null});
        lineitems.add(new_lineitem); //For use in Custom Save
        this.collection.add(new_lineitem);
        this.lineitems.add(new_lineitem);
        this._super('_render');
    },
    removeFromCollection: function (e, cell) {
        var idName = e.currentTarget.id;
        var id = idName.split('_');
        lineitems.remove(lineitems.models[id[1]]);
        this.lineitems.remove(this.lineitems.models[id[1]]);
        this._super('_render');
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
                    taxPercent = response.value;
                    console.log(taxPercent);
                }, this)

            });

        } else
        {
            taxPercent = 0;
        }




    }
    
})