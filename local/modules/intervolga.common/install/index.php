<? IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Tools\CatchFormCrm;
use Intervolga\Common\Tools\Htaccess;

Loc::loadMessages(__FILE__);

class intervolga_common extends CModule
{
	const MODULE_ID = 'intervolga.common';
	var $MODULE_ID = 'intervolga.common';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__) . "/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("INTERVOLGA_COMMON.MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("INTERVOLGA_COMMON.MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("INTERVOLGA_COMMON.PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("INTERVOLGA_COMMON.PARTNER_URI");
	}

	function doInstall()
	{
		registerModule(self::MODULE_ID);
		$this->installFiles();
		$this->installEvents();
		$this->installDb();
		$this->installAgents();
		$this->modifyHtaccess();
	}

	function doUninstall()
	{
		$this->unInstallDb();
		$this->unInstallEvents();
		$this->unInstallFiles();
		$this->unInstallAgents();
		CatchFormCrm::repairSending();
		CAdminNotify::deleteByTag("INTERVOLGA_COMMON_INSTALL");
		unRegisterModule(self::MODULE_ID);
	}

	public function unInstallDb()
	{
		global $DB, $DBType;
		$errors = $DB->RunSQLBatch(__DIR__ . "/db/" . strtolower($DBType) . "/uninstall.sql");
		if ($errors)
		{
			throw new \Exception(implode("<br>", $errors));
		}

		return true;
	}

	public function installFiles()
	{
		$root = Application::getDocumentRoot();
		copyDirFiles(
			$root . '/local/modules/intervolga.common/install/themes',
			$root . '/bitrix/themes',
			true,
			true
		);
		copyDirFiles(
			$root . '/local/modules/intervolga.common/install/admin',
			$root . '/bitrix/admin',
			true,
			true
		);
		copyDirFiles(
			$root . '/local/modules/intervolga.common/install/public',
			$root . '/',
			true,
			true
		);
	}

	protected function modifyHtaccess()
	{
		if (Loader::includeModule('intervolga.common'))
		{
			$file = new Htaccess();
			$file->denyLogs();
		}
	}

	/**
	 * Remove module files outside of installation directory
	 */
	public function uninstallFiles()
	{
		$root = Application::getDocumentRoot();
		File::deleteFile($root . '/bitrix/themes/.default/intervolga.common.css');
		Directory::deleteDirectory($root . '/bitrix/themes/.default/icons/intervolga.common/');
		deleteDirFiles(
			$root . '/local/modules/intervolga.common/install/admin',
			$root . '/bitrix/admin'
		);
		Directory::deleteDirectory($root . '/log/1c');
	}

	function installEvents()
	{
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnBeforeUserAdd
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnBeforeUserAdd",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnBeforeUserAdd");
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnSendUserInfo
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnSendUserInfo",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnSendUserInfo");
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnOrderNewSendEmail
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnOrderNewSendEmail",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnOrderNewSendEmail");

		/**
		 * @see \Intervolga\Common\Iblock\VideoProperty::GetUserTypeDescription
		 */
		EventManager::getInstance()->registerEventHandler("iblock", "OnIBlockPropertyBuildList",
			self::MODULE_ID, "Intervolga\\Common\\Iblock\\VideoProperty", "GetUserTypeDescription");
		/**
		 * @see \Intervolga\Common\Iblock\VideoProperty::OnIBlockElementDelete
		 */
		EventManager::getInstance()->registerEventHandler("iblock", "OnBeforeIBlockElementDelete",
			self::MODULE_ID, "Intervolga\\Common\\Iblock\\VideoProperty", "OnIBlockElementDelete");
		/**
		 * @see \Intervolga\Common\Iblock\CheckboxProperty::OnIBlockPropertyBuildList
		 */
		EventManager::getInstance()->registerEventHandler("iblock", "OnIBlockPropertyBuildList",
			self::MODULE_ID, "\\Intervolga\\Common\\Iblock\\CheckboxProperty", "OnIBlockPropertyBuildList");
		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onProlog
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnProlog",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onProlog");
		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onPageStart
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnPageStart",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onPageStart");

		/**
		 * @see \Intervolga\Common\Tools\CatchEmail::onBeforeMailSend
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnBeforeMailSend",
			self::MODULE_ID, "Intervolga\\Common\\Tools\\CatchEmail", "onBeforeMailSend");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onEventLogGetAuditTypes
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnEventLogGetAuditTypes",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onEventLogGetAuditTypes");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onAdminListDisplay
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnAdminListDisplay",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onAdminListDisplay");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onBuildGlobalMenu
		 */
		EventManager::getInstance()->registerEventHandler("main", "OnBuildGlobalMenu",
			self::MODULE_ID, "\\Intervolga\\Common\\EventHandlers\\Main", "onBuildGlobalMenu");

		return true;
	}

	function installDb()
	{
		global $DB, $DBType;
		$errors = $DB->RunSQLBatch(__DIR__ . "/db/" . strtolower($DBType) . "/install.sql");
		if ($errors)
		{
			throw new \Exception(implode("<br>", $errors));
		}
		if (Loader::includeModule("search"))
		{
			$excludeMaskOption = Option::get("search", "exclude_mask");
			$excludeMasks = explode(";", $excludeMaskOption);
			$needUpdate = false;
			foreach ($this->getModuleSearchExcludeMasks() as $mask)
			{
				if (!in_array($mask, $excludeMasks))
				{
					$needUpdate = true;
					$excludeMasks[] = $mask;
				}
			}
			if ($needUpdate)
			{
				Option::set("search", "exclude_mask", implode(";", $excludeMasks));
			}
		}
		CAdminNotify::add(Array(
			"MESSAGE" => Loc::getMessage("INTERVOLGA_COMMON.GITIGNORE_RESTORE_MESSAGE"),
			"TAG" => "INTERVOLGA_COMMON_INSTALL",
		));
	}

	function installAgents()
	{
		/**
		 * @see \Intervolga\Common\Agents\Log1CAgent::run
		 */
		\CAgent::AddAgent('\Intervolga\Common\Agents\Log1CAgent::run();', self::MODULE_ID, "Y");

		/**
		 * @see \Intervolga\Common\Agents\LogRotate::run
		 */
		\CAgent::AddAgent('\Intervolga\Common\Agents\LogRotate::run();', self::MODULE_ID, "Y");
	}

	function unInstallAgents()
	{
		/**
		 * @see \Intervolga\Common\Agents\Log1CAgent::run
		 */
		\CAgent::RemoveAgent('\Intervolga\Common\Agents\Log1CAgent::run();', self::MODULE_ID);

		/**
		 * @see \Intervolga\Common\Agents\LogRotate::run
		 */
		\CAgent::RemoveAgent('\Intervolga\Common\Agents\LogRotate::run();', self::MODULE_ID);
	}

	/**
	 * @return array|string[]
	 */
	protected function getModuleSearchExcludeMasks()
	{
		return array("/local/*", "/log/*", "/import.php");
	}

	function unInstallEvents()
	{
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnBeforeUserAdd
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnBeforeUserAdd",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnBeforeUserAdd");
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnSendUserInfo
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnSendUserInfo",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnSendUserInfo");
		/**
		 * @see \Intervolga\Common\Main\SendPass::OnOrderNewSendEmail
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnOrderNewSendEmail",
			self::MODULE_ID, "Intervolga\\Common\\Main\\SendPass", "OnOrderNewSendEmail");

		/**
		 * @see \Intervolga\Common\Iblock\VideoProperty::GetUserTypeDescription
		 */
		EventManager::getInstance()->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList",
			self::MODULE_ID, "Intervolga\\Common\\Iblock\\VideoProperty", "GetUserTypeDescription");
		/**
		 * @see \Intervolga\Common\Iblock\VideoProperty::OnIBlockElementDelete
		 */
		EventManager::getInstance()->unRegisterEventHandler("iblock", "OnBeforeIBlockElementDelete",
			self::MODULE_ID, "Intervolga\\Common\\Iblock\\VideoProperty", "OnIBlockElementDelete");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onProlog
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnProlog",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onProlog");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onPageStart
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnPageStart",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onPageStart");

		/**
		 * @see \Intervolga\Common\Tools\CatchEmail::onBeforeMailSend
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnBeforeMailSend",
			self::MODULE_ID, "Intervolga\\Common\\Tools\\CatchEmail", "onBeforeMailSend");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onEventLogGetAuditTypes
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnEventLogGetAuditTypes",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onEventLogGetAuditTypes");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onAdminListDisplay
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnAdminListDisplay",
			self::MODULE_ID, "Intervolga\\Common\\EventHandlers\\Main", "onAdminListDisplay");

		/**
		 * @see \Intervolga\Common\EventHandlers\Main::onBuildGlobalMenu
		 */
		EventManager::getInstance()->unRegisterEventHandler("main", "OnBuildGlobalMenu",
			self::MODULE_ID, "\\Intervolga\\Common\\EventHandlers\\Main", "onBuildGlobalMenu");

		return true;
	}
}

?>