<?php

$PRECOACADAFRACAODEHORA = 8; //a acada 30min custa + 8 reais;
$TOTALDEVAGAS = 15; //a acada 30min custa + 8 reais;
$FREEAPOSTANTASVEZES = 5; //quantidades de ultilizacoes para ganhar uma de graca
function calcularValorTotal($tempo_em_minutos){
    global $PRECOACADAFRACAODEHORA;
    return (ceil($tempo_em_minutos/30) * $PRECOACADAFRACAODEHORA) == 0 ? $PRECOACADAFRACAODEHORA : (ceil($tempo_em_minutos/30) * $PRECOACADAFRACAODEHORA);// é preciso arrendondar para cima ex: 31 = 2 fracoes de hora;
}

