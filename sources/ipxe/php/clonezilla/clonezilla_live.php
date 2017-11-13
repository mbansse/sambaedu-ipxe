<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$se3ip = "\${net0/next-server}";
echo "kernel tftp://${se3ip}/clonezilla/vmlinuz\n";
echo "initrd tftp://${se3ip}/clonezilla/initrd.img\n";
echo "imgargs vmlinuz boot=live config noswap nolocales edd=on nomodeset ocs_live_run=\"\" ocs_live_extra_param=\"\" keyboard-layouts=\"fr\" ocs_live_batch=\"no\" locales=\"fr_FR.UTF-8\" vga=788 nosplash noprompt fetch=tftp://${se3ip}/clonezilla/filesystem.squashfs\n";
echo "boot\n";
?>


