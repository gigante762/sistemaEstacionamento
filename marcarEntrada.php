<?php

require_once 'model/Database.php';

if(isset($_POST['placa'])){
    if(Database::save($_POST['placa'])){
        header('location: index.php?adicionado');
    }else{
        header('location: index.php?falha');
    }
    
}else{
    header('location: index.php?falha');
}