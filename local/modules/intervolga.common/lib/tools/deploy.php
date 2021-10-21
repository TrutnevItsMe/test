<?namespace Intervolga\Common\Tools;

use Bitrix\Main\Config\Option;

class Deploy
{
	public static function isProdServer()
	{
		return Option::get('intervolga.common', 'is_prod') == 'Y';
	}
}