<?php

namespace Perspective\Matomo\Api\Data\Cleaner;

use Perspective\Matomo\Api\Data\MatomoSiteEntityInterface;

interface CleanerInterface
{
    public function clean(array $values): ?array;
    public function getMatomoSite(): MatomoSiteEntityInterface;
    public function setSite(MatomoSiteEntityInterface $matomoSite);
    public function getDate();
    public function setDate($date);
}
