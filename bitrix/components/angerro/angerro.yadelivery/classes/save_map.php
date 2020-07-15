<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if($_REQUEST['map_data'] && $_REQUEST['map_name'] && check_bitrix_sessid()){
	global $DB;
	if ($_REQUEST['map_id']){
		// перезапись существующей карты:
		$DB->Query('UPDATE angerro_yadelivery SET name = "'.$DB->ForSql($_REQUEST['map_name']).'", data = "' . $DB->ForSql($_REQUEST['map_data']) . '" WHERE id = "'.$_REQUEST['map_id'].'"');
	} else {
		// добавление новой карты зон доставки:
		$DB->Query('INSERT INTO angerro_yadelivery (name, data) values("'.$DB->ForSql($_REQUEST['map_name']).'", "' . $DB->ForSql($_REQUEST['map_data']) . '")');
	}
	echo 'OK';
} else {
	echo 'ERROR';
}
?>