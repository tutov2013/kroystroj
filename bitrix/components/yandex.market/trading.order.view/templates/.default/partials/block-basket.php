<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }

use Bitrix\Main\Localization\Loc;
use Yandex\Market;

if (empty($arResult['BASKET']['ITEMS'])) { return; }

if ($arResult['SHIPMENT_EDIT'])
{
	Market\Ui\Assets::loadPlugins([
		'OrderView.BasketItem',
		'OrderView.Basket',
	]);

	Market\Ui\Assets::loadMessages([
		'T_TRADING_ORDER_VIEW_BOX_OPTION',
		'T_TRADING_ORDER_VIEW_BOX_NOT_FOUND',
	]);
}

$columnsCount = count($arResult['BASKET']['COLUMNS']) + 1;

?>
<h2><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BASKET_TITLE'); ?></h2>
<div class="adm-s-order-table-ddi <?= $arResult['SHIPMENT_EDIT'] ? 'js-plugin' : ''; ?>" data-plugin="OrderView.Basket">
	<table class="yamarket-basket-table adm-s-order-table-ddi-table adm-s-bus-ordertable-option" style="width: 100%;">
		<thead>
			<tr>
				<td class="tal"><?php
					if ($arResult['SHIPMENT_EDIT'])
					{
						?>
						<input class="adm-designed-checkbox js-yamarket-basket__check" type="checkbox" id="YAMARKET_BASKET_SELECT_ALL" />
						<label class="adm-designed-checkbox-label" for="YAMARKET_BASKET_SELECT_ALL"></label>
						<?php
					}
					else
					{
						echo Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BASKET_ITEM_INDEX');
					}
				?></td>
				<?php
				foreach ($arResult['BASKET']['COLUMNS'] as $columnTitle)
				{
					?>
					<td class="tal"><?= $columnTitle; ?></td>
					<?
				}
				?>
			</tr>
		</thead>
		<tbody>
			<tr></tr><?php // hack for bitrix css ?>
			<?php
			$basketIndex = 0;

			foreach ($arResult['BASKET']['ITEMS'] as $item)
			{
				?>
				<tr class="bdb-line js-yamarket-basket-item" data-plugin="OrderView.BasketItem" data-id="<?= $item['ID']; ?>">
					<td class="tal"><?php
						if ($arResult['SHIPMENT_EDIT'])
						{
							?>
							<input class="js-yamarket-basket-item__data" type="hidden" value="<?= $item['ID']; ?>" data-name="ID" />
							<input class="adm-designed-checkbox js-yamarket-basket-item__check" type="checkbox" id="YAMARKET_BASKET_SELECT_<?= $basketIndex; ?>" />
							<label class="adm-designed-checkbox-label" for="YAMARKET_BASKET_SELECT_<?= $basketIndex; ?>"></label>
							<?php
						}
						else
						{
							echo $item['INDEX'];
						}
					?></td>
					<?
					foreach ($arResult['BASKET']['COLUMNS'] as $column => $columnTitle)
					{
						$columnValue = isset($item[$column]) ? $item[$column] : null;
						$columnFormattedKey = $column . '_FORMATTED';

						if (isset($item[$columnFormattedKey]))
						{
							$columnFormatted = $item[$columnFormattedKey];
						}
						else if ($columnValue !== null)
						{
							$columnFormatted = $columnValue;
						}
						else
						{
							$columnFormatted = '&mdash;';
						}

						switch ($column)
						{
							case 'COUNT':
							case 'BOX_COUNT':
								?>
								<td class="tal for--<?= strtolower($column); ?>">
									<span class="js-yamarket-basket-item__data" data-name="<?= $column; ?>"><?= (float)$columnValue; ?></span>
									<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BASKET_ITEM_MEASURE'); ?>
								</td>
								<?php
							break;

							default:
								?>
								<td class="tal for--<?= strtolower($column); ?> js-yamarket-basket-item__data" data-name="<?= $column; ?>"><?= $columnFormatted; ?></td>
								<?php
							break;
						}
					}
					?>
				</tr>
				<?php

				++$basketIndex;
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="tal" colspan="<?= $columnsCount - 3; ?>"><?php
					if ($arResult['SHIPMENT_EDIT'])
					{
						?>
						<span class="yamarket-basket-box-select adm-select-wrap">
							<select class="adm-select js-yamarket-basket__box-select">
								<option value=""><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_CHOOSE'); ?></option>
								<?php
								$isOptgroupStarted = false;
								$hasFewShipments = count($arResult['SHIPMENT']) > 1;

								foreach ($arResult['SHIPMENT'] as $shipment)
								{
									$boxIndex = 0;

									if ($hasFewShipments)
									{
										if ($isOptgroupStarted)
										{
											echo '</optgroup>';
										}

										$isOptgroupStarted = true;
										?>
										<optgroup
											label="<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_SHIPMENT', [ '#ID#' => $shipment['ID'] ]); ?>"
											data-id="<?= $shipment['ID']; ?>"
										>
										<?php
									}

									?>
									<option
										value="new"
										data-shipment="<?= $shipment['ID']; ?>"
									><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_NEW'); ?></option>
									<?php

									foreach ($shipment['BOX'] as $box)
									{
										?>
										<option
											value="<?= $boxIndex; ?>"
											data-shipment="<?= $shipment['ID']; ?>"
										><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_OPTION', [ '#NUMBER#' => $box['NUMBER'] ]); ?></option>
										<?php

										++$boxIndex;
									}
								}

								if ($isOptgroupStarted)
								{
									echo '</optgroup>';
								}
								?>
							</select>
						</span>
						<span
							class="yamarket-basket-box-add adm-btn adm-btn-green adm-btn-add adm-btn-disabled js-yamarket-basket__box-add"
							tabindex="0"
						><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_ADD'); ?></span>
						<?
					}
				?></td>
				<td class="yamarket-basket-summary" colspan="3">
					<?php
					if (!empty($arResult['BASKET']['SUMMARY']))
					{
						$isFirstSummaryItem = true;

						foreach ($arResult['BASKET']['SUMMARY'] as $summaryItem)
						{
							echo $isFirstSummaryItem ? '' : '<br />';
							echo $summaryItem['NAME'] . ': ' . $summaryItem['VALUE'];

							$isFirstSummaryItem = false;
						}
					}
					?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>