<?php

namespace Perspective\Matomo\Service\Process;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Perspective\Matomo\Api\Data\Cleaner\CleanerInterface;
use Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface;
use Perspective\Matomo\Api\Data\MatomoSiteEntityInterface;
use Perspective\Matomo\Helper\Config\Connection;
use Perspective\Matomo\Helper\Config\General;
use Perspective\Matomo\Helper\DealId;
use Perspective\Matomo\Service\Cache\OperationsCache;

class PageUrls
{
    /**
     * @var \Magento\Framework\HTTP\ClientInterface
     */
    private $httpClient;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \Perspective\Matomo\Helper\Config\General
     */
    private $generalHelper;

    /**
     * @var \Perspective\Matomo\Helper\Config\Connection
     */
    private $connectionHelper;

    /**
     * @var \Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface
     */
    private $connectivity;

    /**
     * @var \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface
     */
    private $matomoSite;

    private $date;

    /**
     * @var \Perspective\Matomo\Service\Cache\OperationsCache
     */
    private $cache;

    /**
     * @var \Perspective\Matomo\Helper\DealId
     */
    private $dealIdHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Perspective\Matomo\Service\Process\CleanForCustomer
     */
    private $cleanValues;

    /**
     * @param \Magento\Framework\HTTP\ClientInterface $httpClient
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Perspective\Matomo\Helper\Config\General $generalHelper
     * @param \Perspective\Matomo\Helper\Config\Connection $connectionHelper
     * @param \Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface $connectivity
     * @param \Perspective\Matomo\Service\Cache\OperationsCache $cache
     */
    public function __construct(
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        General $generalHelper,
        Connection $connectionHelper,
        ConnectivityInterface $connectivity,
        OperationsCache $cache,
        DealId $dealIdHelper,
        ProductRepositoryInterface $productRepository
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->generalHelper = $generalHelper;
        $this->connectionHelper = $connectionHelper;
        $this->connectivity = $connectivity;
        $this->cache = $cache;
        $this->dealIdHelper = $dealIdHelper;
        $this->productRepository = $productRepository;
    }

    public function process()
    {
        if ($this->generalHelper->isDebug()) {
            $this->httpClient->setOption(CURLOPT_SSL_VERIFYHOST, false);
            $this->httpClient->setOption(CURLOPT_SSL_VERIFYPEER, false);
        }
        if (!empty(unserialize($this->cache->load("page_urls_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}")))) {
            return unserialize($this->cache->load("page_urls_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}"));
        } else {
            $this->httpClient->get($this->connectionHelper->getMainEndpointUri() . '?' . http_build_query($this->getBaseParameters()));
            $curlResult = $this->serializer->unserialize($this->httpClient->getBody());
            $processedResult = [];
            array_walk($curlResult, function (&$value, $key) use (&$processedResult) {
                $preliminaryDealIdResult = $this->dealIdHelper->process($value['url']);
                if (is_integer($preliminaryDealIdResult)) {
                    try {
                        $product = $this->productRepository->getById($preliminaryDealIdResult);
                        $dataArray = $product->getData();
                        $dataArrayProcessed = [];
                        array_walk($dataArray, function (&$productValue, $productKey) use (&$dataArrayProcessed) {
                            if (is_scalar($productValue)) {
                                $dataArrayProcessed[$productKey] = $productValue;
                            }
                        });
                        $value = array_merge($value, $dataArrayProcessed);
                    } catch (NoSuchEntityException $e) {
                        //nothing to do
                    }
                    $value['processed_by_date'] = $this->getDate();
                    if ($this->getCleaner()){
                        $value = $this->getCleaner()->clean($value);
                    }
                    if ($value !== null) {
                        $processedResult[$value['url']] = $value;
                    }
                }
            });
            $this->cache->save(serialize($processedResult), "page_urls_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}");
            return $processedResult;
        }

    }

    /**
     * @return string[]
     */
    protected function getBaseParameters(): array
    {
        $this->connectivity->setApiMethod('Actions.getPageUrls');
        $this->connectivity->setParameter('idSite', $this->getMatomoSite()->getIdsite());
        $this->connectivity->setParameter('date', $this->getDate());
        return $this->connectivity->getBaseParameters();
    }

    public function setSite(MatomoSiteEntityInterface $matomoSite)
    {
        $this->matomoSite = $matomoSite;
    }

    /**
     * @return \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface
     */
    public function getMatomoSite(): MatomoSiteEntityInterface
    {
        return $this->matomoSite;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setCleaner(CleanerInterface $cleanValues)
    {
        $this->cleanValues = $cleanValues;
    }

    /**
     * @return \Perspective\Matomo\Service\Process\CleanForCustomer
     */
    public function getCleaner(): ?CleanerInterface
    {
        return $this->cleanValues;
    }
}
