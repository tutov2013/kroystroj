<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }

use Bitrix\Main\Localization\Loc;
use Yandex\Market;

$hasFewShipments = (count($arResult['SHIPMENT']) > 1);

if ($arResult['SHIPMENT_EDIT'])
{
	echo BeginNote('style="max-width: 550px;"');
	echo Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENT_EDIT_NOTE');
	echo EndNote();

	Market\Ui\Assets::loadPlugins([
		'OrderView.BoxItem',
		'OrderView.BoxItemCollection',
		'OrderView.BoxSize',
		'OrderView.BoxSizeSummary',
		'OrderView.Box',
		'OrderView.BoxCollection',
		'OrderView.Shipment',
		'OrderView.ShipmentCollection',
		'OrderView.ShipmentSubmit'
	]);

	Market\Ui\Assets::loadMessages([
		'T_TRADING_ORDER_VIEW_BOX_SIZE_MODAL_TITLE',
		'T_TRADING_ORDER_VIEW_SHIPMENT_SUBMIT_FAIL',
		'T_TRADING_ORDER_VIEW_SHIPMENT_SUBMIT_DATA_INVALID',
	]);
}

if (!empty($arResult['PRINT_DOCUMENTS']))
{
	Market\Ui\Assets::loadPlugins([
		'lib.printdialog',
		'OrderView.ShipmentPrint',
	]);

	Market\Ui\Assets::loadMessages([
		'PRINT_DIALOG_SUBMIT',
	]);
}

$baseInputName = 'YAMARKET_SHIPMENT';
$shipmentIndex = 0;

?>
<div
	class="<?= $arResult['SHIPMENT_EDIT'] ? 'js-plugin' : ''; ?>"
	data-plugin="OrderView.ShipmentCollection"
	data-base-name="<?= $baseInputName; ?>"
	id="YAMARKET_SHIPMENT_COLLECTION"
>
	<h2 class="yamarket-shipments-title"><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENTS_TITLE'); ?></h2>
	<?php
	foreach ($arResult['SHIPMENT'] as $shipment)
	{
		$shipmentInputName = $baseInputName . '[' . $shipmentIndex . ']';
		$boxIndex = 0;
		$isBoxesEmpty = empty($shipment['BOX']);
		$boxesIterator = $isBoxesEmpty ? [] : (array)$shipment['BOX'];

		if ($isBoxesEmpty && $arResult['SHIPMENT_EDIT'])
		{
			$boxesIterator[] = [
				'PLACEHOLDER' => true,
			];
		}

		?>
		<div class="js-yamarket-shipment" data-plugin="OrderView.Shipment" data-id="<?= $shipment['ID']; ?>">
			<?php
			if ($arResult['SHIPMENT_EDIT'])
			{
				$shipmentInputs = [
					'ID' => $shipment['ID'],
					'SETUP_ID' => $arResult['SETUP_ID'],
					'ORDER_ID' => $arResult['ORDER_EXTERNAL_ID'],
					'ORDER_NUM' => $arResult['ORDER_ACCOUNT_NUMBER'],
				];

				foreach ($shipmentInputs as $inputName => $inputValue)
				{
					?>
					<input
						class="js-yamarket-shipment__input"
						type="hidden" name="<?= $shipmentInputName . '[' . $inputName . ']'; ?>"
						value="<?= htmlspecialcharsbx($inputValue); ?>"
						data-name="<?= $inputName; ?>"
					/>
					<?php
				}
			}

			if ($hasFewShipments)
			{
				?>
				<h3 class="yamarket-shipment-title"><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENT', [ '#ID#' => $shipment['ID'] ]); ?></h3>
				<?
			}
			?>
			<div class="yamarket-box-collection adm-s-order-table-ddi js-yamarket-shipment__child" data-plugin="OrderView.BoxCollection" data-name="BOX">
				<?php
				foreach ($boxesIterator as $box)
				{
					$boxInputName = $shipmentInputName . '[BOX][' . $boxIndex . ']';

					include __DIR__ . '/box.php';

					++$boxIndex;
				}
				?>
			</div>
			<div class="yamarket-shipment-submit <?= $isBoxesEmpty ? 'is--hidden' : ''; ?> js-yamarket-shipment__actions">
				<?php
				if (!empty($arResult['PRINT_DOCUMENTS']))
				{
					$printItems = Market\Utils::jsonEncode($arResult['PRINT_DOCUMENTS'], JSON_UNESCAPED_UNICODE);
					$printUrl = Market\Ui\Admin\Path::getModuleUrl('trading_order_print', [
						'view' => 'dialog',
						'setup' => $arResult['SETUP_ID'],
						'id' => $arResult['ORDER_EXTERNAL_ID'],
						'shipment' => $shipment['ID'],
					]);

					?>
					<button
						class="yamarket-shipment-submit__secondary yamarket-btn adm-btn adm-btn-menu <?= $arResult['PRINT_READY'] ? '' : 'is--hidden'; ?> js-plugin"
						type="button"
						data-plugin="OrderView.ShipmentPrint"
						data-items="<?= htmlspecialcharsbx($printItems); ?>"
						data-url="<?= htmlspecialcharsbx($printUrl); ?>"
					><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENT_PRINT'); ?></button>
					<?php
				}

				if ($arResult['SHIPMENT_EDIT'])
				{
					?>
					<input
						class="yamarket-shipment-submit__primary adm-btn-green js-plugin-click"
						type="button"
						value="<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENT_SUBMIT'); ?>"
						data-plugin="OrderView.ShipmentSubmit"
					/>
					<div class="yamarket-shipment-submit__result js-yamarket-shipment-submit__message"></div>
					<?php
				}
				?>
			</div>
		</div>
		<?php

		++$shipmentIndex;
	}
	?>
</div>