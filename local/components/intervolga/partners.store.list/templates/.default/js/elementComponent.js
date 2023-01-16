if (!window.ElementComponent)
{
	window.ElementComponent = {

		bindClickElem: function(elem)
		{
			BX.bind(elem, "click", function(){

				let coords = elem.getAttribute("data-coordinates");

				if (coords)
				{
					coords = coords.split("_");
					let x = coords[0];
					let y = coords[1];

					YandexMap.moveTo(parseFloat(x), parseFloat(y)).then(
						function(){
							YandexMap.zoom(12);
						});
				}
			});
		},

		bindClickElems: function()
		{
			let items = document.querySelectorAll(".item-wrap");

			items.forEach(function(item){
				window.ElementComponent.bindClickElem(item);
			});
		},

		/**
		 *  Делаем все элементы одной высоты
		 */
		setItemsOneHeight: function()
		{
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
		}
	};
}