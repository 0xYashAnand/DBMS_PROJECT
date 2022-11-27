<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Home</title>
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
	
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
<nav class="navbar">
		<div class="navbar__container">
			<a href="#home" id="navbar__logo">IIIT Nagpur</a>
			<div class="navbar__toggle" id="mobile-menu">
				<span class="bar"></span> <span class="bar"></span>

			</div>
			<ul class="navbar__menu">
				<li class="navbar__item">
					<a href="index.php" class="navbar__links " id="home-page">Home</a>
				</li>
				
				<li class="navbar__btn">
					<a href="index.php" class="button signup1 signup11"  id="Logout">Logout</a>
				</li>
			</ul>
		</div>
	</nav>
<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
</div>


<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-buttons">
  <a href="admin-dept.php" class="btn-admin rect-circ">
    <span>EDIT HEADS</span>
  </a>
  
  <input type="text" class="rect-round-sm admin-uid" name="userid" value="" placeholder="USER ID" maxlength="5">

  <input type="submit" class="btn-admin rect-circ" value="EDIT USER"/>

  <span id="invalid-user"></span>
</form>

<?php
  $servname = "localhost";
  $conn = new mysqli($servname, "root", "", "college_db");
  
  $utype = "inv";
  $sql = "SELECT * FROM STUDENT";
  $res = $conn->query($sql);

  if(isset($_POST['userid']))
  {
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        if ($row['StudentID'] == $_POST['userid']) {
          $utype = "student";
          break;
        }
      }
    }
  }

  if(isset($_POST['userid'])){
    if ($utype == "inv") {
      $sql = "SELECT * FROM INSTRUCTOR";
      $res = $conn->query($sql);
  
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          if ($row['InstructorID'] == $_POST['userid']) {
            $utype = "instructor";
            break;
          }
        }
      }  
    }
  }


  if ($utype == "inv") {
    echo '<script>
      document.getElementById("invalid-user").innerHTML = "Invalid User ID";
    </script>';
  }
  else {
    if(isset($_POST["userid"])){
    echo
    '<script>
      document.getElementById("invalid-user").innerHTML = "";
    </script>';

    $_SESSION["phchanged"] = "false";
    
    $_SESSION["userid"] = $_POST["userid"];
        
    if ($utype == "student")
      $_SESSION["usertype"] = "student";
    
    elseif  ($utype == "instructor")
      $_SESSION["usertype"] = "instructor";

    header("Location: admin-user.php");
  }
 }
?>

</div>

</div>
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
<script>
  function logout() {
    document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "logout=yes";
    window.location.href = 'index.php';
  }
</script>

</body>

</html>