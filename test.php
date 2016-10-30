<?
$data='{"INFO":0}'.chr(0);
$fp=pfsockopen("192.168.3.49",4242, $errstr, $errno, 5); 
      fputs($fp,$data);
      $result=fgets($fp, 64000); 
fclose($fp);
print $result;
$json = str_replace("\u0022","\\\\\"",json_decode( $result,JSON_HEX_QUOT)); 
var_dump($json);
?>
