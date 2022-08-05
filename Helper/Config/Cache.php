<?php

namespace Perspective\Matomo\Helper\Config;

use Magento\Framework\App\Cache\State;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Perspective\Matomo\Service\Cache\OperationsCache;

class Cache extends AbstractHelper
{
    const PATH_CACHE_ENABLE = 'matomo/cache/enabled';

    /**
     * @var \Perspective\Matomo\Helper\Config\General
     */
    private $generalConfigHelper;

    /**
     * @var \Magento\Framework\App\Cache\State
     */
    private $cacheState;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Perspective\Matomo\Helper\Config\General $generalConfigHelper
     * @param \Magento\Framework\App\Cache\State $cacheState
     */
    public function __construct(
        Context $context,
        General $generalConfigHelper,
        State $cacheState
    ) {
        parent::__construct($context);
        $this->generalConfigHelper = $generalConfigHelper;
        $this->cacheState = $cacheState;
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
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->generalConfigHelper->isEnabled() && !!$this->get(self::PATH_CACHE_ENABLE) && $this->cacheState->isEnabled(OperationsCache::TYPE_IDENTIFIER);
    }

}
