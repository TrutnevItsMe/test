window.OffersFilterComponent = {

	/**
	 *
	 * @param {Object} params
	 * @param {Object} params.result -- $arResult
	 * @param {Object} params.params -- $arParams
	 * @param {Object} params.classActiveOfferValueItem -- css класс выбранного значения фильтра
	 * @param {Object} params.classOfferValueItem -- css класс выбранного значения фильтра
	 * @param {Object} params.classOfferValueItem -- css класс html блока значения фильтра
	 * @param {Object} params.classOfferValueContainer -- css класс html блока для групппы значений св--ва
	 * @param {Object} params.classInactive -- css класс недоступного для выбора значения
	 */
	init: function (params)
	{
		self = this;
		this.result = params.result;
		this.params = params.params;

		this.classActiveOfferValueItem = params.classActiveOfferValueItem || "active-offers-filter-item";
		this.classOfferValueItem = params.classOfferValueItem || "offers-filter-item";
		this.classOfferValueContainer = params.classOfferValueContainer || "offers-filter-item-container";
		this.classAccessibleOfferValue = params.classAccessibleOfferValue || "accessible-offer-filter-value";
		this.classSelectedOfferValue = params.classSelectedOfferValue || "accessible-offer-filter-value";
		// Класс, указывающий, что данное предложение недоступно (нельзя выбрать значение в фильтре по предложениям)
		this.classInactive = params.classInactive || "inactive";
		this.currentFilterValues = {};
		this.selectedFilterValues = {};

		// JQuery элемент, на который кликнули
		this.clickedItem = null;

		if (typeof (this.params["FILTER_OFFERS_PROPERTY_CODE"]) == "object")
		{
			// Значения св-в, участвующих в фильтрации
			this.filterProps = Object.values(this.params["FILTER_OFFERS_PROPERTY_CODE"]);
		}

		// ID предложений, помещенных в корзину
		this.offersInBasket = [];

		if (this.result["OFFERS"])
		{
			Object.values(this.result["OFFERS"]).forEach(function (offer)
			{
				if (offer["IN_BASKET"] == "Y")
				{
					self.offersInBasket.push(offer["ID"]);
				}
			});
		}

		if (this.result["CURRENT_OFFER"] && this.offersInBasket.includes(this.result["CURRENT_OFFER"]["ID"]))
		{
			document.querySelector(".btn.in-cart").style.display = "block";
			document.querySelector(".btn.to-cart").style.display = "none";
		}
		else
		{
			document.querySelector(".btn.in-cart").style.display = "none";
			document.querySelector(".btn.to-cart").style.display = "block";
		}

		this.initFilterValues();
		this.setCharacters();

		let prices = {
			price: 0,
			old: 0
		}

		if (this.result["PRICE_MATRIX"])
		{
			let currentCurrencyIndex = Object.keys(this.result["PRICE_MATRIX"]["COLS"])[0];
			prices.price = this.result["PRICE_MATRIX"]["MATRIX"][currentCurrencyIndex][0]["PRICE"];

			if (this.result["CURRENT_OFFER"])
			{
				$('.button_block .btn.to-cart').attr("data-item", this.result["CURRENT_OFFER"]["ID"]);
			}
		}

		let price = calculatePrice();

		if (price.price > 0)
		{
			showPrice(price);
		}
		else
		{
			showPrice(prices);
		}

		this.bindEvents();

	},

	/**
	 * Устанавливает текущее значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @param {string} value
	 */
	setSelectedFilterValue: function (prop, value)
	{
		this.selectedFilterValues[prop] = value;
	},

	/**
	 * Возвращает текущще значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @return {string}
	 */
	getSelectedFilterValue: function (prop)
	{
		return this.selectedFilterValues[prop];
	},

	/**
	 * Устанавливает текущее значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @param {string} value
	 */
	setCurrentFilterValue: function (prop, value)
	{
		this.currentFilterValues[prop] = value;
	},

	/**
	 * Возвращает текущще значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @return {string}
	 */
	getCurrentFilterValue: function (prop)
	{
		return this.currentFilterValues[prop];
	},

	/**
	 * Инициализирует текущее значение фильтра
	 */
	initFilterValues: function ()
	{
		self = this;

		if (!this.currentFilterValues)
		{
			this.currentFilterValues = {};
		}

		if (self.result["CURRENT_OFFER"])
		{
			this.filterProps.forEach(function (prop)
			{
				self.setCurrentFilterValue(prop, self.result["CURRENT_OFFER"]["PROPERTIES"][prop]["VALUE"]);
			});
		}
		else
		{
			this.filterProps.forEach(function (prop)
			{
				self.setCurrentFilterValue(prop, "");
			});
		}

		this.filterProps.forEach(function (prop)
		{
			self.setSelectedFilterValue(prop, "");
		});
	},

	/**
	 * Очищает все текущие значения фильтра
	 */
	clearSelectedFilterValues: function ()
	{
		self = this;
		this.filterProps.forEach(function (prop)
		{
			self.setSelectedFilterValue(prop, "");
		});
	},

	/**
	 * Очищает все совместимые элементы (имеющие соответствующий класс)
	 */
	clearAccessibleItems: function()
	{
		self = this;

		$("." + this.classAccessibleOfferValue + ".grow-up").each(function()
		{
			self.unscale($(this));
			$(this).removeClass(self.classAccessibleOfferValue);
		});
	},

	/**
	 * Очищает все выбранные элеметы (имеющие соответствующий класс)
	 */
	clearSelectedItems: function()
	{
		$("." + this.classSelectedOfferValue).removeClass(self.classSelectedOfferValue);
	},

	/**
	 * Устанавливает для значений фильтра совместимые значения для выбранных значений
	 */
	setAccessibleFilterItems: function ()
	{
		self = this;
		let accessibleItems = self.getAccessibleFilterItems();
		self.clearAccessibleItems();

		if (Object.keys(accessibleItems).length == 0)
		{
			self.clearSelectedItems();
			return;
		}

		let isAllAccessible = true;

		this.filterProps.forEach(function(prop)
		{
			if (accessibleItems[prop].length == 0)
			{
				self.clearSelectedItems();
				self.clearSelectedFilterValues();

				if (self.clickedItem)
				{
					self.setSelectedFilterValue(self.clickedItem.data("column"), self.clickedItem.data("value"));
					self.clickedItem.addClass(self.classSelectedOfferValue);
				}

				self.setAccessibleFilterItems();
				isAllAccessible = false;
				return;
			}
		});

		if (!isAllAccessible)
		{
			return;
		}

		this.filterProps.forEach(function(prop)
		{
			accessibleItems[prop].forEach(function(value)
			{
				let elem = $("." + self.classOfferValueItem + "[data-column='" + prop + "'][data-value='" + value + "']");

				if (elem.length > 0)
				{
					elem.addClass(self.classAccessibleOfferValue);
					self.scale(elem);
				}
			});
		});


		console.log(accessibleItems);
	},

	/**
	 * Создает Object доступных полей для фильтрации
	 * { св-во: [список значений] }
	 * @return {Object}
	 */
	getAccessibleFilterItems: function ()
	{
		self = this;
		let accessibleItems = {};

		// Пробегаем все св-ва, участвующие в фильтрации
		this.filterProps.forEach(function (prop)
		{
			// Выбранное значение в фильтре для св-ва
			let value = self.getSelectedFilterValue(prop);

			self.filterProps.forEach(function (propCode)
			{
				if (self.result["OFFERS_MAP_FILTER"][prop] && self.result["OFFERS_MAP_FILTER"][prop][value])
				{
					let ar = [];

					// Выбираем все доступные значения для выбранных св-в в фильтре
					self.result["OFFERS_MAP_FILTER"][prop][value].forEach(function (offer)
					{
						if (!accessibleItems[propCode])
						{
							accessibleItems[propCode] = [];
						}

						if (offer["ACTIVE_OFFER"])
						{
							ar.push(offer["PROPERTIES"][propCode] ? offer["PROPERTIES"][propCode]["VALUE"] : "");
						}
					});

					accessibleItems[propCode].push(ar);
				}
			});
		});

		// Выбираем пересечения из всех возможных св-в => получаем доступные поля при выбранных значениях
		Object.keys(this.selectedFilterValues).forEach(function (prop)
		{
			if (accessibleItems[prop])
			{
				let _intersect = accessibleItems[prop][0];

				for (let i = 1; i < accessibleItems[prop].length; ++i)
				{
					_intersect = self.intersection(_intersect, accessibleItems[prop][i]);
				}

				accessibleItems[prop] = _intersect;
				accessibleItems[prop] = Array.from((new Set(accessibleItems[prop])));
			}
		});

		return accessibleItems;
	},

	/**
	 * Пересечение 2-х множеств
	 * @param {Array} array1
	 * @param {Array} array2
	 * @return {Array} {*}
	 */
	intersection: function (array1, array2)
	{
		return array1.filter(value => array2.includes(value));
	},

	bindEvents: function ()
	{
		this.bindChangeOfferFilter("." + this.classOfferValueItem);
		this.bindAddToBasket();
	},

	/**
	 * Выводит в html верстку текущее выбранное значение для св-ва
	 * @param {string} column
	 * @param {string} value
	 */
	setColumnValueTitle: function (column, value)
	{
		let propContainer = $("." + this.classOfferValueContainer + "[data-column='" + column + "']");

		if (propContainer.length > 0)
		{
			propContainer.prev(".prop-current-value").html(value);
		}
	},

	/**
	 * Выводит все текущие значения выбранных св-в в html верстку
	 */
	setCurrentValueTitles: function ()
	{
		self = this;

		Object.keys(this.currentFilterValues).forEach(function (prop)
		{
			self.setColumnValueTitle(prop, self.getCurrentFilterValue(prop));
		});
	},

	/**
	 * Возвращает true, если выбраны все поля значений из фильтра
	 * @return {boolean}
	 */
	isAllValuesSelected: function ()
	{
		self = this;
		let isAll = true;

		Object.keys(this.selectedFilterValues).forEach(function (prop)
		{
			if (self.getSelectedFilterValue(prop) == "")
			{
				isAll = false;
				return;
			}
		});

		return isAll;
	},

	/**
	 * Возвращает предложение, соответствующе всем выбранным значениям св-в
	 * @return {null|boolean}
	 */
	getCurrentOffer: function ()
	{
		self = this;
		let returnsOffer = null;

		Object.keys(self.currentFilterValues).forEach(function (prop)
		{
			if (self.result["OFFERS_MAP_FILTER"][prop][self.getCurrentFilterValue(prop)].length == 1)
			{
				returnsOffer = self.result["OFFERS_MAP_FILTER"][prop][self.getCurrentFilterValue(prop)][0];
				return;
			}
		});

		// все предложения встречаются по несколько раз
		if (!returnsOffer)
		{
			Object.values(this.result['OFFERS']).forEach(function (offer)
			{
				let isCurrentOffer = true;

				Object.keys(self.currentFilterValues).forEach(function (prop)
				{
					if (offer["PROPERTIES"][prop]["VALUE"] != self.getCurrentFilterValue(prop))
					{
						isCurrentOffer = false;
						return;
					}
				});

				if (isCurrentOffer)
				{
					returnsOffer = offer;
					return;
				}
			});
		}

		return returnsOffer;
	},

	/**
	 * Устанавливает html верстку и атрибуты для выбранного предложения
	 */
	setCurrentOffer: function ()
	{
		if (!this.templateSets)
		{
			this.templateSets = $("#sets-template").html();
		}

		let template = this.templateSets; // Mustache шаблон
		let currentOffer = this.getCurrentOffer();

		if (currentOffer)
		{
			// У предложения есть набор
			if (currentOffer && currentOffer["SET"] && currentOffer["SET"].length > 0)
			{
				Mustache.parse(template);
				let newHtmlSets = Mustache.render(template, {"ITEMS": currentOffer["SET"]});
				$(".set_new").html(newHtmlSets);
			}

			let price = calculatePrice();

			// Устанавливаем цену из набора
			if (price.price > 0)
			{
				showPrice(price);
			}
			// устанавливаем цену из предлоржения
			else
			{
				showPrice({
					price: currentOffer["CATALOG_PURCHASING_PRICE"],
					old: currentOffer["CATALOG_PURCHASING_PRICE"]
				});
			}

			// Меняем картинки в слайдере
			let htmlSlider = "";
			let i = 0;

			for (let j = 0; j < currentOffer["MORE_PHOTO"].length; ++j)
			{
				let _class = !j ? "current" : "";

				htmlSlider += "<li class='" + _class + "' " +
					" data-slide_key='" + j + "'" +
					" data-big_img='" + currentOffer["MORE_PHOTO"][i]["BIG"]["src"] + "'" +
					" data-small_img='" + currentOffer["MORE_PHOTO"][i]["SMALL"]["src"] + "'>" +
					"<span>" +
					"<img class='xzoom-gallery'" +
					" data-xpreview='" + currentOffer["MORE_PHOTO"][i]["THUMB"]["src"] + "'" +
					" src='" + currentOffer["MORE_PHOTO"][i]["THUMB"]["src"] + "'" +
					" alt='" + currentOffer["MORE_PHOTO"][i]["ALT"] + "'" +
					" title='" + currentOffer["MORE_PHOTO"][i]["TITLE"] + "'>" +
					"</span>" +
					"</li>";

				++i;
			}

			if (currentOffer["PREVIEW_PICTURE"])
			{
				htmlSlider += "<li class='' " +
					" data-slide_key='" + i + "'" +
					" data-big_img='" + currentOffer["PREVIEW_PICTURE"]["SRC"] + "'" +
					" data-small_img='" + currentOffer["PREVIEW_PICTURE"]["SRC"] + "'>" +
					"<span>" +
					"<img class='xzoom-gallery'" +
					" data-xpreview='" + currentOffer["PREVIEW_PICTURE"]["SRC"] + "'" +
					" src='" + currentOffer["PREVIEW_PICTURE"]["SRC"] + "'" +
					" alt='" + currentOffer["PREVIEW_PICTURE"]["NAME"] + "'" +
					" title='" + currentOffer["PREVIEW_PICTURE"]["NAME"] + "'>" +
					"</span>" +
					"</li>";

				++i;
			}

			if (currentOffer["DETAIL_PICTURE"])
			{
				htmlSlider += "<li class='' " +
					" data-slide_key='" + i + "'" +
					" data-big_img='" + currentOffer["DETAIL_PICTURE"]["SRC"] + "'" +
					" data-small_img='" + currentOffer["DETAIL_PICTURE"]["SRC"] + "'>" +
					"<span>" +
					"<img class='xzoom-gallery'" +
					" data-xpreview='" + currentOffer["DETAIL_PICTURE"]["SRC"] + "'" +
					" src='" + currentOffer["DETAIL_PICTURE"]["SRC"] + "'" +
					" alt='" + currentOffer["DETAIL_PICTURE"]["NAME"] + "'" +
					" title='" + currentOffer["DETAIL_PICTURE"]["NAME"] + "'>" +
					"</span>" +
					"</li>";

				++i;
			}

			$("#thumbs").html(htmlSlider);
			window.slider.reloadSlider();
			$('.button_block .btn.to-cart').attr("data-item", currentOffer["ID"]);

			// Показываем, что товар уже в корзине
			if (this.offersInBasket.includes(currentOffer["ID"]))
			{
				$('.button_block .btn.to-cart').hide();
				$(".btn.in-cart").show();
			}
			else
			{
				$('.button_block .btn.to-cart').show();
				$(".btn.in-cart").hide();
			}
		}

		this.setCharacters();
	},

	/**
	 * Биндит клик по элементу фильтра
	 * @param {string | JQuery} selectorItem
	 */
	bindChangeOfferFilter: function (selectorItem)
	{
		self = this;

		$(selectorItem).on("click", function (e)
		{
			e.preventDefault();

			self.clickedItem = $(this);

			// Данное св-во есть хотя бы у 1 предложения
			if (!$(this).hasClass(self.classInactive))
			{
				if (!$(this).hasClass(self.classSelectedOfferValue))
				{
					$(this).addClass(self.classSelectedOfferValue);
					let notCurensElems = $("." + self.classOfferValueItem + "[data-column='" + $(this).data("column") + "']").not(this);

					if (notCurensElems.length > 0)
					{
						notCurensElems.removeClass(self.classSelectedOfferValue);
					}

					self.scale(this);

					self.setSelectedFilterValue(self.clickedItem.data("column"), self.clickedItem.data("value"));
				}
				else
				{
					$(this).removeClass(self.classSelectedOfferValue);
					self.unscale(this);

					self.setSelectedFilterValue(self.clickedItem.data("column"), "");
				}

				self.setAccessibleFilterItems();

				if (self.isAllValuesSelected())
				{
					self.swapFilterValues();
					self.clearSelectedFilterValues();
					self.setActiveItems();
					self.setCurrentValueTitles();
					self.setCurrentOffer();
				}
			}
		});
	},

	/**
	 * Перемещает значения выбранных св-в фильтра в текущий фильтр ( selected -> current )
	 */
	swapFilterValues: function()
	{
		self = this;

		this.filterProps.forEach(function (prop)
		{
			let value = self.getSelectedFilterValue(prop);
			self.setCurrentFilterValue(prop, value);
		});
	},

	setActiveItems: function ()
	{
		self = this;

		$("." + this.classActiveOfferValueItem).removeClass(this.classActiveOfferValueItem);

		this.filterProps.forEach(function (prop)
		{
			let value = self.getCurrentFilterValue(prop);
			let elem = $("." + self.classOfferValueItem + "[data-column='" + prop + "'][data-value='" + value +"']");

			if (elem.length > 0)
			{
				elem.addClass(self.classActiveOfferValueItem);
			}
		});

		this.clearAccessibleItems();
		this.clearSelectedItems();
	},

	/**
	 *
	 * @param {Element | JQuery | String} elem
	 */
	scale: function(elem)
	{
		$(elem).removeClass("grow-down");
		$(elem).addClass("grow-up");
	},

	/**
	 *
	 * @param {Element | JQuery | String} elem
	 */
	unscale: function(elem)
	{
		$(elem).removeClass("grow-up");
		$(elem).addClass("grow-down");
	},

	bindAddToBasket: function ()
	{
		self = this;

		$('.button_block .btn.to-cart').on("click", function ()
		{
			self.offersInBasket.push($(this).attr("data-item"));
			$(".btn.in-cart").show();
		});
	},

	/**
	 * Устанавливает характеристики выбранного предложения в tab с характеристиками
	 */
	setCharacters: function ()
	{
		self = this;
		let charactersNode = document.querySelector(".props_list");

		if (charactersNode)
		{
			this.filterProps.forEach(function (prop)
			{
				let charPropNode = charactersNode.querySelector("[data-prop='" + prop + "']");

				if (charPropNode)
				{
					charPropNode.querySelector(".char_value").innerHTML = "<span>" + self.getCurrentFilterValue(prop) + "</span>";
				}
			});
		}
	}
};