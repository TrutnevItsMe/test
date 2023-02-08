class CustomCheckbox{

	static #checkedClass = "custom-checkbox-active";
	static #customCheckboxClass = "custom-checkbox";

	static toggleCheckbox(id)
	{
		let checkbox = BX(id);
		let customCheckbox = document.querySelector("."+CustomCheckbox.#customCheckboxClass+"[for='" + id + "']");

		if (checkbox.checked)
		{
			BX.addClass(customCheckbox, CustomCheckbox.getCheckedClass());
		}
		else
		{
			BX.removeClass(customCheckbox, CustomCheckbox.getCheckedClass());
		}
	}

	static getCheckedClass()
	{
		return CustomCheckbox.#checkedClass;
	}
}