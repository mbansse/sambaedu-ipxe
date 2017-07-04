# se3-IPXE : Nouvelle version du service PXE pour SE3

Ce module a vocation à remplacer l'actuel se3-clonage devrait être remplacé par une solution plus modulaire basée sur iPXE : paquets se3-ipxe, se3-clients-*

# test iPXE

Avec la version 0.71 de `se3-dhcp` l'infrastructure est en place pour pouvoir booter en iPXE. Il suffit de taper `ipxe` au boot prompt sur le client suite au démarrage pxe.

on peut aussi changer la variable de conf `$dhcp_unatt_filename` de `pxelinux.0` en `undionly.kpxe`  c'est elle qui permet de basculer en ipxe dans la conf dhcp lors de l'écriture des reservations.

Pour le moment il n'existe pas de paquet automatisant l'installation d'iPXE et des différents systèmes, l'installation doit se faire à la main.



## mise en place iPXE




### installations par les paquets officiels

On installe le paquet ipxe. `apt-get install ipxe` Les fichiers sont mis aux emplacement standard Debian, il faudra donc s'adapter :

En version Stretch, le paquet est à jour : 

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


**Problème : En Wheezy la version est tout de même assez ancienne et par ailleurs, certaine soptions utiles ne sont pas activées comme par exemple la commande `console`**

### Mise en place à la mano 

* Directement dans le paquet sambaedu-ipxe, image compilée en ligne [https://rom-o-matic.eu](https://rom-o-matic.eu)

* [Version ipxe Avec les options correctes et le clavier fr ici](https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.lkrn&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/PARAM_CMD:=1&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&)

* [Version kpxe ou undionly avec les bonnes options permettant le boot ipxe sur les anciennes machines (chainload)](https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.kpxe&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/PARAM_CMD:=1&CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&)

* [Version undionly.kpxe avec les bonnes options permettant le boot ipxe sur les  vm (chainload)](https://rom-o-matic.eu/build.fcgi?BINARY=undionly.kpxe&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=%23%21ipxe%0Aset%20user-class%20se3%0Aautoboot&general.h/PXE_STACK:=1&general.h/PXE_MENU:=1&general.h/SANBOOT_PROTO_ISCSI:=1&general.h/PARAM_CMD:=1&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_PCBIOS:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&branding.h/PRODUCT_NAME=se3&

pour pouvoir booter automatiquement y compris sur les machines ayant déjà un bios ipxe, comme par exemple les vm qemu/proxmox, il faut pouvoir distinguer les deux qui s'enchainent. On utilise pour cela l'option user-agent C'est fait avec un script embarqué dans l'image, et la configuration du dhcp
```
#!ipxe
set user-class se3
autoboot
```
### Le fichier de conf ipxe

De nombreuse possibilités sont offertes, comme le support des variables, la creéation de menu graphiques, etc.....

La syntaxe change pas mal par rapport à pxelinux. Voici quelques ressources utiles :

* [Menu et le fonctionnement d'ipxe en général](http://wiki.mbirth.de/know-how/software/ipxe-network-boot.html)
* [Gérer la conf en php](http://brandon.penglase.net/index.php?title=PXE_Booting_and_Utilities_Menu) 
Ce Site expliquae la conf de A à Z et regorge d'exemplesutiles.
* [Le top : Gestion de l'ensemble sous php avec support de l'authentification et avec des fonctions](https://github.com/skunkie/ipxe-phpmenu/blob/master/README.md). Il s'agit d'un dépot github.
C'est exactement quelque chose de ce type qu'il nous faut, il suffit d'adapter à la marge.

Attention, certaines options de compilations sont obligatoires pour que cela fonctionne :

* PARAM_CMD
* CONSOLE_FRAME BUFFER
* CONSOLE_CMD
* Clavier fr (pas obligatoire mais mieux ;))

Lien direct : 
[https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.lkrn&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/PARAM_CMD:=1&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&
](https://rom-o-matic.eu/build.fcgi?BINARY=ipxe.lkrn&BINDIR=bin&REVISION=master&DEBUG=&EMBED.00script.ipxe=&general.h/PARAM_CMD:=1&general.h/CONSOLE_CMD:=1&console.h/CONSOLE_FRAMEBUFFER:=1&console.h/KEYBOARD_MAP=fr&)

**Les versions debian du paquet ne semblent pas comporter ces options, il faudra donc faire sans le paquet debian**

#### Le cas de l'installation de windows via ipxe et wimboot :
* créer le dossier `/var/www/se3/ipxe` et créer un fichier minimal `boot.php` sur ce modèle : 
```
<?php
    include "ldap.inc.php";
    include "ihm.inc.php";
    require("lib_action_tftp.php");
  
    $mac=$_GET['mac'];
   
    echo "#!ipxe
# fichier pour $mac
set boot-url http://$ipse3/ipxe/
kernel ${boot-url}/W10/wimboot
initrd ${boot-url}/w10/boot/install.bat install.bat
initrd ${boot-url}/w10/boot/winpeshl.ini winpeshl.ini
initrd ${boot-url}/w10/boot/bcd BCD
initrd ${boot-url}/w10/boot/boot.sdi boot.sdi
initrd ${boot-url}/w10/sources/boot.wim boot.wim
boot
    "; 
?>
```
* créer l'arborescence de boot wim dans `/var/www/se3/ipxe/W10`, en copiant wimboot depuis http://ipxe.org/wimboot, et les wims obtenus avec les outils Microsoft ADK ou extraits d'une ISO Windows
* Utiliser le partage `\\se3\install\os` pour mettre les fichiers windows nécessaires aux stades suivants de l'installation windows : 
```
mount W10.iso /media/cdrom
mkdir -p /var/se3/unattended/install/os/W10
cp -a /media/cdrom/* /var/se3/unattended/install/W10/
```
on peut ensuite faire des liens symboliques dans le dossier `/var/www/se3/ipxe/W10` pour les fichiers boot.wim et autres.


Il s'agit de la configuration minimale, la page `boot.php` récupère l'adresse mac et peut donc servir des fichier ipxe personnalisés, cela sera l'objectif des nouveaux paquets.

## installation W10
il est possible de personnaliser `unattend.xml` sans avoir à refaire le winpe. La seule chose demandant une intervention sur le winpe est l'ajout des drivers des cartes réseau `dism ....` Attention les drivers doivent être mis dans les 2 index du wim : winpe et setup64 ?

Pour génerer les fichiers unattend.xml on peut utilsier un outil en ligne : http://windowsafg.no-ip.org/win10x86_x64.html infiniment plus simple que l'outils microsoft... On peut configurer ce ficihier pour qu'il lance directement l'intégration se3.

ce ficiher sera lancé par winpe avec la commande `setup.exe /unattend:z:\os\W10\unattend.xml` danss le `install.bat`

**remarque**
La methode `sanboot` ci-dessous est beaucoup plus rapide que le partage samba. Mais il faut un NAS...

## Installation client lourds W10 (sans disque)
La methode "sanhook" + "wimboot" + "samba" ne fonctionne pas, l'installation plante à la fin. En revanche la méthode "sanhook" + "cdrom" fonctionne parfaitement si le cdrom est en local. Le problème est de pouvoir booter sur un cdrom distant... Il faut donc créer une cible iscsi avec un disque bootable contenant l'arborescence d'installation (exactement comme si c'était un disque usb) : méthode `sanboot`

### Méthode ultra-basique : 
- creer un disque de 8Go, partition ntfs active,
- copier les fichiers de l'iso dessus...
C'est tout ! (du moins sur une vm...)
### Plus évolué : 
Modifier le winpe (boot.wim) pour avoir les drivers reseau, des personnalisations.

### Configuration spécifique du poste diskless : 
- **attention** il faut démarrer le service iscsi juste après l'installation sinon le poste plante à la moindre modif réseau ( les disques se deconnectent)
- il n'est pas possible d'activer Netbios sur la carte du boot iscsi! Ceci rend impossible la mise au domaine du poste en mode NT... Il faut donc une carte réseau dédiée pour le iscsi (configurée en statique ou en dhcp sans passerelle par défaut avec une metrique tres haute), et une carte pour le LAN. Le souci c'est qu'il faut que le se3 fournisse le dhcp ipxe au boot sur la carte iscsi et le reseau sur l'autre... 


Pour le boot il suffit de faire `sanboot iscsi:xxxxxxxx`. Le boot est ultra-rapide (sur une vm !). A tester sur un vrai PC avec du réseau 100 mb/s...
## Pour aller plus loin :
l'idée est d'automatiser la création des cibles iscsi au boot : le script php va 
- soit creer un zvol `zfs create -V` et booter sur le disque d'install
- soit cloner un template déjà installé `zfs clone template@origine newmachine` et le booter
- soit booter le disque déjà existant
- soit faire un rollback

Cela suppose que se3 puisse lister et agir sur les luns distants : il faut utiliser targetd https://github.com/open-iscsi/targetd et libstorage et https://github.com/ClusterHQ/pyzfs/tree/master/libzfs_core

ou alors mieux : Monter un NAS Freenas et utiliser l'API freenas http://api.freenas.org/


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


Pour la migration de pxelinux vers ipxe, il suffit de comfigurer le dhcp, quand on veut switcher completement en ipxe : remplacer $unattend_file qui vaut pxelinux.0 par `undionly.kpxe` et c'est tout !


On peut donc envisager de complètement séparer le  nouveau module du module clonage actuel. les deux peuvent cohabiter dans un premier temps...

Donc :

- Création d'un paquet se3-ipxe remplaçant les fonctionnalités de se3-clonage
- Création des paquets se3-client-*  au fur et à mesure.
