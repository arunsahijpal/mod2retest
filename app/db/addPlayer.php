<?php

/**
 * Class AddPlayer.
 *
 * Handles adding a new player to the database.
 */
class AddPlayer
{
  private $conn; // Database connection object.

  /**
   * Constructor function.
   */
  public function __construct()
  {
    // Establish database connection.
    $this->conn = new mysqli('localhost', 'root', '1234', 'IPL_DB');

    // Check for connection errors.
    if ($this->conn->connect_error) {
      throw new Exception('Connection failed: ' . $this->conn->connect_error);
    }
  }

  /**
   * Adds a new player.
   *
   * @param int $employee_id
   *   The employee ID provided by the admin.
   * @param string $employee_name
   *   The employee name provided by the admin.
   * @param string $type
   *   The type provided by the admin.
   * @param int $points
   *   The points provided by the admin.
   *
   * @return bool
   *   TRUE if the player is added successfully, FALSE otherwise.
   */
  public function addNewPlayer(int $employee_id, string $employee_name, string $type, int $points): bool
  {
    try {
      // Prepare the SQL statement.
      $stmt = $this->conn->prepare("INSERT INTO PLAYERS (employee_id, employee_name, type, points) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("issi", $employee_id, $employee_name, $type, $points);

      // Execute the statement.
      if ($stmt->execute()) {
        return true;
      } else {
        throw new Exception("Failed to add player: " . $stmt->error);
      }
    } catch (Exception $e) {
      // Handle exceptions.
      $this->redirectToAdminHomePage($e->getMessage());
      return false; // Adding player failed.
    }
  }

  /**
   * Redirects to the admin home page with an error message.
   *
   * @param string $errorMessage
   *   The error message to display.
   *
   * @return void
   */
  private function redirectToAdminHomePage(string $errorMessage): void
  {
    echo "<script>alert('Ooops: $errorMessage');</script>";
    echo "<script>window.location.href = '/adminhome';</script>";
  }
}

// Check if form is submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Create AddPlayer object.
  $playerAdder = new AddPlayer();

  // Get player details from form.
  $employee_id = $_POST['employee_id'];
  $employee_name = $_POST['employee_name'];
  $type = $_POST['type'];
  $points = $_POST['points'];

  // Add the new player.
  if ($playerAdder->addNewPlayer($employee_id, $employee_name, $type, $points)) {
    echo "<script>alert('Player added Successfully.!'); window.location.href = '/adminhome';</script>";
  }
}
?>
