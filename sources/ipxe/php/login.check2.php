<?
header ("Content-type: text/plain");
echo "#!ipxe\n";
require_once('params.php');

$user = $_REQUEST["user"];
$pass = $_REQUEST["pass"];

if ($user === "${admin_pxe}" && $pass === "${admin_pwd}") {
          #echo "Correct user/pass";
           echo "chain boot-admin.php \n";
} else {
          #echo "Wrong user/pass back to login";
          echo "chain ../boot-base.php \n";
}
?>
