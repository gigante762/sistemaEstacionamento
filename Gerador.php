<?php

require_once 'model/Database.php';  
require_once 'model/config.php';
global $TOTALDEVAGAS;

class Gerador {
    public static function index(){
        global $TOTALDEVAGAS;
        $partes = array();
        //lotacaopatio
        $partes['lotacaopatio'] = count(Database::allEmUso()).' / '.$TOTALDEVAGAS;
        //patioatual
        $partes['patioatual'] = '';
        $carrosNoPatio = Database::allEmUso();
        foreach($carrosNoPatio as $carro){
            $tr = '';
            $tr .= '<tr>';
            $tr .= '<td>'.$carro['placa'].'</td>';
            $tr .= '<td>'.$carro['data_entrada'].'</td>';
            $tr .= '<td>'.'<div class=\'btn btn-warning\'>Estacionado</div>'.'</td>';
            $tr .= "<td><a class='btn btn-info' href='marcarSaida.php?id={$carro['id']}'>Marcar saída</a></td>";
            $tr .= '</tr>';
            $partes['patioatual'] .= $tr;
        }

        //usuariosFidelidade
        $partes['usuariosFidelidade'] = '';

        $partes['usuariosFidelidade'].= '<ol>';
        $top = Database::usuariosFidelidade(3);
        foreach($top as $usu){
            $partes['usuariosFidelidade'].= '<li>'.$usu['placa'].' - '. $usu['ultilizacoes'].'</li>';
        }
        $partes['usuariosFidelidade'].= '</ol>';

        //alert
        $partes['alert'] =  ''; //até que seja substituido

        if(isset($_GET['adicionado'])){
            $alert = file_get_contents('html/alert.html');
            $alertstyle = 'success';
            $alerttitle = 'Veículo adicionado';
            $alerttext = 'O veículo foi registado no sistema.';

            $alert = str_replace('{alertstyle}',$alertstyle,$alert);
            $alert = str_replace('{alerttitle}',$alerttitle,$alert);
            $alert = str_replace('{alerttext}',$alerttext,$alert);

            $partes['alert'] =  $alert;
        }
        if(isset($_GET['falha'])){
            $alert = file_get_contents('html/alert.html');
            $alertstyle = 'warning';
            $alerttitle = 'Houve uma falha';
            $alerttext = 'O veículo se encontra atualmente no pátio.';

            $alert = str_replace('{alertstyle}',$alertstyle,$alert);
            $alert = str_replace('{alerttitle}',$alerttitle,$alert);
            $alert = str_replace('{alerttext}',$alerttext,$alert);

            $partes['alert'] =  $alert;
        }
        
        
        //gerar pagina
        $html = file_get_contents('html/index.html');

        foreach($partes as $tagK => $tagV){
            $html = str_replace('{'.$tagK.'}', $tagV,$html);
        }

        return $html;
    }

    public static function marcarSaida(){
        $partes = array();
       
        //validadedocodigo
        if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
            $partes['validadedocodigo'] = 'Código de parking inválido!';
        }else{
            $partes['validadedocodigo'] = '';
        }

        //gerando a ...
        $id = (int) $_GET['id'];

        //configura o fuso
        $fuso = new DateTimeZone('America/Bahia');
        date_default_timezone_set('America/Sao_Paulo');
        //pegando data atual, hora atual
        $data_saida = new DateTime(date("Y-m-d H:i:s"),$fuso);

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

            $partes['dados_de_tempo'] = $dados_de_tempo;
        
            global $FREEAPOSTANTASVEZES;

            $direitoafree = (Database::getUltilizacoes($placa) % $FREEAPOSTANTASVEZES == 0);

            $valor =  $direitoafree ? 0 : calcularValorTotal($dados_de_tempo);
            
            $partes['gratuidade'] = $direitoafree ? 'Essa sua ultilização é gratuita!' : '';
            
            $partes['valor_total'] = number_format($valor,2,',','');
            

            if (isset($_GET['pagar'])){
                $partes['btndepagar'] = '';

                if(Database::marcarSaida($id,$data_saida_para_salvar,$valor,$placa)){
                    $partes['estadodopagamento']= 'success';
                    $partes['textoestadodopagamento']= 'Pagamento realizado com sucesso!';
                }else{
                    $partes['estadodopagamento']= 'danger';
                    $partes['textoestadodopagamento']= 'Erro ao realizar pagamento!';
                }
                
            }else{
                $partes['estadodopagamento']= '';
                $partes['textoestadodopagamento']= '';
                $partes['btndepagar'] = "<a class='btn btn-success' href='marcarSaida.php?id={$id}&pagar'>Pagar Agora</a>";
               
            }
            //gerar pagina
            $html = file_get_contents('html/marcarSaida.html');

            foreach($partes as $tagK => $tagV){
                $html = str_replace('{'.$tagK.'}', $tagV,$html);
            }

            return $html;

        }catch(Exception  $e){
             'Código de parking inválido!';
        }
    }

    public static function gerarRelatorio(){
        $partes = array();
        
        $partes['valor_total_no_periodo'] = '';

        if(isset($_GET['data_inicio']) && isset($_GET['data_fim'])){
            $partes['data_inicio'] =$_GET['data_inicio'];
            $partes['data_fim'] =$_GET['data_fim'];
            $relatorio = Database::gerarRelatorio($_GET['data_inicio'],$_GET['data_fim']);
            $partes['valor_total_no_periodo'] = number_format($relatorio['valor_total_no_periodo'],2,',','');

            $partes['table'] ='';
            foreach($relatorio['carros'] as $carro){
                $tr = '';
                $valorFormatado = number_format($carro['valor'],2,',','');
                $tr .= '<tr>';
                $tr .= '<td>'.$carro['placa'].'</td>';
                $tr .= '<td>'.$carro['data_entrada'].'</td>';
                $tr .= '<td>'.$carro['data_saida'].'</td>';
                $classEstado = $carro['estado'] == 'Estacionado' ? 'btn-warning' : 'btn-success';
                $tr .= "<td><div class='btn {$classEstado}'>{$carro['estado']}</div></td>";
                $tr .= "<td><span class='badge badge-secondary'>R$".$valorFormatado."</span></td>";
                $tr .= '</tr>';
                
                $partes['table'] .=$tr;
            }
            //gerar pagina
            $html = file_get_contents('html/gerarRelatorio.html');

            foreach($partes as $tagK => $tagV){
                $html = str_replace('{'.$tagK.'}', $tagV,$html);
            }

            return $html;
        }

    }
}

                            
                            