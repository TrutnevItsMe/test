<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;
use Bitrix\Main\Entity\Event;
use Intervolga\Custom\Import\Users;


/**
 * Class HLBKontragenty
 *
 * Event handlers for Highloadblock Kontragenty
 *
 * @package Intervolga\Custom\EventHandlers
 */
class HLBKontragenty extends EventHandler
{
	public static function OnAfterAdd(Event $event)
	{
		self::OnAfterUpdate($event);
	}
	public static function OnAfterUpdate(Event $event)
	{
		//Users::processKontragent(Users::getEventData($event));
	}
	public static function OnAfterDelete(Event $event)
	{
		// do nothing
	}
}