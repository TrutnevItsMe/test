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
		this.ajaxFolder = params.ajaxFolder || "/ajax/";

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

		let btnInCart = document.querySelector(".btn.in-cart");
		let btnToCart = document.querySelector(".btn.to-cart");

		if (this.result["CURRENT_OFFER"] && this.offersInBasket.includes(this.result["CURRENT_OFFER"]["ID"]))
		{
			if (btnInCart)
			{
				btnInCart.style.display = "block";
			}

			if (btnToCart)
			{
				btnToCart.style.display = "none";
			}
		}
		else
		{
			if (btnInCart)
			{
				btnInCart.style.display = "none";
			}

			if (btnToCart)
			{
				btnToCart.style.display = "block";
			}
		}

		if (this.result["CURRENT_OFFER"])
		{
			this.setRest(this.result["CURRENT_OFFER"]["ID"], this.params["STORES"]);
			this.setStoresBlock(this.result["CURRENT_OFFER"]["ID"]);
			this.setProductName(this.result["CURRENT_OFFER"]["NAME"]);
			this.setPreviewText(this.result["CURRENT_OFFER"]["PREVIEW_TEXT"]);
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

		this.setPrice(this.result["CURRENT_OFFER"]["PRICES"]["РРЦ"]["VALUE"],
			this.result["CURRENT_OFFER"]["PRICES"]["РРЦ Константа"]["VALUE"]);

		this.bindEvents();
	},

	/**
	 * Устанавливает текущее значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @param {string} value
	 */
	setCurrentFilterValue: function (prop, value)
	{
		this.currentFilterValues[prop] = value ? value : "";
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

	diff: function (array1, array2)
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
		self = this;

		if (!this.templateSets)
		{
			this.templateSets = $("#sets-template").html();
		}

		let template = this.templateSets; // Mustache шаблон
		let currentOffer = this.getCurrentOffer();

		if (currentOffer)
		{
			self.setRest(currentOffer["ID"], self.params["STORES"]);
			self.setStoresBlock(currentOffer["ID"]);

			// У предложения есть набор
			if (currentOffer && currentOffer["SET"] && currentOffer["SET"].length > 0)
			{
				Mustache.parse(template);
				let newHtmlSets = Mustache.render(template, {"ITEMS": currentOffer["SET"]});
				$(".set_new").html(newHtmlSets);
			}

			if (currentOffer["PRICES"])
			{
				self.setPrice(currentOffer["PRICES"]["РРЦ"]["VALUE"],
					currentOffer["PRICES"]["РРЦ Константа"]["VALUE"]);
			}

			// Меняем картинки в слайдере
			self.setSliderPhotos(currentOffer);
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

			self.setProductName(currentOffer["NAME"]);
			self.setPreviewText(currentOffer["PREVIEW_TEXT"]);
			self.setArticle(currentOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]);
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

					self.filterProps.forEach(function (prop)
					{
						let value = self.getCurrentFilterValue(prop);
						let elem = $("." + self.classOfferValueItem + "[data-column='" + prop + "'][data-value='" + value + "']");
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
				self.setCurrentValueTitles();
				self.setCurrentOffer();
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

			this.filterProps.forEach(function (prop)
			{
				if (accessibleItems[prop])
				{
					self.setCurrentFilterValue(prop, accessibleItems[prop][0]);
				}
				else
				{
					self.setCurrentFilterValue(prop, "");
				}

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
	},

	/** Устанавливает назавание товара в верстке */
	setProductName: function (name)
	{
		let nodeName = $("#pagetitle");

		if (nodeName.length)
		{
			nodeName.html(name);
		}
	},

	/** Устанавливает PREVIEW_TEXT в верстке */
	setPreviewText: function (text)
	{
		let node = $(".preview_text");

		if (node.length)
		{
			node.html(text);
		}
	},

	/** Устанавливает артикул в верстке */
	setArticle: function(article)
	{
		let node = document.querySelector(".article");

		if (node)
		{
			let valueNode = node.querySelector(".value");

			if (valueNode)
			{
				valueNode.innerHTML = article;
			}
		}
	},

	/**
	 * Устнавливает картинки в слайдере
	 *
	 * @param {Object} item
	 */
	setSliderPhotos: function(item)
	{
		let htmlThumbs = ""; // картинки в списке
		let htmlSlider = ""; // картинки в слайдере
		let i = 0;

		if (item["MORE_PHOTO"])
		{
			for (let j = 0; j < item["MORE_PHOTO"].length; ++j)
			{
				let _class = !j ? "current" : "";
				let srcImg = (item["MORE_PHOTO"][i]["BIG"]["src"]) ? item["MORE_PHOTO"][i]["BIG"]["src"] : item["MORE_PHOTO"][i]["SMALL"]["src"];
				let slideItemAttribute = (_class) ? "class='current'" : "style='display: none;'";

				htmlSlider += "<li id='photo-"+i+"' "+ slideItemAttribute +">" +
					"<link href='" + srcImg + "'>" +
					"<a href='" + srcImg + "' data-fancybox-group='item_slider' class='popup_link fancy' title='" + item["MORE_PHOTO"][i]["TITLE"] + "'>" +
					"<img data-lazyload='' class=' lazyloaded' data-src='" + srcImg + "' src='" + srcImg + "' alt='"+ item["MORE_PHOTO"][i]["TITLE"] +"' title='"+ item["MORE_PHOTO"][i]["TITLE"] +"'>" +
					"<div class='zoom'></div>" +
					"</a>" +
					"</li>";

				htmlThumbs += "<li class='" + _class + "' " +
					" data-slide_key='" + j + "'" +
					" data-big_img='" + item["MORE_PHOTO"][i]["BIG"]["src"] + "'" +
					" data-small_img='" + item["MORE_PHOTO"][i]["SMALL"]["src"] + "'>" +
					"<span>" +
					"<img class='xzoom-gallery'" +
					" data-xpreview='" + item["MORE_PHOTO"][i]["THUMB"]["src"] + "'" +
					" src='" + item["MORE_PHOTO"][i]["THUMB"]["src"] + "'" +
					" alt='" + item["MORE_PHOTO"][i]["ALT"] + "'" +
					" title='" + item["MORE_PHOTO"][i]["TITLE"] + "'>" +
					"</span>" +
					"</li>";

				++i;
			}
		}

		if (item["PREVIEW_PICTURE"])
		{
			let srcImg = item["PREVIEW_PICTURE"]["SRC"];

			htmlSlider += "<li id='photo-"+i+"' style='display: none;'>" +
				"<link href='" + srcImg + "'>" +
				"<a href='" + srcImg + "' data-fancybox-group='item_slider' class='popup_link fancy' title='" + item["PREVIEW_PICTURE"]["NAME"] + "'>" +
				"<img data-lazyload='' class=' lazyloaded' data-src='" + srcImg + "' src='" + srcImg + "' alt='"+ item["PREVIEW_PICTURE"]["NAME"] +"' title='"+ item["PREVIEW_PICTURE"]["NAME"] +"'>" +
				"<div class='zoom'></div>" +
				"</a>" +
				"</li>";

			htmlThumbs += "<li class='' " +
				" data-slide_key='" + i + "'" +
				" data-big_img='" + item["PREVIEW_PICTURE"]["SRC"] + "'" +
				" data-small_img='" + item["PREVIEW_PICTURE"]["SRC"] + "'>" +
				"<span>" +
				"<img class='xzoom-gallery'" +
				" data-xpreview='" + item["PREVIEW_PICTURE"]["SRC"] + "'" +
				" src='" + item["PREVIEW_PICTURE"]["SRC"] + "'" +
				" alt='" + item["PREVIEW_PICTURE"]["NAME"] + "'" +
				" title='" + item["PREVIEW_PICTURE"]["NAME"] + "'>" +
				"</span>" +
				"</li>";

			++i;
		}

		$(".slides ul").html(htmlSlider);
		$("#thumbs").html(htmlThumbs);
		window.slider.reloadSlider();
	},

	/**
	 * Устанавливает верстку с ценами
	 *
	 * @param {float | undefined | null} price
	 * @param {float | undefined | null} old
	 */
	setPrice: function(price, old)
	{
		let prices = {};

		if (price)
		{
			prices["price"] = parseFloat(price);
		}

		if (old)
		{
			prices["old"] = parseFloat(old);
		}

		showPrice(prices);
	},

	/**
	 * Устанавливает верстку блока со складами
	 *
	 * @param {string | int} offerId
	 */
	setStoresBlock: function(offerId)
	{
		self = this;

		BX.ajax({
			url: self.ajaxFolder+"stores.php",
			method: "POST",
			dataType: "html",
			data: {
				USE_STORE_PHONE: self.params["USE_STORE_PHONE"],
				SCHEDULE: self.params["SCHEDULE"],
				USE_MIN_AMOUNT: self.params["USE_MIN_AMOUNT"],
				MIN_AMOUNT: self.params["MIN_AMOUNT"],
				OFFER_ID: offerId,
				ELEMENT_ID: self.params["ELEMENT_ID"],
				STORE_PATH: self.params["STORE_PATH"],
				MAIN_TITLE: self.params["MAIN_TITLE"],
				MAX_AMOUNT: self.params["MAX_AMOUNT"],
				USE_ONLY_MAX_AMOUNT: self.params["USE_ONLY_MAX_AMOUNT"],
				SHOW_EMPTY_STORE: self.params['SHOW_EMPTY_STORE'],
				SHOW_GENERAL_STORE_INFORMATION: self.params['SHOW_GENERAL_STORE_INFORMATION'],
				USER_FIELDS: self.params['USER_FIELDS'],
				FIELDS: self.params['FIELDS'],
				SET_ITEMS: self.params["SET_ITEMS"],
			},
			onsuccess: function(response)
			{
				let storesBlock = BX("offers-stores-block");

				if (storesBlock)
				{
					storesBlock.innerHTML = response;
				}
			},
			onfailure: function()
			{
			}
		});
	},

	/**
	 * Устанавливает верстку с остатками для предложения
	 *
	 * @param {string | int} offerId
	 * @param {Array} stores -- массив ID складов
	 */
	setRest: function(offerId, stores)
	{
		self = this;

		BX.ajax({
			url: self.ajaxFolder+"rest.php",
			dataType: "html",
			method: "POST",
			data: {
				ELEMENT_ID: offerId,
				STORES: stores
			},
			onsuccess: function(response)
			{
				let restBlock = document.querySelector(".quantity_block_wrapper .p_block");

				if (restBlock)
				{
					restBlock.innerHTML = response;
				}
			},
			onfailure: function()
			{
			}
		});
	}

};