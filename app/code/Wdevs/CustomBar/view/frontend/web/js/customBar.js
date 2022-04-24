define(['ko', 'jquery', 'uiComponent', 'Magento_Customer/js/customer-data'], function(ko, $, Component, customerData){
    'use strict';

    return Component.extend({
        initialize: function(config) {
            this._super();

            let self = this;

            this.customerMessage = ko.observable('');
            this.isActive = ko.observable(false);

            customerData.reload(['customer'], false).done(function(){
                self.updateCustomerGroupMsg(customerData.get('customer')());
            });
        },
        updateCustomerGroupMsg: function(customerDataObj) {
            if(customerDataObj.hasOwnProperty('customerGroupMessage')) {
                this.isActive(customerDataObj.customerGroupMessage.isEnable);
                this.customerMessage(customerDataObj.customerGroupMessage.message);
            }else{
                console.error("Missing customerGroupMessage object");
            }
        }
    })

})
