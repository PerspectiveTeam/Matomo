<?php

namespace Perspective\Matomo\Service\Cache;

class OperationsCache
{
    const CACHE_LIFETIME = 3600; // 1 hour

    const TYPE_IDENTIFIER = 'matomo_operations_cache';

    const CACHE_TAG = 'MATOMO_OPERATIONS_CACHE_TAG';

    /**
     * @var \Perspective\Matomo\Helper\Config\Cache
     */
    protected $cacheHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \Magento\Framework\App\Cache
     */
    private $cache;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @param \Perspective\Matomo\Helper\Config\Cache $cacheHelper
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\App\Cache $cache
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Perspective\Matomo\Helper\Config\Cache $cacheHelper,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\App\Cache $cache,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->cacheHelper = $cacheHelper;
        $this->serializer = $serializer;
        $this->cache = $cache;
        $this->customerSession = $customerSession;
    }

    /**
     * @param null $cacheId
     * @return false|string
     */
    public function load($cacheId = null)
    {
        if ($this->cacheHelper->isCacheEnabled()) {
            if ($cacheId) {
                return $this->cache->load($cacheId) ? $this->serializer->unserialize($this->cache->load($cacheId)) : false;
            }
            //  $customerId = $this->customerSession->getSessionId();
            $result = $this->cache->load(self::TYPE_IDENTIFIER);
            if ($result) {
                return $this->serializer->unserialize($result);
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $data
     * @param string $cacheId
     * @param int $cacheLifetime
     * @return bool
     */
    public function save($data, $cacheId = self::TYPE_IDENTIFIER, $cacheLifetime = self::CACHE_LIFETIME): bool
    {
        if ($this->cacheHelper->isCacheEnabled()) {
            // $customerId = $this->customerSession->getSessionId();
            $data = $this->serializer->serialize($data);
            $this->cache->save($data, $cacheId, [self::CACHE_TAG, $cacheId], $cacheLifetime);
            return true;
        }
        return false;
    }
}
