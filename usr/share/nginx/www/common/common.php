<?php
//=====================================
//
// 共通定数
//
//=====================================
define('COPYRIGHT', '© Atelier UEDA🐸');
define('DD_BACKUP_LOG', '/tmp/dd.backup.log');
define('DD_RESTORE_LOG', '/tmp/dd.restore.log');

//=====================================
//
// 共通クローズ処理
//
//=====================================

//=====================================
//
// 共通ルーチン
//
//=====================================
//---------------------------------------------------------------
// スクリプト入り口ログの出力
// 
// スクリプトファイルの入り口で、ファイル日付等のログを残す
//---------------------------------------------------------------
function us_script_entry_log(){
	// ファイルの更新日時
	$updated = date( "Y/m/d H:i", getlastmod() );
	error_log("us_log:"."script_entry ".__FILE__." ".$updated);
}

//---------------------------------------------------------------
// エラーログの出力
// 
// 1. session の有効範囲の設定
// 2. time zone の設定（ないと latest な PHP でワーニングがでる）
//---------------------------------------------------------------
function us_error_log(){
}

//-----------------------------
//  show_html_head
//
//-----------------------------

function show_html_head($title) {
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>-->
	<link rel="stylesheet" href="../common/jquery.mobile-1.3.2.min.css" />
	<script src="../common/jquery-1.11.3.min.js"></script>
	<script src="../common/jquery.mobile-1.3.2.min.js"></script>
</head>

<?php  
//-----------------------------
//  function show_html_head end.
//-----------------------------
}

//-----------------------------
//  show_html_jquery_footer
//
//-----------------------------
function show_html_jquery_footer() {
?>

<div data-role="footer" data-position="fixed"  class="no-cache">
    <h4><?php echo COPYRIGHT ?></h4>
	<!-- <div data-role="controlgroup" data-type="horizontal">
	  <a rel="external" href="http://www.uedasoft.com" data-role="button" data-icon="home">UEDASOFT</a> -->
</div>

<?php  
//-----------------------------
//  function show_html_jquery_footer end.
//-----------------------------
}
?>