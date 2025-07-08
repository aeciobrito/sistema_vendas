<?php
session_start();
session_destroy();
header('Location: /sistema_vendas/index.php');
exit();