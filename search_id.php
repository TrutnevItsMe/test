<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
 
CModule::IncludeModule("iblock");
 
$IBLOCK_ID = 17;
$SEARCH_FIELD_NAME = 'SEARCH_ID';
$els = CIBlockElement::GetList(
    array("SORT"=>"ASC"),
    array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y"),
    false,
    false,
    array('ID','IBLOCK_ID')
);
 
$cnt=0;
 
while ($s = $els->Fetch()) {
    
    $ok = CIBlockElement::SetPropertyValuesEx(
        $s["ID"],
        $s['IBLOCK_ID'],
        array('SEARCH_ID' => $s["ID"])
    );
    if ($ok) {
        echo $cnt.' - добавлен'."<br>";
    }
}
 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>