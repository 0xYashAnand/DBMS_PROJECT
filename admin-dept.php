<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin - Department</title>
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
<?php
  $servname = "localhost";
  $conn = new mysqli($servname, "root", "", "college_db");

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>

<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
</div>


<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <input type="submit" value="SUBMIT" class="btn-submit rect-circ" style="margin-top: 6em"/>

  <?php
    $sql = "SELECT COUNT(*) FROM DEPARTMENT";
    $res = $conn->query($sql);

    $count = 0;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $count = $row['COUNT(*)'];
      }
    }
    for ($i = 0; $i < $count; $i++) {
      if(isset($_POST["dhead".$i])){
        $sql = "UPDATE HEAD SET Head = '";
        $sql .= substr($_POST['dhead'.$i], 0, 5)."' WHERE DeptNo = '".($i + 1)."'";
        $conn->query($sql);
      }
    }

    $department = array();
    $sql = "SELECT * FROM DEPARTMENT, HEAD
            WHERE HEAD.DeptNo = DEPARTMENT.DeptNo
            ORDER BY DEPARTMENT.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($department, array($row['DeptNo'], $row['DeptName'], $row['Head']));
      }
    }

    $instructors = array();
    for ($i = 0; $i < COUNT($department); $i++) {
      $sql = "SELECT * FROM INSTRUCTOR, PERSON
              WHERE INSTRUCTOR.DeptNo = ".$department[$i][0].
              " AND INSTRUCTOR.PersonID = PERSON.PersonID";
      $res = $conn->query($sql);

      $temp = array();
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push($temp,
            array($row['InstructorID'], $row['FirstName'], $row['MiddleName'], $row['LastName']));
        }
      }
      array_push($instructors, $temp);
    }

    for ($i = 0; $i < COUNT($department); $i++) {
      echo
      '<div class="admin-dept-container">
        <div class="rect-round-sm admin-dept-name">
          <span>'.$department[$i][1].'</span>
        </div>

        <select class="rect-round-sm" name="dhead'.$i.'" id="dhead'.$i.'">';
        for ($j = 0; $j < COUNT($instructors[$i]); $j++) {
          $name = $instructors[$i][$j][0].": ".$instructors[$i][$j][1]." ";
          if ($instructors[$i][$j][2] != "")
            $name .= $instructors[$i][$j][2]." ";
          $name .= $instructors[$i][$j][3];

          if ($instructors[$i][$j][0] == $department[$i][2])
            echo '<option selected="selected" value="'.$name.'">'.$name.'</option>';
          
          else
            echo '<option value="'.$name.'">'.$name.'</option>';
        }
        echo '</select>
      </div>';
    }
  ?>
</form>

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