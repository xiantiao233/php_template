<?php
require_once __DIR__ . '/../../config.php';
class Client {
    private string $clientIP;
    public function __construct() {
        if (!getConfig('security.reverseProxySupport.enable')) {
            $this->clientIP = $_SERVER['REMOTE_ADDR'];
            return;
        }

        $ipWhitelist = getConfig('security.reverseProxySupport.ipWhitelist.list','array');
        $ipWhitelist_reverse = getConfig('security.reverseProxySupport.ipWhitelist.reverse','bool');
        if (!in_array($_SERVER['REMOTE_ADDR'],$ipWhitelist) && !$ipWhitelist_reverse) {
            http_response_code(403);
            echo 'your ip not can use reverseProxySupport header';
            exit();
        }
        if (in_array($_SERVER['REMOTE_ADDR'],$ipWhitelist) && $ipWhitelist_reverse) {
            http_response_code(403);
            echo 'your ip not can use reverseProxySupport header - reverse';
            exit();
        }

        $ip = '';
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
