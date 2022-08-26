let Visible = function (target) {
	// Все позиции элемента
	let targetPosition = {
			top: window.pageYOffset + target.getBoundingClientRect().top,
			left: window.pageXOffset + target.getBoundingClientRect().left,
			right: window.pageXOffset + target.getBoundingClientRect().right,
			bottom: window.pageYOffset + target.getBoundingClientRect().bottom
		},
		// Получаем позиции окна
		windowPosition = {
			top: window.pageYOffset,
			left: window.pageXOffset,
			right: window.pageXOffset + document.documentElement.clientWidth,
			bottom: window.pageYOffset + document.documentElement.clientHeight
		};

	if (targetPosition.bottom > windowPosition.top && // Если позиция нижней части элемента больше позиции верхней чайти окна, то элемент виден сверху
		targetPosition.top < windowPosition.bottom && // Если позиция верхней части элемента меньше позиции нижней чайти окна, то элемент виден снизу
		targetPosition.right > windowPosition.left && // Если позиция правой стороны элемента больше позиции левой части окна, то элемент виден слева
		targetPosition.left < windowPosition.right) { // Если позиция левой стороны элемента меньше позиции правой чайти окна, то элемент виден справа
		// Если элемент полностью видно, то запускаем следующий код
		return true;
	} else {
		// Если элемент не видно, то запускаем этот код
		return false;
	}
};

BX.ready(function(){

	let topManagersBlockNode = document.querySelector("#basket-root .contact-table");
	let stickyManagersBlockNode = document.querySelector(".fixed-top-managers-block");

	let maxHeight = 140;
	let stickyBlockTopMargin = $(stickyManagersBlockNode).find(".contact-table").css("margin-top");
	stickyBlockTopMargin = parseInt(stickyBlockTopMargin);
	let headerHeight = $("#headerfixed").height();

	$(stickyManagersBlockNode).css("top", headerHeight - stickyBlockTopMargin);

	window.addEventListener('scroll', function() {

		if (!Visible(topManagersBlockNode))
		{
			$(stickyManagersBlockNode).css("display", "block");
			if ($(stickyManagersBlockNode).height() === 0)
			{
				$(stickyManagersBlockNode).animate({height: maxHeight}, 300);
			}
		}
		else
		{
			$(stickyManagersBlockNode).css("display", "none");
			$(stickyManagersBlockNode).css("height", '0');
		}
	});
});