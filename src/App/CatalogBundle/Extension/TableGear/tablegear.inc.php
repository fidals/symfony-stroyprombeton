<?php

// Необходимо для вывода этого файла в переменную и отрисовку в фрейме sonata admin
ob_start();

require_once $libPath;

// --> Эти options нужны для конигурации TableGear
$options = array();
$options["database"] = array();
$options["pagination"] = array();

$options["database"]["name"]     = $container->getParameter('database_name');
$options["database"]["username"] = $container->getParameter('database_user');
$options["database"]["password"] = $container->getParameter('database_password');
$options["database"]["table"]    = 'products';
$options["database"]["utf8"] = true;
//$options["database"]["table1"]   = 'modx_site_content';
//$options["database"]["table2"]   = 'modx_site_tmplvar_contentvalues';

$options["pagination"]["perPage"] = 300;  // rows per page.
$options["pagination"]["prev"] = "prev"; // "prev" link will be shown.
$options["pagination"]["next"] = "next"; // "next" link will be shown.

$options["callback"] = array("onUpdate" => "updatePriceDate", "getPrevious" => true);
$options["columns"] = array("price_date" => "highlite sortable date");
$options['formatting'] = array("price_date" => "date[d.m.Y]");

/**
 * Коллбек для апдейта Даты цены.
 * Используется стандартная сигнатура для функций-коллбеков в TableGear
* @param $key - PK изменяемого кортежа. В нашем случае он null, поэтому получаем явно $previous['id']
* @param $previous - изменяемый объект, хрянящий инфу до изменения
* @param $updated - изменяемый объект, хрянящий инфу после изменения
* @param $ref - ссылка на инстанс TableGear. Нам нужна для выполнения MySQL query.
 */
function updatePriceDate($key, $previous, $updated, $ref)
{
    $oldPrice = $previous["price"];
    $newPrice = $updated["price"];

    if ($newPrice && ($oldPrice != $newPrice)) {
        $updateQuery = "UPDATE products SET price_date = NOW() WHERE id = " . $previous['id'] . ";";
        $ref->query($updateQuery);
    }
}

//$options["headers"]["pagetitle"]="Заголовок";
//$options["headers"]["longtitle"]="Расширенный заголовок";
//$options["headers"]["description"]="Описание";
//$options["headers"]["introtext"]="Аннотация";
$options["headers"] = $productRepository->getTableGearProperties();

//$result = $modx->db->query("SELECT * FROM ". $modx->getFullTableName('site_tmplvars')." WHERE `id` > 4" );
//while($row = $modx->db->getRow($result)) {
//	$options["headers"]["tv_".strtolower($row["name"])]=$row["caption"];
//}

// --> Предполагаю, что этот класс юзаем "из коробки" и вообще не трогаем в нём код
$table = new TableGear($options);


$cookie_ff=array();
if( isset($_COOKIE['tg_filterf']) ){
	$cookie_ff=unserialize($_COOKIE['tg_filterf']);
}

// --> Здесь через $_POST и $_COOKIE формируется список полей. Вроде менять ничего не надо
$ff="";
$sort_ff="";
foreach($options["headers"] as $ind => $val){
	$ff.="<div style=\"float: left; clear: both;\"><label style=\"font-weight: 100\" for=\"ff_".$ind."\" id=\"fs_".$ind."\">".$val."</label></div><div style=\"float: right;\"><input type=\"checkbox\"";
	if( in_array($ind, $_POST["ff"]) || (!$_POST["ff"] && in_array($ind, $cookie_ff)) ){
		$ff.=" checked ";
		$sort_ff.='<li class="ui-state-default sortf_'.$ind.'"><input type="hidden" name="sortff[]" value="'.$ind.'">'.$val.'</li>';
	}
	$ff.="name=\"ff[]\" value=\"".$ind."\" id=\"ff_".$ind."\"></div>";
}

if( isset($_REQUEST['sortff']) && count($_REQUEST['sortff'])>0 ){
  $sort_ff="";
  foreach($_REQUEST['sortff'] as $ind=>$val){
	$sort_ff.='<li class="ui-state-default sortf_'.$val.'"><input type="hidden" name="sortff[]" value="'.$val.'">'.$options["headers"][$val].'</li>';
  }
  setcookie( "tg_filterf_s", serialize($_POST["sortff"]), time()+2592000 );
}elseif( isset($_COOKIE['tg_filterf_s']) ){
	$sort_ff="";
	$cookie_fs=unserialize($_COOKIE['tg_filterf_s']);

	foreach($cookie_fs as $ind=>$val){
		$sort_ff.='<li class="ui-state-default sortf_'.$val.'"><input type="hidden" name="sortff[]" value="'.$val.'">'.$options["headers"][$val].'</li>';
	}
}

if( count($_POST["ff"])>0 ){
	setcookie( "tg_filterf", serialize($_POST["ff"]), time()+2592000 );
}
?>

<form method="post" name="formtg" action="">
<input type="hidden" name="parent_tg_id" value="<?php if( isset($_POST['parent_tg_id']) && intval($_POST['parent_tg_id'])>0 ) echo intval($_POST['parent_tg_id']); ?>">
<input type="hidden" name="parent_tg_name" value="">
<table>
	<tr><td colspan="3"><span style="cursor: pointer; color: #EF1D1D; font-weight: bold;" onclick="enableParentSelectionTG();" class="choose_razd">Выберите раздел</span></td></tr>
	<tr><td colspan="3" style="padding-top: 5px;">(<a class="hide_sort" href="javascript:;"><?php if( isset($_COOKIE['hide_sort']) && $_COOKIE['hide_sort']==0){ echo 'показать</a>)<tr>';}else{ echo 'скрыть</a>)<tr>'; } ?></td></tr>
<!--  Здесь показывается список полей-->
	<td valign="top" colspan="2" style="padding-bottom: 10px;<?php if( isset($_COOKIE['hide_sort']) && $_COOKIE['hide_sort']==0){ echo ' display: none;'; } ?>" class="trhide">Фильтр полей:<br/>
		<!--<select name="ff[]" multiple size="7"><?php echo $ff; ?></select>-->
		<div style="overflow-y: scroll; width: 365px; height: 130px; border: 1px solid #cacaca; padding: 2px 5px;"><?php echo $ff; ?></div>
	</td>
	<td valign="top" style="padding-top: 7px; padding-bottom: 10px;<?php if( isset($_COOKIE['hide_sort']) && $_COOKIE['hide_sort']==0){ echo ' display: none;'; } ?>" class="trhide">
		Сортировка полей:
		<ul id="sortable">
			<?php echo $sort_ff; ?>
		</ul>
	</td>
  </tr>
  <tr><td colspan="3" style="font-size: 14px;">Поиск:</td></tr>
  <tr>
  	<td style="width: 10px;">
		<select name="type_search" style="width: 150px;" onchange="if( $(this).val()==2 ){ $('.range_search').show(); $('.text_search').hide(); }else{ $('.range_search').hide(); $('.text_search').show(); }">
			<option value="0">Точное соответствие</option>
			<option value="1" <?php if(isset($_POST['type_search']) && $_POST['type_search']==1 ) echo 'selected'; ?>>Неточное соответствие</option>
			<option value="2" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ) echo 'selected'; ?>>Числовой диапазон</option>
		</select>
	</td>
	<td style="width: 250px; white-space: nowrap;">
<!-- Здесь заканчивается код, нужный для задачи 1-->
	Поиск по:&nbsp;
	<select name="field_search" style="width: 150px;">
<!--		<option value="pagetitle" --><?php //if(isset($_POST['field_search']) && $_POST['field_search']=="pagetitle" ) echo 'selected'; ?><!-->Заголовок</option>-->
<!--		<option value="longtitle" --><?php //if(isset($_POST['field_search']) && $_POST['field_search']=="longtitle" ) echo 'selected'; ?><!-->Расширенный заголовок</option>-->
<!--		<option value="description" --><?php //if(isset($_POST['field_search']) && $_POST['field_search']=="description" ) echo 'selected'; ?><!-->Описание</option>-->
<!--		<option value="introtext" --><?php //if(isset($_POST['field_search']) && $_POST['field_search']=="introtext" ) echo 'selected'; ?><!-->Аннотация</option>-->
<?php
// --> Это просто список свойств. Тот самый, что у нас хардкодом
foreach($productRepository->getTableGearProperties() as $id => $title) {
	echo "<option value=\"tv_".$id."\" ";
	if(isset($_POST['field_search']) && $_POST['field_search']=="tv_".$id ){ echo 'selected'; }
	echo ">&nbsp;&nbsp;&nbsp;".$title."</option>";
}
?>
	</select>
	</td>
	<td>
		<div class="text_search" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ){ echo 'style="display: none;"'; } ?>>
			<input style="width: 400px;" type="text" name="query" value="<?php if(isset($_POST['query'])) echo $_POST['query']; ?>">
		</div>
		<div class="range_search" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ){ echo ''; }else{ echo 'style="display: none;"'; } ?>>
			От:&nbsp;&nbsp;<input style="width: 200px;" type="text" name="from_query" value="<?php if(isset($_POST['from_query'])) echo $_POST['from_query']; ?>">&nbsp;&nbsp;До:&nbsp;&nbsp;<input style="width: 200px;" type="text" name="to_query" value="<?php if(isset($_POST['to_query'])) echo $_POST['to_query']; ?>">
		</div>
	</td>
	<td><button type="submit">Искать</button></td>
  </tr>
</table>
</form>

<?php
// вот этот fields надо переделать в массив того что выбрано и засунуть в qb
$fileds="";
if( isset($_REQUEST['ff']) && count($_REQUEST['ff'])>0 ){
  foreach($_REQUEST['ff'] as $ind=>$val){
	if( !preg_match("/tv_/i", $val) ){
		$fileds .= ", tb1.". $val;
	}
  }
}

if( isset($_POST['parent_tg_id']) && intval($_POST['parent_tg_id'])>0 ){

// --> Вот это вот всё с $isfolder - хитрожопая проверка. Она нужна только на модХ хитрожопой базе. Убери её
//	$isfolder=false;
//	$res = $modx->db->query("SELECT * FROM modx_site_content WHERE modx_site_content.parent = ".intval($_POST['parent_tg_id']));
//	while($row = $modx->db->getRow($res)) {
//		if( $row['isfolder']==1 ){
//			$isfolder=true;
//			break;
//		}
//	}
//	if( $isfolder ){
//		echo '<table><tr><td style="color: red;">Ошибка: Дочерние ресурсы выбранного раздела не являются конечными</td></tr></table>';
//	}else{
	// --> Здесь нужен просто запрос по формированию списка товаров с выделенными полями. Запрос будет совсем другого вида, не как здесь
//$table->fetchData("SELECT tb1.id " . $fileds . " FROM products as tb1
//WHERE tb1.section_id = " . intval($_POST['parent_tg_id']) . " ORDER BY tb1.id ASC" );
//
//?>
<!---->
<!--  <div>-->
<?//= $table->getTable() ?>
<!--  </div>-->
<?//= $table->getJavascript("jquery") ?>

<?php
//	}
}elseif( isset($_POST['type_search']) && $_POST['type_search']!="" ){
// --> Обработка выбранных условий. Нужно будет переписать каждый запрос. Будет весело =)
	if( preg_match("/tv_/i", $_POST['field_search']) ){
		$field = preg_replace("/tv_/i", "", $_POST['field_search']);
	  if( intval($_POST['type_search']) == 2 && $_POST['from_query'] != "" ){
		  $field_search="tb1." . $field . " >= '" . $_POST['from_query']."' AND tb1." . $field . " <= '".$_POST['to_query']."'";
	  }elseif( intval($_POST['type_search'])==1 ){
		  $field_search="tb1." . $field . " LIKE '%".$_POST['query']."%'";
	  }else{
		  $field_search="tb1.". $field . " LIKE '" . $_POST['query'] . "'";
	  }
	}
//	else{
//	  if( intval($_POST['type_search'])==2 && $_POST['from_query']!="" ){
//		  $field_search="tb1.".$_POST['field_search']." >= '".$_POST['from_query']."' AND tb1.".$_POST['field_search']." <= '".$_POST['to_query']."'";
//	  }elseif( intval($_POST['type_search'])==1 ){
//		  $field_search="tb1.".$_POST['field_search']." LIKE '".$_POST['query']."%'";
//	  }else{
//		  $field_search="tb1.".$_POST['field_search']." LIKE '".$_POST['query']."'";
//	  }
//	}

// --> Запрос с условиями у нас будет только по одной таблице products. Поэтому будет гораздо проще.
$table->fetchData("SELECT tb1.id " . $fileds . " FROM products as tb1 WHERE " . $field_search . " GROUP BY tb1.id");

?>

  <div class="wrapper">
<?= $table->getTable() ?>
  </div>
<?= $table->getJavascript("jquery") ?>

<?php
}
?>

<?php
	return $data = ob_get_clean();
	ob_end_clean();
?>