/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

({
    plugins: ['Dashlet'],
    
    initialize:function(options){
       this._super('initialize',[options]);
       this.city=options.meta.city;
    }
    
    
})