<?php

$uri = request_uri();

define('CONTENT_PATH', realpath(__DIR__ . '/../www-content'));

/**
 * Resolve MIME type by extension first, then fallback to mime_content_type()
 */
function getMimeType(string $filePath): string {
    static $mimeMap = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'xml'  => 'application/xml',
        'svg'  => 'image/svg+xml',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'ico'  => 'image/x-icon',
        'pdf'  => 'application/pdf',
        'html' => 'text/html',
        'htm'  => 'text/html',
        'txt'  => 'text/plain',
    ];

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (isset($mimeMap[$ext])) {
        return $mimeMap[$ext];
    }

    $detected = @mime_content_type($filePath);
    return $detected ?: 'application/octet-stream';
}

$fallbackController = function (string $_uri, bool $onlyRoutes = false) {
    // If $onlyRoutes is 'true', other uris will return 404
    if ($onlyRoutes) {
        return __DIR__ . '/404.php';
    }

    $filePath = CONTENT_PATH . '/' . ltrim($_uri, '/');

    return is_file($filePath) ? $filePath : __DIR__ . '/404.php';
};

$filePath = match (trim(ltrim($uri, '/'))) {
    '', '/', 'home', 'index', 'index.php' => CONTENT_PATH . '/index.php',
    'about', 'about.php' => CONTENT_PATH . '/about.php',
    'contact', 'contact.php' => CONTENT_PATH . '/contact.php',
    default => $fallbackController($uri),
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
