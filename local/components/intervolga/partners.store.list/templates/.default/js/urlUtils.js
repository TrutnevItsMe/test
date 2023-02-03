class URLUtils{

	 /**
	 * изменить или добавить в url get параметры
	 * без перезагрузки
	 * @param prmName
	 * @param val
	 */
	 static setAttr(prmName, val) {
		let url = new URL(window.location);  // == window.location.href
		url.searchParams.set(prmName, val);
		URLUtils.setUrl(url);
	}

	/**
	 *
	 * @param param
	 * @returns boolean
	 */
	static hasAttr(param)
	{
		if (URLUtils.getAttr(param) !== false)
		{
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param key
	 * @returns {*|boolean}
	 */
	static getAttr(key) {

		let getAttrs = URLUtils.getAttrs();

		if (getAttrs && getAttrs[key])
		{
			return getAttrs[key];
		}
		else
		{
			return false;
		}
	}

	static delAttr(key){
		let url = new URL(window.location);
		url.searchParams.delete(key);
		URLUtils.setUrl(url);
	}

	static setUrl(url)
	{
		history.pushState(null, null, url);
	}

	static getAttrs()
	{
		if (!window.location.href.includes("?"))
		{
			return false;
		}

		let params = window.location.href.split("?")[1];
		let getParams = {};

		params.split("&").forEach(function(getParam)
		{
			if (!getParam)
			{
				return; // continue
			}

			let key = getParam.split("=")[0];
			let value = getParam.split("=")[1];

			getParams[key] = value;
		});

		return getParams;
	}
}