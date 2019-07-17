<?php

$con = @mysql_connect("localhost","root","");
//$con = @mysql_connect("localhost","root","O+E7vVgBr#{}");
if ($con) {
		mysql_select_db('skilex');
    } else {
		die("Connection failed");
}
?>