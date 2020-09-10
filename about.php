<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$t=file_get_contents($_SERVER["DOCUMENT_ROOT"]."/upload/test1.csv");
$t=str_replace("'", "", $t);
$y=explode(";", $t); 
$x=array_chunk($y, 6);

$csv=-1;
foreach ($x as $key) {
	$csv++;	
}
session_start();
if ($t!=$_SESSION['time']) {
	$_SESSION['time']=$t;
	echo "<h1>Данные обновлены!</h1>";

	CModule::IncludeModule('iblock');
	$inf=0;
	$sum=1;
	$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>10));
	while($ob = $res->GetNext()){
		$el = new CIBlockElement;
		$PROP = array();
			$PROP['name'] = $x[$sum][1]; 
			$PROP['preview'] = $x[$sum][2]; 
			$PROP['detal'] = $x[$sum][3];
			$PROP['prop1'] = $x[$sum][4]; 
			$PROP['prop2'] = $x[$sum][5];

		$fields = Array(
			"IBLOCK_ID"      => 10,
			"PROPERTY_VALUES"=> $PROP,
			"NAME"           => $PROP['name'],
			"ACTIVE"         => "Y",
			"PREVIEW_TEXT"   => $PROP['preview'],
			"DETAIL_TEXT"    => $PROP['detal']);

	    $el->Update($ob["ID"], $fields);
	    $inf++;
	    $sum++;
	}

	if($csv>$inf){
		for ($i=$inf+1; $i <=$csv ; $i++) { 		
			$el = new CIBlockElement;
			$PROP = array();
			$PROP['name'] = $x[$i][1]; 
			$PROP['preview'] = $x[$i][2]; 
			$PROP['detal'] = $x[$i][3];
			$PROP['prop1'] = $x[$i][4]; 
			$PROP['prop2'] = $x[$i][5]; 

			$fields = Array(
			  "IBLOCK_ID"      => 10,
			  "PROPERTY_VALUES"=> $PROP,
			  "NAME"           => $PROP['name'],
			  "ACTIVE"         => "Y",
			  "PREVIEW_TEXT"   => $PROP['preview'],
			  "DETAIL_TEXT"    => $PROP['detal']);

			$el->Add($fields);
		}
		$un=$csv-$inf;
		echo "<h1>Добавлено новых элементов: ". $un. "</h1>";
	}

	$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>10));
	while($ob = $res->GetNext()){
		$id_el[]+=$ob["ID"];
	}

	if($inf>$csv){
		for ($i=$csv; $i <$inf; $i++) {
			CIBlockElement::Delete($id_el[$i]);
	    }
	    $un=$inf-$csv;
		echo "<h1>Удалено старых элементов: ". $un. "</h1>";
	} 
}
else{
	echo "<h1>Данные не изменились</h1>";
}
	
 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>