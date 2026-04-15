<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); //Pode ser menu_principal.php também
exit();
?>