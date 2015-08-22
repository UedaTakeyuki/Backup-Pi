<?php
require_once("common/common.php");
error_log('['.basename(__FILE__).':'.__LINE__.']'."start");
// dd コマンドに USR1 シグナルを送る
$result = `sudo pkill -USR1 -f dd`;
sleep(4);
// dd.backup.log ファイルの存在確認
$dd_backup_file_exist = `if [ -e /boot/log/dd.backup.log ]; then echo "yes"; else echo "no"; fi`;
$dd_restore_file_exist = `if [ -e /boot/log/dd.restore.log ]; then echo "yes"; else echo "no"; fi`;
//JSON形式で出力する
$json['backup_running']=$dd_backup_file_exist;
$json['restore_running']=$dd_restore_file_exist;

if ($json['backup_running'] == "yes\n") {
  // dd の経過サイズ情報を追加
  $size = explode(' ', `tail -n 1 /boot/log/dd.backup.log`)[0];
#  $json['backup_size']=intval($size);
  $json['backup_size']=$size; # 32bit だと 2G であふれるので、文字列として処理
} else {
  $json['backup_size']=0;
}
if ($json['restore_running'] == "yes\n") {
  // dd の経過サイズ情報を追加
  $size = explode(' ', `tail -n 1 /boot/log/dd.restore.log`)[0];
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