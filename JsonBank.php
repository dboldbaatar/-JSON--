<?php

	function getbasename($filename)
	{
	    $url_arr  = explode('/', $filename);
	    $ct       = count($url_arr);
	    $name     = $url_arr[$ct - 1];
	    $name_div = explode('.', $name);
	    $ct_dot   = count($name_div);
	    $img_type = $name_div[$ct_dot - 2];
	    return $img_type;
	}

	function html2txt($document)
	{
	    
	    $search = array(
	        '@<script[^>]*?>.*?</script>@si', // Strip out javascript 
	        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags 
	        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly 
	        '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments including CDATA 
	        
	    );
	    $text   = preg_replace($search, '', $document);
	    $text   = str_replace('  ', '', $text);
	    $text   = str_replace("\n", "", $text);
	    $text   = str_replace("\r", "", $text);
	    $text   = str_replace(" &nbsp;", "", $text);
	    $text   = str_replace("&nbsp;", "", $text);
	    
	    return $text;
	    
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
	$tdmatch = $matches[0][1];
	preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $tdmatch, $matches);

	foreach ($matches[0] as $key => $item) {
	    if ($key > 1) {
	        preg_match_all('/<td.*?>(.*?)<\/td>/si', $item, $matchestd);
	        
	        preg_match('/src="(.*)"/iS', $matchestd[0][0], $src);
	        $code                                  = getbasename($src[1]);
	        $data['golomt'][$key]['name']          = $ratenames[$code];
	        $data['golomt'][$key]['code']          = $code;
	        $data['golomt'][$key]['haalthansh']    = html2txt($matchestd[0][1]);
	        $data['golomt'][$key]['belenavah']     = html2txt($matchestd[0][2]);
	        $data['golomt'][$key]['belenzarah']    = html2txt($matchestd[0][3]);
	        $data['golomt'][$key]['belenbusavah']  = html2txt($matchestd[0][4]);
	        $data['golomt'][$key]['belenbuszarah'] = html2txt($matchestd[0][5]);
	    }
	    
	}

	$data['golomt'] = array_values($data['golomt']);


	$opts    = array(
	    'http' => array(
	        'header' => "User-Agent:MyAgent/1.0\r\n"
	    )
	);
	$context = stream_context_create($opts);

	$html = file_get_contents("https://www.khanbank.com/mn/home/rates", false, $context);

	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	$tdmatch = $matches[0][0];
	preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $tdmatch, $matches);



	foreach ($matches[0] as $key => $item) {
	    if ($key > 1) {
	        preg_match_all('/<td.*?>(.*?)<\/td>/si', $item, $matchestd);
	        
	        preg_match('/src="(.*)"/iS', $matchestd[0][0], $src);
	        $code                                = getbasename($src[1]);
	        $data['khan'][$key]['name']          = $ratenames[$code];
	        $data['khan'][$key]['code']          = $code;
	        $data['khan'][$key]['haalthansh']    = html2txt($matchestd[0][1]);
	        $data['khan'][$key]['belenavah']     = html2txt($matchestd[0][2]);
	        $data['khan'][$key]['belenzarah']    = html2txt($matchestd[0][3]);
	        $data['khan'][$key]['belenbusavah']  = html2txt($matchestd[0][4]);
	        $data['khan'][$key]['belenbuszarah'] = html2txt($matchestd[0][5]);
	    }
	    
	}

	$data['khan'] = array_values($data['khan']);


	$html = file_get_contents("http://www.tdbm.mn/script.php?mod=rate&ln=mn");
	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	$tdmatch = $matches[0][0];
	preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $tdmatch, $matches);

	foreach ($matches[0] as $key => $item) {
	    if ($key > 2) {
	        preg_match_all('/<td.*?>(.*?)<\/td>/si', $item, $matchestd);
	        preg_match('/src="(.*)"/iS', $matchestd[0][0], $src);
	        $code                                = getbasename($src[1]);
	        $data['tdbm'][$key]['name']          = $ratenames[$code];
	        $data['tdbm'][$key]['code']          = $code;
	        $data['tdbm'][$key]['haalthansh']    = html2txt($matchestd[0][1]);
	        $data['tdbm'][$key]['belenavah']     = html2txt($matchestd[0][2]);
	        $data['tdbm'][$key]['belenzarah']    = html2txt($matchestd[0][3]);
	        $data['tdbm'][$key]['belenbusavah']  = html2txt($matchestd[0][4]);
	        $data['tdbm'][$key]['belenbuszarah'] = html2txt($matchestd[0][5]);
	    }
	    
	}

	$data['tdbm'] = array_values($data['tdbm']);

	$html = file_get_contents("http://xacbank.mn/mn/calculator/rates");
	preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
	$tdmatch = $matches[0][1];
	preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $tdmatch, $matches);

	foreach ($matches[0] as $key => $item) {
	    if ($key > 1) {
	        preg_match_all('/<td.*?>(.*?)<\/td>/si', $item, $matchestd);
	        
	        preg_match('/src="(.*)"/iS', $matchestd[0][0], $src);
	        $code                               = getbasename($src[1]);
	        $data['xac'][$key]['name']          = $ratenames[$code];
	        $data['xac'][$key]['code']          = $code;
	        $data['xac'][$key]['haalthansh']    = html2txt($matchestd[0][1]);
	        $data['xac'][$key]['belenavah']     = html2txt($matchestd[0][2]);
	        $data['xac'][$key]['belenzarah']    = html2txt($matchestd[0][3]);
	        $data['xac'][$key]['belenbusavah']  = html2txt($matchestd[0][4]);
	        $data['xac'][$key]['belenbuszarah'] = html2txt($matchestd[0][5]);
	    }
	    
	}

	$data['xac'] = array_values($data['xac']);

	echo json_encode($data);

