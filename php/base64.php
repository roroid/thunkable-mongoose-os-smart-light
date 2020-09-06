<?php
$str = $_GET['string'];
$myObj->response = base64_encode($str);
$myJSON = json_encode($myObj);
echo $myJSON;
?>
