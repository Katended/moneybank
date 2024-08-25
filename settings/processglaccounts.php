<?php
	require_once('../includes/application_top.php'); 
	require_once('settings.php'); 
	
	$setting = new Settings();
		
	$setting->configurationsettings = array('DEFAULT_LANGUAGE'=>tep_db_prepare_input($_POST['setting_default_language']),'SETTTING_INSTITUTION_NAME'=>tep_db_prepare_input($_POST['setting_institution_name']),'SETTTING_STUDENT_PHOTO_DIR_PATH'=>tep_db_prepare_input($_POST['setting_student_photo_dir_path']),'SETTTING_STAFF_PHOTO_DIR_PATH'=>tep_db_prepare_input($_POST['setting_staff_photo_dir_path']),'SETTTING_CURRENCY_CODE'=>tep_db_prepare_input($_POST['setting_currency_code']),'SETTTING_DATE_FORMAT'=>tep_db_prepare_input($_POST['setting_date_format']),'SETTTING_ROUND_TO'=>tep_db_prepare_input($_POST['setting_round_to']));	
	$setting->UpdateSettings();	
?>