<?php
// Helper JWT sederhana tanpa library
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
}

define('JWT_SECRET', 'ganti_dengan_secret_key_acak_panjang_kamu');

function create_jwt($payload) {
    $header  = base64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64url_encode(json_encode($payload));
    $sig     = base64url_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$sig";
}

function verify_jwt($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;
    [$header, $payload, $sig] = $parts;
    $expected = base64url_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    if (!hash_equals($expected, $sig)) return null;
    return json_decode(base64url_decode($payload), true);
}

function get_auth() {
    $token = $_COOKIE['auth_token'] ?? null;
    if (!$token) return null;
    return verify_jwt($token);
}

function set_auth_cookie($data) {
    $token = create_jwt($data);
    setcookie('auth_token', $token, [
        'expires'  => time() + 86400, // 1 hari
        'path'     => '/',
        'httponly' => true,
        'secure'   => true,
        'samesite' => 'Lax'
    ]);
}

function clear_auth_cookie() {
    setcookie('auth_token', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'secure'   => true,
        'samesite' => 'Lax'
    ]);
}