<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wybierz miasto</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link id="theme-stylesheet" rel="stylesheet" href="miasto_light.css">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const darkTheme = localStorage.getItem("darkTheme") === "true";
            const themeStylesheet = document.getElementById("theme-stylesheet");
            themeStylesheet.href = darkTheme ? "miasto_dark.css" : "miasto_light.css";
        });
    </script>
</head>
<body>
    <div class="container">
        <input type="text" id="search" placeholder="Wyszukaj miasto">
        <ul id="results"></ul>
    </div>

    <script>
        $(document).ready(function () {
            // Obsługa wyszukiwania miasta
            $('#search').on('input', function () {
                let query = $(this).val();
                if (query.length > 0) {
                    $.getJSON("search_city.php", { query: query }, function (data) {
                        $('#results').empty();
                        data.forEach(function (city) {
                            const cityContainer = $('<li>').addClass('city-container');
                            const cityName = $('<div>').text(city.name).addClass('city-name');
                            
                            // Ustawienie tła kontenera miasta na obrazek
                            cityContainer.css('background-image', `url(${city.image})`);
                            
                            cityContainer.append(cityName);

                            // Nasłuchiwanie kliknięcia
                            cityContainer.on('click', function () {
                                window.location.href = `index.php?lat=${city.latitude}&lon=${city.longitude}`;
                            });

                            $('#results').append(cityContainer);
                        });
                    });
                } else {
                    $('#results').empty();
                }
            });
        });
    </script>
</body>
</html>