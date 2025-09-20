<?php

$uri = request_uri();

define('CONTENT_PATH', realpath(__DIR__ . '/../www-content'));

/**
 * Resolve MIME type by extension first, then fallback to mime_content_type()
 */
function getMimeType(string $filePath): string
{
    static $mimeMap = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'svg' => 'image/svg+xml',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        'html' => 'text/html',
        'htm' => 'text/html',
        'txt' => 'text/plain',
    ];

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (isset($mimeMap[$ext])) {
        return $mimeMap[$ext];
    }

    $detected = mime_content_type($filePath);

    return $detected ?: 'application/octet-stream';
}

/**
 * Remove query string e normaliza extensão .php
 */
function normalizeRoute(string $uri): string
{
    // Remove query string
    $path = parse_url($uri, PHP_URL_PATH) ?? '';
    $path = trim($path, '/');

    // Se terminar com .php, remove a extensão
    if (str_ends_with($path, '.php')) {
        $path = substr($path, 0, -4);
    }

    return $path;
}

$fallbackController = function (string $_uri, bool $onlyRoutes = false) {
    if ($onlyRoutes) {
        return __DIR__ . '/404.php';
    }

    $filePath = CONTENT_PATH . '/' . ltrim($_uri, '/');

    return is_file($filePath) ? $filePath : __DIR__ . '/404.php';
};

$route = normalizeRoute($uri);

$filePath = match ($route) {
    '', '/', 'index', 'index.php' => CONTENT_PATH . '/index.php',
    'chat', 'chat.php' => CONTENT_PATH . '/chat.php',
    'data', 'data.php' => CONTENT_PATH . '/data.php',
    'emergencias', 'emergencias.php' => CONTENT_PATH . '/emergencias.php',
    'empresa', 'empresa.php' => CONTENT_PATH . '/empresa.php',
    'feed', 'feed.php' => CONTENT_PATH . '/feed.php',
    'home', 'home.php' => CONTENT_PATH . '/home.php',
    'logout', 'logout.php' => CONTENT_PATH . '/logout.php',
    'map', 'map.php' => CONTENT_PATH . '/map.php',
    'profile', 'profile.php' => CONTENT_PATH . '/profile.php',
    default => $fallbackController($route),
};

// Se for um .php conhecido, "require":
if (str_ends_with($filePath, '.php') && strpos($filePath, CONTENT_PATH) === 0) {
    require $filePath;

    exit;
}

// Caso contrário, entregar o arquivo bruto com o tipo MIME correto
if (is_file($filePath)) {
    $mime = getMimeType($filePath);
    header("Content-Type: {$mime}");
    header('Content-Length: ' . filesize($filePath));

    readfile($filePath);

    exit;
}

// Fallback final (se nada encontrado)
http_response_code(404);

require __DIR__ . '/404.php';
