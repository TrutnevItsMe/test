<? namespace Intervolga\Custom\Import;
use Bitrix\Main\Entity\Event;
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
		echo '<pre>';
		echo 'Создадим/обновим пользователя' . PHP_EOL;
		var_dump($user);
		echo '</pre>';
	}
	protected static function updateSaleUser($saleUser, $user) {
		echo '<pre>';
		echo 'Создадим/обновим профиль покупателя' . PHP_EOL;
		var_dump($saleUser);
		var_dump($user);
		echo '</pre>';
	}
}