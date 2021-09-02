<?php
$request_method = $_SERVER["REQUEST_METHOD"];
$requested_file=explode("/",$_SERVER['REQUEST_URI']);
print_r($request_file);
if($requested_file[1]=="api"){
        if(!empty($requested_file[2])){
                //if
                // TODO tester sur un pattern la structure de $requested_file (si de la forme /api/file on redirige)
                $page=$requested_file[2].".php";
                //on fait une redirection vers ce fichier
                header('Location: '.$page);
                //else
                // TODO s'il y'a des paramètres dans l'url les éliminer avec preg_replace avant la redirection

  

        }else{
                http_response_code(404);
                echo "page not found";
                exit(1);
        }
}

