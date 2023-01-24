if (!window.FilterComponent){

	window.FilterComponent = {

		/**
		 *
		 * @param {object} params
		 * @param {object} params.result -- $arResult
		 * @param {object} params.params -- $arParams
		 * @param {string} params.detailUrlTemplate
		 */
		init: function(params){

			this.result = params.result;
			this.params = params.params;
			this.detailUrlTemplate = params.detailUrlTemplate || "";

			this.initFromUrl();
			this.bindEvents();
		},

		/**
		 * Включаем чекбоксы по параметрам из URL
		 */
		initFromUrl: function()
		{
			this.params["FILTER_VALUES"].forEach(function(field){
				let param = URLUtils.getAttr(field.replace("UF_", ""));

				if (param)
				{
					param.split("-or-").forEach(function(checkboxId){
						BX(checkboxId).click();
					});
				}
			});
		},

		bindEvents: function(){
			this.bindToggleElement();
			this.bindSubmit();
		},

		/**
		 * Биндит раскрытие/сворачивание блока в фильтре
		 */
		bindToggleElement: function(){
			document.querySelectorAll(".filter-item-title-block").forEach(
				function(item){
					BX.bind(item, "click", function(){
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
		bindSubmit: function(){

			let btn = BX("filter").querySelector(".btn");

			BX.bind(btn, "click", function(){

				let urlParams = {};

				// Проходим по всем активным чекбоксам
				BX("filter").querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox){

					let field = checkbox.getAttribute("data-filter-field");

					if (!urlParams[field]){
						urlParams[field] = [];
					}

					urlParams[field].push(checkbox.id);
				});

				// Добавляем в URL параметры для фильтрации
				Object.keys(urlParams).forEach(function (field){
					let fieldWithoutUF = field.replace("UF_", "");
					let params = urlParams[field].join("-or-");
					URLUtils.setAttr(fieldWithoutUF, params);
				});

				// Формируем параметры, которые нужно удалить из URL
				let deletingParamsFromUrls = ArrayUtils.difference(window.FilterComponent.params["FILTER_VALUES"], Object.keys(urlParams));

				deletingParamsFromUrls.forEach(function(param){
					URLUtils.delAttr(param.replace("UF_", ""));
				});

				let ids = window.FilterComponent.getIdsFromUrl();

				if (!ids.length)
				{
					ids = Object.keys(window.FilterComponent.result["ITEMS"]);
				}

				let balloons = window.FilterComponent.getBalloons(ids);

				if (balloons.length)
				{
					YandexMap.removeBalloons();

					balloons.forEach(function(balloon){
						YandexMap.setBalloon(
							balloon["x"],
							balloon["y"],
							balloon["hintContent"],
							balloon["balloonContent"]);


					});
				}
			});
		},

		/**
		 * Формирует массив ID элементов по GET параметрам
		 * @returns {*}
		 */
		getIdsFromUrl: function(){

			let mapFieldIds = {};

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
				return [];
			}

			let detailUrlTemplate = this.detailUrlTemplate;

			ids.forEach(function (id){
				let item = window.FilterComponent.result["ITEMS"][parseInt(id)];

				if (!item || !item["COORDINATES"])
				{
					return; // continue
				}

				let detailUrl = detailUrlTemplate = detailUrlTemplate.replace("#ELEMENT_ID#", id);

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
					hintContent: "<a class='ymap-balloon-link' onclick='YandexMap.clickBalloonHint(this)' href='" + detailUrl + "'>" + item["UF_NAME"] + "</a><br>",
					balloonContent: "<a class='ymap-balloon-link' onclick='YandexMap.clickBalloonHint(this)' href='" + detailUrl + "'>" + item["UF_NAME"] + "</a><br>"
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
		}
	};
}