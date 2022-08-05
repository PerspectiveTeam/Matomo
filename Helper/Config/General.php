<?php

namespace Perspective\Matomo\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class General extends AbstractHelper
{
    const PATH_ENABLED = 'matomo/general/enabled';

    const PATH_ROUTER_PATH = 'matomo/general/router_path';

    const PATH_IS_DEBUG_PATH = 'matomo/general/debug';

    const PATH_DEBUG_TRIM_PATH = 'matomo/general/debug_trim';

    const PATH_DEBUG_SITE_URL_PATH = 'matomo/general/site_url';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
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
     * Is send action enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return !!$this->get(self::PATH_ENABLED);
    }

    /**
     * @return string
     */
    public function getRouterPath()
    {
        return $this->get(self::PATH_ROUTER_PATH);
    }

    /**
     * @return int|string|null
     */
    public function isDebug()
    {
        return $this->get(self::PATH_IS_DEBUG_PATH);
    }

    /**
     * @return int|string|null
     */
    public function getDebugTrim()
    {
        return $this->get(self::PATH_DEBUG_TRIM_PATH);
    }
    /**
     * @return int|string|null
     */
    public function getDebugSiteUrl()
    {
        return $this->get(self::PATH_DEBUG_SITE_URL_PATH);
    }
}
