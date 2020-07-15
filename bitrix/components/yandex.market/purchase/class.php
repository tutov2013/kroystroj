<?php

namespace Yandex\Market\Components;

use Yandex\Market;
use Bitrix\Main;

class Purchase extends \CBitrixComponent
{
	const HTTP_STATUS_200 = '200 OK';
	const HTTP_STATUS_400 = '400 Bad Request';
	const HTTP_STATUS_403 = '403 Forbidden';
	const HTTP_STATUS_500 = '500 Internal Server Error';

	public function onPrepareComponentParams($params)
	{
		$params['SEF_FOLDER'] = trim($params['SEF_FOLDER']);
		$params['SERVICE_CODE'] = trim($params['SERVICE_CODE']);
		$params['SITE_ID'] = trim($params['SITE_ID']);

		return $params;
	}

	public function executeComponent()
	{
		$logger = null;
		$routePath = null;

		try
		{
			$this->loadModules();
			$this->parseRequest();

			$routePath = $this->getRequestPath();
			$setup = $this->getSetup();
			$service = $setup->wakeupService();
			$logger = $service->getLogger();
			$environment = $setup->getEnvironment();
			$router = $service->getRouter();
			$action = $router->getHttpAction($routePath, $environment, $this->request, $this->getServer());

			$logger->log(Market\Logger\Level::DEBUG, $action->getRequest()->getRaw(), [
				'URL' => $routePath,
				'AUDIT' => Market\Logger\Trading\Audit::INCOMING_REQUEST,
			]);

			$action->checkAuthorization();
			$action->process();

			$status = static::HTTP_STATUS_200;
			$response = $action->getResponse()->getRaw();

			$logger->log(Market\Logger\Level::DEBUG, $response, [
				'URL' => $routePath,
				'AUDIT' => Market\Logger\Trading\Audit::INCOMING_RESPONSE,
			]);
		}
		catch (\Exception $exception)
		{
			list($status, $response) = $this->processException($exception, $logger, $routePath);
		}
		catch (\Throwable $exception)
		{
			list($status, $response) = $this->processException($exception, $logger, $routePath);
		}

		$this->sendResponse($response, $status);
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

	protected function parseRequest()
	{
		$this->request->addFilter(new Market\Api\Incoming\JsonBodyFilter());
	}

	protected function getServer()
	{
		return Main\Context::getCurrent()->getServer();
	}

	protected function processException($exception, Market\Psr\Log\LoggerInterface $logger = null, $routePath = null)
	{
		$status = $this->getExceptionStatus($exception);
		$response = $this->getExceptionResponse($exception);

		$this->logException($exception, $logger, $routePath);

		return [$status, $response];
	}

	protected function getExceptionStatus($exception)
	{
		if ($exception instanceof Market\Exceptions\Trading\AccessDenied)
		{
			$result = static::HTTP_STATUS_403;
		}
		else if (
			$exception instanceof Market\Exceptions\Api\InvalidOperation
			|| $exception instanceof Market\Exceptions\Trading\NotImplementedAction
		)
		{
			$result = static::HTTP_STATUS_400;
		}
		else if ($exception instanceof Market\Exceptions\Trading\NotRecoverable)
		{
			$result = static::HTTP_STATUS_200;
		}
		else
		{
			$result = static::HTTP_STATUS_500;
		}

		return $result;
	}

	/**
	 * @param \Throwable|\Exception $exception
	 * @param Market\Psr\Log\LoggerInterface|null $logger
	 * @param string|null $routePath
	 */
	protected function logException($exception, Market\Psr\Log\LoggerInterface $logger = null, $routePath = null)
	{
		if ($logger !== null)
		{
			$logger->log(Market\Logger\Level::ERROR, $exception, [
				'URL' => $routePath ?: '',
				'AUDIT' => Market\Logger\Trading\Audit::INCOMING_RESPONSE,
			]);
		}
	}

	/**
	 * @param \Throwable|\Exception $exception
	 *
	 * @return array
	 */
	protected function getExceptionResponse($exception)
	{
		return [
			'error' => $exception->getMessage()
		];
	}

	protected function sendResponse($response, $status)
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();
		\CHTTP::SetStatus($status);

		if ($response !== null)
		{
			$marker = Market\Api\Marker::getHeader();
			$responseEncoded = is_array($response) ? Main\Web\Json::encode($response) : $response;

			header('Content-Type: application/json');
			header(implode(': ', $marker));
			echo $responseEncoded;
		}

		die();
	}

	protected function getRequestPath()
	{
		$path = $this->request->getRequestedPage();
		$path = $this->normalizeDirectory($path);
		$folder = $this->getParameterSefFolder();
		$folder = $this->normalizeDirectory($folder);

		if (strpos($path, $folder) !== 0)
		{
			throw new Main\SystemException($this->getLang('REQUEST_URL_OUTSIDE_SEF_FOLDER'));
		}

		return substr($path, strlen($folder) + 1); // remove folder and first slash
	}

	protected function normalizeDirectory($path)
	{
		$result = Main\IO\Path::normalize($path);
		$result = preg_replace('#/index\.php$#', '', $result);

		if ($result !== '/')
		{
			$result = rtrim($result, '/');
		}

		return $result;
	}

	protected function getSetup()
	{
		$siteId = $this->getParameterSiteId();
		$serviceCode = $this->getParameterServiceCode();
		$result = Market\Trading\Setup\Model::loadByServiceAndSite($serviceCode, $siteId);

		if (!$result->isActive())
		{
			throw new Main\SystemException($this->getLang('TRADING_SETUP_INACTIVE'));
		}

		return $result;
	}

	protected function getParameterSefFolder()
	{
		return $this->getParameter('SEF_FOLDER');
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
		return Main\Localization\Loc::getMessage('YANDEX_MARKET_PURCHASE_' . $code, $replace, $language);
	}
}