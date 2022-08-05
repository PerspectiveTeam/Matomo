<?php

namespace Perspective\Matomo\Service\Connectivity;

class AllSitesConnection extends Connection
{

    protected $baseParameters = [
        'module' => 'API',
        'method' => 'SitesManager.getAllSites',
        'format' => 'JSON',
        'token_auth' => '',
        'force_api_session' => 1
    ];

}
