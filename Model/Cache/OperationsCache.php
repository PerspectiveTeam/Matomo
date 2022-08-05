<?php
declare(strict_types=1);

namespace Perspective\Matomo\Model\Cache;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\Serializer\Json;

class OperationsCache extends TagScope
{

    /**
     * @param \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        FrontendPool $cacheFrontendPool
    ) {
        parent::__construct($cacheFrontendPool->get(\Perspective\Matomo\Service\Cache\OperationsCache::TYPE_IDENTIFIER), \Perspective\Matomo\Service\Cache\OperationsCache::CACHE_TAG);
    }
}
