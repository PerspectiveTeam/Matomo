<?php

namespace Perspective\Matomo\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Connection extends AbstractHelper
{
    const PATH_CONNECTION_MAIN_ENDPOINT = 'matomo/connection/main_endpoint';
    const PATH_CONNECTION_MATOMO_TOKEN = 'matomo/connection/matomo_token';

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
    public function getMainEndpointUri()
    {
        return $this->get(self::PATH_CONNECTION_MAIN_ENDPOINT);
    }

    public function getMatomoToken()
    {
        return $this->get(self::PATH_CONNECTION_MATOMO_TOKEN);
    }

}
