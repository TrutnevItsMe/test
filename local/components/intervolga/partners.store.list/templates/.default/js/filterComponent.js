if (!window.FilterComponent){

	window.FilterComponent = {

		/**
		 *
		 * @param {object} params
		 * @param {object} params.result -- $arResult
		 * @param {object} params.params -- $arParams
		 * @param {string} params.detailUrlTemplate
		 */
		init: function (params) {

			this.result = params.result;
			this.params = params.params;
			this.detailUrlTemplate = params.detailUrlTemplate || "";

			this.initFromUrl();
			this.bindEvents();
		},

		/**
		 * Включаем чекбоксы по параметрам из URL
		 */
		initFromUrl: function () {
			if (this.params["FILTER_VALUES"]) {
				this.params["FILTER_VALUES"].forEach(function (field) {
					let param = URLUtils.getAttr(field.replace("UF_", ""));

					if (param) {
						param.split("-or-").forEach(function (checkboxId) {
							BX(checkboxId).click();
						});
					}
				});
			}
		},

		bindEvents: function () {
			this.bindToggleElement();
			this.bindSubmit();
			this.bindReset();
			this.bindResetBlock();
			this.bindSearchCity();
		},

		/**
		 * Биндит раскрытие/сворачивание блока в фильтре
		 */
		bindToggleElement: function () {
			document.querySelectorAll(".filter-item-title-block").forEach(
				function (item) {
					BX.bind(item, "click", function () {
						let block = item.parentElement.querySelector(".filter-block-inner");
						let icon = item.querySelector(".down-icon");

						icon.classList.toggle('rotated');
						$(block).toggle("height");
					});
				}
			);
		},

		/**
		 * Биндит клик по кнопке "применить" фильтра
		 */
		bindSubmit: function () {

			let filter = BX("filter")

			if (filter) {
				let btn = BX("submit-filter-btn");

				BX.bind(btn, "click", function () {

					let urlParams = {};

					// Проходим по всем активным чекбоксам
					BX("filter").querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox) {

						let field = checkbox.getAttribute("data-filter-field");

						if (!urlParams[field]) {
							urlParams[field] = [];
						}

						urlParams[field].push(checkbox.id);
					});

					// Проходим по всем активным radio
					BX("filter").querySelectorAll("input[type='radio']:checked").forEach(function (radio) {

						let field = radio.getAttribute("data-filter-field");

						if (!urlParams[field]) {
							urlParams[field] = [];
						}

						urlParams[field].push(radio.id);
					});

					// Проходим по всем активным option
					BX("filter").querySelectorAll("option[type='option']:checked").forEach(function (radio) {

						let field = radio.getAttribute("data-filter-field");

						if (!urlParams[field]) {
							urlParams[field] = [];
						}

						urlParams[field].push(radio.id);
					});

					// Добавляем в URL параметры для фильтрации
					Object.keys(urlParams).forEach(function (field) {
						let fieldWithoutUF = field.replace("UF_", "");
						let params = urlParams[field].join("-or-");
						URLUtils.setAttr(fieldWithoutUF, params);
					});

					// Формируем параметры, которые нужно удалить из URL
					let deletingParamsFromUrls = ArrayUtils.difference(window.FilterComponent.params["FILTER_VALUES"], Object.keys(urlParams));

					deletingParamsFromUrls.forEach(function (param) {
						URLUtils.delAttr(param.replace("UF_", ""));
					});

					let ids = window.FilterComponent.getIdsFromUrl();

					if (ids && !ids.length && !window.FilterComponent.isSetFilter()) {
						ids = Object.keys(window.FilterComponent.result["ITEMS"]);
					}

					let balloons = window.FilterComponent.getBalloons(ids);

					YandexMap.removeBalloons();

					if (balloons.length) {
						YandexMap.moveTo(
							parseFloat(balloons[0]["x"]),
							parseFloat(balloons[0]["y"])
						).then(function () {
							YandexMap.zoom(12);
						});

						balloons.forEach(function (balloon) {
							YandexMap.setBalloon(
								balloon["x"],
								balloon["y"],
								balloon["hintContent"],
								balloon["balloonContent"]);
						});
					}

					window.FilterComponent.updateAjax();
				});
			}
		},

		isSetFilter: function () {
			let isSet = false;

			if (this.params["FILTER_VALUES"]) {
				this.params["FILTER_VALUES"].forEach(function (field) {
					if (isSet) {
						return; //continue
					}

					if (URLUtils.hasAttr(field.replace("UF_", ""))) {
						isSet = true;
					}
				});
			}

			return isSet;
		},

		bindReset: function ()
		{
			self = this;

			let btn = BX("reset-filter-btn");

			if (btn) {
				BX.bind(btn, "click", function (e) {

					if (self.params["FILTER_VALUES"] && self.params["FILTER_VALUES"].length) {
						self.params["FILTER_VALUES"].forEach(function (field) {

							field = field.replace("UF_", "");
							URLUtils.delAttr(field);
						});
					}

					BX("filter").querySelectorAll("input:checked").forEach(function (input) {
						input.checked = false;
					});

					let balloons = window.FilterComponent.getBalloons();

					if (balloons.length) {
						YandexMap.removeBalloons();

						balloons.forEach(function (balloon) {
							YandexMap.setBalloon(
								balloon["x"],
								balloon["y"],
								balloon["hintContent"],
								balloon["balloonContent"]);
						});
					}

					window.FilterComponent.updateAjax();
				});
			}
		},

		/**
		 * Биндит клик по кнопке очистить значение одного поля фильтра
		 */
		bindResetBlock: function ()
		{
			self = this;

			document.querySelectorAll(".reset-filter-block-btn").forEach(function(btn){
				BX.bind(btn, "click", function(e)
				{
					let filterParam = btn.getAttribute("data-filter-field");
					URLUtils.delAttr(filterParam.replace("UF_", ""));
					let ids = [];

					if (window.FilterComponent.isSetFilter())
					{
						ids = window.FilterComponent.getIdsFromUrl();
					}
					else
					{
						ids = Object.keys(self.result["ITEMS"]);
					}

					document.querySelectorAll("input[data-filter-field='" + filterParam +"']").forEach(function(input){
						input.checked = false;
					});

					let balloons = window.FilterComponent.getBalloons(ids);

					YandexMap.removeBalloons();

					if (balloons.length) {

						balloons.forEach(function (balloon) {
							YandexMap.setBalloon(
								balloon["x"],
								balloon["y"],
								balloon["hintContent"],
								balloon["balloonContent"]);
						});
					}

					window.FilterComponent.updateAjax();
				});
			});
		},

		/**
		 * Обновляет пагинацию и элементы через ajax
		 */
		updateAjax: function()
		{
			window.FilterComponent.startAjaxAnimation();

			BX.ajax({
				url: window.location.href,
				method: "GET",
				dataType: "html",
				scriptsRunFirst: false,
				emulateOnload: false,
				processData: false,
				onsuccess: function(response)
				{
					window.FilterComponent.finishAjaxAnimation();

					let elem = document.createElement("div");
					elem.innerHTML = response;
					BX("items").innerHTML = elem.querySelector("#items").innerHTML;

					let items = BX("items").querySelectorAll(".item-wrap");

					items.forEach(function(item){
						window.ElementComponent.bindClickElem(item);
					});

					let paginationBlock = document.querySelector(".module-pagination");

					if (!paginationBlock)
					{
						let rootElem = BX("items").parentElement.parentElement.parentElement;
						let paginationElem = document.createElement("div");
						BX.addClass(paginationElem, "module-pagination");
						rootElem.append(paginationElem);
						paginationBlock = document.querySelector(".module-pagination");
					}

					if (elem.querySelector(".module-pagination"))
					{
						paginationBlock.innerHTML = elem.querySelector(".module-pagination").innerHTML;

						paginationBlock.querySelectorAll(".pagination-item").forEach(function(pagItem){
							window.PaginationComponent.bindClickPaginationItem(pagItem);
						});
					}
					else
					{
						paginationBlock.innerHTML = "";
					}

					window.ElementComponent.setItemsOneHeight();
				},
				onfailure: function()
				{
					window.FilterComponent.finishAjaxAnimation();
				}
			});
		},

		startAjaxAnimation: function()
		{
			let loader = document.querySelector(".loader");

			if (loader)
			{
				BX.removeClass(loader, "d-none");
				BX.addClass(loader, "round");
			}
		},

		finishAjaxAnimation: function()
		{
			let loader = document.querySelector(".loader");

			if (loader)
			{
				BX.removeClass(loader, "round");
				BX.addClass(loader, "d-none");
			}
		},

		/**
		 * Формирует массив ID элементов по GET параметрам
		 * @returns {*}
		 */
		getIdsFromUrl: function(){

			let mapFieldIds = {};

			if (this.params["FILTER_VALUES"])
			{
				this.params["FILTER_VALUES"].forEach(function(field){
					let params = URLUtils.getAttr(field.replace("UF_", ""));

					if (!params)
					{
						return;
					}

					params = params.split("-or-");

					if (!mapFieldIds[field])
					{
						mapFieldIds[field] = [];
					}

					params.forEach(function(param){
						let ids = BX(param).getAttribute("data-filter-items").split(",");
						mapFieldIds[field] = mapFieldIds[field].concat(ids);
					});
				});
			}

			let ids = ArrayUtils.intersectionArray(Object.values(mapFieldIds));
			return ids;
		},

		/**
		 * Возвращает сформированные метки карты по ID
		 * @param {array} ids
		 */
		getBalloons: function(ids){

			let balloons = [];

			if (!ids)
			{
				ids = Object.keys(window.FilterComponent.result["ITEMS"]);
			}

			let detailUrlTemplate = this.detailUrlTemplate;

			ids.forEach(function (id){
				let item = window.FilterComponent.result["ITEMS"][parseInt(id)];
				if (!item || !item["COORDINATES"]["x"] || !item["COORDINATES"]["y"])
				{
					return; // continue
				}

				let detailUrl = detailUrlTemplate = detailUrlTemplate.replace("#ELEMENT_ID#", id);

				if (!detailUrl.includes("?"))
				{
					detailUrl += "?";
				}

				for (const [key, value] of Object.entries(URLUtils.getAttrs())) {
					detailUrl += "&"+key+"="+value;
				}

				if (URLUtils.getAttr("backurl"))
				{
					detailUrl += "&backurl=" + URLUtils.getAttr("backurl");
				}

				let balloon = {
					x: parseFloat(item["COORDINATES"]["x"]),
					y: parseFloat(item["COORDINATES"]["y"]),
					hintContent: "<a class='ymap-balloon-link' onclick='window.ElementComponent.clickDetailShop(this)' href='" + detailUrl + "'>" + item["UF_NAME"] + "</a><br>",
					balloonContent: "<a class='ymap-balloon-link' onclick='window.ElementComponent.clickDetailShop(this)' href='" + detailUrl + "'>" + item["UF_NAME"] + "</a><br>"
				};

				if (item["UF_VREMYARABOTY"])
				{
					balloon["hintContent"] += "<b>" + BX.message("WORKTIME") + ":</b> " + item["UF_VREMYARABOTY"] + "<br>";
					balloon["balloonContent"] += "<b>" + BX.message("WORKTIME") + ":</b> " + item["UF_VREMYARABOTY"]  + "<br>";
				}

				let phones = [];
				let emails = [];
				let addresses = [];

				if (item["KONTRAGENTS"])
				{
					item["KONTRAGENTS"].forEach(function(kontragent){

						if (kontragent["UF_ELEKTRONNAYAPOCHT"])
						{
							emails.push(kontragent["UF_ELEKTRONNAYAPOCHT"]);
						}

						if (kontragent["UF_TELEFON"])
						{
							phones.push(kontragent["UF_TELEFON"]);
						}

						if (kontragent["UF_YURIDICHESKIYADRE"])
						{
							addresses.push(kontragent["UF_YURIDICHESKIYADRE"]);
						}
					});
				}

				if (phones.length)
				{
					balloon["hintContent"] += "<b>" + BX.message("PHONE") + ":</b> " + phones.join(",") + "<br>";
					balloon["balloonContent"] += "<b>" + BX.message("PHONE") + ":</b> " + phones.join(",") + "<br>";
				}

				if (emails.length)
				{
					balloon["hintContent"] += "<b>" + BX.message("EMAIL") + ":</b> " + emails.join(",") + "<br>";
					balloon["balloonContent"] += "<b>" + BX.message("EMAIL") + ":</b> " + emails.join(",") + "<br>";
				}

				if (addresses.length)
				{
					balloon["hintContent"] += addresses.join("<br>") + "<br>";
					balloon["balloonContent"] += addresses.join("<br>") + "<br>";
				}

				balloons.push(balloon);
			});

			return balloons;
		},

		bindSearchCity: function()
		{
			let searchInput = document.getElementById("city-filter-search");

			if (searchInput)
			{
				BX.bind(searchInput, "keyup", function(e){

					let searchValue = searchInput.value

					searchInput.parentElement.querySelectorAll(".filter-value-block").forEach(function (filterElemBlock){

						if (searchInput.value == "")
						{
							BX.removeClass(filterElemBlock, "d-none");
						}

						let city = filterElemBlock.querySelector(".filter-value-item").innerHTML;

						if (city.toUpperCase().includes(searchValue.toUpperCase()))
						{
							BX.removeClass(filterElemBlock, "d-none");
						}
						else
						{
							BX.addClass(filterElemBlock, "d-none");
						}
					});
				});
			}
		}
	};
}