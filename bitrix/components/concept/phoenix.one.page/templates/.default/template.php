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

<?ob_start();?>


<?global $btn_view;?>
<?global $USER;?>
<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?global $PHOENIX_MENU;?>
<?global $Folder;

$Folder = $templateFolder;?>

<?global $h1;?>
<?$h1 = 0;?>


<?global $components;?>
<?$components = 0;?>


<?$btn_view = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE'];?>


<?function CreateEmptyBlock($arSection){


    global $PHOENIX_TEMPLATE_ARRAY;
    global $Folder;
    global $APPLICATION;

    include($_SERVER["DOCUMENT_ROOT"].$Folder."/main/create_empty_block.php");

    

}?>

<?function CreateFirstSlider($arSlider){

        global $USER;
        global $btn_view;
        global $PHOENIX_TEMPLATE_ARRAY;
        global $PHOENIX_MENU;
        global $Folder;
        global $APPLICATION;

        if($PHOENIX_MENU == 0)
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = 1;

        include($_SERVER["DOCUMENT_ROOT"].$Folder."/blocks/".$arSlider["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");

}?>


<?function CreateHead($arItem, $show_menu, $min = false, $main_key){

    global $h1;
    global $Folder;
    global $APPLICATION;
    global $PHOENIX_TEMPLATE_ARRAY;

    if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) > 0)
        include($_SERVER["DOCUMENT_ROOT"].$Folder."/main/create_head.php");

         
}?>



<?function CreateButton($arItem, $show_menu, $center = true, $view = "view-first", $btn = "def"){


    global $btn_view;
    global $PHOENIX_TEMPLATE_ARRAY;
    global $Folder;
    global $APPLICATION;

    if( strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) > 0 )
        include($_SERVER["DOCUMENT_ROOT"].$Folder."/main/create_button.php");   
}?>


<?function CreateElement($arItem, $arSection, $show_menu, $key){?>


    <?
    
        $main_key = $key;

        global $PHOENIX_TEMPLATE_ARRAY;
        global $btn_view;
        global $PHOENIX_MENU;
        global $Folder;
        global $APPLICATION;
        
        global $components;

        $block_name = $arItem['NAME'];

        if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
            $block_name .= " (".strip_tags($arItem["PROPERTIES"]["HEADER"]["~VALUE"]).")";

        $bg_on = '';
        $style = "";
        $style2 = "";

        $class ="";
        $attr = "";
        

        if($PHOENIX_MENU == 0)
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = 1;


    
        if(strlen($arItem["PREVIEW_PICTURE"]["SRC"]))
        {
            if($key == 0)
                $style .= "background-image: url('".$arItem["PREVIEW_PICTURE"]["SRC"]."');";
            
            
            else
            {
                $attr .= "data-src='".$arItem["PREVIEW_PICTURE"]["SRC"]."'";
                $class .= "lazyload ";
            }

            $bg_on = 'bg-on';
        }

        if(strlen($arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"]) > 0)
        {
            $style .= "background-color: ".$arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"].";";
            $bg_on = 'bg-on';
        }
        if(strlen($arItem["PROPERTIES"]["SHADOW"]["VALUE_XML_ID"])>0)
            $bg_on = 'bg-on';

   
    ?>

    
    
    <?if($arItem["PROPERTIES"]["COMPONENT_DESIGN"]["VALUE_XML_ID"] != "Y" && $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "component"):?>
  
            <?include($_SERVER["DOCUMENT_ROOT"].$Folder."/blocks/".$arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");?>
    
    <?else:?>

    	<?

	    	$class_block = 'block item-block '.$class.' '.$arItem["PROPERTIES"]["SHADOW"]["VALUE_XML_ID"].' '.$arItem["PROPERTIES"]["COVER"]["VALUE"];

	    	if($key == 0)
	    		$class_block .= ' first-bigblock phoenix-firsttype-'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"];

	    	if($show_menu)
	    		$class_block .= ' small-block '.$bg_on;
            else
                $class_block .= ' full-block '.$bg_on;

            if($arItem["Z_INDEX"])
                $class_block .= ' z-index';


	    	if(!$arItem["PADDING_CHANGE"])
	    		$class_block .= ' padding-on';

            if($arItem["PROPERTIES"]["PARALLAX"]["VALUE_XML_ID"] == "100")
                $class_block .= ' parallax-attachment';

	    	
            $show_block = true;

	    	if(!$arItem["HIDDEN_BLOCK_SLIDER"])
	    	{
	    		if($arItem["PROPERTIES"]["HIDE_BLOCK_LG"]["VALUE"] == "Y")
	    			$class_block .= ' hidden-xxl hidden-xl hidden-lg hidden-md';

	    		if($arItem["PROPERTIES"]["HIDE_BLOCK"]["VALUE"] == "Y")
	    			$class_block .= ' hidden-sm hidden-xs';
	    	}

            if(
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                ||
                strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
            )
            {
                $class_block .= " parent-video-bg"; 
            }

            if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y" && ($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "text"))
                $class_block .= " overflow-h";
    	?>

        
        <div id="block<?=$arItem["ID"]?>" 
            class="<?=$class_block?>" style="<?=$style?>" <?=$attr?> <?if($arItem["PROPERTIES"]["PARALLAX"]["VALUE_XML_ID"] == "30"):?>data-enllax-ratio=".25"<?endif;?>>

            <?if(!empty($arItem["ELEMENTS"])):?>
                <?foreach ($arItem["ELEMENTS"] as $keyElement => $arElement):?>
                    <?if(!isset($arElement["ID"])) break;?>
                    <?if($arElement["ID"] == $arItem["ID"]) continue;?>
                    <?if($arElement["PROPERTIES"]["SHOW_IN_MENU"]["VALUE"] == "Y"):?>
                        <div id="block<?=$arElement["ID"]?>"></div>
                    <?endif;?>
                <?endforeach;?>
            <?endif;?>
         
            <?if(!$arItem["CUSTOM_MARGIN_PADDING"]):?>
                <style>

                    <?if(
                        strlen($arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])):?>

                        @media (min-width: 768px){
                            #block<?=$arItem["ID"]?>{
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"])>0):?>
                                    margin-top: <?=$arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"]?>px !important;
                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"])>0):?>
                                    margin-bottom: <?=$arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"]?>px !important;
                                <?endif;?>

                                <?if(!$arItem["PADDING_CHANGE"]):?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])>0):?>
                                        padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"]?>px !important;
                                    <?endif;?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])>0):?>
                                        padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"]?>px !important;
                                    <?endif;?>

                                <?endif;?>
                            }

                            <?if($arItem["PADDING_CHANGE"]):?>
                                #block<?=$arItem["ID"]?> .padding-change{

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])>0):?>
                                        padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"]?>px !important;
                                    <?endif;?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])>0):?>
                                        padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"]?>px !important;
                                    <?endif;?>

                                }
                            <?endif;?>
                        }
                    <?endif;?>

                    <?if(
                        strlen($arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"])):?>


                        @media (max-width: 767px){

                            #block<?=$arItem["ID"]?>{
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"])>0):?>margin-top: <?=$arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"]?>px !important;<?endif;?>
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"])>0):?>margin-bottom: <?=$arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"]?>px !important;<?endif;?>
                                
                                <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"])>0):?>padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"]?>px !important; <?endif;?>
                                <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"])>0):?>padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"]?>px !important;;<?endif;?>
                                
                            }

                        }

                    <?endif;?>
                    
                </style>
            <?endif;?>


            


            <?if(
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                ||
                strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
            ):?>

                <?

                    if(
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                    )
                    {
                        $iframeType = "file";

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"])
                            $srcMP4 = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"]);

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"])
                            $srcWEBM = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"]);

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"])
                            $srcOGG = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"]);
                    }

                    
                    elseif(strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0)
                    {
                        $iframeType = "iframe";

                        $srcYB = CPhoenix::createVideo($arItem['PROPERTIES']['VIDEO_BACKGROUND']['~VALUE']);

                    }
                ?>

                <div 
                    class="videoBG hidden-sm hidden-xs"
                    data-type = "<?=$iframeType?>"

                    <?if(isset($srcYB["SRC"]{0})):?>
                        data-srcYB = "<?=$srcYB["SRC"]?>"
                    <?endif;?>

                    <?if(strlen($srcMP4)):?>
                        data-srcMP4 = "<?=$srcMP4?>"
                    <?endif;?>

                    <?if(strlen($srcWEBM)):?>
                        data-srcWEBM = "<?=$srcWEBM?>"
                    <?endif;?>

                    <?if(strlen($srcOGG)):?>
                        data-srcOGG = "<?=$srcOGG?>"
                    <?endif;?>
                >
                </div>

                <img class="lazyload img-for-lazyload videoBG-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">

            <?endif;?>
        
            <div class="shadow-tone"></div>
    
            
            <?if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["HEADER_BG"]):?>
                <?if($key == 0):?>
                    <div class="top-shadow"></div>
                <?endif;?>
            <?endif;?>
    
            
            <?if(is_array($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"]) && !empty($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"])):?>
                
                <?foreach($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"] as $arSlID):?>
                    <div class="corner <?=$arSlID?> hidden-md hidden-sm hidden-xs"></div>
                <?endforeach;?>
                    
            <?endif;?>
    
            
            
            <?if(!$arItem["TITLE_CHANGE"]):?>
                <?CreateHead($arItem, $show_menu, false, $main_key);?>
            <?endif;?>
            
            <div class="content <?if(($arItem["TITLE_CHANGE"]) || !(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) > 0)):?>no-margin<?endif;?>">
    
                <div class="<?if(!$show_menu):?>container<?endif;?>"><!-- open main container -->
                    



                    <?include($_SERVER["DOCUMENT_ROOT"].$Folder."/blocks/".$arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");?>
              
                   
    
                    <?if(!$arItem["BUTTON_CHANGE"] && !isset($arItem["HIDE_BUTTON"])):?>
                        <?CreateButton($arItem, $show_menu);?>
                    <?endif;?>

                   
    
                </div><!-- close main container -->
                
            
                
            </div>
    
            <?if(!empty($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"]) && is_array($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"])):?>
    
                <?foreach($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"] as $line):?>
                    
                    <div class="line-ds <?=$line?>">
    
                        <div class="<?if(!$show_menu):?>container<?endif;?>">
                            <div class="ln"></div>
                        </div>
                    </div>
                
                
                <?endforeach;?>
    
            <?endif;?>

            <?if(!isset($arItem["ADMIN_CHANGE"]))
                CPhoenix::admin_setting($arItem, true);?>
            
            <?
                if(isset($arItem["ADDITIONAL"]))
                {
                    $arParams=array(
                        "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                        "IBLOCK_SECTION_ID" => $arItem["ADDITIONAL"]['IBLOCK_SECTION_ID'],
                        "ID" => $arItem["ADDITIONAL"]['ID'],
                        "NAME" => $arItem["ADDITIONAL"]['NAME'],
                        "IBLOCK_TYPE_ID" => $arItem['IBLOCK_TYPE_ID'],
                        "CLASS" => "to-left"
                    );

                    CPhoenix::admin_setting_cust($arParams);
                }

            ?>

            
        </div>

    <?endif;?>

    
<?}?>



    <?if(!empty($arResult["ITEMS"])):?>

        <?$elements = count($arResult["ITEMS"])?>
        <?$counter = 0?>
        <?$inner_menu = false;?>
        <?$show_menu = false;?>



        <?if( (!empty($arResult["MENU"]) || !empty($arResult["SECTION"]["BANNERS"]))):?>
            <?$inner_menu = true;?>
        <?endif;?>

        
        
        <?foreach($arResult["ITEMS"] as $key=>$arItem):?>
            <?global $USER;?>
            <?global $btn_view?>


            <?if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "first_block"):?>
            
                <?CreateFirstSlider($arItem);?>

            <?else:?>


                <?if($counter == 1 && $inner_menu):?>
                    <?$show_menu = true;?>

                    <div class="container content-container constructor-content">
                        <!-- open content-container -->

                        <?
                       
                            $class1 = 'order-first';
                            $class2 = 'order-last';

                            if(strlen($arResult["SECTION"]["UF_PHX_INMENU_POS"])>0){
                                if($arResult["SECTION"]["UF_PHX_INMENU_POS_RES"]['XML_ID'] == 'right')
                                {
                                    $class1 = 'order-last';
                                    $class2 = 'order-first';
                                }
                            }

                        ?>

                        <div class="row">
                            <!-- open row menu -->
                        
                            <div class="col-lg-3 hidden-md hidden-sm hidden-xs <?=$class1?> parent-fixedSrollBlock">

                                <div class="sidemenu-container">

                                    <div class="wrapperWidthFixedSrollBlock">

                                        <div class="selector-fixedSrollBlock menu-navigation" id='navigation'>

                                            <div class="selector-fixedSrollBlock-real-height menu-navigation-inner">

                                                <?if(!empty($arResult["MENU"])):?>

                                                    <div class="row">

                                                        <ul class='nav'>
                                                            <?foreach($arResult["MENU"] as $menu):?>
                                                                

                                                            <li class='col-12 <?if(strlen($menu['ICON'])>0 || strlen($menu['PICTURE'])>0):?> on-ic<?endif;?> <?if($menu["HIDE_LG"] == "Y") echo 'hidden-xxl hidden-xl '; if($menu["HIDE"] == "Y") echo 'hidden-lg hidden-md hidden-sm hidden-xs';?>'>

                                                                <a href="#block<?=$menu['ID']?>" class='nav-link scroll'>

                                                                <?if(strlen($menu['PICTURE'])>0):?>
                                                                    <?$img = CFile::ResizeImageGet($menu["PICTURE"], array('width'=>20, 'height'=>20), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                                                                    <img class="lazyload" data-src="<?=$img["src"]?>" alt='<?=$menu['NAME']?>' />
                                                                <?elseif(strlen($menu['ICON'])>0):?>
                                                                    <i class="<?=$menu['ICON']?>" style='color: <?=$menu['ICON_COLOR']?>;'></i>
                                                                <?endif;?>

                                                                <span class="text"><?=$menu['NAME']?></span>

                                                            </a></li>

                                                            <?endforeach;?>

                                                           
                                                    
                                                        </ul>

                                                    </div>

                                                <?endif;?>

                                                <?if(is_array($arResult["SECTION"]["UF_PHX_BANNERS"]) && !empty($arResult["SECTION"]["UF_PHX_BANNERS"])):?>
                                                
                                                    <?$GLOBALS["arrBannersFilter"]["ID"] = $arResult["SECTION"]["UF_PHX_BANNERS"];?>
                                            
                                                    <?$APPLICATION->IncludeComponent(
                                                        "bitrix:news.list", 
                                                        "banners-left", 
                                                        array(
                                                            "COMPONENT_TEMPLATE" => "banners-left",
                                                            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"],
                                                            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"],
                                                            "NEWS_COUNT" => "20",
                                                            "SORT_BY1" => "SORT",
                                                            "SORT_ORDER1" => "ASC",
                                                            "SORT_BY2" => "SORT",
                                                            "SORT_ORDER2" => "ASC",
                                                            "FILTER_NAME" => "arrBannersFilter",
                                                            "FIELD_CODE" => array(
                                                                0 => "DETAIL_PICTURE",
                                                                1 => "PREVIEW_PICTURE",
                                                            ),
                                                            "PROPERTY_CODE" => array(
                                                                0 => "",
                                                                1 => "BANNER_BTN_TYPE",
                                                                2 => "BANNER_ACTION_ALL_WRAP",
                                                                3 => "BANNER_USER_BG_COLOR",
                                                                4 => "BANNER_UPTITLE",
                                                                5 => "BANNER_BTN_NAME",
                                                                6 => "BANNER_TITLE",
                                                                7 => "BANNER_BTN_BLANK",
                                                                8 => "BANNER_BORDER",
                                                                9 => "BANNER_DESC",
                                                                10 => "BANNER_TEXT",
                                                                11 => "BANNER_LINK",
                                                                12 => "BANNER_COLOR_TEXT",
                                                                13 => "",
                                                            ),
                                                            "CHECK_DATES" => "Y",
                                                            "DETAIL_URL" => "",
                                                            "AJAX_MODE" => "N",
                                                            "AJAX_OPTION_JUMP" => "N",
                                                            "AJAX_OPTION_STYLE" => "Y",
                                                            "AJAX_OPTION_HISTORY" => "N",
                                                            "AJAX_OPTION_ADDITIONAL" => "",
                                                            "CACHE_TYPE" => "A",
                                                            "CACHE_TIME" => "36000000",
                                                            "CACHE_FILTER" => "Y",
                                                            "CACHE_GROUPS" => "Y",
                                                            "PREVIEW_TRUNCATE_LEN" => "",
                                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                                            "SET_TITLE" => "N",
                                                            "SET_BROWSER_TITLE" => "N",
                                                            "SET_META_KEYWORDS" => "N",
                                                            "SET_META_DESCRIPTION" => "N",
                                                            "SET_LAST_MODIFIED" => "N",
                                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                            "ADD_SECTIONS_CHAIN" => "N",
                                                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                                            "PARENT_SECTION" => "",
                                                            "PARENT_SECTION_CODE" => "",
                                                            "INCLUDE_SUBSECTIONS" => "N",
                                                            "STRICT_SECTION_CHECK" => "N",
                                                            "DISPLAY_DATE" => "N",
                                                            "DISPLAY_NAME" => "N",
                                                            "DISPLAY_PICTURE" => "N",
                                                            "DISPLAY_PREVIEW_TEXT" => "N",
                                                            "COMPOSITE_FRAME_MODE" => "N",
                                                            "PAGER_TEMPLATE" => ".default",
                                                            "DISPLAY_TOP_PAGER" => "N",
                                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                                            "PAGER_TITLE" => "",
                                                            "PAGER_SHOW_ALWAYS" => "N",
                                                            "PAGER_DESC_NUMBERING" => "N",
                                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                            "PAGER_SHOW_ALL" => "N",
                                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                                            "SET_STATUS_404" => "N",
                                                            "SHOW_404" => "N",
                                                            "MESSAGE_404" => ""
                                                        ),
                                                        false
                                                    );?>
                                                
                                                <?endif;?>

                                                    

                                            </div>

                                        </div>

                                       


                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-9 col-12 content-inner <?=$class2?>">
                                <!-- open content-inner -->

                        

                <?endif;?>

            
                <?CreateElement($arItem, $arResult["SECTION"], $show_menu, $key);?>

                <?if((($counter+1) == $elements) && $inner_menu):?>

                            </div>
                            <!-- close content-inner -->

                        </div>
                        <!-- close row menu -->
                        
                    </div><!-- close content-container -->

                    

                <?endif;?>
            
            <?endif;?>

            <?$counter++;?>
            
        
        <?endforeach;?>

    <?else:?>

        <?CreateEmptyBlock($arResult["SECTION"]);?>

    <?endif;?> 
    



<?$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
  ob_get_clean();?>
    
