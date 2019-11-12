<?php

include "db_ninja.php";

session_start();

        if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
        {
                header("location: logon.php");
                exit;
        }
?>

<html>
<head>

</head>
<body>
<?php include "driver_header.php"; ?>
</body>
</html>
