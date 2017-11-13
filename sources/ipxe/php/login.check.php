<?php
header ("Content-type: text/plain");
echo "#!ipxe\n";

# ipXE login menu
echo "login \n";

$user = "\${username}";
$pass = "\${password}";

echo "chain login.check2.php?user=${user}&pass=${pass} \n";
?>
