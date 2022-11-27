<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Student </title>
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
					<a href="index.php" class="button signup1 signup11"  id="Logout" onClick="logout()">Logout</a>
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
<!-- nav bar  -->
<!-- --------------------------------- -->
<div class="page-container">

<div class="user-label rect-circ">
  <span class="rect-circ">STUDENT</span>
</div>


<div class="user-info">
  <?php
    $sql = "SELECT StudentID, PersonID FROM STUDENT
            WHERE STUDENT.StudentID = "."'".$_SESSION["userid"]."'";
    $res = $conn->query($sql);

    $pid = -1;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $pid = $row['PersonID'];
      }
    }

    $sql = "SELECT * FROM PERSON WHERE PERSON.PersonID = ".$pid;
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $name = $row['FirstName']." ";
        if ($row['MiddleName'] != "")
          $name .= $row['MiddleName']." ";
        $name .= $row['LastName'];
      }
    }

    echo "<span>$name</span>";
    echo "<span>"; echo $_SESSION["userid"]; echo "</span>";
  ?>
</div>

<!-- course info  -->
<?php
  $courses = array();

  $sql = "SELECT * FROM UNDERTAKES, COURSE, DEPARTMENT
          WHERE UNDERTAKES.StudentID ="."'".$_SESSION["userid"]."'".
          " AND UNDERTAKES.CourseID = COURSE.CourseID
          AND DEPARTMENT.DeptNo = COURSE.DeptNo";
  $res = $conn->query($sql);
  
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      array_push($courses,
        array($row['DeptName'], $row['CourseID'], $row['CourseName'],
          $row['ClassesTaken'], $row['Attendance'], $row['InternalMarks'], $row['PaperMarks']));
    }
  }


  for ($i = 0; $i < count($courses); $i++) {
    $total = $courses[$i][5] + $courses[$i][6];
    $grdpt = min(floor($total / 10) + 1, 10);

    echo
    '<div class="rect-round-sm std-course-container">
      <div class="std-course-info">
        <div class="rect-round-sm std-course-dept">
          <span>'.$courses[$i][0].'</span>
        </div>
      
        <div class="rect-round-sm std-course-cred">
          <div class="rect-round-sm std-course-id">
            <span>'.$courses[$i][1].'</span>
          </div>

          <div class="std-course-name">
            <span>'.$courses[$i][2].'</span>
          </div>
        </div>
      </div>

      <div class="std-course-data">
        <div class="std-course-attendance">
          <div class="std-course-attendance-data">
            <span>Total Classes: </span>
            <span>'.$courses[$i][3].'</span>
          </div>
  
          <div class="std-course-attendance-data">
            <span>Classes Attended: </span>
            <span>'.$courses[$i][4].'</span>
          </div>
  
          <div class="std-course-attendance-data">
            <span>Attendance: </span>
            <span>'.round(intval($courses[$i][4]) / intval($courses[$i][3]) * 100).'%</span>
          </div>
        </div>
  
        <div class="std-course-marks">
          <div class="std-course-marks-data">
            <span>Internal Marks: </span>
            <span>'.$courses[$i][5].'</span>
          </div>
  
          <div class="std-course-marks-data">
            <span>Paper Marks: </span>
            <span>'.$courses[$i][6].'</span>
          </div>
  
          <div class="std-course-marks-data">
            <span>Total: </span>
            <span>'.$total.'</span>
          </div>
  
          <div class="std-course-marks-data">
            <span>Grade Point: </span>
            <span>'.$grdpt.'</span>
          </div>
        </div>
      </div>
    </div>';
  }
?>

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