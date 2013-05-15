<?php
    function upload_cover($file,$issue){
	$success=true;
	$imgName=$file;
	$ext=substr($file,count($file)-4,3);
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
	
	$pname='/var/www/zagroda/public/covers/';
	$name=$pname.$issue.'_1.jpg';
	$mname=$pname.$issue.'_2.jpg';
	$mmname=$pname.$issue.'_3.jpg';
	list($szerokosc, $wysokosc) = getimagesize($imgName);
	if ($szerokosc>400){
	    if ($szerokosc>$wysokosc){
		$skala=$szerokosc/400;
		$n_wysokosc=floor($wysokosc/$skala);
		$n_szerokosc=400;
	    }else{
		$skala=$wysokosc/580;
		$n_wysokosc=580;
		$n_szerokosc=floor($szerokosc/$skala);
	    }
	    $tempImg = imagecreatetruecolor($n_szerokosc,$n_wysokosc);
	    imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $n_szerokosc, $n_wysokosc, $szerokosc, $wysokosc);
	    if(!imagepng($tempImg, $name))$success=false;
	}else{
	    if(!move_uploaded_file($file,$name)) $success=false;
	}
	if ($szerokosc>250){
	    if ($szerokosc>$wysokosc){
		$skala=$szerokosc/250;
		$n_wysokosc=floor($wysokosc/$skala);
		$n_szerokosc=250;
	    }else{
		$skala=$wysokosc/360;
		$n_wysokosc=360;
		$n_szerokosc=floor($szerokosc/$skala);
	    }
	    $tempImg = imagecreatetruecolor($n_szerokosc,$n_wysokosc);
	    imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $n_szerokosc, $n_wysokosc, $szerokosc, $wysokosc);
	    if(!imagepng($tempImg, $mname))$success=false;
	}else{
	    if(!move_uploaded_file($file,$name)) $success=false;
	}
	if ($szerokosc>70){
	    if ($szerokosc>$wysokosc){
		$skala=$szerokosc/70;
		$n_wysokosc=floor($wysokosc/$skala);
		$n_szerokosc=70;
	    }else{
		$skala=$wysokosc/100;
		$n_wysokosc=100;
		$n_szerokosc=floor($szerokosc/$skala);
	    }
	    $tempImg = imagecreatetruecolor($n_szerokosc,$n_wysokosc);
	    imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $n_szerokosc, $n_wysokosc, $szerokosc, $wysokosc);
	    if (!imagepng($tempImg, $mmname))$success=false;
	}else{
	    if(!move_uploaded_file($file,$name)) $success=false;
	}
	return $success;
    }

?>