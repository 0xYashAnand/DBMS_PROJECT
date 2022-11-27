<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Adminstrator</title>
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
  if(isset($_POST['fname']) or isset($_POST['mname']) or isset($_POST['lname']) or isset($_POST['ugend'])){
    $sql = "UPDATE PERSON SET ";
  }
  if(isset($_POST['fname'])){
    $sql .= "FirstName='".$_POST['fname']."'";
  }
  if(isset($_POST['mname'])){
    $sql .= ", MiddleName='".$_POST['mname']."'";
  }
  if(isset($_POST['lname'])){
    $sql .= ", LastName='".$_POST['lname']."'";
  }

  if(isset($_POST['ugend'])){
    if($_POST['ugend'] == 'Male' or $_POST['ugend'] == "M"){
      $sql .= ", Gender='M'";
    }
    if($_POST['ugend'] == 'Female' or $_POST['ugend'] == "F"){
      $sql .= ", Gender='F'";
    }
    if($_POST['ugend'] == 'Transgender' or $_POST['ugend'] == "T"){
      $sql .= ", Gender='T'";
    }
    $sql .= "WHERE PersonID = '".$_SESSION["personid"]."'";
    $conn->query($sql);
  }

  if ($_SESSION["phchanged"] == "true") {
    $sql = "DELETE FROM PHONE WHERE PersonID = '";
    $sql .= $_SESSION["personid"]."'";
    $conn->query($sql);

    if(isset($_POST["ph0"])){
        if ($_POST["ph0"] != "") {
          $sql = "INSERT INTO PHONE VALUES(";
          $sql .= $_SESSION["personid"].", ".$_POST["ph0"].")";
          $conn->query($sql);
        }  
    }
    
    $_SESSION["phchanged"] = "false";
  }


  if ($_SESSION["usertype"] == "instructor" AND isset($_POST['insdept'])) {
    $sql = "SELECT DeptNo FROM DEPARTMENT WHERE DeptName = '";
    $sql .= $_POST['insdept']."'";
    $res = $conn->query($sql);

    $deptno = $res->fetch_assoc()['DeptNo'];

    $sql = "UPDATE INSTRUCTOR SET DeptNo = ".$deptno." WHERE InstructorID = '";
    $sql .= $_SESSION["userid"]."'";
    $conn->query($sql);
  }
?>

<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
</div>


<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-user-edit">
  <input type="submit" value="SUBMIT" class="btn-submit rect-circ"/>

  <div class="user-type-label">
    <?php
      if ($_SESSION["usertype"] == "student")
        echo '<span>STUDENT</span>';
      elseif ($_SESSION["usertype"] == "instructor")
        echo '<span>INSTRUCTOR</span>';
    ?>
  </div>

  <?php
    $userarray = array();
    if ($_SESSION["usertype"] == "student") {
      $sql = "SELECT StudentID, FirstName, MiddleName, LastName, Gender, STUDENT.PersonID
              FROM STUDENT, PERSON
              WHERE STUDENT.PersonID = PERSON.PersonID
              AND StudentID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $_SESSION["personid"] = $row['PersonID'];
          array_push($userarray,
            $row['StudentID'], $row['FirstName'], $row['MiddleName'], $row['LastName'], $row['Gender']);
        }
      }
    }

    elseif ($_SESSION["usertype"] == "instructor") {
      $sql = "SELECT InstructorID, FirstName, MiddleName, LastName, Gender, PERSON.PersonID
              FROM INSTRUCTOR, PERSON
              WHERE INSTRUCTOR.PersonID = PERSON.PersonID
              AND InstructorID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $_SESSION["personid"] = $row['PersonID'];
          array_push($userarray,
            $row['InstructorID'], $row['FirstName'], $row['MiddleName'], $row['LastName'], $row['Gender']);
        }
      }
    }

    echo '<div class="user-person">
      <div class="rect-round-sm">'.$userarray[0].'</div>
      <input type="text" name="fname" value="'.$userarray[1].'" class="edit-id rect-round-sm">
      <input type="text" name="mname" value="'.$userarray[2].'" class="edit-id rect-round-sm">
      <input type="text" name="lname" value="'.$userarray[3].'" class="edit-id rect-round-sm">
      
      <select name="ugend" class="rect-round-sm">';
        if ($userarray[4] == 'M')
          echo '<option selected="selected">Male</option>';
        else
          echo '<option>Male</option>';
        
        if ($userarray[4] == 'F')
          echo '<option selected="selected">Female</option>';
        else
          echo '<option>Female</option>';

        if ($userarray[4] == 'T')
          echo '<option selected="selected">Transgender</option>';
        else
          echo '<option>Transgender</option>';
      echo '</select>
    </div>';
  ?>

  <?php
    $sql = "SELECT PhNo FROM PHONE WHERE PersonID = ";
    $sql .= $_SESSION["personid"];
    $res = $conn->query($sql);

    $phones = array(); $pcnt = 0;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($phones, $row['PhNo']);
        $pcnt++;
        
        if ($pcnt == 3) break;
      }
    }

    echo '<div class="label-phone"><span>PHONES:</span></div>';
    for($i = 0; $i<COUNT($phones); $i++){
      echo '<input type="number" class="phno rect-round-sm" name="ph0" value="'.$phones[$i].'" maxlength="10">';
    }
    $_SESSION["phchanged"] = "true";

    if ($_SESSION["usertype"] == "instructor") {
      echo '<div class="label-dept"><span>DEPARTMENT:</span></div>
      <select class="user-dept-sel rect-round-sm" name="insdept">';

      $departments = array();
      $sql = "SELECT * FROM DEPARTMENT ORDER BY DeptNo";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push($departments, array($row['DeptNo'], $row['DeptName']));
        }
      }


      $sql = "SELECT DeptNo FROM INSTRUCTOR WHERE InstructorID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      $deptno = 0;
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $deptno = $row['DeptNo'];
        }
      }

      for ($i = 0; $i < COUNT($departments); $i++) {
        if ($deptno == $departments[$i][0])
          echo '<option selected="selected" name="insdept" value="'.$departments[$i][1].'">'.$departments[$i][1].'</option>';
        else
          echo '<option name="insdept" value="'.$departments[$i][1].'">'.$departments[$i][1].'</option>';
      }
      echo '</select>';
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