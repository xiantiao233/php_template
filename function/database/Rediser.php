<?php

class Rediser
{
    private Redis $redis;
    private string $storeKeyHeader;

    public function __construct()
    {
        $this->redis = new Redis();
        try {
            $this->redis->connect(getConfig('database.redis.host'), getConfig('database.redis.port'));
            $this->redis->auth(getConfig('database.redis.password'));
        } catch (RedisException $e) {
            xtSBack(10000011,$e);
        }
        $this->storeKeyHeader = getConfig('database.redis.storeKeyHeader');
    }

    public function getValue(string $key) : ?string
    {
        try {
            return $this->redis->get($this->storeKeyHeader . $key);
        } catch (RedisException $e) {
            xtSBack(10000011,$e);
        }
    }

    public function setValue(string $key, string $value, int $time) : void
    {
        try {
            $this->redis->set($this->storeKeyHeader.$key, $value, $time);
        } catch (RedisException $e) {
            xtSBack(10000011,$e);
        }
    }

    public function ping() : bool
    {
        try {
            return $this->redis->ping();
        } catch (RedisException $e) {
            xtSBack(10000011,$e);
        }
    }
}