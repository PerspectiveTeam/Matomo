<?php

namespace Perspective\Matomo\Api\Data;

interface MatomoSiteEntityInterface
{
    /**
     * String constants for property names
     */
    const IDSITE = "idsite";

    const NAME = "name";

    const MAIN_URL = "main_url";

    const TS_CREATED = "ts_created";

    const ECOMMERCE = "ecommerce";

    const SITESEARCH = "sitesearch";

    const SITESEARCH_KEYWORD_PARAMETERS = "sitesearch_keyword_parameters";

    const SITESEARCH_CATEGORY_PARAMETERS = "sitesearch_category_parameters";

    const TIMEZONE = "timezone";

    const CURRENCY = "currency";

    const EXCLUDE_UNKNOW_URLS = "exclude_unknow_urls";

    const EXCLUDED_IPS = "excluded_ips";

    const EXCLUDED_PARAMETERS = "excluded_parameters";

    const EXCLUDED_USER_AGENTS = "excluded_user_agents";

    const GROUP = "group";

    const TYPE = "type";

    const KEEP_URL_FRAGMENT = "keep_url_fragment";

    const CREATOR_LOGIN = "creator_login";

    const TIMEZONE_NAME = "timezone_name";

    const CURRENCY_NAME = "currency_name";

    /**
     * Getter for Idsite.
     *
     * @return int|null
     */
    public function getIdsite(): ?int;

    /**
     * Setter for Idsite.
     *
     * @param int|null $idsite
     *
     * @return void
     */
    public function setIdsite(?int $idsite): void;

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * Getter for MainUrl.
     *
     * @return string|null
     */
    public function getMainUrl(): ?string;

    /**
     * Setter for MainUrl.
     *
     * @param string|null $mainUrl
     *
     * @return void
     */
    public function setMainUrl(?string $mainUrl): void;

    /**
     * Getter for TsCreated.
     *
     * @return string|null
     */
    public function getTsCreated(): ?string;

    /**
     * Setter for TsCreated.
     *
     * @param string|null $tsCreated
     *
     * @return void
     */
    public function setTsCreated(?string $tsCreated): void;

    /**
     * Getter for Ecommerce.
     *
     * @return string|null
     */
    public function getEcommerce(): ?string;

    /**
     * Setter for Ecommerce.
     *
     * @param string|null $ecommerce
     *
     * @return void
     */
    public function setEcommerce(?string $ecommerce): void;

    /**
     * Getter for Sitesearch.
     *
     * @return string|null
     */
    public function getSitesearch(): ?string;

    /**
     * Setter for Sitesearch.
     *
     * @param string|null $sitesearch
     *
     * @return void
     */
    public function setSitesearch(?string $sitesearch): void;

    /**
     * Getter for SitesearchKeywordParameters.
     *
     * @return string|null
     */
    public function getSitesearchKeywordParameters(): ?string;

    /**
     * Setter for SitesearchKeywordParameters.
     *
     * @param string|null $sitesearchKeywordParameters
     *
     * @return void
     */
    public function setSitesearchKeywordParameters(?string $sitesearchKeywordParameters): void;

    /**
     * Getter for SitesearchCategoryParameters.
     *
     * @return string|null
     */
    public function getSitesearchCategoryParameters(): ?string;

    /**
     * Setter for SitesearchCategoryParameters.
     *
     * @param string|null $sitesearchCategoryParameters
     *
     * @return void
     */
    public function setSitesearchCategoryParameters(?string $sitesearchCategoryParameters): void;

    /**
     * Getter for Timezone.
     *
     * @return string|null
     */
    public function getTimezone(): ?string;

    /**
     * Setter for Timezone.
     *
     * @param string|null $timezone
     *
     * @return void
     */
    public function setTimezone(?string $timezone): void;

    /**
     * Getter for Currency.
     *
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * Setter for Currency.
     *
     * @param string|null $currency
     *
     * @return void
     */
    public function setCurrency(?string $currency): void;

    /**
     * Getter for ExcludeUnknowUrls.
     *
     * @return string|null
     */
    public function getExcludeUnknowUrls(): ?string;

    /**
     * Setter for ExcludeUnknowUrls.
     *
     * @param string|null $excludeUnknowUrls
     *
     * @return void
     */
    public function setExcludeUnknowUrls(?string $excludeUnknowUrls): void;

    /**
     * Getter for ExcludedIps.
     *
     * @return string|null
     */
    public function getExcludedIps(): ?string;

    /**
     * Setter for ExcludedIps.
     *
     * @param string|null $excludedIps
     *
     * @return void
     */
    public function setExcludedIps(?string $excludedIps): void;

    /**
     * Getter for ExcludedParameters.
     *
     * @return string|null
     */
    public function getExcludedParameters(): ?string;

    /**
     * Setter for ExcludedParameters.
     *
     * @param string|null $excludedParameters
     *
     * @return void
     */
    public function setExcludedParameters(?string $excludedParameters): void;

    /**
     * Getter for ExcludedUserAgents.
     *
     * @return string|null
     */
    public function getExcludedUserAgents(): ?string;

    /**
     * Setter for ExcludedUserAgents.
     *
     * @param string|null $excludedUserAgents
     *
     * @return void
     */
    public function setExcludedUserAgents(?string $excludedUserAgents): void;

    /**
     * Getter for Group.
     *
     * @return string|null
     */
    public function getGroup(): ?string;

    /**
     * Setter for Group.
     *
     * @param string|null $group
     *
     * @return void
     */
    public function setGroup(?string $group): void;

    /**
     * Getter for Type.
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Setter for Type.
     *
     * @param string|null $type
     *
     * @return void
     */
    public function setType(?string $type): void;

    /**
     * Getter for KeepUrlFragment.
     *
     * @return int|null
     */
    public function getKeepUrlFragment(): ?int;

    /**
     * Setter for KeepUrlFragment.
     *
     * @param int|null $keepUrlFragment
     *
     * @return void
     */
    public function setKeepUrlFragment(?int $keepUrlFragment): void;

    /**
     * Getter for CreatorLogin.
     *
     * @return string|null
     */
    public function getCreatorLogin(): ?string;

    /**
     * Setter for CreatorLogin.
     *
     * @param string|null $creatorLogin
     *
     * @return void
     */
    public function setCreatorLogin(?string $creatorLogin): void;

    /**
     * Getter for TimezoneName.
     *
     * @return string|null
     */
    public function getTimezoneName(): ?string;

    /**
     * Setter for TimezoneName.
     *
     * @param string|null $timezoneName
     *
     * @return void
     */
    public function setTimezoneName(?string $timezoneName): void;

    /**
     * Getter for CurrencyName.
     *
     * @return int|null
     */
    public function getCurrencyName(): ?string;

    /**
     * Setter for CurrencyName.
     *
     * @param int|null $currencyName
     *
     * @return void
     */
    public function setCurrencyName(?string $currencyName): void;
}
