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

//Add a customer
$app->post('/api/customer/add', function(Request $request, Response $response){
    
        //get params passed in the url
        $first_name = $request->getParam('first_name');
        $last_name = $request->getParam('last_name');
        $phone = $request->getParam('phone');
        $email = $request->getParam('email');
        $address = $request->getParam('address');
        $city = $request->getParam('city');
        $state = $request->getParam('state');
    
        $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,state) VALUES 
        (:first_name,:last_name,:phone,:email,:address,:city,:state)";
        
        try{
            //get db Object
            $db = new db();
            //connect
            $db = $db->connect();
    
            $stmt = $db->prepare($sql);

            //bind placeholders for the sql stmt with the variable set to url params
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':state', $state);

            $stmt->execute();

            echo '{"notice": {"text": "Customer Added Successfully"}';
    
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    });