<?php B_PROLOG_INCLUDED === true || die();

use \Bitrix\Main\Localization\Loc;
global $APPLICATION, $USER;

$moduleId = "intervolga.custom";
\Bitrix\Main\Loader::includeModule($moduleId);
Loc::loadMessages(__FILE__);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

const RESTS_CONDITION_AUTH = "IV_OPTION_RESTS_CONDITION_AUTH";
const RESTS_DESCRIPTION_AUTH = "IV_OPTION_RESTS_DESCRIPTION_AUTH";
const RESTS_CONDITION_NO_AUTH = "IV_OPTION_RESTS_CONDITION_NO_AUTH";
const RESTS_DESCRIPTION_NO_AUTH = "IV_OPTION_RESTS_DESCRIPTION_NO_AUTH";

/**
 * Для авторизованных пользователей
 */
$optionRestsConditionsAuth = [];
$optionRestsDescriptionAuth = [];

/**
 * Для неавторизованных пользователей
 */
$optionRestsConditionsNoAuth = [];
$optionRestsDescriptionNoAuth = [];

if ($request->isPost() && check_bitrix_sessid())
{
	$optionRestsConditionsAuth = $request->get(RESTS_CONDITION_AUTH);
	$optionRestsDescriptionAuth = $request->get(RESTS_DESCRIPTION_AUTH);
	$optionRestsConditionsNoAuth = $request->get(RESTS_CONDITION_NO_AUTH);
	$optionRestsDescriptionNoAuth = $request->get(RESTS_DESCRIPTION_NO_AUTH);

	$mapConditionDescription = [];

	for ($i = 0; $i < count($optionRestsDescriptionAuth); ++$i)
	{
		if ($optionRestsConditionsAuth[$i] != "")
		{
			$mapConditionDescription["AUTH"][$optionRestsConditionsAuth[$i]] = $optionRestsDescriptionAuth[$i];
		}
	}

	for ($i = 0; $i < count($optionRestsDescriptionNoAuth); ++$i)
	{
		if ($optionRestsConditionsNoAuth[$i] != "")
		{
			$mapConditionDescription["NO_AUTH"][$optionRestsConditionsNoAuth[$i]] = $optionRestsDescriptionNoAuth[$i];
		}
	}

	\Bitrix\Main\Config\Option::set($moduleId, "RESTS_CONDITION", serialize($mapConditionDescription));
}

$optionRestsConditionsAuth = [];
$optionRestsDescriptionAuth = [];
$optionRestsConditionsNoAuth = [];
$optionRestsDescriptionNoAuth = [];

$conditions = unserialize(\Bitrix\Main\Config\Option::get($moduleId, "RESTS_CONDITION"));

if (!$conditions)
{
	$conditions = [];
}

if (!$conditions["AUTH"])
{
	$conditions["AUTH"] = [
		"<=0" => "Нет в наличии"
	];

	$optionRestsConditionsAuth = ["<=0"];
	$optionRestsDescriptionAuth = ["Нет в наличии"];

	\Bitrix\Main\Config\Option::set($moduleId, "RESTS_CONDITION",
		serialize($conditions));
}
else
{
	foreach ($conditions["AUTH"] as $condition => $description)
	{
		$optionRestsConditionsAuth[] = $condition;
		$optionRestsDescriptionAuth[] = $description;
	}
}

if (!$conditions["NO_AUTH"])
{
	$conditions = unserialize(\Bitrix\Main\Config\Option::get($moduleId, "RESTS_CONDITION"));
	$conditions["NO_AUTH"] = [
		"<=0" => "Нет в наличии"
	];

	$optionRestsConditionsNoAuth = ["<=0"];
	$optionRestsDescriptionNoAuth = ["Нет в наличии"];

	\Bitrix\Main\Config\Option::set($moduleId, "RESTS_CONDITION",
		serialize($conditions));
}
else
{
	foreach ($conditions["NO_AUTH"] as $condition => $description)
	{
		$optionRestsConditionsNoAuth[] = $condition;
		$optionRestsDescriptionNoAuth[] = $description;
	}
}

$aTabs = [
	[
		"DIV" => "edit1",
		"TAB" => Loc::getMessage("MAIN_TAB_SET"),
		"TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET")
	],
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();

?>
<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($moduleId)?>&lang=<?=LANGUAGE_ID?>">
	<?php
	$tabControl->BeginNextTab();
	?>

	<div class="adm-info-message">
		<?=Loc::getMessage("RESTS_DESCR_HELP")?>
	</div>

	<table>
		<td>
			<h3>Отображение остатков для АВТОРИЗОВАННЫХ пользователей</h3>
			<table class="rests-description-table">
				<thead>
				<tr>
					<td>Условие</td>
					<td>Описание</td>
					<td></td>
				</tr>
				</thead>

				<?php for ($i = 0; $i < count($optionRestsConditionsAuth); ++$i):?>
					<tr>
						<td><input type="text" maxlength="255" value="<?=$optionRestsConditionsAuth[$i]?>"
								   name="<?=RESTS_CONDITION_AUTH?>[]"></td>
						<td><input type="text" maxlength="255" value="<?=$optionRestsDescriptionAuth[$i]?>"
								   name="<?=RESTS_DESCRIPTION_AUTH?>[]"></td>
						<td class="delete-item" onclick="bindDelete(this)"><span></span></td>
					</tr>
				<?php endfor;?>
			</table>
			<br>
			<input type="button" class="add-item-auth adm-btn-save" value="<?=Loc::getMessage("ADD")?>">
		</td>
		<td width="25%"></td>
		<td>
			<h3>Отображение остатков для НЕАВТОРИЗОВАННЫХ пользователей</h3>
			<table>
				<thead>
				<tr>
					<td>Условие</td>
					<td>Описание</td>
					<td></td>
				</tr>
				<?php for ($i = 0; $i < count($optionRestsConditionsNoAuth); ++$i):?>
					<tr>
						<td><input type="text" maxlength="255" value="<?=$optionRestsConditionsNoAuth[$i]?>"
								   name="<?=RESTS_CONDITION_NO_AUTH?>[]"></td>
						<td><input type="text" maxlength="255" value="<?=$optionRestsDescriptionNoAuth[$i]?>"
								   name="<?=RESTS_DESCRIPTION_NO_AUTH?>[]"></td>
						<td class="delete-item" onclick="bindDelete(this)"><span></span></td>
					</tr>
				<?php endfor;?>
				</thead>
			</table>
			<br>
			<input type="button" class="add-item-no-auth adm-btn-save" value="<?=Loc::getMessage("ADD")?>">
		</td>
	</table>

<br>
<br>
<br>
<input type="submit" name="Save" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>"
	   class="adm-btn-save">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>
<script>
	function bindDelete(elem)
	{
		elem.parentElement.remove();
	}

	BX.ready(function(){
		let addBtnAutn = document.querySelector(".add-item-auth");
		let addBtnNoAutn = document.querySelector(".add-item-no-auth");

		BX.bind(addBtnAutn, "click", function(e){

			let tr = document.createElement("TR");
			let table = addBtnAutn.parentElement.querySelector("table");
			tr.innerHTML = "<tr>" +
				"<td><input type='text' maxlength='255'" +
						   "name='<?=RESTS_CONDITION_AUTH?>[]'></td>" +
				"<td><input type='text' maxlength='255'" +
						   "name='<?=RESTS_DESCRIPTION_AUTH?>[]'></td>" +
				"<td class='delete-item' onclick='bindDelete(this);'><span></span></td>" +
			"</tr>";
			table.append(tr);
		});

		BX.bind(addBtnNoAutn, "click", function(e){

			let tr = document.createElement("TR");
			let table = addBtnNoAutn.parentElement.querySelector("table");
			tr.innerHTML = "<tr>" +
				"<td><input type='text' maxlength='255'" +
				"name='<?=RESTS_CONDITION_NO_AUTH?>[]'></td>" +
				"<td><input type='text' maxlength='255'" +
				"name='<?=RESTS_DESCRIPTION_NO_AUTH?>[]'></td>" +
				"<td class='delete-item' onclick='bindDelete(this);'><span></span></td>" +
				"</tr>";
			table.append(tr);
		});
	});
</script>

<style>
	.delete-item span{
		background: #ef4b4b url("/local/modules/intervolga.custom/img/cross.png") center no-repeat;
		width: 10px;
		height: 10px;
		padding: 6px;
		display: inline-block;
		background-size: 13px;
		border-radius: 50%;
		cursor: pointer;
	}

	.delete-item span:hover{
		background-color: red;
	}
</style>

<?php