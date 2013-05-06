<?php
require_once('config.php');
//var_dump(umask());die();
umask(0000);
clearstatcache();
$filename='html.zip';
$filename2='html_updated.zip';

function list_dir($dir, &$ar) {
	assert(substr($dir,-1)=='/') or die('directory need to end with /');
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if(strcmp($file,'.')==0) continue;
				if(strcmp($file,'..')==0) continue;
				//echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
				if(is_file($dir.$file)) {
					$ar[]=$dir.$file;
				}
				else if(is_dir($dir.$file)) {
					list_dir($dir.$file.'/', $ar);
				}
				else {
					fprintf(stderr, '[ERROR] in %s("%s", $ar) in %s:%d'."\n", __FUNC__, $dir, __FILE__, __LINE__);
					die();
				}
			}
			closedir($dh);
		}
		else {
			fprintf(stderr, '[ERROR] failed to open directory "%s"'."\n", $dir);
			die();
		}
	}
	else {
		fprintf(stderr, '[ERROR] "%s" isn\'t a directory'."\n", $dir);
		die();
	}
}

$dir = "html/";
$files=array();
list_dir($dir, $files);

echo '<pre>'."\r\n";
//print_r($files);
var_dump(count($files));
echo '</pre>'."\r\n";
//die();

$params=array();
$params1=array();
$params1['add_path']='html/';
$params2=array();
$params2['remove_path']='html/';
$res='';

function add_path(&$value, $key) {
	$value='html/'.$value;
}
function remove_path(&$value, $key) {
	if(substr($value,0,5)=='html/') $value=substr($value,5);
}
function show_crc($crc) {
	return str_pad(dechex($crc), 8, '0', STR_PAD_LEFT);
}
//array_walk($files, 'add_path');

if(!file_exists($filename)) {
	$zip = new Archive_Zip2($filename);
	$res=$zip->create($files, $params2);
	assert(is_array($res)) or die($res.' - '.$zip->errorName(true));
	echo '<pre>'."\r\n";
	var_dump(count($res));
	//print_r($res);
	echo '</pre>'."\r\n";
}
else {
	$current=array();
	$zip = new Archive_Zip2($filename);
	$res=$zip->_list($current);
	echo '<pre>'."\r\n";
	var_dump(count($current));
	//print_r($current);
	echo '</pre>'."\r\n";
	assert($res==1) or die($res.' - '.$zip->errorName(true));
	unset($zip);
	$zip=NULL;
	$files2=array();
	foreach($current as $file) {
		$files2[$dir.$file['filename']]=$file;
	}
	unset($current);
	$current=$files2;
	$files2=array();
	foreach($current as $key=>$value) {
		if(crc32(file_get_contents($key))!=$value['crc']) {
			echo 'different file : '.$key.' !'."<br/>\r\n";
			$files2[]=$key;
		}
	}
	echo 'number of different files : ';
	var_dump(count($files2));
	$zip2 = new Archive_Zip2($filename2);
	$res=$zip2->create($files2, $params2);
	assert(is_array($res)) or die($res.' - '.$zip->errorName(true));
}
?>