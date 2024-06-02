<?php

class Rediser
{
    private Redis $redis;
    private string $storeKeyHeader;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(getConfig('database.redis.host'), getConfig('database.redis.port'));
        $this->redis->auth(getConfig('database.redis.password'));
        $this->storeKeyHeader = getConfig('database.redis.storeKeyHeader');
    }

    public function getValue(string $key) : ?string
    {
        try {
            if ($this->redis->exists($key)) {
                return $this->redis->get($this->storeKeyHeader . $key);
            } else {
                return null;
            }
        } catch (RedisException $e) {

        }
    }

    /**
     * @throws RedisException
     */
    public function setValue(string $key, string $value) : void
    {
        $this->redis->set($this->storeKeyHeader.$key,$value);
    }

    /**
     * @throws RedisException
     */
    public function ping() : bool
    {
        return $this->redis->ping();
    }
}