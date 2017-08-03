<?php
class db_driver
{
  var $mysql_host=""; //string
  var $mysql_user=""; //string
  var $mysql_password=""; //string
  var $mysql_database=""; //string
  var $col_names="";//returns column names as a 1D array, to be referenced separately as needed or can be ignored
  var $col_values="";/* Returns a 2D, numerical array of
                     associatve arrays such that a single col value has the
                     index $col_values[n][("some_column_name"|$col_names[n])]
                     examples:
                     $this->title=$database->col_values[0]["title"];
                     $this->title=$database->col_values[0][col_names[0]];
                     */

  function __construct($host, $user, $password, $database)
  {
    $this->mysql_host=$host;
    $this->mysql_user=$user;
    $this->mysql_password=$password;
    $this->mysql_database=$database;
  }

  function get_columns()
  {
    return $array=$this->col_names;
  }

  function get_rows()
  {
    return $array=$this->col_values;
  }

  function db_query($query_string)
    {
    $mysqli = new mysqli("$this->mysql_host", "$this->mysql_user", "$this->mysql_password", "$this->mysql_database");


/* check connection */
$err_date=date(DATE_RFC2822);
      if ($mysqli->connect_errno) {
          $err_file_hndl = fopen("errors.txt", "a");
          $errors="\n\nDate\\time: {$err_date}\nPage: database_driver_enhanced.php\n Error: Database connection failed!\n".$mysqli->connect_error;
          fwrite($err_file_hndl, $errors);
          fclose($err_file_hndl);
          exit();
      }


      if(!$result = $mysqli->query($query_string)){
        $err_file_hndl = fopen("errors.txt", "a");
        $errors = "\n\nDate\\time: {$err_date}\nPage: database_driver_enhanced.php\n Error: Database query failed! \n{$mysqli->error}";
        fwrite($err_file_hndl, $errors);
        fclose($err_file_hndl);
        exit();
      }

        /*IMPORTANT CONSIDERATION:
      If strpos finds the string it returns 0. If it does not, it returns false.
      But 0 is interpreted as false, and === does not seem to help (as in strpos($query[$i],'INSERT')===FALSE).
      So this statement, rather than trying to weed out the non-SELECT
      statements, only runs the code block if a SELECT statement is being used.*/
            if(strpos($query_string,'SELECT')===0){
        //returns an array of
        $field_obj=$result->fetch_fields();
          for($j=0; $j<sizeof($field_obj); $j++)
          {
              $this->col_names[$j]=$field_obj[$j]->name;
          }

        //fetch_array returns one row at a time, so we need to do this
        $i=0;//each $i is a row
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
          //each $k is a column value
          for($k=0; $k<sizeof($this->col_names); $k++)
          {
           $this->col_values[$i][$this->col_names[$k]]=$row[$this->col_names[$k]];
          }

         $i++;
       } //END: While...
    } #END: if(strpos...)
  }//END: function db_query...
}
 ?>
