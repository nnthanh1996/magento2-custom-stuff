<?php


namespace Wdevs\CustomBar\Plugin\Magento\Customer\CustomerData;


use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Model\GroupFactory;
use Wdevs\CustomBar\Helper\Data;

class CustomerPlugin
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;
    /** @var GroupFactory $customerGroupFactory */
    protected $customerGroupFactory;
    /** @var Data $helper */
    protected $helper;

    /**
     * CustomerPlugin constructor.
     * @param CurrentCustomer $currentCustomer
     * @param GroupFactory $customerGroupFactory
     * @param Data $helper
     */
    public function __construct(CurrentCustomer $currentCustomer, GroupFactory $customerGroupFactory, Data $helper)
    {
        $this->currentCustomer = $currentCustomer;
        $this->customerGroupFactory = $customerGroupFactory;
        $this->helper = $helper;
    }


    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $subject, $result) {

        if (!$this->currentCustomer->getCustomerId()) {
            return [
                'customerGroupMessage' => [
                    'isEnable' => $this->helper->isModuleEnableConfig(),
                    'message' => $this->getGroupMessage(0) // for non-login customer group
                ]
            ];
        }

        $customerGroupId = $this->currentCustomer->getCustomer()->getGroupId();

        $result['customerGroupMessage'] = [
            'isEnable' => $this->helper->isModuleEnableConfig(),
            'message' => $this->getGroupMessage($customerGroupId)
        ];

        return $result;
    }

    public function getGroupMessage($groupId) {

        $customerGroup = $this->customerGroupFactory->create()->load($groupId);

        if($customerGroup->getCustomerGroupCode()) {
            return $customerGroup->getData('custom_message');
        }

        return '';

    }

}
