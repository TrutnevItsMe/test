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
			</tr>

			<tr>
				<td><?= $partner["UF_NAME"] ?></td>
				<td><?= $partner["UF_OSNOVNOYMENEDZHER"] ?></td>
				<td><?= $partner["UF_POMOSHNIK1"] ?></td>
				<td><?= $partner["UF_POMOSHNIK2"] ?></td>
			</tr>

			<tr>
				<td>
					<? if ($partner["UF_IMLOGIN"]): ?>
						<a href="mailto:<?= $partner["UF_IMLOGIN"] ?>"><?= $partner["UF_IMLOGIN"] ?></a>
					<? endif; ?>
				</td>
				<td>
					<? if ($partner["UF_OSNMENEDZHERTELEF"]): ?>
						<a href="tel:<?= $partner["UF_OSNMENEDZHERTELEF"] ?>"><?= $partner["DISPLAY_OSNMENEDZHERTELEF"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_OSNMENEDZHERADRES"]): ?>
						<a href="mailto:<?= $partner["UF_OSNMENEDZHERADRES"] ?>"><?= $partner["UF_OSNMENEDZHERADRES"] ?></a>
					<? endif; ?>
				</td>
				<td>
					<? if ($partner["UF_POMOSHNIKTELEFON1"]): ?>
						<a href="tel:<?= $partner["UF_POMOSHNIKTELEFON1"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON1"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMOSHNIKADRES1"]): ?>
						<a href="mailto:<?= $partner["UF_POMOSHNIKADRES1"] ?>"><?= $partner["UF_POMOSHNIKADRES1"] ?></a>
					<? endif; ?>
				</td>
				<td>
					<? if ($partner["UF_POMOSHNIKTELEFON2"]): ?>
						<a href="tel:<?= $partner["UF_POMOSHNIKTELEFON2"] ?>"><?= $partner["DISPLAY_POMOSHNIKTELEFON2"] ?></a>
					<? endif; ?>

					<? if ($partner["UF_POMOSHNIKADRES2"]): ?>
						<a href="mailto:<?= $partner["UF_POMOSHNIKADRES2"] ?>"><?= $partner["UF_POMOSHNIKADRES2"] ?></a>
					<? endif; ?>
				</td>
			</tr>

			<tr style="height: 20px"></tr>

		<? endforeach; ?>


		</tbody>
	</table>
<? endif; ?>


<?php