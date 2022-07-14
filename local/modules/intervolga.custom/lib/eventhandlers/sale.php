<?namespace Intervolga\Custom\EventHandlers;

use Bitrix\Main\Application;
use Bitrix\Main\Event;
use Intervolga\Common\Tools\EventHandler;
use Intervolga\Custom\Tools\SaleUtil;

class Sale extends EventHandler{

	public static function OnSaleOrderBeforeSaved(Event $event){
		$order = $event->getParameter("ENTITY");
		$request = Application::getInstance()->getContext()->getRequest();
		$isDraft = boolval($request->get("isDraft"));
		$isNewOrder = $order->isNew();

		if ($isNewOrder && $isDraft){
			$statusDraftCode = SaleUtil::getStatusIdByXml(SALE_DRAFT_STATUS_XML_ID);
			$order->setField('STATUS_ID', $statusDraftCode);
		}
	}
}