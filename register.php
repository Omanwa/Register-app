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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="exist.php";
  $loginUsername = $_POST['UserName'];
  $LoginRS__query = sprintf("SELECT username FROM register WHERE username=%s", GetSQLValueString($loginUsername, "text"));
  
  $LoginRS=mysqli_query($connections,$LoginRS__query) or die(mysqli_error($connections));
  $loginFoundUser = mysqli_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf("INSERT INTO register (firstname, lastname, email, username, password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  
  $Result1 = mysqli_query($connections,$insertSQL) or die(mysqli_error($connections));

  $insertGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$query_Register = "SELECT * FROM register";
$Register = mysqli_query($connections,$query_Register) or die(mysqli_error($connections));
$row_Register = mysqli_fetch_assoc($Register);
$totalRows_Register = mysqli_num_rows($Register);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>register</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
</head>
<body>
<div id="Container">
<div id="Header"></div>
<div id="NavBar">
	<nav>
    	<ul>
        	<li><a href="./login.php">Login</a></li>
            <li><a href="./register.php">Register</a></li>
            <li><a href="./admin.php">Admin</a></li>
        </ul>
    </nav>

</div>
<div id="Content">
	<div id="PageHeading">
	  <h1>SignUp!</h1>
	  <h2>Account links</h2>
	</div>
	<div id="ContentLeft"></div>
    <div id="ContentRight">
      <form id="RegisterForm" name="RegisterForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="300" border="0" align="center">
          <tr>
            <td><h3><span id="sprytextfield1">
              <label for="FirstName"></label>
              FirstName:<br />
              <input name="FirstName" type="text" class="ppf" id="FirstName" />
            </span></h3>
            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
            <td><h3><span id="sprytextfield2">
              <label for="LastName"></label>
              LastName:<br />
              <input name="LastName" type="text" class="ppf" id="LastName" />
            </span></h3>
            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td><h3><span id="sprytextfield3">
              <label for="UserName"></label>
              Username:<br />
              <input name="UserName" type="text" class="ppf" id="UserName" />
</span></h3>
            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><h3><span id="sprytextfield4">
              <label for="Email"></label>
              Email:<br />
              <input name="Email" type="text" class="ppf" id="Email" />
              <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></h3>
            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><h3><span id="sprypassword1">
              <label for="Password"></label>
              Password:
<input name="Password" type="password" class="ppf" id="Password" />
            </span></h3>
            <span><span class="passwordRequiredMsg">A value is required.</span></span></td>
            <td><h3><span id="spryconfirm1">
              <label for="ConfirmPassword"></label>
              ConfirmPassword:
              <input name="ConfirmPassword" type="password" class="ppf" id="ConfirmPassword" />
            </span></h3>
            <span><span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
          </tr>
          <tr>
            <td><input name="Reset" type="reset" class="kip" id="Reset" value="Reset" /></td>
            <td><input name="RegisterButton" type="submit" class="kip" id="RegisterButton" value="Register" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="RegisterForm" />
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "Password");
</script>
</body>
</html>
<?php
mysqli_free_result($Register);
?>
