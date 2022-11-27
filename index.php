<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Login - IIIT Nagpur </title>
  <link rel="stylesheet" href="./styl.css">
  <link rel="stylesheet" href="nav.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="signup.css">
  <link rel="stylesheet" href="style.css">
	<link rel="shortcut icon" href="images/fav.ico" type="image/x-icon">
	<style>
		section {
			min-height: 5vh;
			z-index: 1;
			position: relative;
		}
	</style>
</head>
<body>
  <!-- nav -->
<nav class="navbar">
		<div class="navbar__container">
			<a href="#home" id="navbar__logo">IIIT Nagpur</a>
			<div class="navbar__toggle" id="mobile-menu">
				<span class="bar"></span> <span class="bar"></span>

			</div>
			<ul class="navbar__menu">
				
			</ul>
		</div>
	</nav>
 <?php
  if (isset($_COOKIE["logout"]) && $_COOKIE["logout"] == "yes") {
    session_unset();
    session_destroy();
    setcookie("logout", "", time() - 3600);
  }

  if (isset($_COOKIE["loggedin"])) {
    if ($_COOKIE["usertype"] == "student")
      header("Location: student-home.php");

    else if ($_COOKIE["usertype"] == "instructor")
      header("Location: instructor-home.php");
  }
?>
<!-- partial:index.partial.html -->
<div id="bg"></div>
<div id="box">
  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <div class="form-field">
      <input type="text" id="user" name="username" placeholder="USER ID"><br>
    </div>
    
    <div class="form-field">
      <input type="password"  id="pass" name="password" placeholder="PASSWORD"><br>                        </div>
    
    <div class="form-field">
      <button class="btn" type="submit">Log in</button>
    </div>
    <span id="invalid-login"></span>
  </form>
</div>

<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $password = md5($password);
    
    $servname = "localhost";
    $conn = new mysqli($servname, "root", "", "college_db");
    
    if ($conn->connect_error)
      die("Connection failed: " . $conn->connect_error);
    
    $sql = "SELECT StudentID, PassHash FROM STUDENT";
    $res = $conn->query($sql);
    
    $login = "none";
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        if ($row['StudentID'] == $username && $password == $row['PassHash']) {
          $login = "student";
          break;
        }
      }
    }

    $sql = "SELECT InstructorID, PassHash FROM INSTRUCTOR";
    $res = $conn->query($sql);
    
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        if ($row['InstructorID'] == $username && $password == $row['PassHash']) {
          $login = "instructor";
          break;
        }
      }
    }

    if ($username == '00000' && $password == md5("password"))
      $login = "admin";
    
    $conn->close();
    
    if ($login == "none") {
      echo
      '<script>
        document.getElementById("invalid-login").innerHTML = "Invalid User Id or Password";
      </script>';
      session_unset();
      session_abort();
    }
    
    else {
      echo
      '<script>
        document.getElementById("invalid-login").innerHTML = "";
      </script>';
      
      $_SESSION["userid"] = $username;

      if ($_REQUEST["remember"] == "on") {
        setcookie("loggedin", "yes");
      }
          
      if ($login == "admin") {
        $_SESSION["usertype"] = "admin";
        if ($_REQUEST["remember"] == "on")
          setcookie("usertype", "admin");

        header("Location: admin-home.php");
      }

      elseif ($login == "student") {
        $_SESSION["usertype"] = "student";
        if ($_REQUEST["remember"] == "on")
          setcookie("usertype", "student");

        header("Location: student-home.php");
      }
      
      elseif  ($login == "instructor") {
        $_SESSION["usertype"] = "instructor";
        if ($_REQUEST["remember"] == "on")
          setcookie("usertype", "instructor");

        header("Location: instructor-home.php");
      }
    }
  }
?>
<!-- partial -->
  

<!-- Footer Section -->
<div class="footer__container">
  <section class="social__media">
    <div class="social__media--wrap">
      <div class="footer__logo">
        <a href="#" id="footer__logo">IIIT Nagpur</a>
      </div>
      <p class="website__rights">© IIIT Nagpur 2022. All rights reserved</p>
      <div class="social__icons">
        <a href="#" class="social__icon--link" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="#" class="social__icon--link"><i class="fab fa-instagram"></i></a>
        <a href="#" class="social__icon--link"><i class="fab fa-youtube"></i></a>
        <a href="#" class="social__icon--link"><i class="fab fa-linkedin"></i></a>
        <a href="#" class="social__icon--link"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </section>
</div>

<script src="nav.js" defer></script>
</body>
</html>
