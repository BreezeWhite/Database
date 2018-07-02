<?php  

require_once "loadIcon.php";
require_once "getConnectList.php";
# $icon = load_icon($_SESSION['IdUser']);

$i = 0;
foreach($result as $contact){
  if ($i == 0) {
    echo "<div class='tab-pane fade show active' role='tabpanel' id='{$contact['MailFrom']}'>\n\t<section>";
  }else{
    echo "<div class='tab-pane fade' role='tabpanel' id='{$contact['MailFrom']}'>\n\t\t\t<section>";
  }
  $i ++;
  
  $res = getContent($contact['MailFrom']);

  if(empty($res)){
    echo "<p> you don't have any message. </p>";
    return;
  }

  foreach($res as $content){
    #  if($mail['IdTo'] = $_SESSION['IdUser']){
    if($content['IdTo'] == $_SESSION['IdUser']){
      echo <<< _END
              <div class="from-them">
                <p>{$content['Content']}</p>
              </div><!--End of message container-->
_END;
    }else{
      echo <<< _END
              <div class="from-me">
                <p>{$content['Content']}</p>
              </div><!--End of message container-->
_END;
    }
    echo "<div class='clear'></div>";
  }
  echo "</section></div>\n";
}

function getContent($name){
  
  require_once "login2.php";
  $conn = get_connection();
  $name = mysqli_real_escape_string($conn, $name);

  $query = "SELECT Mail.IdFrom, Mail.IdTo, Mail.Content, Mail.CreateAt, Mail.Enabled
            FROM User INNER JOIN Mail 
            WHERE User.Name='$name' AND ((Mail.IdFrom={$_SESSION['IdUser']} AND Mail.IdTo=User.IdUser) OR (Mail.IdTo={$_SESSION['IdUser']} AND Mail.IdFrom=User.IdUser));";
    
  if($result = $conn->query($query)){
    $return_v = array();
    while($row = $result->fetch_array()){
      $assoc = array('IdFrom'=>$row[0], 'IdTo'=>$row[1], 'Content'=>$row[2], 
                     'CreateDate'=>$row[3], 'Show'=>$row[4]);
      $return_v[] = $assoc;
    }

    return $return_v;
  }

  ALERT("Fail to process the query. haha");
  return 0;
}

?>
