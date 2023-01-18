
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
	 * Добавляем текущую страницу в параметр backurl в href ссылки элемента
	 * */
	document.querySelectorAll(".item-field-block > a").forEach(function (link){

		BX.bind(link, "click", function(e){
			let href = link.href;
			let url = new URL(href);
			url.searchParams.set("backurl", window.location.href);
			href = url.toString();
			link.href = href;
		});
	});

	window.ElementComponent.setItemsOneHeight();
	window.ElementComponent.bindClickElems();

});