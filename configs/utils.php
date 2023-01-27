<?php
    /* Utils.php
    Contém algumas funções úteis para o desenvolvimento web. */
    
    // Entrada:
    //      $metodo -> Uma string com o método da requisição ("GET", "POST", "PUT", etc...)
    // Saída:
    //      TRUE se o método da requisição é igual a string de entrada
    function isMethod($metodo)
    {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], $metodo)) {
            return true;
        }
        return false;
    }

    // Entrada:
    //      $metodo -> O vetor associativo com o método a ser pesquisado (ex: $_GET ou $_POST)
    //      $lista -> Uma lista de strings com os parâmetros que devem existir na requisição
    // Saida:
    //      TRUE, se todos os parâmetros em $lista forem encontrados em $metodo.
    function validParameters($metodo, $lista)
    {
        $obtidos = array_keys($metodo);
        $nao_encontrados = array_diff($lista, $obtidos);
        if (empty($nao_encontrados)) {
            foreach ($lista as $p) {
                if (empty(trim($metodo[$p])) and trim($metodo[$p]) != "0") {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    function reply($codigo, $msg) {
        http_response_code($codigo);
        echo json_encode($msg);
        exit;
    }