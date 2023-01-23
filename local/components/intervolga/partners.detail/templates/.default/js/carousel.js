window.CarouselComponent = {

	/**
	 *
	 * @param {Object} params
	 */
	init: function (params)
	{
		this.duration = parseInt(params.duration) ?? 1000;
		this.intervalDuration = parseInt(params.intervalDuration) ?? 2000;
		this.autoScroll = !!params.autoScroll ?? true;

		this.selectorRightBtn = params.selectorRightBtn ?? ".right-btn";
		this.selectorLeftBtn = params.selectorLeftBtn ?? ".left-btn";
		this.selectorCarousel = params.selectorCarousel ?? ".carousel";

		this.rightBtnNode = document.querySelector(this.selectorRightBtn);
		this.leftBtnNode = document.querySelector(this.selectorLeftBtn);
		this.carouselNode = document.querySelector(this.selectorCarousel);

		this.bindEvents();
	},

	bindEvents: function()
	{
		self = this;
		//Обработка клика на стрелку вправо
		this.bindRightBtnClick();
		//Обработка клика на стрелку влево
		this.bindLeftBtnClick();

		if (this.autoScroll)
		{
			setInterval(function(){
				let rightBtn = self.rightBtnNode;

				if (rightBtn)
				{
					rightBtn.click();
				}
			}, this.intervalDuration);
		}

	},

	bindRightBtnClick: function()
	{
		self = this;
		let carousel = this.carouselNode;

		if (this.rightBtnNode && carousel)
		{
			BX.bind(this.rightBtnNode, "click", function(e){
				e.preventDefault();
				self.rightCarousel();
				return false;
			});
		}
	},

	bindLeftBtnClick: function()
	{
		self = this;
		let carousel = this.carouselNode;

		if (this.leftBtnNode && carousel)
		{
			BX.bind(this.leftBtnNode, "click", function(e){
				e.preventDefault();
				self.leftCarousel();
				return false;
			});
		}
	},

	leftCarousel: function() {

		let carousel = this.carouselNode;
		let block_width = $(carousel).find('.carousel-block').outerWidth();
		let duration = this.duration;

		$(carousel).find(".carousel-items").animate({left: "+" + block_width + "px"}, duration, 'linear', function () {
			$(carousel).find(".carousel-items .carousel-block").eq(-1).clone().prependTo($(carousel).find(".carousel-items"));
			$(carousel).find(".carousel-items .carousel-block").eq(-1).remove();
			$(carousel).find(".carousel-items").css({"left": "0px"});
		});
	},

	rightCarousel: function() {

		let carousel = this.carouselNode;
		let block_width = $(carousel).find('.carousel-block').outerWidth();
		let duration = this.duration;

		$(carousel).find(".carousel-items").animate({left: "-" + block_width + "px"}, duration, 'linear', function () {
			$(carousel).find(".carousel-items .carousel-block").eq(0).clone().appendTo($(carousel).find(".carousel-items"));
			$(carousel).find(".carousel-items .carousel-block").eq(0).remove();
			$(carousel).find(".carousel-items").css({"left": "0px"});
		});
	}

};