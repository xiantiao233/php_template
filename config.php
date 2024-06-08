<?php

function config_2c17c6393771ee3048ae34d6b380c5ec(): array
{
    return [
        'debug' => true,
        'info' => [
            'APIName' => 'templateAPI',
            'APIVersion' => 'alpha-0.0.1',
            'APIServerURL' => 'http://localhost/template/public',
            "returnHeader" => [
                # 允许的跨域请求
                "Access-Control-Allow-Origin" => [
                    'http://localhost',
                    'https://yghpy.com'
                ]
            ]
        ],
        'jwt' => [
            # 密钥 (至少256位，且定期更换)
            'secret' => '5cae6fb206936d25068fc0a147197efda828726c2af562266e0e037639b8faf55f5cb7bd8cac35fa87030b88f612ab88dfa1bcd8de3f25e3567abd61545585c9',
            # 散列算法 可选的 https://www.php.net/manual/zh/function.hash-hmac-algos.php
            'algo' => 'sha256'
        ],
        # 访问速率限制
        # 1/60 代表每60s至多1次 使用'-'分割以配置多个
        'accessRestrictions' => [
            # 邮件发送限制
            'sendMail' => [
                'qq' => '3/600-8/3600',
                'ip' => ''
            ]
        ],
        'access_token' => [
            # 登入有效期
            'periodValidity' => 3600
        ],
        'security' => [
            # 反向代理设置
            'reverseProxySupport' => [
                'enable' => false,
                # 可以传递ip信息的白名单
                'ipWhitelist' => [
                    # 反转为黑名单
                    'reverse' => false,
                    'list' => ['127.0.0.1']
                ]
            ]
        ],
        # 数据库配置
        'database' => [
            'redis' => [
                'host' => 'localhost',
                'port' => 6379,
                'password' => '',
                # 储存key时额外添加的redis的key头
                'storeKeyHeader' => 'rkzyo.'
            ],
            'mysql' => [
                'host' => 'localhost',
                'port' => 3306,
                'user' => 'root',
                'password' => '123456',
                'database' => 'template_api'
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