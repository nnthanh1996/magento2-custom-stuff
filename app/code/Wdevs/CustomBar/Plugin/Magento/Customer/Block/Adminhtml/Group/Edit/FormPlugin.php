<?php


namespace Wdevs\CustomBar\Plugin\Magento\Customer\Block\Adminhtml\Group\Edit;


use Magento\Customer\Api\GroupExcludedWebsiteRepositoryInterface;
use Magento\Customer\Controller\RegistryConstants;

class FormPlugin
{

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;


    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Customer\Api\Data\GroupInterfaceFactory
     */
    protected $groupDataFactory;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $_taxHelper;

    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * @var GroupExcludedWebsiteRepositoryInterface
     */
    protected $groupExcludedWebsiteRepository;

    /**
     * FormPlugin constructor.
     * @param \Magento\Cms\Model\Wysiwyg\Config $_wysiwygConfig
     * @param \Magento\Backend\Model\Session $_backendSession
     * @param \Magento\Framework\Registry $_coreRegistry
     * @param \Magento\Customer\Api\Data\GroupInterfaceFactory $groupDataFactory
     * @param \Magento\Tax\Helper\Data $_taxHelper
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     * @param GroupExcludedWebsiteRepositoryInterface $groupExcludedWebsiteRepository
     */
    public function __construct(\Magento\Cms\Model\Wysiwyg\Config $_wysiwygConfig, \Magento\Backend\Model\Session $_backendSession, \Magento\Framework\Registry $_coreRegistry, \Magento\Customer\Api\Data\GroupInterfaceFactory $groupDataFactory, \Magento\Tax\Helper\Data $_taxHelper, \Magento\Customer\Model\GroupFactory $groupFactory, GroupExcludedWebsiteRepositoryInterface $groupExcludedWebsiteRepository)
    {
        $this->_wysiwygConfig = $_wysiwygConfig;
        $this->_backendSession = $_backendSession;
        $this->_coreRegistry = $_coreRegistry;
        $this->groupDataFactory = $groupDataFactory;
        $this->_taxHelper = $_taxHelper;
        $this->groupFactory = $groupFactory;
        $this->groupExcludedWebsiteRepository = $groupExcludedWebsiteRepository;
    }

    public function beforeToHtml(\Magento\Customer\Block\Adminhtml\Group\Edit\Form $subject) {

       $form = $subject->getForm();

       $baseFieldset = $form->getElement('base_fieldset');
        // Add Custom Message Field to Form
       $baseFieldset->addField(
           'custom_message',
           'editor',
           [
               'name' => 'custom_message',
               'label' => __('Custom Message'),
               'title' => __('Custom Message'),
               'rows' => '10',
               'cols' => '30',
               'wysiwyg' => true,
               'config' => $this->_wysiwygConfig->getConfig(),
               'required' => false
           ]
       );

       // Set Custom Message value to form

        $groupId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_GROUP_ID);
        /** @var \Magento\Customer\Api\Data\GroupInterface $customerGroup */
        $customerGroupExcludedWebsites = [];
        if ($groupId === null) {
            $customerGroup = $this->groupDataFactory->create();
            $defaultCustomerTaxClass = $this->_taxHelper->getDefaultCustomerTaxClass();
        } else {
            $customerGroup = $this->groupFactory->create()->load($groupId);
            $defaultCustomerTaxClass = $customerGroup->getTaxClassId();
            $customerGroupExcludedWebsites = $this->groupExcludedWebsiteRepository->getCustomerGroupExcludedWebsites(
                $groupId
            );
        }

        if ($this->_backendSession->getCustomerGroupData()) {
            $form->addValues($this->_backendSession->getCustomerGroupData());
            $this->_backendSession->setCustomerGroupData(null);
        } else {

            $form->addValues(
                [
                    'id' => $customerGroup->getId(),
                    'customer_group_code' => $customerGroup->getCode(),
                    'tax_class_id' => $defaultCustomerTaxClass,
                    'customer_group_excluded_website_ids' => $customerGroupExcludedWebsites,
                    'custom_message' => $customerGroup->getData('custom_message')
                ]
            );
        }

    }

}
