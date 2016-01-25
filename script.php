<?php
// These four parameters must be changed dependent on your MySQL settings
$hostdb = 'localhost';   // MySQl host
$userdb = 'root';    // MySQL username
$passdb = '';    // MySQL password
$namedb = 'rtqii'; // MySQL database name

$link = mysql_connect ($hostdb, $userdb, $passdb);
	
if (!$link) {
	// we should have connected, but if any of the above parameters
	// are incorrect or we can't access the DB for some reason,
	// then we will stop execution here
	die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db($namedb);
if (!$db_selected) {
	die ('Can\'t use database : ' . mysql_error());
}
else
{
	$sql_surveys = "SELECT * FROM surveys WHERE deleted_at IS NULL;";
	$sql_result_surveys = mysql_query($sql_surveys) or die(mysql_error());
	while($srvy = mysql_fetch_assoc($sql_result_surveys))
	{		
		$data_month = 'NULL';
		$deleted_at = 'NULL';
		$longitude = 'NULL';
		$latitude = 'NULL';
		$details = 'NULL';
		if(isset($srvy['data_month']))
		{
			$data_month = "'".$srvy['data_month']."'";
		}
		if(isset($srvy['deleted_at']))
		{
			$deleted_at = $srvy['deleted_at'];
		}
		if(isset($srvy['longitude']))
		{
			$longitude = $srvy['longitude'];
		}
		if(isset($srvy['latitude']))
		{
			$latitude = $srvy['latitude'];
		}
		if(isset($srvy['comment']))
		{
			$details = '"'.$srvy['comment'].'"';
		}
		$sql_ssdps = "SELECT * FROM survey_sdps where survey_id=".$srvy['id']." AND deleted_at IS NULL;";
		$sql_result_ssdps = mysql_query($sql_ssdps) or die(mysql_error());
		if(mysql_num_rows($sql_result_ssdps)>1)
		{
			while($ssdp = mysql_fetch_assoc($sql_result_ssdps))
			{
				$tier = NULL;
				$facility_sdp_id = NULL;
				if(isset($ssdp['comment']))
				{
					$sql_tiers = "SELECT name FROM tiers WHERE name LIKE"." '%".$ssdp['comment']."%';";
					$sql_result_tiers = mysql_query($sql_tiers) or die(mysql_error());
					if(count(mysql_fetch_assoc($sql_result_tiers))==1)
					{
						$sql_try = "SELECT id FROM tiers WHERE name='".$ssdp['comment']."';";
						$sql_result_try = mysql_query($sql_try) or die(mysql_error());
						$tier = mysql_fetch_array($sql_result_try)[0];
					}
				}
				if(!empty($tier))
				{
					$sql_fsi = "SELECT id FROM facility_sdps WHERE facility_id=".$srvy['facility_id']." AND sdp_id=".$ssdp['sdp_id']." AND sdp_tier_id=".$tier.";";
				}
				else
				{
					$sql_fsi = "SELECT id FROM facility_sdps WHERE facility_id=".$srvy['facility_id']." AND sdp_id=".$ssdp['sdp_id']." AND sdp_tier_id IS NULL;";
				}
				$sql_fsi_results = mysql_query($sql_fsi) or die(mysql_error($sql_fsi));
				$facility_sdp_id = mysql_fetch_array($sql_fsi_results)[0];
				if(empty($facility_sdp_id))
					die(var_dump($ssdp));
				$sql_duplicate = "INSERT INTO surveys (qa_officer, facility_id, facility_sdp_id, survey_sdp_id, longitude, latitude, checklist_id, comment, date_started, date_ended, date_submitted, data_month, deleted_at, created_at, updated_at) VALUES ('".$srvy['qa_officer']."',".$srvy['facility_id'].",".$facility_sdp_id.",".$ssdp['id'].",".$longitude.",".$latitude.",".$srvy['checklist_id'].",".$details.",'".$srvy['date_started']."','".$srvy['date_ended']."','".$srvy['date_submitted']."',".$data_month.",".$deleted_at.",'".$srvy['created_at']."','".$srvy['updated_at']."');";
				//die($sql_duplicate);
				$sql_duplicate_results = mysql_query($sql_duplicate) or die($sql_duplicate);
			}
		}
		else
		{
			while($ssdp = mysql_fetch_assoc($sql_result_ssdps))
			{
				$tier = NULL;
				$facility_sdp_id = NULL;
				$sql_fsi = '';
				if(isset($ssdp['comment']))
				{
					$sql_tiers = "SELECT name FROM tiers WHERE name LIKE"." '%".$ssdp['comment']."%';";
					$sql_result_tiers = mysql_query($sql_tiers) or die(mysql_error());
					if(count(mysql_fetch_assoc($sql_result_tiers))==1)
					{
						$sql_try = "SELECT id FROM tiers WHERE name='".$ssdp['comment']."';";
						$sql_result_try = mysql_query($sql_try) or die(mysql_error());
						$tier = mysql_fetch_array($sql_result_try)[0];
					}
				}
				if(!empty($tier))
				{
					$sql_fsi = "SELECT id FROM facility_sdps WHERE facility_id=".$srvy['facility_id']." AND sdp_id=".$ssdp['sdp_id']." AND sdp_tier_id=".$tier.";";
				}
				else
				{
					$sql_fsi = "SELECT id FROM facility_sdps WHERE facility_id=".$srvy['facility_id']." AND sdp_id=".$ssdp['sdp_id']." AND sdp_tier_id IS NULL;";
				}
				$sql_fsi_results = mysql_query($sql_fsi) or die(mysql_error($sql_fsi));
				$facility_sdp_id = mysql_fetch_array($sql_fsi_results)[0];
				if(empty($facility_sdp_id))
					die(var_dump($ssdp));
				$sql_duplicate = "UPDATE surveys SET facility_sdp_id =".$facility_sdp_id.", survey_sdp_id=".$ssdp['id']." WHERE id=".$srvy['id'].";";
				$sql_duplicate_results = mysql_query($sql_duplicate) or die(mysql_error());
			}
		}
	}
}
?>