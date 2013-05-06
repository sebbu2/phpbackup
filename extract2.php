<?php
require_once('config.php');
//var_dump(umask());die();
umask(0000);
clearstatcache();
$dir='html2/';
$filename='html2.zip';
$filename2='html2_updated.zip';

$params=array();
$params1=array();
$params1['add_path']=$dir;
$params2=array();
$params2['remove_path']=$dir;
$res='';

assert(substr($dir,-1)==='/');

function add_path(&$value, $key) {
	global $dir;
	$value=$dir.$value;
	//$value='html2/'.$value;
}
function remove_path(&$value, $key) {
	global $dir;
	if(substr($value,0,strlen($dir))==$dir) $value=substr($value,strlen($dir));
	//if(substr($value,0,6)=='html2/') $value=substr($value,6);
}
function show_crc($crc) {
	return str_pad(dechex($crc), 8, '0', STR_PAD_LEFT);
}
//array_walk($files, 'add_path');

if(!file_exists($filename)) {
	die('can\'t extract an unexistant archive !');
}
function decompress($filename, $dir) {
	global $params1;
	global $params2;
	if(!file_exists($filename)) return;
	$current=array();
	$zip = new Archive_Zip2($filename);
	var_dump(microtime(true));echo '<br/>'."\r\n";
	$res=$zip->_list($current);
	var_dump(microtime(true));echo '<br/>'."\r\n";
	echo '<pre>'."\r\n";
	var_dump(count($current));
	//print_r($current);
	echo '</pre>'."\r\n";
	assert($res==1) or die($res.' - '.$zip->errorName(true));
	//unset($zip);
	//$zip=NULL;
	var_dump(microtime(true));echo '<br/>'."\r\n";
	$files2=array();
	foreach($current as $file) {
		$files2[$dir.$file['filename']]=$file;
	}
	var_dump(microtime(true));echo '<br/>'."\r\n";
	unset($current);
	$current=$files2;
	$files2=array();
	var_dump(microtime(true));echo '<br/>'."\r\n";
	foreach($current as $key=>$value) {
		if(!file_exists($key)) {
			echo 'non-existent file : '.$key.' !<br/>'."\r\n";
			$files2[]=$key;
		}
		else {
			if(crc32(file_get_contents($key))!=$value['crc']) {
				echo 'different file : '.$key.' !<br/>'."\r\n";
				$files2[]=$key;
			}
		}
	}
	var_dump(microtime(true));echo '<br/>'."\r\n";
	echo '<pre>'."\r\n";
	echo 'number of non-existant/different files : ';
	var_dump(count($files2));echo '<br/>'."\r\n";
	echo '</pre>'."\r\n";
	//$zip2 = new Archive_Zip2($filename2);
	//$res=$zip2->create($files2, $params2);
	//assert(is_array($res)) or die($res.' - '.$zip->errorName(true));
	var_dump(microtime(true));echo '<br/>'."\r\n";
	$res=$zip->extract(array_merge(array('by_name'=>implode(',',$files2)), $params1));
	var_dump(microtime(true));echo '<br/>'."\r\n";
	assert(is_array($res)) or die($res.' - '.$zip->errorName(true));
	echo '<pre>'."\r\n";
	var_dump(count($res));
	//print_r($res);
	echo '</pre>'."\r\n";
}
decompress($filename, $dir);
decompress($filename2, $dir);
?>