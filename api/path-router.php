<?php


$uri = request_uri();

define('CONTENT_PATH', realpath(__DIR__ . '/../www-content'));

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
    // Detecta tipo de conteúdo
    $mime = mime_content_type($filePath) ?: 'application/octet-stream';
    header("Content-Type: {$mime}");
    header('Content-Length: ' . filesize($filePath));

    // Se quiser forçar download:
    // header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');

    readfile($filePath);
    exit;
}

// Fallback final (se nada encontrado)
http_response_code(404);
require __DIR__ . '/404.php';
