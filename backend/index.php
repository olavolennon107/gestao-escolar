<?php
// backend/models/index.php

// Redireciona imediatamente qualquer acesso direto a esta pasta para a página principal (login ou dashboard)
header("Location: ../../frontend/login.php");
exit();
?>