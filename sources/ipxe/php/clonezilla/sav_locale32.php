<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
require_once('../params.php');

echo "kernel tftp://${se3ip}/clonezilla/vmlinuz\n";
echo "initrd tftp://${se3ip}/clonezilla/initrd.img\n";
echo "imgargs vmlinuz boot=live config noswap nolocales edd=on nomodeset ocs_prerun=\"mount -t auto /dev/sda2 /home/partimag/\"  ocs_live_run=\"ocs-sr -q2  -j2 -z1 -i 2000 -fsck-src-part -p reboot saveparts savesda1 sda1\" ocs_live_extra_param=\"\" keyboard-layouts=\"fr\" ocs_live_batch=\"no\" locales=\"fr_FR.UTF-8\" vga=788 nosplash noprompt fetch=tftp://${se3ip}/clonezilla/filesystem.squashfs\n";
echo "boot\n";
?>
