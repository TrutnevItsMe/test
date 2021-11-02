<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;
use Bitrix\Main\Entity\Event;
use Intervolga\Custom\Import\Users;
use Bitrix\Main\SystemException;


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
		try {
			Users::processPartner(Users::getEventData($event));
		} catch (SystemException $exception) {
			// do nothing
		}
	}
	public static function OnAfterDelete(Event $event)
	{
		// do nothing
	}
}