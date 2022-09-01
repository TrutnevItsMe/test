<? if ($arResult["PARTNERS"]): ?>

	<table class="contact-table">

		<tbody>

		<? foreach ($arResult["PARTNERS"] as $partner): ?>

			<tr>
				<th>
					<? if ($partner["UF_NAME"]): ?>
						<?= \Bitrix\Main\Localization\Loc::getMessage("PARTNER") ?>
					<? endif; ?>
				</th>

				<th>
					<? if ($partner["UF_OSNOVNOYMENEDZHER"]): ?>
						<?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER") ?>
					<? endif; ?>
				</th>

				<th>
					<? if ($partner["UF_POMOSHNIK1"]): ?>
						<?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER") ?>
					<? endif; ?>
				</th>

				<th>
					<? if ($partner["UF_POMOSHNIK2"]): ?>
						<?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER") ?>
					<? endif; ?>
				</th>

				<th>
					<? if ($partner["UF_POMOSHNIK3"]): ?>
						<?= \Bitrix\Main\Localization\Loc::getMessage("MANAGER_HELPER") ?>
					<? endif; ?>
				</th>
			</tr>

			<tr>
				<td><?= $partner["UF_NAME"] ?></td>
				<td><?= $partner["UF_OSNOVNOYMENEDZHER"] ?></td>
				<td><?= $partner["UF_POMOSHNIK1"] ?></td>
				<td><?= $partner["UF_POMOSHNIK2"] ?></td>
				<td><?= $partner["UF_POMOSHNIK3"] ?></td>
			</tr>

			<tr>
				<td class="content-td partner-content">
					<? if ($partner["UF_IMLOGIN"]): ?>
						<span>Login: </span>
						<a href="mailto:<?= $partner["UF_IMLOGIN"] ?>"><?= $partner["UF_IMLOGIN"] ?></a>
					<? endif; ?>
				</td>
				<td class="content-td manager-content">
					<? if ($partner["UF_OSNMENEDZHERTELEF"]): ?>
						<span>Тел: </span>
						<a href="tel:<?= $partner["UF_OSNMENEDZHERTELEF"] ?>"><?= $partner["DISPLAY_OSNMENEDZHERTELEF"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_OSNMENEDZHERRABTE"]): ?>
						<br>
						<span>Раб. тел: </span>
						<a href="tel:<?= $partner["UF_OSNMENEDZHERRABTE"] ?>"><?= $partner["DISPLAY_OSNMENEDZHERRABTE"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_OSNMENEDZHERADRES"]): ?>
						<br>
						<span>Почта: </span>
						<a href="mailto:<?= $partner["UF_OSNMENEDZHERADRES"] ?>"><?= $partner["UF_OSNMENEDZHERADRES"] ?></a>
					<? endif; ?>
				</td>
				<td class="content-td assistant1-content">
					<? if ($partner["UF_POMOSHNIKTELEFON1"]): ?>
						<span>Тел: </span>
						<a href="tel:<?= $partner["UF_POMOSHNIKTELEFON1"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON1"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMRABTELEFON1"]): ?>
						<br>
						<span>Раб. тел: </span>
						<a href="tel:<?= $partner["UF_POMRABTELEFON1"] ?>"><?= $partner["DISPLAY_POMRABTELEFON1"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMOSHNIKADRES1"]): ?>
						<br>
						<span>Почта: </span>
						<a href="mailto:<?= $partner["UF_POMOSHNIKADRES1"] ?>"><?= $partner["UF_POMOSHNIKADRES1"] ?></a>
					<? endif; ?>
				</td>
				<td class="content-td assistant2-content">
					<? if ($partner["UF_POMOSHNIKTELEFON2"]): ?>
						<span>Тел: </span>
						<a href="tel:<?= $partner["UF_POMOSHNIKTELEFON2"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON2"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMRABTELEFON2"]): ?>
						<br>
						<span>Раб. тел: </span>
						<a href="tel:<?= $partner["UF_POMRABTELEFON2"] ?>"><?= $partner["DISPLAY_POMRABTELEFON2"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMOSHNIKADRES2"]): ?>
						<br>
						<span>Почта: </span>
						<a href="mailto:<?= $partner["UF_POMOSHNIKADRES2"] ?>"><?= $partner["UF_POMOSHNIKADRES2"] ?></a>
					<? endif; ?>
				</td>
				<td class="content-td assistant3-content">
					<? if ($partner["UF_POMOSHNIKTELEFON3"]): ?>
						<span>Тел: </span>
						<a href="tel:<?= $partner["UF_POMOSHNIKTELEFON3"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON3"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMRABTELEFON3"]): ?>
						<br>
						<span>Раб. тел: </span>
						<a href="tel:<?= $partner["UF_POMRABTELEFON3"] ?>"><?= $partner["DISPLAY_POMRABTELEFON3"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMOSHNIKADRES3"]): ?>
						<br>
						<span>Почта: </span>
						<a href="mailto:<?= $partner["UF_POMOSHNIKADRES3"] ?>"><?= $partner["UF_POMOSHNIKADRES3"] ?></a>
					<? endif; ?>
				</td>
			</tr>

			<tr style="height: 20px"></tr>

		<? endforeach; ?>

<!--		TODO: перенести в css файл-->
		<style>
			.content-td
			{
				text-align: left!important;
				vertical-align: top !important;
				padding-left: 5%!important;
			}
		</style>

		</tbody>
	</table>
<? endif; ?>


