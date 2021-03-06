﻿function getName(name){
	document.getElementById('surname-data').value = name;
	//document.getElementById('surnames-list').style.display = 'none';
}
jQuery(function(){
	
	var all_price = 0.00;
	
	function time_hide(id){
		jQuery(id).hide();
	}
	
	// вывод товаров указываем источник и тип
	function show_elements(source,_type){
		var _html = '<div class="row" >';
		jQuery.each(source,function(index,value){
			_html += '<div class="select_list select_napoi col-lg-2 col-sm-3 col-xs-6" >';
				_html += '<div class="element-data" data-price="'+value.price.toFixed(2)+'" data-qty="1" data-type="'+_type+'" data-name="'+value.name+'" data-id="'+index+'">';
					_html += '<img src="'+value.img+'" /><br>';
					_html += '<span class="title-element">'+value.name+'</span><br>';
					_html += '<span class="price-element">'+value.price.toFixed(2)+' грн.</span>';
				_html += '</div>';
				_html += '<span class="qty product-qty qty-block">1</span>';
				_html += '<span class="qty button-qty button-minus"><i class="fa fa-minus-square" aria-hidden="true"></i></span>';
				_html += '<span class="qty button-qty button-plus"><i class="fa fa-plus-square" aria-hidden="true"></i></span>';
			_html +='</div>';
		});
		_html += '</div>';
		return _html;
	}
	
	// формируем объект заказа
	function createOrderObj(){
		// формируем общую цену
		checklist();
		// тянем все выбранные продукты
		var objList = jQuery('.selected');
		// IP клиента
		var raddr = remote_addr;
		// тянем фамилию клиента
		var clientName = jQuery('#surname-data').val();
		// если с фамилией все ок (ну или в ней 3 и больше символа :) )
		if (clientName.length > 2){
			// базовый объект
			var objOrder = {'remote_addr':raddr,'price':all_price,'client': clientName,'order':[]};
			// если товары выбраны
			if (objList.length){
				// для каждого объекта
				jQuery.each(objList, function(index, _data_){
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
			// если не один товар не выбран -- шлем ошибку (-1)
			}else{
				return -1; // no products
			}
		// если фамилия не подходит -- шлем ошибку (-2)
		}else{
			return -2; // no clientName
		}
		// все отлично объект готов
		return objOrder;
	}
	
	// чекинг для подсчета суммы заказа
	function checklist(){
		// тянем все выбранные продукты
		var _list = jQuery('.selected');
		//var _order = [];
		// обнуляем сумму для перерасчета
		all_price = 0.00;
		// товары таки выбраны
		if (_list.length){
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
	}
	
	// выводим кофе 
	jQuery('#kava-data').html(show_elements(napoi,'napoi'));
	// выводим снеки
	jQuery('#snack-data').html(show_elements(snacks,'snacks'));
	
	// блюр на строке фамилий
	jQuery('#surname-data').blur(function(){
		setTimeout(function(){
		  time_hide('#surnames-list');
		}, 200);
		//jQuery('#surnames-list').delay(1000).hide();
	});
	
	// увеличиваем /уменьшаем кол-во товара
	jQuery('.button-qty').on('click', function(){
		var elm = jQuery(this);
		var pElm = elm.parent().find('.element-data');
		// уменьшаем
		if (elm.hasClass('button-minus')){
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
		if (elm.hasClass('button-plus')){
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
		checklist();
	});
	
	// поиск фамилий из списка и сборка списка
	jQuery('#surname-data').on('keyup',function(){
		// счетчик вывода максиамльного кол-ва записей
		var counter = 10;
		// поисковое слово
		var find = $(this).val();
		// зачаток хтмл
		var _html = '';
		// в поисковом слове есть хоть символ
		if (find.length > 2){
			// для каждого из списа фамилиий
			jQuery.each(surnames,function(k,v){
				// тольео если счетчик содержит
				if (counter > 0){
					// находим совпадения для поисковго слова
					var f_01 = v.indexOf(find);
					var f_02 = v.toLowerCase().indexOf(find);
					//var trying = (f_01>-1)||(f_02>-1);
					var trying = (f_01==0)||(f_02==0);
					// нашли?
					if (trying){
						// счетчик считает только найденые записи
						counter--;
						// добавляем значение в хмтльку
						_html += '<div class="list-surname" onclick="getName(\''+v+'\')">'+v+'</div>';
					}
				}
			});
		}
		// хтмлька не пуста
		if (_html.length)
			// покажем что там
			jQuery('#surnames-list').html(_html).show();
		// если в поисковом слове меньше 2 символов
		if (find.length < 2)
			// прячем от глаза
			jQuery('#surnames-list').hide();
	});
	
	// отправка заказа
	jQuery('.make-order').on('click', function(){
		// собираем объект для отправки
		var _ord = createOrderObj();
		jQuery('.error-surname').hide();
		jQuery('.error-kava-snack').hide();
		// -2 код ошибки (нет фамилии)
		if (_ord==-2){
			jQuery('.error-surname').html('Вкажіть Ваше прізвище');
			jQuery('#surname-data').focus();
			jQuery('.error-surname').show();
		// -1 код ошибки (не выбран ни кофе ни снек)
		}else  if (_ord==-1){
			jQuery('.error-kava-snack').html('Виберіть хоча б один напій або снек');
			jQuery('.error-kava-snack').show();
		}else{
			// отправка данных
			$.ajax({
			  //url: "check.php?action=send_order",
			  url: "http://kava.deps.ua/index.php?option=com_exussalebanner&controller=exussalebanner&task=kava&view=exussalebanner&Itemid=1906&action=send_order&tmpl=content",
			  //url: "test.php",
			  type: "POST",
			  data: _ord,
			  dataType: "html",
			  // перед началом отправки
			  beforeSend: function(xhr) {
				//xhr.setRequestHeader("Access-Control-Allow-Origin", "*");  
				// затяняем экран
				jQuery('.darkness').show();
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				// делам боди без скроллов
				jQuery('body').css('overflow','hidden');
			  },
			}).success(function(backdata){
				var resp = jQuery(backdata);
				var fixx = backdata.indexOf('kava-data');
				//console.log("good "+result);
				if (fixx > -1){
					// показываем все хорошо
					jQuery('.kava-success').fadeIn();
				}else{
					// показываем все плохо
					jQuery('.kava-error').fadeIn();
				}
			}).fail(function(backdata){
				var resp = jQuery(backdata);
				var fixx = backdata.indexOf('kava-data');
				//console.log("good "+result);
				if (fixx > -1){
					// показываем все хорошо
					jQuery('.kava-success').fadeIn();
				}else{
					// показываем все плохо
					jQuery('.kava-error').fadeIn();
				}
			});
		}
		
	});
	
	// полная отмена заказа
	jQuery('.cancel-order').on('click', function(){
		// прячем ошибки
		jQuery('.error-surname').hide();
		jQuery('.error-kava-snack').hide();
		// фамилию обнуляем
		jQuery('#surname-data').val('');
		// все выбраные товары
		var selected = jQuery('.selected');
		// для каждого из них
		jQuery.each(selected, function(index, ord){
			// нажимаем на него (тем самым прячем)
			jQuery(ord).click();
			// ставим кол-во на 1
			jQuery(ord).data('qty','1');
			// и в верхнем кружочке тоже
			jQuery(ord).parent().find('.product-qty').html('1');
		});
		// прячем анимацию загрузки
		jQuery('.kava-loader').hide();
		// прячем мрак
		jQuery('.darkness').fadeOut();
		// прячем все сообщения
		jQuery('.kava-msg').fadeOut();
		// боди приводим чувства (отдаем автотический скроллинг)
		jQuery('body').css('overflow','auto');
	});
	
	// тыцаем на товар
	jQuery('.element-data').on('click', function(){
		// компактная форма
		var elm = jQuery(this);
		// если был выбран
		if (elm.hasClass('selected')){
			// делаем невыбранным
			elm.removeClass('selected');
			// прячм кнопки товара
			elm.parent().find('.qty').hide();
		// если не был выбран
		}else{
			// делаем выбранным
			elm.addClass('selected');
			// показываем кнопки товара
			elm.parent().find('.qty').show();
		}
		// чекаем сумму заказа
		checklist();
		
	});
	
});
