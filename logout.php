<?php
session_start();
session_unset(); // Išvalo visus sesijos duomenis
session_destroy(); // Uždaro sesiją

// Nukreipiame į prisijungimo puslapį
header("Location: login.php");
exit();
?>
