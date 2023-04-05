<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>

<div class="modal_background">
	<div class="modal_form modal_form_review">
		<a href="#" class="close_form">X</a>
              <div class="cb-title fr_body">
                Оставьте Ваш отзыв
              </div>
              <div class="row fr_body">
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="name" placeholder="Имя" autocomplete="off" value="" class="input-name" required="required" pattern=".{3,}">
                </div>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="email" placeholder="E-mail" autocomplete="off" value="" class="input-email" required="required">
                </div>
                <div class="col-sm-12 col-xs-12">
                  <textarea name="review" placeholder="Отзыв" autocomplete="off" value="" class="input-review" required="required"></textarea>
                </div>
                <div class="col-sm-12 col-xs-12 text-center">
                  <div class="fcallback">Оставить отзыв</div>
                </div>
              </div>
              <div class="ok-message"></div>
			  <script src="/review/inputmask.js"></script>
              <script type="text/javascript">
                $(document).ready(function(){
                  $(".fcallback").on('click', function() {
                    var name = $('.input-name').val();
                    var mail = $('.input-email').val();
                    var review = $('.input-review').val();
                    if(name!=''&&review!=''){
                          $.ajax({
                            type: "GET",
                            url: "/review/review-sender.php",
                            data: 'name='+name+'&mail='+mail+'&review='+review,
                            success: function() {
                                  $('.ok-message').html('Благодарим за Ваш отзыв!');
                                  $('.fr_body').html('');
                                  setTimeout(function() { $('.ok-message').html(''); }, 9999999999)
                              }
                          });
                      } else {
                        $('.ok-message').html('<span class="err">Заполните все поля</span>');
                        setTimeout(function() { $('.ok-message').html(''); }, 9999999999)
                      }
                    });
                })
				$(document).ready(function(){   
					$(".input-email").inputmask("email")
				});
              </script>
	</div>
</div>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");?>