<?php


namespace Wdevs\CustomBar\Plugin\Magento\Customer\Controller\Adminhtml\Group;


class SavePlugin
{
    /** @var \Magento\Customer\Model\GroupFactory $groupFactory */
    protected $groupFactory;

    /**
     * SavePlugin constructor.
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     */
    public function __construct(\Magento\Customer\Model\GroupFactory $groupFactory)
    {
        $this->groupFactory = $groupFactory;
    }

    public function afterExecute(\Magento\Customer\Controller\Adminhtml\Group\Save $subject, $result) {

        $request = $subject->getRequest();

        $customMessage = $request->getParam('custom_message');

        $id = $request->getParam('id');

        if($id != null) {

            $customerGroup = $this->groupFactory->create()->load($id);

            $customerGroup->setData('custom_message', $customMessage);

            $customerGroup->save();

        }

        return $result;
    }

}
