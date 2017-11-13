<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$se3ip = "\${net0/next-server}";

echo "kernel tftp://172.20.0.12/ltsp/amd64/vmlinuz init=/sbin/init-ltsp  root=/dev/nfs real_root=/dev/nfs  ip=dhcp boot=nfs nfsroot=172.20.0.12:/opt/ltsp/amd64\n";
echo "initrd tftp://172.20.0.12/ltsp/amd64/initrd.img\n";
echo "boot || goto failed\n";
echo "goto start\n";
?>

