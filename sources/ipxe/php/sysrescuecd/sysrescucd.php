<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$se3ip = "\${net0/next-server}";

echo "kernel http://${se3ip}/sysresccd/isolinux/rescue32\n";
echo "initrd http://${se3ip}/sysresccd/isolinux/initram.igz\n";
echo "imgargs rescue32 setkmap=fr dodhcp scandelay=5 netboot=http://${se3ip}/sysresccd/sysrcd.dat autoruns=2 ar_source=http://${se3ip}/sysresccd/ ar_nowait\n";
echo "boot || goto failed\n";
echo "goto start\n";
?>
