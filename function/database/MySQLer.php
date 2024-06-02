<?php

class MySQLer
{
    function createMySQLConn(): mysqli
    {
        try {
            return new mysqli(
                getConfig('database.mysql.host'),
                getConfig('database.mysql.user'),
                getConfig('database.mysql.password'),
                getConfig('database.mysql.database'),
                getConfig('database.mysql.port'));
        } catch (Exception $e) {
            xtSBack(10000020,$e);
        }
    }
}