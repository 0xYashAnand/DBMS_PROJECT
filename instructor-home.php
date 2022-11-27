<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Instructor - Home</title>
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

<div class="user-label rect-circ">
  <span class="rect-circ">INSTRUCTOR</span>
</div>



<div class="dept-label">
  <?php
    $sql = "SELECT DEPARTMENT.DeptNo, DEPARTMENT.DeptName
            FROM INSTRUCTOR, DEPARTMENT
            WHERE INSTRUCTOR.InstructorID = '".$_SESSION["userid"]."'".
            " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        echo "<span>".$row['DeptName']."</span>";
        $_SESSION["deptid"] = $row['DeptNo'];
      }
    }
  ?>
</div>

<?php
  $sql = "SELECT * FROM HEAD WHERE Head = '";
  $sql .= $_SESSION["userid"]."'";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    echo
    '<div class="rect-circ btn-course-edit" onclick="location.href = '."'course-info.php'".'">
      <span>EDIT DEPARTMENT COURSES</span>
    </div>';
  }
?>

<div class="user-info">
  <?php
    $sql = "SELECT InstructorID, PersonID FROM INSTRUCTOR
            WHERE INSTRUCTOR.InstructorID = ".$_SESSION["userid"];
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

<?php
  $courses = array();

  $sql = "SELECT COURSE.CourseID, COURSE.CourseName, CCOUNT.Cnt, COURSE.ClassesTaken
          FROM COURSE, (
                        SELECT UNDERTAKES.CourseID, COUNT(UNDERTAKES.StudentID) AS Cnt
                        FROM COURSE, UNDERTAKES
                        WHERE COURSE.InstructorID = ".$_SESSION["userid"].
                        " AND COURSE.CourseID = UNDERTAKES.CourseID
                        GROUP BY UNDERTAKES.CourseID) CCOUNT
          WHERE COURSE.CourseID = CCOUNT.CourseID";
  $res = $conn->query($sql);
  
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      array_push($courses,
        array($row['CourseID'], $row['CourseName'], $row['Cnt'], $row['ClassesTaken']));
    }
  }

  for ($i = 0; $i < COUNT($courses); $i++) {
    echo
    '<div class="ins-course-container">
      <div class="rect-round-sm std-course-cred" id="'.$courses[$i][0].'" onclick="send('.$courses[$i][0].')">
        <div class="rect-round-sm std-course-id">
          <span>'.$courses[$i][0].'</span>
        </div>
    
        <div class="std-course-name">
          <span>'.$courses[$i][1].'</span>
        </div>
      </div>

      <div class="rect-round-sm">
        <span><strong>'.$courses[$i][2].'</strong> students</span>
      </div>

      <div class="rect-round-sm">
        <span><strong>'.$courses[$i][3].'</strong> classes</span>
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

  function send(str) {
    document.cookie = "courseid=" + str.id;
    window.location.href = "instructor-data.php";
  }
</script>

</body>

</html>
