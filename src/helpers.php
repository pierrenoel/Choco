<?php 

function vite($entry)
{
    if ($_ENV['APP_ENV'] === 'local') {
        return "http://localhost:5173/{$entry}";
    }

    $manifestPath = __DIR__ . '/../public/build/.vite/manifest.json';

    if (!file_exists($manifestPath)) {
        throw new \Exception("Vite manifest not found. Run npm run build");
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    return '/build/' . $manifest[$entry]['file'];
}

function redirect(string $url, int $status = 200)
{
    http_response_code($status);
    header("location: {$url}");
}