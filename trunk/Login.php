<!DOCTYPE html>

<?php
// Open a connection to the database
// (display an error if the connection fails)
$conn = mysqli_connect('localhost', 'Admin', 'F8eGy7WtY3HScSnU') or die(mysqli_error());
mysqli_select_db($conn, 'logintest') or die(mysqli_error());
?>
<?php session_start(); ?>

<html>


<html>
<head>
<title>Login</title>
</head>
<body>

<h1>Login Test!</h1>
<?php
// Only execute if we're receiving a post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // This will be the string we collect errors in
    $errors = '';


    // Make sure the username field is filled
    $username = $_POST['username'];
    $username = mysqli_real_escape_string($conn, $username);
    $username = htmlspecialchars($username, ENT_COMPAT | ENT_HTML401);
    if (empty($username)) $errors .= '<li>Username is required</li>';

    // Make sure the password field is filled
    $password = $_POST['password'];
    $password = mysqli_real_escape_string($conn, $password);
    $password = htmlspecialchars($password, ENT_COMPAT | ENT_HTML401);
    if (empty($password)) $errors .= '<li>Password is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
     // First, check for that username already being taken
    $user_results = mysqli_query($conn, "SELECT * FROM users WHERE Username = '" . $username . "'");
    // We don't care what the result is
    // If there is one, that means the username is taken
    if ($user_results) {
		$_SESSION['loggedin']=TRUE;
		header( "location: /wwiidatabase/WWII.php" );
	
    }

    }
}
?>

<form action="" method="post">
	<label for="username">Username</label><br/>
	<input type="text" name="username"/><br/>
	<label for="username">Password</label><br/>
	<input type="password" name="password"/><br/>
    <input type="submit" value="Login"/><br/>
</form>
<a href="/wwiidatabase/Register.php" style="float:right">New User?</a>



</body>
</html>
