<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/*********************************************************************************
 * $Id: preflight.php 58178 2010-09-15 00:30:38Z kjing $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

// LEGACY for old versions - emulating upload.php
// aw: make this better for later versions.
//refreshing mod_strings
global $mod_strings;

$curr_lang = 'en_us';
if(isset($GLOBALS['current_language']) && ($GLOBALS['current_language'] != null)){
    $curr_lang = $GLOBALS['current_language'];
}
$mod_strings = return_module_language($curr_lang, 'UpgradeWizard',true);

function check_php($sys_php_version = '')
{
    $min_considered_php_version = '5.2.1';

    $supported_php_versions = array (
    '5.2.1', '5.2.2', '5.2.3', '5.2.4', '5.2.5', '5.2.6', '5.2.8', '5.3.0'
    );
    //Find out what Database the system is using.
    global $sugar_config;
    $dbType = '';
    if (isset ($sugar_config['dbconfig']) && isset ($sugar_config['dbconfig']['db_type'])) {
        $dbType = $sugar_config['dbconfig']['db_type'];
    }

    // invalid versions above the $min_considered_php_version,
    // should be mutually exclusive with $supported_php_versions

    // SugarCRM prohibits install on PHP 5.2.x on all platforms
    $invalid_php_versions = array('5.2.7');

    // default unsupported
    $retval = 0;

    // versions below $min_considered_php_version are invalid
    if(1 == version_compare($sys_php_version, $min_considered_php_version, '<')) {
        $retval = -1;
    }

    // supported version check overrides default unsupported
    foreach($supported_php_versions as $ver) {
        if(1 == version_compare($sys_php_version, $ver, 'eq') || strpos($sys_php_version,$ver) !== false) {
            $retval = 1;
            break;
        }
    }

    // invalid version check overrides default unsupported
    foreach($invalid_php_versions as $ver) {
        if(1 == version_compare($sys_php_version, $ver, 'eq') || strpos($sys_php_version,$ver) !== false) {
            $retval = -1;
            break;
        }
    }

    //allow a redhat distro to install, regardless of version.  We are assuming the redhat naming convention is followed
    //and the php version contains 'rh' characters
    if(strpos($sys_php_version, 'rh') !== false) {
        $retval = 1;
    }

    return $retval;
}



$curr_lang = 'en_us';
if(isset($GLOBALS['current_language']) && ($GLOBALS['current_language'] != null)){
	$curr_lang = $GLOBALS['current_language'];
}
return_module_language($curr_lang, 'UpgradeWizard');

logThis('at preflight.php');
//set the upgrade progress status.
set_upgrade_progress('preflight','in_progress');
$php_warnings = '';
if (version_compare(phpversion(),'5.2.0') >=0) {
	$stop = true; // flag to show "next"
	if(isset($_SESSION['files'])) {
		unset($_SESSION['files']);
	}

	$errors = preflightCheck();

	$php_version = constant('PHP_VERSION');
    if(check_php($php_version) == -1)
    {
        $phpVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_PHP_INVALID_VER']} {$php_version} </span></b>";
        $error_txt = '<span class="error">'.$phpVersion.'</span>';
        if(count($errors) == 0)
        $errors[] = '';
        $errors[] = $error_txt;
        logThis($error_txt);
    }
	$diffs = '';
	$schema = '';
	$anyScriptChanges = '';
	$db =& DBManagerFactory::getInstance();

	if((count($errors) == 1)) { // only diffs
		logThis('file preflight check passed successfully.');
		$stop = false;
		$out  = $mod_strings['LBL_UW_PREFLIGHT_TESTS_PASSED']."<BR><BR><font color='red'>".$mod_strings['LBL_UW_PREFLIGHT_TESTS_PASSED3']."</font>";
		$stop = false;

		$disableEmail = (empty($current_user->email1)) ? 'DISABLED' : 'CHECKED';

		if(count($errors['manual']) > 0) {
			$preserveFiles = array();

		$diffs =<<<eoq
			<script type="text/javascript" language="Javascript">
				function preflightToggleAll(cb) {
					var checkAll = false;
					var form = document.getElementById('diffs');

					if(cb.checked == true) {
						checkAll = true;
					}

					for(i=0; i<form.elements.length; i++) {
						if(form.elements[i].type == 'checkbox') {
							form.elements[i].checked = checkAll;
						}
					}
					return;
				}
			</script>

			<table cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td valign='top'>
						<input type='checkbox' name='addTask' id='addTask' CHECKED>
					</td>
					<td valign='top'>
						{$mod_strings['LBL_UW_PREFLIGHT_ADD_TASK']}
					</td>
				</tr>
				<tr>
					<td valign='top'>
						<input type='checkbox' name='addEmail' id='addEmail' $disableEmail>
					</td>
					<td valign='top'>
						{$mod_strings['LBL_UW_PREFLIGHT_EMAIL_REMINDER']}
					</td>
				</tr>
			</table>

			<form name='diffs' id='diffs'>
			<p><a href='javascript:void(0); toggleNwFiles("diffsHide");'>{$mod_strings['LBL_UW_SHOW_DIFFS']}</a></p>
			<div id='diffsHide' style='display:none'>
				<table cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td valign='top' colspan='2'>
							{$mod_strings['LBL_UW_PREFLIGHT_FILES_DESC']}
							<br />&nbsp;
						</td>
					</tr>
					<tr>
						<td valign='top' colspan='2'>
							<input type='checkbox' onchange='preflightToggleAll(this);'>&nbsp;<i><b>{$mod_strings['LBL_UW_PREFLIGHT_TOGGLE_ALL']}</b></i>
							<br />&nbsp;
						</td>
					</tr>
eoq;
		foreach($errors['manual'] as $diff) {
			$diff = clean_path($diff);
			$_SESSION['files']['manual'][] = $diff;

			$checked = (isAutoOverwriteFile($diff)) ? 'CHECKED' : '';

			if(empty($checked)) {
				$preserveFiles[] = $diff;
			}

			$diffs .= "<tr><td valign='top'>";
			$diffs .= "<input type='checkbox' name='diff_files[]' value='{$diff}' $checked>";
			$diffs .= "</td><td valign='top'>";
			$diffs .= str_replace(getcwd(), '.', $diff);
			$diffs .= "</td></tr>";
		}
		$diffs .= "</table>";
		$diffs .= "</div></p>";
		$diffs .= "</form>";

		// list preserved files (templates, etc.)
		$preserve = '';
		foreach($preserveFiles as $pf) {
			if(empty($preserve)) {
				$preserve .= "<table cellpadding='0' cellspacing='0' border='0'><tr><td><b>";
				$preserve .= $mod_strings['LBL_UW_PREFLIGHT_PRESERVE_FILES'];
				$preserve .= "</b></td></tr>";
			}
			$preserve .= "<tr><td valign='top'><i>".str_replace(getcwd(), '.', $pf)."</i></td></tr>";
		}
		if(!empty($preserve)) {
			$preserve .= '</table><br>';
		}
		$diffs = $preserve.$diffs;
	} else { // NO FILE DIFFS REQUIRED
		$diffs = $mod_strings['LBL_UW_PREFLIGHT_NO_DIFFS'];
	}
} else {
	logThis('*** ERROR: found too many preflight errors - displaying errors and stopping execution.');
	$out = "<b>{$mod_strings['ERR_UW_PREFLIGHT_ERRORS']}:</b><hr />";
	$out .= "<span class='error'>";

	foreach($errors as $error) {
		if(is_array($error)) { // manual diff files
			continue;
		} else {
			$out .= "{$error}<br />";
		}
	}
	$out .= "</span><br />";
}

$diffs ='';

///////////////////////////////////////////////////////////////////////////////
////	SCHEMA SCRIPT HANDLING
	logThis('starting schema preflight check...');
	//Check the current and target versions and store them in session variables
    if (empty($sugar_db_version))
    {
        include('sugar_version.php');
    }
	if(!isset($manifest['version']) || empty($manifest['version'])) {
		include($_SESSION['unzip_dir'].'/manifest.php');
	}

    $origVersion = implodeVersion($sugar_db_version, 3, '0');
    $destVersion = implodeVersion($manifest['version'], 3, '0');

	//save the versions as session variables
    $_SESSION['current_db_version'] = $sugar_db_version;
    $_SESSION['target_db_version']  = $manifest['version'];
	$_SESSION['upgrade_from_flavor']  = $manifest['name'];
	// aw: BUG 10161: check flavor conversion sql files
	$sqlFile = ''; // cn: bug
    if (version_compare($sugar_db_version, $manifest['version'], '='))
    {
	    $type = $db->getScriptName();

        switch($manifest['name'])
        {
            case 'SugarCE to SugarPro':
                $sqlFile = $origVersion . '_ce_to_pro_' . $type;
                break;
            case 'SugarCE to SugarEnt':
                $sqlFile = $origVersion . '_ce_to_ent_' . $type;
                break;
            case 'SugarCE to SugarCorp':
                $sqlFile = $origVersion . '_ce_to_corp_' . $db->dbType;
                break;
            case 'SugarCE to SugarUlt':
                $sqlFile = $origVersion . '_ce_to_ult_' . $db->dbType;
                break;
            case 'SugarPro to SugarEnt':
                $sqlFile = $origVersion . '_pro_to_ent_' . $type;
                break;
            default:
                break;
        }
	} else {
	    $type = $db->dbType;
        if($type == 'oci8') $type = 'oracle';
        $sqlFile = $origVersion . '_to_' . $destVersion . '_' . $type;
	}

	$newTables = array();

    $sqlScript = $_SESSION['unzip_dir'].'/scripts/'.$sqlFile.'.sql';

	logThis('looking for schema script at: '.$sqlScript);
	if(is_file($sqlScript)) {
		logThis('found schema upgrade script: '.$sqlScript);

		logThis('schema preflight using MySQL');
		if(function_exists('sugar_fopen')){
			$fp = sugar_fopen($sqlScript, 'r');
		}
		else{
			$fp = fopen($sqlScript, 'r');
		}
		$contents = stream_get_contents($fp);
	    $anyScriptChanges =$contents;

		fclose($fp);

		$customTables = getCustomTables();
		if ( !empty($customTables) ) {
			$_SESSION['alterCustomTableQueries'] = alterCustomTables($customTables);
		} else {
			$_SESSION['alterCustomTableQueries'] = false;
		}

		$schema  = "<p><a href='javascript:void(0); toggleNwFiles(\"schemashow\");'>{$mod_strings['LBL_UW_SHOW_SCHEMA']}</a>";
		$schema .= "<div id='schemashow' style='display:none;'>";
		$schema .= "<textarea readonly cols='80' rows='10'>{$contents}</textarea>";
		$schema .= "</div></p>";

		if(!empty($sqlErrors)) {
			$stop = true;
			$out = "<b class='error'>{$mod_strings['ERR_UW_PREFLIGHT_ERRORS']}:</b> ";
			$out .= "<a href='javascript:void(0);toggleNwFiles(\"sqlErrors\");'>{$mod_strings['LBL_UW_SHOW_SQL_ERRORS']}</a><div id='sqlErrors' style='display:none'>";
			foreach($sqlErrors as $sqlError) {
				$out .= "<br><span class='error'>{$sqlError}</span>";
			}
			$out .= "</div><hr />";
		}
} else {
	$customTableSchema = '';
	logThis('no schema script found - all schema preflight skipped');
}
	logThis('schema preflight done.');
////	END SCHEMA SCRIPT HANDLING
///////////////////////////////////////////////////////////////////////////////
//php 521 suggestion
	$php_521 = '';
	if(version_compare(phpversion(),'5.2.1') < 0){
		$php_521=$mod_strings['LBL_CURRENT_PHP_VERSION'].phpversion().$mod_strings['LBL_RECOMMENDED_PHP_VERSION'];
	}
	if(empty($mod_strings['LBL_UPGRADE_TAKES_TIME_HAVE_PATIENCE'])){
		$mod_strings['LBL_UPGRADE_TAKES_TIME_HAVE_PATIENCE'] = 'Upgrade may take some time';
	}

$style_for_out = empty($out)?'style=\'display:none\'':'';
$style_for_dif = empty($diffs)?'style=\'display:none\'':'';
$style_for_schemaChange = empty($schema)?'style=\'display:none\'':'';

$final =<<<eoq
<table cellpadding="3" cellspacing="0" border="0">
    <tr {$style_for_out}>
        <td colspan="2" align="left" valign="top">
            <br>{$out}
        </td>
    </tr>
    <tr {$style_for_dif}>
        <td align="left" valign="top">
            <b>{$mod_strings['LBL_UW_MANUAL_MERGE']}</b>
        </td>
        <td align="left" valign="top">
            {$diffs}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr {$style_for_schemaChange}>
        <td align="left" valign="top">
            <b>{$mod_strings['LBL_UW_SCHEMA_CHANGE']}</b>
        </td>
        <td align="left" valign="top">
            {$schema}
        </td>
    </tr>
    <tr {$style_for_schemaChange}>
        <td>
        </td>
        <td valign="top">
            <div>
            <b>{$mod_strings['LBL_UW_DB_METHOD']}</b><br />
            <select name="schema_change" id="select_schema_change" onchange="checkSqlStatus(false);">
                <option value="sugar">{$mod_strings['LBL_UW_DB_CHOICE1']}</option>
                <option value="manual">{$mod_strings['LBL_UW_DB_CHOICE2']}</option>
            </select>
            </div>
            <div id='show_sql_run' style='display:none'>
                <input type='checkbox' name='sql_run' id='sql_run' onmousedown='checkSqlStatus(true);'> {$mod_strings['LBL_UW_SQL_RUN']}
            </div>
        </td>
    </tr>
</table>

eoq;

$form5 =<<<eoq5
<br>
<div id="upgradeDiv" style="display:none">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr><td>
           <p><!--not_in_theme!--><img src='modules/UpgradeWizard/processing.gif' alt='Processing'></p>
        </td></tr>
     </table>
 </div>

eoq5;

	$uwMain = $final.$form5;

	//set the upgrade progress status.
	set_upgrade_progress('preflight','done');
}
else{
	$stop = true;
	if(empty($mod_strings['LBL_INCOMPATIBLE_PHP_VERSION'])){
		$mod_strings['LBL_INCOMPATIBLE_PHP_VERSION'] = 'Php version 5.2.1 or above is required.';
	}

$php_verison_warning =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>{$mod_strings['LBL_INCOMPATIBLE_PHP_VERSION']}</b></span>
			</th>
		</tr>
	</table>
eoq;
	$php_warnings = $php_verison_warning;
}
//Add the backward compatibility check as well.

//Php Backward compatibility checks
if(ini_get("zend.ze1_compatibility_mode")) {
	$stop = true;
	if(empty($mod_strings['LBL_BACKWARD_COMPATIBILITY_ON'])){
		$mod_strings['LBL_BACKWARD_COMPATIBILITY_ON'] = 'Php Backward Compatibility mode is turned on. Set zend.ze1_compatibility_mode to Off for proceeding further';
	}

$php_compatibility_warning =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>{$mod_strings['LBL_BACKWARD_COMPATIBILITY_ON']}</b></span>
			</th>
		</tr>
	</table>
eoq;
$php_warnings .= $php_compatibility_warning;
}
if($php_warnings != null){
	$uwMain = $php_warnings;
}

$GLOBALS['top_message'] = "<b>{$mod_strings['LBL_UW_PREFLIGHT_TESTS_PASSED2']}</b>";
$showBack		= false;
$showCancel		= true;
$showRecheck	= true;
$showNext		= ($stop) ? false : true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';