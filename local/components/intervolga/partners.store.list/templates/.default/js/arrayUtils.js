class ArrayUtils{

	static intersection(...arrays){

		let array = arrays[0];

		for (let i = 1; i < arrays.length; ++i){
			array = array.filter(value => arrays[i].includes(value));
		}

		return array;
	}

	/**
	 *
	 * @param {Array} array -- array of arrays
	 */
	static intersectionArray(array)
	{
		let _array = array[0];

		for (let i = 1; i < array.length; ++i){
			_array = _array.filter(value => array[i].includes(value));
		}

		return _array;
	}

	static difference(array1, array2){
		return array1.filter(x => !array2.includes(x));
	}
}