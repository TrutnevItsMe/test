class YandexMap
{
	static #oMap = null;
	static #MAX_ZOOM = 19;
	static #MIN_ZOOM = 0;

	/**
	 *
	 * @param {string} mapId
	 * @param {object} params
	 */
	static init(mapId, params)
	{
		if (!ymaps)
		{
			throw "YMaps not defined!";
		}

		if (YandexMap.getMap())
		{
			return;
		}

		return ymaps.ready(function(){

			let controlsMap = [];

			if (params["SHOW_MAP_ZOOM"] === "Y")
			{
				controlsMap.push('zoomControl'); // Ползунок масштаба
			}

			if (params["SHOW_MAP_RULER"] === "Y")
			{
				controlsMap.push('rulerControl'); // Линейка
			}

			if (params["SHOW_MAP_FULLSCREEN"] === "Y")
			{
				controlsMap.push('fullscreenControl'); // Полноэкранный режим
			}

			// Создание карты.
			// https://tech.yandex.ru/maps/doc/jsapi/2.1/dg/concepts/map-docpage/
			YandexMap.#oMap = new ymaps.Map(
				mapId,
				{
					// Координаты центра карты.
					// Порядок по умолчнию: «широта, долгота».
					center: [55.76, 37.64],
					// Уровень масштабирования. Допустимые значения:
					// от 0 (весь мир) до 19.
					zoom: 3,
					// Элементы управления
					// https://tech.yandex.ru/maps/doc/jsapi/2.1/dg/concepts/controls/standard-docpage/
					controls: controlsMap
				});
			YandexMap.#oMap.options.set("maxZoom", 8);
			YandexMap.#oMap.options.set("restrictMapArea", [[85,-180], [-85, 179]]);

			if (params["BALLOONS"])
			{
				let isCentered = false;

				params["BALLOONS"].forEach(function(oBalloon)
				{
					YandexMap.setBalloon(
						oBalloon["x"],
						oBalloon["y"],
						oBalloon["hintContent"] ?? "",
						oBalloon["balloonContent"] ?? ""
					);

					if (!isCentered)
					{
						YandexMap.moveTo(
							parseFloat(oBalloon["x"]),
							parseFloat(oBalloon["y"])).
						then(function(){
							YandexMap.zoom(12);
						});

						isCentered = true;
					}
				});
			}

			if (params["USE_DRAG"] === "N")
			{
				YandexMap.#oMap.behaviors.disable('drag');
			}
		});
	}

	/**
	 * Добавление метки
	 * @param {float} x
	 * @param {float} y
	 * @param {string} hintContent -- показывается при наведении мышкой
	 * @param {string} balloonContent -- откроется при клике по метке
	 */
	static setBalloon(x,
					  y,
					  hintContent = "",
					  balloonContent = "")
	{
		if (!ymaps)
		{
			throw "YMaps not defined!";
		}

		if (!YandexMap.getMap())
		{
			return;
		}

		// https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Placemark-docpage/
		let placemark = new ymaps.Placemark(
			[x, y], {
			// Хинт показывается при наведении мышкой на иконку метки.
			hintContent: hintContent,
			// Балун откроется при клике по метке.
			balloonContent: balloonContent
		});

		// После того как метка была создана, добавляем её на карту.
		YandexMap.getMap().geoObjects.add(placemark);
	}

	static getMap()
	{
		return YandexMap.#oMap;
	}

	static getMaxZoom()
	{
		return YandexMap.#MAX_ZOOM;
	}

	static getMinZoom()
	{
		return YandexMap.#MIN_ZOOM;
	}

	/**
	 *
	 * @param {float} x
	 * @param {float} y
	 */
	static moveTo(x, y)
	{
		if (!YandexMap.getMap())
		{
			return;
		}

		return YandexMap.getMap().panTo(
			[x, y]
		);
	}

	/**
	 *
	 * @param {int} k
	 */
	static zoom(k)
	{
		if (!YandexMap.getMap())
		{
			return;
		}

		YandexMap.getMap().setZoom(k);
	}

	static removeBalloons()
	{
		if (!YandexMap.getMap())
		{
			return;
		}

		YandexMap.getMap().geoObjects.removeAll();
	}

}