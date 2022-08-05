<?php

namespace Perspective\Matomo\Service\Connectivity;

class LastVisitsDetailsConnection extends Connection
{

    protected $baseParameters = [
        'module' => 'API',
        'method' => 'Live.getLastVisitsDetails',
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
