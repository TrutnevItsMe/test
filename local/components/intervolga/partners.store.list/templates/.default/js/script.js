
BX.ready(function()
{
	/**
	 * Биндим переключение чекбокса в фильтре
	 * */
	document.querySelectorAll(".filter-value-block input[type='checkbox']").forEach(
		function (elem) {
			BX.bind(elem, "change", function(){
				CustomCheckbox.toggleCheckbox(elem.id);
			});
		});

	/**
	 *  Делаем все элементы одной высоты
	 */
	let maxHeight = 0;
	let items = document.querySelectorAll(".item-wrap");

	items.forEach(
		function (item){

			if (maxHeight < item.offsetHeight)
			{
				maxHeight = item.offsetHeight;
			}
		}
	);

	items.forEach(
		function (item) {
			item.style.height = maxHeight + "px";
		}
	)
});