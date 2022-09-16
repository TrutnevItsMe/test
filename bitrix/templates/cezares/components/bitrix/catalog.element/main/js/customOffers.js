window.OffersFilterComponent = {

	init: function (params)
	{
		this.result = params.result;
		this.params = params.params;

		this.classActiveOfferItem = params.classActiveOfferItem || "active-offers-filter-item";
		this.classOfferItem = params.classOfferItem || "offers-filter-item";
		// Класс, указывающий, что данное предложение недоступно (нельзя выбрать значение в фильтре по предложениям)
		this.classInactive = params.classInactive || "inactive";
		// Класс, показывающий, что данное значение вместе с др. значениями фильтра не совместимо
		this.classInaccessible = params.classInaccessible || "inaccessible";
		this.currentFilterValues = {};

		if (typeof(this.params["FILTER_OFFERS_PROPERTY_CODE"]) == "object")
		{
			// Значения св-в, учасвтующих в фильтрации
			this.filterProps = Object.values(this.params["FILTER_OFFERS_PROPERTY_CODE"]);
		}

		this.initFilterValues();
		this.setAccessibleFilterItems();
		this.setPrice(this.result);
		this.bindEvents();
	},

	/**
	 *
	 * @param {string} prop
	 * @param {string} value
	 */
	setFilterValue: function(prop, value)
	{
		this.currentFilterValues[prop] = value;
	},

	/**
	 *
	 * @param {string} prop
	 * @return {string}
	 */
	getFilterValue: function(prop)
	{
		return this.currentFilterValues[prop];
	},

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
				self.currentFilterValues[prop] = self.result["CURRENT_OFFER"]["PROPERTIES"][prop]["VALUE"];
			}
			else
			{
				self.currentFilterValues[prop] = "";
			}
		});
	},

	clearFilterValues: function()
	{
		self = this;
		Object.keys(this.result["OFFERS_MAP_FILTER"]).forEach(function (prop)
		{
			self.currentFilterValues[prop] = "";
		});
	},

	/**
	 * Устанавливает для значений фильтра совместимые значения для выбранных значений
	 */
	setAccessibleFilterItems: function()
	{
		self = this;
		let accessibleItems = this.getAccessibleFilterItems();

		Object.keys(accessibleItems).forEach(function (prop){

			let offers = $("." + self.classOfferItem + "[data-column='" + prop + "']");

			if (offers.length > 0)
			{
				offers.each(function (){

					if (!$(this).hasClass(self.classInactive))
					{
						// []
						if (typeof(accessibleItems[prop]) == "object")
						{
							if (accessibleItems[prop].includes($(this).data(prop.toLowerCase()).toString())
							|| !accessibleItems)
							{
								// TODO: настроить автовыбор при 1 значении
								// TODO: изменять заголовок выбранного значения при выборе "несвязанного" значения

								// if (accessibleItems[prop].length == 1 && $(this).hasClass(self.classInaccessible))
								// {
								// 	$(this).addClass(self.classActiveOfferItem);
								// 	self.setFilterValue(prop, $(this).data(prop.toLowerCase()).toString());
								// }

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

						ar.push(offer["PROPERTIES"][propCode] ? offer["PROPERTIES"][propCode]["VALUE"] : "");
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
			}
		});

		return accessibleItems;
	},

	/**
	 *
	 * @param {Array} array1
	 * @param {Array} array2
	 * @return {Array} {*}
	 */
	intersection: function(array1, array2)
	{
		return array1.filter(value => array2.includes(value));
	},

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
		this.bindChangeOfferFilter("." + this.classOfferItem);
	},

	bindChangeOfferFilter: function(selectorItem)
	{
		self = this;

		$(selectorItem).on("click", function(e){

			e.preventDefault();

			if (!$(this).hasClass(self.classInactive))
			{
				let containerItems = $(this).parents(".filter-item-container");
				let currentProp = $(this).data("column");
				let currentValue = $(this).data(currentProp.toLowerCase());

				if ($(this).hasClass(self.classActiveOfferItem))
				{
					$(this).removeClass(self.classActiveOfferItem);
					self.setFilterValue(currentProp, "");
					containerItems.prev(".prop-current-value").html("");
				}
				else
				{
					// Кликнули на значение, не совместимое с остальными
					if ($(this).hasClass(self.classInaccessible))
					{
						self.clearFilterValues();
						$("." + self.classOfferItem).removeClass(self.classActiveOfferItem);
					}

					self.setFilterValue(currentProp, currentValue);
					$(this).addClass(self.classActiveOfferItem);
					// Выводим выбранное значение
					containerItems.prev(".prop-current-value").html($(this).html());
				}

				console.log(self.currentFilterValues);

				containerItems.find("." + self.classOfferItem).not(this).removeClass(self.classActiveOfferItem);
				self.setAccessibleFilterItems();
			}

		});
	}
};