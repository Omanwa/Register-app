<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>logout</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>
<body>
<div id="Container">
<div id="Header"></div>
<div id="NavBar">
	<nav>
    	<ul>
            <?php if($_SESSION['MM_UserGroup'] == 1): ?>
            <li><a href="./services.php"> Services</a></li>
            <li><a href="./forgotpassword.php">ForgotPassword</a></li>
            <li><a href="./logout.php">Logout</a></li>
            <?php elseif($_SESSION['MM_UserGroup'] == 2): ?>
            <li><a href="./admin.php">Admin</a></li>
        	  <li><a href="./admin -ManageUsers.php">ManageUsers</a></li>
            <li><a href="./forgotpassword.php">ForgotPassword</a></li>
            <li><a href="./logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>
<div id="Content">
	<div id="PageHeading">
	  <h1>Email Password</h1>
	</div>
	<div id="ContentLeft"></div>
    <div id="ContentRight">
      <form id="EMPWform" name="EMPWform" method="post" action="EMPWscript.php">
        <span id="sprytextfield1">
        <label for="Email"></label>
        Email:<br />
        <input name="Email" type="text" class="ppf" id="Email" />
        <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input name="EMPWButton" type="submit" class="kip" id="EMPWButton" value="RecoverPw" />
        </p>
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
</script>
</body>
</html>