<? namespace Intervolga\Custom\Import;
use CUser;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\UserTable;
use Intervolga\Common\Highloadblock\HlbWrap;

class Users {
	public static function processPartner($partner) {
		if (!$partner['UF_IMLOGIN']) {
			// обрабатывем только записи с указаными логинами
			return;
		}
		// обработаем партнера
		self::updateUser($partner);
		// найдем контрагентов партнера
		$hlKontragents = new HlbWrap('Kontragenty');
		$kontragents = $hlKontragents->getList([
			'filter' => ['=UF_PARTNER' => $partner['UF_XML_ID']],
			'select' => ['*'],
		]);
		// И обработаем их
		while ($kontragent = $kontragents->fetch()) {
			self::updateSaleUser($kontragent, $partner);
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
		echo '<pre>';
		if ($dbUser) {
			echo 'Обновим пользователя' . PHP_EOL;
		} else {
			echo 'Создадим пользователя' . PHP_EOL;
			$password = randString(14);
			$fields = [
				'LOGIN' => $user['UF_IMLOGIN'],
				'PASSWORD' => $password,
				'CONFIRM_PASSWORD' => $password,
				'NAME' => $user['UF_NAME'],
				'XML_ID' => $user['UF_XML_ID'],
				'LID' => 's1',
			];
			$userId = $cUser->Add($fields);
			var_dump($cUser->LAST_ERROR);
		}
		var_dump($userId);
		echo '</pre>';
		return $userId;
	}
	protected static function updateSaleUser($saleUser, $user) {
		echo '<pre>';
		echo 'Создадим/обновим профиль покупателя' . PHP_EOL;
		var_dump($saleUser);
		var_dump($user);
		echo '</pre>';
	}
}