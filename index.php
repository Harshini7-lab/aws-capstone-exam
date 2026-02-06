<?php
// A simple helper to get the server IP reliably across environments.
function getServerIp(): string {
    // Preferred: web server-provided address (Apache/Nginx/FPM)
    if (!empty($_SERVER['SERVER_ADDR'])) {
        return $_SERVER['SERVER_ADDR'];
    }
    // Fallback: resolve local hostname (works in CLI / built-in PHP server)
    $host = gethostname();
    if ($host !== false) {
        $ip = gethostbyname($host);
        if ($ip !== $host) {
            return $ip;
        }
    }
    // Last resort
    return 'Unknown';
}

$serverIp = htmlspecialchars(getServerIp(), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Streamline - v1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root {
            color-scheme: light dark;
        }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background: #ffffff; /* v1 default background */
            color: #1a1a1a;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }
        .card {
            text-align: center;
            padding: 2.5rem 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            max-width: 640px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: 1.9rem;
            letter-spacing: 0.3px;
        }
        .subtitle {
            color: #6b7280;
            margin-top: 0.25rem;
            font-size: 0.95rem;
        }
        .ip {
            margin-top: 1rem;
            font-weight: 600;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background: #f8fafc;
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #e5e7eb;
        }
        .badge {
            margin-top: 1rem;
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            border: 1px solid #c7d2fe;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Welcome to <strong>Streamline</strong> - v1</h1>
        <p class="subtitle">This is the stable release running on your server.</p>
        <div class="ip">Server IP: <?= $serverIp ?></div>
        <div class="badge">Branch: main • Version: v1</div>
    </main>
</body>
</html>
