<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?


if(!empty($arResult["SECTIONS"]))
{
    global $PHOENIX_TEMPLATE_ARRAY;
    $hide_menu = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"] == "Y") ? true : false;

    $arRes = array();

    foreach($arResult["SECTIONS"] as $key=>$arSection)
    {
        if($arSection["DEPTH_LEVEL"] > 2)
            unset($arResult["SECTIONS"][$key]);
        else
        {
            if($hide_menu && intval($arSection["ELEMENT_CNT"]) <= 0)
            {
                unset($arResult["SECTIONS"][$key]);
                continue;
            }
            $arRes[] = $arSection["ID"];            
        }
        
    }



    if(!empty($arRes))
    {
        $arUF = array();
        $arSelect = Array("ID","UF_PHX_CTLG_PRTXT", "UF_PHX_CTLG_SIZE", "UF_PHX_MAIN_CTLG", "UF_PHX_CTLG_BTN", "UF_PHX_PICT_IN_HEAD", "UF_PHX_MENU_PICT");
        $arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ID" => $arRes);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

        while($ar_result = $db_list->GetNext())
        {
            $arUF["UF"][$ar_result["ID"]] = $ar_result;

            if($ar_result["UF_PHX_CTLG_SIZE"])
                $arUF["UF_PHX_CTLG_SIZE_ENUM"][$ar_result["UF_PHX_CTLG_SIZE"]] = $ar_result["UF_PHX_CTLG_SIZE"];
        }

      
        if(!empty($arUF["UF_PHX_CTLG_SIZE_ENUM"]))
        {
            foreach ($arUF["UF_PHX_CTLG_SIZE_ENUM"] as $value) {
                $arUF["UF_PHX_CTLG_SIZE_ENUM_VALUES"][$value] = CUserFieldEnum::GetList(array(), array(
                    "ID" => $value,
                ))->GetNext();
                $arResult["SETTINGS"]["COLS"] .= "<input type=\"hidden\" class=\"colls_".$arUF["UF_PHX_CTLG_SIZE_ENUM_VALUES"][$value]["XML_ID"]."\" value=\"".$value."\" />";
            }

        }
        
        $size2 = array("width" => 356, "height" => 255);
        $size3 = array("width" => 400, "height" => 262);
        
        foreach($arResult["SECTIONS"] as $key=>$arSection)
        {
            if($arSection["ID"] == $arUF["UF"][$arSection["ID"]]["ID"])
            {
                unset($arUF["UF"][$arSection["ID"]]["ID"], $arUF["UF"][$arSection["ID"]]["~ID"]);

                $arSection = array_merge($arSection, $arUF["UF"][$arSection["ID"]]);

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]["VALUE"] == "view-2")
                {
                    $img = null;

                    if($arSection["UF_PHX_MENU_PICT"])
                    {
                        $img = CFile::ResizeImageGet($arSection["UF_PHX_MENU_PICT"], array("width" => 70, "height" => 70), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                        $arSection["PREVIEW_PICTURE_SRC"] = $img["src"];
                    }
                }
                else
                {
                    if($arSection["UF_PHX_CTLG_SIZE"])
                        $arSection["UF_PHX_CTLG_SIZE_ENUM"] = $arUF["UF_PHX_CTLG_SIZE_ENUM_VALUES"][$arSection["UF_PHX_CTLG_SIZE"]];


                    $cols = 'col-lg-3 small';
                    $size = array("width" => 430, "height" => 370);

                    if($arSection["UF_PHX_CTLG_SIZE_ENUM"]["XML_ID"] == 'middle')
                    {
                        $size = array("width" => 750, "height" => 370);
                        $cols = 'middle';
                    }

                    $arSection["COLS_FRAME"] = $cols;

                    $imgXs = null;
                    $imgMd = null;
                    $imgXl = null;

                    if($arSection["PICTURE"] > 0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]["VALUE"] == "view-1")
                    {

                        $imgXs = CFile::ResizeImageGet($arSection["PICTURE"], $size3, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                                    
                                                    
                        $imgMd = CFile::ResizeImageGet($arSection["PICTURE"], $size2, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                        
                        
                        $imgXl = CFile::ResizeImageGet($arSection["PICTURE"], $size, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);


                        $arSection["PICTURE_XS"] = $imgXs["src"];
                        $arSection["PICTURE_MD"] = $imgMd["src"];
                        $arSection["PICTURE_XL"] = $imgXl["src"];
                    }
                }

                

                
                

                

                if($arSection["DEPTH_LEVEL"] == 1)
                {
                    $main_key = $key;
                    $arResult["SECTIONS"][$main_key] = $arSection;
                }
                
                if($arSection["DEPTH_LEVEL"] == 2)
                {
                    $main_key1 = $key;
                    
                    $arResult["SECTIONS"][$main_key]["SUB"][$main_key1] = $arSection;
                    unset($arResult["SECTIONS"][$main_key1]);
                }

                unset($arUF["UF"][$arSection["ID"]]);
            }
        }
        

        foreach($arResult["SECTIONS"] as $key=>$arSection)
        {
            if($arSection['UF_PHX_MAIN_CTLG']=="0")
                unset($arResult["SECTIONS"][$key]);
                    
                    
            if(!empty($arSection["SUB"]) && is_array($arSection["SUB"]))
            {
                foreach($arSection["SUB"] as $k=>$arSection1)
                {
                    if($arSection['UF_PHX_MAIN_CTLG']=="0")
                        unset($arResult["SECTIONS"][$key]["SUB"][$k]);

                    if($hide_menu && $arSection1["ELEMENT_CNT"] <= 0)
                        unset($arResult["SECTIONS"][$key]["SUB"][$k]);
                }
            }

            if($hide_menu && $arSection["ELEMENT_CNT"] <= 0)
                unset($arResult["SECTIONS"][$key]);
        }

    }

    $arResult["SECTIONS_CNT"] = count($arResult["SECTIONS"]);
}


/*$arSizes = Array();

$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arParams["IBLOCK_ID"]."_SECTION", "LANG" => "ru", "FIELD_NAME" => "UF_PHX_CTLG_SIZE"));

while($arRes = $rsData->Fetch())
{    
    $rsList = CUserFieldEnum::GetList(array("SORT"=>"ASC"), array(
        "USER_FIELD_ID" => $arRes["ID"],
    ));
    
    while($arList = $rsList->GetNext())
    {
        $arSizes[$arList["XML_ID"]] = $arList["ID"];
    }
    
        
}


$arResult["SIZES"] = $arSizes;*/

?>