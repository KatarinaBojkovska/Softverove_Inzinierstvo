<!doctype html>
<html>
<head>
    <title>Zoznamy</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <style>
        table {
            width: 80%;
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
        .btn {
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Zoznamy</h2>
    <?php
    include("config.php");

    // Connect to the database
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch lists from the "Zoznamy" table
    $query = "SELECT ID_zoznam, Datum_vytvorenia, Typ_notifikacie, Datum_notifikacie, Poznamka FROM Zoznamy";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>ID Zoznam</th><th>Dátum Vytvorenia</th><th>Typ Notifikácie</th><th>Dátum Notifikácie</th><th>Poznámka</th><th>Akcia</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_zoznam']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Datum_vytvorenia']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Typ_notifikacie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Datum_notifikacie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Poznamka']) . "</td>";
            echo "<td><a class='btn' href='zoznam_detail.php?id=" . urlencode($row['ID_zoznam']) . "'>Zobraziť</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align: center;'>Žiadne záznamy sa nenašli.</p>";
    }

    mysqli_close($connection);
    ?>
</body>
</html>
