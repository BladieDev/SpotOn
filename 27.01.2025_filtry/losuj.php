<?php
// Połączenie z bazą danych
include('server.php');

header('Content-Type: application/json');

try {
    // Zapytanie SQL w celu wylosowania jednego spota
    $query = "SELECT latitude, longitude, location_name FROM locations ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $spot = mysqli_fetch_assoc($result);

        // Zwrócenie wylosowanego spota w formacie JSON
        echo json_encode([
            'latitude' => $spot['latitude'],
            'longitude' => $spot['longitude'],
            'name' => $spot['location_name']
        ]);
    } else {
        echo json_encode(['error' => 'Brak dostępnych spotów w bazie danych.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Błąd serwera: ' . $e->getMessage()]);
}
?>
