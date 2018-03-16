<?php
class MyDB extends mysqli
{
  function getQuerySingleResult($query)
  {
    if(!$result = $this->query($query)){
      //echo 'Wystąpił błąd (getQuerySingleResult): nieprawidłowe zapytanie...';
      return false;
    }
    if($row = $result->fetch_row()){
      return $row[0];
    }
    else{
      return false;
    }
  }
  
  function getQueryResultAsTableRows($query, $colNames = false, $colNamesAsTh = true)
  {
    if(!$result = $this->query($query)) return false;
    if(!$columns = $result->fetch_fields()) return false;
    
    $str = '';
    
    if($colNames){
      $str .= '<tr>';
      $tag = $colNamesAsTh ? 'th':'td';
      foreach($columns as $col){
        $str .= "<$tag>{$col->name}</$tag>";
      }
      $str .= '</tr>';
    }
    
    while($row = $result->fetch_row()){
      $str .= '<tr>';
      foreach($row as $val){
        $str .= "<td>$val</td>";
      }
      $str .= '</tr>';
    }
    
    return $str;
  }
  
  function getPagination($page, $pages, $link, $msg)
  {
    $str = '';
    for($i = 0; $i < $pages; $i++){
      if($i != $page){
        $str .= "<a href=\"$link&amp;page=$i\">".($i+1)."</a>";
      }
      else{
        $str .= '<span class="activePaginationPage">' . ($i+1)
              . '</span>';
      }
      $str .= '<span class="space"> </span>';
    }
    $str = $msg . $str;
    return $str;
  }
  
}
?>