<?php

/**
 * Class UserAuthentication.
 *
 * Handles user authentication logic.
 */
class UserAuthentication
{
  private $conn; // Database connection object.

  /**
   * Constructor function.
   */
  public function __construct()
  {
    // Establish database connection.
    $this->conn = mysqli_connect('localhost', 'root', '1234', 'IPL_DB');
  }

  /**
   * Authenticates user.
   *
   * @param string $email
   *   The email provided by the user.
   * @param string $password
   *   The password provided by the user.
   *
   * @return bool
   *   TRUE if authentication is successful, FALSE otherwise.
   */
  public function authenticateUser(string $email, string $password): bool
  {
    try {
      // Start session.
      session_start();
      // Check database connection.
      if ($this->conn->connect_error) {
        throw new Exception('Connection failed: ' . $this->conn->connect_error);
      } else {
        // Retrieve hashed password from database.
        $result = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT id,password, role FROM USERS WHERE email = '$email'"));

        // Check if user exists.
        if (!$result) {
          throw new Exception("Invalid email or password, please try again !!");
        } else {
          // Verify password.
          $userPassword = $result['password'];

          if ($userPassword != $password) {
            throw new Exception("Invalid email or password, please try again !!");
          } else {
            // Set session variables.
            $_SESSION['registered'] = true;
            $_SESSION['email'] = $email;
            $_SESSION["role"] = $result["role"];
            $_SESSION["user_id"] = $result['id'];
            if ($_SESSION["role"] == 'admin') {
              echo "<script>window.location.href = '/adminhome';</script>";
            }
            else {
              echo "<script>window.location.href = '/userhome';</script>";

            }
          }
        }
      }
    } catch (Exception $e) {
      // Handle exceptions.
      $errorMessage = addslashes($e->getMessage());
      $this->redirectToLoginPage($errorMessage);
      return false; // Authentication failed.
    }
  }

  /**
   * Redirects to login page with error message.
   *
   * @param string $errorMessage
   *   The error message to display.
   *
   * @return void
   */
  private function redirectToLoginPage(string $errorMessage): void
  {
    echo "<script>alert('Ooops : $errorMessage');</script>";
    echo "<script>window.location.href = '/';</script>";
  }
}

// Check if form is submitted.
if (isset($_POST["submit"])) {
  // Create UserAuthentication object.
  $authenticator = new UserAuthentication();

  // Get email and password from form.
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Authenticate user.
  $authenticator->authenticateUser($email, $password);
}
