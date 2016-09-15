/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


({
    extendsFrom: 'EnumField',
    
    initialize: function (opts) {
        this._super('initialize', [opts]);
        this.type = 'enum';
    },
    
    loadData:function(){
        this.loadTaxOptions();
    },
    
    loadTaxOptions: function() {
        // clear the list
        this.items = null;
       
    },
    
    
    loadEnumOptions: function (fetch, callback) {
     
        var self = this,
                meta = app.metadata.getModule(this.module, 'fields'),
                fieldMeta = meta && meta[this.name] ? meta[this.name] : this.def,
                request;

        this.isFetchingOptions = true;

        
        var taxRates = app.data.createBeanCollection("TaxRates");
        taxRates.fetch({
            success: function (data) {
                var o = {};
                for (var i = 0; i < data.models.length; i++) {
                    console.log(data.models[i].get('id') + '---' + data.models[i].get('name') );
                    o[data.models[i].get('id')] = data.models[i].get('name');
                }
                self.items = o;
                callback.call(self);
                
            }
        });
        
    }

})