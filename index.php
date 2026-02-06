<?php
function getServerIp(): string {
    if (!empty($_SERVER['SERVER_ADDR'])) {
        return $_SERVER['SERVER_ADDR'];
    }
    $host = gethostname();
    if ($host !== false) {
        $ip = gethostbyname($host);
        if ($ip !== $host) {
            return $ip;
        }
    }
    return 'Unknown';
}

$serverIp = htmlspecialchars(getServerIp(), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>StreamLine - v2 [New Feature]</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root { color-scheme: light dark; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans";
            background: #e6f2ff; /* v2 NEW: soft light-blue */
            color: #0f172a;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }
        .card {
            text-align: center;
            padding: 2.5rem 2rem;
            border: 1px solid #cfe3ff;
            border-radius: 12px;
            max-width: 680px;
            backdrop-filter: blur(2px);
            background: rgba(255,255,255,0.6);
            box-shadow: 0 12px 36px rgba(30, 64, 175, 0.15);
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: 2rem;
            letter-spacing: 0.2px;
        }
        .subtitle {
            color: #1e3a8a;
            margin-top: 0.25rem;
            font-size: 1rem;
            font-weight: 500;
        }
        .ip {
            margin-top: 1rem;
            font-weight: 600;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background: #eff6ff;
            color: #1e3a8a;
            padding: 0.45rem 0.65rem;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #bfdbfe;
        }
        .badge {
            margin-top: 1rem;
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            border: 1px solid #93c5fd;
        }
        .feature {
            margin-top: 1rem;
            display: inline-block;
            font-size: 0.92rem;
            color: #0f172a;
            background: #fff;
            border: 1px dashed #93c5fd;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Welcome to <strong>StreamLine</strong> - v2 [New Feature]</h1>
        <p class="subtitle">Development build showcasing a new UI background.</p>
        <div class="feature">✨ New Feature: Light‑blue theme preview</div>
        <div class="ip">Server IP: <?= $serverIp ?></div>
        <div class="badge">Branch: dev • Version: v2</div>
    </main>
</body>
</html>
