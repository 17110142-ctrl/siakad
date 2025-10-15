<?php
// Paths
$scriptPath = '/home/u852176022/domains/whatsapp.smpmuhammadiyahsuruh.sch.id/public_html/index.js';
$logDir    = '/home/u852176022/domains/whatsapp.smpmuhammadiyahsuruh.sch.id/logs';
$foreverBin= trim(shell_exec('which forever')); // auto‑detect

if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$message = '';
$logs = ['forever.log'=>'', 'out.log'=>'', 'err.log'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $command = escapeshellcmd(sprintf(
        '%s start %s --uid "whatsapp-app" --logFile %s/forever.log --outFile %s/out.log --errFile %s/err.log 2>&1',
        $foreverBin,
        $scriptPath,
        $logDir,
        $logDir,
        $logDir
    ));

    exec($command, $output, $returnVar);
    $message = $returnVar === 0
        ? '✅ Node.js app berhasil dijalankan.'
        : '❌ Gagal menjalankan Node.js app: ' . implode("\n", $output);
}

// Read logs (tail last 100 lines)
foreach ($logs as $filename => &$content) {
    $path = "$logDir/$filename";
    if (file_exists($path)) {
        $content = shell_exec("tail -n 100 " . escapeshellarg($path));
    } else {
        $content = '(file not found)';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Run Node.js & Logs</title>
    <style>
        body { font-family: monospace; }
        pre { background:#f4f4f4; padding:10px; overflow:auto; max-height:300px; }
        .log-container { margin-bottom:2rem; }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <p><strong><?= nl2br(htmlspecialchars($message)) ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <button type="submit">Run Node.js</button>
    </form>

    <?php foreach ($logs as $filename => $content): ?>
        <div class="log-container">
            <h3><?= htmlspecialchars($filename) ?></h3>
            <pre><?= htmlspecialchars($content) ?></pre>
        </div>
    <?php endforeach; ?>
</body>
</html>