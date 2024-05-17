<?php
session_start();
if ($_SESSION['registered'] == true) {
  header('Location: /home');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./app/styles/login.css">
  <title>Login</title>
</head>

<body>
  <section class="center-container">
    <section class="container">
      <form action="../app/db/logincheck.php" class="name-form" method='post'>
        <h1 id="page-heading">Welcome</h1>
        <p class="innerheading">Please login with email</p>

        <div class="input-wrapper">
          <label for="email">
            <i class="fa-solid fa-user"></i>
          </label>
          <input type="email" name="email" id="email" required placeholder="Enter your email">
        </div>

        <div class="input-wrapper">
          <label for="password">
            <i class="fa-solid fa-lock icon"></i>
          </label>
          <input type="password" name="password" id="password" required placeholder="Enter your Password">
        </div>
        <input type="submit" value="Login" name='submit'>

      </form>
    </section>

    <section class="image-container">
      <img src="../app/images/login.jpg" alt="">
    </section>
  </section>
</body>

</html>