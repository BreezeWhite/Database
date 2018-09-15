<?php

session_start();

require_once "login2.php";
$conn = get_connection();

$query = "SELECT IdMail FROM Mail;";
if($result = $conn->query($query)){
  $return_v = array();
  while($row = $result->fetch_array()){
    $assoc = array('MailFrom'=>$row[0]);
    $return_v[] = $assoc;
  }
}else{
  echo json_encode(array("success"=>0, "msg"=>"Fail to process the query: $query"));
  return;
}

$nameto = $_POST['nameTo'];
$nameto = mysqli_real_escape_string($conn, $nameto);

$query = "SELECT IdUser 
          FROM User
          WHERE Name ='$nameto';";

if($result = $conn->query($query)){
  $return_v = array();
  while($row = $result->fetch_array()){
    $assoc = array('id'=>$row[0]);
    $return_v[] = $assoc;
  }
}else{
  echo json_encode(array("success"=>0, "msg"=>"Fail to process the query: $query"));
  return;
}

foreach($return_v as $idnumber){
  $idto = $idnumber['id'];
}
  
$stmt = $conn->stmt_init();
$query = "INSERT INTO `Mail` (`IdFrom`, `IdTo`, `Content`, `CreateAt`, `Enabled`) 
          VALUES ({$_SESSION['IdUser']}, $idto, ?, CURRENT_TIMESTAMP, '1');";
  
if(!$stmt->prepare($query)){
  echo json_encode(array("success"=>0, "msg"=>"Fail to prepare query: $query"));
  return;
} 
$stmt->bind_param("s", $mail);
$mail = $_POST['content'];
$stmt->execute();
if(!empty($stmt->error)){
  echo json_encode(array("success"=>0, "msg"=>"Insertion error: {$stmt->error}"));
  return;
}
else{
  echo json_encode(array("success"=>1, "msg"=>"Mail id inserted: ".$stmt->insert_id));
}
  
$result->free();
$stmt->close();
$conn->close();
  
return 1;


?>
