<?php

namespace Yandex\Market\Components;

use Bitrix\Main;
use Yandex\Market;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class TradingOrderView extends \CBitrixComponent
{
	protected $setup;

	public function onPrepareComponentParams($params)
	{
		$params['ALLOW_EDIT'] = (bool)$params['ALLOW_EDIT'];
		$params['EXTERNAL_ID'] = trim($params['EXTERNAL_ID']);
		$params['SERVICE_CODE'] = trim($params['SERVICE_CODE']);
		$params['SITE_ID'] = trim($params['SITE_ID']);

		return $params;
	}

	public function executeComponent()
	{
		$templatePage = '';

		try
		{
			$this->loadModules();

			$orderExternalId = $this->getOrderExternalId();
			$orderNum = $this->getOrderNum($orderExternalId);
			$response = $this->runAction($orderExternalId, $orderNum);

			$this->buildResult($response);
			$this->extendResult($orderExternalId, $orderNum);
		}
		catch (Main\SystemException $exception)
		{
			$this->arResult['ERROR'] = $exception->getMessage();
			$templatePage = 'exception';
		}

		$this->includeComponentTemplate($templatePage);
	}

	protected function getSetup()
	{
		if ($this->setup === null)
		{
			$this->setup = $this->loadSetup();
		}

		return $this->setup;
	}

	protected function loadSetup()
	{
		$siteId = $this->getParameterSiteId();
		$serviceCode = $this->getParameterServiceCode();

		return Market\Trading\Setup\Model::loadByServiceAndSite($serviceCode, $siteId);
	}

	protected function loadModules()
	{
		$requiredModules = $this->getRequiredModules();

		foreach ($requiredModules as $requiredModule)
		{
			if (!Main\Loader::includeModule($requiredModule))
			{
				$message = $this->getLang('MODULE_NOT_INSTALLED', [ '#MODULE_ID#' => $requiredModule ]);

				throw new Main\SystemException($message);
			}
		}
	}

	protected function getRequiredModules()
	{
		return [
			'yandex.market',
		];
	}

	protected function getParameterServiceCode()
	{
		return $this->getParameter('SERVICE_CODE');
	}

	protected function getParameterSiteId()
	{
		return $this->getParameter('SITE_ID');
	}

	protected function getParameter($key)
	{
		$result = (string)$this->arParams[$key];

		if ($result === '')
		{
			$message = $this->getLang('PARAMETER_' . $key . '_REQUIRED');
			throw new Main\ArgumentException($message);
		}

		return $result;
	}

	protected function getLang($code, $replace = null, $language = null)
	{
		return Main\Localization\Loc::getMessage('YANDEX_MARKET_TRADING_ORDER_VIEW_' . $code, $replace, $language);
	}

	protected function getOrderExternalId()
	{
		return (int)$this->getParameter('EXTERNAL_ID');
	}

	protected function getOrderNum($externalId)
	{
		$platform = $this->getSetup()->getPlatform();
		$registry = $this->getSetup()->getEnvironment()->getOrderRegistry();
		$result = $registry->search($externalId, $platform);

		if ($result === null)
		{
			$message = $this->getLang('ORDER_NOT_REGISTERED', [ '#EXTERNAL_ID#' => $externalId ]);
			throw new Main\SystemException($message);
		}

		return $result;
	}

	protected function runAction($externalId, $orderNum)
	{
		$setup = $this->getSetup();
		$procedure = new Market\Trading\Procedure\Runner(
			Market\Trading\Entity\Registry::ENTITY_TYPE_ORDER,
			$orderNum
		);

		return $procedure->run($setup, 'admin/view', [
			'id' => $externalId,
			'flushCache' => true,
			'useCache' => true,
		]);
	}

	protected function getBoxDimensions()
	{
		return [
			'WEIGHT' => [
				'NAME' => $this->getLang('DIMENSION_WEIGHT'),
				'UNIT' => Market\Data\Weight::UNIT_GRAM,
			],
			'WIDTH' => [
				'NAME' => $this->getLang('DIMENSION_WIDTH'),
				'UNIT' => Market\Data\Size::UNIT_CENTIMETER,
			],
			'HEIGHT' => [
				'NAME' => $this->getLang('DIMENSION_HEIGHT'),
				'UNIT' => Market\Data\Size::UNIT_CENTIMETER,
			],
			'DEPTH' => [
				'NAME' => $this->getLang('DIMENSION_DEPTH'),
				'UNIT' => Market\Data\Size::UNIT_CENTIMETER,
			],
		];
	}

	protected function buildResult(Market\Trading\Service\Reference\Action\Response $response)
	{
		$this->arResult = [
			'PROPERTIES' => $response->getField('properties'),
			'BASKET' => [
				'COLUMNS' => $response->getField('basket.columns'),
				'ITEMS' => $response->getField('basket.items'),
				'SUMMARY' => $response->getField('basket.summary'),
			],
			'SHIPMENT' => $response->getField('shipments'),
			'SHIPMENT_EDIT' => (bool)$response->getField('shipmentEdit'),
			'PRINT_READY' => (bool)$response->getField('printReady'),
		];
	}

	protected function extendResult($orderExternalId, $orderNum)
	{
		$this->fillCommonData($orderExternalId, $orderNum);
		$this->fillBoxDimensions();
		$this->fillBasketItemsIndex();
		$this->fillBasketItemsBoxCount();
		$this->fillBoxNumber($orderExternalId);
		$this->convertBoxDimensions();
		$this->fillBoxItemsFromBasket();
		$this->resolveShipmentEdit();
		$this->fillPrintDocuments();
	}

	protected function fillCommonData($orderExternalId, $orderNum)
	{
		$this->arResult['SETUP_ID'] = $this->getSetup()->getId();
		$this->arResult['ORDER_EXTERNAL_ID'] = $orderExternalId;
		$this->arResult['ORDER_ACCOUNT_NUMBER'] = $orderNum;
	}

	protected function fillBoxDimensions()
	{
		$this->arResult['BOX_DIMENSIONS'] = $this->getBoxDimensions();
	}

	protected function fillBoxNumber($orderId)
	{
		if (empty($this->arResult['SHIPMENT'])) { return; }

		$boxNumber = 1;

		foreach ($this->arResult['SHIPMENT'] as &$shipment)
		{
			foreach ($shipment['BOX'] as &$box)
			{
				$box['NUMBER'] = $boxNumber;
				$box['FULFILMENT_ID'] = $orderId . '-' . $boxNumber;

				++$boxNumber;
			}
			unset($box);
		}
		unset($shipment);
	}

	protected function fillBasketItemsIndex()
	{
		if (empty($this->arResult['BASKET']['ITEMS'])) { return; }

		$basketItemIndex = 0;

		foreach ($this->arResult['BASKET']['ITEMS'] as &$basketItem)
		{
			if (isset($basketItem['INDEX']))
			{
				$basketItemIndex = max($basketItemIndex, $basketItem['INDEX'] + 1);
			}
			else
			{
				++$basketItemIndex;
				$basketItem['INDEX'] = $basketItemIndex;
			}
		}
		unset($basketItem);
	}

	protected function fillBasketItemsBoxCount()
	{
		if (empty($this->arResult['BASKET']['ITEMS']) || empty($this->arResult['SHIPMENT'])) { return; }

		$itemsIds = array_map(static function($item) { return $item['ID']; }, $this->arResult['BASKET']['ITEMS']);
		$itemsMap = array_flip($itemsIds);

		foreach ($this->arResult['SHIPMENT'] as &$shipment)
		{
			foreach ($shipment['BOX'] as &$box)
			{
				foreach ($box['ITEMS'] as &$boxItem)
				{
					if (!isset($itemsMap[$boxItem['ID']])) { continue; }

					$itemKey = $itemsMap[$boxItem['ID']];
					$item = &$this->arResult['BASKET']['ITEMS'][$itemKey];

					if (!isset($item['BOX_COUNT']))
					{
						$item['BOX_COUNT'] = 0;
					}

					$item['BOX_COUNT'] += $boxItem['COUNT'];

					unset($item);
				}
				unset($boxItem);
			}
			unset($box);
		}
		unset($shipment);
	}

	protected function fillBoxItemsFromBasket()
	{
		if (empty($this->arResult['BASKET']['ITEMS']) || empty($this->arResult['SHIPMENT'])) { return; }

		$itemsIds = array_map(static function($item) { return $item['ID']; }, $this->arResult['BASKET']['ITEMS']);
		$itemsMap = array_flip($itemsIds);

		foreach ($this->arResult['SHIPMENT'] as &$shipment)
		{
			foreach ($shipment['BOX'] as &$box)
			{
				foreach ($box['ITEMS'] as &$boxItem)
				{
					if (!isset($itemsMap[$boxItem['ID']])) { continue; }

					$itemKey = $itemsMap[$boxItem['ID']];
					$item = $this->arResult['BASKET']['ITEMS'][$itemKey];
					$itemSlice = array_intersect_key($item, [
						'INDEX' => true,
						'NAME' => true,
						'PRICE' => true,
						'PRICE_FORMATTED' => true,
					]);

					if (isset($item['COUNT']))
					{
						$itemSlice['COUNT_TOTAL'] = $item['COUNT'];
					}

					$boxItem += $itemSlice;
				}
				unset($boxItem);
			}
			unset($box);
		}
		unset($shipment);
	}

	protected function convertBoxDimensions()
	{
		if (empty($this->arResult['SHIPMENT']) || empty($this->arResult['BOX_DIMENSIONS'])) { return; }

		foreach ($this->arResult['SHIPMENT'] as &$shipment)
		{
			foreach ($shipment['BOX'] as &$box)
			{
				foreach ($this->arResult['BOX_DIMENSIONS'] as $dimensionName => $dimensionDescription)
				{
					if (!isset($box['DIMENSIONS'][$dimensionName])) { continue; }

					$boxDimension = &$box['DIMENSIONS'][$dimensionName];

					if (
						(string)$boxDimension['VALUE'] !== ''
						&& $boxDimension['UNIT'] !== $dimensionDescription['UNIT']
					)
					{
						$boxDimension['VALUE'] = $this->convertDimension(
							$dimensionName,
							$boxDimension['VALUE'],
							$boxDimension['UNIT'],
							$dimensionDescription['UNIT']
						);
						$boxDimension['UNIT'] = $dimensionDescription['UNIT'];
					}

					unset($boxDimension);
				}
			}
			unset($box);
		}
		unset($shipment);
	}

	protected function convertDimension($dimension, $value, $fromUnit, $toUnit)
	{
		if ($dimension === 'WEIGHT')
		{
			$result = Market\Data\Weight::convertUnit($value, $fromUnit, $toUnit);
		}
		else
		{
			$result = Market\Data\Size::convertUnit($value, $fromUnit, $toUnit);
		}

		return $result;
	}

	protected function resolveShipmentEdit()
	{
		if (empty($this->arResult['SHIPMENT']) || !$this->arParams['ALLOW_EDIT'])
		{
			$this->arResult['SHIPMENT_EDIT'] = false;
		}
	}

	protected function fillPrintDocuments()
	{
		if (!$this->arResult['SHIPMENT_EDIT'] && !$this->arResult['PRINT_READY']) { return; }

		$printer = $this->getSetup()->getService()->getPrinter();
		$documents = [];

		foreach ($printer->getTypes() as $type)
		{
			$document = $printer->getDocument($type);

			$documents[] = [
				'TYPE' => $type,
				'TITLE' => $document->getTitle(),
			];
		}

		$this->arResult['PRINT_DOCUMENTS'] = $documents;
	}
}