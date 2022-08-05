<?php

namespace Perspective\Matomo\Service\Process;

use Perspective\Matomo\Api\Data\MatomoSiteEntityInterface;

class CleanUnnecessaryData implements \Perspective\Matomo\Api\Data\Cleaner\CleanerInterface
{
    /**
     * @var array|mixed
     */
    protected $allowedKeys;

    /**
     * @var \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface
     */
    protected MatomoSiteEntityInterface $matomoSite;

    protected $date;

    public function __construct(

        $allowedKeys = []
    ) {
        $this->allowedKeys = $allowedKeys;
    }

    public function clean(array $values): ?array
    {
        foreach ($values as $key => $value) {
            if (!in_array($key, $this->allowedKeys)) {
                unset($values[$key]);
            }
        }
        return $values;
    }
    /**
     * @return \Perspective\Matomo\Api\Data\MatomoSiteEntityInterface
     */
    public function getMatomoSite(): MatomoSiteEntityInterface
    {
        return $this->matomoSite;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
    public function setSite(MatomoSiteEntityInterface $matomoSite)
    {
        $this->matomoSite = $matomoSite;
    }

}
