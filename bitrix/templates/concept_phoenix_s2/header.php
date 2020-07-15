<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use \Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\Page\Asset as Asset; 

$moduleID = "concept.phoenix";
global $phoenix_rights;
$phoenix_rights = $APPLICATION->GetGroupRight($moduleID);
CModule::IncludeModule($moduleID);


$bIsMainPage = $APPLICATION->GetCurDir(false) == SITE_DIR;


global $PHOENIX_TEMPLATE_ARRAY;
global $USER;
global $PhoenixCssFullList; 
$PhoenixCssListOther = array();

CPhoenix::phoenixOptionsValues(SITE_ID, array(
	"start",
	"design",
	"contacts",
	"menu",
	"footer",
	"catalog",
	"shop",
	"blog",
	"news",
	"actions",
	"lids",
	"services",
	"politic",
	"customs",
	"other",
	"search",
	"catalog_fields",
	"compare",
	"brands",
	"personal",
	"rating"
));

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_ON"]['VALUE']["ACTIVE"] == "Y" && !$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
{

	if(!$bIsMainPage)
		LocalRedirect(SITE_DIR);


	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_LINK"]["VALUE"])>0)
		require_once($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_LINK"]["VALUE"]);
	
	else
		echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_TEXT"]["~VALUE"];

	die();
}
?>

<?

$GLOBALS["PHOENIX_STR_FOR_CATALOG_CACHE"] = "";

//CPhoenix::ClearCacheIBCatalog($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH);

$GLOBALS["SHOW_PHOENIX_SEO"] = "Y";
$GLOBALS["IS_CONSTRUCTOR"] = CPhoenix::IsConstructor("concept_phoenix_site_".SITE_ID);

?>

<?

	$cur_color = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR"]['VALUE'];

	if(strlen($cur_color) <= 4)
	{
	    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR_STD"]['VALUE']) > 0)
	        $cur_color = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR_STD"]['VALUE'];
	}


	if(preg_match("/^rgb/", $cur_color))
	{
		$cur_color = str_replace(array("rgba", "rgb","(",")", ";"), array("","","","",""), $cur_color);
		$cur_color = explode(",", $cur_color);
		$cur_color = CPhoenix::convertRgb2hex($cur_color);
	}

	$name_color = str_replace(array("#"), array(""), $cur_color);


	$file_less = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/css/main_color.less";

    $dir = SITE_TEMPLATE_PATH."/css/generate_colors/site/";

    $dir_abs = $_SERVER["DOCUMENT_ROOT"].$dir;

    $newfile_css = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/css/generate_colors/site/main_color_".$name_color.".css";

    $flag = false;



    if(!file_exists($newfile_css))
    {
        DeleteDirFilesEx($dir);
        $flag = true;
    }


    if($flag)
    {

        CheckDirPath($dir_abs);

        require ($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lessc.inc.php");

        $less = new lessc;
        $less->setVariables(array(
            "color" => $cur_color
        ));

        $less->compileFile($file_less, $newfile_css);

    }


	$PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/generate_colors/site/main_color_".$name_color.".css";
?>

<?Loc::loadMessages(__FILE__);?>

<!DOCTYPE HTML>
<html lang="<?=LANGUAGE_ID?>" prefix="og: //ogp.me/ns#">
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=2.4" />

    <title><?$APPLICATION->ShowTitle()?></title>


    <?$APPLICATION->ShowHead();?>


	<?if(Bitrix\Main\Loader::includeModule('currency')):?>
	    <script skip-moving="true">
	        BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($PHOENIX_TEMPLATE_ARRAY["CURRENCIES"], false, true, true)?>);
	    </script>
	<?endif;?>


    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SCROLLBAR"]['VALUE']["ACTIVE"]=="Y"):?>
		<style>
		    ::-webkit-scrollbar{
		        width: 0px;
		    }
		</style>
	<?endif;?>


	<?
		if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["META"]["VALUE"]))
		{
			foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["META"]["~VALUE"] as $arMeta){
				echo $arMeta["name"]."\n" ;
			}
		}

		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]["VALUE"]["ACTIVE"] != "Y" && isset($PHOENIX_TEMPLATE_ARRAY["HEAD_JS"]{0}))
			echo $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"];


		if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INHEAD"]['VALUE']))
			echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INHEAD"]['~VALUE'];
	?>


	<?$APPLICATION->ShowViewContent("service_head");?>


    
</head>

<?
	if(CModule::IncludeModuleEx($moduleID) == 3)
	{
	    include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/expired.php");
	    die();
	}

	$APPLICATION->ShowPanel();

	$offset = 0;

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"] == 'fixed')
		$offset = 150;

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE'] == '')
		$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE'] == 'light';
?>


<body class="font-maincolor-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE']?> <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]{0}))? "captcha":"";?>" id="body" data-spy="scroll" data-target="#navigation" data-offset="<?=$offset?>">

	<?require_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/styles_and_scripts.php");?>

	<input type="hidden" class="tmpl_path" name="tmpl_path" value="<?=SITE_TEMPLATE_PATH?>">
	<input type="hidden" class="tmpl" name="tmpl" value="<?=SITE_TEMPLATE_ID?>">
	<input type="hidden" class="site_id" name="site_id" value="<?=SITE_ID?>">
	<input type="hidden" class="urlpage" name="urlpage" value="">
	<input type="hidden" id="showBasketAfterFirstAdd" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ADD_ON"]["VALUE"]["ACTIVE"]?>">
	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]{0})):?>
		<input class="captcha-site-key" type="hidden" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]?>">

	<?endif;?>


	<?
		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]["VALUE"]["ACTIVE"] != "Y" && isset($PHOENIX_TEMPLATE_ARRAY["BODY_JS"]{0}))
			echo $PHOENIX_TEMPLATE_ARRAY["BODY_JS"];

		if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INBODY"]['VALUE']))
			echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INBODY"]['~VALUE'];
	
		//standart fonts
		$arStandart = array();

		$arStandart[] = "arial";


		//include
		$arInclude = Array();

		$arInclude[] = "lato";
		$arInclude[] = "helvetica";
		$arInclude[] = "segoeUI";


		//Google fonts
		$arGoogle = Array();

		$arGoogle["elmessiri"] = "El+Messiri:400,700";
		$arGoogle["exo2"] = "Exo+2:400,700";
		$arGoogle["ptserif"] = "PT+Serif:400,700";
		$arGoogle["roboto"] = "Roboto:300,400,700";
		$arGoogle["yanonekaffeesatz"] = "Yanone+Kaffeesatz:400,700";
		$arGoogle["firasans"] = "Fira+Sans+Condensed:300,700";
		$arGoogle["arimo"] = "Arimo:400,700";
		$arGoogle["opensans"] = "Open+Sans:400,700";


		if(in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arInclude))
		{
		    $font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']] = true;
		    $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'].".css";
		}
		elseif(!in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arStandart) && !in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arInclude))
		{
		    $font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']] = true;
		    $PhoenixCssListOther[] = "https://fonts.googleapis.com/css?family=".$arGoogle[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']]."&amp;subset=cyrillic&display=swap";
		}

		if(in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arInclude) && !$font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']]){
		    $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'].".css";
		}
		elseif(!in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arStandart) && !in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arInclude) && !$font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']])
		{
		    $PhoenixCssListOther[] = "https://fonts.googleapis.com/css?family=".$arGoogle[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']]."&amp;subset=latin,cyrillic";
		}

		/*if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["USE_RUB"]["VALUE"]["ACTIVE"]=="Y")
		    $PhoenixCssListOther[] = "https://fonts.googleapis.com/css?family=PT+Sans+Caption&amp;display=swap&amp;subset=latin-ext";*/
		


		$PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/title/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'].".css";
		$PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/text/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'].".css";

		$PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/custom.css";
		$PhoenixCssFullList = array_merge($PhoenixCssFullList, $PhoenixCssListOther);

		foreach($PhoenixCssListOther as $css)
			Asset::getInstance()->addCss($css, true);
		
	   	


		if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC']))
		{

	    	if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]))
	    		$arFile = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"];

		    $APPLICATION->AddHeadString('<link rel="icon" href="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC'].'" type="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"].'">', false, true);

		    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"] == "image/x-icon")
		        $APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC'].'" type="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"].'">', false, true);

		}
		else
		    $APPLICATION->AddHeadString('<link rel="icon" href="'.SITE_TEMPLATE_PATH.'/favicon.png" type="image/png">');

	
		global $PHOENIX_MENU;


		if($PHOENIX_MENU>0)
			$APPLICATION->IncludeComponent(
				"concept:phoenix.menu", 
				"open_menu", 
				array(
					"COMPONENT_TEMPLATE" => "open_menu",
					"COMPOSITE_FRAME_MODE" => "N",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CACHE_USE_GROUPS" => "Y"
				),
				false
			);
	?>

	<?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU'] == "Y" ):?>
	    
	    <div class="search-top search-top-js">
	        
	        <div class="container">
	            <div class="close-search-top"></div>


	            <?$APPLICATION->IncludeComponent("concept:phoenix.search.line", "top", 
	            	Array(
	            		"CONTAINER_ID" => "search-page-input-container-top",
	            		"INPUT_ID" => "search-page-input-top",
	            		"SHOW_RESULTS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['FASTSEARCH_ACTIVE']['VALUE']['ACTIVE'],
	            		"COMPOSITE_FRAME_MODE" => "N"
	            	)
	            );?>
	            
	        </div>

	    </div>

	<?endif;?>


	<div id="phoenix-container" class="wrapper tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>">


		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["SHARE_ON"]["VALUE"]["ACTIVE"] == 'Y'):?>
	    	
	    
	        <div class="public_shares d-none d-sm-block">
	        
	            <a class='vkontakte' onclick=""><i class="concept-vkontakte"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	            <a class='facebook' onclick=""><i class="concept-facebook-1"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	            <a class='twitter' onclick=""><i class="concept-twitter-bird-1"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	        </div>
	        
	    <?endif;?>


		<?
			if($PHOENIX_MENU>0)
				$APPLICATION->IncludeComponent(
					"concept:phoenix.menu",
					"mobile_menu",
					Array(
						"COMPONENT_TEMPLATE" => "mobile_menu",
						"COMPOSITE_FRAME_MODE" => "N",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"CACHE_USE_GROUPS" => "Y"
					)
				);

			$PHOENIX_TEMPLATE_ARRAY["HEADER_BG"] = false;

			$style_header = "";

			if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'])>0)
			{
				$arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'];
				$percent = 1;


				if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'] == "transparent")
					$style_header .= 'background-color: transparent; ';

				
				else if(preg_match('/^\#/', $arColor))
		        {
		            $arColor = CPhoenix::convertHex2rgb($arColor);
		            $arColor = implode(',',$arColor);

		            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_OPACITY"]['VALUE'])>0)
		            $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_OPACITY"]['VALUE'])/100;

		        
					$style_header .= 'background-color: rgba('.$arColor.', '.$percent.'); ';
		        }

		        else
		        	$header_style .= 'background-color: '.$arColor.'; ';
			}

			if(strlen($style_header)>0)
			{
				$style_header = "style = '".$style_header."'";
				$PHOENIX_TEMPLATE_ARRAY["HEADER_BG"] = true;
			}

			if($PHOENIX_MENU==0)
				$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = "hidden";

		?>

		<header 

			class=
			"
				tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> 
				menu-type-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
				menu-view-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]["VALUE"]?>
				<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"]?>
				<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COVER"]["VALUE"]["ACTIVE"]?>
				color_header-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["COLOR_HEADER"]["VALUE"]?>
				
			"
			<?if(strlen($style_header)>0) echo $style_header;?>

			<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG"]["VALUE"])>0):?>
				<?$headBgResize = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG"]["VALUE"], array('width'=>2000, 'height'=>2000), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
				data-src-lg="<?=$headBgResize['src']?>"
			<?endif;?>
		>

		    <div class="static-board hidden-sm hidden-xs">

		        <div class="container">

		        	<?
		        		
		        		$order_logotype = "order-1";
		        		$order_contacts = "order-2";

		        		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_VIEW"]["VALUE"] == "two")
		        		{
		        			$order_logotype = "order-2";
		        			$order_contacts = "order-1";
		        		}
		        	?>

		        	<div class="wrapper-head-top">
			            <div class="inner-head-top row align-items-center">

			            	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_VIEW"]["VALUE"] == "two"):?>


				            	<div class="col board-contacts <?=$order_contacts?>">

						    		<div class=
						    		"
						    			row no-gutters align-items-center wrapper-item 

						    		">
						    			<?if($PHOENIX_MENU>0):?>
						        			<div class="col-auto board-menu order-first">
						        				<div class="wrapper-icon-hamburger open-main-menu">
								                    <div class="icon-hamburger">
								                        <span class="icon-bar"></span>
								                        <span class="icon-bar"></span>
								                        <span class="icon-bar"></span>
								                    </div>
							                    </div>
						        			</div>
						    			<?endif;?>

						    			<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>

							    			<div class="col-11 wrapper-contacts">
							    				<div class="wrapper-board-contact parent-show-board-contact-js">
							                            	
							                		<?	
							                			$show_list = false;
							                			
							                			$contact_text = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['name'];

							                			$email_text = 
							                				"
							                					<a class='visible-part mail' href='mailto:"
							                    					.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
							                    					."'>

							                    					<span class='bord-bot'>"
							                    						.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
							                    					."</span>
							                        			</a>
							                        		";

							                        	$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['desc'];



							                        	if( strlen($contact_text) )
							                        	{
							                        		if(strlen($comment)<=0)
							                					$comment = $email_text;
							                        		
							                        		$email_text = "";
							                        	}


							                			if( strlen($comment)<=0 )
							                				$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['desc'];

							                		?>

							                		<?if( strlen($contact_text) ):?>

							                			<div>

							                    			<div class="visible-part phone">
							                    				
							                    				<?=$contact_text?>

							                    				<div class="ic-open-list-contact show-board-contact-js open-list-contact"><span></span></div>
							                    					
							                				</div>

							                			</div>

							                		<?endif;?>

							                		<?if( strlen($email_text) ):?>

							                			<div>

							                    			<div class='visible-part'>
								                    			<?=$email_text?>

								                    			<div class="ic-open-list-contact show-board-contact-js"><span></span></div>
							                        		</div>

							                    		</div>

							                		<?endif;?>

							                		<?if( strlen($comment) ):?>

							                			<div class='comment'>
							                    			<?=$comment?>
							                    		</div>

							                		<?endif;?>

							                		<div class="list-contacts">

							                            <table>

							                            	<?$flagcallback = true;?>

							                            	<?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
							                            	
							                                    <tr>
							                                        <td>
							                                            <div class="phone"><span ><?=$val['name']?></span></div>
							                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
							                                            	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
							                                            <?endif;?>
							                                        </td>
							                                    </tr>

							                                    <?if($key == 0):?>

							                                    	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]["CALLBACK"]["VALUE"] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
									                               		<tr class="no-border-top">
									                                        <td>

													                            <div class="button-wrap">
													                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
													                            </div>
													                        </td>
													                    </tr>
											                        <?endif;?>

											                        <?$flagcallback = false;?>

							                                    <?endif;?>

							                                <?endforeach;?>

							                                <?if($flagcallback):?>

							                                	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
								                               		<tr>
								                                        <td>

												                            <div class="button-wrap">
												                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
												                            </div>
												                        </td>
												                    </tr>
										                        <?endif;?>

							                                <?endif;?>


							                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

							                                	<tr>
							                                    	<td>
							                                            <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
							                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
							                                            	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
							                                            <?endif;?>
							                                        </td>
							                                    </tr>

							                                <?endforeach;?>

							                                <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE']) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']) ):?>
							                               		<tr>
							                                        <td>
							                                        	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE'])):?>

							                                        		<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']?></div>

							                                        	<?endif;?>

							                                        	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

												                            
											                                <a class="btn-map-ic show-dialog-map"><i class="concept-icon concept-location-5"></i> <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CONTACTS_DIALOG_MAP_BTN_NAME"]?></span></a>
										                                	

											                            <?endif;?>
											                        </td>
											                    </tr>
									                        <?endif;?>

							                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
							                                    <tr>
							                                        <td>
							                                            <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
							                                        </td>
							                                    </tr>
							                                
							                                <?endif;?>
							                                    
							                            </table>
							                        </div>

							                	</div>
							    			</div>

						    			<?endif;?>
						    		</div>
						    		
						    	</div>

						    	<?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"]{0})):?>
									<div class="col hidden-md text-html <?=$order_contacts?>">

										<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

									</div>
								<?endif;?>

			            	<?else:?>

			            		<?if($PHOENIX_MENU>0):?>
			            			<div class="col-auto board-menu order-first">
			            				<div class="wrapper-icon-hamburger open-main-menu">
						                    <div class="icon-hamburger">
						                        <span class="icon-bar"></span>
						                        <span class="icon-bar"></span>
						                        <span class="icon-bar"></span>
						                    </div>
					                    </div>
			            			</div>
		            			<?endif;?>

				            	<div class="col board-contacts <?=$order_contacts?>">

				            		<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>

					            		<div class=
					            		"
					            			row no-gutters align-items-center wrapper-item 

					            		">

					            			<div class="<?=($order_contacts == "order-2")?"col-12":"col-7"?> wrapper-contacts">
					            				<div class="wrapper-board-contact parent-show-board-contact-js">
							                            	
													<?	
														$show_list = false;
														
														$contact_text = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['name'];

														$email_text = 
															"
																<a class='visible-part mail' href='mailto:"
																	.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
																	."'>

																	<span class='bord-bot'>"
																		.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
																	."</span>
												    			</a>
												    		";

												    	$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['desc'];



												    	if( strlen($contact_text) )
												    	{
												    		if(strlen($comment)<=0)
																$comment = $email_text;
												    		
												    		$email_text = "";
												    	}


														if( strlen($comment)<=0 )
															$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['desc'];

													?>

													<?if( strlen($contact_text) ):?>

														<div>

															<div class="visible-part phone">
																
																<?=$contact_text?>

																<div class="ic-open-list-contact show-board-contact-js open-list-contact"><span></span></div>
																	
															</div>

														</div>

													<?endif;?>

													<?if( strlen($email_text) ):?>

														<div>

															<div class='visible-part'>
												    			<?=$email_text?>

												    			<div class="ic-open-list-contact show-board-contact-js"><span></span></div>
												    		</div>

														</div>

													<?endif;?>

													<?if( strlen($comment) ):?>

														<div class='comment'>
															<?=$comment?>
														</div>

													<?endif;?>

													<div class="list-contacts">

												        <table>

												        	<?$flagcallback = true;?>

												        	<?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
												        	
												                <tr>
												                    <td>
												                        <div class="phone"><span ><?=$val['name']?></span></div>
												                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
												                        	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
												                        <?endif;?>
												                    </td>
												                </tr>

												                <?if($key == 0):?>

												                	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]["CALLBACK"]["VALUE"] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
												                   		<tr class="no-border-top">
												                            <td>

													                            <div class="button-wrap">
													                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
													                            </div>
													                        </td>
													                    </tr>
												                    <?endif;?>

												                    <?$flagcallback = false;?>

												                <?endif;?>

												            <?endforeach;?>

												            <?if($flagcallback):?>

												            	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
												               		<tr>
												                        <td>

												                            <div class="button-wrap">
												                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
												                            </div>
												                        </td>
												                    </tr>
												                <?endif;?>

												            <?endif;?>


												            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

												            	<tr>
												                	<td>
												                        <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
												                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
												                        	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
												                        <?endif;?>
												                    </td>
												                </tr>

												            <?endforeach;?>

												            <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE']) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']) ):?>
												           		<tr>
												                    <td>
												                    	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE'])):?>

												                    		<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']?></div>

												                    	<?endif;?>

												                    	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

												                            
												                            <a class="btn-map-ic show-dialog-map"><i class="concept-icon concept-location-5"></i> <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CONTACTS_DIALOG_MAP_BTN_NAME"]?></span></a>
												                        	

												                        <?endif;?>
												                    </td>
												                </tr>
												            <?endif;?>

												            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
												                <tr>
												                    <td>
												                        <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
												                    </td>
												                </tr>
												            
												            <?endif;?>
												                
												        </table>
												    </div>

												</div>
					            			</div>
					            		</div>

				            		<?endif;?>
				            		
				            	</div>

				            	<?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"]{0})):?>
									<div class="col hidden-md text-html <?=$order_contacts?>">

										<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

									</div>
								<?endif;?>

			            	<?endif;?>


			            	<div class="col-md-2 col-4 wrapper-logotype <?=$order_logotype?>">

				    			<div class="row no-gutters align-items-center wrapper-item">
				    				<div class="col">

				    					<?if(!$bIsMainPage):?>
			                                <a href="<?=SITE_DIR?>" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["LOGO_TOMAIN_TITLE"]?>">
			                            <?endif;?>

				            				<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
			            				
			            				<?if(!$bIsMainPage):?>
			                            	</a>
			                            <?endif;;?>
				    					
				    				</div>
				    			</div>

			            	</div>


			            	<div class="col-5 board-info order-last">
			            		<div class="row no-gutters align-items-center wrapper-item">


			            			<div class="col-xl-6 col-4 wrapper-cabinet">

			            				<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("cabinet-1");?>

				            				<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"):?>

				            					<?=CPhoenix::ShowCabinetLink();?>

				        					<?endif;?>

			        					<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("cabinet-1");?>


			            			</div>

				            		<div class="col-xl-6 col-8 row no-gutters justify-content-end counts-board">

				            			<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>

					            			<div class="col-4">
						            			<div class="basket-quantity-info-icon cart count-basket-items-parent"><span class="count count-basket">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a></div>
						            		</div>

					            		<?endif;?>

					            		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

						            		<div class="col-4">
						        				<div class="basket-quantity-info-icon delay count-delay-parent"><span class="count count-delay">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"]?>"></a></div>
					        				</div>

				        				<?endif;?>

				        				<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

					        				<div class="col-4">
						        				<div class="basket-quantity-info-icon compare count-compare-parent"><span class="count count-compare">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"]?>"></a></div>
						            		</div>

					            		<?endif;?>
				            		</div>
			            		</div>
			            	</div>
			            </div>
		            </div>

		        </div>

		        <?

			        if($PHOENIX_MENU>0 && ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] == "on_board" || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] == "on_line"))
			        {
			        	
			        	$APPLICATION->IncludeComponent(
							"concept:phoenix.menu",
							".default",
							Array(
								"COMPONENT_TEMPLATE" => ".default",
								"COMPOSITE_FRAME_MODE" => "N",
								"CACHE_TIME" => "36000000",
								"CACHE_TYPE" => "A",
								"CACHE_USE_GROUPS" => "Y"
							)
						);
			        }
				?>

		            
		    </div>


		    <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"] == "fixed" ):?>



			    <div class="fix-board hidden-sm hidden-xs">

			    	<?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_FIXED_VIEW"]["VALUE"] == "view-open"):?>

				    	<div class="container">

			    			<div class="wrapper-head-top d-none d-sm-block">
			    				<div class="row align-items-center wrapper-item">
				            		<div class="col-xl-3 col-lg-4 col-md-5">

										<div class=
										"
										row no-gutters align-items-center wrapper-item 

										<?if($PHOENIX_MENU>0):?>

											menu-width

										<?endif;?>
										">

											<?if($PHOENIX_MENU>0):?>
												<div class="col-auto wrapper-menu">
													<div class="wrapper-icon-hamburger open-main-menu">
											            <div class="icon-hamburger">
											                <span class="icon-bar"></span>
											                <span class="icon-bar"></span>
											                <span class="icon-bar"></span>
											            </div>
											        </div>
												</div>
											<?endif;?>

											<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>

												<div class="col-11 wrapper-contacts">
													<div class="wrapper-board-contact parent-show-board-contact-js">
												            	
														<?$show_list = false;?>

														<?
															
															$contact_text = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['name'];

															$email_text = 
																"
																	<a class='visible-part mail' href='mailto:"
												    					.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
												    					."'>

												    					<span class='bord-bot'>"
												    						.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
												    					."</span>
												        			</a>
												        		";

												        	$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['desc'];



												        	if( strlen($contact_text) )
												        	{
												        		if(strlen($comment)<=0)
																	$comment = $email_text;
												        		
												        		$email_text = "";
												        	}


															if( strlen($comment)<=0 )
																$comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['desc'];

														?>

														<?if( strlen($contact_text) ):?>

															<div>

												    			<div class="visible-part phone">
												    				
												    				<?=$contact_text?>

												    				<div class="ic-open-list-contact show-board-contact-js"><span></span></div>
												    					
																</div>

															</div>

														<?endif;?>

														<?if( strlen($email_text) ):?>

															<div>

												    			<div class='visible-part'>
												        			<?=$email_text?>

												        			<div class="ic-open-list-contact show-board-contact-js"><span></span></div>
												        		</div>

												    		</div>

														<?endif;?>

														<?if( strlen($comment) ):?>

															<div class='comment'>
												    			<?=$comment?>
												    		</div>

														<?endif;?>

														<div class="list-contacts">
												            <table>

												            	<?$flagcallback = true;?>

												            	<?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
												            	
												                    <tr>
												                        <td>
												                            <div class="phone"><span ><?=$val['name']?></span></div>
												                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
												                            	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
												                            <?endif;?>
												                        </td>
												                    </tr>

												                    <?if($key==0):?>

												                    	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
								                                       		<tr class="no-border-top">
								                                                <td>

														                            <div class="button-wrap">
														                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
														                            </div>
														                        </td>
														                    </tr>
												                        <?endif;?>

												                        <?$flagcallback = false;?>

												                    <?endif;?>

												                <?endforeach;?>


												                <?if($flagcallback):?>

											                    	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
							                                       		<tr>
							                                                <td>

													                            <div class="button-wrap">
													                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
													                            </div>
													                        </td>
													                    </tr>
											                        <?endif;?>

											                    <?endif;?>


												                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

												                	<tr>
												                    	<td>
												                            <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
												                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
												                            	<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
												                            <?endif;?>
												                        </td>
												                    </tr>

												                <?endforeach;?>

												                <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE']) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']) ):?>
								                               		<tr>
								                                        <td>
								                                        	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE'])):?>

								                                        		<div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']?></div>

								                                        	<?endif;?>

								                                        	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

													                            <a class="btn-map-ic show-dialog-map"><i class="concept-icon concept-location-5"></i> <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CONTACTS_DIALOG_MAP_BTN_NAME"]?></span></a>

												                            <?endif;?>
												                        </td>
												                    </tr>
										                        <?endif;?>

												                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
												                    <tr>
												                        <td>
												                            <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
												                        </td>
												                    </tr>
												                
												                <?endif;?>
												                    
												            </table>
												        </div>

												       

													</div>
												</div>

											<?endif;?>



										</div>

									</div>

									<?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"]{0})):?>
										<div class="col hidden-md text-html">

											<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

										</div>
									<?endif;?>


									<div class="col hidden-md">

										<?
					            			if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU'] == "Y" )
					            				$APPLICATION->IncludeComponent("concept:phoenix.search.line", "fix-header",
					            					Array(
								            		"CONTAINER_ID" => "search-page-input-container-fix-header",
								            		"INPUT_ID" => "search-page-input-fix-header",
								            		"COMPOSITE_FRAME_MODE" => "N",
								            		"SHOW_RESULTS" => "",
								            		"SEARCH_CONTAINER_WIDTH" => "half-width"
								            	)

					            			);
					            		?>

									</div>

									<div class="col-2 d-lg-none wrapper-logotype">
										<?if(!$bIsMainPage):?>
			                                <a href="<?=SITE_DIR?>" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["LOGO_TOMAIN_TITLE"]?>">
			                            <?endif;?>

				            				<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
			            				
			            				<?if(!$bIsMainPage):?>
			                            	</a>
			                            <?endif;;?>
									</div>


									<div class="col-xl-5 col-lg-4 col-5">
										<div class="row no-gutters align-items-center wrapper-item">
											
											<div class="col-xl-6 col-2 wrapper-cabinet">

												<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("cabinet-2");?>

													<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"):?>

													<?=CPhoenix::ShowCabinetLink();?>

													<?endif;?>

													<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("cabinet-2");?>
												
											</div>
											

											<div class="col-xl-6 col-10 row no-gutters cart-delay-compare justify-content-end">

						            			<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>

							            			<div class="col-4">
								            			<div class="basket-quantity-info-icon cart count-basket-items-parent"><span class="count count-basket">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a></div>
								            		</div>

							            		<?endif;?>

							            		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

								            		<div class="col-4">
								        				<div class="basket-quantity-info-icon delay count-delay-parent"><span class="count count-delay">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"]?>"></a></div>
							        				</div>

						        				<?endif;?>

						        				<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

							        				<div class="col-4">
								        				<div class="basket-quantity-info-icon compare count-compare-parent"><span class="count count-compare">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"]?>"></a></div>
								            		</div>

								            	<?endif;?>
						            		</div>

										</div>
									</div>


				            	</div>
				    		</div>
				    	</div>

			    	<?//endif;?>

			    </div>

			<?endif;?>

			<div class="mobile-menu d-md-none">

				<div class="container">
					<div class="in-mobile-menu row align-items-center justify-content-between">

						<div class="col-auto <?if($PHOENIX_MENU>0):?>open-main-menu<?endif;?> item">
							<div class="wr-btns">
								
								<?if($PHOENIX_MENU>0):?>
									<div class="icon-hamburger">
								        <span class="icon-bar"></span>
								        <span class="icon-bar"></span>
								        <span class="icon-bar"></span>
								    </div>
							    <?endif;?>
							</div>
							
						</div>

						<div class="col-auto item">
							<div class="wr-btns">
								
								<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
									<a class="ic-callback-mob common-svg-style open_modal_contacts">
									</a>
								<?endif?>

							</div>
						</div>

						<div class="col item">

	    					<?if(!$bIsMainPage):?>
	                            <a href="<?=SITE_DIR?>">
	                        <?endif;?>

	                        	<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
	        				
	        				<?if(!$bIsMainPage):?>
	                        	</a>
	                        <?endif;;?>
				    		
						</div>

						<div class="col-auto item">
							<div class="wr-btns">
								<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y"):?>

								<div class="mini-search-style mob open-search-top common-svg-style"></div>

								<?endif;?>

							</div>

						</div>

						<div class="col-auto item">
							<div class="wr-btns">
								<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>
									<div class="ic-cart-mob count-basket-items-parent common-svg-style">
										<span class="count-basket"></span>
										<a class="url-basket" href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a>
									</div>
								<?endif;?>


							</div>
						</div>

					</div>
				</div>

			</div>
		    
		</header>