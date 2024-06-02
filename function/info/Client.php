<?php
class Client {
    private string $clientIP;
    public function __construct() {
        if (!getConfig('security.reverseProxySupport')) {
            $this->clientIP = $_SERVER['REMOTE_ADDR'];
            return;
        }$ip = '';
        // 检查是否存在 HTTP_CLIENT_IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } // 检查是否存在 HTTP_X_FORWARDED_FOR，并处理代理 IP 地址列表
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim(end($ipList));
        } // 获取 REMOTE_ADDR 作为备选方案
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // 对 IP 地址进行验证
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->clientIP = $ip;
        } else {
            $this->clientIP = null;
        }
    }

    /**
     * 获取客户端IP，如果有反向代理使用反向代理传递的ip
     * @return string|null
     */
    function getClientIP(): ?string
    {
        return $this->clientIP;
    }
}
