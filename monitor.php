<?php
require_once 'config.php';
require_once 'database.php';

session_start();

$data = array(
    'merchant' => $merchand_id,
    'trackId' => $_SESSION["trackId"],
);
$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => json_encode($data),
        'header' => "Content-Type: application/json\r\n" .
            "Accept: application/json\r\n"
    )
);

$context = stream_context_create($options);
$result = file_get_contents("https://api.oxapay.com/merchants/inquiry", false, $context);
$response = json_decode($result);
if ($response->status == "Paid" && $response->payAmount == 9.99) {

    $stmt = $conn->prepare("UPDATE user SET premium=TRUE WHERE id=?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo "OK";
} else {
    echo "NO";
}

?>