<?php
session_start();

// Check if the user is logged in and is a normal user.
if (!isset($_SESSION['registered']) || $_SESSION['registered'] !== true || $_SESSION['role'] !== 'user') {
  // Redirect to login page if not authenticated or not a user.
  header('Location: /');
  exit;
}
// Database connection details.
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "IPL_DB";

try {
  // Establish database connection using MySQLi.
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection.
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Fetch all players from the database.
  $sql = "SELECT * FROM PLAYERS";
  $result = $conn->query($sql);

  // Check if there are any players.
  if ($result->num_rows > 0) {
    // Fetch players data as associative array.
    $players = [];
    while ($row = $result->fetch_assoc()) {
      $players[] = $row;
    }
  } else {
    echo "No players found.";
  }

  // Close connection.
  $conn->close();
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Home - IPL Fantasy League</title>
  <link rel="stylesheet" href="./app/styles/userhome.css">
  <script src="./app/scripts/userhome.js"></script>
</head>

<body>
  <header>
    <section class="navbar container">
      <div class="logo">
        <a href="/home">IPL.com</a>
      </div>
      <nav>
        <a href="/userteam">Your Team</a>
        <a href="/logout" id="logout-btn">Logout</a>
      </nav>
    </section>
  </header>

  <main class="container">
    <h1>Welcome, User</h1>
    <h2>Select Your Team</h2>
    <p>Total Points Available: <span id="pointsLeft">100</span></p>
    <p>Total Team members: <span id="teammembers">0</span></p>
    <form action="./app/db/save_team.php" method="POST" onsubmit="return validateTeam()">
      <table border="1">
        <tr>
          <th>Select</th>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Type</th>
          <th>Points</th>
        </tr>
        <?php foreach ($players as $player) : ?>
          <tr>
            <td><input type="checkbox" id="<?php echo $player['employee_id']; ?>" name="players[]" value="<?php echo $player['employee_id']; ?>" onclick="selectPlayer(<?php echo $player['employee_id']; ?>, <?php echo $player['points']; ?>, '<?php echo $player['type']; ?>')"></td>
            <td><?php echo htmlspecialchars($player['employee_id']); ?></td>
            <td><?php echo htmlspecialchars($player['employee_name']); ?></td>
            <td><?php echo htmlspecialchars($player['type']); ?></td>
            <td><?php echo htmlspecialchars($player['points']); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <input type="submit" value="Save Team">
    </form>
  </main>

</body>

</html>