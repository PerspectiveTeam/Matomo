<?php

namespace Perspective\Matomo\Service;

use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface;
use Perspective\Matomo\Api\Data\MatomoSiteEntityInterfaceFactory;
use Perspective\Matomo\Helper\Config\Connection;
use Perspective\Matomo\Helper\Config\General;
use Perspective\Matomo\Service\Cache\OperationsCache;
use Perspective\Matomo\Service\Connectivity\AllSitesConnection;

class SiteComparator
{
    protected $siteArray = [];

    /**
     * @var \Magento\Framework\HTTP\ClientInterface
     */
    private $httpClient;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \Perspective\Matomo\Api\Data\MatomoSiteEntityInterfaceFactory
     */
    private $matomoSiteEntityFactory;

    /**
     * @var \Perspective\Matomo\Helper\Config\Connection|\Perspective\Matomo\Service\Connection
     */
    private $connectionHelper;

    /**
     * @var \Perspective\Matomo\Service\Connectivity\AllSitesConnection
     */
    private $connectivity;

    /**
     * @var \Perspective\Matomo\Helper\Config\General
     */
    private $generalHelper;

    /**
     * @var \Perspective\Matomo\Service\Cache\OperationsCache
     */
    private $operationsCache;

    /**
     * @param \Magento\Framework\HTTP\ClientInterface $httpClient
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Perspective\Matomo\Api\Data\MatomoSiteEntityInterfaceFactory $matomoSiteEntityFactory
     * @param \Perspective\Matomo\Helper\Config\Connection $connectionHelper
     * @param \Perspective\Matomo\Service\Connectivity\AllSitesConnection $connectivity
     * @param \Perspective\Matomo\Helper\Config\General $generalHelper
     * @param \Perspective\Matomo\Service\Cache\OperationsCache $operationsCache
     */
    public function __construct(
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        MatomoSiteEntityInterfaceFactory $matomoSiteEntityFactory,
        Connection $connectionHelper,
        ConnectivityInterface $connectivity,
        General $generalHelper,
        OperationsCache $operationsCache
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->matomoSiteEntityFactory = $matomoSiteEntityFactory;
        $this->connectionHelper = $connectionHelper;
        $this->connectivity = $connectivity;
        $this->generalHelper = $generalHelper;
        $this->operationsCache = $operationsCache;
    }

    /**
     * @param $siteUrl
     * @return \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface
     */
    public function getSiteData($siteUrl)
    {
        if ($this->generalHelper->isDebug()) {
            //for debug purposes
            $siteUrl = $this->generalHelper->getDebugSiteUrl();
            if (!$siteUrl) {
                $siteUrl = 'partner.corplife.at';
            }
            $siteUrl = str_replace(explode(',', $this->generalHelper->getDebugTrim()), '', $siteUrl);
            if (strpos($siteUrl, '.at') === false) {
                $siteUrl = $siteUrl . '.at';
            }
        }
        if ($cache = $this->operationsCache->load()) {
            return unserialize($cache)[$siteUrl];
        } else {
            $this->fillSiteArray();
            $this->operationsCache->save(serialize($this->siteArray));
            return $this->siteArray[$siteUrl];
        }
    }

    /**
     * @return array
     */
    protected function fillSiteArray(): array
    {
        if (!$this->siteArray) {
            if ($this->generalHelper->isDebug()) {
                $this->httpClient->setOption(CURLOPT_SSL_VERIFYHOST, false);
                $this->httpClient->setOption(CURLOPT_SSL_VERIFYPEER, false);
            }
            $this->httpClient->get($this->connectionHelper->getMainEndpointUri() . '?' . http_build_query($this->getBaseParameters()));
            $resultUnserialized = $this->serializer->unserialize($this->httpClient->getBody());
            array_walk($resultUnserialized, function ($value, $key) {
                /** @var \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface $matomoSiteEntity */
                $matomoSiteEntity = $this->matomoSiteEntityFactory->create();
                foreach ($value as $key => $data) {
                    // this need for situation like "htpps://wir.corplife.at"
                    if ($key == 'main_url') {
                        $data = $this->prepareUrl($data);
                        $matomoSiteEntity->setMainUrl($data);
                        continue;
                    }
                    $matomoSiteEntity->setDataUsingMethod($key, $data);
                }
                $this->siteArray[$this->prepareUrl($value['main_url'])] = $matomoSiteEntity;
            });
        }
        return $this->siteArray;
    }


    /**
     * @return string[]
     */
    protected function getBaseParameters(): array
    {
        $this->connectivity->setApiMethod('SitesManager.getAllSites');
        return $this->connectivity->getBaseParameters();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function prepareUrl($data)
    {
        return $this->connectivity->prepareUrl($data);
    }

}
