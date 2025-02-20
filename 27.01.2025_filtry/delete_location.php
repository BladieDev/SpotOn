<?php
// Połączenie z bazą danych
include('server.php');

// Sprawdzamy, czy mamy przekazane ID lokalizacji
if (isset($_POST['location_id'])) {
    $location_id = $_POST['location_id'];

    // Upewnij się, że ID jest liczbą
    if (is_numeric($location_id)) {
        // Zapytanie SQL do usunięcia lokalizacji
        $query = "DELETE FROM locations WHERE id = '$location_id'";

        if (mysqli_query($db, $query)) {
            echo "<p>Location deleted successfully.</p>";
        } else {
            echo "<p style='color:red;'>Failed to delete location. Try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid location ID.</p>";
    }
} else {
    echo "<p style='color:red;'>No location ID provided.</p>";
}
?>
