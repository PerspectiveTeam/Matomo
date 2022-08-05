<?php

namespace Perspective\Matomo\Model\Data;

use Magento\Framework\DataObject;
use Perspective\Matomo\Api\Data\MatomoSiteEntityInterface;

class MatomoSiteEntity extends DataObject implements MatomoSiteEntityInterface
{
    /**
     * Getter for Idsite.
     *
     * @return int|null
     */
    public function getIdsite(): ?int
    {
        return $this->getData(self::IDSITE) === null ? null
            : (int)$this->getData(self::IDSITE);
    }

    /**
     * Setter for Idsite.
     *
     * @param int|null $idsite
     *
     * @return void
     */
    public function setIdsite(?int $idsite): void
    {
        $this->setData(self::IDSITE, $idsite);
    }

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * Getter for MainUrl.
     *
     * @return string|null
     */
    public function getMainUrl(): ?string
    {
        return $this->getData(self::MAIN_URL);
    }

    /**
     * Setter for MainUrl.
     *
     * @param string|null $mainUrl
     *
     * @return void
     */
    public function setMainUrl(?string $mainUrl): void
    {
        $this->setData(self::MAIN_URL, $mainUrl);
    }

    /**
     * Getter for TsCreated.
     *
     * @return string|null
     */
    public function getTsCreated(): ?string
    {
        return $this->getData(self::TS_CREATED);
    }

    /**
     * Setter for TsCreated.
     *
     * @param string|null $tsCreated
     *
     * @return void
     */
    public function setTsCreated(?string $tsCreated): void
    {
        $this->setData(self::TS_CREATED, $tsCreated);
    }

    /**
     * Getter for Ecommerce.
     *
     * @return string|null
     */
    public function getEcommerce(): ?string
    {
        return $this->getData(self::ECOMMERCE);
    }

    /**
     * Setter for Ecommerce.
     *
     * @param string|null $ecommerce
     *
     * @return void
     */
    public function setEcommerce(?string $ecommerce): void
    {
        $this->setData(self::ECOMMERCE, $ecommerce);
    }

    /**
     * Getter for Sitesearch.
     *
     * @return string|null
     */
    public function getSitesearch(): ?string
    {
        return $this->getData(self::SITESEARCH);
    }

    /**
     * Setter for Sitesearch.
     *
     * @param string|null $sitesearch
     *
     * @return void
     */
    public function setSitesearch(?string $sitesearch): void
    {
        $this->setData(self::SITESEARCH, $sitesearch);
    }

    /**
     * Getter for SitesearchKeywordParameters.
     *
     * @return string|null
     */
    public function getSitesearchKeywordParameters(): ?string
    {
        return $this->getData(self::SITESEARCH_KEYWORD_PARAMETERS);
    }

    /**
     * Setter for SitesearchKeywordParameters.
     *
     * @param string|null $sitesearchKeywordParameters
     *
     * @return void
     */
    public function setSitesearchKeywordParameters(?string $sitesearchKeywordParameters): void
    {
        $this->setData(self::SITESEARCH_KEYWORD_PARAMETERS, $sitesearchKeywordParameters);
    }

    /**
     * Getter for SitesearchCategoryParameters.
     *
     * @return string|null
     */
    public function getSitesearchCategoryParameters(): ?string
    {
        return $this->getData(self::SITESEARCH_CATEGORY_PARAMETERS);
    }

    /**
     * Setter for SitesearchCategoryParameters.
     *
     * @param string|null $sitesearchCategoryParameters
     *
     * @return void
     */
    public function setSitesearchCategoryParameters(?string $sitesearchCategoryParameters): void
    {
        $this->setData(self::SITESEARCH_CATEGORY_PARAMETERS, $sitesearchCategoryParameters);
    }

    /**
     * Getter for Timezone.
     *
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->getData(self::TIMEZONE);
    }

    /**
     * Setter for Timezone.
     *
     * @param string|null $timezone
     *
     * @return void
     */
    public function setTimezone(?string $timezone): void
    {
        $this->setData(self::TIMEZONE, $timezone);
    }

    /**
     * Getter for Currency.
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->getData(self::CURRENCY);
    }

    /**
     * Setter for Currency.
     *
     * @param string|null $currency
     *
     * @return void
     */
    public function setCurrency(?string $currency): void
    {
        $this->setData(self::CURRENCY, $currency);
    }

    /**
     * Getter for ExcludeUnknowUrls.
     *
     * @return string|null
     */
    public function getExcludeUnknowUrls(): ?string
    {
        return $this->getData(self::EXCLUDE_UNKNOW_URLS);
    }

    /**
     * Setter for ExcludeUnknowUrls.
     *
     * @param string|null $excludeUnknowUrls
     *
     * @return void
     */
    public function setExcludeUnknowUrls(?string $excludeUnknowUrls): void
    {
        $this->setData(self::EXCLUDE_UNKNOW_URLS, $excludeUnknowUrls);
    }

    /**
     * Getter for ExcludedIps.
     *
     * @return string|null
     */
    public function getExcludedIps(): ?string
    {
        return $this->getData(self::EXCLUDED_IPS);
    }

    /**
     * Setter for ExcludedIps.
     *
     * @param string|null $excludedIps
     *
     * @return void
     */
    public function setExcludedIps(?string $excludedIps): void
    {
        $this->setData(self::EXCLUDED_IPS, $excludedIps);
    }

    /**
     * Getter for ExcludedParameters.
     *
     * @return string|null
     */
    public function getExcludedParameters(): ?string
    {
        return $this->getData(self::EXCLUDED_PARAMETERS);
    }

    /**
     * Setter for ExcludedParameters.
     *
     * @param string|null $excludedParameters
     *
     * @return void
     */
    public function setExcludedParameters(?string $excludedParameters): void
    {
        $this->setData(self::EXCLUDED_PARAMETERS, $excludedParameters);
    }

    /**
     * Getter for ExcludedUserAgents.
     *
     * @return string|null
     */
    public function getExcludedUserAgents(): ?string
    {
        return $this->getData(self::EXCLUDED_USER_AGENTS);
    }

    /**
     * Setter for ExcludedUserAgents.
     *
     * @param string|null $excludedUserAgents
     *
     * @return void
     */
    public function setExcludedUserAgents(?string $excludedUserAgents): void
    {
        $this->setData(self::EXCLUDED_USER_AGENTS, $excludedUserAgents);
    }

    /**
     * Getter for Group.
     *
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->getData(self::GROUP);
    }

    /**
     * Setter for Group.
     *
     * @param string|null $group
     *
     * @return void
     */
    public function setGroup(?string $group): void
    {
        $this->setData(self::GROUP, $group);
    }

    /**
     * Getter for Type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Setter for Type.
     *
     * @param string|null $type
     *
     * @return void
     */
    public function setType(?string $type): void
    {
        $this->setData(self::TYPE, $type);
    }

    /**
     * Getter for KeepUrlFragment.
     *
     * @return int|null
     */
    public function getKeepUrlFragment(): ?int
    {
        return $this->getData(self::KEEP_URL_FRAGMENT) === null ? null
            : (int)$this->getData(self::KEEP_URL_FRAGMENT);
    }

    /**
     * Setter for KeepUrlFragment.
     *
     * @param int|null $keepUrlFragment
     *
     * @return void
     */
    public function setKeepUrlFragment(?int $keepUrlFragment): void
    {
        $this->setData(self::KEEP_URL_FRAGMENT, $keepUrlFragment);
    }

    /**
     * Getter for CreatorLogin.
     *
     * @return string|null
     */
    public function getCreatorLogin(): ?string
    {
        return $this->getData(self::CREATOR_LOGIN);
    }

    /**
     * Setter for CreatorLogin.
     *
     * @param string|null $creatorLogin
     *
     * @return void
     */
    public function setCreatorLogin(?string $creatorLogin): void
    {
        $this->setData(self::CREATOR_LOGIN, $creatorLogin);
    }

    /**
     * Getter for TimezoneName.
     *
     * @return string|null
     */
    public function getTimezoneName(): ?string
    {
        return $this->getData(self::TIMEZONE_NAME);
    }

    /**
     * Setter for TimezoneName.
     *
     * @param string|null $timezoneName
     *
     * @return void
     */
    public function setTimezoneName(?string $timezoneName): void
    {
        $this->setData(self::TIMEZONE_NAME, $timezoneName);
    }

    /**
     * Getter for CurrencyName.
     *
     * @return string|null
     */
    public function getCurrencyName(): ?string
    {
        return $this->getData(self::CURRENCY_NAME) === null ? null
            : (int)$this->getData(self::CURRENCY_NAME);
    }

    /**
     * Setter for CurrencyName.
     *
     * @param string|null $currencyName
     *
     * @return void
     */
    public function setCurrencyName(?string $currencyName): void
    {
        $this->setData(self::CURRENCY_NAME, $currencyName);
    }
}
