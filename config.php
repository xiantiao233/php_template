<?php

function config_2c17c6393771ee3048ae34d6b380c5ec(): array
{
    return [
        'debug' => true,
        'info' => [
            'APIName' => 'templateAPI',
            'APIVersion' => 'alpha-0.0.1',
            'APIServerURL' => 'http://localhost/template/',
            "returnHeader" => [
                "Access-Control-Allow-Origin" => [
                    'http://localhost',
                    'https://yghpy.com'
                ]
            ]
        ],
        'security' => [
            'reverseProxySupport' => true,
            'ipWhitelist' => 'reverse:false,list'
        ],
        'database' => [
            'redis' => [
                'host' => 'localhost',
                'port' => 6379,
                'password' => '123456',
                'storeKeyHeader' => 'rkzyo.'
            ]
        ]
    ];
}

function getConfig(string $key, ?string $expectedType = null): string|int|float|array|bool|null
{
    $keys = explode('.', $key);
    $config = config_2c17c6393771ee3048ae34d6b380c5ec();

    /** @noinspection PhpForeachVariableOverwritesAlreadyDefinedVariableInspection */
    foreach ($keys as $key) {
        if (isset($config[$key])) {
            $config = $config[$key];
        } else {
            http_response_code(500);
            exit("Configuration key '$key' not found.");
        }
    }

    if ($expectedType !== null) {
        switch ($expectedType) {
            case 'string':
                if (!is_string($config)) {
                    http_response_code(500);
                    exit("Expected type 'string' for key '$key', but found type '".gettype($config)."'.");
                }
                break;
            case 'int':
                if (!is_int($config)) {
                    http_response_code(500);
                    exit("Expected type 'int' for key '$key', but found type '".gettype($config)."'.");
                }
                break;
            case 'float':
                if (!is_float($config) && !is_int($config)) { // Accept int for float due to type juggling
                    http_response_code(500);
                    exit("Expected type 'float' for key '$key', but found type '".gettype($config)."'.");
                }
                break;
            case 'array':
                if (!is_array($config)) {
                    http_response_code(500);
                    exit("Expected type 'array' for key '$key', but found type '".gettype($config)."'.");
                }
                break;
            case 'bool':
                if (!is_bool($config)) {
                    http_response_code(500);
                    exit("Expected type 'bool' for key '$key', but found type '".gettype($config)."'.");
                }
                break;
            default:
                http_response_code(500);
                exit("Invalid expected type '$expectedType' specified.");
        }
    }

    return $config;
}