<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "sql7.freesqldatabase.com";
$username = "sql7747669";
$password = "suHFe6f92Y";
$dbname = "sql7747669";

// Prisijungimas prie duomenų bazės
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Prisijungti prie duomenų bazės nepavyko: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validacijos
    if (!$name || !$surname || !$email || !$phone || !$password || !$confirm_password) {
        $error = "Visi laukai turi būti užpildyti!";
    } elseif (!preg_match('/^\d{9}$/', $phone)) {
        $error = "Telefono numeris turi būti sudarytas iš 9 skaitmenų.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]{8,}$/', $password)) {
        $error = "Slaptažodis turi būti bent 8 simboliai, turėti 1 didžiąją raidę, 1 specialų simbolį ir 1 skaičių.";
    } elseif ($password !== $confirm_password) {
        $error = "Slaptažodžiai nesutampa!";
    } else {
        $check_sql = "SELECT email FROM accounts WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "Šis el. paštas jau egzistuoja.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO accounts (name, surname, email, phone_number, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $surname, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registracija sėkminga! Galite prisijungti.";
            } else {
                $error = "Klaida registruojantis: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('pvvvv3.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        #registration-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            text-align: center;
        }
        h3 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-column {
            flex-basis: 48%;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            display: block;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
            font-size: 14px;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
    <script>
        // Telefono numerio validacija
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('phone').addEventListener('input', function (e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9); // Tik skaičiai, max 9
            });
        });

        // Slaptažodžio validacija
        document.querySelector('form').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]{8,}$/;
    if (!passwordRegex.test(password)) {
    e.preventDefault();
    alert('Slaptažodyje turi būti bent 8 simboliai, 1 didžioji raidė, 1 specialusis simbolis ir 1 skaičius.');
}

});
    </script>
</head>
<body>
    <div id="registration-container">
        <h3>Registracija</h3>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-column">
                    <label for="name">Vardas</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-column">
                    <label for="surname">Pavardė</label>
                    <input type="text" id="surname" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-column">
                    <label for="email">El. paštas</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-column">
                    <label for="phone">Telefono numeris</label>
                    <input type="tel" id="phone" name="phone" pattern="\d{9}" title="9 skaitmenys" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-column">
                    <label for="password">Slaptažodis</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-column">
                    <label for="confirm_password">Patvirtinkite slaptažodį</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            <button type="submit">Registruotis</button>
        </form>
        <a href="login.php">Jau turite paskyrą? Prisijunkite čia</a>
    </div>
</body>
</html>
