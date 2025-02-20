<?php
session_start();

$username = "";
$email    = "";
$errors = array(); 

// Połączenie z bazą danych
$db = mysqli_connect('dpg-cupidqhopnds73953dl0-a', 'admin', 'Rqc8I9lKkRwJgvoJ2l4Yu3ces1SdtxMr', 'dbname_usht','5432');

// REJESTRACJA UŻYTKOWNIKA
if (isset($_POST['reg_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
  
    if ($user) { 
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password = md5($password_1);

        $query = "INSERT INTO users (username, email, password) 
                  VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);

        // Pobranie ID nowo zarejestrowanego użytkownika
        $user_id = mysqli_insert_id($db);

        $_SESSION['user_id'] = $user_id; // Zapis ID użytkownika do sesji
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// LOGOWANIE UŻYTKOWNIKA
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password); // Hasło zaszyfrowane w ten sam sposób co przy rejestracji
        $query = "SELECT id, username FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);

            $_SESSION['user_id'] = $user['id']; // Zapis ID użytkownika do sesji
            $_SESSION['username'] = $user['username'];
            $_SESSION['success'] = "You are now logged in";

            header('location: index.php');
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}
?>
