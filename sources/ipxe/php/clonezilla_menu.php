<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$menu_timeout = '10000';
//$se3ip = "\${net0/next-server}";
//$ip = "\${net0/ip}";

require_once('params.php');

function title($name) {
    # the max number of characters for resolution 1024 x 768 is 107
    $total_length = 107;
    $name_length = strlen($name);
    $start = intval(($total_length - $name_length) / 2);
    $end = $total_length - $start - $name_length;
    $title = str_repeat("-", $start) . $name . str_repeat("-", $end);
    echo "item --gap -- {$title}\n";
}

echo "console --x 1024 --y 768 --picture ../png/clonezilla.png\n";
echo ":menu\n";
echo "menu maintenance pour ${ip}\n";
echo "set menu-default exit\n";
echo "set menu-timeout $menu_timeout\n";

title("Clonezilla");
echo "item  live32 Utilisation de Clonezilla-livecd 32 bits \n";
echo "item  live64 Utilisation de Clonezilla-livecd 64 bits \n";
echo "item  sav_locale32 Sauvegarde locale (sda1 vers sda2) 32 bits \n";
echo "item  sav_locale64 Sauvegarde locale (sda1 vers sda2) 64 bits \n";
echo "item  rest_locale32 Restauration locale 32 bits (sda2 vers sda1) \n";
echo "item  rest_locale64 Restauration locale 64 bits (sda2 vers sda1) \n";
title("Autres options");
echo "item --key s shell  (s) iPXE shell\n";
echo "item --key r retour (r) Retour au menu precedent\n";
echo "item --key x exit (x) Boot sur disque dur\n";
echo "choose --default \${menu-default} --timeout \${menu-timeout} selected && goto \${selected} || exit 0\n";

echo ":retour\n";
echo "chain --replace --autofree boot-admin.php\n";

echo ":shell\n";
echo "echo iPXE shell...\n";
echo "shell\n";

echo ":exit\n";
echo "echo Booting harddisk ...\n";
echo "sanboot --no-describe --drive 0x80\n";

echo ":live32\n";
echo "chain --replace clonezilla/live32.php\n";

echo ":live64\n";
echo "chain --replace clonezilla/live64.php\n";

echo ":sav_locale64\n";
echo "chain --replace clonezilla/sav_locale64.php\n";

echo ":sav_locale32\n";
echo "chain --replace clonezilla/sav_local32.php\n";

echo ":rest_locale32\n";
echo "chain --replace clonezilla/rest_locale32.php\n";

echo ":rest_locale64\n";
echo "chain --replace clonezilla/rest_locale64.php\n";

?>
