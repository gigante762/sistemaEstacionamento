<?php

require_once 'model/Database.php';

if(isset($_POST['placa'])){
    Database::save($_POST['placa']);
    header('location: index.php');
}else{
    header('location: index.php');
}