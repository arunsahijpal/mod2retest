<?php
session_start();

// Check if the user is logged in and is an admin.
if (!isset($_SESSION['registered']) || $_SESSION['registered'] !== true || $_SESSION['role'] !== 'admin') {
  header('Location: /');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Home - IPL Fantasy League</title>
  <link rel="stylesheet" href="./app/styles/adminhome.css">
</head>

<body>
  <header>
    <section class="navbar container">
      <div class="logo">
        <a href="/home">IPL.com</a>
      </div>
      <nav>
        <a href="/logout" id="logout-btn">Logout</a>
      </nav>
    </section>
  </header>

  <main class="container">
    <h1>Welcome, Admin</h1>
    <section class="players-list">
      <h2>Add New Player</h2>
      <form action="./app/db/addPlayer.php" method="POST">
        <label for="employee_id">Employee ID:</label>
        <input type="number" id="employee_id" name="employee_id" required><br><br>

        <label for="employee_name">Employee Name:</label>
        <input type="text" id="employee_name" name="employee_name" required><br><br>

        <label for="type">Type:</label>
        <select id="type" name="type" required>
          <option value="batsman">Batsman</option>
          <option value="bowler">Bowler</option>
          <option value="allrounder">All-Rounder</option>
        </select><br><br>

        <label for="points">Points:</label>
        <input type="number" id="points" name="points" min="2" max="10" required><br><br>

        <input type="submit" value="Add Player" name="submit">
      </form>

      <h2>Existing Players</h2>
      <table border="1">
        <tr>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Type</th>
          <th>Points</th>
        </tr>
        <?php
        // Include database connection details.
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "IPL_DB";

        // Establish database connection using MySQLi.
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection.
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Fetch all players from the database.
        $sql = "SELECT * FROM PLAYERS";
        $result = $conn->query($sql);

        // Display players in a table.
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['employee_id']}</td>
                    <td>{$row['employee_name']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['points']}</td>
                </tr>";
          }
        } else {
          echo "<tr><td colspan='4'>No players found</td></tr>";
        }

        // Close connection.
        $conn->close();
        ?>
      </table>
    </section>
  </main>
</body>

</html>