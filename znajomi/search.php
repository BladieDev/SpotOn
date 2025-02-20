<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'db_connection.php';

// Sprawdzenie połączenia
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Błąd połączenia: ' . $conn->connect_error]);
    exit;
}

if (!isset($_GET['query'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Brak zapytania']);
    exit;
}

$query = $_GET['query'];
$stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ?");
if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Błąd przygotowania zapytania: ' . $conn->error]);
    exit;
}
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'users' => $users]);
?>