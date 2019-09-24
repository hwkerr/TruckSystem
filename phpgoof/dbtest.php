<?php
$link = new mysqli('database-1.c3icyvcxw6xg.us-east-1.rds.amazonaws.com', 'admin', 'beefsteak', 'trucks');
if (!$link) 
{
	die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully to:' . "\n";
echo $link->host_info . "\n";

$res = $link->query("SELECT Email FROM Account");
echo 'All account emails:' . "\n";
$res->data_seek(0);
while ($row = $res->fetch_assoc())
{
	print_r($row);
	echo $row['Email'] . "\n";
}

echo 'Prepared version:' . "\n";
$st = $link->prepare("SELECT ? FROM Account");
if (!$st->bind_param("s", $email))
{
	echo "Binding failed" . "\n";
}
$email = 'Email';
if (!$st->execute())
{
	echo "Execute failed" . "\n";
}
$res2 = NULL;
if (!$st->bind_result($res2))
{
	echo "Bind failed" . "\n";
}
$st->data_seek(0);
while ($st->fetch())
{
	echo $res2 . "\n";
}
$st->close();
mysqli_close($link);
?> 
