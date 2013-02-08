<!DOCTYPE html>

<?php
$server = 'whale.csse.rose-hulman.edu';

// Connect to MSSQL
$link = odbc_connect("Driver={SQL Server Native Client 10.0};Server=whale.csse.rose-hulman.edu;Database=Nadine_WWII", 'coxbc', 'whales');

if (!$link) {
    die('Something went wrong while connecting to MSSQL');
}
?>
<?php session_start(); ?>
<?php
  $bg = array('/BG1.jpg', '/BG2.jpg', '/BG3.jpg', '/BG4.jpg', '/BG5.jpg', '/BG6.jpg', '/BG7.jpg', '/BG8.jpg', '/BG9.jpg', '/BG10.jpg'); // array of filenames

  $i = rand(0, count($bg)-1); // generate random number size of the array
  $selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen

?>

<html>
<style type="text/css">
body { 
background: url(<?php echo $selectedBg; ?>) no-repeat center center fixed;
-moz-background-size: cover;
-webkit-background-size: cover;
-o-background-size: cover;
background-size: cover;
}
.box_textshadow {
     text-shadow: 3px 2px 0px #000000; /* FF3.5+, Opera 9+, Saf1+, Chrome, IE10 */
}
a:hover {
  color: black ;
  background-color: #ff0 ;
}
</style>


<head>
<title>Login</title>
</head>
<body>
<!--img src="/BG1.jpg" /-->

<h1 class="box_textshadow" style="font: 35px"><font color = "FFFFFF">Login</font></h1>
<?php
// Only execute if we're receiving a post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // This will be the string we collect errors in
    $errors = '';


    // Make sure the username field is filled
    $username = $_POST['username'];
    $username = htmlspecialchars($username, ENT_COMPAT | ENT_HTML401);
    if (empty($username)) $errors .= '<li>Username is required</li>';

    // Make sure the password field is filled
    $password = $_POST['password'];
    $password = htmlspecialchars($password, ENT_COMPAT | ENT_HTML401);
    if (empty($password)) $errors .= '<li>Password is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {	
	$data = odbc_exec($link, "SELECT * FROM Users WHERE Username = '" . $username . "' AND Password = '" . sha1("'" . $username . $password . "'") . "'");
			
    // We don't care what the result is
    // If there is one, that means the username is taken
	//Hash
    if (odbc_fetch_row($data)) {
		$_SESSION['loggedin']=TRUE;
		header( "location: /WWII.php" );
	
    }else{
		echo '<strong><font size="5" color="#FF0000">Login unsuccessful</font></strong><br /><br />';
	}

    }
}
?>

<form action="" method="post">
	<label for="username" style="font-size:30px"><strong class="box_textshadow"><font color = "FFFFFF">Username</font></strong></label><br/>
	<input type="text" style="width:200px; height: 25px;" name="username"/><br/>
	<label for="username" style="font-size:30px"><strong class="box_textshadow"><font color = "FFFFFF">Password</font></strong></label><br/>
	<input type="password" style="width:200px;  height: 25px;" name="password"/><br/><br />
    <input type="submit" style="width:100px; height: 40px;" value="Login"/><br/>
</form>
<a href="/Register.php" style="float:right; font-size:20px"><strong>New User?</strong></a>



</body>
</html>
