<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR.'auth/');



global $PHOENIX_TEMPLATE_ARRAY;

/*
if ($arParams['SHOW_PRIVATE_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}


if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PRIVATE"));
if ($arParams['SET_TITLE'] == 'Y')
{
	$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));
}
*/

require("include/_head.php");

?>
<div class="cabinet-wrap private">
	<div class="container">
		<div class="block-move-to-up">

			<div class="row">
				<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
					<?CPhoenix::ShowCabinetMenu()?>
				</div>

				<div class="<?$APPLICATION->ShowViewContent('class-personal-menu-content');?> col-12 pad_top_container">
			
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.profile",
						"main",
						Array(
							"SET_TITLE" =>$arParams["SET_TITLE"],
							"AJAX_MODE" => $arParams['AJAX_MODE_PRIVATE'],
							"SEND_INFO" => $arParams["SEND_INFO_PRIVATE"],
							"CHECK_RIGHTS" => $arParams['CHECK_RIGHTS_PRIVATE'],
							"EDITABLE_EXTERNAL_AUTH_ID" => $arParams['EDITABLE_EXTERNAL_AUTH_ID'],
						),
						$component
					);?>

				</div>

				<?$APPLICATION->ShowViewContent('banners-personal-content');?>
			</div>
		</div>
	</div>
</div>