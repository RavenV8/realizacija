<?php
header('Content-Type: application/json');

// Prisijungimo duomenys
$servername = "sql7.freesqldatabase.com";
$username = "sql7747669";
$password = "suHFe6f92Y";
$dbname = "sql7747669";

// Prisijungiame prie DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Patikriname prisijungimą
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Nepavyko prisijungti prie DB.']);
    exit();
}

// Patikriname POST duomenis
if (isset($_POST['trackingNumber'])) {
    $trackingNumber = $_POST['trackingNumber'];

    $sql = "SELECT Name, Surname, Email, PhoneNumber, Street, CreatedAt FROM Users WHERE GeneratedCode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'name' => $row['Name'],
            'surname' => $row['Surname'],
            'email' => $row['Email'],
            'phone' => $row['PhoneNumber'],
            'street' => $row['Street'],
            'createdAt' => $row['CreatedAt']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Siunta nerasta.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Nepateiktas siuntos numeris.']);
}

$conn->close();
?>
