<!doctype html>
<html>
<head>
    <title>Detail Zoznamu</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<a href="http://localhost/zoznamy/" class="btn" style="display: block; text-align: center; font-weight: bold; margin-top: 20;">Domov</a>    <h2 style="text-align: center;">Detail Zoznamu</h2>
    <?php
    include("config.php");

    // Get the ID_zoznam from the URL
    $id_zoznam = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id_zoznam > 0) {
        $connection = mysqli_connect($servername, $username, $password, $dbname);

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to fetch items in the list with appropriate joins
        $query_items = "
            SELECT Z.ID_uprav_polozky, P.ID_polozky, 
                   P.Nazov AS Polozka_Nazov, 
                   P.kategoria AS Polozka_Kategoria, 
                   P.cena AS Polozka_Cena, 
                   P.mnozstvo AS Polozka_Mnozstvo, 
                   P.obchod AS Polozka_Obchod, 
                   P.oblubene AS Polozka_Oblubene,
                   UP.Nazov AS Upravena_Nazov, 
                   UP.kategoria AS Upravena_Kategoria, 
                   UP.cena AS Upravena_Cena, 
                   UP.mnozstvo AS Upravena_Mnozstvo, 
                   UP.obchod AS Upravena_Obchod, 
                   UP.oblubene AS Upravena_Oblubene
            FROM Zoznam Z
            LEFT JOIN Polozky P ON Z.ID_polozky = P.ID_polozky
            LEFT JOIN Upravene_polozky UP ON Z.ID_uprav_polozky = UP.ID_uprav_polozky
            WHERE Z.ID_zoznam = ?";

        $stmt_items = mysqli_prepare($connection, $query_items);
        mysqli_stmt_bind_param($stmt_items, "i", $id_zoznam);
        mysqli_stmt_execute($stmt_items);
        $result_items = mysqli_stmt_get_result($stmt_items);

        // Display items if available
        if (mysqli_num_rows($result_items) > 0) {
            echo "<h3 style='text-align: center;'>Položky v zozname</h3>";
            echo "<table>";
            echo "<tr><th>Názov Polozky</th><th>Kategória</th><th>Cena</th><th>Množstvo</th><th>Obchod</th><th>Obľúbené</th><th>Upraviť</th></tr>";

            while ($item = mysqli_fetch_assoc($result_items)) {
                echo "<tr>";
                
                // Display original data from Polozky or updated data from Upravene_polozky, handle NULLs
                echo "<td>" . htmlspecialchars($item['Upravena_Nazov'] ?? $item['Polozka_Nazov'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($item['Upravena_Kategoria'] ?? $item['Polozka_Kategoria'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($item['Upravena_Cena'] ?? $item['Polozka_Cena'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($item['Upravena_Mnozstvo'] ?? $item['Polozka_Mnozstvo'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($item['Upravena_Obchod'] ?? $item['Polozka_Obchod'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($item['Upravena_Oblubene'] ?? $item['Polozka_Oblubene'] ?? '') . "</td>";
                
                // Add "Upraviť" button for each item
                echo "<td><a href='edit_item.php?id=" . $item['ID_polozky'] . "&zoznam=" . $id_zoznam . "'><button class='edit-btn'>Upraviť</button></a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p style='text-align: center;'>Žiadne položky v zozname.</p>";
        }

        mysqli_stmt_close($stmt_items);
        mysqli_close($connection);
    } else {
        echo "<p style='text-align: center;'>Neplatné ID zoznamu.</p>";
    }
    ?>
</body>
</html>
