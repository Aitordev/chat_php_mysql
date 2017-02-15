<?php

  class chatClass
  {
    public static function getRestChatLines($id,$me,$session)
    {
      $arr = array();
      $jsonData = '{"results":[';
      $db_connection = new mysqli( mysqlServer, mysqlUser, mysqlPass, mysqlDB);
      $db_connection->query( "SET NAMES 'UTF8'" );
      $statement = $db_connection->prepare( "SELECT id, username, chattext, chattime, admin, color FROM messages WHERE id > ? and session=? and chattime >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
      $statement->bind_param( 'is', $id, $session);
      $statement->execute();
      $statement->bind_result( $id, $username, $chattext, $chattime, $admin, $color);
      $line = new stdClass;
      while ($statement->fetch()) {
        $line->id = $id;
        $line->username = $username;
        $line->chattext = $chattext;
        $line->admin = $admin;
        $line->color = $color;
        $line->chattime = date('H:i', strtotime($chattime));
        if ($username == $me){
          $line->type = 'self';
        }
        else{
          $line->type = 'other';
        }
        $arr[] = json_encode($line);
      }
      $statement->close();
      $db_connection->close();
      $jsonData .= implode(",", $arr);
      $jsonData .= ']}';
      return $jsonData;
    }

    public static function setChatLines( $chattext, $username,$color,$admin,$session) {
      $db_connection = new mysqli( mysqlServer, mysqlUser, mysqlPass, mysqlDB);
      $db_connection->query( "SET NAMES 'UTF8'" );
      $statement = $db_connection->prepare( "INSERT INTO messages( username, chattext, admin, color, session) VALUES(?, ?, ?, ?, ?)");
      $statement->bind_param( 'ssiss', $username, $chattext, $admin, $color,$session);
      $statement->execute();
      $statement->close();
      $db_connection->close();
    }

    public static function getSearchOf($info) {
      $arr = array();
      $jsonData = '{"results":[';
      $db_connection = new mysqli( mysqlServer, mysqlUser, mysqlPass, mysqlDB);
      $db_connection->query( "SET NAMES 'UTF8'" );
      $statement = $db_connection->prepare( "SELECT username, chattext, chattime, color, session FROM messages WHERE chattext LIKE ? ORDER BY id DESC");
      $query = '%' . $info . '%';
      $statement->bind_param( 's', $query);
      $statement->execute();
      $statement->bind_result($username, $chattext, $chattime, $color, $session);
      $line = new stdClass;
      while ($statement->fetch()) {
        $line->username = $username;
        $line->chattext = $chattext;
        $line->session = $session;
        $line->color = $color;
        $line->chattime = date('H:i', strtotime($chattime));
        $line->chatdate = date('d M Y', strtotime($chattime));
        $arr[] = json_encode($line);
      }
      $statement->close();
      $db_connection->close();
      $jsonData .= implode(",", $arr);
      $jsonData .= ']}';
      return $jsonData;
    }

    public static function getPastChat($session) {
      $arr = array();
      $jsonData = '{"results":[';
      $db_connection = new mysqli( mysqlServer, mysqlUser, mysqlPass, mysqlDB);
      $db_connection->query( "SET NAMES 'UTF8'" );
      $statement = $db_connection->prepare( "SELECT username, admin, chattext, chattime, color FROM messages WHERE session = ?");
      $statement->bind_param( 's', $session);
      $statement->execute();
      $statement->bind_result($username, $admin, $chattext, $chattime, $color);
      $line = new stdClass;
      while ($statement->fetch()) {
        $line->username = $username;
        $line->chattext = $chattext;
        $line->admin = $admin;
        $line->color = $color;
        $line->chattime = date('H:i', strtotime($chattime));
        $arr[] = json_encode($line);
      }
      $statement->close();
      $db_connection->close();
      $jsonData .= implode(",", $arr);
      $jsonData .= ']}';
      return $jsonData;
    }
    public static function login($user,$pass,$factory){
      $db_connection = new mysqli( mysqlServer, mysqlUser, mysqlPass, mysqlDB);
      $db_connection->query( "SET NAMES 'UTF8'" );
      $statement = $db_connection->prepare( "SELECT user, pass, admin, factory FROM users WHERE user = ? AND pass = ? AND factory = ?");
      $statement->bind_param( 'sss', $user,$pass,$factory);
      $statement->execute();
      $statement->bind_result($username, $password, $admin, $floor);
      $line = new stdClass;
      if ($statement->fetch()) {
        $line->username = $username;
        $line->password = $password;
        $line->admin = $admin;
        $line->floor = $floor;
      }
      $statement->close();
      $db_connection->close();
      return $line;
    }
  }
?>
