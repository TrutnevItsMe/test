<? namespace Intervolga\Custom\Import;
use CUser;
use CSaleOrderProps;
use CSaleOrderUserProps;
use CSaleOrderUserPropsValue;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\UserTable;
use Intervolga\Common\Highloadblock\HlbWrap;

class Users {
	const COMPANY_PERSON_TYPE_ID = 1;
	public static function processPartner($partner) {
		if (!$partner['UF_IMLOGIN']) {
			// обрабатывем только записи с указаными логинами
			return;
		}
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
			var_export([
				'PARTNER' => $partner,
			], true),
			FILE_APPEND
		);
		
		// обработаем партнера
		$userId = self::updateUser($partner);
		// найдем контрагентов партнера
		$hlKontragents = new HlbWrap('Kontragenty');
		$kontragents = $hlKontragents->getList([
			'filter' => ['=UF_PARTNER' => $partner['UF_XML_ID']],
			'select' => ['*'],
		]);
		// И обработаем их
		while ($kontragent = $kontragents->fetch()) {
			self::updateSaleUser($userId, $kontragent, $partner);
		}
	}
	public static function processKontragent($kontragent) {
		$hlPartnery = new HlbWrap('Partnery');
		
		// Найдем партнера, которому принадлежит контрагент
		$partner = $hlPartnery->getList([
			'filter' => ['=UF_XML_ID' => $kontragent['UF_PARTNER']],
			'select' => ['*'],
		])->fetch();
		// и обработаем его
		if ($partner) {
			self::processPartner($partner);
		}
	}
	public static function processAllPartners() {
		$hlPartnery = new HlbWrap('Partnery');
		$rs = $hlPartnery->getList([
			'filter' => ['!UF_IMLOGIN' => false],
			'select' => ['*'],
		]);
		while ($el = $rs->fetch()) {
			self::processPartner($el);
		}
	}
	public static function getEventData(Event $event) {
		$hl = new HlbWrap($event->getEntity()->getName());
		$id = $event->getParameter('id');
		$id = is_array($id) ? $id['ID'] : $id;
		$data = $hl->getList([
			'filter' => ['=ID' => $id],
			'select' => ['*'],
		])->fetch();
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
			var_export([
				'HLBLOCK' => $event->getEntity()->getName(),
				'ID' => $id,
				'DATA' => $data,
			], true),
			FILE_APPEND
		);
		
		return $data;
	}
	protected static function updateUser($user) {
		$cUser = new CUser;
		$dbUser = UserTable::getRow([
			'filter' => ['=XML_ID' => $user['UF_XML_ID']],
			'select' => ['ID', 'XML_ID']
		]);
		$fields = [
			'ACTIVE' => ($user['UF_POMETKAUDALENIYA'] == 'Да') ? 'N' : 'Y',
			'LOGIN' => $user['UF_IMLOGIN'],
			'EMAIL' => $user['UF_IMLOGIN'],
			'NAME' => $user['UF_NAME'],
			'LID' => 's1',
		];
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
			var_export([
				'DB_USER' => $dbUser,
			], true),
			FILE_APPEND
		);
		if ($dbUser) {
			$userId = $dbUser['ID'];
			$cUser->Update($userId, $fields);
			if ($error = $cUser->LAST_ERROR) {
				// Intervolga Akentyev Logs
				file_put_contents(
					$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
					var_export([
						'ERROR' => $error,
					], true),
					FILE_APPEND
				);
			}
		} else {
			$password = randString(14);
			$fields['XML_ID'] = $user['UF_XML_ID'];
			$fields['PASSWORD'] = $password;
			$fields['CONFIRM_PASSWORD'] = $password;
			$userId = $cUser->Add($fields);
			if ($userId) {
				$cUser->SendPassword($user['UF_IMLOGIN'], $user['UF_IMLOGIN'], 's1');
			} else {
				$error = $cUser->LAST_ERROR;
				// Intervolga Akentyev Logs
				file_put_contents(
					$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
					var_export([
						'ERROR' => $error,
					], true),
					FILE_APPEND
				);
			}
		}
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/users' . DATE('_Y_m_d') . '.log',
			var_export([
				'USER_ID' => $userId,
			], true),
			FILE_APPEND
		);
		return $userId;
	}
	protected static function updateSaleUser($userId, $saleUser, $user) {
		if ($saleUser['UF_YURFIZLITSO'] == 'Физическое лицо') {
			return;
		}
		
		if (Loader::includeModule('sale')) {
			$profile = CSaleOrderUserProps::GetList(
				[],
				['XML_ID' => $saleUser['UF_XML_ID']],
				false,
				false,
				['ID', 'XML_ID']
			)->Fetch();
			$fields = [
				'NAME' => $saleUser['UF_NAME'],
				'USER_ID' => $userId,
				'PERSON_TYPE_ID' => self::COMPANY_PERSON_TYPE_ID,
				'XML_ID' => $saleUser['UF_XML_ID'],
			];
			if ($profile) {
				$profileId = $profile['ID'];
				CSaleOrderUserProps::Update($profileId, $fields);
			} else {
				$profileId = CSaleOrderUserProps::Add($fields);
			}
			if ($profileId) {
				self::setSaleUserProperties(
					$profileId,
					self::COMPANY_PERSON_TYPE_ID,
					[
						'COMPANY' => $saleUser['UF_NAME'],
						'COMPANY_ADR' => $saleUser['UF_YURIDICHESKIYADRE'],
						'INN' => $saleUser['UF_INN'],
						'KPP' => $saleUser['UF_KPP'],
						'CONTACT_PERSON' => '', // $user['UF_POMOSHNIK1'],
						'EMAIL' => $saleUser['UF_ELEKTRONNAYAPOCHT'],
						'PHONE' => $saleUser['UF_TELEFON'],
						'FAX' => $saleUser['UF_FAKS'],
						//'ZIP' => $saleUser[''],
						//'CITY' => $saleUser[''],
						//'LOCATION' => $saleUser[''],
						'ADDRESS' => $saleUser['UF_ADRESADOSTAVKI1'],
					]
				);
			}
		}
	}
	protected static function setSaleUserProperties($profileId, $personTypeId, $fields) {
		$props = CSaleOrderProps::GetList(
			[],
			['PERSON_TYPE_ID' => $personTypeId, 'CODE' => array_keys($fields)],
			false,
			false,
			['ID', 'CODE', 'NAME']
		);
		$properties = [];
		while ($property = $props->Fetch()) {
			$properties[$property['CODE']] = $property;
			$properties[$property['CODE']]['VALUE'] = $fields[$property['CODE']];
			$properties[$property['CODE']]['USER_PROPS_ID'] = $profileId;
			$properties[$property['CODE']]['ORDER_PROPS_ID'] = $property['ID'];
		}
		$props = CSaleOrderUserPropsValue::GetList(
			[],
			['USER_PROPS_ID' => $profileId, 'ORDER_PROPS_ID' => array_column($properties, 'ID')],
			false,
			false,
			['ID', 'CODE']
		);
		while ($property = $props->Fetch()) {
			
			CSaleOrderUserPropsValue::Update(
				$property['ID'],
				[
					'ID' => $property['ID'],
					'USER_PROPS_ID' => $profileId,
					'ORDER_PROPS_ID' => $properties[$property['CODE']]['ID'],
					'NAME' => $properties[$property['CODE']]['NAME'],
					'VALUE' => $fields[$property['CODE']],
				]
			);
			unset($properties[$property['CODE']]);
		}
		foreach ($properties as $property) {
			CSaleOrderUserPropsValue::Add([
				'USER_PROPS_ID' => $property['USER_PROPS_ID'],
				'ORDER_PROPS_ID' => $property['ORDER_PROPS_ID'],
				'NAME' => $property['NAME'],
				'VALUE ' => $property['VALUE'],
			]);
		}
	}
}