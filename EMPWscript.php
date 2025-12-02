<?php
@session_start();
$_SESSION['EMPW'] = $_POST['Email'];



?>
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

$colname_EMPWord = "-1";
if (isset($_SESSION['EMPW'])) {
  $colname_EMPWord = $_SESSION['EMPW'];
}

$query_EMPWord = sprintf("SELECT * FROM register WHERE email = %s", GetSQLValueString($colname_EMPWord, "text"));
$EMPWord = mysqli_query($connections,$query_EMPWord) or die(mysqli_error($connections));
$row_EMPWord = mysqli_fetch_assoc($EMPWord);
$totalRows_EMPWord = mysqli_num_rows($EMPWord);

mysqli_free_result($EMPWord);
?>
<?php
	if($totalRows_EMPWord > 0) {

$from="noreply@yourdomain.com";
$email=$_POST['Email'];
$subject="Your domain - Email Password";
$message="Here is your password:".$row_EMPWord['password'];


}
	if($totalRows_EMPWord > 0) {
    try{
      mail( $email, $subject, $message, "From:".$from);
      echo "Please check your email, you have been sent your recovery password";
    }catch(Exception $e){
      echo "Sending mail failed. Exception: ".$e;
    }
		} else {
			echo "fail - please try again!";
		}

?>
