<?php

function dojo_connect()
{
	include "../inc/dbinfo.inc";
	return mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
}

function ninja_login($email, $pword)
{
	$db = dojo_connect();
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
			
			$pst2 = $db->prepare("SELECT UserID FROM Driver WHERE UserID = ?");
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
				$pst2 = $db->prepare("SELECT UserID FROM Sponsor WHERE UserID = ?");
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

function ninja_points($uid, $cid)
{
	$total = 0;

	$db = dojo_connect();
	
	$pst = $db->prepare("SELECT SUM(PointAddition.Amount) AS Total FROM PointAddition INNER JOIN Sponsor ON PointAddition.SponsorID = Sponsor.UserID WHERE PointAddition.DriverID = ? AND Sponsor.CompanyID = ?");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		$total += $row['Total'];
	}

	$pst = $db->prepare("SELECT SUM(ItemOrderCatalogItem.PointPrice) AS Total FROM (((ItemOrderCatalogItem INNER JOIN ItemOrder ON ItemOrderCatalogItem.OrderID = ItemOrder.OrderID) INNER JOIN CatalogItem ON CatalogItem.ItemID = ItemOrderCatalogItem.ItemID) INNER JOIN CatalogCatalogItem ON CatalogItem.ItemID = CatalogCatalogItem.ItemID) INNER JOIN Catalog ON CatalogCatalogItem.CatalogID = Catalog.CatalogID WHERE ItemOrder.DriverID = ? AND Catalog.CompanyID = ?");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())	
	{
		$total -= $row['Total'];
	}

	return $total;
}

function ninja_check_email_taken($email)
{
	$db = dojo_connect();
	
	$pst = $db->prepare("SELECT Email FROM User WHERE Email = ?");
	$pst->bind_param("s", $email);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
		return true;
	else
		return false;
}

function ninja_apply($type, $fname, $lname, $email, $info)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT AppID FROM Application");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		while ($row = $res->fetch_assoc())
			if ($row['AppID'] === $newid)
				$unique = false;
	}

	// insert application
	$pst = $db->prepare("INSERT INTO Application VALUES (?, ?, ?, ?, ?, ?, 0)");
	$pst->bind_param("ssssss", $newid, $fname, $lname, $email, $info, $type);
	$pst->execute();
}

function dojo_new_generic_account($fname, $lname, $email, $pword)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT UserID FROM Account");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		while ($row = $res->fetch_assoc())
			if ($row['UserID'] === $newid)
				$unique = false;
	}

	// hash password
	$phash = password_hash($pword, PASSWORD_BCRYPT);

	// insert user
	$pst = $db->prepate("INSERT INTO Account VALUES (?, ?, ?, ?, ?, x'', 1, 0)");
	$pst->bind_param("sssss", $newid, $email, $phash, $fname, $lname);
	$pst->execute();
	return $newid;
}

function ninja_new_driver($fname, $lname, $email)
{
	$db = dojo_connect();

	// generate random temporary password
	$tpass = substr(md5(rand()), 0, 8);

	// create account
	$uid = dojo_new_generic_account($fname, $lname, $email, $tpass);

	// create driver
	$pst = $db->prepare("INSERT INTO Driver VALUES (?, '', '', '', '', '')");
	$pst->bind_param("s", $uid);
	$pst->execute();
	return $tpass;
}

function ninja_new_sponsor($fname, $lname, $email, $cid)
{
	$db = dojo_connect();
	
	// generate random temporary password
	$tpass = substr(md5(rand()), 0, 8);
	
	// create account
	$uid = dojo_new_generic_account($fname, $lname, $email, $tpass);

	// create sponsor
	$pst = $db->prepare("INSERT INTO Sponsor VALUES (?, ?)");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
	return $tpass;
}

function ninja_accept_company($cname, $fname, $lname, $email)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT CompanyID FROM Company");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		while ($row = $res->fetch_assoc())
			if ($row['CompanyID'] === $newid)
				$unique = false;
	}
	
	// create company
	$pst = $db->prepare("INSERT INTO Company VALUES (?, ?, x'', 0)");
	$pst->bind_param("ss", $newid, $cname);
	$pst->execute();

	// create sponsor account for company
	$tpass = ninja_new_sponsor($fname, $lname, $email, $newid);
	return $tpass;
}

function ninja_email($uid)
{
	$db = dojo_connect();
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

function ninja_pfp($uid)
{
	$db = dojo_connect();
	$st = $db->query("SELECT Image FROM Account WHERE UserID = '$uid'");
	if ($row = $st->fetch_assoc())
	{
		$image = $row['Image'];
	}
	return $image;
}

function ninja_company_name($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Name FROM Company WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	$name = "";
	if ($row = $res->fetch_assoc())
	{
		$name = $row['Name'];
	}
	return $name;
}

function ninja_current_driver_company($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Company.Name AS CName FROM Driver INNER JOIN Company ON Driver.CurrComp = Company.CompanyID WHERE Driver.UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	$name = "";
	if ($row = $res->fetch_assoc())
	{
		$name = $row['CName'];
	}
	return $name;
}

function ninja_driver_company_list($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Company.Name AS CName FROM (Company INNER JOIN DriverCompany ON Company.CompanyID = DriverCompany.CompanyID) INNER JOIN Driver ON DriverCompany.DriverID = Driver.UserID WHERE Driver.UserID = ? AND DriverCompany.Accepted = 1");
}

?>
