<?php
require_once __DIR__ . '/../config.php';

class JWT
{
    private string|array|bool|int|null|float $algo;
    private string|array|bool|int|null|float $secret;

    public function __construct()
    {
        $this->algo = getConfig('jwt.algo', 'string');
        $this->secret = getConfig('jwt.secret');
    }

    public function create(array $payload): string
    {
        $header = $this->base64UrlEncode(json_encode(['alg' => $this->algo, 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode($payload));

        $merge = "$header.$payload";
        $signature = $this->sign($merge);

        return "$header.$payload.$signature";
    }

    public function verify(string $jwt): bool|string
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            return false;
        }

        [$headerB64, $payloadB64, $signature] = $parts;

        $header = json_decode($this->base64UrlDecode($headerB64), true);
        $payload = $this->base64UrlDecode($payloadB64);

        if (!$header || $header['typ'] !== 'JWT' || $header['alg'] !== $this->algo) {
            return false;
        }

        $merge = "$headerB64.$payloadB64";
        $expectedSignature = $this->sign($merge);

        return hash_equals($expectedSignature, $signature) ? $payload : false;
    }

    private function sign(string $data): string
    {
        return $this->base64UrlEncode(hash_hmac($this->algo, $data, $this->secret, true));
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}