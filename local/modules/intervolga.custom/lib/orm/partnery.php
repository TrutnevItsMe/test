<? namespace Intervolga\Custom\ORM;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\Entity\DataManager;

class PartneryTable extends DataManager {

    public static function getTableName() {
        return "b_partnery";
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap() {
        return array(
            (new StringField('ID'))
                ->configurePrimary(true),
            (new StringField('UF_DATAREGISTRATSII')),
            (new StringField('UF_DESCRIPTION')),
            (new StringField('UF_IMLOGIN')),
            (new StringField('UF_KOD')),
            (new StringField('UF_NAME')),
            (new StringField('UF_OSNMENEDZHERADRES')),
            (new StringField('UF_OSNMENEDZHERTELEF')),
            (new StringField('UF_OSNOVNOYMENEDZHER')),
            (new StringField('UF_POMETKAUDALENIYA')),
            (new StringField('UF_POMOSHNIK1')),
            (new StringField('UF_POMOSHNIK2')),
//            (new StringField('UF_POMOSHNIK3')),
            (new StringField('UF_POMOSHNIKADRES1')),
            (new StringField('UF_POMOSHNIKADRES2')),
//            (new StringField('UF_POMOSHNIKADRES3')),
            (new StringField('UF_POMOSHNIKTELEFON1')),
            (new StringField('UF_POMOSHNIKTELEFON2')),
//            (new StringField('UF_POMOSHNIKTELEFON3')),
            (new StringField('UF_POMRABTELEFON1')),
            (new StringField('UF_POMRABTELEFON2')),
//            (new StringField('UF_POMRABTELEFON3')),
			(new StringField('UF_VERSION')),
			(new StringField('UF_XML_ID')),
			(new StringField('UF_YURFIZLITSO')),
			(new BooleanField('UF_RAZRESHENAOTGRUZK')),
            (new DatetimeField('UF_OSNMENEDZHERTELEF')),
        );
    }

    public static function getByXmlId($xmlId) {
        $q = static::query()
            ->where("UF_XML_ID", $xmlId)
            ->setSelect(["*"]);

        $q_res = $q->exec();
        return $q_res->fetch();
    }
}