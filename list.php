<?php
require_once('config.php');
//var_dump(umask());die();
umask(0000);
$filename='html.zip';
$zip = new Archive_Zip2($filename);
$files=array();
$files[]='index.html';
//$files[]='tabs.css';
//$files[]='search/search.css';
//$files[]='search/search.js';
//$files[]='doxygen.css';
//$files[]='search/mag_sel.png';
//$files[]='search/close.png';
//$files[]='doxygen.png';
$params=array();
$params1=array();
$params1['add_path']='html2/';
$params2=array();
$params2['remove_path']='html2/';
$res='';

if(file_exists($filename)) {
	/*$res2=chown($filename, 'sebbu');
	var_dump($res2);
	$res2=chgrp($filename, 'sebbu');
	var_dump($res2);
	//$res2=chmod($filename, 0644);
	//$res2=chmod($filename, 0755);
	$res2=chmod($filename, 0777);
	var_dump($res2);//*/
}

//$res=$zip->listContent();
$files2=array();
$res=$zip->_list($files2);
//$res=$zip->listExtended($files2);
$res=$files2;
echo '<pre>'."\r\n";
var_dump($res);
echo '</pre>'."\r\n";
?>