<?php
require_once("common/common.php");
error_log('['.basename(__FILE__).':'.$line.']'."start");
// dd.backup.log ファイルの存在確認
$dd_backup_file_remove_command = "rm ".DD_BACKUP_LOG;
$dd_backup_file_remove = `$dd_backup_file_remove_command`;
#$dd_backup_file_remove = `rm /boot/log/dd.backup.log`;
$dd_restore_file_remove_command = "rm ".DD_RESTORE_LOG;
$dd_restore_file_remove = `$dd_restore_file_remove_command`;
#$dd_restore_file_remove = `rm /boot/log/dd.restore.log`;
//JSON形式で出力する
$json['result_remove_backuplog']=$dd_backup_file_remove;
$json['result_remove_restorelog']=$dd_restore_file_remove;

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
$json_str = json_encode( $json );
error_log('$json_str = '.$json_str);
echo $json_str;
//	echo json_encode( $json );
	exit;
?>