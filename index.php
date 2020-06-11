<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Estacinamento</title>
</head>

<body>
    <h1 class="bg-dark text-white p-2">Estacionamento</h1>
    <div class='container'>
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-center">Marcar entrada</h4>
                <form action="marcarEntrada.php" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Placa</label>
                        <input type="text" class="form-control" name='placa' style = 'text-transform: uppercase'required>
                        <small class="form-text text-muted">Coloque a placa do veículo</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Marcar entrada</button>
                </form>
                
                <div class='mt-5'>
                    <h6>Gerar Relatorio</h6>
                    <form action="gerarRelatorio.php" method = 'get' >
                        <input type="date" name='data_inicio' value='<?php echo $_GET['data_inicio']?>'>
                        até
                        <input type="date" name='data_fim' value='<?php echo $_GET['data_fim']?>'>
                        <button class ='btn btn-info'>Filtar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <h4>Pátio <span class="badge badge-info"><?php  require_once 'model/Database.php'; require_once 'model/config.php'; global $TOTALDEVAGAS;echo count(Database::allEmUso()).' / '.$TOTALDEVAGAS;?></span></h4>
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Data de entrada</th>
                            <th>Estado</th>
                            <th>Marcar saída</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $carrosNoPatio = Database::allEmUso();
                        foreach($carrosNoPatio as $carro){
                            echo '<tr>';
                            echo '<td>'.$carro['placa'].'</td>';
                            echo '<td>'.$carro['data_entrada'].'</td>';
                            echo '<td>'.'<div class=\'btn btn-warning\'>Estacionado</div>'.'</td>';
                            echo "<td><a class='btn btn-info' href='marcarSaida.php?id={$carro['id']}'>Marcar saída</a></td>";
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <section class='container'>
                <div class='card' style='width: 18rem;'>
                    <div class="card-body">
                        <h5 class="card-title">Usuários fidelidade</h5>
                        <p class="card-text">
                            <?php
                                echo '<ol>';
                                $top = Database::usuariosFidelidade(3);
                                foreach($top as $usu){
                                    echo '<li>'.$usu['placa'].' - '. $usu['ultilizacoes'].'</li>';
                                }
                                echo '</ol>';
                            ?>
                            
                        </p>
                    </div>
                </div>
                
            </section>
        </div>
    </div>
    <div class='bg-dark text-white p-5'>
        <h6>Criado por Kevin Rodrigues - <a href="mailto:igantekevin@hotmail.com">igantekevin@hotmail.com</a></h6>
    </div>


</body>

</html>