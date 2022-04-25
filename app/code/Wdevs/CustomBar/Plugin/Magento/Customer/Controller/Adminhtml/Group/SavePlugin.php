<?php


namespace Wdevs\CustomBar\Plugin\Magento\Customer\Controller\Adminhtml\Group;


use Magento\Cms\Model\Template\FilterProvider;

class SavePlugin
{
    /** @var \Magento\Customer\Model\GroupFactory $groupFactory */
    protected $groupFactory;
    /**
     * @var FilterProvider
     */
    protected $filterProvider;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * SavePlugin constructor.
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     * @param FilterProvider $filterProvider
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Customer\Model\GroupFactory $groupFactory,
        FilterProvider $filterProvider,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->groupFactory = $groupFactory;
        $this->filterProvider = $filterProvider;
        $this->logger = $logger;
    }

    public function afterExecute(\Magento\Customer\Controller\Adminhtml\Group\Save $subject, $result) {

        $request = $subject->getRequest();

        $customMessage = $request->getParam('custom_message');

        try {
            $filteredCustomMessage = $this->filterProvider->getPageFilter()->filter($customMessage);
        } catch (\Exception $e) {
            $filteredCustomMessage = $customMessage;
            $this->logger->error($e->getMessage());
        }

        $id = $request->getParam('id');

        if($id != null) {

            $customerGroup = $this->groupFactory->create()->load($id);

            $customerGroup->setData('custom_message', $filteredCustomMessage);

            $customerGroup->save();

        }

        return $result;
    }

}
