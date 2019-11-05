<?php

function dojo_connect()
{
	include "/var/www/inc/dbinfo.inc";
	return mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
}

function ninja_login($email, $pword)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT PassHash, TempPass, UserID, FName, LName FROM
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
			$_SESSION['FName'] = $row['FName'];
			$_SESSION['LName'] = $row['LName'];
			
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

function ninja_userid($email)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT UserID FROM Account WHERE Email = ?");
	$pst->bind_param("s", $email);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['UserID'];
	}
	return false;
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

function ninja_point_additions($uid, $cid)
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

	return $total;
}

function ninja_point_subtractions($uid, $cid)
{
	$total = 0;

	$db = dojo_connect();
	
	$pst = $db->prepare("SELECT SUM(ItemOrderCatalogItem.PointPrice) AS Total FROM (((ItemOrderCatalogItem INNER JOIN ItemOrder ON ItemOrderCatalogItem.OrderID = ItemOrder.OrderID) INNER JOIN CatalogItem ON CatalogItem.ItemID = ItemOrderCatalogItem.ItemID) INNER JOIN CatalogCatalogItem ON CatalogItem.ItemID = CatalogCatalogItem.ItemID) INNER JOIN Catalog ON CatalogCatalogItem.CatalogID = Catalog.CatalogID WHERE ItemOrder.DriverID = ? AND Catalog.CompanyID = ?");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())	
	{
		$total += $row['Total'];
	}

	return $total;
}

function ninja_check_email_taken($email)
{
	$db = dojo_connect();
	
	$pst = $db->prepare("SELECT Email FROM Account WHERE Email = ?");
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

	// check for existing email in account
	$pst = $db->prepare("SELECT Email FROM Account WHERE Email = ?");
	$pst->bind_param("s", $email);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		return 1;
	}
	
	// check for existing email in application
	$pst = $db->prepare("SELECT Email FROM Application WHERE Email = ? AND Processed = 0");
	$pst->bind_param("s", $email);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		return 2;
	}
	
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
	return 0;
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
	$pst = $db->prepare("INSERT INTO Account VALUES (?, ?, ?, ?, ?, x'', 1, 0)");
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

function ninja_current_driver_company($uid)  // modified to return CompanyID
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Company.CompanyID AS CName FROM Driver INNER JOIN Company ON Driver.CurrComp = Company.CompanyID WHERE Driver.UserID = ?");
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
	$pst = $db->prepare("SELECT Company.Name AS CName, Company.CompanyID AS CID FROM (Company INNER JOIN DriverCompany ON Company.CompanyID = DriverCompany.CompanyID) INNER JOIN Driver ON DriverCompany.DriverID = Driver.UserID WHERE Driver.UserID = ? AND DriverCompany.Accepted = 1 AND Company.Deleted = 0");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_name($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT FName, LName FROM Account WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$res->data_seek(0);
	$name = "";
	if ($row = $res->fetch_assoc())
	{
		$name = $row['FName']." ".$row['LName'];
	}
	return $name;
}

function ninja_driver_applications()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT FName, LName, Email, Info FROM Application WHERE UserType = 'Driver' AND Processed = 0");
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_company_applications()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT FName, LName, Email, Info FROM Application WHERE UserType = 'Sponsor' AND Processed = 0");
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_sponsor_company_id($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT CompanyID FROM Sponsor WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$cid = "";
	if ($row = $res->fetch_assoc())
	{
		$cid = $row['CompanyID'];
	}
	return $cid;
}

function ninja_company_driver_list($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT UserID, FName, LName, Email FROM Account INNER JOIN DriverCompany ON UserID = DriverID WHERE CompanyID = ? AND Accepted = 1");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_mark_email_application_processed($email)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Application SET Processed = 1 WHERE Email = ?");
	$pst->bind_param("s", $email);
	$pst->execute();
}

function ninja_user_type($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT UserID FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
		return "Driver";
	$pst = $db->prepare("SELECT UserID FROM Sponsor WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
		return "Sponsor";
	$pst = $db->prepare("SELECT UserID FROM Admin WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
		return "Admin";

	return "error";
}

function ninja_fname($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT FName FROM Account WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$fname = "";
	if ($row = $res->fetch_assoc())
		$fname = $row['FName'];
	return $fname;
}

function ninja_lname($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT LName FROM Account WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$lname = "";
	if ($row = $res->fetch_assoc())
		$lname = $row['LName'];
	return $lname;
}

function ninja_update_name($uid, $fname, $lname)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Account SET FName = ?, LName = ? WHERE UserID = ?");
	$pst->bind_param("sss", $fname, $lname, $uid);
	$pst->execute();
}

function ninja_address_oneline($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Street, Street2, City, State, Zip FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$address = "";
	if ($row = $res->fetch_assoc())
	{
		if ($row['Street2'] === '')
			$address = $row['Street'].', '.$row['City'].', '.$row['State'].', '.$row['Zip'];
		else
			$address = $row['Street'].' '.$row['Street2'].', '.$row['City'].', '.$row['State'].', '.$row['Zip'];
	}
	return $address;
}

function ninja_phone_dashes($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Phone FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$phone = "";
	if ($row = $res->fetch_assoc())
	{
		$phone = $row['Phone'];
		// TODO format
	}
	return $phone;
}

function ninja_phone($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Phone FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$phone = "";
	if ($row = $res->fetch_assoc())
	{
		$phone = $row['Phone'];
	}
	return $phone;
}

function ninja_address_street($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Street AS Value FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$value = "";
	if ($row = $res->fetch_assoc())
	{
		$value = $row['Value'];
	}
	return $value;
}

function ninja_address_street2($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Street2 AS Value FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$value = "";
	if ($row = $res->fetch_assoc())
	{
		$value = $row['Value'];
	}
	return $value;
}

function ninja_address_city($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT City AS Value FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$value = "";
	if ($row = $res->fetch_assoc())
	{
		$value = $row['Value'];
	}
	return $value;
}

function ninja_address_state($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT State AS Value FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$value = "";
	if ($row = $res->fetch_assoc())
	{
		$value = $row['Value'];
	}
	return $value;
}

function ninja_address_zip($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Zip AS Value FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	$value = "";
	if ($row = $res->fetch_assoc())
	{
		$value = $row['Value'];
	}
	return $value;
}

function ninja_address_update($uid, $street, $street2, $city, $state, $zip)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Driver SET Street = ?, Street2 = ?, City = ?, State = ?, Zip = ? WHERE UserID = ?");
	$pst->bind_param("ssssss", $street, $street2, $city, $state, $zip, $uid);
	$pst->execute();
}

function ninja_update_phone($uid, $phone)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Driver SET Phone = ? WHERE UserID = ?");
	$pst->bind_param("ss", $phone, $uid);
	$pst->execute();
}

function ninja_random_admin()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT UserID FROM Admin");
	$pst->execute();
	$res = $pst->get_result();
	$length = 0;
	$res->data_seek(0);
	while ($row = $res->fetch_assoc())
	{
		$length++;
	}
	$target = rand(0, $length-1);
	$current = 0;
	$res->data_seek(0);
	while ($row = $res->fetch_assoc())
	{
		if ($target == $current)
		{
			return $row['UserID'];
		}
		$current++;
	}
	return false;
}

function ninja_drivers()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Account.UserID, CurrComp AS CompanyID, FName, LName, Email FROM Driver INNER JOIN Account ON Driver.UserID = Account.UserID WHERE Deleted = 0");
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_sponsors()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Account.UserID, CompanyID, FName, LName, Email FROM Sponsor INNER JOIN Account ON Sponsor.UserID = Account.UserID WHERE Deleted = 0");
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_companies()
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT CompanyID, Name FROM Company WHERE Deleted = 0");
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_company_sponsor_count($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT COUNT(UserID) AS Sponsors FROM Sponsor WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['Sponsors'];
	}
	return 0;
}

function ninja_company_driver_count($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT COUNT(DriverID) AS Drivers FROM DriverCompany WHERE CompanyID = ? AND Accepted = 1");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['Drivers'];
	}
	return 0;
}

function ninja_driver_apply_company($uid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("INSERT INTO DriverCompany VALUES (?, ?, 0)");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
}

function ninja_company_accept_driver($cid, $uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE DriverCompany SET Accepted = 1 WHERE CompanyID = ? AND DriverID = ?");
	$pst->bind_param("ss", $cid, $uid);
	$pst->execute();
}

function ninja_driver_company_status($uid, $cid)  // -1 = no app, 0 = app submitted, 1 = app approved
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Accepted FROM DriverCompany WHERE DriverID = ? AND CompanyID = ?");
	$pst->bind_param("ss", $uid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['Accepted'];
	}
	else
	{
		return -1;
	}
}

function ninja_company_pic($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Image FROM Company WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		$image = $row['Image'];
	}
	return $image;
}

function ninja_company_decline_driver($cid, $uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("DELETE FROM DriverCompany WHERE CompanyID = ? AND DriverID = ?");
	$pst->bind_param("ss", $cid, $uid);
	$pst->execute();
}

function ninja_company_driver_applications($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT UserID, FName, LName, Email FROM Account INNER JOIN DriverCompany ON UserID = DriverID WHERE DriverCompany.Accepted = 0 AND DriverCompany.CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

?>
