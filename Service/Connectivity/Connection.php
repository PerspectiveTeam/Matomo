<?php

namespace Perspective\Matomo\Service\Connectivity;

use Perspective\Matomo\Api\Data\Connectivity\ConnectivityInterface;

class Connection implements ConnectivityInterface
{
    /**
     * @var \Perspective\Matomo\Helper\Config\Connection
     */
    private $connectionHelper;

    /**
     * @param \Perspective\Matomo\Helper\Config\Connection $connectionHelper
     */
    public function __construct(
        \Perspective\Matomo\Helper\Config\Connection $connectionHelper
    ) {
        $this->connectionHelper = $connectionHelper;
    }

    protected $baseParameters = [
        'module' => 'API',
        'method' => '', // for ex 'SitesManager.getAllSites'
        'format' => 'JSON',
        'token_auth' => '',
        'force_api_session' => 1
    ];

    /**
     * @return string[]
     */
    public function getBaseParameters(): array
    {
        if (!$this->baseParameters['token_auth']) {
            $this->appendToken();
        }
        return $this->baseParameters;
    }

    /**
     * @return void
     */
    protected function appendToken(): void
    {
        $this->baseParameters['token_auth'] = $this->connectionHelper->getMatomoToken();
    }

    public function setApiMethod($apiMethod)
    {
        $this->setParameter('method', $apiMethod);
    }

    public function setParameter($param, $value)
    {
        $this->baseParameters[$param] = $value;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function prepareUrl($data)
    {
        return parse_url($data)['host'] ?? $data;
    }
}
