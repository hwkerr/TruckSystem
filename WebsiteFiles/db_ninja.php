<?php

function ninja_connect()
{
	include "../inc/dbinfo.inc";
	return mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
}

function ninja_login($email, $pword)
{
	$db = ninja_connect();
	$pst = $db->prepare("SELECT PassHash, TempPass, UserID FROM
			     Account WHERE Email = ?");
	$pst->bind_param("s", $email);
	$pst->execute();
	$res =  $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		if (password_verify($pword, $row['PassHash']))
		{
			session_start();  // TODO move session stuff somewhere else
			$uid = $row['UserID'];
			$_SESSION['UserID'] = $uid;
			$_SESSION['Logged'] = true;
			$_SESSION['Email'] = $email;
			
			$pst2 = $db->prepare("SELECT Accepted FROM Driver WHERE UserID = ?");
			$pst2->bind_param("s", $uid);
			$pst2->execute();
			$res2 =  $pst2->get_result();
			$type = -3;
			$res2->data_seek(0);
			if ($row2 = $res2->fetch_assoc())
			{
				$_SESSION['UserType'] = "Driver";
				$type = 1;
			}
			else
			{
				$pst2 = $db->prepare("SELECT Accepted FROM Sponsor WHERE UserID = ?");
				$pst2->bind_param("s", $uid);
				$pst2->execute();
				$res2 =  $pst2->get_result();
				$res2->data_seek(0);
				if ($row2 = $res2->fetch_assoc())
				{
					$_SESSION['UserType'] = "Sponsor";
					$type = 2;
				}
				else
				{
					$pst2 = $db->prepare("SELECT UserID FROM Admin WHERE UserID = ?");
					$pst2->bind_param("s", $uid);
					$pst2->execute();
					$res2 =  $pst2->get_result();
					$res2->data_seek(0);
					if ($row2 = $res2->fetch_assoc())
					{
						$_SESSION['UserType'] = "Admin";
						$type = 3;
					}
				}
			}
			if ($row['TempPass'] == 1)
			{
				return 0;
			}
			else
			{
				return $type;
			}
		}
		else
		{
			return -1;
		}
	}
	else
	{
		return -2;
	}
}

function ninja_points($uid)
{
	$total = 0;

	$db = ninja_connect();
	
	$pst = $db->prepare("SELECT SUM(Amount) AS Total FROM PointAddition WHERE DriverID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		$total += $row['Total'];
	}

	$pst = $db->prepare("SELECT SUM(ItemOrderCatalogItem.PointPrice) AS Total FROM ItemOrderCatalogItem INNER JOIN ItemOrder ON ItemOrderCatalogItem.OrderID = ItemOrder.OrderID WHERE ItemOrder.DriverID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		$total -= $row['Total'];
	}

	return $total;
}

function ninja_email_from_id($uid)
{
	$db = ninja_connect();
	$pst = $db->prepare("SELECT Email FROM Account WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res =  $pst->get_result();
	$res->data_seek(0);
	$email = "";
	if ($row = $res->fetch_assoc())
	{
		$email = $row['Email'];
	}
	return $email;
}

function ninja_pfp_from_id($uid)
{
	$db = ninja_connect();
	$st = $db->query("SELECT Image FROM Account WHERE UserID = '$uid'");
	if ($row = $st->fetch_assoc())
	{
		$image = $row['Image'];
	}
	return $image;
}

?>
