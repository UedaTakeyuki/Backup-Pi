<?php
require_once("common/common.php");
error_log('['.basename(__FILE__).':'.__LINE__.']'."start");
// dd コマンドに USR1 シグナルを送る
$result = `sudo pkill -USR1 -f dd`;
sleep(1);
// dd.backup.log ファイルの存在確認
#$dd_backup_file_exist = `if [ -e /boot/log/dd.backup.log ]; then echo "yes"; else echo "no"; fi`;
$dd_backup_file_exist_command = 'if [ -e '.DD_BACKUP_LOG.' ]; then echo "yes"; else echo "no"; fi';
$dd_backup_file_exist = `$dd_backup_file_exist_command`;
#$dd_restore_file_exist = `if [ -e /boot/log/dd.restore.log ]; then echo "yes"; else echo "no"; fi`;
$dd_restore_file_exist_command = 'if [ -e '.DD_RESTORE_LOG.' ]; then echo "yes"; else echo "no"; fi';
$dd_restore_file_exist = `$dd_restore_file_exist_command`;
$dd_process_exist = `ps -aef | grep "sudo /bin/dd " | wc -l`;
#sleep(9);
//JSON形式で出力する
$json['backup_running']=$dd_backup_file_exist;
$json['restore_running']=$dd_restore_file_exist;
$json['dd_process_exist']=substr($dd_process_exist,0,-1);

if ($json['backup_running'] == "yes\n") {
  // dd の経過サイズ情報を追加
  $backup_running_command = 'tail -n 1 '.DD_BACKUP_LOG;
  $size = explode(' ', `$backup_running_command`)[0];
#  $size = explode(' ', `tail -n 1 /boot/log/dd.backup.log`)[0];
#  $json['backup_size']=intval($size);
  $json['backup_size']=$size; # 32bit だと 2G であふれるので、文字列として処理
} else {
  $json['backup_size']=0;
}
if ($json['restore_running'] == "yes\n") {
  // dd の経過サイズ情報を追加
  $backup_running_command = 'tail -n 1 '.DD_RESTORE_LOG;
  $size = explode(' ', `$backup_running_command`)[0];
#  $size = explode(' ', `tail -n 1 /boot/log/dd.restore.log`)[0];
#  $json['restore_size']=intval($size);
  $json['restore_size']=$size;
} else {
  $json['restore_size']=0;
}

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
$json_str = json_encode( $json );
error_log('$json_str = '.$json_str);
echo $json_str;
//	echo json_encode( $json );
	exit;
?>