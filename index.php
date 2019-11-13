<?php

require_once('configuration.php');
	
$remote_addr = $_SERVER['REMOTE_ADDR'];

if (!checkAvailableIps($remote_addr)) {
	echo 'Access denied for '.$remote_addr;
	die;
}

?>

<head>
<meta charset="UTF-8">
<title>ДЕПС Кава</title>
<script src='js/data.php'></script>
<script> var remote_addr='<?php echo $remote_addr; ?>'; var keyboard_list = [0,1,2,3,4,5,6,7,8,9,10];
var base_link = '<?=BASE_LINK?>';
function pull_in(_array,_data) {
	if (_data.length) {
		_array.shift();
		_array.push(_data);
	}
}

function detect_enter_push(kl) {
	if (kl[kl.length-1] == 'Enter') {
		//console.log('enter detected!');
		var _keys = kl;
		//console.log(_keys);
		var _keycode = _keys.join('');
		//console.log(_keycode.slice(0,10));
		var _s_code = _keycode.slice(0,10);
		if (surnames_codes[_s_code] != undefined) {
			getName(surnames_codes[_s_code]);
		}
	}
}
</script>
</head>
<body onkeypress="pull_in(keyboard_list,event.key); detect_enter_push(keyboard_list);">
<div class="darkness">&nbsp;</div>
<div class="kava-loader" style="display:none;" ><img src='elements-images/bg/img_loader.gif'></div>
<div class="kava-msg kava-success" style="display:none;">
	<div class="kava-msg-block">
		<div style="font-size: 100px;"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
		<div><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;&nbsp;<span class="kava-msg-desc">Замовлення успішно відправлено</span></div>
		<div class="btn btn-success cancel-order-timer end-order">Дякую!</div>
		<div class="timer" >&nbsp;</div>
	</div>
</div>
<div class="kava-msg kava-error" style="display:none;">
	<div class="kava-msg-block">
		<div style="font-size: 100px; color: red;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
		<div style="color: red;">Помилка на сервері<br>Вибачте за технічні незручності!</div>
		<div class="btn btn-warning cancel-order end-order">Закрити</div>
	</div>
</div>
<div class="kava-msg kava-confirm-order" style="display:none;">
	<div class="kava-msg-block">
		<div style="font-size: 28px;color: #18b156;"><i class="fa fa-shopping-basket" aria-hidden="true"></i><div style="display: inline-block ;margin-left: 20px;">Ваше замовлення</div></div>
		<hr class="hr-order" />
		<div class="tmp-order-list"><div class="div-inline-block tmp-order-list-inner"></div></div>
		<hr class="hr-order" />
		<div class="tmp-order-list"><div class="div-inline-block tmp-order-sum"></div></div>
		<div class="btn btn-success make-order end-order" style="margin-right: 100px;" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Підтвердити</div>
		<div class="btn btn-warning hide-order end-order"><i class="fa fa-backward" aria-hidden="true"></i> Назад</div>
	</div>
</div>
<div class="kava-msg books-confirm-order" style="display:none;">
	<div class="kava-msg-block">
		<div style="font-size: 28px;color: #18b156;"><i class="fa fa-book" aria-hidden="true"></i><div style="display: inline-block ;margin-left: 20px;">Ваше замовлення</div></div>
		<hr class="hr-order" />
		<div class="tmp-books-order-list"><div class="div-inline-block tmp-books-order-list-inner"></div></div>
		<hr class="hr-order" />
		<!--<div class="tmp-order-list"><div class="div-inline-block tmp-order-sum"></div></div>-->
		<div class="btn btn-success make-books-order end-order" style="margin-right: 100px;" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Підтвердити</div>
		<div class="btn btn-warning hide-order end-order"><i class="fa fa-backward" aria-hidden="true"></i> Назад</div>
	</div>
</div>
<div class="kava-msg books-return-order" style="display:none;">
	<div class="kava-msg-block">
		<div style="font-size: 28px;color: #18b156;"><i class="fa fa-book" aria-hidden="true"></i><div style="display: inline-block ;margin-left: 20px;">Повернення книжки</div></div>
		<hr class="hr-order" />
		<div class="tmp-books-order-list"><div class="div-inline-block tmp-books-return-list-inner"></div></div>
		<hr class="hr-order" />
		<div class="btn btn-success make-books-return end-order" style="margin-right: 100px;" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Повернути</div>
		<div class="btn btn-warning hide-order end-order"><i class="fa fa-backward" aria-hidden="true"></i> Відміна</div>
	</div>
</div>
<ul class="top-kava-menu nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#kava-tab">Кав'ярня</a></li>
  <li><a data-toggle="tab" href="#books-tab">Бібліотека</a></li>
  <li><a data-toggle="tab" href="#return-books-tab">Повернути книжку</a></li>
</ul>
<div class="all-containers-pack tab-content">
	<div id="kava-tab" class="tab-pane fade in active">
		<div class="main-container container kava-container" style="padding:20px;">
			<div class="row">
				<div class="form-group" style="/*width: 70%;display: inline-block;*/">
					<label for="surname-data">Прізвище:</label>
					<span class="errors error-surname-kava"></span>
					<input class="form-control input-kava surname-data input-data has-clear" autocomplete="off" data-finder="kava" rows="5" id="surname-data-kava" type="text" placeholder="Введіть прізвище" />
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
					<div class="surnames_list" id="surnames-list-kava" style="display:none;"></div>
				</div>
				<!--<div class="books-button"><div class="btn btn-success show-me-books"><i class="fa fa-book" aria-hidden="true"></i> Книжки</div></div>-->
				<span class="errors error-kava-snack"></span>
				<div class="form-group">
					<label for="kava-data">Напої:</label>
					<div id="kava-data" >
				</div>
				<hr/>
				</div>
				<div class="form-group">
					<label for="snack-data">Снеки:</label>
					<div id="snack-data" >
				</div>
				</div>
				<div class="navbar-fixed-bottom col-sm-12 col-lg-12 main-button-block" style="display:none;">
					<div class="btn all_prices" ><span><span id="all_prices_sum">0.00</span> грн.</span></div>
					<div class="btn btn-success try-make-order" >Замовити</div>
					<div class="btn btn-warning cancel-order" data-finder="kava">Скасувати</div>
				</div>
			</div>
		</div>
	</div>
	<div id="books-tab" class="tab-pane fade">
		<div class="tab-pane main-container container books-container" style="padding:20px;">
			<div class="row">
				<div class="form-group">
					<label for="surname-data-books">Прізвище:</label>
					<span class="errors error-surname-books"></span>
					<input class="form-control input-books surname-data input-data has-clear" autocomplete="off" data-finder="books" rows="5" id="surname-data-books" type="text" placeholder="Введіть прізвище" />
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
					<div class="surnames_list" id="surnames-list-books" style="display:none;"></div>
				</div>
				<div class="form-group">
					<label for="book-date">Дата повернення:</label>
					<span class="errors error-date-books"></span>
					<input class="form-control input-books date-data input-data has-clear" autocomplete="off" type="text" id="book-date" placeholder="Виберіть дату">
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
				</div>
				<span class="errors error-books-list"></span>
				<div class="form-group">
					<label for="books-data">Бібліотека:</label>
					<div id="books-data"></div>
				</div>
				<div class="navbar-fixed-bottom col-sm-12 col-lg-12 books-button-block" style="display:none;">
					<div class="btn btn-book all_prices" ><span><span id="all_books_sum">0</span> шт.</span></div>
					<div class="btn btn-book btn-success try-make-books-order" >Замовити</div>
					<div class="btn btn-book btn-warning cancel-order" data-finder="books">Скасувати</div>
				</div>
			</div>
		</div>
	</div>
	<div id="return-books-tab" class="tab-pane fade">
		<div class="tab-pane main-container container return-books-container" style="padding:20px;">
			<div class="row">
				<div class="form-group">
					<label for="surname-data-return-books">Прізвище:</label>
					<span class="errors error-surname-return-books"></span>
					<input class="form-control surname-data input-data has-clear" autocomplete="off" data-finder="return-books" data-class-clear="client-books-orders" rows="5" id="surname-data-return-books" type="text" placeholder="Введіть прізвище" />
					<span id="return-book-close-glypicon" class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
					<div class="surnames_list" id="surnames-list-return-books" style="display:none;"></div>
				</div>
				<div class="form-group">
					<div id="client-books-orders" class="client-books-orders"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/jquery-2.1.4.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/air-datepicker.js"></script>
<script src="js/kava.js"></script>
<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/kava.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/air-datepicker.css">

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.js"></script>
</body> 
