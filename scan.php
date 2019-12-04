<?php
function save($filename, $file) {
	$handle = fopen($filename, "a+");
	fwrite($handle, $file);
	fclose($handle);
	return;
}
function check(String $url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url . "/xmlrpc.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $post = file_get_contents("payload.txt");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return false;
    }
    curl_close ($ch);
    
    return (boolean) stripos($result, "faultCode");
}

foreach (@explode(PHP_EOL, file_get_contents("targets.txt")) as $key => $value) {
    echo (check($value) ? " [+] {$value} => VULN\n" . save("output.txt", $value.PHP_EOL) : " [-] {$value} AaHh BAD\n");
}
