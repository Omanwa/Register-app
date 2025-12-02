<?php require_once('Connections/connections.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  global $connections;
if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connections,$theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


$query_login2 = "SELECT * FROM register";
$login2 = mysqli_query($connections,$query_login2) or die(mysqli_error($connections));
$row_login2 = mysqli_fetch_assoc($login2);
$totalRows_login2 = mysqli_num_rows($login2);
$query_login2 = "SELECT * FROM register";
$login2 = mysqli_query($connections,$query_login2) or die(mysqli_error($connections));
$row_login2 = mysqli_fetch_assoc($login2);
$totalRows_login2 = mysqli_num_rows($login2);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['UserName'])) {
  $loginUsername=$_POST['UserName'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "userlevel";
  $MM_redirectLoginSuccess = "admin.php";
  $MM_redirectLoginFailed = "Wrong.php";
  $MM_redirecttoReferrer = true;
  
  	
  $LoginRS__query=sprintf("SELECT username, password, userlevel FROM register WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query($connections,$LoginRS__query) or die(mysqli_error($connections));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysqli_result($LoginRS,0,'userlevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>login2</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
</head>
<body>
<div id="Container">
<div id="Header"></div>
<div id="NavBar">
	<nav>
    	<ul>
        	<li><a href="./login.php">BackToUserLogin</a></li>
            
      </ul>
    </nav>

</div>
<div id="Content">
	<div id="PageHeading">
	  <h1> Login!</h1>
	  <h2>&nbsp;</h2>
	</div>
	<div id="ContentLeft">Admin login!</div>
    <div id="ContentRight">
      <form id="LoginForm" name="LoginForm" method="POST" action="<?php echo $loginFormAction; ?>">
        <table width="400" border="0" align="center">
          <tr>
            <td><span id="sprytextfield1">
              <label for="UserName"></label>
              UserName:<br />
<input name="UserName" type="text" class="ppf" id="UserName" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprypassword1">
              <label for="Password"></label>
Password: <br />
              <input name="Password" type="password" class="ppf" id="Password" />
            <span class="passwordRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><input name="LoginButton" type="submit" class="kip" id="LoginButton" value="Login" /></td>
          </tr>
        </table>
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysqli_free_result($login2);
?>
