<!doctype html>
<html>
<head>
    <title>Editovať Položku</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <style>
        form {
            width: 60%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<a href="http://localhost/zoznamy/" class="btn" style="display: block; text-align: center; font-weight: bold; margin-top: 20;">Domov</a>

    <h2 style="text-align: center;">Editovať Položku</h2>
    <?php
include("config.php");

// Get the ID_polozky from the URL
$id_polozky = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get the zoznam parameter from the URL (if it exists)
$zoznam = isset($_GET['zoznam']) ? $_GET['zoznam'] : '';

if ($id_polozky > 0) {
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch the item details from the Polozky table
    $query_item = "SELECT * FROM Polozky WHERE ID_polozky = ?";
    $stmt_item = mysqli_prepare($connection, $query_item);
    mysqli_stmt_bind_param($stmt_item, "i", $id_polozky);
    mysqli_stmt_execute($stmt_item);
    $result_item = mysqli_stmt_get_result($stmt_item);

    if ($row = mysqli_fetch_assoc($result_item)) {
        // Prepare the values with empty string in case of NULL
        $nazov = isset($row['Nazov']) ? htmlspecialchars($row['Nazov']) : '';
        $kategoria = isset($row['Kategoria']) ? htmlspecialchars($row['Kategoria']) : '';
        $cena = isset($row['Cena']) ? htmlspecialchars($row['Cena']) : '';
        $mnozstvo = isset($row['Mnozstvo']) ? htmlspecialchars($row['Mnozstvo']) : '';
        $obchod = isset($row['Obchod']) ? htmlspecialchars($row['Obchod']) : '';
        $oblubene = isset($row['Oblubene']) ? htmlspecialchars($row['Oblubene']) : '';

        // Display the form with pre-filled values
        echo "<form action='update_item.php' method='POST'>";
        echo "<input type='hidden' name='ID_polozky' value='" . $row['ID_polozky'] . "'>";
        echo "<input type='hidden' name='zoznam' value='" . $zoznam . "'>";  // Add the hidden zoznam field
        echo "<label for='Nazov'>Názov:</label><input type='text' name='Nazov' value='" . $nazov . "' required>";
        echo "<label for='Kategoria'>Kategória:</label><input type='text' name='Kategoria' value='" . $kategoria . "'>";
        echo "<label for='Cena'>Cena:</label><input type='text' name='Cena' value='" . $cena . "'>";
        echo "<label for='Mnozstvo'>Množstvo:</label><input type='text' name='Mnozstvo' value='" . $mnozstvo . "'>";
        echo "<label for='Obchod'>Obchod:</label><input type='text' name='Obchod' value='" . $obchod . "'>";
        
        // Replace the text input for "Obľúbené" with a select dropdown
        echo "<label for='Oblubene'>Obľúbené:</label>";
        echo "<select name='Oblubene' required>";
        echo "<option value='Ano'" . ($oblubene == 'Ano' ? ' selected' : '') . ">Ano</option>";
        echo "<option value='Nie'" . ($oblubene == 'Nie' ? ' selected' : '') . ">Nie</option>";
        echo "</select>";
        
        echo "<button type='submit' class='submit-btn'>Uložiť Zmeny</button>";
        echo "</form>";
    } else {
        echo "<p>Táto položka neexistuje.</p>";
    }

    mysqli_stmt_close($stmt_item);
    mysqli_close($connection);
} else {
    echo "<p>Neplatné ID položky.</p>";
}
?>
</body>
</html>
