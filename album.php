<?php

// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');
enable_implicit_flush();
set_time_limit(0);
require_once("DropboxClient.php");
$dropbox = new DropboxClient(array(
	'app_key' => "7x7rbpqvqw0w0tq",      // Put your Dropbox API key here
	'app_secret' => "lvjqy591o1jzylg",   // Put your Dropbox API secret here
	'app_full_access' => false,
),'en');
$access_token = load_token("access");
if(!empty($access_token)) {
	$dropbox->SetAccessToken($access_token);
}
elseif(!empty($_GET['auth_callback'])) // are we coming from dropbox's auth page?
{
	// then load our previosly created request token
	$request_token = load_token($_GET['oauth_token']);
	if(empty($request_token)) die('Request token not found!');
	
	// get & store access token, the request token is not needed anymore
	$access_token = $dropbox->GetAccessToken($request_token);	
	store_token($access_token, "access");
	delete_token($_GET['oauth_token']);
}

// checks if access token is required
if(!$dropbox->IsAuthorized())
{
	// redirect user to dropbox auth page
	$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?auth_callback=1";
	$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
	$request_token = $dropbox->GetRequestToken();
	store_token($request_token, $request_token['t']);
	die("Authentication required. <a href='$auth_url'>Click here.</a>");
}
function store_token($token, $name)
{
	if(!file_put_contents("tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}
function load_token($name)
{
	if(!file_exists("tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("tokens/$name.token"));
}
function delete_token($name)
{
	@unlink("tokens/$name.token");
}
function enable_implicit_flush()
{
	@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 0);
	@ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	echo "<!-- ".str_repeat(' ', 2000)." -->";
}


if (isset($_GET['Delete'])){
$p=$_GET['Delete'];
$f = $dropbox->GetFiles("",false);
$path=del_file($p,$f);
$dropbox->Delete($path);
}
create_form();
if(isset($_FILES["photo"]["name"]))
{
	$a=check_file();
	if($a==1)
	{
		$f = $_FILES["photo"]["name"];
		$dropbox->UploadFile($_FILES["photo"]["tmp_name"], $f);
		echo "<br> done!";
	}
}	
create_tab();
$f = $dropbox->GetFiles("",false);
enter_tab($f);
if (isset($_GET['link'])){
$p=$_GET['link'];
$f = $dropbox->GetFiles("",false);
foreach ($f as $files)
{
	$n = basename($files->path);
	if($files->path==$p) {
	echo "<script type='text/javascript'>document.getElementById('Image').src = '".$dropbox->GetLink($files,false)."';</script>";
	$dropbox->DownloadFile($files, $n);
	}
}
}
function create_tab(){
echo "<form name='myform' action='album.php' method='GET'>";
echo "<br/>Files:<br/>";
echo "<table border='1' bgcolor=#DCDCDC> <tr>  <th>image link</th>  <th>delete</th>  </tr>";
}
function enter_tab($f){
foreach ($f as $files) {
	echo "<tr><td><a href='album.php?link=$files->path'>$files->path</td><td><button value='$files->path' name='Delete' type='submit'>Del</button></td></tr>";
}
echo "</form>  </table>";
echo "<br/><div id='imagedisp' style='width:400px;height:400px'><img id='Image' style='width:399px;height:399px;' /></div>";
}
function check_file(){
	$ext = explode('.',$_FILES['photo']['name']);
	$file_ext = end($ext);
    $ex= array("jpeg","jpg");
    if(in_array($file_ext,$ex)=== false){
         echo"<br>please choose a JPEG file.";
      }
	else{
		return 1;
    }
}
function del_file($p,$f){
	foreach ($f as $files)
	{
		if($files->path==$p) {
		return $files->path;
		}
	}
}
function create_form(){
echo"<form action='album.php' method='post' enctype='multipart/form-data'>";
echo"Get image to upload:<br><input type='file' name='photo' id='photo'/><br>";
echo"<input type='submit' name='submit' value='Submit' />";
echo"</form>";
}
?>
