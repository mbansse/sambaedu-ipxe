<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";
$menu_timeout = '10000';
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

echo "console --x 1024 --y 768 --picture ../png/windows10.png\n";
echo ":menu\n";
echo "menu installation clients Windows pour ${ip}\n";
echo "set menu-default exit\n";
echo "set menu-timeout $menu_timeout\n";

title("Menu Windows");
echo "item  installw10 Installation de Windows 10 \n";
echo "item  Win10up   Mise a jour W10 (experimental!!!)\n";
echo "item  Win10man  Installation W10 manuelle\n";
echo "item  Win10l2   boot W10 diskless(experimental!!!)\n";
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


echo ":installw10\n";
echo "chain --replace install_windows/wimboot10.php\n";

echo ":Win10up\n";
echo "chain --replace install_windows/wimboot10r.php\n";
echo "boot\n";
echo ":Win10man\n";
echo "chain --replace install_windows/wimboot10man.php\n";
echo ":Win10l2\n";
echo "chain --replace install_windows/win10diskless2.php\n";

echo ":retour\n";
echo "chain --replace --autofree boot-admin.php\n";

?>
