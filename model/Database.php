<?php
//if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__))) die('Acesso Negado!');
    
    class Database{

        private static $conn;

        public static function getConnection(){
            if(empty(self::$conn)){
                self::$conn =  new PDO('mysql:host=localhost;dbname=estacionameto', 'root', '');
                
                self::$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }
            return self::$conn;
        }
        //retorna um bool para saber se esta cadastrado
        private static function findCarro($placa){
            try {
                $db = self::getConnection();

                $result = $db->prepare('SELECT * FROM carros WHERE placa = :placa'); // PDOstament

                $res = $result->execute([':placa' => $placa]); // :bool

                $val = $result->fetch(PDO::FETCH_ASSOC);

                return $val;
                //var_dump($val);
                $db = NULL;

            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //retorna um bool para saber se esta no pario
        private static function findEmNoPatio($placa){
            try {
                $db = self::getConnection();

                $result = $db->prepare("SELECT * FROM estadias WHERE placa = :placa AND estado ='Estacionado'"); // PDOstament

                $res = $result->execute([':placa' => $placa]); // :bool

                $val = $result->fetch(PDO::FETCH_ASSOC);

                return $val;
                //var_dump($val);
                $db = NULL;

            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //
        public function getInfoByid($id){
            try {
                $db = self::getConnection();

                $result = $db->prepare('SELECT * FROM estadias WHERE id = :id'); // PDOstament

                $res = $result->execute([':id' => $id]); // :bool

                $val = $result->fetch();

                return $val;
                //var_dump($val);
                $db = NULL;

            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //Cadastra o carro
        private static function cadastrarCarro($placa){
            try {
                $db = self::getConnection();

                $sql = 'INSERT INTO carros(placa) VALUES (:placa)';
                $result = $db->prepare($sql);
                $res = $result->execute([':placa' => $placa]);

                $db = NULL;
                
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //Coloca no patio
        private static function marcarEntrada($placa){
            try {
                $db = self::getConnection();

                //coloca no patio
                $sql = 'INSERT INTO estadias(placa) VALUES (:placa)';
                $result = $db->prepare($sql);
                $res = $result->execute([':placa' => $placa]);

                //faz update nas ultilizacoes
                $ultilizacoes = self::getUltilizacoes($placa);
                $ultilizacoes++;
                $sql = "UPDATE carros SET ultilizacoes = '$ultilizacoes' WHERE placa = :placa";
                $result = $db->prepare($sql);
                $res = $result->execute([':placa' => $placa]);

                $db = NULL;
                
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //tirar do patio e pagar
        public static function marcarSaida($id,$data_saida,$valor){
            try {
                $db = self::getConnection();

                

                //Retira do patio
                $sql = "UPDATE estadias SET data_saida = :data_saida, valor = :valor , estado = 'Pago' WHERE id = :id ";
                $result = $db->prepare($sql);
                $res = $result->execute([':data_saida' => $data_saida, ':id' => $id, ':valor' => $valor]);

                return true;
                $db = NULL;
                
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //pega a quantidade de ultilizacoes
        public static function getUltilizacoes($placa){
            try {
                    $db = self::getConnection();
                    
                    $sql = 'SELECT ultilizacoes FROM carros WHERE placa =:placa';
                    $result = $db->prepare($sql);
                    $res = $result->execute([':placa' => $placa]);

                    $ultilizacoes = (int) $result->fetch()[0];

                    return $ultilizacoes;

                    $db = NULL;
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //pega todos os carros do patio retorna um array
        public static function allEmUso(){
            try {
                $db = self::getConnection();

                $res = $db->query("SELECT * FROM estadias WHERE estado = 'Estacionado'");
                return $res->fetchAll();

                $db = NULL;
                
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
        //Metodo principal para chamar os outros
        public static function save($placa): bool{
            try {
                $db = self::getConnection();
                $placa = strtoupper( $placa );

                //ver se já existe o carro cadastrado
                if(self::findCarro($placa)){
                    //verifica se não esta no patio
                    
                    
                    if(!self::findEmNoPatio($placa)){
                        
                        
                        $ultilizacoes = self::getUltilizacoes($placa);
                        self::marcarEntrada($placa);
                        return true;

                    }else{
                        //não pode colocar no patio o carro que ja está
                       
                        
                        return false;
                    }
                }else{
                    //caso nao exita
            
                    
                    self::cadastrarCarro($placa);
                    self::marcarEntrada($placa);
                    return true;
                }
                $db = NULL;
            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }

        public function gerarRelatorio($data_inicio,$data_final){
            try {
                $db = self::getConnection();

                $relatorio = array();
                
                //pegando todos os carros
                $result = $db->prepare("SELECT * FROM estadias WHERE data_entrada BETWEEN :data_inicio AND :data_final ORDER BY estado ASC "); // PDOstament

                $res = $result->execute([
                ':data_inicio' => $data_inicio,
                ':data_final' => $data_final]); // :bool

                $relatorio['carros'] = $result->fetchAll(PDO::FETCH_ASSOC);

                $relatorio['total_carros'] = count($relatorio['carros']);
                
                //pegando o valor total no periodo
                $result = $db->prepare("SELECT SUM(valor)as valor_total_do_periodo FROM estadias WHERE data_entrada BETWEEN :data_inicio AND :data_final"); // PDOstament

                $res = $result->execute([
                ':data_inicio' => $data_inicio,
                ':data_final' => $data_final]); // :bool

                //echo ('<pre>');
                $relatorio['valor_total_no_periodo'] = (float) $result->fetch(PDO::FETCH_ASSOC)['valor_total_do_periodo'];

                return $relatorio;
                //var_dump($relatorio);
                $db = NULL;

            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }

        public function usuariosFidelidade($qtd){
            try {
                $db = self::getConnection();

                $result = $db->query("SELECT * FROM carros  ORDER BY ultilizacoes DESC LIMIT $qtd"); // PDOstament

                $res = $result->fetchAll(); // :bool
               
                return $res;
                //var_dump($val);
                $db = NULL;

            }catch(PDOException $e){
                echo "Erro!". $e->getMessage() . "<br>";
            }
        }
    }
  //var_dump(Database::allEmUso());
  //Database::save('abc');
  //Database::findEmUso('abc');
?>