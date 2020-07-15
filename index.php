<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Купить всё для КРОВЛИ и ФАСАДА просто!");
$APPLICATION->SetTitle("Купить всё для КРОВЛИ и ФАСАДА просто!");
?><?$APPLICATION->IncludeComponent(
	"concept:phoenix.pages",
	".default",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "N",
		"FILE_404" => "",
		"IBLOCK_ID" => "39",
		"IBLOCK_TYPE" => "concept_phoenix_s2",
		"MESSAGE_404" => "",
		"SEF_FOLDER" => "/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array("main"=>"","page"=>"#SECTION_CODE_PATH#/",),
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y"
	)
);?><br>
 <br>
 <br>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>