<?namespace Intervolga\Common\Tools;

abstract class EventHandler
{
	public static  function getModuleName()
	{
		return strtolower(
			preg_replace(
				'/(.)([A-Z])/',
				'$1.$2',
				array_pop(
					explode(
						'\\',
						get_called_class()
					)
				)
			)
		);
	}
}