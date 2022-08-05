<?php

namespace Perspective\Matomo\Service\Process;

use Perspective\Matomo\Helper\Config\Cta;
use Perspective\Matomo\Helper\Config\General;
use Perspective\Matomo\Helper\DealId;
use Perspective\Matomo\Service\Cache\OperationsCache;

class CtaClicks
{
    /**
     * @var \Perspective\Matomo\Helper\Config\Cta
     */
    private $ctaHelper;

    /**
     * @var \Perspective\Matomo\Helper\DealId
     */
    private $dealIdHelper;

    /**
     * @var \Perspective\Matomo\Service\Cache\OperationsCache
     */
    private $cache;

    /**
     * @param \Perspective\Matomo\Helper\Config\Cta $ctaHelper
     * @param \Perspective\Matomo\Helper\DealId $dealIdHelper
     * @param \Perspective\Matomo\Service\Cache\OperationsCache $cache
     */
    public function __construct(
        Cta $ctaHelper,
        DealId $dealIdHelper,
        OperationsCache $cache
    ) {
        $this->ctaHelper = $ctaHelper;
        $this->dealIdHelper = $dealIdHelper;
        $this->cache = $cache;
    }

    /**
     * @param array $eventsData
     * @param array $dateInfo
     * @return array
     */
    public function process(array $eventsData, array $dateInfo): array
    {
        $dealCtaCountArr = [];
        foreach ($eventsData as $date => $visitors) {
            $dealCtaCountArr[$date] = [];
            if (!empty(unserialize($this->cache->load("cta_clicks_{$date}")))) {
                $dealCtaCountArr[$date] = unserialize($this->cache->load("cta_clicks_{$date}"));
            } else {
                foreach ($visitors as $visitorData) {
                    if (isset($visitorData['actionDetails'])) {
                        foreach ($visitorData['actionDetails'] as $action) {
                            if ($action['type'] == 'event' && $action[$this->ctaHelper->getEventType()] == $this->ctaHelper->getEventValue()) {
                                $dealId = is_integer($this->dealIdHelper->process($action['url'])) ? $this->dealIdHelper->process($action['url']) : 0;
                                if (!isset($dealCtaCountArr[$date][$dealId])) {
                                    $dealCtaCountArr[$date][$dealId] = 0;
                                }
                                $dealCtaCountArr[$date][$dealId] += 1;
                            }
                        }
                    }
                }
                $this->cache->save(serialize($dealCtaCountArr[$date]), "cta_clicks_{$date}");
            }
        }
        foreach ($dateInfo as $date => &$urls) {
            foreach ($urls as $url => &$urlData) {
                $dealId = is_integer($this->dealIdHelper->process($urlData['url'])) ? $this->dealIdHelper->process($urlData['url']) : 0;
                $urlData['ctaCount'] = $dealCtaCountArr[$date][$dealId] ?? 0;
            }
        }
        return $dateInfo;
    }
}
