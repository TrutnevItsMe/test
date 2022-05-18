<? namespace Intervolga\Custom\ORM;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\Entity\DataManager;

class SoglasheniyaSKlientamiTable extends DataManager {

    public static function getTableName() {
        return "b_soglasheniyasklientami";
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap() {
        return array(
            (new StringField('ID'))
                ->configurePrimary(true),
            (new StringField('UF_NAME')),
            (new StringField('UF_XML_ID')),
            (new StringField('UF_VERSION')),
            (new StringField('UF_DESCRIPTION')),
            (new StringField('UF_KOD')),
            (new StringField('UF_VIDTSEN')),
            (new StringField('UF_SOGLASHENIE')),
            (new StringField('UF_DOGOVOR')),
            (new StringField('UF_OPLATA')),
            (new StringField('UF_NOMER')),
            (new StringField('UF_PARTNER')),
            (new StringField('UF_VALYUTA')),
            (new StringField('UF_KONTRAGENT')),
            (new StringField('UF_POMETKAUDALENIYA')),
            (new DatetimeField('UF_DATA')),
        );
    }
}