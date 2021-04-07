<?php
    header('Content-Type: application/json; charset=utf-8');
    //Escaneia a pasta classes e faz o require_once de todas elas
    $scan = array_diff(scandir(implode(DIRECTORY_SEPARATOR, array(includePath,'api','classes'))), array('..', '.', 'main.class.php'));

    //Adiciona primeiro o MainClass para daí adicionar as outras classes
    require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'api','classes','main.class.php')));
    foreach($scan as $file){
        require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'api','classes',$file)));
    }

    if(isset($_REQUEST)){
        Rest::open($_REQUEST);
    }

    class Rest{

        public static function open($requisicao){

            foreach($requisicao as $key => $value){
                $$key = $value;
            }            

            $url = explode('/',$url);

            $classe = ucfirst($url[1]);
            $metodo = $url[2];
            
            $parametros = array();

            if(class_exists($classe)){
                if(method_exists($classe, $metodo)){
                    $retorno = call_user_func_array(array(new $classe, $metodo), $parametros);
                    echo json_encode($retorno);
                }
                else{
                    echo json_encode(array('status' => 'false', 'mensagem' => 'Método inexistente!'));
                }
            }
            else{
                echo json_encode(array('status' => 'false', 'mensagem' => 'Classe inexistente!'));
            }

        }

    }
?>