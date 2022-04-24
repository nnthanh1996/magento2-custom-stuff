<?php


namespace Wdevs\CustomBar\Helper;


use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
    protected $storeManager;

    public function __construct(Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }


    public function isModuleEnableConfig(){
        return $this->scopeConfig->getValue('customer/customer_group_configuration/enable_custom_bar', 'stores', $this->storeManager->getStore()->getId()) === '1';
    }

}
