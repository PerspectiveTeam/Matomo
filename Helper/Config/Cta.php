<?php

namespace Perspective\Matomo\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Cta extends AbstractHelper
{
    const PATH_CTA_EVENT_TYPE = 'matomo/cta/event_type';
    const PATH_CTA_EVENT_VALUE = 'matomo/cta/event_value';

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Get config value by path.
     *
     * @param string $path
     * @param int|string|null $storeId
     * @return int|string|null
     */
    public function get(string $path, $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->get(self::PATH_CTA_EVENT_TYPE);
    }

    public function getEventValue()
    {
        return $this->get(self::PATH_CTA_EVENT_VALUE);
    }

}
