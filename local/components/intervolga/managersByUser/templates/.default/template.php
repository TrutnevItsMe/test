<? if ($arResult["PARTNERS"]): ?>

<style>
    hr{
        width: 100%;
    }
</style>

    <table class="contact-table">
        <thead>
        <tr>
            <td><?= \Bitrix\Main\Localization\Loc::getMessage("PARTNER") ?><hr></td>
            <td><?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER") ?><hr></td>
            <td><?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER1") ?><hr></td>
            <td><?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER2") ?><hr></td>
        </tr>
        </thead>

        <tbody>

		<?
		$countPartners = 0;
		?>

		<? foreach ($arResult["PARTNERS"] as $partner): ?>

            <tr>
                <td><?= $partner["UF_NAME"] ?></td>
                <td><?= $partner["UF_OSNOVNOYMENEDZHER"] ?></td>
                <td><?= $partner["UF_POMOSHNIK1"] ?></td>
                <td><?= $partner["UF_POMOSHNIK2"] ?></td>
            </tr>

            <tr>
                <td>
                    <a href="mailto:<?= $partner["UF_IMLOGIN"] ?>"><?= $partner["UF_IMLOGIN"] ?></a>
                    <hr>
                </td>
                <td>
                    <a href="tel:<?= $partner["UF_OSNMENEDZHERTELEF"] ?>"><?= $partner["DISPLAY_OSNMENEDZHERTELEF"] ?></a>
                    <a href="mailto:<?= $partner["UF_OSNMENEDZHERADRES"] ?>"><?= $partner["UF_OSNMENEDZHERADRES"] ?></a>
                    <hr>
                </td>
                <td>
                    <a href="tel:<?= $partner["UF_POMOSHNIKTELEFON1"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON1"] ?></a>
                    <a href="mailto:<?= $partner["UF_POMOSHNIKADRES1"] ?>"><?= $partner["UF_POMOSHNIKADRES1"] ?></a>
                    <hr>
                </td>
                <td>
                    <a href="tel:<?= $partner["UF_POMOSHNIKTELEFON2"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON2"] ?></a>
                    <a href="mailto:<?= $partner["UF_POMOSHNIKADRES2"] ?>"><?= $partner["UF_POMOSHNIKADRES2"] ?></a>
                    <hr>
                </td>
            </tr>


			<? ++$countPartners; ?>
		<? endforeach; ?>


        </tbody>
    </table>
<? endif; ?>


<?php