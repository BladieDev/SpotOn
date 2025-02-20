<?php 
include('server.php'); 

$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$friend_ids = []; // Initialize empty array for friend IDs

if ($isLoggedIn) {
    // Get user ID
    $query = "SELECT id FROM users WHERE username='$username'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];

    // Only fetch friends if we have a valid user_id
    if (isset($user_id)) {
        $friends_query = "SELECT friend_id FROM friends WHERE user_id = '$user_id'";
        $friends_result = mysqli_query($db, $friends_query);
        if ($friends_result) {
            while ($row = mysqli_fetch_assoc($friends_result)) {
                $friend_ids[] = $row['friend_id'];
            }
        }
    }
}

$locations_query = "SELECT * FROM locations";
$locations_result = mysqli_query($db, $locations_query);
$saved_locations = [];
while ($row = mysqli_fetch_assoc($locations_result)) {
    $saved_locations[] = $row;
}

$spot_categories = [
    'skateboarding' => 'Skateboarding',
    'street_workout' => 'Street Workout',
    'parkour' => 'Parkour',
    'graffiti' => 'Graffiti',
    'viewpoint' => 'Viewpoint',
    'hangout' => 'Hangout',
    'other' => 'Other'
];

// Pass categories to JavaScript
echo "<script>const spotCategories = " . json_encode($spot_categories) . ";</script>";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SpotON</title>
    <link id="theme-stylesheet" rel="stylesheet" href="style_glowna_light.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style_popup.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/x-icon" href="img/spotOn_logo.png">
    <script>
        function updateTheme() {
            const darkTheme = localStorage.getItem("darkTheme") === "true";
            const themeStylesheet = document.getElementById("theme-stylesheet");
            themeStylesheet.href = darkTheme ? "style_glowna_dark.css" : "style_glowna_light.css";
            document.body.classList.toggle("dark-theme", darkTheme);
        }

        document.addEventListener("DOMContentLoaded", updateTheme);
        window.addEventListener("storage", updateTheme);
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <p id="login">Welcome, <?php echo $isLoggedIn ? htmlspecialchars($username) : "<a href='login.php'>Zaloguj się</a>"; ?>!</p>
        <ul>
            <?php if ($isLoggedIn): ?>
                <li><a href="powiadomienia/notifications.html">Powiadomienia</a></li>
                <li><button id="losuj_spota">Losuj SPOT-a</button></li>
                <li><a href="miasto.php">Miasto</a></li>
                <li><a href="#">Twoje komentarze</a></li>
                <li>
                    <div class="filter-section">
                        <h3>Filtry</h3>
                        <form id="filters-form">
                            <div class="filter-item">
                                <label for="radius">Promień (km):</label>
                                <input type="range" id="radius" name="radius" min="1" max="50" value="10">
                                <span id="radius-value">10 km</span>
                            </div>
                            <div class="filter-item">
                                <label>
                                    <input type="checkbox" id="friends-spots" name="friends-spots">
                                    Tylko spoty znajomych
                                </label>
                            </div>
                            <button type="button" id="apply-filters">Zastosuj filtry</button>
                        </form>
                    </div>
                </li>
                <li><a href="znajomi/znajomi.html">Znajomi</a></li>
                <li><a href="ustawienia/ustawienia.html">Ustawienia</a></li>
                <li><a href="logout.php" class="logout">Wyloguj</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="map-container">
        <div id="map"></div>
        <div id="message"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);

    // Pobierz wartości z URL lub ustaw domyślne
    let lat = parseFloat(urlParams.get('lat')) || 53.4285; // Domyślna szerokość geograficzna
    let lon = parseFloat(urlParams.get('lon')) || 14.5523; // Domyślna długość geograficzna

    // Inicjalizacja mapy z domyślnymi współrzędnymi
    const map = L.map("map").setView([lat, lon], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    // Dodanie markera dla parametrów w URL
    if (urlParams.has('lat') && urlParams.has('lon')) {
        L.marker([lat, lon]).addTo(map).bindPopup("Wybrana lokalizacja").openPopup();
    }
    navigator.geolocation.watchPosition(success, error);
    
    var locationIcon = L.icon({
        iconUrl: 'img/icon.png',
        iconSize: [50, 50],
        iconAnchor: [25, 50],
        popupAnchor: [0, -50]
    });

    let userLocation = null;

    function success(pos) {
        const latitude = pos.coords.latitude;
        const lgn = pos.coords.longitude;
        
        userLocation = [latitude, lgn]; // Store user location

        L.marker([latitude, lgn], {icon:locationIcon}).addTo(map);
        
        if(marker){
            map.removeLayer(marker);
        }
        map.setView([latitude, lgn], 13);
    }

    function error(err) {
        if(err == 1){
            alert("Proszę zezwolić na dostęp do lokalizacji");
        }else{
            alert("Nie udało się pobrać lokalizacji");
        }
    }

    // Funkcja obsługująca losowanie spota
    function losujSpota() {
        $.ajax({
            url: 'losuj_spot.php', // Skrypt backendowy zwracający wylosowanego spota
            method: 'GET',
            success: function (response) {
                const spotData = JSON.parse(response); // Oczekujemy {latitude: ..., longitude: ...}

                // Ustaw nowe współrzędne jako default location
                lat = spotData.latitude;
                lon = spotData.longitude;

                // Zmień URL w przeglądarce bez przeładowania strony
                const newUrl = `${window.location.pathname}?lat=${lat}&lon=${lon}`;
                window.history.pushState({}, '', newUrl);

                // Zaktualizuj widok mapy na nowe współrzędne
                map.setView([lat, lon], 13);

                // Dodanie markera dla wylosowanego spota
                L.marker([lat, lon]).addTo(map).bindPopup("Wylosowany SPOT").openPopup();
            },
            error: function () {
                alert("Nie udało się wylosować spota. Spróbuj ponownie.");
            }
        });
    }

    // Podpięcie funkcji losowania spota do przycisku
    const losujButton = document.getElementById('losuj-spot-btn');
    if (losujButton) {
        losujButton.addEventListener('click', function () {
            losujSpota();
        });
    }

    // Funkcja obsługująca zmianę widoku mapy na wybrane miasto
    function changeDefaultLocation(cityId) {
        $.ajax({
            url: 'miasto.php', // Skrypt backendowy, który zwróci współrzędne miasta
            method: 'GET',
            data: { id: cityId }, // Wysyłamy ID wybranego miasta
            success: function (response) {
                const cityData = JSON.parse(response); // Oczekujemy {latitude: ..., longitude: ...}

                // Zmień widok mapy na nowe współrzędne
                lat = cityData.latitude;
                lon = cityData.longitude;

                map.setView([lat, lon], 13); // Ustawienie widoku mapy na nowe współrzędne

                // Dodanie markera dla wybranego miasta
                L.marker([lat, lon]).addTo(map).bindPopup("Wybrane miasto").openPopup();

                // Aktualizacja URL z nowymi współrzędnymi
                const newUrl = `${window.location.pathname}?lat=${lat}&lon=${lon}`;
                window.history.pushState({}, '', newUrl);
            },
            error: function () {
                alert("Nie udało się załadować danych miasta. Spróbuj ponownie.");
            }
        });
    }

    // Obsługa kliknięcia w miasto (przykład: lista miast w menu)
    const cityLinks = document.querySelectorAll(".city-link"); // Przyjmujemy, że miasta mają klasę 'city-link'

    cityLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const cityId = this.getAttribute("data-id"); // ID miasta przypisane do atrybutu 'data-id'
            changeDefaultLocation(cityId); // Wywołaj funkcję zmieniającą domyślną lokalizację
        });
    });

                const markers = {};
                const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
                const currentUserId = <?php echo json_encode($isLoggedIn ? $user_id : null); ?>;
                const savedLocations = <?php echo json_encode($saved_locations); ?>;
                const friendIds = <?php echo json_encode($friend_ids); ?>;

                savedLocations.forEach(function(location) {
                    const latlng = [location.latitude, location.longitude];
                    const isFriendSpot = friendIds.includes(parseInt(location.user_id));
                    
                    const markerIcon = new L.Icon({
                        iconUrl: isFriendSpot ? 'img/icons/markers/marker-icon-green.png' : 'img/icons/markers/marker-icon.png',
                        shadowUrl: 'img/icons/markers/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });

                    const marker = L.marker(latlng, { icon: markerIcon }).addTo(map);
                    marker.locationId = location.id;
                    marker.userId = location.user_id;
                    marker.isFriendSpot = isFriendSpot;
                    markers[latlng] = marker;

                    marker.on("click", function() {
                        const popupContent = document.createElement('div');
                        popupContent.className = 'popup-form ' + (marker.userId === currentUserId ? 'existing-spot' : 'other-spot');
                        
                        const nameInput = document.createElement('input');
                        nameInput.className = 'popup-input';
                        nameInput.value = location.location_name;
                        nameInput.readOnly = true;
                        popupContent.appendChild(nameInput);

                        const buttonContainer = document.createElement('div');
                        buttonContainer.className = 'button-container';

                        if (marker.userId === currentUserId) {
                            const deleteBtn = document.createElement('button');
                            deleteBtn.className = 'popup-button delete-btn';
                            deleteBtn.textContent = 'Delete';
                            buttonContainer.appendChild(deleteBtn);

                            deleteBtn.addEventListener('click', function() {
                                $.ajax({
                                    url: 'delete_location.php',
                                    method: 'POST',
                                    data: { location_id: marker.locationId },
                                    success: function(response) {
                                        $('#message').html(response);
                                        map.removeLayer(marker);
                                        delete markers[latlng];
                                    },
                                    error: function() {
                                        $('#message').html("<p style='color:red;'>Failed to delete location. Try again.</p>");
                                    }
                                });
                            });
                        }

                        popupContent.appendChild(buttonContainer);
                        marker.bindPopup(popupContent).openPopup();
                    });
                });

                if (isLoggedIn) {
                    map.on('click', function(e) {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        const latlngKey = `${lat},${lng}`;

                        if (markers[latlngKey]) {
                            map.removeLayer(markers[latlngKey]);
                            delete markers[latlngKey];
                        } else {
                            const marker = L.marker([lat, lng], {
                                icon: new L.Icon({
                                    iconUrl: 'img/markers/marker-blue.png',
                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41]
                                })
                            }).addTo(map);

                            const popupContent = document.createElement('div');
                            popupContent.className = 'popup-form new-spot';

                            // Name input
                            const inputField = document.createElement('input');
                            inputField.className = 'popup-input';
                            inputField.type = 'text';
                            inputField.placeholder = 'Enter location name';

                            // Category select
                            const categorySelect = document.createElement('select');
                            categorySelect.className = 'category-select';
                            Object.entries(spotCategories).forEach(([value, label]) => {
                                const option = document.createElement('option');
                                option.value = value;
                                option.textContent = label;
                                categorySelect.appendChild(option);
                            });

                            // File upload
                            const fileContainer = document.createElement('div');
                            fileContainer.className = 'file-upload';
                            
                            const fileLabel = document.createElement('label');
                            fileLabel.textContent = 'Upload Photo';
                            
                            const fileInput = document.createElement('input');
                            fileInput.type = 'file';
                            fileInput.accept = 'image/*';
                            
                            const imagePreview = document.createElement('img');
                            imagePreview.className = 'preview-image';
                            imagePreview.style.display = 'none';

                            fileLabel.appendChild(fileInput);
                            fileContainer.appendChild(fileLabel);
                            fileContainer.appendChild(imagePreview);

                            fileInput.addEventListener('change', function(e) {
                                if (e.target.files && e.target.files[0]) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        imagePreview.src = e.target.result;
                                        imagePreview.style.display = 'block';
                                    };
                                    reader.readAsDataURL(e.target.files[0]);
                                }
                            });

                            // Buttons
                            const buttonContainer = document.createElement('div');
                            buttonContainer.className = 'button-container';

                            const saveBtn = document.createElement('button');
                            saveBtn.className = 'popup-button save-btn';
                            saveBtn.textContent = 'Save';

                            const deleteBtn = document.createElement('button');
                            deleteBtn.className = 'popup-button delete-btn';
                            deleteBtn.textContent = 'Cancel';

                            buttonContainer.appendChild(saveBtn);
                            buttonContainer.appendChild(deleteBtn);

                            // Append all elements
                            popupContent.appendChild(inputField);
                            popupContent.appendChild(categorySelect);
                            popupContent.appendChild(fileContainer);
                            popupContent.appendChild(buttonContainer);

                            marker.bindPopup(popupContent).openPopup();

                            // Event handlers
                            deleteBtn.addEventListener('click', function() {
                                map.removeLayer(marker);
                                delete markers[latlngKey];
                            });

                            saveBtn.addEventListener('click', function() {
                                const spotName = inputField.value.trim();
                                const category = categorySelect.value;
                                const photoFile = fileInput.files[0];

                                if (!spotName) {
                                    alert("Please enter a location name.");
                                    return;
                                }

                                const formData = new FormData();
                                formData.append('user_id', currentUserId);
                                formData.append('location_name', spotName);
                                formData.append('category', category);
                                formData.append('latitude', lat);
                                formData.append('longitude', lng);
                                if (photoFile) {
                                    formData.append('photo', photoFile);
                                }

                                $.ajax({
                                    url: 'save_location.php',
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        $('#message').html(response);
                                        marker.bindPopup(spotName);
                                        marker.userId = currentUserId;
                                        location.reload();
                                    },
                                    error: function() {
                                        $('#message').html("<p style='color:red;'>Failed to save location. Try again.</p>");
                                    }
                                });
                            });
                        }
                    });
                } else {
                    $('#message').html("<p>Log in to add locations!</p>");
                }

                // Filter functionality
                let radiusCircle = null;

                // Function to update the circle and filters
                function updateFilters(radius) {
                    if (!userLocation) {
                        alert('Waiting for your location. Please try again in a moment.');
                        return;
                    }

                    const radiusInMeters = radius * 1000; // Convert to meters
                    const showFriendsOnly = document.getElementById('friends-spots').checked;

                    // Remove existing radius circle if any
                    if (radiusCircle) {
                        map.removeLayer(radiusCircle);
                    }

                    // Draw new radius circle centered on user location
                    radiusCircle = L.circle(userLocation, {
                        radius: radiusInMeters,
                        color: '#3388ff',
                        fillColor: '#3388ff',
                        fillOpacity: 0.1
                    }).addTo(map);

                    // Filter markers based on distance and friend status
                    Object.values(markers).forEach(marker => {
                        const markerLatLng = marker.getLatLng();
                        const distance = L.latLng(userLocation).distanceTo(markerLatLng);
                        const isInRadius = distance <= radiusInMeters;
                        
                        if (showFriendsOnly) {
                            // Show only friends' spots within radius
                            marker.setOpacity(isInRadius && marker.isFriendSpot ? 1 : 0.2);
                        } else {
                            // Show all spots within radius
                            marker.setOpacity(isInRadius ? 1 : 0.2);
                        }
                    });
                }

                // Update radius value display and circle when slider moves
                document.getElementById('radius').addEventListener('input', function() {
                    const radius = this.value;
                    document.getElementById('radius-value').textContent = radius + ' km';
                    updateFilters(radius);
                });

                // Handle the apply filters button for the friends-only toggle
                document.getElementById('apply-filters').addEventListener('click', function() {
                    const radius = document.getElementById('radius').value;
                    updateFilters(radius);
                });

                // Initialize filters with default radius
                const initialRadius = document.getElementById('radius').value;
                updateFilters(initialRadius);
            });
        </script>
    </div>
</body>
</html>