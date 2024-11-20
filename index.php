<?php
// Andmebaasi ühenduse seaded
$server = "localhost";
$username = "rasmus"; // Muuda vastavalt oma andmetele
$password = "qwerty"; // Muuda vastavalt oma andmetele
$database = "tekstirakendus";

// Ühenda MySQL andmebaasiga
$conn = new mysqli($server, $username, $password, $database);

// Kontrolli ühendust
if ($conn->connect_error) {
    die("Ühendus nurjus: " . $conn->connect_error);
}

// Vormist saadud andmete töötlemine
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sisestus = $conn->real_escape_string($_POST['sisestus']);
    
    if (!empty($sisestus)) {
        $sql = "INSERT INTO tekstid (sisestus) VALUES ('$sisestus')";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Tekst salvestatud!</p>";
        } else {
            echo "<p style='color: red;'>Viga: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Sisestus ei tohi olla tühi!</p>";
    }
}

// Andmebaasist andmete kuvamine
$sql = "SELECT * FROM tekstid ORDER BY kuupaev DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Teksti Salvestamine</title>
</head>
<body>
    <h1>Salvesta tekst MySQL andmebaasi</h1>
    <form method="POST" action="">
        <textarea name="sisestus" rows="4" cols="50" placeholder="Sisesta oma tekst siin..."></textarea><br>
        <button type="submit">Salvesta</button>
    </form>
    
    <h2>Salvestatud tekstid</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . htmlspecialchars($row['kuupaev']) . ":</strong> " . htmlspecialchars($row['sisestus']) . "</li>";
            }
        } else {
            echo "<p>Ühtegi teksti pole veel salvestatud.</p>";
        }
        ?>
    </ul>
</body>
</html>

<?php
// Sulge andmebaasi ühendus
$conn->close();
?>
