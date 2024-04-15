<?php

require __DIR__."/vendor/autoload.php";

$metodo = $_SERVER['REQUEST_METHOD'];
$caminho = $_SERVER['PATH_INFO']  ?? '/';

#use Php\Primeiroprojeto\Router

$r = new Php\Primeiroprojeto\Router($metodo, $caminho);

#ROTAS

$r->get('/olamundo', 'Php\Primeiroprojeto\Controllers\HomeController@olaMundo');

$r->get('/olapessoa/{nome}', function($params){
    return 'Olá ' . $params[1];
});

#Exercício professora
$r->get('/exer1/formulario', 'Php\Primeiroprojeto\Controllers\HomeController@formExer1');

$r->post('/exer1/resposta', function(){
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $soma = $valor1 + $valor2;
    return "A soma é: {$soma}";
});

#Exercício 1
$r->get('/exercicio1/formulario', function(){
    require_once('exercicio1.html');
});
 
$r->post('/exercicio1/resposta', function(){
    $valor = $_POST['valor'];
    if ($valor > 0 ){
        return "O número é positivo!";
    } elseif ($valor < 0){
        return "O número é negativo!";
    } else {
        return "O número é igual a zero!";
    }
});

#Exercício 2
$r->get('/exercicio2/formulario', function(){
    require_once('exercicio2.html');
});

$r->post('/exercicio2/resposta', function(){
    $numeros = $_POST['numeros'];
    $menor_valor = min($numeros);
    $posicao_menor = array_search($menor_valor, $numeros) + 1;

    return "O menor valor é: " . $menor_valor . "<br>" .
           "A posição do menor valor é: " . $posicao_menor . "<br>";

});

#Exercicio 3
$r->get('/exercicio3/formulario', function(){
    require_once('exercicio3.html');
});

$r->post('/exercicio3/resposta', function(){
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $soma = $valor1 + $valor2;
    if ($valor1 === $valor2){
       return "A valor é: " . $soma = $soma * 3;
    }
    return "A soma é: {$soma}";
});

#Exercício 4
$r->get('/exercicio4/formulario', function(){
    require_once('exercicio4.html');
});

$r->post('/exercicio4/resposta', function(){
    $valor = $_POST["valor"];
    $resposta = "";
    for ($i = 0; $i <= 10; $i++){
        $resposta .= "$valor x $i = ".($valor * $i)."<br/>";
    }
    return $resposta;
});

#Exercício 5
$r->get('/exercicio5/formulario', function(){
    require_once('exercicio5.html');
});

$r->post('/exercicio5/resposta', function(){
    $valor = $_POST["valor"];
    $fatorial = 1;
    for ($i = $valor; $i >= 1; $i--){
        $fatorial *= $i;
    }
    return "Fatorial de $valor é: $fatorial";
});

#Exercício 6
$r->get('/exercicio6/formulario', function(){
    require_once('exercicio6.html');
});

$r->post('/exercicio6/resposta', function(){
    $valora = $_POST['valora'];
    $valorb = $_POST['valorb'];
    if ($valora < $valorb) {
        echo "O valor de A é: $valora e o valor de B é: $valorb\n";
    } elseif ($valora > $valorb) {
        echo "O valor de B é: $valorb e o valor de A é: $valora\n";
    } else {
        echo "Os valores são iguais: $valora\n";
    }
});

#Exercício 7
$r->get('/exercicio7/formulario', function(){
    require_once('exercicio7.html');
});

$r->post('/exercicio7/resposta', function(){
    $valor = $_POST['valor'];
    $centimetro = $valor * 100;
    return "$valor metro(s) convertido é igual a $centimetro centímetros.";
});

#Exercício 8
$r->get('/exercicio8/formulario', function(){
    require_once('exercicio8.html');
});

$r->post('/exercicio8/resposta', function(){
    $tamanho_area = $_POST['tamanho_area'];
    $litros_tinta = ceil($tamanho_area / 3);
    $latas = ceil($litros_tinta / 18);
    $preco_total = $latas * 80;

    return "Quantidade de latas de tinta necessárias: $latas <br>" .
           "Preço total: R$ $preco_total";
});

#Exercício 9
$r->get('/exercicio9/formulario', function(){
    require_once('exercicio9.html');
});

$r->post('/exercicio9/resposta', function(){
    $ano_nascimento = $_POST['ano_nascimento'];
    $ano_atual = date("Y");
    $idade = $ano_atual - $ano_nascimento;
    $dias_vividos = $idade * 365;
    $idade_2025 = 2025 - $ano_nascimento;

    return "Idade: $idade anos <br>" .
           "Dias vividos: $dias_vividos dias <br>".
           "Idade em 2025: $idade_2025 anos";
});

#Exercício 10
$r->get('/exercicio10/formulario', function(){
    require_once('exercicio10.html');
});

$r->post('/exercicio10/resposta', function(){
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $imc = $peso / ($altura ** 2);
    return "Seu IMC é: " . number_format($imc,2);
});

// Chamando o formulário para inserir categoria
$r->get('/categoria/inserir', 'Php\Primeiroprojeto\Controllers\CategoriaController@inserir');

$r->post('/categoria/novo', 'Php\Primeiroprojeto\Controllers\CategoriaController@novo');
#ROTAS

$resultado = $r->handler();

if(!$resultado){
    http_response_code(404);
    echo "Página não encontrada!";
    die();
}

if ($resultado instanceof Closure){
    echo $resultado($r->getParams());
} elseif (is_string($resultado)){
    $resultado = explode("@", $resultado);
    $controller = new $resultado[0];
    $resultado = $resultado[1];
    echo $controller->$resultado($r->getParams());
}