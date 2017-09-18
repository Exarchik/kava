<?php 
header('Content-Type: text/html; charset=utf-8');

$conf_path = "configuration.php";
if (file_exists($conf_path))
{
	require_once($conf_path);
	$_CONF 			= new KavaConfig();

	$servername 	= $_CONF->host;			//"localhost";
	$dbname	   		= $_CONF->db;			//"deps17";
	$username 		= $_CONF->user;			//"u_deps17";
	$password 		= $_CONF->password;		//"vxzIEgUH";
	

	$link = mysql_connect($servername , $username , $password);
	if (!$link) {
		die('Ошибка соединения: ' . mysql_error());
	}

	$db_selected = mysql_select_db($dbname, $link);
	if (!$db_selected) {
		die ('Не удалось выбрать базу '.$mysqlDB.' : ' . mysql_error());
	}

	$sql = "SET NAMES utf8";
	$result = mysql_query($sql);
	
	
	/********************************************/
	/* ключ должен совпадать с ключем в data.js */
	/********************************************/

			$access_key = 'fG78tgio1e';

	/********************************************/
	/* ключ должен совпадать с ключем в data.js */
	/********************************************/


	$_data = $_REQUEST;


	// проверка action
	if (isset($_data['action']))
	{		
		// проверка ключа
		if($_data['key']==$access_key){
			// адресса
			$remote_addr = $_SERVER['REMOTE_ADDR'];
			if ($remote_addr=='77.52.105.188'||$remote_addr=='127.0.0.1'||(strpos('+'.$remote_addr,'91.231.206')))
			{
				// MAIN SELECTION BLOCK
				
				/********************************************/
				/*                order_send                */
				/********************************************/
				// присылаем запрос из кафе
				if ($_data['action']=='order_send')
				{
					// фамилия клиента
					$surname = $_data['client'];
					// заказ клиента
					$order = $_data['order'];
					// общая сумма заказа
					$summary = $_data['price'];
					// IP-адресс с которого сделан заказ
					$remote_addr = $_data['remote_addr'];
					// сериализированный заказ 
					$_order = serialize($order);
					
					// запрос заказа 
					$sql = "INSERT INTO `_kava_data` (`client-ip`, `surname`, `products`, `summary`) VALUES ('".$remote_addr."', '".$surname."', '".$_order."', ".$summary." )";
					
					// отправка заказа
					$result = mysql_query($sql);
					if ($result){
						print "<div class='kava-data'>".$result."</div>";
					}else{
						print "<div class='no-data'>".$result."</div>";
					}
					
				}
				
				/********************************************/
				/*                get_order                 */
				/********************************************/
				// показываем ссылки на отчеты
				elseif ($_data['action']=='get_order')
				{
					?>
	<style>
	span.elm {
		display: inline-block;
		width: 53px;
	}
	.icon-calendar::before {
		content: '[]';
	}
	.input-append {
		display: inline-block;
	}
	.input-append input {
		width: 75px;
	}
	</style>
	<script src="js/jquery-2.1.4.js"></script>
	<script>
		function getlink(baselink){
			var _from = 'fperiod='+jQuery('#begin-date-id').val();
			var _to = 'tperiod='+jQuery('#end-date-id').val();
			var newlink = baselink+'&'+_from+'&'+_to;
			console.log(newlink);
			document.location.href = newlink;
		}
	</script>
					<?php
					$sql = "SELECT COUNT(*) count, MIN(`order_time`) mintime, MAX(`order_time`) maxtime  FROM _kava_data ";
					$result = mysql_query($sql);
					$results = mysql_fetch_object($result);
					
					/*$db = JFactory::getDBO();
					$db->setQuery($sql);
					$results = $db->loadObject();
					*/
					//print_r($results);
					
					
					$base_DL_link = 'check.php?action=xls-data&key='.$access_key.'&nocache='.rand(10000,99999);
					$base_GO_link = 'check.php?action=get_order&key='.$access_key.'&nocache='.rand(10000,99999);
					
					$monthes = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль','08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь');
					$years = 
					
					$current_year = ($_data['year']?$_data['year']:date('Y')); 
					$current_month = ($_data['month']?$_data['month']:date('m'));
					$current_day = ($_data['day']?$_data['day']:date('d'));
					
					$now_year = date('Y');
					$now_month = date('m');
					$now_day = date('d');
					
					$month_minus = date('m',strtotime($current_year.'-'.$current_month.'-01 -1 month'));
						$year_minus = date('Y',strtotime($current_year.'-'.$current_month.'-01 -1 month'));
					$month_plus = date('m',strtotime($current_year.'-'.$current_month.'-01 +1 month'));
						$year_plus = date('Y',strtotime($current_year.'-'.$current_month.'-01 +1 month'));
					
					$daysInMonth = (int)date('t', strtotime($current_year.'-'.$current_month.'-01'));
					
					$unixDate = strtotime($current_year.'-'.$current_month.'-01');
					
					
					// список по датам
					$_time_ = $current_year.'-'.$current_month.'-01 00:00:00';
					$_to_ = date("Y-m-d H:i:s",strtotime($_time_." +1 month"));
					$_where_ = "WHERE (`order_time` > '".$_time_."' AND `order_time` < '".$_to_."' )";
					
					// MAIN ACTIVE XLS LIST
					$a_xls = array();
					$sql = "SELECT COUNT(*) AS count, DATE_FORMAT(`order_time`,'%e') as day FROM `_kava_data` ".$_where_." GROUP BY convert(`order_time`, date)";
					
					//$db->setQuery($sql);
					$result = mysql_query($sql); 
					//$active_xls = $db->loadAssocList();
					while ($active_xls = mysql_fetch_assoc($result)) {
						if ($active_xls){
							if ($active_xls['count']>0){
									$a_xls[$active_xls['day']] = $active_xls['count'];
							}
						}
					}
					
					//print_r($a_xls);
					// надо ограничить текущим месяцем
					
					
					echo "<div style='padding:25px;'>";
					//echo "<br><br>";
					
					echo "<div style='width:377px;' >";
					echo "<div style='text-align:center;'><h2 style='margin-top: 0;'>Отчеты по кафетерию</h2></div>";
					echo "<div> Всего записей: ".$results->count."</div>";
					echo "<div> Первая запись: ".date("Y-m-d",strtotime($results->mintime))."</div>";
					echo "<div> После. запись: ".date("Y-m-d",strtotime($results->maxtime))."</div><br>";
					echo "<div style='text-align:center;'>";
							//echo "".$year_minus."-".$month_minus."  ---  ".$year_plus."-".$month_plus;
							echo "<a href='".$base_GO_link."&year=".$year_minus."&month=".$month_minus."' > <<< </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<span > ".$monthes[$current_month]." - ".$current_year." </span>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$base_GO_link."&year=".$year_plus."&month=".$month_plus."' > >>> </a>";
					echo "</div><br>";
					echo "<div style='text-align:center;' >";
						echo "Выберите отчет за нужный Вам день: ";
					echo "</div><hr />";
					echo "<div><span class='elm'>[ ПН ]</span><span class='elm'>[ ВТ ]</span><span class='elm'>[ СР ]</span><span class='elm'>[ ЧТ ]</span><span class='elm'>[ ПТ ]</span><span class='elm'>[ СБ ]</span><span class='elm'>[ ВС ]</span></div>";
					echo "<hr>";
					echo "<div style='float: right; display: inline-block;'>";
					for ($i=1;$i<=$daysInMonth;$i++){
						$day_num = (int)(date('N',strtotime($current_year.'-'.$current_month.'-'.$i)));
						if ($day_num==1) echo "<div style='display: inline-block;' >";
						$_now_ = '';
						if(($current_year==$now_year)&&($current_month==$now_month)&&((int)$now_day==$i)){
							$_now_ = 'font-weight:bold; background-color: #ddd; border: 1px dotted #333;';
						}
						if ($a_xls[$i] > 0){ 
							echo " <span style='text-align:center; display:inline-block; width: 50px; ".$_now_."'><a href='".$base_DL_link."&year=".$current_year."&month=".$current_month."&day=".$i."'>[ ".$i." ]</a> </span> ";
						}else{
							echo " <span style='text-align:center; display:inline-block; width: 50px; ".$_now_."'><span>[ ".$i." ]</span> </span> ";
						}
						if (($day_num==7)||($i==$daysInMonth)) echo "</div>";
					}
					echo "<hr><div style='text-align:center;'>";
						echo "Отчет за весь месяц: <a href='".$base_DL_link."&year=".$current_year."&month=".$current_month."'> скачать ".$monthes[$current_month]." - ".$current_year." </a>";
					echo "</div>";
					
					echo "<hr>";
					echo "<div >";
						echo "Отчет за период";
						echo "<div> c ";
						echo "<input type='text' value='".$current_year.'-'.$current_month."-01' id='begin-date-id' >";
						//echo JHtml::calendar( $current_year.'-'.$current_month.'-01', 'begin-date', 'begin-date-id', '%Y-%m-%d');
						echo " по ";
						//echo JHtml::calendar( $current_year.'-'.$current_month.'-'.$daysInMonth, 'end-date', 'end-date-id', '%Y-%m-%d')." </div>";
						echo "<input type='text' value='".$current_year.'-'.$current_month.'-'.$daysInMonth."' id='end-date-id' >";
						echo "<br><button class='send-period' onclick='getlink(\"".$base_DL_link."\");'>Скачать отчет за период</button>";
					echo "</div>";
					
					echo "</div>";
					echo "</div>";
				}
				
				/********************************************/
				/*                 xls-data                 */
				/********************************************/
				// файл отчета
				elseif ($_data['action']=='xls-data')
				{
						
					$filename = 'kava.xls';
					$where = "WHERE 1";
					$order = "ORDER BY `order_time` ASC";
					
					if ($_data['fperiod']&&$_data['tperiod']){
						
						$fperiod = $_data['fperiod']." 00:00:00";
						$tperiod = $_data['tperiod']." 23:59:59";
						$filename = 'kava_'.$_data['fperiod'].'__'.$_data['tperiod'].'.xls';
						$where = $where = "WHERE (`order_time` > '".$fperiod."' AND `order_time` < '".$tperiod."' )";
						
					}else{
					
						if ($_data['year']){
							$_time_ = $_data['year'].'-01-01 00:00:00';
							$_to_ = date("Y-m-d H:i:s",strtotime($_time_." +1 year"));
							$filename = 'kava-'.date("Y",strtotime($_time_)).'.xls';
							$where = "WHERE (`order_time` > '".$_time_."' AND `order_time` < '".$_to_."' )";
						}
						if ($_data['year']&&$_data['month']){
							$_time_ = $_data['year'].'-'.$_data['month'].'-01 00:00:00';
							$_to_ = date("Y-m-d H:i:s",strtotime($_time_." +1 month"));
							$filename = 'kava-'.date('Y-m',strtotime($_time_)).'.xls';
							$where = "WHERE (`order_time` > '".$_time_."' AND `order_time` < '".$_to_."' )";
						}
						if ($_data['year']&&$_data['month']&&$_data['day']){
							$_time_ = $_data['year'].'-'.$_data['month'].'-'.$_data['day'].' 00:00:00';
							$_to_ = date("Y-m-d H:i:s",strtotime($_time_." +1 day"));
							$filename = 'kava-'.date('Y-m-d',strtotime($_time_)).'.xls';
							$where = "WHERE (`order_time` > '".$_time_."' AND `order_time` < '".$_to_."' )"; 
						}
					}
					
					
					header('Content-Type: text/html; charset=windows-1251');
					//header('Content-Type: text/html; charset=utf-8');
					header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
					 //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Cache-Control: no-store, no-cache, must-revalidate');
					header('Cache-Control: post-check=0, pre-check=0', FALSE);
					header('Pragma: no-cache');
					header('Content-transfer-encoding: binary');
					header('Content-Disposition: attachment; filename='.$filename); 
					header('Content-Type: application/x-unknown');
					
					$sql = "SELECT * FROM `_kava_data` ".$where." ".$order;
					//JFactory::test($sql);
					
					//$db->setQuery($sql);
					$result = mysql_query($sql); 
					
					//$results = $db->loadAssocList();
					
					$_total_sum = 0.00;
					
					echo "<table class='kava_data' cellspacing=0 cellpadding=0 style='border: 1px #ccc solid; width:100%'>";
						/*
						echo "<tr> <th width=25 >№</th> <th width=125 >Прізвище</th>  <th width=500>Список</th> <th width=50>Сума замовлення</th> <th width=125 >Час</th> </tr>";
						echo "<tr> <th></th> <th></th> <th><table></tr><td> Назва </td><td> Вартість 1 шт. </td><td> К-сть</td></tr></table></th> <th></th> <th></th> </tr>";
						*/
						echo "<tr> <th width=25 >№</th> <th width=125 >Час</th> <th width=125 >Прізвище</th>  <th> Назва </th><th> Вартість 1 шт. </th><th> К-сть</th> <th width=50> Сума замовлення </th>  </tr>";
						$counter = 0;
						//foreach($results as $key=>$row){
						while ($row = mysql_fetch_assoc($result)) {
							$_surname = $row['surname'];
							$_products = unserialize($row['products']);
							if (count($_products)){
								foreach ($_products as $k=>$v){
									
									$sum_price = ($v['prod_price']*$v['prod_qty']);
									$_total_sum += (float)$sum_price;
									
									echo "<tr >";
									echo "<td style='border-bottom:1px #ccc solid;' >".++$counter."</td>";
									echo "<td style='border-bottom:1px #ccc solid;' >".$row['order_time']."</td>";
									echo "<td style='border-bottom:1px #ccc solid;' >".$_surname."</td>";
									//echo "<td style='border-bottom:1px #ccc solid;' >";
									
									/*if (count($_products)){
										echo "<table class='inner_kava' style='width:100%'>";
										foreach ($_products as $k=>$v){
											echo "<tr><td width=250 >".$v['prod_name']." </td><td width=150 > ".$v['prod_price']." грн.</td><td width=50 > ".$v['prod_qty']."</td></tr>";
										}
										echo "</table>";
									}*/
									
									//echo "</td>";
									echo "<td width=250 >".$v['prod_name']." </td>";
									echo "<td width=150 >".number_format($v['prod_price'], 2, ',', '')."</td>";
									echo"<td width=50 > ".$v['prod_qty']."</td>";
									//echo "<td style='border-bottom:1px #ccc solid;' >".$row['summary']." грн.</td>";
									echo "<td style='border-bottom:1px #ccc solid;' >".number_format($sum_price, 2, ',', '')."</td>";
									
									echo "</tr>";
								}
							}
						}
						echo "<tr> <td ></td> <td ></td> <td ></td>  <td ></td><td ></td><td >Разом: </td> <td >".number_format($_total_sum, 2, ',', '')."</td>  </tr>";
					echo "</table>";
						
					
					
				}
				
				else
				{
					// unknown action type
					echo 'unknown action type';
				}
				
				
			}else
			{
				// bad remote address
				echo 'Access denied for '.$remote_addr;
			}
		}
		else
		{
			// bad access_key
			echo 'Bad access key!';
		}
	}
	else
	{
		// no action
		echo 'no action type selected';
	}
}
?>
