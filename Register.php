<!DOCTYPE html>

<?php
$server = 'whale.csse.rose-hulman.edu';

// Connect to MSSQL
$link = odbc_connect("Driver={SQL Server Native Client 10.0};Server=whale.csse.rose-hulman.edu;Database=Nadine_WWII", 'coxbc', 'whales');

if (!$link) {
    die('Something went wrong while connecting to MSSQL');
}
?>

<html>
<head>
<title>Register</title>
</head>
<body>

<h1>Register</h1>

<?php
// Only execute if we're receiving a post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // This will be the string we collect errors in
    $errors = '';

    // Make sure the name field is filled
    $fname =  $_POST['fname'];
    $fname =  odbc_access_escape_str($fname);
    $fname = htmlspecialchars($fname, ENT_COMPAT | ENT_HTML401);  
    if (empty($fname)) $errors .= '<li>First Name is required</li>';
	
	$lname =  $_POST['lname'];
    $lname = odbc_access_escape_str($lname);
    $lname = htmlspecialchars($lname, ENT_COMPAT | ENT_HTML401);  
    if (empty($lname)) $errors .= '<li>Last Name is required</li>';
	$email =  $_POST['email'];
    $email = odbc_access_escape_str($email);
    $email = htmlspecialchars($email, ENT_COMPAT | ENT_HTML401);  
    if (empty($email)) $errors .= '<li>Email is required</li>';

    // Make sure the username field is filled
    $username = $_POST['username'];
    $username = odbc_access_escape_str($username);
    $username = htmlspecialchars($username, ENT_COMPAT | ENT_HTML401);
    if (empty($username)) $errors .= '<li>Username is required</li>';

    // Make sure the password field is filled
    $password = $_POST['password'];
    $password = odbc_access_escape_str($password);
    $password = htmlspecialchars($password, ENT_COMPAT | ENT_HTML401);
    if (empty($password)) $errors .= '<li>Password is required</li>';
    // Make sure the passwords match
    $confirm = $_POST['confirmpassword'];
    if (strcmp($password, $confirm) != 0) $errors .= '<li>Passwords do not match</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
     // First, check for that username already being taken
    $user_results = odbc_exec($link, "SELECT Username FROM Users WHERE Username = '" . $username . "'");

    // We don't care what the result is
    // If there is one, that means the username is taken
    if (odbc_fetch_row($user_results)) {
        	echo '<ul><li>Username already taken</li></ul>';
    // If no duplicates are found, go ahead and create the new user
    } else {
	echo '<script>alert("Check")</script>';
	odbc_exec($link,"INSERT INTO Users (FName, LName, Email, Username, Password) " .
                    "VALUES ('" . $fname . "', '" . $lname . "','" . $email . "', '" . 
                                  $username .
                                  "', '" . sha1("'" . $username . $password . "'") . "')") or die(‘error’);
								  

        // Show a success message
        echo '<ul><li>Registration successful!</li></ul>';
		$_SESSION['loggedin']=TRUE;

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $fname = '';
		$lname = '';
		$email = '';
        $username = '';
		header( "location: /WWII.php" );
    }

    }
}
?>
<?php
function odbc_access_escape_str($str) {
 $out="";
 for($a=0; $a<strlen($str); $a++) {
  if($str[$a]=="'") {
   $out.="''";
  } else
  if($str[$a]!=chr(10)) {
   $out.=$str[$a];
  } 
 }
 return $out; 
}
?>


<form action="" method="post">
    <label for="fname">First Name</label><br/>
    <input type="text" name="fname"/><br/>
	
	<label for="lname">Last Name</label><br/>
    <input type="text" name="lname"/><br/>

	<label for="email">Email</label><br/>
    <input type="text" name="email"/><br/>
	
    <label for="username">Username</label><br/>
    <input type="text" name="username"/><br/>

    <label for="password">Password</label><br/>
    <input type="password" name="password"/><br/>

    <label for="confirmpassword">Confirm Password</label><br/>
    <input type="password" name="confirmpassword"/><br/>
	
    <input type="submit" value="Register"/>
</form>

</body>
</html>
