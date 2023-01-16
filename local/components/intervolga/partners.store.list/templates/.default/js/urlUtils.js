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

	static getAttr(key) {
		let s = window.location.search;
		s = decodeURI(s).match(new RegExp(key + '=([^&=]+)'));
		return s ? s[1] : false;
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
}