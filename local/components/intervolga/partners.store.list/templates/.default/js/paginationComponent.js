if (!window.PaginationComponent) {
	window.PaginationComponent = {
		/**
		 *
		 * @param {object} params
		 * @param {object} params.result
		 * @param {object} params.params
		 */
		init: function (params) {
			this.result = params.result;
			this.params = params.params;

			this.bindEvents();
		},

		bindEvents: function () {
			self = this;

			document.querySelectorAll(".pagination-item").forEach(function (paginationItem) {
				self.bindClickPaginationItem(paginationItem);
			});
		},

		bindClickPaginationItem: function (item) {
			self = this;

			BX.bind(item, "click", function (e) {

				if (self.params["PAGINATION_AJAX"] === "Y") {
					e.preventDefault();

					let re = /(page=[\w\-\d]*)/;
					let attr = re.exec(item.href);

					if (attr.length)
					{
						URLUtils.setAttr(
							attr[0].split("=")[0],
							attr[0].split("=")[1]
						)
					}

					BX.ajax({
						url: item.href,
						method: "GET",
						scriptsRunFirst: false,
						emulateOnload: false,
						processData: false,
						dataType: "html",
						onsuccess: function (response) {

							// Устанавливаем get параметры в url
							for (const [key, value] of Object.entries(URLUtils.getAttrs())) {
								URLUtils.setAttr(key, value);
							}

							let elem = document.createElement("div");
							elem.innerHTML = response;

							let items = BX("items");
							let paginations = document.querySelector(".module-pagination");

							let easing = new BX.easing({
								duration : 500,
								start : { opacity : 100 },
								finish : { opacity: 0 },
								transition : BX.easing.transitions.quart,
								step : function(state){
									items.style.opacity = state.opacity/100;
								},
								complete : function() {
									items.style.opacity = 100;
									// Заменяем элементы на новые
									BX("items").innerHTML = elem.querySelector("#items").innerHTML;
									window.ElementComponent.bindClickElems();
									window.ElementComponent.setItemsOneHeight();
									window.ElementComponent.addGetParamsToDetail();
								}
							});

							easing.animate();

							easing = new BX.easing({
								duration : 500,
								start : { opacity : 100 },
								finish : { opacity: 0 },
								transition : BX.easing.transitions.quart,
								step : function(state){
									paginations.style.opacity = state.opacity/100;
								},
								complete : function() {
									paginations.style.opacity = 100;
									// Заменяем пагинацию на новую
									paginations.innerHTML = elem.querySelector(".module-pagination").innerHTML;

									paginations.querySelectorAll(".pagination-item").forEach(function (paginationItem) {
										window.PaginationComponent.bindClickPaginationItem(paginationItem);
									});
								}
							});

							easing.animate();
						},
						onfailure: function () {
							console.error("REQUEST ERROR");
						}
					});
				}
			});
		}
	};
}