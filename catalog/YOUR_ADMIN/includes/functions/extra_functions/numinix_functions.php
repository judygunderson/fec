<?php
  if (!function_exists('nmx_check_field')) {
    function nmx_check_field($tableName,$columnName,$add = false,$column_type='VARCHAR(72) NULL default NULL'){
        global $db;
        
      $return = false;
        //Getting table fields through mysql built in function, passing db name and table name
      $tableFields = $db->metaColumns($tableName);
      
      $columnNameUpper = strtoupper($columnName);
      //loop to traverse tableFields result set
      foreach($tableFields as $key=>$value) 
      {    
          if($key == $columnNameUpper){
              $return = true;
          }
      }
      if($add != false && $return == false){
          $db->Execute("ALTER TABLE " . $tableName . " ADD ".$columnName." ".$column_type.";");
          $return = true;
      }
      return $return;
    } //end of function
  }