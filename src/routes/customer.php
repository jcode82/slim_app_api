<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Hello name write test
$app = new \Slim\App;

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

//get all customers
$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM customers";
    
    try{
        //get db Object
        $db = new db();
        //connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//get single customer
$app->get('/api/customer/{id}', function(Request $request, Response $response){

    //get id passed in the url
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM customers WHERE id = $id";
    
    try{
        //get db Object
        $db = new db();
        //connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});