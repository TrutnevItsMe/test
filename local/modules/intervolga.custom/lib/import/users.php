<? namespace Intervolga\Custom\Import;
use CUser;
use CSaleOrderUserProps;
use CSaleOrderUserPropsValue;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\UserTable;
use Intervolga\Common\Highloadblock\HlbWrap;

class Users {
	const PERSON_TYPE_ID = 1;
	public static function processPartner($partner) {
		if (!$partner['UF_IMLOGIN']) {
			// обрабатывем только записи с указаными логинами
			return;
		}
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
		return  $hl->getList([
			'filter' => ['=ID' => $event->getParameter('id')['ID']],
			'select' => ['*'],
		])->fetch();
	}
	protected static function updateUser($user) {
		$cUser = new CUser;
		$dbUser = UserTable::getRow([
			'filter' => ['=XML_ID' => $user['UF_XML_ID']],
			'select' => ['ID', 'XML_ID']
		]);
		$fields = [
			'LOGIN' => $user['UF_IMLOGIN'],
			'EMAIL' => $user['UF_IMLOGIN'],
			'NAME' => $user['UF_NAME'],
			'LID' => 's1',
		];
		echo '<pre>';
		if ($dbUser) {
			echo 'Обновим пользователя' . PHP_EOL;
			$userId = $dbUser['ID'];
			$cUser->Update($userId, $fields);
		} else {
			echo 'Создадим пользователя' . PHP_EOL;
			$password = randString(14);
			$fields['XML_ID'] = $user['UF_XML_ID'];
			$fields['PASSWORD'] = $password;
			$fields['CONFIRM_PASSWORD'] = $password;
			$userId = $cUser->Add($fields);
			if ($userId) {
				$cUser->SendPassword($user['UF_IMLOGIN'], $user['UF_IMLOGIN'], 's1');
			} else {
				var_dump($cUser->LAST_ERROR);
			}
		}
		var_dump($userId);
		echo '</pre>';
		return $userId;
	}
	protected static function updateSaleUser($userId, $saleUser, $user) {
		echo '<pre>';
		if ($saleUser['UF_YURFIZLITSO'] == 'Физическое лицо') {
			echo 'Физлицо -- ничего не делаем' . PHP_EOL;
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
				'PERSON_TYPE_ID' => self::PERSON_TYPE_ID,
				'XML_ID' => $saleUser['UF_XML_ID'],
			];
			if ($profile) {
				echo 'Обновим профиль покупателя' . PHP_EOL;
				$profileId = $profile['ID'];
				CSaleOrderUserProps::Update($profileId, $fields);
			} else {
				echo 'Создадим профиль покупателя' . PHP_EOL;
				$profileId = CSaleOrderUserProps::Add($fields);
			}
			if ($profileId) {
				self::setSaleUserProperties(
					$profileId,
					[
						'COMPANY' => $saleUser['UF_NAME'],
						'COMPANY_ADR' => $saleUser['UF_YURIDICHESKIYADRE'],
						'INN' => $saleUser['UF_INN'],
						'KPP' => $saleUser['UF_KPP'],
						'CONTACT_PERSON' => $user['UF_NAME'],
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
			echo '</pre>';
		}
	}
	protected static function setSaleUserProperties($profileId, $fields) {
		echo 'Заполним свойство' . PHP_EOL;
		var_dump($profileId);
		var_dump($fields);
	}
}