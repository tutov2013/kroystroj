<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if($_REQUEST['map_id'] && check_bitrix_sessid()){
	global $DB;
	$DB->Query('DELETE FROM angerro_yadelivery WHERE id = "'.$DB->ForSql($_REQUEST['map_id']).'"');
	echo 'OK';
} else {
	echo 'ERROR';
}
?>