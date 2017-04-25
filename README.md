# se3-IPXE : Nouvelle version du service PXE pour SE3

Ce module a vocation à remplacer l'actuel se3-clonage devrait être remplacé par une solution plus modulaire basée sur iPXE : paquets se3-ipxe, se3-clients-*

# test iPXE

Avec la version 0.71 de `se3-dhcp` l'infrastructure est en place pour pouvoir booter en iPXE. Il suffit de taper `ipxe` au boot prompt sur le client suite au démarrage pxe.

Pour le moment il n'existe pas de paquet automatisant l'installation d'iPXE et des différents systèmes, l'installation doit se faire à la main.



## mise en place iPXE




### installations par les paquets officiels

On installe le paquet ipxe. `apt-get install ipxe` Les fichiers sont mis aux emplacement standard Debian, il faudra donc s'adapter :

```
/boot/ipxe.efi
/boot/ipxe.lkrn
/etc/grub.d/20_ipxe
/usr/lib/ipxe/ipxe.efi
/usr/lib/ipxe/ipxe.iso
/usr/lib/ipxe/ipxe.lkrn
/usr/lib/ipxe/ipxe.pxe
/usr/lib/ipxe/undionly.kkpxe
/usr/lib/ipxe/undionly.kpxe
/usr/share/doc/ipxe/changelog.Debian.gz
/usr/share/doc/ipxe/copyright
```


* faire un lien entre `/boot/` et `/tftpboot/`

** Problème : Cette version est tout de même assez ancienne et par ailleurs, certaine soptions utiles ne sont pas activées comme par exemple la commande `console`**

### Mise en place à la mano 

Mise ee place avec le paquet sambaedu-ipxe d'une image compilée en ligne avec https://rom-o-matic.eu/

Avec les options correctes et le clavier fr :
<a href="https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.lkrn&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&" target="_blank">https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.lkrn&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&</a>



### Le fichier de de conf ipxe


#### Le cas de l'installation de windows via ipxe :
* créer le dossier `/var/www/se3/ipxe` et créer un fichier minimal `boot.php` sur ce modèle : 
```
<?php
    include "ldap.inc.php";
    include "ihm.inc.php";
    require("lib_action_tftp.php");
  
    $mac=$_GET['mac'];
   
    echo "#!ipxe
# fichier pour $mac
set boot-url http://$ipse3
kernel ${boot-url}/winpe/wimboot
initrd ${boot-url}/winpe/boot/bcd BCD
initrd ${boot-url}/winpe/boot/boot.sdi boot.sdi
initrd ${boot-url}/winpe/sources/boot.wim boot.wim
boot
    "; 
?>
```
* créer l'arborescence de boot wim dans `/var/www/winpe`, en copiant wimboot depuis http://ipxe.org/wimboot, et les wims obtenus avec les outils Microsoft MDT ou extraits d'une ISO Windows
* eventuellement il est possible d'utiliser le partage `\\se3\install\os` pour mettre les fichiers windows nécessaires aux stades suivants de l'installation windows

Il s'agit de la configuration minimale, la page `boot.php` récupère l'adresse mac et peut donc servir des fichier ipxe personnalisés, cela sera l'objectif des nouveaux paquets.




# Notes, modifications à envisager : 

Cela implique que dans les pages php qui actuellement appellent pxe_gen_cfg.sh, on remplace cet appel par l'enregistrement des options qui ne le sont pas déjà  dans la table sql.

l'écriture de ipxe.php est pas compliquée, sauf que le script pxe_gen fait 4000 lignes... il ne faut rien oublier, mais on doit pouvoir factoriser un peu


la logique serait la suivante :

- si l'@mac est dans la table et qu'une action est définie, on génère le fichier texte pour le boot réseau. on peut aussi être amené à lancer d'autres actions comme le wol, le reboot des récepteurs...
- selon les actions, on a une logique pour valider le bon déroulement de l'action et enregistrer son état dans la table ( N boots, page remontee_tftp, etc...)
- si @mac n'est pas dans la table ou l'action est validée, on génère un fichier pour booter le DD (ou un disque iscsi, ou un preseed, c'est tout l'avantage )

Au niveau des pages action_*, celles-ci n'ont plus besoin d'être bloquantes, car le processus est déclenché par la page ipxe.php. C'est un énorme avantage ! on pourrait faire une page d'état qui affiche en temps reéel l'avancement des opérations, il suffit de rafraichir en fonction de la table sql et du fping des postes

Pour la modularisation des actions :

- un paquet par système :  se3-client-linux, se3-client-windows, se3-client_sysrecuecd, se3-clonezilla, etc.

- Chaque paquet fournit des capacités : installer, cloner, sauvegarder, restaurer, client leger, etc.

- pour chaque capacité on a des options : par exemple pour cloner : partitionnement, mode de clonage, etc.

les paquets contiennnent

- un dl des sources upstream si possible géré directement par le paquet ( voir ce que fait le paquet debian flash par exemple )

- des scripts de mise en place des fichiers nécessaires au boot.

- un template ipxe si besoin spécifique (on peut mettre pas mal de logique dedans, en particulier il y a plein de choses intéressantes pour les preseed, pour les clients légers...)


Pour la migration de pxelinux vers ipxe, il suffit de comfigurer le dhcp, quand on veut switcher completement en ipxe : remplacer $unattend_file qui vaut pxelinux.0 par ipxe.lkrn et c'est tout !


On peut donc envisager de complètement séparer le  nouveau module du module clonage actuel. les deux peuvent cohabiter dans un premier temps...

Donc :

- Création d'un paquet se3-ipxe remplaçant les fonctionnalités de se3-clonage
- Création des paquets se3-client-*  au fur et à mesure. 

