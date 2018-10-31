<?php
include databases.php
include toolbox.php

$username="username";
$password="password";
$database="your_database";
$mysqli = new mysqli("localhost", $username, $password, $database);
@mysql_select_db($database) or die( "Unable to select database");
$query2="SELECT * FROM tablename";
$result=$mysqli->query($query2);
$num=$mysqli->mysqli_num_rows($result);
$mysqli->close();
echo "<b>
<center>Database Output</center>
</b>
<br>
<br>";
$i=0;
while ($i < $num) {
$field1-name=mysql_result($result,$i,"field1-name");
$field2-name=mysql_result($result,$i,"field2-name");
$field3-name=mysql_result($result,$i,"field3-name");
$field4-name=mysql_result($result,$i,"field4-name");
$field5-name=mysql_result($result,$i,"field5-name");
echo "<b>
$field1-name $field2-name2</b>
<br>
$field3-name<br>
$field4-name<br>
$field5-name<hr>
<br>";
$i++;
}
?>
