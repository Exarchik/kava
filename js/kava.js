
// таймер
timez = 0;
// максимальнон значение таймера
maxtimez = 7800;
// ширина таймера
real_width = 0;
// интервал
interv = 300;
// 0/1 - закончен ли подсчет
timer_end = 1;
// server sending packages link
/* access_key находится в js/data.php */
serverKavaLink = base_link+"/check.php?action=order_send&key="+access_key;
serverBooksLink = base_link+"/check.php?action=books_send&key="+access_key;
serverReturnBooksLink = base_link+"/check.php?action=return_book&key="+access_key;
serverGetReturnBooksLink = base_link+"/check.php?action=get_book_client_order&key="+access_key;

// константы
// для кофе
const KV_PRODUCT_NOT_SELECTED = -1;
const KV_FIO_EMPTY = -2;
const KV_FIO_INAPPROPRIATE= -3;
// для книг
const BO_PRODUCT_NOT_SELECTED = -11;
const BO_FIO_EMPTY = -12;
const BO_FIO_INAPPROPRIATE= -13;
const BO_DATE_ERROR = -14;

jQuery( document ).ready(function() {
	var all_price = 0.00;
	var _returnObj = {};
	
	jQuery('.has-clear').on('keyup', function() {
		$this = jQuery(this);
		var visible = Boolean($this.val());
		$this.siblings('.form-control-clear').toggleClass('hidden', !visible);
	});
	jQuery('.form-control-clear').click(function() {
		$this = jQuery(this);
		$this.siblings('input[type="text"]').val('').trigger('keyup');

		var classClear = $this.data('classClear');
		if (classClear) {
			jQuery('.'+classClear).html('');
		}
	});

	jQuery.fn.getName = function(name, typeFinder) {
		jQuery('#surname-data-'+typeFinder).val(name);
		afterSelect(name, typeFinder);
	};

	jQuery.fn.returnBook = function(returnId, bookName) {
		_returnObj = {id:returnId, bookName: bookName};
		//jQuery('#return-book-id-'+returnId).fadeOut();
		jQuery(".tmp-books-return-list-inner").html("Бажаєте повернути книжку <hr>'"+bookName+"' ?");

		jQuery('.darkness').fadeIn();
		jQuery('.books-return-order').show();
	}

	function successMessage(msg) {
		var msg = msg == undefined ? 'Замовлення успішно відправлено' : msg;
		jQuery('.kava-msg-desc').html(msg);
		jQuery('.kava-success').show();
	}

	function afterSelect(name, typeFinder) {
		// для возврата книг генерим список долгов по имени
		if (typeFinder == 'return-books') {
			// отправка данных
			jQuery.ajax({
				url: serverGetReturnBooksLink,
				type: "POST",
				data: {'name': name},
				dataType: "html",
				// перед началом отправки
				beforeSend: function(xhr) {
					// показываем анимацию загрузки
					jQuery('.kava-loader').show();
				},
			}).success(function(backdata) {
				var response = jQuery.parseJSON(backdata);
				//console.log("good");
				//console.log(response);
				if (response.result) {
					// показываем все хорошо
					jQuery('#client-books-orders').html(bookReturnHtml(response.result));
				} else {
					// показываем все плохо
					jQuery('#client-books-orders').html('<h3>Для цього користувача немає неповернутих книжок</h3>');
				}
				jQuery('.kava-loader').hide();
			}).fail(function(backdata) {
				var response = jQuery.parseJSON(backdata);
				console.log(response);
				jQuery('.kava-loader').hide();
			});
		}
	}

	function bookReturnHtml(booksClientData) {
		var _html = '<h3>Список неповернутих книжок:</h3>';
		for (i = 0; i < booksClientData.length; i++) {
			var _o = booksClientData[i];
			_html += '<div id="return-book-id-'+_o.id+'" class="return-book-order return-book-'+_o.warning+'">';
			_html += '<div class="return-book-list col-sm-7"><b>'+_o.books+'</b></div>';
			_html += '<div class="return-book-list col-sm-4">Дата повернення: <span class="return-book-return-date">'+_o['return-date']+'</span></div>';
			_html += '<div class="col-sm-1"><a class="return-book-return btn btn-default" href="#" onclick="jQuery(this).returnBook('+_o.id+', \''+_o.books+'\')">Повернути</a></div>';
			_html += '</div>';
		}
		return _html;
	}

	function timerAgain() {
		timez = maxtimez;
		timer_end = 0;
		jQuery('.timer').css('width','0%');
	}
	
	function time_hide(id) {
		jQuery(id).hide();
	}
	
	// вывод товаров указываем источник и тип
	function show_elements(source, _type, isBooks) {
		var _html = '<div class="row" >';
		if (isBooks == undefined) {
			jQuery.each(source, function(index, value) {
				_html += '<div class="select_list select_napoi col-lg-3 col-sm-3 col-xs-6" >';
					_html += '<div class="element-data" data-price="'+parseFloat(value.price).toFixed(2)+'" data-qty="1" data-type="'+_type+'" data-name="'+value.name+'" data-id="'+index+'">';
						_html += '<img src="elements-images/'+_type+'/'+value.img+'" /><br>';
						_html += '<span class="title-element">'+value.name+'</span><br>';
						_html += '<span class="price-element">'+parseFloat(value.price).toFixed(2)+' грн</span>';
					_html += '</div>';
					_html += '<span class="qty product-qty qty-block">1</span>';
					_html += '<span class="qty button-qty button-minus"><i class="fa fa-minus-square" aria-hidden="true"></i></span>';
					_html += '<span class="qty button-qty button-plus"><i class="fa fa-plus-square" aria-hidden="true"></i></span>';
				_html +='</div>';
			});
		} else {
			var drawLine = false;
			jQuery.each(source, function(index, value) {
				if (!drawLine && value.is_get == 1) {
					_html += '</div> <hr /><h3>Книжки що наразі недоступні:<h3/> <div class="row" >';
					drawLine = true;
				}
				_html += '<div class="select_list select_books col-lg-3 col-sm-3 col-xs-6" >';
					_html += '<div class="element-data'+(value.is_get == 1 ? ' disabled' : '')+'" data-author="'+value.author+'" data-qty="1" data-type="'+_type+'" data-name="'+value.caption+'" data-id="'+value.id+'">';
						if (Math.abs(value.amount) > 1) {
							_html += '<span class="product-qty qty-block">'+Math.abs(value.amount)+'</span>';
						}
						//_html += '<img src="elements-images/books/'+value.img+'" /><br>';
						_html += '<img src="elements-images/books/'+value.img+'" />';
						//_html += '<span class="title-element">'+value.caption+'</span><br>';
						_html += '<div class="title-element">'+value.caption+'</div>';
						//_html += '<span class="price-element">'+value.author+'</span>';
						_html += '<div class="price-element">'+value.author+'</div>';
					_html += '</div>';
				_html +='</div>';
			});
		}
		_html += '</div>';
		return _html;
	}
	
	// проверка находится ли фамилия в списке
	function findClientName(clientName){
		var tmpValue = false;
		// проходимся по списку
		jQuery.each(surnames, function(k,v){
			// фамилия нашлась
			if (v == clientName) tmpValue = true;
		});
		// нет в списке такой фамилии
		return tmpValue;
	}
	
	// формируем ошибку
	function showOrderErrors(orderErrorCode) {
		jQuery('.error-surname-kava').hide();
		jQuery('.error-kava-snack').hide();
		jQuery('.error-surname-books').hide();
		jQuery('.error-books-list').hide();
		jQuery('.error-date-books').hide();
		// -3 код ошибки (нет фамилии в списке) 
		if (orderErrorCode == KV_FIO_INAPPROPRIATE) {
			jQuery('.error-surname-kava').html('Це прізвище відсутнє у списку.');
			jQuery('#surname-data-kava').focus();
			jQuery('.error-surname-kava').show();
		// -2 код ошибки (нет фамилии)
		} else if (orderErrorCode == KV_FIO_EMPTY) {
			jQuery('.error-surname-kava').html('Вкажіть Ваше прізвище');
			jQuery('#surname-data-kava').focus();
			jQuery('.error-surname-kava').show();
		// -1 код ошибки (не выбран ни кофе ни снек)
		} else if (orderErrorCode == KV_PRODUCT_NOT_SELECTED) {
			jQuery('.error-kava-snack').html('Виберіть хоча б один напій або снек');
			jQuery('.error-kava-snack').show();
		// -13 код ошибки (нет фамилии в списке) 
		} else if (orderErrorCode == BO_FIO_INAPPROPRIATE) {
			jQuery('.error-surname-books').html('Це прізвище відсутнє у списку.');
			jQuery('#surname-data-books').focus();
			jQuery('.error-surname-books').show();
		// -12 код ошибки (нет фамилии)
		} else if (orderErrorCode == BO_FIO_EMPTY) {
			jQuery('.error-surname-books').html('Вкажіть Ваше прізвище');
			jQuery('#surname-data-books').focus();
			jQuery('.error-surname-books').show();
		// -11 код ошибки (не выбрана ни одна книга)
		} else if (orderErrorCode == BO_PRODUCT_NOT_SELECTED) {
			jQuery('.error-books-list').html('Виберіть хоча б одну книжку');
			jQuery('.error-books-list').show();
		// -14 код ошибочной даты
		} else if (orderErrorCode == BO_DATE_ERROR) {
			jQuery('.error-date-books').html('Невірний формат дати або дата не вказана');
			jQuery('#book-date').focus();
			jQuery('.error-date-books').show();
		}
	}

	// формируем объект заказа
	function createOrderObj() {
		// формируем общую цену
		checkFoodList();
		// тянем все выбранные продукты
		var objList = jQuery('.selected');
		// IP клиента
		var raddr = remote_addr;
		// тянем фамилию клиента
		var clientName = jQuery('#surname-data-kava').val();

		// KV_FIO_EMPTY -2 | если с фамилией не все ок (ну или в меньше 3 символов) -- шлем ошибку
		if (clientName.length < 3) {
			return KV_FIO_EMPTY; // no clientName
		}
		// KV_FIO_INAPPROPRIATE -3 | проверка фамилии на наличие
		if (!findClientName(clientName)) {
			return KV_FIO_INAPPROPRIATE; // no such clientName in surnames list
		}
		// KV_PRODUCT_NOT_SELECTED -1 | если не один товар не выбран -- шлем ошибку
		if (!objList.length) {
			return KV_PRODUCT_NOT_SELECTED; // no products
		}

		// базовый объект
		var objOrder = {'remote_addr':raddr,'price':all_price,'client': clientName,'order':[]};
		// для каждого объекта
		jQuery.each(objList, function(index, _data_) {
			// компактный вид текущего товара
			var lE = jQuery(_data_);
			// объект с данными 
			var _tmp_ord = {
				'prod_type':lE.data('type'),
				'prod_price':lE.data('price'),
				'prod_qty':lE.data('qty'),
				'prod_name':lE.data('name'),
				'prod_id':lE.data('id')
			};
			// пихаем в массив заказа базового объекта
			objOrder.order.push(_tmp_ord);
		});
		
		// все отлично объект готов
		return objOrder;
	}

	function createOrderBooksObj() {
		// формируем кол-во книг
		checkBooksList();
		// тянем все выбранные книги
		var objList = jQuery('.selected-books');
		// IP клиента
		var raddr = remote_addr;
		// дата возврата книги
		var bookDate = jQuery('#book-date').val();
		// тянем фамилию клиента
		var clientName = jQuery('#surname-data-books').val();

		// BO_FIO_EMPTY -12 | если с фамилией не все ок (ну или в меньше 3 символов) -- шлем ошибку
		if (clientName.length < 3) {
			return BO_FIO_EMPTY; // no clientName
		}
		// BO_FIO_INAPPROPRIATE -13 | проверка фамилии на наличие
		if (!findClientName(clientName)) {
			return BO_FIO_INAPPROPRIATE; // no such clientName in surnames list
		}
		// BO_PRODUCT_NOT_SELECTED -11 | если ни одна книга не выбрана -- шлем ошибку
		if (!objList.length) {
			return BO_PRODUCT_NOT_SELECTED; // no products
		}
		// BO_DATE_ERROR
		if(!/^\d{4}-\d{2}-\d{2}$/.test(bookDate)) {
			return BO_DATE_ERROR; // not valid date
		}

		// базовый объект
		var objBooks = {'remote_addr':raddr, 'client':clientName, 'return_date':bookDate, 'order':[]};
		// для каждого объекта
		jQuery.each(objList, function(index, _data_) {
			// компактный вид текущего товара
			var lE = jQuery(_data_);
			// объект с данными 
			var _tmp_ord = {
				'prod_type':lE.data('type'),
				'prod_author':lE.data('author'),
				'prod_qty':1,
				'prod_name':lE.data('name'),
				'prod_id':lE.data('id'),
			};
			// пихаем в массив заказа базового объекта
			objBooks.order.push(_tmp_ord);
		});
		
		// все отлично объект готов
		return objBooks;
		
	}
	
	// чекинг для подсчета суммы заказа
	function checkFoodList() {
		// тянем все выбранные продукты
		var _list = jQuery('.selected');
		//var _order = [];
		// обнуляем сумму для перерасчета
		all_price = 0.00;
		// товары таки выбраны
		if (_list.length) {
			// для каждого
			jQuery.each(_list,function(key, value){
				// компактный вид товара
				var lE = jQuery(value);
				// сумножаем цену на кол-во и добавляем в общак
				all_price += parseFloat(lE.data('price')*lE.data('qty'));
			});
		}
		// выдача в поле общей цены
		jQuery('#all_prices_sum').html(all_price.toFixed(2));
        
        // прячем / показываем блок к нопки заказа, если выбран хоть один товар
        var hidePayBlock = _list.length ? "fadeIn" : "fadeOut";
        eval("jQuery('.main-button-block')."+hidePayBlock+"();");
	}

	// чекинг списка выбранных книг
	function checkBooksList() {
		// тянем все выбранные книги
		var _list = jQuery('.selected-books');
		
		// выдача в поле кол-во книг
		jQuery('#all_books_sum').html(_list.length);
        
        // прячем / показываем блок к нопки заказа, если выбран хоть один товар
		var hidePayBlock = _list.length ? "fadeIn" : "fadeOut";
        eval("jQuery('.books-button-block')."+hidePayBlock+"();");
	}
	
	// выводим кофе 
	jQuery('#kava-data').html(show_elements(napoi, 'napoi'));
	// выводим снеки
	jQuery('#snack-data').html(show_elements(snack, 'snack'));
	// выводим книжки
	jQuery('#books-data').html(show_elements(books, 'books', true));
	
	// блюр на строке фамилий
	jQuery('.surname-data').blur(function() {
		var typeFinder = jQuery(this).data('finder');
		setTimeout(function() {
		  time_hide('#surnames-list-'+typeFinder);
		}, 200);
		//jQuery('#surnames-list').delay(1000).hide();
	});
	
	// увеличиваем /уменьшаем кол-во товара
	jQuery('.button-qty').on('click', function() {
		var elm = jQuery(this);
		var pElm = elm.parent().find('.element-data');
		// уменьшаем
		if (elm.hasClass('button-minus')) {
			// тянем кол-во
			var _qty = parseInt(pElm.data('qty'));
			// уменьшаем на 1
			_qty --;
			// исключение для 1
			if (_qty < 1) { 
				_qty = 1;
				// нажимаем на элемент что бы скрыть
				pElm.click();
			}
			// сохраняем кол-во
			pElm.data('qty',_qty);
			// выводим кол-во в кружочек над товаром
			elm.parent().find('.product-qty').html(_qty);
		}
		// увеличиваем
		if (elm.hasClass('button-plus')) {
			// тянем кол-во
			var _qty = parseInt(pElm.data('qty'));
			// увеличиваем на 1
			_qty ++;
			// кол-во больше 9 нам ник чему
			if (_qty > 9) _qty = 9;
			// сохраняем кол-во
			pElm.data('qty',_qty);
			// выводим кол-во в кружочек над товаром
			elm.parent().find('.product-qty').html(_qty);
		}
		// небольшая анимация нажатия кнопочек +/-
		elm.animate({ fontSize: "35px" }, 50 ).animate({ fontSize: "40px" }, 50 );
		// расчет заказа и вывод суммы заказа
		checkFoodList();
	});
	
	// поиск фамилий из списка и сборка списка
	// obj - input obj
	function findSurnamesFor(obj) {
		// счетчик вывода максиамльного кол-ва записей
		var counter = 10;
		// поисковое слово
		var find = obj.val();
		// тип typeFinder - (kava, books)
		var typeFinder = obj.data('finder');
		// зачаток хтмл
		var _html = '';
		if (find.length > 2) {
			// для каждого из списка фамилиий
			jQuery.each(surnames, function(k,v) {
				// тольео если счетчик содержит
				if (counter > 0) {
					// находим совпадения для поисковго слова
					var f_01 = v.indexOf(find);
					var f_02 = v.toLowerCase().indexOf(find);
					var trying = (f_01 == 0) || (f_02 == 0);
					// нашли?
					if (trying) {
						// счетчик считает только найденые записи
						counter--;
						// добавляем значение в хмтльку
						_html += '<div class="list-surname" onclick="$(this).getName(\''+v+'\', \''+typeFinder+'\')">'+v+'</div>';
					}
				}
			});
		}
		// хтмлька не пуста
		if (_html.length)
			// покажем что там
			jQuery('#surnames-list-'+typeFinder).html(_html).show();
		// если в поисковом слове меньше 2 символов
		if (find.length < 2)
			// прячем от глаза
			jQuery('#surnames-list-'+typeFinder).hide();
	}

	// вешаем функции поиска на input класса "surname-data"
	jQuery('.surname-data').on('keyup', function() {
		findSurnamesFor(jQuery(this));
	});

	// отказ при проверке
	jQuery('.hide-order').on('click', function() {
		// прячем анимацию загрузки
		jQuery('.kava-loader').hide();
		// прячем мрак
		jQuery('.darkness').fadeOut();
		// прячем все сообщения
		jQuery('.kava-msg').fadeOut();
		// боди приводим чувства (отдаем автоматический скроллинг)
		jQuery('body').css('overflow','auto');
	});

	// проверка заказа книг
	jQuery('.try-make-books-order').on('click', function() {
		var _ord = createOrderBooksObj();
		// если ошибка, то светим ошибки
		if (typeof _ord == 'number') {
			showOrderErrors(_ord);
			return;
		}

		// обнуляем список проверки заказа
		jQuery('.tmp-books-order-list-inner').html('');
		// проходимя по списку товаров заказа
		jQuery.each(_ord.order, function(index, ord_elm) {
			// добавляем в список проверки заказа
			jQuery('.tmp-books-order-list-inner').append("<div class='tmp-order-element' ><span class='tmp-order-caption'>"+ord_elm.prod_name+"</span> <span class='tmp-order-qty'>x"+ord_elm.prod_qty+"</span></div> ");
		});
		//console.log(_ord);
		//jQuery('.tmp-order-sum').html("<div class='tmp-order-element' ><span class='tmp-order-caption'>Разом:</span> <span class='tmp-order-qty'>"+_ord.price.toFixed(2)+" грн</span></div> ");
		// затяняем экран
		jQuery('.darkness').show();
		// делам боди без скроллов
		jQuery('body').css('overflow','hidden');
		// показываем блок подтверждения
		jQuery('.books-confirm-order').fadeIn();
	});
	
	// проверка заказа
	jQuery('.try-make-order').on('click', function() {
		// собираем объект для отправки
		var _ord = createOrderObj();
		// если ошибка, то светим ошибки
		if (typeof _ord == 'number') {
			showOrderErrors(_ord);
			return;
		}

		// обнуляем список проверки заказа
		jQuery('.tmp-order-list-inner').html('');
		// проходимя по списку товаров заказа
		jQuery.each(_ord.order, function(index, ord_elm) {
			// добавляем в список проверки заказа
			jQuery('.tmp-order-list-inner').append("<div class='tmp-order-element' ><span class='tmp-order-caption'>"+ord_elm.prod_name+"</span> <span class='tmp-order-qty'>x"+ord_elm.prod_qty+"</span></div> ");
		});
		//console.log(_ord);
		jQuery('.tmp-order-sum').html("<div class='tmp-order-element' ><span class='tmp-order-caption'>Разом:</span> <span class='tmp-order-qty'>"+_ord.price.toFixed(2)+" грн</span></div> ");
		// затяняем экран
		jQuery('.darkness').show();
		// делам боди без скроллов
		jQuery('body').css('overflow','hidden');
		// показываем блок подтверждения
		jQuery('.kava-confirm-order').fadeIn();
	});
	
	// отправка заказа
	jQuery('.make-order').on('click', function() {
		// собираем объект для отправки
		var _ord = createOrderObj();
		// если ошибка, то светим ошибки
		if (typeof _ord == 'number') {
			showOrderErrors(_ord);
			return;
		}

		// отправка данных
		jQuery.ajax({
			url: serverKavaLink,
			type: "POST",
			data: _ord,
			dataType: "html",
			// перед началом отправки
			beforeSend: function(xhr) {
				// прячем блок проверку
				jQuery('.kava-confirm-order').hide();
				// затяняем экран
				jQuery('.darkness').show();
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				// делам боди без скроллов
				jQuery('body').css('overflow','hidden');
			},
		}).success(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			//console.log("good");
			//console.log(response);
			if (response.result) {
				// показываем все хорошо
				successMessage();
				//jQuery('.kava-success').fadeIn();
				// задаем параметры для таймера
				timerAgain();
			} else {
				// показываем все плохо
				jQuery('.kava-error').fadeIn();
			}
		}).fail(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			console.log(response);
			jQuery('.kava-error').fadeIn();
		});
		
	});

	// отправка заказа
	jQuery('.make-books-order').on('click', function() {
		// собираем объект для отправки
		var _ord = createOrderBooksObj();
		// если ошибка, то светим ошибки
		if (typeof _ord == 'number') {
			showOrderErrors(_ord);
			return;
		}

		// отправка данных
		jQuery.ajax({
			url: serverBooksLink,
			type: "POST",
			data: _ord,
			dataType: "html",
			// перед началом отправки
			beforeSend: function(xhr) {
				// прячем блок проверку
				jQuery('.books-confirm-order').hide();
				// затяняем экран
				jQuery('.darkness').show();
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				// делам боди без скроллов
				jQuery('body').css('overflow','hidden');
			},
		}).success(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			//console.log("good");
			//console.log(response);
			if (response.result) {
				// показываем все хорошо
				successMessage();
				//jQuery('.kava-success').fadeIn();
				// задаем параметры для таймера
				timerAgain();

				if (response.new_books_list) {
					// выводим книжки
					jQuery('#books-data').html(show_elements(response.new_books_list, 'books', true));
				}
			} else {
				// показываем все плохо
				jQuery('.kava-error').fadeIn();
			}
		}).fail(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			console.log(response);
			jQuery('.kava-error').fadeIn();
		});
	});

	// возвращаем книгу
	jQuery('.make-books-return').on('click', function() {
		// объект возврата
		var _ord = _returnObj;
		// если ошибка, то светим ошибки
		if (_ord.length < 1) {
			return;
		}

		// отправка данных
		jQuery.ajax({
			url: serverReturnBooksLink,
			type: "POST",
			data: _ord,
			dataType: "html",
			// перед началом отправки
			beforeSend: function(xhr) {
				// прячем блок проверку
				jQuery('.books-return-order').hide();
				// затяняем экран
				jQuery('.darkness').show();
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				// делам боди без скроллов
				jQuery('body').css('overflow','hidden');
			},
		}).success(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			//console.log("good");
			//console.log(response);
			if (response.result) {
				jQuery('#return-book-id-'+_ord.id).fadeOut();
				// показываем все хорошо
				successMessage('Книжку успішно повернуто!');
				//jQuery('.kava-success').fadeIn();
				// задаем параметры для таймера
				timerAgain();
				if (response.new_books_list) {
					// выводим книжки
					jQuery('#books-data').html(show_elements(response.new_books_list, 'books', true));
				}
			} else {
				// показываем все плохо
				jQuery('.kava-error').fadeIn();
			}
		}).fail(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			console.log(response);
			jQuery('.kava-error').fadeIn();
		});
	});
	
	// полная отмена заказа
	jQuery('.cancel-order').on('click', function() {
		// тип заказа
		var typeFinder = jQuery(this).data('finder');
		// прячем ошибки
		jQuery('.error-surname-'+typeFinder).hide();
		jQuery('.error-kava-snack').hide();
		jQuery('.error-books-list').hide();
		jQuery('.error-date-books').hide();

		// обнуляем поля
		jQuery('.input-'+typeFinder).val('').trigger('keyup');
		//jQuery('.surname-data').val('');
		//jQuery('.date-data').val('');

		// все выбраные товары
		var selected = jQuery('.selected');
		// для каждого из них
		jQuery.each(selected, function(index, ord) {
			// нажимаем на него (тем самым прячем)
			jQuery(ord).click();
			// ставим кол-во на 1
			jQuery(ord).data('qty','1');
			// и в верхнем кружочке тоже
			jQuery(ord).parent().find('.product-qty').html('1');
		});

		// прячем книжки
		var selected = jQuery('.selected-books');
		// для каждого из них
		jQuery.each(selected, function(index, ord) {
			// нажимаем на него (тем самым прячем)
			jQuery(ord).click();
			// ставим кол-во на 1
			jQuery(ord).data('qty','1');
			// и в верхнем кружочке тоже
			jQuery(ord).parent().find('.product-qty').html('1');
		});
		// kstl 01
		jQuery('.books-button-block').fadeOut();

		// прячем анимацию загрузки
		jQuery('.kava-loader').hide();
		// прячем мрак
		jQuery('.darkness').fadeOut();
		// прячем все сообщения
		jQuery('.kava-msg').fadeOut();
		// боди приводим чувства (отдаем автотический скроллинг)
		jQuery('body').css('overflow','auto');
		// скроллинг вверх
		jQuery('html, body').animate({scrollTop:0}, 500);
	});

	jQuery('.cancel-order-timer').on('click', function(){
		timez = 0;
	});
	
	// тыцаем на товар
	//jQuery('.element-data').on('click', function() {
	jQuery('body').on('click', '.element-data', function() {	
		if (jQuery(this).hasClass('disabled')) {
			return;
		}
		// компактная форма
		var elm = jQuery(this);
		var selectClass = 'selected';
		if (elm.data('type') == 'books') {
			selectClass = 'selected-books';
		}

		// если был выбран
		if (elm.hasClass(selectClass)) {
			// делаем невыбранным
			elm.removeClass(selectClass);
			// прячм кнопки товара
			elm.parent().find('.qty').hide();
		// если не был выбран
		} else {
			// делаем выбранным
			elm.addClass(selectClass);
			// показываем кнопки товара
			elm.parent().find('.qty').show();
		}

		// если тип - книга, то работамем с другой формой заказа специально для книг
		if (elm.data('type') == 'books') {
			checkBooksList();
		// чекаем сумму заказа если выбранна книга	
		} else {
			checkFoodList();
		}
		
	});

	jQuery('a[data-toggle=tab]').on('click', function() {
		var bgTabs = {'kava-tab': 'url(/kava/elements-images/bg/kava-bg.jpg)', 'default':'url(/kava/elements-images/bg/books-bg.jpg)'}
		var hashData = this.hash.split('#');

		var bBg = bgTabs.default;
		if (bgTabs[hashData[1]] != undefined) {
			bBg = bgTabs[hashData[1]];
		}

		// для переключение в другой раздел - чистить фамилию из возврата
		if (hashData[1] != 'return-books-tab') {
			jQuery('#return-book-close-glypicon').click();
		}
		jQuery('body').css('background-image', bBg);
	});

	jQuery.fn.datepicker.language['ua'] =  {
		days: ['Неділя','Понеділок','Вівторок','Середа','Четвер','П\'ятница','Субота'],
		daysShort: ['Нед','Пон','Вів','Сер','Чет','Пят','Суб'],
		daysMin: ['Нд','Пн','Вт','Ср','Чт','Пт','Сб'],
		months: ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
		monthsShort: ['Січ','Лют','Бер','Кві','Тра','Чер','Лип','Сер','Вер','Жов','Лис','Гру'],
		today: 'Сьогодні',
		clear: 'Очистити',
		dateFormat: 'dd-mm-yyyy',
		timeFormat: 'hh:ii',
		firstDay: 1
	};
	jQuery('#book-date').datepicker({
		dateFormat : 'yyyy-mm-dd',
		//range: true,
		autoClose: true,
		language: 'ua',
		startDate: new Date(),
		onSelect: function(formattedDate, date, inst) {
			$this = jQuery(this);
			if (inst.$el.hasClass('has-clear')) {
				var visible = Boolean(inst.$el.val());
				inst.$el.siblings('.form-control-clear').toggleClass('hidden', !visible);
			}
		}
	});
	
	// interval timer
	setInterval(
		function() {
			if (timez > 0) {
				timez -= interv;
				//console.log(timez);
				timer_end = 0;
				real_width = 100-Math.round((timez/maxtimez)*100);
				//jQuery('.timer').css('width', real_width+'%');
				jQuery('.timer').animate({width: real_width+'%'}, interv);
			}
			if ((timez <=0) && timer_end == 0) {
				timez = 0;
				timer_end = 1;
				jQuery('.cancel-order').click();
			}
		}, interv);
});
