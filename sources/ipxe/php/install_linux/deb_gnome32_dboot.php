<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
require_once('../params.php');
echo "kernel tftp://${se3ip}/debian-installer/i386/linux\n";
echo "initrd tftp://${se3ip}/debian-installer/i386/initrd.gz\n";
echo "imgargs linux auto=true netcfg/dhcp_timeout=60 netcfg/get_hostname=poste netcfg/get_domain=${se3domain} preseed/url=http://${se3ip}/install/preseed_debian_gnome_dboot.cfg\n";
echo "boot || goto failed\n";
echo "goto start\n";

?>

