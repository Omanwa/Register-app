<?php 
@session_start();
?>
<?php require_once('Connections/connections.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['DelUserhiddenField'])) && ($_POST['DelUserhiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM register WHERE userid=%s",
                       GetSQLValueString($_POST['DelUserhiddenField'], "int"));

  
  $Result1 = mysqli_query($connections,$deleteSQL) or die(mysqli_error($connections));

  $deleteGoTo = "admin -ManageUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}

$query_User = sprintf("SELECT * FROM register WHERE username = %s", GetSQLValueString($colname_User, "text"));
$User = mysqli_query($connections,$query_User) or die(mysqli_error($connections));
$row_User = mysqli_fetch_assoc($User);
$totalRows_User = mysqli_num_rows($User);

$maxRows_Manageusers = 10;
$pageNum_Manageusers = 0;
if (isset($_GET['pageNum_Manageusers'])) {
  $pageNum_Manageusers = $_GET['pageNum_Manageusers'];
}
$startRow_Manageusers = $pageNum_Manageusers * $maxRows_Manageusers;


$query_Manageusers = "SELECT * FROM register ORDER BY `Timestamp` DESC";
$query_limit_Manageusers = sprintf("%s LIMIT %d, %d", $query_Manageusers, $startRow_Manageusers, $maxRows_Manageusers);
$Manageusers = mysqli_query($connections,$query_limit_Manageusers) or die(mysqli_error($connections));
$row_Manageusers = mysqli_fetch_assoc($Manageusers);

if (isset($_GET['totalRows_Manageusers'])) {
  $totalRows_Manageusers = $_GET['totalRows_Manageusers'];
} else {
  $all_Manageusers = mysqli_query($connections,$query_Manageusers);
  $totalRows_Manageusers = mysqli_num_rows($all_Manageusers);
}
$totalPages_Manageusers = ceil($totalRows_Manageusers/$maxRows_Manageusers)-1;

$queryString_Manageusers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Manageusers") == false && 
        stristr($param, "totalRows_Manageusers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Manageusers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Manageusers = sprintf("&totalRows_Manageusers=%d%s", $totalRows_Manageusers, $queryString_Manageusers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>admin_manageusers</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Container">
<div id="Header"></div>
<div id="NavBar">
	<nav>
        <ul>
             <li><a href="./admin.php">Admin</a></li>
        	  <li><a href="./admin -ManageUsers.php">ManageUsers</a></li>
            <li><a href="./forgotpassword.php">ForgotPassword</a></li>
            <li><a href="./logout.php">Logout</a></li>
        </ul>
    </nav>

</div>
<div id="Content">
	<div id="PageHeading">
	  <h1> Admin Cp</h1>
	  <h2>&nbsp;</h2>
	</div>
	<div id="ContentLeft"></div>
    <div id="ContentRight">
      <table width="670" border="0" align="center">
        <tr>
          <td align="right" valign="top">Showing&nbsp;<?php echo ($startRow_Manageusers + 1) ?>to <?php echo min($startRow_Manageusers + $maxRows_Manageusers, $totalRows_Manageusers) ?>of <?php echo $totalRows_Manageusers ?></td>
        </tr>
        <tr>
          <td align="center" valign="top"><?php do { ?>
            <?php if ($totalRows_Manageusers > 0) { // Show if recordset not empty ?>
  <table width="500" border="0">
    <tr>
      <td><?php echo $row_Manageusers['firstname']; ?>-<?php echo $row_Manageusers['lastname']; ?>- <?php echo $row_Manageusers['email']; ?></td>
      </tr>
    <tr>
      <td><form id="DelUserForm" name="DelUserForm" method="post" action="">
        <input name="DelUserhiddenField" type="hidden" id="DelUserhiddenField" value="<?php echo $row_Manageusers['userid']; ?>" />
        <input type="submit" name="DeleteUserButton" id="DeleteUserButton" value="DeleteUser" />
        </form></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
          <?php } while ($row_Manageusers = mysqli_fetch_assoc($Manageusers)); ?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><?php if ($pageNum_Manageusers > 0) { // Show if not first page ?>
              <p><a href="<?php printf("%s?pageNum_Manageusers=%d%s", $currentPage, min($totalPages_Manageusers, $pageNum_Manageusers + 1), $queryString_Manageusers); ?>">Next</a></p>
              <?php } // Show if not first page ?>
            <?php if ($pageNum_Manageusers > 0) { // Show if not first page ?>
              <p>&nbsp;<a href="<?php printf("%s?pageNum_Manageusers=%d%s", $currentPage, max(0, $pageNum_Manageusers - 1), $queryString_Manageusers); ?>">Previous</a></p>
              <?php } // Show if not first page ?>          </td>
        </tr>
      </table>
    </div>  
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysqli_free_result($User);

mysqli_free_result($Manageusers);
?>
