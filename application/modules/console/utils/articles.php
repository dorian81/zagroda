<?php
    function resize($width,$height,$imgName){
	$ext=substr($imgName,count($imgName)-4,3);
	if ($ext=='png'){
	    if(!($img = imagecreatefrompng($imgName))){
		echo("Nie mogę otworzyć pliku:\"". $imgName."\"");
		return false;
	    }
	}else{
	    if(!($img = imagecreatefromjpeg($imgName))){
		echo("Nie mogę otworzyć pliku:\"". $imgName."\"");
		return false;
	    }
	}
	list($szerokosc, $wysokosc) = getimagesize($imgName);
	if ($szerokosc>$width){
	    if ($szerokosc>$wysokosc){
		$skala=$szerokosc/$width;
		$n_wysokosc=floor($wysokosc/$skala);
		$n_szerokosc=$width;
	    }else{
		$skala=$wysokosc/$height;
		$n_wysokosc=$height;
		$n_szerokosc=floor($szerokosc/$skala);
	    }
	    $tempImg = imagecreatetruecolor($n_szerokosc,$n_wysokosc);
	    imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $n_szerokosc, $n_wysokosc, $szerokosc, $wysokosc);
	}else $tempImg = $img;
	return $tempImg;
    }
    
    function upload_file($file,$dir){
	$name=$file;
	$public_path = '/var/www/zagroda/public/';
	if (imagejpeg(resize(70,100,$public_path.'tmp/'.$name),$public_path.'issues/'.$dir.'/m/'.$name)&&imagejpeg(resize(900,1273,$public_path.'tmp/'.$name),$public_path.'issues/'.$dir.'/'.$name)) return true;
	else return false;
    }
?>