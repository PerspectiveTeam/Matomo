<?php

namespace Perspective\Matomo\Service\Connectivity;

class PageUrlsConnection extends Connection
{

    protected $baseParameters = [
        'module' => 'API',
        'method' => 'Actions.getPageUrls',
        'format' => 'JSON',
        'token_auth' => '',
        'idSite' => '', // this is the default value and need to be changed before calling the API
        'force_api_session' => 1,
        'flat' => 1,
        'filter_limit' => -1,
        'period' => 'day',
        'date' => 'today', // this is the default value and need to be changed before calling the API
    ];

}
