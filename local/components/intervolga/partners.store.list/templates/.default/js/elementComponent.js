if (!window.ElementComponent)
{
	window.ElementComponent = {

		bindClickElem: function(elem)
		{
			BX.bind(elem, "click", function(){

				let coords = elem.getAttribute("data-coordinates");

				if (coords)
				{
					coords = coords.split(",");
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
		},

		addGetParamsToDetail: function()
		{
			let items = document.querySelectorAll(".item-wrap");
			let getParams = URLUtils.getAttrs();

			items.forEach(function(item){
				let elemUrl = item.href;

				if (!elemUrl)
				{
					elemUrl = "";
				}

				if (!elemUrl.includes("?"))
				{
					elemUrl += "?";
					for (const [key, value] of Object.entries(getParams)) {
						elemUrl += "&" + key + "=" + value;
					}
				}
				else
				{
					let strGetParams = [];

					elemUrl.split("&").forEach(function(elemGetParam){

						if (!elemGetParam)
						{
							return; // continue
						}

						let key = elemUrl.split("=")[0];
						strGetParams.push(key);
					});

					for (const [key, value] of Object.entries(getParams)) {

						if (!strGetParams.includes(key))
						{
							elemUrl += "&" + key + "=" + value;
						}
					}
				}

				item.href = elemUrl;
			});
		},

		/**
		 * Клик по ссылке на детальную страницу
		 * @param {Element} link
		 */
		clickDetailShop: function(link)
		{
			let href = link.href;
			let url = new URL(href);
			url.searchParams.set("backurl", window.location.href);
			href = url.toString();
			link.href = href;
		}
	};
}