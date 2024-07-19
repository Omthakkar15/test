<?php

$update = json_decode(file_get_contents("php://input"), TRUE);

if (!$update) {
    // If update is empty or invalid, exit
    exit();
}

// Log all incoming messages and commands
$logFile = 'message_log.txt'; // Log file

// Determine if it's a bot reply
$isBotReply = isset($update['message']['reply_to_message']) && $update['message']['reply_to_message']['from']['username'] == 'YOUR_BOT_USERNAME';

// Prepare log data
$logData = date('Y-m-d H:i:s') . " - Chat ID: " . $update['message']['chat']['id'] . " - " . ($isBotReply ? "Bot" : "User") . ": @" . $update['message']['from']['username'] . " - Message: " . $update['message']['text'] . "\n";

// Append to log file
file_put_contents($logFile, $logData, FILE_APPEND);

// Function to send message to your DM
function sendMessageToDM($message) {
    $botToken = "7480510540:AAGa_b9e4JXGVcREGdXysjg27z7rczc-qWI"; // Replace with your bot token
    $chatId = "1504978999"; // Replace with your Telegram user ID

    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $params = [
        'chat_id' => $chatId,
        'text' => $message,
    ];

    $query = http_build_query($params);
    $url .= '?' . $query;

    // Send the message
    file_get_contents($url);
}

// Prepare message to send to DM
$messageToDM = "";

// Determine message type
if ($isBotReply) {
    // It's a bot reply
    $messageToDM .= "Bot Reply:\n";
} else {
    // It's a user message
    $messageToDM .= "User Message:\n";
}

$messageToDM .= "Chat ID: " . $update['message']['chat']['id'] . "\n";
$messageToDM .= ($isBotReply ? "Bot" : "User") . ": @" . $update['message']['from']['username'] . "\n";
$messageToDM .= "Message: " . $update['message']['text'];

// Send the message to your DM
sendMessageToDM($messageToDM);

?>
