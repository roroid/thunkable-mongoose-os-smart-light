
application/x-httpd-php firmware.php ( PHP script, ASCII text )
<?

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
   http_response_code(404);
   echo "no access here";
   die();
 }
if(isset($_POST['mod']) && !empty($_POST['mod']) && is_numeric($_POST['mod'])) {
	$mod=$_POST['mod'];
} else {
	// value is not integer or parameter 'catid' is not in url or is empty
}
if(isset($_POST["device_id"]) && !empty($_POST["device_id"])) {
	$device_id=strip_tags($_POST["device_id"]);
} else {
    echo "go away";    
die();
}
if(isset($_POST["token"]) && !empty($_POST["token"])) {
	$token=strip_tags($_POST["token"]);
} else {
        echo "go away";   
die();
}
if(isset($_POST["firm"]) && !empty($_POST["firm"])) {
	$firm=strip_tags($_POST["firm"]);
} else {
        echo "go away";   
die();
}
$userAgent = $_SERVER['HTTP_USER_AGENT'];
switch ($mod) {
    case 1:
        
        if (firmware_name()>$firm){
        $myObj = new \stdClass();
        $myObj->firmware = "1";
        $myObj->text = "\nVersion:".firmware_name()."\n Change log:"."\nWhen button is click you will get a multiple blink.\n";
        $myJSON = json_encode($myObj);
        echo $myJSON;}
        else{
        $myObj->firmware = "0";
        $myJSON = json_encode($myObj);
        echo $myJSON;}
        break;
    case 2:
        $target_url = 'https://mdash.net/api/v2/devices/'.$device_id.'/ota?access_token='.$token;
        $file_name_with_full_path = realpath(firmware_name().'.zip');
        $post['file'] = curl_file_create($file_name_with_full_path, 'application/zip', firmware_name().'.zip');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt( $ch, CURLOPT_USERAGENT, $userAgent );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result=curl_exec ($ch);
        http_response_code(200);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        echo $result;
        curl_close ($ch);
        break;
    default:
        echo "no access here";
        die();
        break;
}


function firmware_name() {
	
	// directory we want to scan
	$dircontents = scandir(".");
	
	// list the contents
	foreach ($dircontents as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'zip') {
			return basename($file, ".zip");
		}
	}
}

?>
