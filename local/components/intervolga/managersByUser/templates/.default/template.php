<table class="contact-table">
            <thead>
            <tr>
                <td><?=\Bitrix\Main\Localization\Loc::getMessage("PARTNER")?></td>
                <td><?=\Bitrix\Main\Localization\Loc::getMessage("MANAGER")?></td>
                <td><?=\Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER")?></td>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td><?=$arResult["PARTNER"]["UF_NAME"]?></td>
<td><?=$arResult["PARTNER"]["UF_OSNOVNOYMENEDZHER"]?></td>
<td><?=$arResult["PARTNER"]["UF_POMOSHNIK1"]?></td>
</tr>
<tr>
	<td>
        <a href="mailto:<?=$arResult["PARTNER"]["UF_IMLOGIN"]?>"><?=$arResult["PARTNER"]["UF_IMLOGIN"]?></a>
    </td>
	<td>
        <a href="tel:<?=$arResult["PARTNER"]["UF_OSNMENEDZHERTELEF"]?>"><?=$arResult["PARTNER"]["DISPLAY_OSNMENEDZHERTELEF"]?></a>
        <a href="mailto:<?=$arResult["PARTNER"]["UF_OSNMENEDZHERADRES"]?>"><?=$arResult["PARTNER"]["UF_OSNMENEDZHERADRES"]?></a>
    </td>
	<td>
        <a href="tel:<?=$arResult["PARTNER"]["UF_POMOSHNIKTELEFON1"]?>"><?=$arResult["PARTNER"]["DISPLAY_POMOSHNIKTELEFON1"]?></a>
        <a href="mailto:<?=$arResult["PARTNER"]["UF_POMOSHNIKADRES1"]?>"><?=$arResult["PARTNER"]["UF_POMOSHNIKADRES1"]?></a>
    </td>
</tr>
</tbody>
</table>

