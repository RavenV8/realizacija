<?php
session_start();

// Tikriname, ar vartotojas yra prisijungęs
if (!isset($_SESSION['name']) || !isset($_SESSION['surname'])) {
    // Jei neprisijungęs, nukreipiame į prisijungimo puslapį
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagrindinis puslapis</title>
    <link rel="stylesheet" href="style.css">
    <script src="tracking.js" defer></script>
</head>
<body>
    <!-- Pagrindinis konteineris -->
    <div class="main-container">
        <!-- Antraštė -->
        <header>
            <h1>DronExpress</h1>
        </header>

        <!-- Prisijungusio vartotojo sveikinimas -->
        <div class="user-info">
            <p>Sveiki, <strong><?php echo htmlspecialchars($_SESSION['name'] . " " . $_SESSION['surname']); ?></strong>!</p>
            <a href="logout.php" class="btn logout">Atsijungti</a>
        </div>

        <!-- Siuntų sekimo sekcija -->
        <div class="tracking-section">
            <h2>Siuntų sekimas</h2>
            <div class="input-container">
                <input type="text" id="tracking-number" placeholder="Įrašykite siuntos numerį">
                <button type="button" onclick="getTrackingNumber()">Sekti</button>
            </div>
        </div>

        <!-- Mygtukai -->
        <div class="buttons">
            <a href="send_package.php" class="btn">Siųsti siuntą</a>
            <a href="stoteles.html" class="btn">Stotelių adresai</a>
        </div>
    </div>
</body>
</html>
