<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if($arResult["ERROR_MESSAGE"] <> '')
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
if($arResult["NAV_STRING"] <> '')
{
	?>
	<p><?=$arResult["NAV_STRING"]?></p>
	<?
}
if (is_array($arResult["PROFILES"]) && !empty($arResult["PROFILES"]))
{
	?>
	<table class="table sale-personal-profile-list-container ka">
		<tr>
			<th>Название</th>
			<th>Тип плательщика</th>
			<th>ИНН</th>
			<th>КПП</th>
			<th>Юр. адрес</th>
			<th>Физ. адрес</th>
		</tr>
		<?foreach($arResult["PROFILES"] as $val)
		{
			?>
			<tr class="toggle" data-toggle="<?= $val["XML_ID"] ?>">
				<td><?= $val["NAME"] ?></td>
				<td><?= $val["PERSON_TYPE"]["NAME"] ?></td>
				<td><?= $val["PROPS"][3]["VALUE"] ?></td>
				<td><?= $val["PROPS"][4]["VALUE"] ?></td>
				<td><?= $val["PROPS"][2]["VALUE"] ?></td>
				<td><?= $val["PROPS"][12]["VALUE"] ?></td>
			</tr>
            <?if ($val["DOCS"]) :?>
                <tr class="toggle-target hidden" id="<?= $val["XML_ID"] ?>">
                    <td colspan="6">
                        <table class="table sale-personal-profile-list-container">
                            <tr>
                                <th>Номер</th>
                                <th>Наименование</th>
                                <th>Состояние</th>
                                <th>Действует с</th>
                            </tr>
                            <? foreach ($val["DOCS"] as $DOC) {?>
                                <tr>
                                    <td><?= $DOC["UF_NOMER"] ?></td>
                                    <td><?= $DOC["UF_DOGOVOR"] ?></td>
                                    <td><?= $DOC["UF_OPLATA"] ?></td>
                                    <td><?= $DOC["UF_DATA"] ?></td>
                                </tr>
                            <?}?>
                        </table>
                    </td>
                </tr>

        <?endif;?>
			<?
		}?>
	</table>
	<?
	if($arResult["NAV_STRING"] <> '')
	{
		?>
		<p><?=$arResult["NAV_STRING"]?></p>
		<?
	}
}
elseif ($arResult['USER_IS_NOT_AUTHORIZED'] !== 'Y')
{
	?>
	<h3><?=Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></h3>
	<?
}
?>
