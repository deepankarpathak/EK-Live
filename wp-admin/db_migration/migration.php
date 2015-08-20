<?php
require('../../wp-blog-header.php');

if( is_user_logged_in() &&  current_user_can( 'administrator')) {

//exec('rsync -avW -e --delete-before /var/www/html/d1/ /var/www/html/d2');
//exec('rsync -avW -e --delete-before edukart@139.162.28.92:dev/wp-content/uploads edukart@139.162.28.92:alpha/wp-content/uploads');

$livehost = '139.162.28.92';
$livedb = 'edukart_devalpha';
$liveuser = 'edukart_devuser';
$livepwd= 'ekdevalpha@dm!n#';

$new_live_db = 'edukart_new';

$alphahost = '139.162.28.92';
$alphadb = 'edukart_dev';
$alphauser = 'edukart_devuser';
$alphapwd= 'cjdqavrq';

$post_start_date = '2015-08-15';
$post_end_date = '2015-08-20';

file_put_contents('migration_log.txt',"\n\nDB Migration Started".date('yyyy-m-d').":\n", FILE_APPEND | LOCK_EX);

file_put_contents('migration_log.txt',"Step1:Live DB backup started.\n", FILE_APPEND | LOCK_EX);	  
//Step1
try {
exec("mysqldbcopy --source=".$liveuser.":".$livepwd."@".$livehost." --destination=".$liveuser.":".$livepwd."@".$livehost." ".$livedb.":".$livedb."_backup_".date('Ymd'));
} catch (Exception $e) {
    echo 'Caught exception in Step1(Live DB backup): ',  $e->getMessage(), "\n";
}


file_put_contents('migration_log.txt',"Step1:Live DB backup Ended.\n", FILE_APPEND | LOCK_EX);
file_put_contents('migration_log.txt',"Step2:Alpha's clone created.\n", FILE_APPEND | LOCK_EX);
//Step2
try{
exec("mysqldbcopy --source=".$alphauser.":".$alphapwd."@".$alphahost." --destination=".$liveuser.":".$livepwd."@".$livehost." ".$alphadb.":".$new_live_db);
} catch (Exception $e) {
    echo 'Caught exception in Step2(Alpha db clone): ',  $e->getMessage(), "\n";
}
file_put_contents('migration_log.txt',"Step2:Alpha's clone completed.\n", FILE_APPEND | LOCK_EX);
file_put_contents('migration_log.txt',"Step3:Orders.xml exporting.\n", FILE_APPEND | LOCK_EX);
//Step3
try{
header("Location: ".get_site_url()."/wp-admin/export.php?download=true&post_author=0&post_start_date=".$post_start_date."&post_end_date=0&post_status=0&page_author=0&page_start_date=0&page_end_date=0&page_status=0&content=shop_order&submit=Download+Export+File");
} catch (Exception $e) {
    echo 'Caught exception in Step3(Orders.xml exporting): ',  $e->getMessage(), "\n";
}
file_put_contents('migration_log.txt',"Step3:Orders.xml exported.\n", FILE_APPEND | LOCK_EX);
file_put_contents('migration_log.txt',"Step4:exporting table users,user_meta,options.\n", FILE_APPEND | LOCK_EX);
//Step4
try{
exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_users > edkwp_users".date('Ymd').".sql");
exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_usermeta > edkwp_usermeta".date('Ymd').".sql");
exec("mysqldump --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$livedb." edkwp_options > edkwp_options".date('Ymd').".sql");
} catch (Exception $e) {
    echo 'Caught exception in Step4(exporting table users,user_meta,options): ',  $e->getMessage(), "\n";
}
file_put_contents('migration_log.txt',"Step5:Importing table users,user_meta,options.\n", FILE_APPEND | LOCK_EX);
//Step5
try{
exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < edkwp_users".date('Ymd').".sql");
exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < edkwp_usermeta".date('Ymd').".sql");
exec("mysql --user=".$liveuser." --password=".$livepwd." --host=".$livehost.' '.$new_live_db." < edkwp_options".date('Ymd').".sql");
} catch (Exception $e) {
    echo 'Caught exception in Step5(Importing table users,user_meta,options): ',  $e->getMessage(), "\n";
}

file_put_contents('migration_log.txt',"Step6&7:updateing all links using wordpress migration queries.\n", FILE_APPEND | LOCK_EX);
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
}
file_put_contents('migration_log.txt',"Step8:Updateing wp-config.php DB name.\n", FILE_APPEND | LOCK_EX);
//Step8
try{
$str=file_get_contents('../wp-config.php');
$str=str_replace($livedb, $new_live_db,$str);
file_put_contents('../wp-config.php', $str);
} catch (Exception $e) {
    echo 'Caught exception in Step8(Updateing wp-config.php DB name): ',  $e->getMessage(), "\n";
}
//Step9:
//.xml file should be imported manually

//redirect on home page
header("Location: ".get_site_url()."/wp-admin");

}else{
	//You need to log-in as admin to execute that Migration script!!!
	echo "error";
}


?>	