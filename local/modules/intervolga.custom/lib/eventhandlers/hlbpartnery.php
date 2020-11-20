<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;
use Bitrix\Main\Entity\Event;
use Intervolga\Custom\Import\Users;


/**
 * Class HLBPartnery
 *
 * Event handlers for Highloadblock Partnery
 *
 * @package Intervolga\Custom\EventHandlers
 */
class HLBPartnery extends EventHandler
{
	public static function OnAfterAdd(Event $event)
	{
		self::OnAfterUpdate($event);
	}
	public static function OnAfterUpdate(Event $event)
	{
		//Users::processPartner(Users::getEventData($event));
	}
	public static function OnAfterDelete(Event $event)
	{
		// do nothing
	}
}