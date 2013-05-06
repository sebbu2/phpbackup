<?php
/* vim: set ts=4 sw=4: */
ini_set('include_path',ini_get('include_path').':/home/bipyyyyy/php');
include_once('Archive/Zip.php');
class Archive_Zip2 extends Archive_Zip {
	function Archive_Zip2($p_zipname)
	{
		parent::Archive_Zip($p_zipname);
	}
	function _convertHeader2FileInfo($p_header, &$p_info)
	{
		$v_result = 1;

		// ----- Get the interesting attributes
		$p_info['filename']			= $p_header['filename'];
		$p_info['stored_filename']	= $p_header['stored_filename'];
		$p_info['size']				= $p_header['size'];
		$p_info['compressed_size']	= $p_header['compressed_size'];
		$p_info['mtime']			= $p_header['mtime'];
		$p_info['crc']				= $p_header['crc'];
		$p_info['comment']			= $p_header['comment'];
		$p_info['folder']			= (($p_header['external']&0x00000010)==0x00000010);
		$p_info['index']			= $p_header['index'];
		$p_info['status']			= $p_header['status'];

		return $v_result;
	}
}
?>