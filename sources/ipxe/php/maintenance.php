<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$menu_timeout = '10000';
require_once('params.php');

//$se3ip = "\${net0/next-server}";
//$ip = "\${net0/ip}";

function title($name) {
    # the max number of characters for resolution 1024 x 768 is 107
    $total_length = 107;
    $name_length = strlen($name);
    $start = intval(($total_length - $name_length) / 2);
    $end = $total_length - $start - $name_length;
    $title = str_repeat("-", $start) . $name . str_repeat("-", $end);
    echo "item --gap -- {$title}\n";
}

echo "console --x 1024 --y 768 --picture ../png/sysrescuecd.png\n";
echo ":menu\n";
echo "menu maintenance pour ${ip}\n";
echo "set menu-default exit\n";
echo "set menu-timeout $menu_timeout\n";

title("Sysrescuecd");
echo "item  rescue32 Utilisation de sysrescuecd 32 bits \n";
echo "item  rescue64 Utilisation de sysrescuecd 64 bits \n";
echo "item  altker32 Utilisation de sysrescuecd 32 bits  (noyau alternatif) \n";
echo "item  altker64 Utilisation de sysrescuecd 64 bits  (noyau alternatif) \n";
title("Autres options");
echo "item --key s shell  (6) iPXE shell\n";
echo "item --key r retour (r) Retour au menu precedent\n";
echo "item --key x exit (x) Boot sur disque dur\n";
echo "choose --default \${menu-default} --timeout \${menu-timeout} selected && goto \${selected} || exit 0\n";

echo ":shell\n";
echo "echo iPXE shell...\n";
echo "shell\n";

echo ":exit\n";
echo "echo Booting harddisk ...\n";
echo "sanboot --no-describe --drive 0x80\n";

echo ":rescue32\n";
echo "chain --replace sysrescuecd/rescue32.php\n";
//echo "kernel http://${se3ip}/sysresccd/isolinux/rescue32\n";
//echo "initrd http://${se3ip}/sysresccd/isolinux/initram.igz\n";
//echo "imgargs rescue32 setkmap=fr dodhcp scandelay=5 netboot=http://${se3ip}/sysresccd/sysrcd.dat autoruns=2 ar_source=http://${se3ip}/sysresccd/ ar_nowait\n";
//echo "boot || goto failed\n";
//echo "goto start\n";

echo ":rescue64\n";
echo "chain --replace sysrescuecd/rescue64.php\n";
//echo "kernel http://${se3ip}/sysresccd/isolinux/rescue64\n";
//echo "initrd http://${se3ip}/sysresccd/isolinux/initram.igz\n";
//echo "imgargs rescue64 setkmap=fr dodhcp scandelay=5 netboot=http://${se3ip}/sysresccd/sysrcd.dat autoruns=2 ar_source=http://${se3ip}/sysresccd/ ar_nowait\n";
//echo "boot || goto failed\n";
//echo "goto start\n";

echo ":altker32\n";
echo "chain --replace sysrescuecd/altker32.php\n";
//echo "kernel http://${se3ip}/sysresccd/isolinux/altker32\n";
//echo "initrd http://${se3ip}/sysresccd/isolinux/initram.igz\n";
//echo "imgargs altker32 setkmap=fr dodhcp scandelay=5 netboot=http://${se3ip}/sysresccd/sysrcd.dat autoruns=2 ar_source=http://${se3ip}/sysresccd/ ar_nowait\n";
//echo "boot || goto failed\n";
//echo "goto start\n";

echo ":altker64\n";
echo "chain --replace sysrescuecd/altker64.php\n";
//echo "kernel http://${se3ip}/sysresccd/isolinux/altker64\n";
//echo "initrd http://${se3ip}/sysresccd/isolinux/initram.igz\n";
//echo "imgargs altker64 setkmap=fr dodhcp scandelay=5 netboot=http://${se3ip}/sysresccd/sysrcd.dat autoruns=2 ar_source=http://${se3ip}/sysresccd/ ar_nowait\n";
//echo "boot || goto failed\n";
//echo "goto start\n";

echo ":retour\n";
echo "chain --replace --autofree boot-admin.php\n";

?>
