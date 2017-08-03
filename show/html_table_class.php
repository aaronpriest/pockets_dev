<?php
//class html_table

class html_table
{
  public $warning_row_values="You have entered more items than expected.";
  public $warning_row_values2="You have fewer items than expected.";
  public $col_names=["Empty"]; //1D numeric arry of names of the columns set by constructor
  public $col_data; //2D array of values for each column
  public $col_name_map; //allows displayed column names to be differnt while the keys for col_names[n][] stay the same

  public function __construct($col_names_from_user, $row_data_from_user)
  {

    //initalize $col_names
    $this->col_names = $col_names_from_user;

    //inialize $col_name_map
    for($i=0; $i<sizeof($this->col_names); $i++){
        $this->col_name_map[$this->col_names[$i]]=$this->col_names[$i];
      }

      //initialize $col_data

      for($i=0; $i<sizeof($row_data_from_user); $i++){
        for($j=0; $j<sizeof($this->col_names); $j++){
            $this->col_data[$i][$this->col_names[$j]]=$row_data_from_user[$i][$this->col_names[$j]];
          }
        }
  }

  public function add_row_data($new_row_values)//new_row_values is the associatve array created by entering data or a mysqli result set
  {
      for($i=0; $i<sizeof($this->col_names); $i++)
      {
        $this->col_data[$i][$this->col_names[$i]]=$new_row_values[$i][$this->col_names[$i]];
      }

  }

  public function append_row_data($appended_values)/*$appended_values is a numerical array created by entering data
                                                    or a mysqli result set; user is expected to know the number and order of the columns*/
  {
      if(sizeof($appended_values)>sizeof($this->col_names)){echo $this->warning_row_values; return;}
      else if(sizeof($appended_values)<sizeof($this->col_names)){echo $this->warning_row_values2; return;}
      else {
            $next_index=sizeof($this->col_data);

            for($i=0; $i<sizeof($this->col_names); $i++)
            {
              $this->col_data[$next_index][$this->col_names[$i]]=$appended_values[$i];
            }
          }
  }

  public function convert_col_data_to_numerical()
  {
    for($i=0; $i<sizeof($this->col_data); $i++){
      for($j=0; $j<sizeof($this->col_names); $j++){
          $col_data_numerical[$i][$j]=$this->col_data[$i][$this->col_names[$j]];
        }
      }
      return $col_data_numerical;
  }

  public function create_html_table()
  {
    
  //create the table html
    echo "<table>";

    //lay down the header row
    echo "<tr>";
    //header names are stored here; display them
   for($i=0; $i<sizeof($this->col_names); $i++)
   {

     echo "<th>".$this->col_name_map[$this->col_names[$i]]."</th>";
   }
   //done with the header row
   echo "</tr>";
   //now will add rows to the table the header names are keys to the second dimension of $row_values_arr
   $this->add_rows_to_table();
   //close this table
   echo "</table>";
  }

  public function add_rows_to_table()
  {
  /*need to add IDs to TDs so that odd and even rows can be separate*/
  //For each numerical index in $row_values_arr[$i]...
  for($i=0; $i<sizeof($this->col_data); $i++){
    $row_num=$i+1;//for adding row id's, but I did not want to start with 0
    echo "<tr id=\"row{$row_num}\">";//Oh, BTW we need a new row for each numerical index in $row_values_arr since each one represents a row
        //...access the associative key in the $row_values_arr using the names in $header_names_arr as the keys
        for($j=0; $j<sizeof($this->col_names); $j++){
          $cell_num=$j+1; //for making an id for each cell, but I did not want to start with 0
          echo "<td id=\"row{$row_num}cell{$cell_num}\">".$this->col_data[$i][$this->col_names[$j]]."</td>";
        }
    echo "</tr>";//end the row after each iteration of this outer for loop
    }

  }

  public function rename_column($old_name, $new_name)
  {
    $this->col_name_map[$old_name]=$new_name;
  }
}
?>
