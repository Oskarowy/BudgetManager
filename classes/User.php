<?php
class User
{
  public $id;
  public $username;
  public $mail;
  function __construct($id, $username, $mail)
  {
    $this->id = $id;
    $this->username = $username;
    $this->mail = $mail;
  }
}
?>