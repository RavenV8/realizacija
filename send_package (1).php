<?php
session_start();

// Patikriname, ar vartotojas prisijungęs
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// MySQL duomenų bazės prisijungimo duomenys
$host = "sql7.freesqldatabase.com"; // arba Jūsų serveris
$username = "sql7747669"; // pakeisti pagal realius duomenis
$password = "suHFe6f92Y"; // slaptažodis
$database = "sql7747669";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prisijungiame prie duomenų bazės
    $conn = new mysqli($host, $username, $password, $database);

    // Tikriname prisijungimo klaidas
    if ($conn->connect_error) {
        die("Prisijungimo klaida: " . $conn->connect_error);
    }

    // Gauname duomenis iš formos
    $recipientName = $_POST['recipient_name'];
    $recipientSurname = $_POST['recipient_surname'];
    $sendAddress = $_POST['send_address'];
    $deliveryAddress = $_POST['delivery_address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $trackingNumber = uniqid("TRACK"); // Generuojame unikalų siuntos numerį

    // SQL užklausa duomenims įrašyti
    $stmt = $conn->prepare("INSERT INTO Packages (sender_id, receiver_id, tracking_number, send_address, delivery_address, description, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $senderID = $_SESSION['id']; // Siuntėjo ID iš sesijos
    $receiverID = NULL; // Galite nustatyti pagal poreikį
    
    $stmt->bind_param("iissss", $senderID, $receiverID, $trackingNumber, $sendAddress, $deliveryAddress, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Siunta sėkmingai išsiųsta! Sekimo numeris: $trackingNumber');</script>";
    } else {
        echo "Klaida: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siųsti siuntą</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('pvvvv3.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-row input, .form-row select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        const stations = [
            "Gedimino pr. 43",
            "Pilies g. 12",
            "Vokiečių g. 76",
            "Švitrigailos g. 5",
            "Ozo g. 43",
            "Konstitucijos pr. 27",
            "A. Jakšto g. 1",
            "Mindaugo g. 68",
            "Laisvės pr. 90",
            "J. Basanavičiaus g. 25"
        ];

        function populateStations() {
            const sendSelect = document.getElementById('send_address');
            const deliverySelect = document.getElementById('delivery_address');

            stations.forEach(station => {
                const option1 = document.createElement('option');
                option1.value = station;
                option1.textContent = station;
                sendSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = station;
                option2.textContent = station;
                deliverySelect.appendChild(option2);
            });
        }

        function updateStations(selectedId, otherId) {
            const selectedValue = document.getElementById(selectedId).value;
            const otherSelect = document.getElementById(otherId);

            Array.from(otherSelect.options).forEach(option => {
                if (option.value === selectedValue && option.value !== "") {
                    option.disabled = true; // Išjungia pasirinktą reikšmę
                } else {
                    option.disabled = false; // Įgalina visas kitas
                }
            });
        }

        window.onload = populateStations;
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Siųsti siuntą</h2>
        <form method="POST" action="send_package.php">
            <div class="form-row">
                <input type="text" name="recipient_name" placeholder="Gavėjo vardas" required>
                <input type="text" name="recipient_surname" placeholder="Gavėjo pavardė" required>
            </div>
            <div class="form-row">
                <select id="send_address" name="send_address" onchange="updateStations('send_address', 'delivery_address')" required>
                    <option value="" disabled selected>-Spustelėkite pasirinkti-</option>
                </select>
                <select id="delivery_address" name="delivery_address" onchange="updateStations('delivery_address', 'send_address')" required>
                    <option value="" disabled selected>-Spustelėkite pasirinkti-</option>
                </select>
            </div>
            <div class="form-row">
                <input type="text" name="phone" placeholder="Telefono numeris" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                <input type="email" name="email" placeholder="El. paštas" required>
            </div>
            <textarea name="description" placeholder="Siuntos turinio aprašymas" required></textarea>
            <button type="submit">Siųsti siuntą</button>
        </form>
    </div>
</body>
</html>
