<?php require_once('Connections/connections.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
unset($_SESSION['PrevUrl']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
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

$colname_Logout = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Logout = $_SESSION['MM_Username'];
}

$query_Logout = sprintf("SELECT * FROM register WHERE username = %s", GetSQLValueString($colname_Logout, "text"));
$Logout = mysqli_query($connections,$query_Logout) or die(mysqli_error($connections));
$row_Logout = mysqli_fetch_assoc($Logout);
$totalRows_Logout = mysqli_num_rows($Logout);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>logout</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
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
	  <h1> You Have logged out sucessifully!	</h1>
	</div>
	<div id="ContentLeft"></div>
    <div id="ContentRight"></div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysqli_free_result($Logout);
?>
