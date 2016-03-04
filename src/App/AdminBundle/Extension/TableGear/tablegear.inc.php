<?php

// Необходимо для вывода этого файла в переменную и отрисовку в фрейме sonata admin
ob_start();

require_once $libPath;

// --> Эти options нужны для конигурации TableGear
$options = array();
$options["database"]   = array();
$options["pagination"] = array();

$options["database"]["name"]     = $container->getParameter('database_name');
$options["database"]["username"] = $container->getParameter('database_user');
$options["database"]["password"] = $container->getParameter('database_password');
$options["database"]["table"]    = 'products';
$options["database"]["utf8"]     = true;

$options["pagination"]["perPage"] = 300;    // rows per page.
$options["pagination"]["prev"]    = "prev"; // "prev" link will be shown.
$options["pagination"]["next"]    = "next"; // "next" link will be shown.

$options["callback"] = array("onUpdate" => "updatePriceDate", "getPrevious" => true);
$options["columns"] = array("price_date" => "highlite sortable date");
$options['formatting'] = array("price_date" => "date[d.m.Y]");

/**
 * Коллбек для апдейта Даты цены.
 * Используется стандартная сигнатура для функций-коллбеков в TableGear
* @param $key      - PK изменяемого кортежа. В нашем случае он null, поэтому получаем явно $previous['id']
* @param $previous - изменяемый объект, хрянящий инфу до изменения
* @param $updated  - изменяемый объект, хрянящий инфу после изменения
* @param $ref      - ссылка на инстанс TableGear. Нам нужна для выполнения MySQL query.
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

$options["headers"] = $productRepository->getTableGearProperties();

// --> Предполагаю, что этот класс юзаем "из коробки" и вообще не трогаем в нём код
$table = new TableGear($options);

$cookie_ff = array();
if (isset($_COOKIE['tg_filterf'])) {
	$cookie_ff = unserialize($_COOKIE['tg_filterf']);
} else {
	$cookie_ff = $productRepository->getTableGearDefaultProperties();
	$cookie_fs = $productRepository->getTableGearDefaultProperties();
}

// --> Здесь через $_POST и $_COOKIE формируется список полей. Вроде менять ничего не надо
$ff = "";
$sort_ff = "";

foreach($options["headers"] as $ind => $val){
	$ff.="<div style=\"float: left; clear: both;\"><label style=\"font-weight: 100\" for=\"ff_".$ind."\" id=\"fs_".$ind."\">".$val."</label></div><div style=\"float: right;\"><input type=\"checkbox\"";

	if( in_array($ind, $_POST["ff"]) || (!$_POST["ff"] && in_array($ind, $cookie_ff)) ){
		$ff.=" checked ";
		$sort_ff.='<li class="ui-state-default sortf_'.$ind.'"><input type="hidden" name="sortff[]" value="'.$ind.'">'.$val.'</li>';
	}

	$ff.="name=\"ff[]\" value=\"".$ind."\" id=\"ff_".$ind."\"></div>";
}

if (isset($_REQUEST['sortff']) && count($_REQUEST['sortff']) > 0) {
  $sort_ff = "";

  foreach($_REQUEST['sortff'] as $ind=>$val){
	$sort_ff.='<li class="ui-state-default sortf_'.$val.'"><input type="hidden" name="sortff[]" value="'.$val.'">'.$options["headers"][$val].'</li>';
  }

  setcookie( "tg_filterf_s", serialize($_POST["sortff"]), time()+2592000 );
} elseif (isset($_COOKIE['tg_filterf_s'])) {
	$sort_ff = "";
	$cookie_fs = unserialize($_COOKIE['tg_filterf_s']);

	foreach($cookie_fs as $ind=>$val) {
		$sort_ff.='<li class="ui-state-default sortf_'.$val.'"><input type="hidden" name="sortff[]" value="'.$val.'">'.$options["headers"][$val].'</li>';
	}
}

if (count($_POST["ff"]) > 0){
	setcookie("tg_filterf", serialize($_POST["ff"]), time()+2592000);
}
?>
<form method="post" name="formtg">
	<input type="hidden" name="parent_tg_id" value="<?php if( isset($_POST['parent_tg_id']) && intval($_POST['parent_tg_id'])>0 ) echo intval($_POST['parent_tg_id']); ?>">
	<input type="hidden" name="parent_tg_name" value="">

	<h4 class="choose_razd" style="cursor: pointer;" onclick="enableParentSelectionTG();" >Выберите раздел</h4>
	<a class="btn btn-primary btn-xs hide_sort" href="javascript:;"><?php if( isset($_COOKIE['hide_sort']) &&
		$_COOKIE['hide_sort']==0){ echo 'показать'; } else { echo 'скрыть'; } ?></a>

	<div class="filter-wrapper row">
		<div class="filter-fields-wrapper trhide" style="<?php if( isset($_COOKIE['hide_sort']) &&
			$_COOKIE['hide_sort']==0){
			echo
		'display: none;'; } ?>">
			<label>Фильтр полей:</label>
			<div class="filter-fields"><?php echo $ff;?></div>
		</div>

		<div class="sort-fields-wrapper trhide" style="<?php if( isset($_COOKIE['hide_sort']) &&
			$_COOKIE['hide_sort']==0){ echo
		'display: none;'; } ?>" >
			<label>Сортировка полей:</label>
			<ul id="sortable">
				<?php echo $sort_ff; ?>
			</ul>
		</div>
	</div>

	<div class="filter-wrapper row">
		<div class="col-lg-4">
			<div class="form-group">
				<label>Соответствие:</label>
				<select class="form-control" name="type_search" onchange="if( $(this).val()==2 ){ $('' +
					 '.range_search').show(); $('.text_search').hide(); }else{ $('.range_search').hide(); $('.text_search').show(); }">
					<option value="0">Точное соответствие</option>
					<option value="1" <?php if(isset($_POST['type_search']) && $_POST['type_search']==1 ) echo 'selected'; ?>>Неточное соответствие</option>
					<option value="2" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ) echo 'selected'; ?>>Числовой диапазон</option>
				</select>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="form-group">
				<label>Поиск по:</label>
				<select class="form-control" name="field_search">
					<?php
					foreach($productRepository->getTableGearProperties() as $id => $title) {
						echo "<option value=\"tv_".$id."\" ";
						if(isset($_POST['field_search']) && $_POST['field_search']=="tv_".$id ){ echo 'selected'; }
						echo ">".$title."</option>";
					}
					?>
				</select>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="form-group">
				<div class="text_search" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ){ echo 'style="display: none;"'; } ?>>
					<label>Искомое слово:</label>
					<input class="form-control" type="text" name="query" value="<?php if(isset
					($_POST['query'])) echo $_POST['query']; ?>" placeholder="Например: плита">
				</div>
				<div class="range_search" <?php if(isset($_POST['type_search']) && $_POST['type_search']==2 ){ echo ''; }else{ echo 'style="display: none;"'; } ?>>
					От:&nbsp;<input style="width: 200px;" type="text" name="from_query" value="<?php if(isset($_POST['from_query'])) echo $_POST['from_query']; ?>">&nbsp;&nbsp;До:&nbsp;&nbsp;<input style="width: 200px;" type="text" name="to_query" value="<?php if(isset($_POST['to_query'])) echo $_POST['to_query']; ?>">
				</div>
			</div>
		</div>
	</div>

	<button class="btn btn-success btn-lg btn-search" type="submit">Искать</button>
</form>

<?php
// вот этот fields надо переделать в массив того что выбрано и засунуть в qb
$fileds = "";
if (isset($_REQUEST['ff']) && count($_REQUEST['ff']) > 0) {
  foreach($_REQUEST['ff'] as $ind=>$val) {
	if( !preg_match("/tv_/i", $val) ){
		$fileds .= ", tb1.". $val;
	}
  }
} else {
	foreach($cookie_fs as $val) {
		$fileds .= ", tb1.". $val;
	}
}

if (isset($_POST['parent_tg_id']) && intval($_POST['parent_tg_id']) > 0) {
} elseif ( isset($_POST['type_search']) && $_POST['type_search'] != "" ) {
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

	// --> Запрос с условиями у нас будет только по одной таблице products. Поэтому будет гораздо проще.
	$table->fetchData("SELECT tb1.id " . $fileds . " FROM products as tb1 WHERE " . $field_search . " GROUP BY tb1.id");
	?>
	<?= $table->getTable() ?>
	<?= $table->getJavascript("jquery") ?>
	<?php
}
?>

<?php
	return $data = ob_get_clean();
	ob_end_clean();
?>