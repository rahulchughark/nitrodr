<?php
include_once('helpers/DataController.php');
require __DIR__ . '/vendor/autoload.php';

set_time_limit(0);
date_default_timezone_set('Asia/Kolkata'); // adjust as needed

$logFile = __DIR__ . '/logs/reminder_mailer.log';
$errorFile = __DIR__ . '/logs/reminder_mailer_error.log';

$intervalSeconds = $argv[1] ?? 10; // Default to 10 seconds if not passed
$dataObj = new DataController;

// ---------- Helper Functions ----------

function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] $message\n", FILE_APPEND);
}

function logError($message)
{
    global $errorFile;
    file_put_contents($errorFile, "[" . date("Y-m-d H:i:s") . "] ERROR: $message\n", FILE_APPEND);
}

function runReminder($dataObj)
{
    try {
        // Example call to your reminder logic
        $dataObj->sendReminderMails();
        logMessage("✅ Reminder mails executed successfully.");
    } catch (Exception $e) {
        logError("runReminder(): " . $e->getMessage());
    }
}

// ---------- Main Loop ----------

logMessage("🚀 Reminder Mailer started (Interval: {$intervalSeconds}s)");

while (true) {
    runReminder($dataObj);
    sleep($intervalSeconds);
}
