<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Funkcija sugeneruoti atsitiktinį 8 skaitmenų kodą
function generateRandomCode() {
    return mt_rand(10000000, 99999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $street = trim($_POST['street']);

    // Tikriname, ar visi laukai užpildyti
    if (!$name || !$surname || !$email || !$phone || !$street) {
        $error = "Visi laukai turi būti užpildyti!";
    } else {
        $generatedCode = generateRandomCode(); // Sugeneruojame kodą

        // Laiško turinys
        $subject = "Jūsų registracijos informacija";
        $message = "Sveiki, $name $surname!\n\n"
                 . "Ačiū, kad pasirinkote mūsų paslaugas. Jūsų unikalus kodas yra: $generatedCode\n\n"
                 . "Pateikti duomenys:\n"
                 . "- Vardas: $name\n"
                 . "- Pavardė: $surname\n"
                 . "- El. paštas: $email\n"
                 . "- Telefono numeris: $phone\n"
                 . "- Gatvė: $street\n\n"
                 . "Jeigu turite klausimų, susisiekite su mumis!\n\n"
                 . "Pagarbiai,\nKurjerejai4";

        // Laiško antraštės
        $headers = "From: noreply@jusu-domenas.lt\r\n";
        $headers .= "Reply-To: noreply@jusu-domenas.lt\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Siunčiame el. laišką
        if (mail($email, $subject, $message, $headers)) {
            $success = "Laiškas su jūsų informacija buvo išsiųstas į $email!";
        } else {
            $error = "Nepavyko išsiųsti laiško. Bandykite dar kartą.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Siųsti siuntą</title>
</head>
<body>
    <h3>Siųsti siuntą</h3>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <form method="POST" action="">
        <label for="name">Vardas</label>
        <input type="text" id="name" name="name" required><br>

        <label for="surname">Pavardė</label>
        <input type="text" id="surname" name="surname" required><br>

        <label for="email">El. paštas</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Telefono numeris</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="street">Gatvė</label>
        <input type="text" id="street" name="street" required><br>

        <button type="submit">Siųsti</button>
    </form>
</body>
</html>
