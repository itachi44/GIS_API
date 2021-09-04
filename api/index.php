<?php
$request_method = $_SERVER["REQUEST_METHOD"];
$requested_file=explode("/",$_SERVER['REQUEST_URI']);
if($requested_file[1]=="api"){
        if(!empty($requested_file[2])){
                $page=$requested_file[2].".php";
                if(file_exists($page)){
                        if(array_key_exists(3, $requested_file) && empty($requested_file[3])){
                                //il y'a un slash à la fin de l'URI
                                header('Location: ../'.$page);
                                exit();
                        }else{
                                //il n'y a pas de slash à la fin
                                header('Location: '.$page);
                                exit();
                        }
                        
                }
                else{
                $search=[
                        "/\/api\//"=>""
                ];
                $uri=preg_replace(array_keys($search),array_values($search), $_SERVER["REQUEST_URI"]);
                $pattern1='/\w+\?\w+\=\w/'; //for normal URL e.g: api/file?key=value
                $pattern2='/\s/'; //for URL which come from Swagger e.g: api/file/{parameter}
                if(preg_match($pattern1,$uri)){
                        $params=explode("?",$uri)[1];
                        $page=explode("?",$uri)[0].".php";
                        $paramsElts=explode("=",$params);
                        $_GET[$paramsElts[0]]=$paramsElts[1];
                        //TODO prévoir une boucle pour gérer plusieurs paramètres
                        if(file_exists($page)){
                        header('Location: ./'.$page.'?'.$params);
                        exit();
                        }
                }else{
                        http_response_code(404);
                        echo "page not found";
                        exit(1);
                }
                }

  

        }else{
                http_response_code(404);
                echo "page not found";
                exit(1);
        }
}

