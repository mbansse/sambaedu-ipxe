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

# set resolution and background
echo "console --x 1024 --y 768 --picture ../png/ipxe-se3.png\n";
echo ":menu\n";
echo "menu Preboot eXecution Environment for ${ip}\n";
echo "set menu-default exit\n";
echo "set menu-timeout $menu_timeout\n";

title("Menu");
echo "item --key x exit   (x) Quitter le menu pour démarrer le poste normalement\n";
title("Maintenance du poste");
echo "item --key m maintenance  (m) Outils de maintenance (syrescuecd,etc...)\n";
title("Clonezilla");
echo "item --key c clonezilla  (c) Utilisation de Clonezilla pour créer/restaurer des images\n";
title("Installation");
echo "item --key i installation-windows  (i) Installation de Windows 10\n";
echo "item --key l installation-linux  (l) Installation de Linux Debian et Ubuntu\n";
title("Autres options");
echo "item --key s shell  (s) iPXE shell\n";
echo "choose --default \${menu-default} --timeout \${menu-timeout} selected && goto \${selected} || exit 0\n";


echo ":shell\n";
echo "echo iPXE shell...\n";
echo "shell\n";

echo ":exit\n";
echo "echo Booting harddisk ...\n";
echo "sanboot --no-describe --drive 0x80\n";

echo ":maintenance\n";
echo "chain --replace  maintenance.php\n";

echo ":clonezilla\n";
echo "chain --replace --autofree  clonezilla_menu.php\n";

echo ":installation-windows\n";
echo "chain --replace --autofree installation-windows.php\n";

echo ":installation-linux\n";
echo "chain --replace --autofree installation-linux.php\n";
?>
