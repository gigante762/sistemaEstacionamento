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
    <section class= 'container text-center align-middle'>

<?php
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    echo 'Código de parking inválido!';
    die();
}
$id = (int) $_GET['id'];

require_once 'model/Database.php';
require_once 'model/config.php';
//configura o fuso
$fuso = new DateTimeZone('America/Bahia');
date_default_timezone_set('America/Sao_Paulo');
//pegando data atual, hora atual
$data_saida = new DateTime(date("Y-m-d H:i:s"),$fuso);
//$data_saida->set


    //fazer um tratamentos antes
    try{
        //Peguando data de entrada
        $infoId = Database::getInfoByid($id);
        $data_entrada = $infoId['data_entrada'];
        $placa = $infoId['placa'];
        $data_entrada = new DateTime($data_entrada,$fuso);


        //pegando o tempo que ficou no estacionamento
        $tempo_de_estacionamento = $data_saida->diff($data_entrada);
        $data_saida_para_salvar = $data_saida->format('Y-m-d H:i:s');
        
        $dados_de_tempo = $tempo_de_estacionamento->i + ($tempo_de_estacionamento->h *60) + ($tempo_de_estacionamento->d  * 24 *60);
        
        global $FREEAPOSTANTASVEZES;

        $direitoafree = (Database::getUltilizacoes($placa) % $FREEAPOSTANTASVEZES == 0);

        $valor =  $direitoafree ? 0 : calcularValorTotal($dados_de_tempo);


        echo '<h3>O Tempo total foi de: '. $dados_de_tempo. ' minutos.<br></h3>';

        if($direitoafree){
            echo '<h6>Essa sua ultilização é gratuita!</h6>';
            echo '<p>O valor total é de: R$'. number_format($valor,2,',','').'</p>';
        }else{
            echo "<h3>O valor total é de: <span class='badge text-info'>R$". number_format($valor,2,',','').'</span></h3>';
        }
        

        if (isset($_GET['pagar'])){
            
            if(Database::marcarSaida($id,$data_saida_para_salvar,$valor,$placa)){
                //setcookie('operacao','')
                header('location: index.php');
            }else{
                echo '<div class="alert alert-danger" role="alert">
                Erro ao realizar pagamento.
              </div>';
            }
           
        }
        echo "<a class='btn btn-dark mr-5' href='index.php'>Voltar ao menu</a>";
        echo "<a class='btn btn-success' href='marcarSaida.php?id=".$id.'&pagar'."'>Pagar Agora</a>";
        

    }catch(Exception  $e){
        echo 'Código de parking inválido!';
    }

?>
</section>
</body>
</html>