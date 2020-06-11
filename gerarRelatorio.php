<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Estacinamento</title>
</head>
<body>
    <div>
        <nav class="navbar navbar-light bg-light sticky-top">
            <a class="navbar-brand" href="index.php">Voltar ao menu</a>
            <form action="" method = 'get'>
                <input type="date" name='data_inicio' value='<?php echo $_GET['data_inicio']?>'>
                <input type="date" name='data_fim' value='<?php echo $_GET['data_fim']?>'>
                <button class ='btn btn-info'>Filtar</button>
            </form>
        </nav>
    </div>
<table class= 'table table-dark'>
    

    
<?php

require_once 'model/Database.php';
if(isset($_GET['data_inicio']) && isset($_GET['data_fim'])){
    $relatorio = Database::gerarRelatorio($_GET['data_inicio'],$_GET['data_fim']);

    echo "
    <thead>
        <th>Placa</th>
        <th>Data enthada</th>
        <th>Data saida</th>
        <th>Estado</th>
        <th> Valor total: R$ ".number_format($relatorio['valor_total_no_periodo'],2,',','')."</th>
    </thead>
    <tbody>
    ";
    echo '<pre>';
    //var_dump($relatorio);
    //var_dump($relatorio['carros']);
    
    foreach($relatorio['carros'] as $carro){
        $valorFormatado = number_format($carro['valor'],2,',','');
        
        echo '<tr>';
        echo '<td>'.$carro['placa'].'</td>';
        echo '<td>'.$carro['data_entrada'].'</td>';
        echo '<td>'.$carro['data_saida'].'</td>';
        $classEstado = $carro['estado'] == 'Estacionado' ? 'btn-warning' : 'btn-success';
        echo "<td><div class='btn {$classEstado}'>{$carro['estado']}</div></td>";
        echo "<td><span class='badge badge-secondary'>R$".$valorFormatado."</span></td>";
        echo '</tr>';
        
    }
    
    
}
?>
</tbody>
</table>
</body>
</html>