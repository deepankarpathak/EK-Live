<?php
/*
  Plugin Name: EK DB Migration 
  Plugin URI:  http://aurigait.com
  Description: DB Migration
  Version: 0.1
  Author: AurigaIT
  Author URI: someshsoni.in
  Copyright: Aurigait.com
 */
add_action('admin_enqueue_scripts', 'admin_hscripts');
add_action('admin_post_db_mi_save','admin_post_db_mi_save');
add_action('admin_post_migration_process','migration_process');
add_action('admin_menu', 'add_mi_menu');

function add_mi_menu(){
	add_menu_page('DB Migration', 'Migration Settings', 'manage_options', 'ek-mi-settings', 'admin_view');
	 
}
function admin_hscripts($hook)
{
	?>
<style tytext/csspe="text/css">
.loading{
	top:0;
	left: 0;
	position: fixed;
	bottom: 0;
	right: 0;
	/*display: block;*/
	background: #CCC;
	/* IE */
	-moz-opacity: 0.6;
	/* Mozilla */
	opacity: 0.6;
	z-index: 999999
}
.loading-icon{
	height: 100px;
	position: absolute;
	top:50%;
	margin-top:-50px; 
	left: 50%;
	margin-left: -50px;
}
.migrate-db{
	  cursor: pointer;
}
</style>
<script>
window.onload =function(){

	jQuery('#loading').css('display','none');
	document.getElementById('wp-admin-bar-migrate_db').onclick=function(){
	var r = confirm("Do you really want to migrate alpha DB with Live DB?");
	if (r == true) {
		jQuery('#loading').css('display','block');
		window.location.href = '<?php echo get_site_url(); ?>/wp-admin/admin-post.php?action=migration_process';
	}else{
	    alert('You clicked to cancel Migration!!!');
	    return false;
	}
};};
</script>
<?php 

}
add_action( 'admin_bar_menu', 'toolbar_link_to_mypage', 999 );
function toolbar_link_to_mypage( $wp_admin_bar ) {
	$url = get_site_url().'/wp-admin/admin-post.php?action=migration_process';
	$args = array(
			'id'    => 'migrate_db',
			'title' => 'Migrate DB',
			'href'  => $url,
			'meta'  => array( 'class' => 'migrate-db')
	);
	$wp_admin_bar->add_node( $args );
}


function migration_process()
{
	//echo "start";die;
	//require('../../wp-blog-header.php');
	
	if( is_user_logged_in() &&  current_user_can( 'administrator')) {
	
		//exec('rsync -avW -e --delete-before /var/www/html/d1/ /var/www/html/d2');
		//exec('rsync -avW -e --delete-before edukart@139.162.28.92:dev/wp-content/uploads edukart@139.162.28.92:alpha/wp-content/uploads');
	
// 		$livehost = '139.162.28.92';
// 		$livedb = 'edukart_devalpha';
// 		$liveuser = 'edukart_devuser';
// 		$livepwd= 'ekdevalpha@dm!n#';
	
// 		$new_live_db = 'edukart_new';
	
// 		$alphahost = '139.162.28.92';
// 		$alphadb = 'edukart_dev';
// 		$alphauser = 'edukart_devuser';
// 		$alphapwd= 'cjdqavrq';
	
// 		$post_start_date = '2015-08-15';

		$a=get_option("ek_db_mi_info");
		$values=unserialize($a);
		

		if (!empty($values['livedbhost']) && !empty($values['livedbname']) && !empty($values['livedbuser']) && !empty($values['livedbpass']) && !empty($values['livenewdbname']) && !empty($values['alphadbhost']) && !empty($values['alphadbname']) && !empty($values['alphadbuser'])&& !empty($values['alphadbpass'])&& !empty($values['orderfromdate']) && !empty($values['alphamediapath']) ){


			$upload=wp_upload_dir();
			$upload=$upload["path"]."/migration/";
			
			// echo $a["path"]."/log.txt";die;
			//file_put_contents($a["path"]."/log.txt", print_r($plugin,true), FILE_APPEND | LOCK_EX);
			
			$livehost = $values['livedbhost'];

			$livehostuser = $values['livehostuser'];
			$livehostpwd= $values['livehostpwd'];

			$livedb = $values['livedbname'];
			$liveuser = $values['livedbuser'];
			$livepwd= $values['livedbpass'];
			
			$new_live_db = $values['livenewdbname'];
			
			$alphahost = $values['alphadbhost'];

			$alphahostuser = $values['alphahostuser'];
			$alphahostpwd= $values['alphahostpwd'];
			$alphadb = $values['alphadbname'];
			$alphauser = $values['alphadbuser'];
			$alphapwd= $values['alphadbpass'];
			$alphamediapath= $values['alphamediapath'];
			
			$post_start_date = $values['orderfromdate'];
			//$post_end_date = '2015-08-20';
		
			file_put_contents($upload.'migration_log.txt',"\n\nDB Migration Started".date('yyyy-m-d').":\n", FILE_APPEND);

			if($livehost == $alphahost){
				try {
					exec('rsync -avW -e --delete-before '.$alphamediapath.' '.$upload["path"]);
				} catch (Exception $e) {
					echo 'Caught exception in Uploads rsync completed.: ',  $e->getMessage(), "\n";
					die("Exception");
				}
			}else{
				try {
					exec('sshpass -p '.$alphahostpwd.' rsync --progress -avz -e ssh '.$alphahostuser.'@'.$alphahost.':'.$alphamediapath.' '.$upload["path"]);
				} catch (Exception $e) {
					echo 'Caught exception in Uploads rsync completed.: ',  $e->getMessage(), "\n";
					die("Exception");
				}
			}
			file_put_contents($upload.'migration_log.txt',"Rsync Command get executed.\n", FILE_APPEND);

		
			file_put_contents($upload.'migration_log.txt',"Step1:Live DB backup started.\n", FILE_APPEND);
			//Step1
			try {
				//exec("mysqldbcopy --source=".$liveuser.":".$livepwd."@".$livehost." --destination=".$liveuser.":".$livepwd."@".$livehost." ".$livedb.":".$livedb."_backup_".date('Ymd'));
				// $fname_bzip2 = time().'_'.'edukart_live_'.date('d-M-Y').'.sql.bz2';
				// exec("mysqldump -h ". $livehost ." -u ". $liveuser ." -p'". $livepwd ."' ". $livedb . " | bzip2 > ". $upload.$fname_bzip2);

				$filename = time().'_'.'edukart_live_'.date('d-M-Y').'.sql.gz';
			    exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb."|gzip > ".$upload.$filename);

			} catch (Exception $e) {
				echo 'Caught exception in Step1(Live DB backup): ',  $e->getMessage(), "\n";
				die("Exception");
			}
		
		
			file_put_contents($upload.'migration_log.txt',"Step1:Live DB backup Ended.\n", FILE_APPEND);
			file_put_contents($upload.'migration_log.txt',"Step2:Alpha's clone created.\n", FILE_APPEND);

			//Step2
			try{
				exec("mysqldbcopy --source=".$alphauser.":".$alphapwd."@".$alphahost." --destination=".$liveuser.":".$livepwd."@".$livehost." ".$alphadb.":".$new_live_db);
			} catch (Exception $e) {
				echo 'Caught exception in Step2(Alpha db clone): ',  $e->getMessage(), "\n";
				die("Exception");
			}

			file_put_contents($upload.'migration_log.txt',"Step2:Alpha's clone completed.\n", FILE_APPEND);
			file_put_contents($upload.'migration_log.txt',"Step3:Orders.xml exporting.\n", FILE_APPEND);

			//Step3
			try{
				header("Location: ".get_site_url()."/wp-admin/export.php?download=true&post_author=0&post_start_date=".$post_start_date."&post_end_date=0&post_status=0&page_author=0&page_start_date=0&page_end_date=0&page_status=0&content=shop_order&submit=Download+Export+File");
			} catch (Exception $e) {
				echo 'Caught exception in Step3(Orders.xml exporting): ',  $e->getMessage(), "\n";
				die("Exception");
			}

			file_put_contents($upload.'migration_log.txt',"Step3:Orders.xml exported.\n", FILE_APPEND);
			file_put_contents($upload.'migration_log.txt',"Step4:exporting table users,user_meta,options.\n", FILE_APPEND);

			//Step4
			try{
				exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_users > ".$upload."edkwp_users".date('Ymd').".sql");
				exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_usermeta > ".$upload."edkwp_usermeta".date('Ymd').".sql");
				exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_options > ".$upload."edkwp_options".date('Ymd').".sql");
			} catch (Exception $e) {
				echo 'Caught exception in Step4(exporting table users,user_meta,options): ',  $e->getMessage(), "\n";
				die("Exception");
			}
			file_put_contents($upload.'migration_log.txt',"Step4:exported table users,user_meta,options.\n", FILE_APPEND);
			file_put_contents($upload.'migration_log.txt',"Step5:Importing table users,user_meta,options.\n", FILE_APPEND);

			//Step5
			try{
				exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < ".$upload."edkwp_users".date('Ymd').".sql");
				exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < ".$upload."edkwp_usermeta".date('Ymd').".sql");
				exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < ".$upload."edkwp_options".date('Ymd').".sql");
			} catch (Exception $e) {
				echo 'Caught exception in Step5(Importing table users,user_meta,options): ',  $e->getMessage(), "\n";
				die("Exception");
			}
		
			file_put_contents($upload.'migration_log.txt',"Step5:Imported table users,user_meta,options.\n", FILE_APPEND);
			file_put_contents($upload.'migration_log.txt',"Step6&7:updateing all links using wordpress migration queries.\n", FILE_APPEND);

			//Step6&7
			try{
				$conn = new mysqli($livehost, $liveuser, $livepwd, $new_live_db);
				if ($conn->connect_error) { // Check connection
					die("Connection failed: " . $conn->connect_error);
				}
		
				$sql = "set @oldurl = 'http://alpha.edukart.com', @newurl = 'http://edukart.com';
	UPDATE edkwp_options SET option_value = replace(option_value, @oldurl, @newurl) ;
	UPDATE edkwp_posts SET guid = REPLACE (guid, @oldurl, @newurl);
	UPDATE edkwp_posts SET post_content = REPLACE (post_content, @oldurl, @newurl);
	UPDATE edkwp_posts SET post_content = REPLACE (post_content, CONCAT('src=\"', @oldurl), CONCAT('src=\"', @newurl));
	UPDATE edkwp_posts SET guid = REPLACE (guid, @oldurl, @newurl) WHERE post_type = 'attachment';
	UPDATE edkwp_postmeta SET meta_value = REPLACE (meta_value, @oldurl, @newurl);";
		
				$result = $conn->query($sql);
			} catch (Exception $e) {
				echo 'Caught exception in (Step6&7)updateing all links using wordpress migration queries: ',  $e->getMessage(), "\n";
				die("Exception");
			}

			file_put_contents($upload.'migration_log.txt',"Step8:Updateing wp-config.php DB name.\n", FILE_APPEND);

			//Step8
			try{
				$str=file_get_contents(ABSPATH.'wp-config.php');
				$str=str_replace($livedb, $new_live_db,$str);
				file_put_contents(ABSPATH.'wp-config.php', $str);
			} catch (Exception $e) {
				echo 'Caught exception in Step8(Updateing wp-config.php DB name): ',  $e->getMessage(), "\n";
				die("Exception");
			}
			//Step9:
			//.xml file should be imported manually
		
			//redirect on home page
			wp_redirect('admin.php?page=ek-mi-settings');
		
		}else{
			//You need to log-in as admin to execute that Migration script!!!
			echo "error";
		}
	}
	else 
	{
		echo "Configuration Error";
		
	}
}

function admin_view()
{
	$a=get_option("ek_db_mi_info");
	$values=unserialize($a);
	
	
	?>
	<h1>DB MIGRATION SETTINGS</h1>
	<div style="margin:50px;">
		<form action="<?php echo site_url()?>/wp-admin/admin-post.php" method="post">
		
		<table>
			<tr>
				<td>Live DB Host : </td>
				<td><input type="text" name="livedbhost" value="<?php echo @$values["livedbhost"] ? $values["livedbhost"] : "";?>"/></td>
			</tr> 
			
			<tr>

				<td>Live Host User : </td>
				<td><input type="text" name="livehostuser" value="<?php echo @$values["livehostuser"] ? $values["livehostuser"] : "";?>"/></td>
			</tr> 

			<tr>
				<td>Live Host Password : </td>
				<td><input type="text" name="livehostpwd" value="<?php echo @$values["livehostpwd"] ? $values["livehostpwd"] : "";?>"/></td>
			</tr>

			<tr>

				<td>Live DB Name : </td>
				<td><input type="text" name="livedbname" value="<?php echo @$values["livedbname"] ? $values["livedbname"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Live DB User : </td>
				<td><input type="text" name="livedbuser" value="<?php echo @$values["livedbuser"] ? $values["livedbuser"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Live DB User Password : </td>
				<td><input type="password" name="livedbpass" value="<?php echo @$values["livedbpass"] ? $values["livedbpass"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Live New DB Name : </td>
				<td><input type="text" name="livenewdbname" value="<?php echo @$values["livenewdbname"] ? $values["livenewdbname"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Alpha DB Host : </td>
				<td><input type="text" name="alphadbhost" value="<?php echo @$values["alphadbhost"] ? $values["alphadbhost"] : "";?>"/></td>
			</tr> 
			
			<tr>

				<td>Alpha Host User : </td>
				<td><input type="text" name="alphahostuser" value="<?php echo @$values["alphahostuser"] ? $values["alphahostuser"] : "";?>"/></td>
			</tr> 

			<tr>
				<td>Alpha Host Password : </td>
				<td><input type="text" name="alphahostpwd" value="<?php echo @$values["alphahostpwd"] ? $values["alphahostpwd"] : "";?>"/></td>
			</tr>

			<tr>

				<td>Alpha DB Name : </td>
				<td><input type="text" name="alphadbname" value="<?php echo @$values["alphadbname"] ? $values["alphadbname"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Alpha DB User : </td>
				<td><input type="text" name="alphadbuser" value="<?php echo @$values["alphadbuser"] ? $values["alphadbuser"] : "";?>"/></td>
			</tr> 
			
			<tr>
				<td>Alpha DB User Password : </td>
				<td><input type="password" name="alphadbpass" value="<?php echo @$values["alphadbpass"] ? $values["alphadbpass"] : "";?>"/></td>
			</tr> 	

			<tr>
				<td>Alpha Media Directory ABSPATH </td>
				<td><input type="text" name="alphamediapath" value="<?php echo @$values["alphamediapath"] ? $values["alphamediapath"] : "";?>"/></td>
			</tr> 

			<tr>
				<td>Order From Date </td>
				<td><input type="text" name="orderfromdate" value="<?php echo @$values["orderfromdate"] ? $values["orderfromdate"] : "";?>" placeholder="yyyy-mm-dd"/></td>
			</tr> 
			

			<tr><td>
			<input type="submit"/>
		   	<input type="hidden" name="action" value="db_mi_save" />
   			</td></tr>
   		</table>
   		</form>
   	</div>
	<?php 		
}
function admin_post_db_mi_save (){


	if (!empty($_POST['livedbhost']) && !empty($_POST['livedbname']) && !empty($_POST['livedbuser']) && !empty($_POST['livedbpass']) && !empty($_POST['livenewdbname']) && !empty($_POST['alphadbhost']) && !empty($_POST['alphadbname']) && !empty($_POST['alphadbuser'])&& !empty($_POST['alphadbpass'])&& !empty($_POST['orderfromdate']) && !empty($_POST['alphamediapath'])){

		$serialize_arr=serialize($_POST);
		update_option("ek_db_mi_info",$serialize_arr);
		wp_redirect('admin.php?page=ek-mi-settings');
	}
	else
	{
		echo "Invalid Input";
	}
	
}
