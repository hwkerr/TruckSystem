<?php
$link = new mysqli('database-1.c3icyvcxw6xg.us-east-1.rds.amazonaws.com', 'admin', 'beefsteak', 'trucks');
if (!$link) 
{
	die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully to:' . "\n";
echo $link->host_info . "\n";

if ($argc < 3)
{
	mysqli_close($link);
	die('Need email and password args' . "\n");
}

$st = $link->prepare("SELECT PassHash, TempPass FROM Account WHERE Email = ?");
$st->bind_param("s", $argv[1]);
$st->execute();
$res = $st->get_result();
$res->data_seek(0);
if ($row = $res->fetch_assoc())
{
	if (password_verify($argv[2], $row['PassHash']))
	{
		echo 'Logged in to ' . $argv[1] . "\n";
		if ($row['TempPass'])
		{
			echo 'Your password is temporary, change it!' . "\n";
		}
	}
	else
	{
		echo 'Incorrect password!' . "\n";		
	} 
}
else
{
	echo 'Username not found' . "\n";
}
mysqli_close($link);
?>
