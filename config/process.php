<?php

use function PHPSTORM_META\type;

session_start();

include_once("connection.php");
include_once("url.php");

$data = $_POST;

  //MODIFICAÇÕES NO BANCO
if(!empty($data)){

    //CRIAR CONTATO
    if($data["type"] === "create") {

      $name = $data["nome"];
      $phone = $data["phone"];
      $observations = $data["observations"];

      $query = "INSERT INTO contacts (nome, phone, observations) VALUES (:nome, :phone, :observations)";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":nome", $name);
      $stmt->bindParam(":phone", $phone);
      $stmt->bindParam(":observations", $observations);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato criado com sucesso";
       
       }catch(PDOException $e) {
           // erro na conexão
           $error = $e->getMessage();
           echo "Erro: $error";
       }

    } else if($data["type"] === "edit") {

      $id = $data["id"];
      $nome = $data["nome"];
      $phone = $data["phone"];
      $observations = $data["observations"];

      $query = "UPDATE contacts SET nome = :nome, phone = :phone, observations = :observations WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);
      $stmt->bindParam(":nome", $nome);
      $stmt->bindParam(":phone", $phone);
      $stmt->bindParam(":observations", $observations);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato atualizado com sucesso";
       
       }catch(PDOException $e) {
           // erro na conexão
           $error = $e->getMessage();
           echo "Erro: $error";
       }

    }else if($data["type"] === "delete") {

      $id = $data["id"];

      $query = "DELETE FROM contacts WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);


      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato deletado com sucesso";
       
       }catch(PDOException $e) {
           // erro na conexão
           $error = $e->getMessage();
           echo "Erro: $error";
       }

    }

    //Redirect HOME
    header("Location:" . $BASE_URL . "../index.php");

  //SELEÇÕES DE DADOS
}else{

  $id = "";

  if(!empty($_GET["id"])) {
      $id = $_GET["id"];
  }
  
  //retorna o dado de um contato
  if(!empty($id)) {
  
    $query = "SELECT * FROM contacts WHERE id = :id";
    
    $stmt = $conn->prepare($query);
  
    $stmt->bindParam(":id", $id);
  
    $stmt->execute();
  
    $contact = $stmt->fetch();
  
  }else{
  
  }
  
  //retorna todos os contatos
  $contacts = [];
  
  $query = "SELECT * FROM contacts";
  
  $stmt = $conn->prepare($query);
  
  $stmt->execute();
  
  $contacts = $stmt->fetchAll();

}

//FECHAR A CONEXÃO
$conn = null;

?>