<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/angerro/angerro.yadelivery/classes/main.php");
$db = new angerro_yadelivery_db();
?>
<?=$db->get_data_by_map_id($_REQUEST['map_id'])?>