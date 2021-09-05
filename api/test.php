<?php



$request_method = $_SERVER["REQUEST_METHOD"];


switch ($request_method) {
    case 'GET':
        # code...
        break;

    case 'POST':

        break;
    
    case 'PUT':

        break;

    case 'DELETE':

        break;
    
    default:
      header("HTTP/1.0 405 Méthode non autorisée");
      break;
        break;
}

