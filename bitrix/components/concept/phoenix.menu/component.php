<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;
global $APPLICATION;
global $PHOENIX_TEMPLATE_ARRAY;



use Bitrix\Iblock;

CModule::IncludeModule("iblock");

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;


$cur_page = $_SERVER["REQUEST_URI"];
$cur_page_no_index = $APPLICATION->GetCurPage(false);


$maxLevel = 3;

$arLinks = Array();

$arLinks["catalog"] = "#SITE_DIR#catalog/";
$arLinks["blog"] = "#SITE_DIR#blog/";
$arLinks["news"] = "#SITE_DIR#news/";
$arLinks["action"] = "#SITE_DIR#offers/";
$arLinks["brands"] = "#SITE_DIR#brands/";

$arCodes = Array();

$arCodes["catalog"] = "concept_phoenix_catalog";
$arCodes["blog"] = "concept_phoenix_blog";
$arCodes["news"] = "concept_phoenix_news";
$arCodes["action"] = "concept_phoenix_action";
$arCodes["brands"] = "concept_phoenix_brand";

$arrUrls = array();
$arSections = array();

$iblockID = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_ID"];


if($arParams["COMPONENT_TEMPLATE"] == "footer")
    $iblockID = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_MENU"]["IBLOCK_ID"];




if($this->startResultCache($arParams["CACHE_TIME"], array($cur_page)))
{


    $arLands = Array();

    $code = 'concept_phoenix_site_'.SITE_ID;
    $arFilter1 = Array('IBLOCK_CODE' => $code, "ACTIVE"=>"");
    $dbResSect1 = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter1, false, Array("ID", "SECTION_PAGE_URL"));

    while($sectRes1 = $dbResSect1->GetNext())
        $arLands[$sectRes1["ID"]] = $sectRes1["SECTION_PAGE_URL"];


    $arCols = Array();

    $rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$iblockID."_SECTION", "FIELD_NAME" => "UF_PHX_MENU_COL"));
    if($arRes = $rsData->Fetch())
    {

        $dbRes = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" =>$arRes["ID"]));

        while($arRes = $dbRes->GetNext())
            $arCols[$arRes["ID"]] = $arRes;
            
    }

    $arTypes = Array();

    $rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$iblockID."_SECTION", "FIELD_NAME" => "UF_PHX_MENU_TYPE"));
    if($arRes = $rsData->Fetch())
    {

        $dbRes = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" =>$arRes["ID"]));

        while($arRes = $dbRes->GetNext())
            $arTypes[$arRes["ID"]] = $arRes;
            
    }


    if($arParams["COMPONENT_TEMPLATE"] == "open_menu" || $arParams["COMPONENT_TEMPLATE"] == ".default")
    {


        $arViews = Array();

        $rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_ID"]."_SECTION", "FIELD_NAME" => "UF_MENU_VIEW"));
        if($arRes = $rsData->Fetch())
        {

            $dbRes = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arRes["ID"]));

            while($arRes = $dbRes->GetNext())
                $arViews["ITEMS"][$arRes["ID"]] = $arRes; 
        }

        $firstItem = reset($arViews["ITEMS"]);

        $arViews["DEFAULT"] = $firstItem["XML_ID"];
        unset($firstItem);
    }


    
    
    $arResult = array();

    $arFilter = Array('IBLOCK_ID' => $iblockID, "GLOBAL_ACTIVE"=>"Y", "IBLOCK_ACTIVE"=>"Y", "ACTIVE"=>"Y", "<="."DEPTH_LEVEL" => $maxLevel);
    $dbResSect = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, true, array("ID", "DEPTH_LEVEL", "NAME", "SECTION_PAGE_URL", "UF_*"));

    $selected = 0;

    while($sectRes = $dbResSect->GetNext())
    {
        if(strlen($sectRes["UF_PHX_MENU_COL"])>0)
            $sectRes["UF_PHX_MENU_COL_VAL"] = $arCols[$sectRes["UF_PHX_MENU_COL"]];
        
        if(strlen($sectRes["UF_PHX_MENU_TYPE"])>0)
            $sectRes["UF_PHX_MENU_TYPE_VAL"] = $arTypes[$sectRes["UF_PHX_MENU_TYPE"]];

            
        $menuElement = Array();
        
        $menuElement["ID"] = $sectRes["ID"]; 
        $menuElement["NAME"] = strip_tags(html_entity_decode($sectRes["NAME"]));
        $menuElement["LINK"] = $sectRes["UF_PHX_MENU_LINK"]; 
        $menuElement["DEPTH_LEVEL"] = intval($sectRes["DEPTH_LEVEL"]);
        $menuElement["TYPE"] = $sectRes["UF_PHX_MENU_TYPE_VAL"]['XML_ID'];
        $menuElement["ACCENTED"] = $sectRes["UF_ACCENTED"];
           
                    
        $menuElement["MENU_COLOR"] = $sectRes["~UF_PHX_MENU_COLOR"];    
        $menuElement["MENU_ICON"] = $sectRes["~UF_PHX_MENU_ICON"]; 
        $menuElement["MENU_IC_US"] = $sectRes["~UF_PHX_MENU_IC_US"];


        $hide_menu = false;
        
        if(isset($arViews))
        {
            $menuElement["VIEW"] = (isset($arViews["ITEMS"][$sectRes["UF_MENU_VIEW"]]["XML_ID"]{0}))? $arViews["ITEMS"][$sectRes["UF_MENU_VIEW"]]["XML_ID"] : $arViews["DEFAULT"];


            $menuElement["MAX_QUANTITY_SECTION_SHOW"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["OTHER_COUNT_SHOW"]["VALUE"];

            if($menuElement["TYPE"] == 'catalog')
            {
                $menuElement["MAX_QUANTITY_SECTION_SHOW"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["CATALOG_COUNT_SHOW"]["VALUE"];

                $hide_menu = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"] == "Y") ? true : false;


                $menuElement["COL"] = $sectRes["UF_PHX_MENU_COL_VAL"]["XML_ID"];
                
                if($menuElement["VIEW"] == "view_2")
                    $menuElement["COL"] = "catalog";
            }
            else
            {
                $menuElement["COL"] = $sectRes["UF_PHX_MENU_COL_VAL"]["XML_ID"];
            }

        }



        if($sectRes["UF_MENU_PICT"])
        {
            $img = CFile::ResizeImageGet($sectRes["UF_MENU_PICT"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);
            $menuElement["PICTURE_SRC"] = $img["src"];
        }




        
        if($sectRes["UF_PHX_M_BLANK"])
            $menuElement["BLANK"] = true;
          
        
        if($menuElement["TYPE"] == 'none')
            $menuElement["NONE"] = true;          
        
        
        if($menuElement["TYPE"] == 'action' || $menuElement["TYPE"] == 'brands')
            $menuElement["LINK"] = $arLinks[$menuElement["TYPE"]];
        
                    
        if($menuElement["TYPE"] == 'land')
        {
          
            if($sectRes['UF_PHX_LAND'] > 0)
                $menuElement["LINK"] = $arLands[$sectRes['UF_PHX_LAND']];        
            

        }
        else if($menuElement["TYPE"] == 'form' || $menuElement["TYPE"] == 'modal' || $menuElement["TYPE"] == 'quiz')
        {
            $menuElement['NOLINK'] = true;
            $menuElement["NONE"] = true;
            
            
            $menuElement["FORM"] = $sectRes["UF_PHX_M_FORM"]; 
            $menuElement["MODAL"] = $sectRes["UF_PHX_M_MODAL"]; 
            $menuElement["QUIZ"] = $sectRes["UF_PHX_M_QUIZ"];

            if( ($menuElement["TYPE"] == 'form' && strlen($sectRes["UF_PHX_M_FORM"])>0) || ($menuElement["TYPE"] == 'modal' && strlen($sectRes["UF_PHX_M_MODAL"])>0) || ($menuElement["TYPE"] == 'quiz' && strlen($sectRes["UF_PHX_M_QUIZ"])>0) )
                $menuElement["NONE"] = false;
            
        }
        
        if(strlen($menuElement["LINK"]) > 0)
            $menuElement["LINK"] = str_replace("#SITE_DIR#", SITE_DIR, $menuElement["LINK"]);
        
        if(strlen($menuElement["LINK"]) > 0)
        {
            $selected = CMenu::IsItemSelected($menuElement["LINK"], $cur_page, $cur_page_no_index);

            if(strlen($menuElement["LINK"]) > 0)
            {
                if($selected > 0 && !empty($arrUrls) && in_array($menuElement["LINK"], $arrUrls))
                    $selected = 0;
                
                if($selected > 0)
                    $arrUrls[] = $menuElement["LINK"];
            }

            $menuElement['SELECTED'] = $selected;
        }
        
        
        
        $arSections["land_".$menuElement["ID"]] = $menuElement;
        

        $lvl = $maxLevel - intval($menuElement["DEPTH_LEVEL"]);
        $step = intval($menuElement["DEPTH_LEVEL"]);        


        if($menuElement["TYPE"] == 'catalog' || $menuElement["TYPE"] == 'blog' || $menuElement["TYPE"] == 'news')
        {
            
            $arSections["land_".$menuElement["ID"]]["LINK"] = $arLinks[$menuElement["TYPE"]];
            
            
            if(strlen($arSections["land_".$menuElement["ID"]]["LINK"]) > 0)
                $arSections["land_".$menuElement["ID"]]["LINK"] = str_replace("#SITE_DIR#", SITE_DIR, $arSections["land_".$menuElement["ID"]]["LINK"]);
            
            if(strlen($arSections["land_".$menuElement["ID"]]["LINK"]) > 0)
            {
                $selected = CMenu::IsItemSelected($arSections["land_".$menuElement["ID"]]["LINK"], $cur_page, $cur_page_no_index);
                

                if(strlen($arSections["land_".$menuElement["ID"]]["LINK"]) > 0)
                {

                    if($selected > 0 && !empty($arrUrls) && in_array($arSections["land_".$menuElement["ID"]]["LINK"], $arrUrls))
                        $selected = 0;
                    
                    if($selected > 0)
                        $arrUrls[] = $arSections["land_".$menuElement["ID"]]["LINK"];

                }


                $arSections["land_".$menuElement["ID"]]['SELECTED'] = $selected;
            }

            $code = $arCodes[$menuElement["TYPE"]]."_".SITE_ID;

            $cnt = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] == "Y" && !$hide_menu)?false:true;
            
            
            $iblock = CIBlock::GetList(Array(), Array("CODE"=>$code), true);
            $arIBlock = $iblock->Fetch();
            
            $arSelect = Array("ID", "NAME", "SECTION_PAGE_URL", "DEPTH_LEVEL", "ELEMENT_CNT", "DETAIL_PICTURE", "UF_*");
            $arFilter1 = Array('IBLOCK_ID' => $arIBlock["ID"], "GLOBAL_ACTIVE"=>"Y", "IBLOCK_ACTIVE"=>"Y", "ACTIVE"=>"Y", "<="."DEPTH_LEVEL" => $lvl, "CNT_ACTIVE" => "Y");
            $dbResSect1 = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter1, $cnt, $arSelect);

            while($sectRes1 = $dbResSect1->GetNext())
            {

                $menuElement = Array();

                if(trim($sectRes1["UF_PHX_MAIN_MENU"]) == "")
                    $sectRes1["UF_PHX_MAIN_MENU"] = 1;
                
                
                $menuElement["ID"] = $sectRes1["ID"]; 
                $menuElement["NAME"] = strip_tags(html_entity_decode($sectRes1["NAME"]));
                $menuElement["LINK"] = $sectRes1["SECTION_PAGE_URL"]; 
                $menuElement["DEPTH_LEVEL"] = intval($sectRes1["DEPTH_LEVEL"]) + $step;
                $menuElement["SHOW"] = $sectRes1["UF_PHX_MAIN_MENU"]; 
                $menuElement["ADD"] = true;
                $menuElement["ELEMENT_CNT"] = $sectRes1["ELEMENT_CNT"];

                if($hide_menu && $menuElement["ELEMENT_CNT"] <= 0)
                    continue;

                /*$menuElement["PICTURE"] = $sectRes1["UF_PHX_MENU_PICT"];   */ 

                if($sectRes1["UF_PHX_MENU_PICT"])
                {
                    $img = CFile::ResizeImageGet($sectRes1["UF_PHX_MENU_PICT"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);
                    $menuElement["PICTURE_SRC"] = $img["src"];
                }      


                if(strlen($menuElement["LINK"]) > 0)
                {
                    $selected = CMenu::IsItemSelected($menuElement["LINK"], $cur_page, $cur_page_no_index);
                    

                    if(strlen($menuElement["LINK"]) > 0)
                    {
                        if($selected > 0 && !empty($arrUrls) && in_array($menuElement["LINK"], $arrUrls))
                            $selected = 0;

                        if($selected > 0)
                            $arrUrls[] = $menuElement["LINK"];
                    }



                    $menuElement['SELECTED'] = $selected;
                }

                    
                $arSections[] = $menuElement;



            }

        }


    }

    if(!empty($arSections))
    {
        foreach($arSections as $key=>$arItem)
        {
            if($arItem["DEPTH_LEVEL"] == 1)
            {
                $main_key = $key;
                $arResult[$main_key] = $arItem;
            }

            
            if($arItem["DEPTH_LEVEL"] == 2)
            {
                $main_key1 = $key;
                
                $arResult[$main_key]["SUB"][$main_key1] = $arItem;
                $arResult[$main_key]["LEVEL"] = 2;
                unset($arResult[$main_key1]);
                
                if($arItem["SELECTED"])
                    $arResult[$main_key]["SELECTED"] = true;
            }
            
            if($arItem["DEPTH_LEVEL"] == 3)
            {
                $main_key2 = $key;
                
                $arResult[$main_key]["SUB"][$main_key1]["SUB"][$main_key2] = $arItem;
                $arResult[$main_key]["LEVEL"] = 3;
                unset($arResult[$main_key2]);
                
                if($arItem["SELECTED"])
                {
                    $arResult[$main_key]["SELECTED"] = true;
                    $arResult[$main_key]["SUB"][$main_key1]["SELECTED"] = true;
                }
                    
            }
            
        }
    }
    
    
    if(!empty($arResult))
    {

        foreach($arResult as $key=>$arItem)
        {
            if($arItem["ADD"] && !$arItem["SHOW"])
            {
                unset($arResult[$key]);
            }  
            else  
            {
                if(!empty($arItem["SUB"]) && is_array($arItem["SUB"]))
                {
                    foreach($arItem["SUB"] as $key1=>$arSub)
                    {
                        if($arSub["ADD"] && !$arSub["SHOW"])
                        {
                            unset($arResult[$key]["SUB"][$key1]);
                        }  
                        else  
                        {
                            if(!empty($arSub["SUB"]) && is_array($arSub["SUB"]))
                            {
                                foreach($arSub["SUB"] as $key2=>$arSub2)
                                {
                                    if($arSub2["ADD"] && !$arSub2["SHOW"])
                                    {
                                        unset($arResult[$key]["SUB"][$key1]["SUB"][$key2]);
                                    }  
                                }
                            }
                        }
                    }
                }
            }
            
        }
    }

   
    $this->includeComponentTemplate();
    
}

?>

