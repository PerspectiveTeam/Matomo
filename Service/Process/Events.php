<?php

namespace Perspective\Matomo\Service\Process;

use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface;
use Perspective\Matomo\Api\Data\MatomoSiteEntityInterface;
use Perspective\Matomo\Helper\Config\Connection;
use Perspective\Matomo\Helper\Config\General;
use Perspective\Matomo\Service\Cache\OperationsCache;

class Events
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
        OperationsCache $cache
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->generalHelper = $generalHelper;
        $this->connectionHelper = $connectionHelper;
        $this->connectivity = $connectivity;
        $this->cache = $cache;
    }

    public function process()
    {
        if ($this->generalHelper->isDebug()) {
            $this->httpClient->setOption(CURLOPT_SSL_VERIFYHOST, false);
            $this->httpClient->setOption(CURLOPT_SSL_VERIFYPEER, false);
        }
        if (!empty(unserialize($this->cache->load("events_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}")))) {
            return unserialize($this->cache->load("events_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}"));
        } else {
            $this->httpClient->get($this->connectionHelper->getMainEndpointUri() . '?' . http_build_query($this->getBaseParameters()));
            $curlResult = $this->serializer->unserialize($this->httpClient->getBody());
            $this->cache->save(serialize($curlResult), "events_{$this->getMatomoSite()->getIdsite()}_{$this->getDate()}");
            return $curlResult;
        }

    }

    /**
     * @return string[]
     */
    protected function getBaseParameters(): array
    {
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
}
