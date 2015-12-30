<?php
require_once("common/common.php");
define("TITLE","Backup-Pi");

// timezone が Asia/Tokyo でなければ英語
$timezone = substr(`cat /etc/timezone`,0,-1);
if ($timezone == 'Asia/Tokyo') {
  header("Location: " . "index.php");
}
# バックアップ/リストアの処理実体
$command = "";
$filename = "";
$sh_script = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $command = $_POST['command'];
  $filename = $_POST['filename'];
  $path_parts = pathinfo($filename);

  if ($command == "backup"){
    switch ($path_parts['extension']){
#   case "zip":
#     $sh_script = "sudo /bin/dd if=/dev/sda bs=1M | /usr/bin/zip /boot/Data/".$filename." - &";
#     $sh_script = "sudo /usr/share/nginx/www/zip.sh ".$filename." > /dev/null 2>&1 &";
#     break;
    case "gz":
      $sh_script = "sudo /bin/dd if=/dev/sda bs=1M 2>".DD_BACKUP_LOG." | /bin/gzip --fast > /boot/DATA/".$filename." 2>/dev/null &";
#      $sh_script = "sudo /bin/dd if=/dev/sda bs=1M 2>/boot/log/dd.backup.log | /bin/gzip --fast > /boot/DATA/".$filename." 2>/dev/null &";
      break;
      $sh_script = "sudo /bin/dd if=/dev/sda of=/boot/DATA/".$filename." bs=1M > ".DD_BACKUP_LOG." 2>&1 &";
#      $sh_script = "sudo /bin/dd if=/dev/sda of=/boot/DATA/".$filename." bs=1M > /boot/log/dd.backup.log 2>&1 &";
      break;
    default:
      $sh_script = "sudo /bin/dd if=/dev/sda of=/boot/DATA/".$filename.".img bs=1M > ".DD_BACKUP_LOG." 2>&1 &";
#      $sh_script = "sudo /bin/dd if=/dev/sda of=/boot/DATA/".$filename.".img bs=1M > /boot/log/dd.backup.log 2>&1 &";
      break;
    }
  } elseif ($command == "restore"){
    switch ($path_parts['extension']){
   case "zip":
     $sh_script = "/usr/bin/funzip /boot/DATA/".$filename." | sudo /bin/dd of=/dev/sda bs=1M 1>&2 2>".DD_RESTORE_LOG." &";
#     $sh_script = "/usr/bin/funzip /boot/DATA/".$filename." | sudo /bin/dd of=/dev/sda bs=1M 1>&2 2>/boot/log/dd.restore.log &";
     break;
    case "gz":
      $sh_script = "/bin/gzip -dc /boot/DATA/".$filename." | sudo /bin/dd of=/dev/sda bs=1M 1>&2 2>".DD_RESTORE_LOG." &";
#      $sh_script = "/bin/gzip -dc /boot/DATA/".$filename." | sudo /bin/dd of=/dev/sda bs=1M 1>&2 2>/boot/log/dd.restore.log &";
      break;
    case "img":
#     $sh_script = "sudo dd if=/boot/DATA/".$filename." of=/dev/sda bs=1M > /dev/null 2>&1 &";
      $sh_script = "sudo dd if=/boot/DATA/".$filename." of=/dev/sda bs=1M > ".DD_RESTORE_LOG." 2>&1 &";
#      $sh_script = "sudo dd if=/boot/DATA/".$filename." of=/dev/sda bs=1M > /boot/log/dd.restore.log 2>&1 &";
      break;
    default:
      $sh_script = "sudo dd if=/boot/DATA/".$filename." of=/dev/sda bs=1M > ".DD_RESTORE_LOG." 2>&1 &";
#      $sh_script = "sudo dd if=/boot/DATA/".$filename." of=/dev/sda bs=1M > /boot/log/dd.restore.log 2>&1 &";
      break;
    }
  }
  if ($sh_script != ""){
    error_log('['.basename(__FILE__).':'.__LINE__.']'.' $sh_script = '.$sh_script);    
    #$output=shell_exec($sh_script);
    $output=exec($sh_script,$output2,$retval);
    error_log('['.basename(__FILE__).':'.__LINE__.']'.' $output = '.$output);    
    error_log('['.basename(__FILE__).':'.__LINE__.']'.' $sh_script = '.$sh_script);    
    error_log('['.basename(__FILE__).':'.__LINE__.']'.' $output2 = '.$output2);    
    error_log('['.basename(__FILE__).':'.__LINE__.']'.' $retval = '.$retval);    
  }

  // リロード時の二重送信を防ぐために、自分自身に一度 GET を発行する（と、リロードされても POST がでない）
  error_log('['.basename(__FILE__).':'.__LINE__.']'.' *** RELOAD ***');    
  header("Location: " . $_SERVER['SCRIPT_NAME']);
}

#バックアップファイル名（仮）の作成
$now = date("Y-m-d-Hi");
$bf_name = $now."-RPi_Backup.gz";

# ヘッダの表示
error_log('['.basename(__FILE__).':'.__LINE__.']'.' *** RENDERING START ***');    
show_html_head(TITLE);
?>
<body>

<script>
  /*
  stabation で ESS が帰ってこないので Ajax にする

  var es1 = new EventSource("number_of_resultmoviefile_sse.php");
  es1.addEventListener("message", function(e){
    if (e.data == "1"){
      document.getElementById("tlf").innerHTML = '完了。<a href="./your_output.mp4">TimeLapse 動画ファイル</a>';
    }
  },false);
  */

  prev_size_of_backuplog = 0;
  prev_size_of_restorelog = 0;

  // dd コマンドの中断
  function break_dd(){
    $.ajax({
      type: "POST",
      url: "break.php",
      dataType: "json",
      success: function(data, dataType) 
      {
        if(data == null) alert('0 data returned.');
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) 
      {
      }
    });
  };

  $(document).ready(function() 
  {
    // 正規表現で数値を３桁ずつセパレート
    function separate(num){
      return String(num).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
    };

    function start_backuprestore(){
      // バックアップ中、もしくはリストア中なので、他の操作を抑止に
      console.log("start_backuprestore start");
      document.getElementById('command_backup').disabled = true;
      document.getElementById('command_restore').disabled = true;
      document.getElementById('backup_to_filename').disabled = true;
      document.getElementById('restore_from_file').disabled = true;
      document.getElementById('sub_b_btn').disabled = true;
      document.getElementById('sub_r_btn').disabled = true;
      document.getElementById('sub_r_btn').disabled = true;
      document.getElementById('backup_restore').setAttribute("style", 'color: #aaaaaa;');
      console.log("start_backuprestore end");
    };
    
    function end_backupresotre(){
      // 終わったので表示を戻し、log ファイルを削除
      document.getElementById('command_backup').disabled = false;
      document.getElementById('command_restore').disabled = false;
      document.getElementById('backup_to_filename').disabled = false;
      document.getElementById('restore_from_file').disabled = false;
      document.getElementById('sub_b_btn').disabled = false;
      document.getElementById('sub_r_btn').disabled = false;
      document.getElementById('backup_restore').removeAttribute("style", 'color: #ffffff;');
      $.ajax({
        type: "POST",
        url: "removelog.php"
      });
    }

    //timer1 = setInterval(function(){
    /**
     * Ajax通信メソッド
     * @param type    : HTTP通信の種類
     * @param url     : リクエスト送信先のURL
     * @param dataType  : データの種類
     */
//    prev_size_of_resultfile = 0;
    function call_ajax(){
    $.ajax({
      type: "POST",
      url: "exist.php",
      dataType: "json",
      /**
       * Ajax通信が成功した場合に呼び出されるメソッド
       */
      success: function(data, dataType) 
      {
        console.log("success start");
        //結果が0件の場合
        if(data == null) alert('0 data returned.');

        console.log("data.backup_running = "+data.backup_running);
        console.log("data.restore_running = "+data.restore_running);
        if (data.backup_running == "yes\n"){
          console.log("data.backup_running");
          console.log("prev_size_of_backupog = "+prev_size_of_backuplog);
          console.log("data.backup_size = "+data.backup_size);
          start_backuprestore();
          document.getElementById("status").innerHTML = 'Backup running ... ' + separate(data.backup_size) + 'Byte '
            + '<a href="javascript:break_dd();" >break</a>';// + "<br> p=" + prev_size_of_resultfile + "<br> c=" + size_of_resultfile;
          if (data.dd_process_exist < 3 && prev_size_of_backuplog == data.backup_size && data.backup_size != 0){
            document.getElementById("status").innerHTML = 'Backup completed.';//+ "<br> p=" + prev_size_of_resultfile + "<br> c=" + size_of_resultfile;
            end_backupresotre();
            //clearInterval(timer1);
          } else {
            prev_size_of_backuplog = data.backup_size
            call_ajax();
          }
        }
        if (data.restore_running == "yes\n"){
          console.log("data.restore_running");
          console.log("prev_size_of_restorelog = "+prev_size_of_restorelog);
          console.log("data.restore_size = "+data.restore_size);
          start_backuprestore();
          document.getElementById("status").innerHTML = 'Restor running ... ' + separate(data.restore_size) + 'Byte '
            + '<a href="javascript:break_dd();" >break</a>';// + "<br> p=" + prev_size_of_resultfile + "<br> c=" + size_of_resultfile;
          if (data.dd_process_exist < 3 && prev_size_of_restorelog == data.restore_size && data.restore_size != 0){
            document.getElementById("status").innerHTML = 'Restore completed.';//+ "<br> p=" + prev_size_of_resultfile + "<br> c=" + size_of_resultfile;
            end_backupresotre();
            //clearInterval(timer1);
          } else {
            prev_size_of_restorelog = data.restore_size
            call_ajax();
          }
        }
      },
      /**
       * Ajax通信が失敗場合に呼び出されるメソッド
       */
      error: function(XMLHttpRequest, textStatus, errorThrown) 
      {
        //通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、単純に通信に失敗した際の処理を記述します。

        //this;
        //thisは他のコールバック関数同様にAJAX通信時のオプションを示します。

        //エラーメッセージの表示
        //alert('Error : ' + textStatus + errorThrown);
      }
    });
  };
  call_ajax();
//  },5000);
  });
</script>

<div data-role="page" id="new"> 
	  
<div data-role="header" data-position="fixed">
    <h1><?php echo TITLE; ?></h1>
    <!-- <a href="index.php" data-icon="forward" data-transition="fade" data-ajax="false">時刻合わせ</a> -->
</div>

<div data-role="content" data-theme="c" class="no-cache">
	<!-- <p><?#php echo $command ?></p>
	<p><?#php echo $filename ?></p>
	<p><?#php echo $path_parts['extension'] ?></p>
  <p><?#php echo $sh_script ?></p>
  <p><?#php echo $output ?></p>
  <p><?#php var_dump($output2) ?></p>
  <p><?#php echo $output2[0] ?></p>
  <p><?#php echo $retval ?></p>
  <p><?#php echo $timezone ?></p> -->

  <div id="status"></div>

  <div id="backup_restore">
	<h2><p>Backup</p></h2>
		<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" data-ajax="false">
			<input type="hidden" name="command" id="command_backup" value="backup" />
			<p>Put a filename to cleate. (.img or .gz)：<input type="text" name="filename" id="backup_to_filename" value="<?php echo $bf_name;?>" /></p>
			<input type="submit" id="sub_b_btn" value="START BACKUP" />
		</form>

	<h2><p>Restore</p></h2>
		<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" data-ajax="false">
			<input type="hidden" name="command" id="command_restore" value="restore" />
			<p>Select a file to restore:<select name="filename" id="restore_from_file">				
			<?php
				foreach(glob('/boot/DATA/{*.img,*.zip,*.gz}',GLOB_BRACE) as $file){
					if(is_file($file)){
						$filename = basename($file);
						echo '<option value"'.$filename.'">';
						echo htmlspecialchars($filename);
						echo '</option>';
					}
				}
			?>
			</select></p>
			<input type="submit" id="sub_r_btn" value="START RESTORE" />
  </div><!-- <div id="backup_restore"> -->
</div><!-- <div data-role="content" data-theme="c" class="no-cache">-->

<?php show_html_jquery_footer(); ?>
</div> <!-- page -->


</body>
</html>
