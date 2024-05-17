<?php
session_start();

// Redirect to login page if user is not logged in or not a normal user.
class Auth {
    public static function checkUser() {
        if (!isset($_SESSION['registered']) || $_SESSION['registered'] !== true || $_SESSION['role'] !== 'user') {
            header('Location: /');
            exit;
        }
    }
}

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

class UserTeam {
    private $conn;
    private $userId;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function fetchTeamDetails() {
        $sql = "SELECT p.employee_id, p.employee_name, p.type, p.points 
                FROM USER_TEAMS ut 
                INNER JOIN PLAYERS p ON ut.player_id = p.employee_id 
                WHERE ut.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function displayTeam($result) {
        if ($result->num_rows > 0) {
            echo "<h1>User Team</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Employee ID</th><th>Name</th><th>Type</th><th>Points</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["employee_id"] . "</td>";
                echo "<td>" . $row["employee_name"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td>" . $row["points"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<a href='/userhome'>Edit Team</a>";
        } else {
            echo "No team details found.";
        }
    }
}

try {
    // Check if user is authenticated
    Auth::checkUser();

    // Database connection details
    $db = new Database("localhost", "root", "1234", "IPL_DB");
    $db->connect();
    $conn = $db->getConnection(); // Corrected method call

    // Get user ID from session
    $userId = $_SESSION['user_id'];

    // Fetch and display user's team details
    $userTeam = new UserTeam($conn, $userId);
    $result = $userTeam->fetchTeamDetails();
    $userTeam->displayTeam($result);

    // Close database connection
    $db->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
