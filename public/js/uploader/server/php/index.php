<?php
/*
 * jQuery File Upload Plugin PHP Example 5.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

require('upload.class.php');

$file = fopen('err.log','w');
fwrite($file,'test');
fclose($file);

$options=array(
    'upload_dir' => '/var/www/zagroda/public/issues/'.$_POST['dir'].'/',
    'upload_url' => 'http://'.$_SERVER['SERVER_NAME'].'/issues/'.$_POST['dir'].'/',
    'image_versions' => array(
			      'large' => array(
						'upload_dir' => '/var/www/zagroda/public/issues/'.$_POST['dir'].'/',
						'upload_url' => 'http://'.$_SERVER['SERVER_NAME'].'/issues/'.$_POST['dir'].'/',
						'max_width' => 900,
						'max_height' => 1273,
					),
				'thumbnail' => array(
						    'upload_dir' => '/var/www/zagroda/public/issues/'.$_POST['dir'].'/m/',
						    'upload_url' => 'http://'.$_SERVER['SERVER_NAME'].'/issues/'.$_POST['dir'].'/m/',
						    'max_width' => 80,
						    'max_height' => 80
						)
			    ),
			      'issue'=>$_POST['dir']
		);

$upload_handler = new UploadHandler($options);

//$upload_handler = new UploadHandler();

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Disposition: inline; filename="files.json"');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
        break;
    case 'HEAD':
    case 'GET':
        $upload_handler->get();
        break;
    case 'POST':
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            $upload_handler->delete();
        } else {
            $upload_handler->post();
        }
        break;
    case 'DELETE':
        $upload_handler->delete();
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
}
