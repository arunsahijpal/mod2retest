<?php

/**
 * Class UserTeamManager.
 *
 * Handles user team management logic.
 */
class UserTeamManager
{
    private $conn; // Database connection object.

    /**
     * Constructor function.
     */
    public function __construct()
    {
        // Establish database connection.
        $this->conn = new mysqli('localhost', 'root', '1234', 'IPL_DB');
        // Check connection.
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /**
     * Save user team.
     *
     * @param int $userId
     *   The ID of the user.
     * @param array $selectedPlayers
     *   An array of selected player IDs.
     *
     * @return bool
     *   TRUE if team saved successfully, FALSE otherwise.
     */
    public function saveUserTeam(int $userId, array $selectedPlayers): bool
    {
        try {
            // Delete existing team for the user.
            $deleteStmt = $this->conn->prepare("DELETE FROM USER_TEAMS WHERE user_id = ?");
            $deleteStmt->bind_param("i", $userId);
            $deleteStmt->execute();

            // Insert new team for the user.
            $insertStmt = $this->conn->prepare("INSERT INTO USER_TEAMS (user_id, player_id) VALUES (?, ?)");
            $insertStmt->bind_param("ii", $userId, $playerId);

            foreach ($selectedPlayers as $playerId) {
                $insertStmt->execute();
            }

            return true; // Team saved successfully.
        } catch (Exception $e) {
            // Log error or handle exception.
            return false; // Team saving failed.
        }
    }
}

session_start();

// Check if the user is logged in and is a normal user.
if (!isset($_SESSION['registered']) || $_SESSION['registered'] !== true || $_SESSION['role'] !== 'user') {
    // Redirect to login page if not authenticated or not a user.
    header('Location: /');
    exit;
}

// Check if form is submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['players'])) {
    // Create UserTeamManager object.
    $teamManager = new UserTeamManager();

    // Get user ID from session.
    $userId = $_SESSION['user_id'];
    // Get selected players from form.
    $selectedPlayers = $_POST['players'];

    // Save user team.
    if ($teamManager->saveUserTeam($userId, $selectedPlayers)) {
        // Team saved successfully.
        echo "<script>alert('Your team is saved.');</script>";
        header('Location: /userteam');
        exit();
    } else {
        // Team saving failed.
        echo "<script>alert('Failed to save team.');</script>";
    }

    // Redirect to user home page.
    header('Location: /userhome');
    exit;
} else {
    // No players selected.
    echo "<script>alert('No players selected.');</script>";
}
