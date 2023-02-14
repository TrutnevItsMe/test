
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

	window.ElementComponent.bindClickElems();

});