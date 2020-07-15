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



<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?

$currentMainPageForSearch = CPhoenix::GetCurrentMainPageCodeForSearch($arParams["IBLOCK_ID"]);
$menu_on = "";
$m = 0;

if($arParams["TYPE"] == "ACT")
{
	$menu_on = "Y";
}
else
{
	$arSelect = Array("UF_PHX_MAIN_".$arParams["TYPE"]);
	$arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
	$db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
	
	while ($ar_result = $db_list->GetNext())
	{
		if($m == 1)
			break;

		if($ar_result["UF_PHX_MAIN_".$arParams["TYPE"]])
	    	$m = 1;
	}
}

if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]['BANNERS']["VALUE"]) || $m == 1)
	$menu_on = "Y";

?>

<?

	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"])>0)
		$header_back = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);


	$colsLeft = "col-md-7 col-12";
	$colsRight = "col-md-5 col-12";

?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]):?>
    <?
        $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"], array('width'=>800, 'height'=>900), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
        $header_back_xs = $img["src"];
    ?>
    <style>
        @media (max-width: 767.98px){
            div.page-header{
                background-image: url('<?=$header_back_xs?>') !important;
            }
        }
    </style>
<?endif;?>

<div class=
		"
			page-header
			sections
			<?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE'][$currentMainPageForSearch] == "Y")? "padding-bottom-section":""?>
			cover
			parent-scroll-down
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
			phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
			<?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>

		" 
	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"])>0):?>
		data-src = "<?=$header_back["src"]?>"
		style="background-image: url(<?=$header_back["src"]?>);"
	<?endif;?>
>

	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

    <div class="top-shadow"></div>

    <div class="container z-i-9">

    	<div class="row">
    		<div class="<?=$colsLeft?> part part-left">
	    			
    			<div class="head margin-bottom">

    				<?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                            "COMPONENT_TEMPLATE" => ".default",
                    		"START_FROM" => "0",
                    		"PATH" => "",
                    		"SITE_ID" => SITE_ID,
                    		"COMPOSITE_FRAME_MODE" => "A",
                    		"COMPOSITE_FRAME_TYPE" => "AUTO",
                    	),
                    	false
                    );*/?>

                    <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["DESC"]["VALUE"]) > 0):?>
                        <div class="subtitle"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["DESC"]["~VALUE"]?></div>
                    <?endif;?>

    			</div>

    		</div>

    		<div class="<?=$colsRight?> part part-right">

    			<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["PIC_IN_HEAD"]["VALUE"])):?>

    				<?$pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["PIC_IN_HEAD"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>

    				<img class = "lazyload d-block mx-auto" data-src="<?=$pic["src"]?>" alt="">

    			<?else:?>

	    			<div class="wrap-scroll-down hidden-md hidden-sm hidden-xs">
	                    <div class="down-scrollBig scroll-down">
	                        <i class="fa fa-chevron-down"></i>
	                    </div>
	                </div>

                <?endif;?>


    		</div>


    	</div>

    	<?include("search.php");?>
    </div>

</div>


<div class="news-list-wrap page_pad_bot <?if($menu_on != "Y"):?>no_menu<?endif;?> <?=$arParams["ARRAY_NAME"]?>">

    <div class="container">

    	<div class="block-move-to-up">

	        <div class="row">

	        	<?
	            	$class2 = "no-menu col-12";

	            	if($menu_on == "Y")
	            	{
	            		$class = "col-lg-3 hidden-md hidden-sm hidden-xs";
	            		$class2 = "col-lg-9 col-md-12 col-12 content-inner";

	            	}
	            ?>

	            <div class="<?=$class2?> page">

	                <div class="block<?if($menu_on == "Y"):?> small <?endif;?> padding-on">

	                	<?if($menu_on == "Y"):?>

		                	<div class="small-info-product news-page align-items-center d-lg-none">
		                		<a class="name show-side-menu"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["NEWS_SHOW_SIFE_MENU_NAME_BTN"]?></a>
		                	</div>

	                	<?endif;?>

	                	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["TOP_TEXT"]["~VALUE"])>0):?>

			        		<div class="top-description text-content">

			        			<div class="<?if($menu_on != "Y"):?>container<?endif;?>">
			        				
	        						<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["TOP_TEXT"]["~VALUE"]?>
					        	
					        	</div>
			        				

		        			</div>

			        	<?endif;?>

			        	<div class="<?if($menu_on != "Y"):?>container<?endif;?>">

	                    	<?$GLOBALS['arActionfilter'] = Array();?>

	                    	<?
	                    		if($arParams["TYPE"] == "ACT")
		                    	{
		                    		$GLOBALS['arActionfilter'] = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE" => "", "<DATE_ACTIVE_FROM" => ConvertTimeStamp(time(),"FULL"));

		                    		if($_REQUEST["action"]=="on")
		                    			$GLOBALS['arActionfilter'] = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE" => "Y", "<DATE_ACTIVE_FROM" => ConvertTimeStamp(time(),"FULL"));

		                    		if($_REQUEST["action"]=="off")
		                    			$GLOBALS['arActionfilter'] = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"", "<=DATE_ACTIVE_TO" => ConvertTimeStamp(time(),"FULL"));
		                    	}

	                    	?>

	                    	<?if($arParams["TYPE"] != "ACT"):?>
									
								<?$GLOBALS['arActionfilter']["ACTIVE"] = "Y";?>
		                    	<?$GLOBALS['arActionfilter']["SECTION_GLOBAL_ACTIVE"] = "Y";?>
		                    	<?//$GLOBALS['arActionfilter']["SECTION_ACTIVE"] = "Y";?>
		                    	<?$GLOBALS['arActionfilter']["SECTION_SCOPE"] = "IBLOCK";?>
		                    	<?$GLOBALS['arActionfilter']["SECTION_ID"] = "0";?>

	                  		
	                  		<?endif;?>

	                  		


							<?$APPLICATION->IncludeComponent(
								"bitrix:news.list",
								"news.news-list",
								Array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"NEWS_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["NEWS_COUNT"]["VALUE"],
									"DISPLAY_BOTTOM_PAGER" => "Y",
									"SORT_BY1" => $arParams["SORT_BY1"],
									"SORT_ORDER1" => $arParams["SORT_ORDER1"],
									"SORT_BY2" => $arParams["SORT_BY2"],
									"SORT_ORDER2" => $arParams["SORT_ORDER2"],
									"FIELD_CODE" => array(
	                                    0 => "ID",
	                                    1 => "CODE",
	                                    2 => "NAME",
	                                    3 => "SORT",
	                                    4 => "PREVIEW_TEXT",
	                                    5 => "PREVIEW_PICTURE",
	                                    6 => "DETAIL_TEXT",
	                                    7 => "DETAIL_PICTURE",
	                                    8 => "DATE_ACTIVE_FROM",
	                                    9 => "ACTIVE_FROM",
	                                    10 => "SHOW_COUNTER",
	                                    11 => "IBLOCK_TYPE_ID",
	                                    12 => "IBLOCK_ID",
	                                    13 => "IBLOCK_CODE",
	                                    14 => "DATE_ACTIVE_TO",
	                                    15 => "ACTIVE_TO",
	                                    16 => "",
	                                ),
	                                "PROPERTY_CODE" => array(
	                                    0 => "BANNERS_RIGHT",
	                                    1 => "NEWS_ELEMENTS_ACTION",
	                                    2 => "NEWS_ELEMENTS_BLOG",
	                                    3 => "BUTTON_MODAL",
	                                    4 => "NEWS_ELEMENTS_NEWS",
	                                    5 => "BUTTON_FORM",
	                                    6 => "BUTTON_TYPE",
	                                    7 => "NEWS_TITLE_NBA",
	                                    8 => "NEWS_TITLE_CATALOG",
	                                    9 => "NEWS_GALLERY_TITLE",
	                                    10 => "BUTTON_ONCLICK",
	                                    11 => "BUTTON_NAME",
	                                    12 => "NEWS_GALLERY_BORDER",
	                                    13 => "BUTTON_BLANK",
	                                    14 => "BANNERS_RIGHT_TYPE",
	                                    15 => "CATALOG",
	                                    16 => "BUTTON_QUIZ",
	                                    17 => "BUTTON_LINK",
	                                    18 => "ITEM_TEMPLATE",
	                                    19 => "NEWS_DETAIL_TEXT",
	                                    20 => "DATE_ACTIVE_TO",
	                                    21 => "ACTIVE_TO",
	                                    22 => "",
	                                ),
									"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
									"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
									"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
									"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
									"SET_TITLE" => $arParams["SET_TITLE"],
									"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
									"MESSAGE_404" => $arParams["MESSAGE_404"],
									"SET_STATUS_404" => $arParams["SET_STATUS_404"],
									"SHOW_404" => $arParams["SHOW_404"],
									"FILE_404" => $arParams["FILE_404"],
									"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_FILTER" => "Y",
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
									"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
									
									"PAGER_TITLE" => $arParams["PAGER_TITLE"],
									"PAGER_TEMPLATE" => "phoenix_round",
									"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
									"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
									"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
									"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
									"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
									"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
									"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
									"DISPLAY_DATE" => "Y",
									"DISPLAY_NAME" => "Y",
									"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
									"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
									"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
									"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
									"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
									"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
									"FILTER_NAME" => "arActionfilter",
									"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
									"CHECK_DATES" => "Y",
									"ADD_SECTIONS_CHAIN" => "Y",
									"COL_LG" => ($menu_on == "Y") ? "4": "3",
									"TYPE" => $arParams["TYPE"],
									"COMPOSITE_FRAME_MODE" => "N",
	                          
	                          
								),
								$component
							);?>

							<?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = 0;?>
							<?$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";?>

							
			        	</div>

						<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BOT_TEXT"]["~VALUE"])>0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]['BOT_TEXT_POS']['VALUE'] == "short" && $menu_on == "Y"):?>

		        			<div class="bottom-description text-content">

				        		<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BOT_TEXT"]["~VALUE"]?>
				        			
				        	</div>

			        	<?endif;?>

					</div>
	                 
	                    
	                   
			    </div>

	        	<?if($menu_on == "Y"):?>
	                <div class="<?=$class?> parent-fixedSrollBlock">

	                	<div class="wrapperWidthFixedSrollBlock">

	                		<div class="selector-fixedSrollBlock menu-navigation" id='navigation'>
	                			<div class="selector-fixedSrollBlock-real-height menu-navigation-inner">

	                				<div class="for-sidemenu-mobile">
			                            	
		                            	<?
		                                    $tmpl = "";

		                                    if($arParams["TYPE"] == "ACT")
		                                        $tmpl = "-action";
		                                    
		                                ?>
		                                    
		                                <?$APPLICATION->IncludeComponent(
		                                        "bitrix:catalog.section.list",
		                                        "news.sections".$tmpl,
		                                        array(
		                                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		                                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
		                                            "SECTION_ID" => $arFields["IBLOCK_SECTION_ID"],
		                                            "SECTION_CODE" => "",
		                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
		                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
		                                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		                                            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		                                            "TOP_DEPTH" => 1,
		                                            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		                                            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		                                            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
		                                            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		                                            "ADD_SECTIONS_CHAIN" => "N",
		                                            "TYPE"=> $arParams["TYPE"],
		                                            "COMPOSITE_FRAME_MODE" => "N",
		                                           
		                                        ),
		                                        $component,
		                                        array("HIDE_ICONS" => "Y")
		                                    );                  
		                                ?>


		                                <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]['BANNERS']["VALUE"])):?>
			                                
			                                <?$GLOBALS["arrBannersFilter"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]['BANNERS']["VALUE"];?>
			                                
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

			                            <div class="close-mob close-side-menu d-lg-none"></div>

		                            </div>

	                            </div>

			                </div>

	                    </div>
	                </div>
	            <?endif;?>


			</div>

		</div>

	</div>

	

</div>

<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BOT_TEXT"]["~VALUE"])>0 && ($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]['BOT_TEXT_POS']['VALUE'] == "long" || $menu_on != "Y") ):?>
		

	<div class="bottom-description-full text-content">
		<div class="container">
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BOT_TEXT"]["~VALUE"]?>
		</div>

	</div>

<?endif;?>
