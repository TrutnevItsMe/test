window.OffersFilterComponent = {

	/**
	 *
	 * @param {Object} params
	 */
	init: function (params)
	{
		this.result = params.result;
		this.params = params.params;

		this.classActiveOfferValueItem = params.classActiveOfferValueItem || "active-offers-filter-item";
		this.classOfferValueItem = params.classOfferValueItem || "offers-filter-item";
		this.classOfferValueContainer = params.classOfferValueContainer || "offers-filter-item-container";
		// Класс, указывающий, что данное предложение недоступно (нельзя выбрать значение в фильтре по предложениям)
		this.classInactive = params.classInactive || "inactive";
		// Класс, показывающий, что данное значение вместе с др. значениями фильтра не совместимо
		this.classInaccessible = params.classInaccessible || "inaccessible";
		this.currentFilterValues = {};

		// Флаг, что кликнули на несовместимое значение фильтра
		this.clickedToInaccessibleItem = false;
		// JQuery элемент, на который кликнули
		this.clickedItem = null;

		if (typeof(this.params["FILTER_OFFERS_PROPERTY_CODE"]) == "object")
		{
			// Значения св-в, участвующих в фильтрации
			this.filterProps = Object.values(this.params["FILTER_OFFERS_PROPERTY_CODE"]);
		}

		this.initFilterValues();
		this.setAccessibleFilterItems();
		this.setPrice(this.result);
		this.bindEvents();
	},

	/**
	 * Устанавливает текущее значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @param {string} value
	 */
	setFilterValue: function(prop, value)
	{
		this.currentFilterValues[prop] = value;
	},

	/**
	 * Возвращает текущще значение фильтра для 1 св-ва
	 * @param {string} prop
	 * @return {string}
	 */
	getFilterValue: function(prop)
	{
		return this.currentFilterValues[prop];
	},

	/**
	 * Иницивализирует текущее значение фильтра
	 */
	initFilterValues: function()
	{
		self = this;

		if (!this.currentFilterValues)
		{
			this.currentFilterValues = {};
		}

		Object.keys(this.result["OFFERS_MAP_FILTER"]).forEach(function(prop)
		{
			if (self.result["CURRENT_OFFER"])
			{
				self.setFilterValue(prop, self.result["CURRENT_OFFER"]["PROPERTIES"][prop]["VALUE"]);
			}
			else
			{
				self.setFilterValue(prop, "");
			}
		});
	},

	/**
	 * Очищает все текущие значения фильтра
	 */
	clearFilterValues: function()
	{
		self = this;
		Object.keys(this.result["OFFERS_MAP_FILTER"]).forEach(function (prop)
		{
			self.setFilterValue(prop, "");
		});
	},

	/**
	 * Устанавливает для значений фильтра совместимые значения для выбранных значений
	 */
	setAccessibleFilterItems: function()
	{
		self = this;
		let accessibleItems = this.getAccessibleFilterItems();

		// Не выбрали ни одного значения
		if (Object.keys(accessibleItems).length == 0)
		{
			self.filterProps.forEach(function (prop){
				$("." + self.classOfferValueItem + "[data-column='" + prop + "']").removeClass(self.classInaccessible);
				self.setFilterValue(prop, "");
			});

			return;
		}

		// Если кликнули на невыбранный элемент, совместимый с остальными значениями
		if (self.clickedItem
			&& self.clickedItem.hasClass(self.classActiveOfferValueItem)
			&& !self.clickedItem.hasClass(self.classInaccessible))
		{
			let isAllValuesSingle = true;

			Object.keys(accessibleItems).forEach(function (prop){
				if (prop && accessibleItems[prop].length > 1){
					isAllValuesSingle = false;
					return;
				}
			});

			// Если все совместимые св-ва имеют по 1 значению
			if (isAllValuesSingle)
			{
				Object.keys(accessibleItems).forEach(function (prop){

					$("." + self.classOfferValueItem + "[data-column='" + prop + "']").addClass(self.classInaccessible);
					let currentValueItem = $("." + self.classOfferValueItem + "[data-value='" + accessibleItems[prop][0] + "']");
					currentValueItem.removeClass(self.classInaccessible);
					currentValueItem.addClass(self.classActiveOfferValueItem);
					self.setFilterValue(prop, accessibleItems[prop][0]);
				});
				return;
			}
		}

		// Пробегаемся по св-ам совместимых значений
		Object.keys(accessibleItems).forEach(function (prop){

			// Выбираем все предложения с этим св-ом
			let offers = $("." + self.classOfferValueItem + "[data-column='" + prop + "']");

			if (offers.length > 0)
			{
				offers.each(function (){

					// Данное предложение можно выбрать
					if (!$(this).hasClass(self.classInactive))
					{
						// []
						if (typeof(accessibleItems[prop]) == "object")
						{
							let propValue = $(this).data("value").toString();
							// Текущее св-во совместимо с выбранными
							if (accessibleItems[prop].includes(propValue)
							|| !accessibleItems)
							{
								// Совместимо лишь 1 св-во и мы кликунли на несовместимое значение
								if (accessibleItems[prop].length == 1 && self.clickedToInaccessibleItem)
								{
									$(this).addClass(self.classActiveOfferValueItem);
									self.setFilterValue(prop, propValue);
								}

								$(this).removeClass(self.classInaccessible);
							}
							else
							{
								$(this).addClass(self.classInaccessible);
							}
						}
					}
				});
			}
		});

		self.clickedToInaccessibleItem = false;
	},

	/**
	 * Создает Object доступных полей для фильтрации
	 * { св-во: [список значений] }
	 * @return {Object}
	 */
	getAccessibleFilterItems: function()
	{
		self = this;
		let accessibleItems = {};

		// Пробегаем все св-ва, участвующие в фильтрации
		Object.keys(this.currentFilterValues).forEach(function (prop)
		{
			// Выбранное значение в фильтре для св-ва
			let value = self.currentFilterValues[prop];

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
		Object.keys(this.currentFilterValues).forEach(function (prop)
		{
			if (accessibleItems[prop])
			{
				let _intersect = accessibleItems[prop][0];

				for (let i = 1; i < accessibleItems[prop].length; ++i)
				{
					_intersect = self.intersection(_intersect, accessibleItems[prop][i]);
				}

				accessibleItems[prop] = _intersect;
				accessibleItems[prop] = Array.from( (new Set(accessibleItems[prop])) );
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
	intersection: function(array1, array2)
	{
		return array1.filter(value => array2.includes(value));
	},

	/**
	 *
	 * @param {Object} result
	 */
	setPrice: function(result)
	{
		let prices = {
			price: 0,
			old: 0
		}

		if (result["PRICE_MATRIX"])
		{
			let currentCurrencyIndex = Object.keys(result["PRICE_MATRIX"]["COLS"])[0];
			prices.price = result["PRICE_MATRIX"]["MATRIX"][currentCurrencyIndex][0]["PRICE"];

			if (result["CURRENT_OFFER"])
			{
				$('.button_block .btn.to-cart').attr("data-item", result["CURRENT_OFFER"]["ID"]);
			}
		}

		showPrice(prices);
	},

	bindEvents: function()
	{
		this.bindChangeOfferFilter("." + this.classOfferValueItem);
	},

	/**
	 * Выводит в html верстку текущее выбранное значение для св-ва
	 * @param {string} column
	 * @param {string} value
	 */
	setColumnValueTitle: function(column, value)
	{
		let propContainer = $("." + this.classOfferValueContainer + "[data-column='" + column +"']");

		if (propContainer.length > 0)
		{
			propContainer.prev(".prop-current-value").html(value);
		}
	},

	/**
	 * Выводит все текущие значения выбранных св-в в html верстку
	 */
	setCurrentValueTitles: function()
	{
		self = this;

		Object.keys(this.currentFilterValues).forEach(function (prop){

			self.setColumnValueTitle(prop, self.getFilterValue(prop));
		});
	},

	/**
	 * Биндит клик по элементу фильтра
	 * @param {string | JQuery} selectorItem
	 */
	bindChangeOfferFilter: function(selectorItem)
	{
		self = this;

		$(selectorItem).on("click", function(e){

			e.preventDefault();

			self.clickedItem = $(this);

			// Данное св-во есть хотя бы у 1 предложения
			if (!$(this).hasClass(self.classInactive))
			{
				let containerItems = $(this).parents("." + self.classOfferValueContainer);
				let currentProp = $(this).data("column");
				let currentValue = $(this).data("value");

				// Кликнули на выбранное значение
				if ($(this).hasClass(self.classActiveOfferValueItem))
				{
					$(this).removeClass(self.classActiveOfferValueItem);
					self.setFilterValue(currentProp, "");
				}
				else
				{
					// Кликнули на значение, не совместимое с остальными
					if ($(this).hasClass(self.classInaccessible))
					{
						// Очищаем все текущие значения фильтра
						self.clearFilterValues();
						$("." + self.classOfferValueItem).removeClass(self.classActiveOfferValueItem);
						self.clickedToInaccessibleItem = true; // флаг, что кликнули на несовместимое значение
					}

					self.setFilterValue(currentProp, currentValue);
					$(this).addClass(self.classActiveOfferValueItem);
				}

				containerItems.find("." + self.classOfferValueItem).not(this).removeClass(self.classActiveOfferValueItem);
				self.setAccessibleFilterItems();
				self.setCurrentValueTitles();
			}
		});
	}
};