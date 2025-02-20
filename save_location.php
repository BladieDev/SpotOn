<?php
session_start();
include('server.php');

if (!isset($_POST['user_id']) || !isset($_POST['location_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$user_id = $_POST['user_id'];
$location_name = $_POST['location_name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$category = $_POST['category'];

$photo_path = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/spots/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
        $photo_path = $target_path;
    }
}

$query = "INSERT INTO locations (user_id, location_name, latitude, longitude, category, photo_path) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("isddss", $user_id, $location_name, $latitude, $longitude, $category, $photo_path);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Location saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving location']);
}
?>
