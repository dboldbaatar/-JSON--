<?php 

	function html2txt($document){ 
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
		               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
		               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
		               '@<![\s\S]*?--[ \t\n\r]*>@',         // Strip multi-line comments including CDATA 
		               
		); 
		$text = preg_replace($search, '', $document);
		$text = str_replace('  ', '', $text);
		$text = str_replace("\n", "", $text);
		$text = str_replace("\r", "", $text);
		$text = str_replace(" &nbsp;", "", $text);
		$text = str_replace("&nbsp;", "", $text);
		return $text; 
	}
	
	function get_basename($filename){
    	$url_arr = explode ('/', $filename);
		$ct = count($url_arr);
		$name = $url_arr[$ct-1];
		$name_div = explode('.', $name);
		$ct_dot = count($name_div);
		$img_type = $name_div[$ct_dot-2];
		
		return $img_type;
	}
	
	$ratenames['USD'] = "АНУ доллар";
	$ratenames['EUR'] = "Евро";
	$ratenames['JPY'] = "Японы иен";
	$ratenames['CHF'] = "Швейцар франк";
	$ratenames['SEK'] = "Шведийн крон";
	$ratenames['GBP'] = "Английн фунт";
	$ratenames['BGN'] = "Болгарын лев";
	$ratenames['HUF'] = "Унгарын форинт";
	$ratenames['EGP'] = "Египетийн фунт";
	$ratenames['INR'] = "Энэтхэгийн рупи";
	$ratenames['HKD'] = "Хонгконг доллар";
	$ratenames['RUB'] = "ОХУ-ын рубль";
	$ratenames['KZT'] = "Казахстан тэнгэ";
	$ratenames['CNY'] = "БНХАУ-ын юань";
	$ratenames['KRW'] = "БНСУ-ын вон";
	$ratenames['KPW'] = "БНАСАУ-ын вон";
	$ratenames['CAD'] = "Канадын доллар";
	$ratenames['AUD'] = "Австралийн доллар";
	$ratenames['CZK'] = "Чех крон";
	$ratenames['TWD'] = "Тайван доллар";
	$ratenames['THB'] = "Тайланд бат";
	$ratenames['IDR'] = "Индонезийн рупи";
	$ratenames['MYR'] = "Малайзын ринггит";
	$ratenames['SGD'] = "Сингапур доллар";
	$ratenames['AED'] = "АНЭУ-ын дирхам";
	$ratenames['KWD'] = "Кувейт динар";
	$ratenames['NZD'] = "Шинэ Зеланд доллар";
	$ratenames['DKK'] = "Данийн крон";
	$ratenames['PLN'] = "Польшийн злот";
	$ratenames['UAH'] = "Украйны гривн";
	$ratenames['NOK'] = "Норвегийн крон";
	$ratenames['NPR'] = "Непалын рупи";
	$ratenames['ZAR'] = "Өмнөд Африкийн ранд";
	$ratenames['TRY'] = "Туркийн лира";
	$ratenames['XAU'] = "Алт /унцаар/";
	$ratenames['XAG'] = "Мөнгө /унцаар/";
	$ratenames['SDR'] = "Зээлжих тусгай эрх";
	$ratenames['AUG'] = "Алт /унцаар/";
	$ratenames['AGG'] = "Мөнгө /унцаар/";
	$ratenames['POS'] = "";
	
	$html = file_get_contents("http://golomtbank.mn/mn/home/rates");

	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	
	preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\"/si", $matches[0][1], $images); 
	$images = $images[1];
	
	$tdmatch = preg_replace('/(<img.*>)/', '', $matches[0][1]);
	preg_match_all('/<td.*?>(.*?)<\/td>/si', $tdmatch, $matches); 
	
	
	for ($i=0; $i < count($images)-1 ; $i++) {
		
			$code = get_basename($images[$i]);
			$data['golomt'][$i]['name'] = $ratenames[$code];
			$data['golomt'][$i]['code'] = $code; 
			
			$data['golomt'][$i]['haalthansh']  		= html2txt($matches[0][$i*7+1]);
			//$data['golomt'][$i]['haalthansh_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$i*7+1])));
			
			$data['golomt'][$i]['belenavah'] 		= html2txt($matches[0][$i*7+2]);
			//$data['golomt'][$i]['belenavah_float'] 	= floatval(str_replace(',', '', html2txt($matches[0][$i*7+2])));
			
			$data['golomt'][$i]['belenzarah']  		= html2txt($matches[0][$i*7+3]);
			//$data['golomt'][$i]['belenzarah_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$i*7+3])));
			
			$data['golomt'][$i]['belenbusavah'] 		= html2txt($matches[0][$i*7+4]);
			//$data['golomt'][$i]['belenbusavah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$i*7+4])));
			
			$data['golomt'][$i]['belenbuszarah'] 		= html2txt($matches[0][$i*7+5]);
			//$data['golomt'][$i]['belenbuszarah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$i*7+5])));
			
	}
	
	
	
	
	$html = file_get_contents("https://www.khanbank.com/mn/home/rates");
	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	
	preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\"/si", $matches[0][0], $images); 
	$zurag = $images[1];
	
	for ($i=0; $i < count($zurag) ; $i++) { 
		if ($i % 2 == 0) {
			
		}else{
			$zuragnuud[] = $zurag[$i];
		}
		
	}
	
	$tdmatch = $matches[1][0];
	preg_match_all('/<td.*?>(.*?)<\/td>/si', $tdmatch, $matches); 
	
	// echo '<pre>';
	// print_r($matches[0]);

	for ($i=0; $i < count($zuragnuud)-1 ; $i++) {
			
			$k = $i * 7 +1;
		
			$code = get_basename($zuragnuud[$i]);
			$data['khan'][$i]['name'] = $ratenames[$code];
			$data['khan'][$i]['code'] = $code; 
			
			
			$data['khan'][$i]['haalthansh']  		= html2txt($matches[0][$k+1]);
			//$data['khan'][$i]['haalthansh_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+1])));
			
			$data['khan'][$i]['belenavah'] 		= html2txt($matches[0][$k+2]);
			//$data['khan'][$i]['belenavah_float'] 	= floatval(str_replace(',', '', html2txt($matches[0][$k+2])));
			
			$data['khan'][$i]['belenzarah']  		= html2txt($matches[0][$k+3]);
			//$data['khan'][$i]['belenzarah_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+3])));
			
			$data['khan'][$i]['belenbusavah'] 		= html2txt($matches[0][$k+4]);
			//$data['khan'][$i]['belenbusavah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+4])));
			
			$data['khan'][$i]['belenbuszarah'] 		= html2txt($matches[0][$k+5]);
			//$data['khan'][$i]['belenbuszarah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+5])));
		
		
	}
	
	$html = file_get_contents("http://www.tdbm.mn/script.php?mod=rate&ln=mn");
	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\"/si", $matches[0][0], $images); 
	$images = $images[1];
	
	$tdmatch = $matches[1][0];
	preg_match_all('/<td.*?>(.*?)<\/td>/si', $tdmatch, $matches); 
	
	for ($i=0; $i < count($images) ; $i++) {
			
			
			if ($i==0) {
				$k = 9;
			}else{
				$k = ($i * 6) + 9;
			}
			
			$code = get_basename($images[$i]);
			$data['tdbm'][$i]['name'] = $ratenames[$code];
			$data['tdbm'][$i]['code'] = $code; 
			
			$data['tdbm'][$i]['haalthansh']  		= html2txt($matches[0][$k+1]);
			//$data['tdbm'][$i]['haalthansh_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+1])));
			
			$data['tdbm'][$i]['belenavah'] 		= html2txt($matches[0][$k+2]);
			//$data['tdbm'][$i]['belenavah_float'] 	= floatval(str_replace(',', '', html2txt($matches[0][$k+2])));
			
			$data['tdbm'][$i]['belenzarah']  		= html2txt($matches[0][$k+3]);
			//$data['tdbm'][$i]['belenzarah_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+3])));
			
			$data['tdbm'][$i]['belenbusavah'] 		= html2txt($matches[0][$k+4]);
			//$data['tdbm'][$i]['belenbusavah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+5])));
			
			$data['tdbm'][$i]['belenbuszarah'] 		= html2txt($matches[0][$k+5]);
			//$data['tdbm'][$i]['belenbuszarah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+5])));
		
		
	}
	
	
	$html = file_get_contents("http://xacbank.mn/mn/calculator/rates");
	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	
	preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\"/si", $matches[0][1], $images); 
	$images = $images[1];
	
	$tdmatch = preg_replace('/(<img.*>)/', '', $matches[0][1]);
	preg_match_all('/<td.*?>(.*?)<\/td>/si', $tdmatch, $matches); 
	
	
	
	for ($i=0; $i < count($images) ; $i++) {
			
			$k = $i * 6;
		
			$code = get_basename($images[$i]);
			$data['xac'][$i]['name'] = $ratenames[$code];
			$data['xac'][$i]['code'] = $code; 
			
			$data['xac'][$i]['haalthansh']  		= html2txt($matches[0][$k+1]);
			//$data['xac'][$i]['haalthansh_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+1])));
			
			$data['xac'][$i]['belenavah'] 		= html2txt($matches[0][$k+2]);
			//$data['xac'][$i]['belenavah_float'] 	= floatval(str_replace(',', '', html2txt($matches[0][$k+2])));
			
			$data['xac'][$i]['belenzarah']  		= html2txt($matches[0][$k+3]);
			//$data['xac'][$i]['belenzarah_float']  	= floatval(str_replace(',', '', html2txt($matches[0][$k+3])));
			
			$data['xac'][$i]['belenbusavah'] 		= html2txt($matches[0][$k+4]);
			//$data['xac'][$i]['belenbusavah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+4])));
			
			$data['xac'][$i]['belenbuszarah'] 		= html2txt($matches[0][$k+5]);
			//$data['xac'][$i]['belenbuszarah_float'] = floatval(str_replace(',', '', html2txt($matches[0][$k+5])));
		
		
	}
	
	$var = json_encode($data);
	$filename = 'jsonBank.html';
	$data = fopen( $filename, 'w');
	fwrite($data, $var);
	fclose($data);
	
	
?>
