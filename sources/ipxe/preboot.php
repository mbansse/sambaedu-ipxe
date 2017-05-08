    <?php 

            echo "#!ipxe\n";

            echo "chain http://".$_SERVER['SERVER_NAME'].":909/boot.php?MAC=\${netX/mac}";

    ?>
