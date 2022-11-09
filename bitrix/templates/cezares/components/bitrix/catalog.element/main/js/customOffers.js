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
		this.classInaccessibleOfferValue = params.classInaccessibleOfferValue || "inaccessible-offer-filter-value";
		// Класс, указывающий, что данное предложение недоступно (нельзя выбрать значение в фильтре по предложениям)
		this.classInactive = params.classInactive || "inactive";
		this.currentFilterValues = {};

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
		this.setAccessibleFilterItems();

		if (this.result["PRICE_MATRIX"])
		{
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

		this.bindEvents();

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

	clearCurrentFilterValues: function ()
	{
		self = this;

		this.filterProps.forEach(function (prop)
		{
			self.setCurrentFilterValue(prop, "");
		});
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
	},

	/**
	 * Очищает все совместимые элементы (имеющие соответствующий класс)
	 */
	clearAccessibleItems: function ()
	{
		$("." + this.classAccessibleOfferValue).removeClass(this.classAccessibleOfferValue);
	},

	/**
	 * Очищает все несовместимые элементы (имеющие соответствующий класс)
	 */
	clearInaccessibleItems: function ()
	{
		$("." + this.classInaccessibleOfferValue).removeClass(this.classInaccessibleOfferValue);
	},

	/**
	 * Устанавливает для значений фильтра совместимые значения для выбранных значений
	 */
	setAccessibleFilterItems: function ()
	{
		self = this;
		this.clearAccessibleItems();
		this.clearInaccessibleItems();

		let curProp = "";
		let curValue = "";

		this.filterProps.forEach(function (prop)
		{
			if (curProp)
			{
				self.setCurrentFilterValue(curProp, curValue);
			}

			curProp = prop;
			curValue = self.getCurrentFilterValue(prop);

			self.setCurrentFilterValue(curProp, "");
			let accessibleItems = self.getAccessibleFilterItems();

			self.filterProps.forEach(function (accessibleProp)
			{
				if (accessibleItems[accessibleProp])
				{
					accessibleItems[accessibleProp].forEach(function (accessibleValue)
					{
						let elem = $("." + self.classOfferValueItem + "[data-column='" + accessibleProp + "'][data-value='" + accessibleValue + "']");

						if (elem.length > 0)
						{
							elem.addClass(self.classAccessibleOfferValue);
						}
					});
				}
			});
		});

		self.setInaccessibleItems();

		self.setCurrentFilterValue(curProp, curValue);
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
			let value = self.getCurrentFilterValue(prop);

			self.filterProps.forEach(function (propCode)
			{
				if (self.result["OFFERS_MAP_FILTER"]
					&& self.result["OFFERS_MAP_FILTER"][prop]
					&& self.result["OFFERS_MAP_FILTER"][prop][value])
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
		this.filterProps.forEach(function (prop)
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
				accessibleItems[prop] = self.diff(accessibleItems[prop], ['']);
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

	diff: function(array1, array2)
	{
		return array1.filter(x => !array2.includes(x));
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

		Object.keys(this.currentFilterValues).forEach(function (prop)
		{
			if (self.getCurrentFilterValue(prop) == "")
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
			if (self.result["OFFERS_MAP_FILTER"][prop]
				&& self.result["OFFERS_MAP_FILTER"][prop][self.getCurrentFilterValue(prop)]
				&& self.result["OFFERS_MAP_FILTER"][prop][self.getCurrentFilterValue(prop)].length == 1)
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
			if (!$(this).hasClass(self.classInactive) && !$(this).hasClass(self.classActiveOfferValueItem))
			{
				if ($(this).hasClass(self.classInaccessibleOfferValue))
				{
					$("." + self.classActiveOfferValueItem).removeClass(self.classActiveOfferValueItem);
					self.clearCurrentFilterValues();
					self.setCurrentFilterValue($(this).data("column"), $(this).data("value"));
					self.setCurrentFilterByFirstValues();

					self.filterProps.forEach(function(prop)
					{
						let value = self.getCurrentFilterValue(prop);
						let elem = $("." + self.classOfferValueItem + "[data-column='" + prop +"'][data-value='" + value + "']");
						$(elem).addClass(self.classActiveOfferValueItem);
					});
				}
				else
				{
					let notCurrentItemsProps = $("." + self.classOfferValueItem + "[data-column='" + $(this).data("column") + "']").not($(this));
					notCurrentItemsProps.removeClass(self.classActiveOfferValueItem);
					$(this).addClass(self.classActiveOfferValueItem);
					self.setCurrentFilterValue($(this).data("column"), $(this).data("value"));
				}

				self.setAccessibleFilterItems();

				if (self.isAllValuesSelected())
				{
					self.setCurrentValueTitles();
					self.setCurrentOffer();
				}
			}
		});
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
	},

	setInaccessibleItems: function ()
	{
		let notAccessibles = $("." + this.classOfferValueItem).not("." + this.classAccessibleOfferValue);
		notAccessibles.not("." + this.classInactive).addClass(this.classInaccessibleOfferValue);
	},

	/**
	 * Заполняет фильтр первыми совпадающими значениями
	 */
	setCurrentFilterByFirstValues: function ()
	{
		self = this;

		while (true)
		{
			let isAllSelected = true;
			let accessibleItems = self.getAccessibleFilterItems();

			this.filterProps.forEach(function(prop)
			{
				self.setCurrentFilterValue(prop, accessibleItems[prop][0]);

				if (accessibleItems[prop].length > 1)
				{
					isAllSelected = false;
					return;
				}
			});

			if (isAllSelected)
			{
				break;
			}
		}
	}
};