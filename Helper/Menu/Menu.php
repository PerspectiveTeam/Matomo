<?php

namespace Perspective\Matomo\Helper\Menu;

use Magento\Framework\App\Helper\Context;

class Menu extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    protected $context;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        $this->context = $context;
    }

    public function getLink()
    {

        if ($this->getGroupName() == 'PARTNER') {
            $link = "matomoext/customer/statistics";
        } else {
            $link = '';
        }
        return $link;
    }

    public function getTitle()
    {

        if ($this->getGroupName() == 'PARTNER') {
            $links = __("Statistics");
        } else {
            $links = '';
        }
        return $links;
    }

    /**
     * @param \Magento\Customer\Model\Customer|null $customer
     * @return array|string|string[]|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGroupName($customer = null)
    {
        if ($customer) {
            $customer->getGroupId();
        } else {
            $groupId = $this->customerSession->getCustomer()->getGroupId();
        }
        $groupModel = $this->groupRepository->getById($groupId);
        return preg_replace('/(\s)+/', ' ', $groupModel->getCode());
    }
}
