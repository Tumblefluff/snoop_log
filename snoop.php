<?php
$logFile = __DIR__ . '/snoop.log';
$input = file_get_contents('php://input');
error_log('Raw POST data: ' . $input);
$data = json_decode($input, true);

if ($data) {
    $logEntry = sprintf(
        "[%s] User-Agent: %s | Language: %s | Platform: %s | Cookies Enabled: %s | Screen Resolution: %s | Referrer: %s | Current URL: %s\n",
        date('Y-m-d H:i:s'),
        $data['userAgent'] ?? 'Unknown',
        $data['language'] ?? 'Unknown',
        $data['platform'] ?? 'Unknown',
        $data['cookiesEnabled'] ? 'Yes' : 'No',
        $data['screenResolution'] ?? 'Unknown',
        $data['referrer'] ?? 'Unknown',
        $data['currentUrl'] ?? 'Unknown'
    );

    // Write to the log file
    if (!file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX)) {
        error_log('Failed to write to log file.');
    } else {
        error_log('Log entry written successfully.');
    }
} else {
    error_log('JSON parsing failed or no data received.');
}
?>
