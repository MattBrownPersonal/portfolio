<?php

return [
    'projectId'     => '1',
    'projectKey'    => env('ERRBIT_API_KEY'),
    'environment'   => env('ERRBIT_APP_ENV', 'unset'),
    'enabled' => (env('ERRBIT_API_KEY') != null),
    //leave the following options empty to use defaults
    'host'          => 'https://errbit.tccs.io', //airbrake api host e.g.: 'api.airbrake.io' or 'http://errbit.example.com
    'appVersion'    => null,
    'revision'      => null, //git revision
    'rootDirectory' => null,
    'keysBlacklist' => null, //list of keys containing sensitive information that must be filtered out
    'httpClient'    => null, //http client implementing GuzzleHttp\ClientInterface
];
