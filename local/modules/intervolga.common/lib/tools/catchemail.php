<?namespace Intervolga\Common\Tools;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CatchEmail
{
	/**
	 * @param \Bitrix\Main\Event $event
	 *
	 * @return \Bitrix\Main\EventResult|null
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public static function onBeforeMailSend(Event $event)
	{
		if (Option::get("intervolga.common", "catch_all_emails") == "Y")
		{
			$redirect = Option::get("intervolga.common", "redirect_emails");
			if ($redirect)
			{
				$mailParams = $event->getParameter(0);
				$oldReceiver = $mailParams["TO"];
				$mailParams["TO"] = $redirect;

				unset($mailParams["HEADER"]["BCC"]);
				unset($mailParams["HEADER"]["CC"]);

				if ($mailParams["CONTENT_TYPE"] == "text")
				{
					$mailParams["BODY"] = $mailParams["BODY"] . "\n\n" . Loc::getMessage("INTERVOLGA_COMMON.THIS_MESSAGE_WAS_INTERCEPTED_FROM", array("#EMAIL#" => $oldReceiver));
				}
				else
				{
					$mailParams["BODY"] = $mailParams["BODY"] . "<br><br>" . Loc::getMessage("INTERVOLGA_COMMON.THIS_MESSAGE_WAS_INTERCEPTED_FROM", array("#EMAIL#" => $oldReceiver));
				}
				$mailParams["SUBJECT"] .= " " . Loc::getMessage("INTERVOLGA_COMMON.INTERCEPTED_MESSAGE");

				return new EventResult(EventResult::SUCCESS, $mailParams);
			}
		}
		return null;
	}
}