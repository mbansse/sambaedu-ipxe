<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
require_once('../params.php');

echo "kernel tftp://${se3ip}/clonezilla64/vmlinuz\n";
echo "initrd tftp://${se3ip}/clonezilla64/initrd.img\n";
echo "imgargs vmlinuz boot=live config noswap nolocales edd=on nomodeset ocs_live_run=\"\" ocs_live_extra_param=\"\" keyboard-layouts=\"fr\" ocs_live_batch=\"no\" locales=\"fr_FR.UTF-8\" vga=788 nosplash noprompt fetch=tftp://${se3ip}/clonezilla64/filesystem.squashfs\n";
echo "boot\n";
?>
