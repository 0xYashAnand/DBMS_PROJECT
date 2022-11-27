<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Instructor </title>
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
  
  if (!isset($_COOKIE["courseid"]))
    echo "Enable Cookies";
?>

<div class="page-container">

<div class="user-label rect-circ">
  <span class="rect-circ">INSTRUCTOR</span>
</div>


<div class="dept-label">
  <?php

    $sql = "SELECT DEPARTMENT.DeptName
            FROM INSTRUCTOR, DEPARTMENT
            WHERE INSTRUCTOR.InstructorID = ".$_SESSION["userid"].
            " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        echo "<span>".$row['DeptName']."</span>";
      }
    }
  ?>
</div>

<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <input type="submit" value="SUBMIT" class="btn-submit rect-circ"/>

  <?php
  if (isset($_POST['clsInp'])){
    $sql = "UPDATE COURSE SET COURSE.ClassesTaken = '".$_POST["clsInp"].
            "' WHERE COURSE.CourseId = '".$_COOKIE["courseid"]."'";
    $conn->query($sql);
  }

    
    
    $sql = "SELECT * FROM UNDERTAKES WHERE UNDERTAKES.CourseID = '".$_COOKIE["courseid"];
    $sql .= "'ORDER BY UNDERTAKES.StudentID";
    $res = $conn->query($sql);

    $students = array();
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($students, $row['StudentID']);
      }
    }
    
    if(isset($_POST['cls0'])){
      for ($i = 0; $i < COUNT($students); $i++) {   
        $sql = "UPDATE UNDERTAKES SET Attendance = '";
        $sql .= $_POST['cls'.$i]."', PaperMarks = '";
        $sql .= $_POST['pap'.$i]."', InternalMarks = '";
        $sql .= $_POST['int'.$i]."' WHERE StudentID = '".$students[$i]."'";
        $res = $conn->query($sql);
        }
    }

    $sql = "SELECT COURSE.CourseID, COURSE.CourseName, COURSE.ClassesTaken FROM COURSE
            WHERE COURSE.CourseID = '".$_COOKIE["courseid"]."'";
    $res = $conn->query($sql);

    $course = null;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $course = array($row['CourseID'], $row['CourseName'], $row['ClassesTaken']);
      }
    }

    echo
    '<div class="ins-course-edit-container">
      <div class="rect-round-sm std-course-cred">
        <div class="rect-round-sm std-course-id">
          <span>'.$course[0].'</span>
        </div>

        <div class="std-course-name">
          <span>'.$course[1].'</span>
        </div>
      </div>

      <div class="rect-round-sm" onClick="decrClass()"><span>-</span></div>

      <div class="rect-round-sm">
        <span><strong><span id="numClass">'.$course[2].'</span></strong> classes</span>
        <input id="clsInp" type="hidden" name="clsInp" value="'.$course[2].'"/>
      </div>

      <div class="rect-round-sm" onClick="incrClass()"><span>+</span></div>
    </div>';

  
  ?>

  <div class="line"></div>

  <div class="ins-course-edit-label">
    <span>Internal</span>
    <span>Paper</span>
    <span>Classes</span>
  </div>

  <?php
    $sql = "SELECT *
            FROM UNDERTAKES, STUDENT, PERSON
            WHERE UNDERTAKES.CourseID = '".$_COOKIE["courseid"];
    $sql .= "'AND UNDERTAKES.StudentID = STUDENT.StudentID
            AND PERSON.PersonID = STUDENT.PersonID
            ORDER BY STUDENT.StudentID";
    $res = $conn->query($sql);

    $students = array();
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $name = $row['FirstName']." ";
        if ($row['MiddleName'] != "")
          $name .= $row['MiddleName']." ";
        $name .= $row['LastName'];
        array_push($students,
          array($row['StudentID'], $name, $row['Attendance'], $row['PaperMarks'], $row['InternalMarks']));
      }
    }

    for ($i = 0; $i < COUNT($students); $i++) {
      echo
      '<div class="ins-course-edit-course">
        <div class="rect-round-sm std-course-cred">
          <div class="rect-round-sm std-course-id">
            <span>'.$students[$i][0].'</span>
          </div>
          <div class="std-course-name">
            <span>'.$students[$i][1].'</span>
          </div>
        </div>

        <input type="number" class="rect-round-sm"
          name="cls'.$i.'" value="'.$students[$i][2].'" maxlength="2"/>

        <input type="text" class="rect-round-sm"
          name="pap'.$i.'" value="'.$students[$i][3].'" maxlength="2"/>

        <input type="text" class="rect-round-sm"
          name="int'.$i.'" value="'.$students[$i][4].'" maxlength="2"/>
      </div>';
    }
  ?>
</form>

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

  function incrClass() {
    var temp = document.getElementById("numClass").innerHTML;
    document.getElementById("numClass").innerHTML = parseInt(temp) + 1;
    document.getElementById("clsInp").value = parseInt(temp) + 1;
  }

  function decrClass() {
    var temp = document.getElementById("numClass").innerHTML;
    document.getElementById("numClass").innerHTML = parseInt(temp) - 1;
    document.getElementById("clsInp").value = parseInt(temp) - 1;
  }
</script>

</body>

</html>
