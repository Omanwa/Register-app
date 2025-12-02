<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connections = "localhost";
$database_connections = "testingserver";
$username_connections = "root";
$password_connections = "";
$connections = mysqli_connect($hostname_connections, $username_connections, $password_connections,$database_connections); 
if(!$connections){
    echo "Cannot connect to the database ". mysqli_error($connections);
    exit;
}

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}
?>