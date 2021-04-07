<?php
    $DB = new DBConnection;
    $DB = $DB->connection();
    for($i = 1; $i <= 100; $i++){
        $j = str_pad($i, 3, '0', STR_PAD_LEFT);
        $DB->query("INSERT INTO clientes (nome, cpf, email) VALUES ('CLIENTE - $j','00000000000','email@email.com')");
    }

?>