
<?php

$db_name = "";
$mysql_user = "";
$mysql_pass = "";
$server_name = "";

$con = mysqli_connect($server_name, $mysql_user, $mysql_pass, $db_name);

if(!$con)
{
	//echo "Blad bazy danych :/".mysqli_connect_error();
}
else
{
	//echo"<h3>Polaczono pomyslnie!!!</h3>";
}

?>