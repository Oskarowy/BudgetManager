<?php
class Registration
{
  private $dbo = null;
  
  private $fields = array();
  
  function __construct($dbo)
  {
    $this->dbo = $dbo;
    $this->initFields();
  }
  
  function initFields()
  {
    $this->fields['email'] = new FormInput('email', 'Adres e-mail');
    $this->fields['username'] = new FormInput('username', 'Imię');
    $this->fields['password'] = new FormInput('password', 'Hasło', '', 'password');
    $this->fields['passwordConf'] = new FormInput('passwordConf', 'Powtórz Hasło', '', 'password');
  }
  
  function showRegistrationForm()
  {
    foreach($this->fields as $name => $field){
      $field->value = isset($_SESSION['formData'][$name]) ? $_SESSION['formData'][$name] : '';
    }
    $formData = $this->fields;
    if(isset($_SESSION['formData'])){
      unset($_SESSION['formData']);
    }
    include 'templates/registrationForm.php'; 
  }
  
  function registerUser()
  {
    foreach($this->fields as $name => $val){
      if(!isset($_POST[$name])){
        return FORM_DATA_MISSING;
      }
    }
    
    $fieldsFromForm = array();
    $emptyFieldDetected = false;

    foreach($this->fields as $name => $val){
      if($val->type != 'password'){
        $fieldsFromForm[$name] = filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS);
      } else {
        $fieldsFromForm[$name] = $_POST[$name];
      }
      
      $fieldsFromForm[$name] = 
        $this->dbo->real_escape_string($fieldsFromForm[$name]);
        
      if($fieldsFromForm[$name] == '' && $val->required){
        $emptyFieldDetected = true;
      }
    }
    
    if($emptyFieldDetected){
      unset($fieldsFromForm['password']);
      unset($fieldsFromForm['passwordConf']);
      $_SESSION['formData'] = $fieldsFromForm;
      return FORM_DATA_MISSING;
    }
    
    $query = "SELECT COUNT(*) FROM Users WHERE Email='"
           . $fieldsFromForm['email'] . "'";
    if($this->dbo->getQuerySingleResult($query) > 0){
      unset($fieldsFromForm['password']);
      unset($fieldsFromForm['passwordConf']);
      $_SESSION['formData'] = $fieldsFromForm;
      return USER_NAME_ALREADY_EXISTS;
    }
    
    if($fieldsFromForm['password'] != $fieldsFromForm['passwordConf']){
      unset($fieldsFromForm['password']);
      unset($fieldsFromForm['passwordConf']);
      $_SESSION['formData'] = $fieldsFromForm;
      return PASSWORDS_DO_NOT_MATCH;
    }
    unset($fieldsFromForm['passwordConf']);
    unset($this->fields['passwordConf']);
    
    $fieldsFromForm['password'] = crypt($fieldsFromForm['password']);
    
    $fieldsNames = '`'.implode('`,`', array_keys($this->fields)).'`';
    $fieldsVals = '\''.implode('\',\'', $fieldsFromForm).'\'';
    
    $query = "INSERT INTO Users ($fieldsNames) VALUES ($fieldsVals)";

    if($this->dbo->query($query)){
      return ACTION_OK;
    }
    else{
      unset($fieldsFromForm['password']);
      $_SESSION['formData'] = $fieldsFromForm;
      return ACTION_FAILED;
    }
  }
}