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
			     Account WHERE Email = ? AND Deleted = 0");
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

	$pst = $db->prepare("SELECT SUM(ItemOrderCatalogItem.PointPrice) AS Total FROM ItemOrderCatalogItem WHERE ItemOrderCatalogItem.Cancelled = 0 AND ItemOrderCatalogItem.OrderID IN (SELECT OrderID FROM ItemOrder WHERE ItemOrder.DriverID = ?) AND ItemOrderCatalogItem.CatalogID IN (SELECT CatalogID FROM Catalog WHERE Catalog.CompanyID = ?)");
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
		$res->data_seek(0);
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
	
	if (ninja_check_email_taken($email))
		return false;
	
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
		$res->data_seek(0);
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
	if (!$uid)
		return false;

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
	if (!$uid)
		return false;

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
	$pst = $db->prepare("SELECT UserID, FName, LName, Email FROM Account INNER JOIN DriverCompany ON UserID = DriverID WHERE CompanyID = ? AND Accepted = 1 AND Account.Deleted = 0");
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
	$pst = $db->prepare("SELECT UserID, FName, LName, Email FROM Account INNER JOIN DriverCompany ON UserID = DriverID WHERE DriverCompany.Accepted = 0 AND DriverCompany.CompanyID = ? AND Account.Deleted = 0");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_point_alert($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT PointAlert FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['PointAlert'];
	}
}

function ninja_order_alert($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT OrderAlert FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['OrderAlert'];
	}
}

function ninja_change_alert($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT ChangeAlert FROM Driver WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		return $row['ChangeAlert'];
	}
}

function ninja_update_alerts($uid, $point, $order, $change)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Driver SET PointAlert = ?, OrderAlert = ?, ChangeAlert = ? WHERE UserID = ?");
	$pst->bind_param("iiis", $point, $order, $change, $uid);
	$pst->execute();
}

function ninja_add_points($did, $sid, $points)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT AdditionID FROM PointAddition");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		$res->data_seek(0);
		while ($row = $res->fetch_assoc())
			if ($row['AdditionID'] === $newid)
				$unique = false;
	}
	$pst = $db->prepare("INSERT INTO PointAddition VALUES (?, ?, NOW(), ?, ?)");
	$pst->bind_param("siss", $newid, $points, $sid, $did);
	$pst->execute();
}

function ninja_company_sponsor_info($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT SponsorInfo FROM Company WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$info = "";
	if ($row = $res->fetch_assoc())
	{
		$info = $row['SponsorInfo'];
	}
	return $info;
}

function ninja_company_driver_ad($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT DriverAd FROM Company WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$info = "";
	if ($row = $res->fetch_assoc())
	{
		$info = $row['DriverAd'];
	}
	return $info;
}

function ninja_update_company($cid, $name, $sponsorinfo, $driverad)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Company SET Name = ?, SponsorInfo = ?, DriverAd = ? WHERE CompanyID = ?");
	$pst->bind_param("ssss", $name, $sponsorinfo, $driverad, $cid);
	$pst->execute();
}

function ninja_update_pfp($uid, $image)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Account SET Image = ? WHERE UserID = ?");
	$pst->bind_param("ss", $image, $uid);
	if($pst->execute())
	{
        	return "The file has been uploaded successfully.";
	}
	else
	{
        	return "File upload failed, please try again.";
        } 
}

function ninja_update_company_image($cid, $image)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Company SET Image = '$image' WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	if($pst->execute())
	{
        	return "The file has been uploaded successfully.";
	}
	else
	{
        	return "File upload failed, please try again.";
        } 
}

function ninja_point_gains($did, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Amount, Timestamp FROM PointAddition INNER JOIN Sponsor ON PointAddition.SponsorID = Sponsor.UserID WHERE PointAddition.DriverID = ? AND Sponsor.CompanyID = ? ORDER BY Timestamp DESC");
	$pst->bind_param("ss", $did, $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_orders($did, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT ItemOrderCatalogItem.PointPrice AS Price, CatalogCatalogItem.Image AS Image, CatalogCatalogItem.Name AS Name, CatalogItem.WebSource AS WebSource, CatalogItem.LinkInfo AS LinkInfo, ItemOrderCatalogItem.OrderID AS OrderID, ItemOrder.Timestamp AS Timestamp, CatalogCatalogItem.CustomImg AS CustomImg, CatalogCatalogItem.CustomDesc AS CustomDesc FROM (((ItemOrderCatalogItem INNER JOIN ItemOrder ON ItemOrderCatalogItem.OrderID = ItemOrder.OrderID) INNER JOIN CatalogItem ON CatalogItem.ItemID = ItemOrderCatalogItem.ItemID) INNER JOIN CatalogCatalogItem ON ItemOrderCatalogItem.ItemID = CatalogCatalogItem.ItemID AND ItemOrderCatalogItem.CatalogID = CatalogCatalogItem.CatalogID) INNER JOIN Catalog ON CatalogCatalogItem.CatalogID = Catalog.CatalogID WHERE ItemOrder.DriverID = ? AND Catalog.CompanyID = ? ORDER BY ItemOrder.Timestamp");
	$pst->bind_param("ss", $did, $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_catalog_items($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT CatalogCatalogItem.Name AS Name, CatalogCatalogItem.PointPrice AS Price, CatalogCatalogItem.Image AS Image, CatalogCatalogItem.Description AS Description, CatalogCatalogItem.ItemID AS ItemID, CatalogCatalogItem.CatalogID AS CatalogID, CatalogItem.WebSource AS WebSource, CatalogItem.LinkInfo AS LinkInfo, CatalogCatalogItem.CustomImg AS CustomImg, CatalogCatalogItem.CustomDesc AS CustomDesc FROM CatalogCatalogItem INNER JOIN CatalogItem ON CatalogItem.ItemID = CatalogCatalogItem.ItemID WHERE CatalogCatalogItem.CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_add_base_item($source, $link)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT ItemID FROM CatalogItem");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		$res->data_seek(0);
		while ($row = $res->fetch_assoc())
			if ($row['ItemID'] === $newid)
				$unique = false;
	}

	// insert item
	$pst = $db->prepare("INSERT INTO CatalogItem VALUES (?, ?, ?)");
	$pst->bind_param("sss", $newid, $source, $link);
	$pst->execute();

	return $newid;
}

function ninja_new_catalog($cid, $name)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT CatalogID FROM Catalog");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		$res->data_seek(0);
		while ($row = $res->fetch_assoc())
			if ($row['CatalogID'] === $newid)
				$unique = false;
	}

	// insert catalog
	$pst = $db->prepare("INSERT INTO Catalog VALUES(?, ?, 0, ?)");
	$pst->bind_param("sss", $newid, $name, $cid);
	$pst->execute();

	return $newid;
}

function ninja_browse_catalog_items($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Name, Image, PointPrice AS Price, CustomImg, CatalogCatalogItem.ItemID AS ItemID, CatalogCatalogItem.CatalogID AS CatalogID, WebSource, LinkInfo FROM CatalogCatalogItem INNER JOIN CatalogItem ON CatalogCatalogItem.ItemID = CatalogItem.ItemID WHERE CatalogCatalogItem.CatalogID IN (SELECT CatalogID FROM Catalog WHERE Catalog.CompanyID = ? AND Catalog.Deleted = 0 AND Catalog.Visible = 1)");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_place_order($uid, $items)
{
	$db = dojo_connect();
	
	// generate new id
	$pst = $db->prepare("SELECT OrderID FROM ItemOrder");
	$pst->execute();
	$res = $pst->get_result();
	$newid = "";
	$unique = false;
	while (!$unique)
	{
		$newid = substr(md5(rand()), 0, 16);
		$unique = true;
		$res->data_seek(0);
		while ($row = $res->fetch_assoc())
			if ($row['OrderID'] === $newid)
				$unique = false;
	}


	// create new order
	$pst = $db->prepare("INSERT INTO ItemOrder VALUES(?, NOW(), 0, ?)");
	$pst->bind_param("ss", $newid, $uid); 
	$pst->execute();

	// add items to order
	$position = 1;
	foreach ($items as $item)
	{
		$pst = $db->prepare("INSERT INTO ItemOrderCatalogItem VALUES(?, ?, ?, ?, 0, 0, ?, 1.1)");  // TODO 
		$iid = $item['ItemID'];
		$price = $item['Price'];
		$cid = $item['CatalogID'];
		$pst->bind_param("ssiis", $newid, $iid, $price, $position, $cid);
		$pst->execute();
		$position++;
	}

	return $newid;
}

function ninja_item_price($iid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT PointPrice FROM CatalogCatalogItem WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$price = 0;
	if ($row = $res->fetch_assoc())
	{
		$price = $row['PointPrice'];
	}
	return $price;
}

function ninja_item_name($iid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Name FROM CatalogCatalogItem WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$name = "";
	if ($row = $res->fetch_assoc())
	{
		$name = $row['Name'];
	}
	return $name;
}

function ninja_item_description($iid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Description FROM CatalogCatalogItem WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$desc = "";
	if ($row = $res->fetch_assoc())
	{
		$desc = $row['Description'];
	}
	return $desc;
}

function ninja_item_image($iid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Image FROM CatalogCatalogItem WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	$pst->execute();
	$res = $pst->get_result();
	$image = '';
	if ($row = $res->fetch_assoc())
	{
		$image = $row['Image'];
	}
	return $image;
}

function ninja_catalogs($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Name, CatalogID FROM Catalog WHERE CompanyID = ? AND Deleted = 0");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_catalog_item_count($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT COUNT(*) AS Items FROM CatalogCatalogItem WHERE CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$items = 0;
	if ($row = $res->fetch_assoc())
	{
		$items = $row['Items'];
	}
	return $items;
}

function ninja_catalog_name($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Name FROM Catalog WHERE CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$name = "";
	if ($row = $res->fetch_assoc())
	{
		$name = $row['Name'];
	}
	return $name;
}

function ninja_update_catalog_item($iid, $cid, $name, $price, $desc)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE CatalogCatalogItem SET Name = ?, PointPrice = ?, Description = ? WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("sisss", $name, $price, $desc, $iid, $cid);
	$pst->execute();
}

function ninja_update_catalog_item_image($iid, $cid, $image)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE CatalogCatalogItem SET Image = '$image' WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	if($pst->execute())
	{
        	return "The file has been uploaded successfully.";
	}
	else
	{
        	return "File upload failed, please try again.";
        } 
}

function ninja_remove_catalog_item($iid, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("DELETE FROM CatalogCatalogItem WHERE ItemID = ? AND CatalogID = ?");
	$pst->bind_param("ss", $iid, $cid);
	$pst->execute();
}

function ninja_catalog_company_id($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT CompanyID FROM Catalog WHERE CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$id = "";
	if ($row = $res->fetch_assoc())
	{
		$id = $row['CompanyID'];
	}
	return $id;
}

function ninja_update_catalog_name($cid, $name)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Catalog SET Name = ? WHERE CatalogID = ?");
	$pst->bind_param("ss", $name, $cid);
	$pst->execute();
}

function ninja_browse_base_items($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT ItemID, WebSource, LinkInfo FROM CatalogItem WHERE ItemID NOT IN (SELECT ItemID FROM CatalogCatalogItem WHERE CatalogID = ?)");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_add_catalog_item($iid, $cid, $name, $price, $desc, $image)
{
	$db = dojo_connect();
	$pst = $db->prepare("INSERT INTO CatalogCatalogItem VALUES(?, ?, ?, ?, 0.0, 1, ?, 1, '$image')");
	$pst->bind_param("sssis", $cid, $iid, $name, $price, $desc);
	$pst->execute();
}

function ninja_set_driver_current_company($did, $cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Driver SET CurrComp = ? WHERE UserID = ?");
	$pst->bind_param("ss", $cid, $did);
	$pst->execute();
}

function ninja_company_points_added($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT SUM(PointAddition.Amount) AS Points FROM PointAddition INNER JOIN Sponsor ON PointAddition.SponsorID = Sponsor.UserID WHERE Sponsor.CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$points = 0;
	if ($row = $res->fetch_assoc())
	{
		$points = $row['Points'];
	}
	return $points;
}

function ninja_company_points_spent($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT SUM(ItemOrderCatalogItem.PointPrice) AS Points FROM ItemOrderCatalogItem INNER JOIN Catalog ON ItemOrderCatalogItem.CatalogID = Catalog.CatalogID WHERE Catalog.CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	$points = 0;
	if ($row = $res->fetch_assoc())
	{
		$points = $row['Points'];
	}
	return $points;
}

function ninja_company_points_held($cid)
{
	$added = ninja_company_points_added($cid);
	$spent = ninja_company_points_spent($cid);
	$held = $added - $spent;
	return $held;
}

function ninja_delete_account($uid)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Account SET Deleted = 1 WHERE UserID = ?");
	$pst->bind_param("s", $uid);
	$pst->execute();
}

function ninja_delete_company($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Company SET Deleted = 1 WHERE CompanyID = ?");
	$pst->bind_param("s", $cid);
	$pst2 = $db->prepare("UPDATE Account SET Deleted = 1 WHERE UserID IN (SELECT UserID FROM Sponsor WHERE CompanyID = ?)");
	$pst2->bind_param("s", $cid);
	$pst2->execute();
	$pst->execute();
}

function ninja_sponsor_company_list($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Account.UserID AS UserID, FName, LName, Email FROM Account INNER JOIN Sponsor ON Account.UserID = Sponsor.UserID WHERE Sponsor.CompanyID = ? AND Account.Deleted = 0");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();
	return $res;
}

function ninja_delete_catalog($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Catalog SET Deleted = 1 WHERE CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
}

function ninja_catalog_visible($cid)
{
	$db = dojo_connect();
	$pst = $db->prepare("SELECT Visible FROM Catalog WHERE CatalogID = ?");
	$pst->bind_param("s", $cid);
	$pst->execute();
	$res = $pst->get_result();	
	$visible = false;
	if ($row = $res->fetch_assoc())
	{
		$visible = $row['Visible'];
	}
	return $visible;
}

function ninja_set_catalog_visible($cid, $visible)
{
	$db = dojo_connect();
	$pst = $db->prepare("UPDATE Catalog SET Visible = ? WHERE CatalogID = ?");
	$pst->bind_param("is", $visible, $cid);
	$pst->execute();
}

?>
