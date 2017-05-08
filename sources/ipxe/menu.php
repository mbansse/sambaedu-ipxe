<?php
require_once('params.php');

echo ":menu\n";
echo $header;


title("Utilitaires de disque et clonage", "hp", "linux_live");
item("System rescue CD", "sysresccd", "sysresccd.php", "hp");
item("Clonezilla", "clonezilla", "clonezilla.php", "hp");


title("Utilitaires de Diagnostics", "hp", "tools");
item("Memtest86+", "memtest86plus", "memtest86plus.php", "hp");
item("Hardware Detection Tool", "hdt", "hdt.php", "hp");
item_cmd("Config iPXE", "configipxe", "config", "hp");
item_cmd("iPXE shell", "ipxeshell", "shell", "hp");

title("Installation Linux", "hp", "linux_install");
item("Debian 8 - Jessie", "install_debian", "install_ubuntu.php", "hp");
item("Ubuntu 16.04", "install_ubuntu", "install_ubuntu.php", "hp");


echo "item --gap\n";
echo "item backtotop Back to top\n";
echo "item signin Sign in as a different user\n";
echo $default;

foreach ($entries as $i) {
    echo "{$i}\n";
}
echo ":backtotop\n";
echo "goto menu\n";
echo ":signin\n";
echo "chain --replace --autofree {$url}boot.php\n";

?>
