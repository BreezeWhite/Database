<?php

require_once "login2.php";
$conn = get_connection();

$return_v = array();
if(isset($_POST['Dscpt']) && isset($_POST['IdMusic'])){
  $result = update_description($conn, $_POST['IdMusic'], $_POST['Dscpt']);

  $return_v['success'] = $result['success'];
  $return_v['msg']     = $result['msg']."\n";
}
if(isset($_POST['NewTag']) && isset($_POST['IdMusic'])){
  $result = update_tag($conn, $_POST['IdMusic'], $_POST['NewTag']);

  $return_v['success'] &= $result['success'];
  $return_v['msg']     .= $result['msg']."\n";
}

echo json_encode($return_v);




function update_description($conn, $mid, $val){
  $stmt  = $conn->stmt_init();
  $query = "UPDATE Music SET Description=? WHERE IdMusic=?";

  if(!$stmt->prepare($query)){
    $stmt->close();
    $conn->close();
    return array("success" => 0, "msg" => "Invalid query: $query");
  }

  $stmt->bind_param('si', $val, $mid);
  if(!$stmt->execute()){
    $stmt->close();
    $conn->close();
    return array("success" => 0, "msg" => $stmt->error);
  }

  $stmt->close();
  return array("success" => 1);
}

function update_tag($conn, $mid, $tags){
  $stmt  = $conn->stmt_init();
  $query = "INSERT INTO Tag(TagName, IdUser, IdMusic) VALUES(?, ?, ?)";

  if(!$stmt->prepare($query)){
    $stmt->close();
    $conn->close();
    return array("success" => 0, "msg" => "Invalid query: $query");
  }
  
  session_start();
  $uid = $_SESSION['IdUser'];
  $stmt->bind_param('sii', $tag, $uid, $mid);
  $tmp = "";
  foreach($tags as $tag){
    $tmp = $tmp." ".$tag; 
    if(!$stmt->execute()){
      $stmt->close();
      $conn->close();
      return array("success" => 0, "msg" => $stmt->error);
    }
  }

  $stmt->close();
  return array("success" => 1, "msg" => $tmp);
}
?>
