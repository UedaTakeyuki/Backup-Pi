<?php
require_once("common/common.php");
error_log('['.basename(__FILE__).':'.__LINE__.']'."start");
// dd コマンドの中断
$result = `sudo pkill -9 -f dd`;
//JSON形式で出力する
$json['result']=$result;

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
$json_str = json_encode( $json );
error_log('$json_str = '.$json_str);
echo $json_str;
//	echo json_encode( $json );
	exit;
?>