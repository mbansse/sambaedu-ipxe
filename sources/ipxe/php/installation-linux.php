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

echo "console --x 1024 --y 768 --picture ../png/linux2.png\n";
echo ":menu\n";
echo "menu installation clients-linux pour ${ip}\n";
echo "set menu-default exit\n";
echo "set menu-timeout $menu_timeout\n";

title("DEBIAN JESSIE");
echo "item  deb_lxde64  Installation de Debian LXDE 64 bits \n";
echo "item  deb_lxde32  Installation de Debian LXDE 32 bits \n";
echo "item  deb_xfce64  Installation de Debian XFCE 64 bits \n";
echo "item  deb_xfce32  Installation de Debian XFCE 32 bits \n";
echo "item  deb_gnome64 Installation de Debian GNOME 64 bits \n";
echo "item  deb_gnome32 Installation de Debian GNOME 32 bits \n";
title("DEBIAN AVEC DUAL BOOT");
echo "item  deb_lxde64_dboot  Installation de Debian LXDE 64 bits dual boot \n";
echo "item  deb_lxde32_dboot  Installation de Debian LXDE 32 bits dual boot \n";
echo "item  deb_xfce64_dboot  Installation de Debian XFCE 64 bits dual boot \n";
echo "item  deb_xfce32_dboot  Installation de Debian XFCE 32 bits dual boot \n";
echo "item  deb_gnome64_dboot Installation de Debian GNOME 64 bits dual boot \n";
echo "item  deb_gnome32_dboot Installation de Debian GNOME 32 bits dual boot \n";
title("UBUNTU XENIAL");
echo "item  ubuntu32  Installation de Xenial gnome 32 bits \n";
echo "item  ubuntu64  Installation de Xenial gnome 64 bits \n";
echo "item  xubuntu32  Installation de Xenial XFCE 32 bits \n";
echo "item  xubuntu64  Installation de Xenial XFCE 64 bits \n";
echo "item  lubuntu32 Installation de Xenial LXDE  32 bits \n";
echo "item  lubuntu64 Installation de Xenial LXDE  64 bits \n";
echo "item  mubuntu32 Installation de Xenial MATE  32 bits \n";
echo "item  mubuntu64 Installation de Xenial MATE  64 bits \n";
title("UBUNTU XENIAL DUAL BOOT");
echo "item  ubuntu32_dboot  Installation de Xenial gnome 32 bits dual boot\n";
echo "item  ubuntu64_dboot  Installation de Xenial gnome 64 bits dual boot\n";
echo "item  xubuntu32_dboot  Installation de Xenial XFCE 32 bits dual boot\n";
echo "item  xubuntu64_dboot  Installation de Xenial XFCE 64 bits dual boot\n";
echo "item  lubuntu32_dboot Installation de Xenial LXDE  32 bits dual boot\n";
echo "item  lubuntu64_dboot Installation de Xenial LXDE  64 bits dual boot\n";
echo "item  mubuntu32_dboot Installation de Xenial MATE  32 bits dual boot\n";
echo "item  mubuntu64_dboot Installation de Xenial MATE  64 bits dual boot\n";

title("Autres options");
echo "item --key s shell  (s) iPXE shell\n";
echo "item --key r retour (r) Retour au menu precedent\n";
echo "item --key x exit (x) Boot sur disque dur\n";
echo "choose --default \${menu-default} --timeout \${menu-timeout} selected && goto \${selected} || exit 0\n";

echo ":shell\n";
echo "echo iPXE shell...\n";
echo "shell\n";

echo ":exit\n";
echo "echo Booting harddisk ...\n";
echo "sanboot --no-describe --drive 0x80\n";

echo ":retour\n";
echo "echo Retour au menu précédent ...\n";
echo "chain --replace boot-admin.php\n";

echo ":deb_lxde64\n";
echo "chain --replace install_linux/deb_lxde64.php\n";

echo ":deb_lxde32\n";
echo "chain --replace install_linux/deb_lxde32.php\n";

echo ":deb_lxde64_dboot\n";
echo "chain --replace install_linux/deb_lxde64_dboot.php\n";

echo ":deb_lxde32_dboot\n";
echo "chain --replace install_linux/deb_lxde32_dboot.php\n";

echo ":deb_xfce64\n";
echo "chain --replace install_linux/deb_xfce64.php\n";

echo ":deb_xfce32\n";
echo "chain --replace install_linux/deb_xfce32.php\n";

echo ":deb_xfce64_dboot\n";
echo "chain --replace install_linux/deb_xfce64_dboot.php\n";

echo ":deb_xfce32_dboot\n";
echo "chain --replace install_linux/deb_xfce32_dboot.php\n";

echo ":deb_gnome64\n";
echo "chain --replace install_linux/deb_gnome64.php\n";

echo ":deb_gnome32\n";
echo "chain --replace install_linux/deb_gnome32.php\n";

echo ":deb_gnome64_dboot\n";
echo "chain --replace install_linux/deb_gnome64_dboot.php\n";

echo ":deb_gnome_dboot\n";
echo "chain --replace install_linux/deb_gnome32_dboot.php\n";

echo ":ubuntu32\n";
echo "chain --replace install_linux/ubuntu32.php\n";

echo ":ubuntu64\n";
echo "chain --replace install_linux/ubuntu64.php\n";

echo ":ubuntu32_dboot\n";
echo "chain --replace install_linux/ubuntu32_dboot.php\n";

echo ":ubuntu64_dboot\n";
echo "chain --replace install_linux/ubuntu64_dboot.php\n";

echo ":lubuntu32\n";
echo "chain --replace install_linux/lubuntu32.php\n";

echo ":lubuntu64\n";
echo "chain --replace install_linux/lubuntu64.php\n";

echo ":lubuntu32_dboot\n";
echo "chain --replace install_linux/lubuntu32_dboot.php\n";

echo ":lubuntu64_dboot\n";
echo "chain --replace install_linux/lubuntu64_dboot.php\n";

echo ":xubuntu32\n";
echo "chain --replace install_linux/xubuntu32.php\n";

echo ":xubuntu64\n";
echo "chain --replace install_linux/xubuntu64.php\n";

echo ":xubuntu32_dboot\n";
echo "chain --replace install_linux/xubuntu32_dboot.php\n";

echo ":xubuntu64_dboot\n";
echo "chain --replace install_linux/xubuntu64_dboot.php\n";

echo ":mubuntu32\n";
echo "chain --replace install_linux/mubuntu32.php\n";

echo ":mubuntu64\n";
echo "chain --replace install_linux/mubuntu64.php\n";

echo ":mubuntu32_dboot\n";
echo "chain --replace install_linux/mubuntu32_dboot.php\n";

echo ":mubuntu64_dboot\n";
echo "chain --replace install_linux/mubuntu64_dboot.php\n";

?>
