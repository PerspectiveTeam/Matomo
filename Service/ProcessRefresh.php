<?php

namespace Perspective\Matomo\Service;

use DateInterval;
use DatePeriod;
use DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Perspective\Matomo\Api\Data\Cleaner\CleanerInterface as CleanForCustomer; /** @see \Perspective\Matomo\Service\Process\CleanForCustomer */
use Perspective\Matomo\Service\Process\CtaClicks;
use Perspective\Matomo\Service\Process\Events;
use Perspective\Matomo\Service\Process\PageUrls;

class ProcessRefresh
{
    /**
     * @var \Perspective\Matomo\Service\SiteComparator
     */
    private $siteComparator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Perspective\Matomo\Service\Process\PageUrls
     */
    private $pageUrls;

    /**
     * @var \Perspective\Matomo\Service\Process\Events
     */
    private $events;

    /**
     * @var CleanForCustomer
     */
    private $cleanValues;

    /**
     * @var CtaClicks
     */
    private $ctaClicks;

    /**
     * @param \Perspective\Matomo\Service\SiteComparator $siteComparator
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Perspective\Matomo\Service\Process\PageUrls $pageUrls
     * @param \Perspective\Matomo\Service\Process\Events $events
     * @param CleanForCustomer $cleanValues
     * @param \Perspective\Matomo\Service\Process\CtaClicks $ctaClicks
     */
    public function __construct(
        SiteComparator $siteComparator,
        StoreManagerInterface $storeManager,
        PageUrls $pageUrls,
        Events $events,
        CleanForCustomer $cleanValues,
        CtaClicks $ctaClicks
    ) {
        $this->siteComparator = $siteComparator;
        $this->storeManager = $storeManager;
        $this->pageUrls = $pageUrls;
        $this->events = $events;
        $this->cleanValues = $cleanValues;
        $this->ctaClicks = $ctaClicks;
    }

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function process($request)
    {
        $result = [];
        $dateInfo = [];
        $eventsData = [];
        if (!$request->getParam('site')) {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        } else {
            $baseUrl = $request->getParam('site');
        }
        $matomoSite = $this->siteComparator->getSiteData($this->siteComparator->prepareUrl($baseUrl));
        if ($request->getParam('range')) {
            $dateRange = explode(',', $request->getParam('range'));
            //$dateRange = ['2022-07-11'];
            $period = new DatePeriod(
                new DateTime(current($dateRange)),
                new DateInterval('P1D'),
                new DateTime(next($dateRange) . ' +1 day')
            );
            $this->pageUrls->setSite($matomoSite);
            $this->events->setSite($matomoSite);
            $this->cleanValues->setSite($matomoSite);
            foreach ($period as $key => $value) {

                //for debugging
                //$this->pageUrls->setDate('2022-03-15');
                //$dateInfo['2022-03-15'] = $this->pageUrls->process();
                //for debugging

                $this->pageUrls->setDate($value->format('Y-m-d'));
                $this->events->setDate($value->format('Y-m-d'));
                $this->cleanValues->setDate($value->format('Y-m-d'));
                $this->pageUrls->setCleaner($this->cleanValues);
                $eventsData[$value->format('Y-m-d')] = $this->events->process();
                $dateInfo[$value->format('Y-m-d')] = $this->pageUrls->process();
            }
            $dateInfo = $this->ctaClicks->process($eventsData, $dateInfo);
            $result['pageUrls'] = $dateInfo;
        }
        return $result;
    }
}
