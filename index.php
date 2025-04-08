<?php

require "db.php";

global $conn;
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

    if($_SERVER['REQUEST_METHOD']==="GET"){

        $request = $conn->query("SELECT * FROM weather ORDER BY date ASC");//DESC
        $result = $request->fetchAll(PDO::FETCH_ASSOC); //_NUM, _BOTH
        echo json_encode($result);

    }elseif ($_SERVER['REQUEST_METHOD']==="POST"){
        if(isset($data['date'], $data['temperature'], $data['precipitation'])){
            $request = $conn->prepare("INSERT INTO weather (date, temperature, precipitation) VALUES (?, ?, ?)");
            $result = $request->execute([$data['date'], $data['temperature'], $data['precipitation']]);
            echo json_encode(['success' => true, 'message' => 'Data has been added']);
        }else{
            http_response_code(400);
            echo json_encode(['success' => false, 'message'=>'Input data is not correct']);
        }


    }elseif ($_SERVER['REQUEST_METHOD']==="PUT"){
        if(isset($data['id'],$data['date'], $data['temperature'], $data['precipitation'])){
            $request = $conn->prepare("UPDATE weather SET date = ?, temperature = ?, precipitation = ? WHERE id = ?");
            $result = $request->execute([$data['date'], $data['temperature'], $data['precipitation'],$data['id']]);
            echo json_encode(['success' => true, 'message' => 'Data has been changed']);
        }else{
            http_response_code(400);
            echo json_encode(['success' => false, 'message'=>'Request data is not correct']);
        }

    }elseif ($_SERVER['REQUEST_METHOD']==="DELETE"){
        if(isset($data['id'])){
            $request = $conn->prepare("DELETE FROM weather WHERE id = ?");
            $result = $request->execute([$data['id']]);
            echo json_encode(['success'=>true, 'message'=>'Data has been deleted']);
        }else{
            http_response_code(400);
            echo json_encode(['success' => false, 'message'=>'ID is not correct']);
        }

    }else{
        http_response_code(400);
        echo "Oops! The request method is not supported.";
    }

