<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


<?if($arResult["LANDING"]["ID"] > 0):?>

    <?
    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "GLOBAL_ACTIVE"=>"Y", "ACTIVE"=>"Y", 'ID' => $arResult["LANDING"]["ID"]);
    $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("ID", "UF_PHX_LINK_OBJ", "UF_PHX_MAIN_PAGE"));

    $ar_result = $db_list->GetNext();


    if($ar_result["UF_PHX_LINK_OBJ"] || $ar_result["UF_PHX_MAIN_PAGE"])
         LocalRedirect(SITE_DIR, true, "301 Moved permanently");
    ?>



    <?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $APPLICATION->IncludeComponent(
    	"concept:phoenix.one.page", 
    	"", 
    	array(
    		"CACHE_TIME" => $arParams["CACHE_TIME"],
    		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
    		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
    		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    		"PARENT_SECTION" => $ar_result["ID"],
    		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
    		"MESSAGE_404" => $arParams["MESSAGE_404"],
    		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
    		"SHOW_404" => $arParams["SHOW_404"],
    		"FILE_404" => $arParams["FILE_404"],
    		"COMPONENT_TEMPLATE" => "",
            "COMPOSITE_FRAME_MODE" => "N",
    	),
    	$component
    );?>
    
    
<!-- dlya nastroek saita -->

    <?$GLOBALS["PHOENIX_IBLOCK_ID"] = $arParams["IBLOCK_ID"];?>
    <?$GLOBALS["PHOENIX_IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];?>

<?endif;?>