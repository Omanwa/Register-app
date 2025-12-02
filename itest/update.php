<?php @session_start(); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateForm")) {
  $updateSQL = sprintf("UPDATE register SET password=%s WHERE userid=%s",
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserIdhiddenField'], "int"));

  
  $Result1 = mysqli_query($connections,$updateSQL) or die(mysqli_error($connections));

  $updateGoTo = "services.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}

$query_User = sprintf("SELECT * FROM register WHERE username = %s", GetSQLValueString($colname_User, "text"));
$User = mysqli_query($connections,$query_User) or die(mysqli_error($connections));
$row_User = mysqli_fetch_assoc($User);
$totalRows_User = mysqli_num_rows($User);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update</title>
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
          	<li><a href="./login.php">Login</a></li>
            <li><a href="./services.php"> Services</a></li>
            <li><a href="./register.php">Register</a></li>
            <li><a href="./admin.php">Admin</a></li>
            <li><a href="./logout.php">Logout</a></li>
        </ul>
    </nav>

</div>
<div id="Content">
	<div id="PageHeading">
	  <h1>Update Account</h1>
	  <h2>&nbsp;</h2>
	</div>
	<div id="ContentLeft"></div>
    <div id="ContentRight">
      <form id="UpdateForm" name="UpdateForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="600" border="0">
          <tr>
            <td><h2>Account:<?php echo $row_User['firstname']; ?> <?php echo $row_User['lastname']; ?> Username:<?php echo $row_User['username']; ?></h2></td>
          </tr>
        </table>
        <table width="400" border="0" align="center">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprytextfield1">
              <label for="Email"></label>
              Email:
              <br />
              <input name="Email" type="text" class="ppf" id="Email" value="<?php echo $row_User['email']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprypassword1">
              <label for="Password"></label>
              Password:<br />
              <input name="Password" type="password" class="ppf" id="Password" value="<?php echo $row_User['password']; ?>" />
            <span class="passwordRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td align="center"><input name="UpdateButton" type="submit" class="kip" id="UpdateButton" value="Update" />
            <input name="UserIdhiddenField" type="hidden" id="UserIdhiddenField" value="<?php echo $row_User['userid']; ?>" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="UpdateForm" />
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
mysqli_free_result($User);
?>
