<?php

// Telegram bot access token
$botToken = 'TOKEN';

// ID of the chat to which you want to send the message
$chatId = 'CHAT_ID';

// MySQL database access details
$host = 'XXXXX';
$dbname = 'XXXX';
$user = 'XXXX';
$pass = 'XXXXX';

// Connect to the database
try {
    $db = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}

// Query to check for a new item in the table
$query = "SELECT id, campo1, campo2 FROM your_table WHERE filter = 'filter_value'";
$stmt = $db->prepare($query);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a new item is found
if ($item) {
    $itemId = $item['id'];
    $campo1 = $item['campo1'];
    $campo2 = $item['campo2'];

    // Check if the item ID is already registered
    $query = "SELECT COUNT(*) FROM sent_items WHERE item_id = :itemId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // If the item ID is not registered, send the message and register the ID
    if ($count == 0) {
        // Message to send
        $message = "New item found:\nField 1: {$campo1}\nField 2: {$campo2}";

        // Telegram API URL
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        // Message parameters
        $params = [
            'chat_id' => $chatId,
            'text' => $message
        ];

        // Initialize a cURL request
        $ch = curl_init();

        // Set the URL and other parameters
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Close the cURL connection
        curl_close($ch);

        // Check if the message was sent successfully
        if ($response === false) {
            echo 'An error occurred while sending the message.';
        } else {
            echo 'Message sent successfully!';

            // Register the item ID in the sent_items table
            $query = "INSERT INTO sent_items (item_id) VALUES (:itemId)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':itemId', $itemId);
            $stmt->execute();
        }
    } else {
        echo 'Item already sent previously.';
    }
} else {
    echo 'No new item found.';
}
?>
