<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $id_polozky = intval($_POST['ID_polozky']);
    $nazov = $_POST['Nazov'];
    $kategoria = $_POST['Kategoria'];
    $cena = $_POST['Cena'];
    $mnozstvo = $_POST['Mnozstvo'];
    $obchod = $_POST['Obchod'];
    $oblubene = $_POST['Oblubene'];

    // Capture the zoznam parameter from the POST request
    $zoznam = isset($_POST['zoznam']) ? $_POST['zoznam'] : '';

    // Create the query to insert into Upravene_polozky
    $query_update = "INSERT INTO Upravene_polozky (Nazov, Kategoria, Cena, Mnozstvo, Obchod, Oblubene)
                     VALUES (?, ?, ?, ?, ?, ?)";

    $connection = mysqli_connect($servername, $username, $password, $dbname);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the query for Upravene_polozky
    $stmt_update = mysqli_prepare($connection, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ssdiss", $nazov, $kategoria, $cena, $mnozstvo, $obchod, $oblubene);

    if (mysqli_stmt_execute($stmt_update)) {
        // Get the ID of the newly inserted item in Upravene_polozky
        $id_upravene_polozky = mysqli_insert_id($connection);

        // Now insert the ID of the updated item into Zoznam table
        if (!empty($zoznam)) {
            // Insert the ID_uprav_polozky into Zoznam instead of ID_polozky
            $query_zoznam = "UPDATE Zoznam SET ID_uprav_polozky = ? WHERE ID_zoznam = ? AND ID_polozky = ?";
            $stmt_zoznam = mysqli_prepare($connection, $query_zoznam);
            mysqli_stmt_bind_param($stmt_zoznam, "iii", $id_upravene_polozky, $zoznam, $id_polozky);
            mysqli_stmt_execute($stmt_zoznam);
            mysqli_stmt_close($stmt_zoznam);
        }

        // Redirect back to zoznam_detail.php with the updated ID
        header("Location: zoznam_detail.php?id=" . $zoznam);
        exit;  // Ensure no further code is executed after the redirect
    } else {
        echo "<p style='text-align: center;'>Chyba pri ukladaní zmien.</p>";
    }

    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);
}
?>
