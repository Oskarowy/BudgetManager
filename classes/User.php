<?php
class User
{
  public $id;
  public $username;
  function __construct($id, $username)
  {
    $this->id = $id;
    $this->username = $username;
  }
}
?>