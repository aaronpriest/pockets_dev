<?php
include("../db.inc");
include("../database_driver_enhanced.php");

/*Check to see if update included a name change*/
$db_handle1 = new db_driver($host, $user, $password, $database);

$select_query="SELECT name, pocket_link FROM pockets WHERE pocket_id={$_POST["id"]} LIMIT 1";

$db_handle1->db_query($select_query);

/*Cleanup name*/
$pocket_name=str_replace("'", "\'", $_POST["name"]);
$pocket_name=str_replace("=", "", $pocket_name);
$pocket_name=str_replace(";", "", $pocket_name);


$current_name=$db_handle1->col_values[0]["name"];

$rename="";

if(!strcmp($current_name, $pocket_name)){/*strcmp returns 0 when both strings are equal*/
    $rename="";
    $new_link="";
}
else //Strings are not equal
{
  $rename="name='{$pocket_name}', ";
  $old_name=str_replace(" ", "-",$current_name)."-".$_POST["id"];
  $new_name=str_replace(" ", "-",$pocket_name)."-".$_POST["id"];

  /*Clean both old and new DIR name the same way create_pockets.php does*/
  //Old
  $old_name=str_replace("'", "", $old_name);
  $old_name=str_replace("\\", "", $old_name);
  $old_name=str_replace("/", "", $old_name);
  //New
  $new_name=str_replace("'", "", $new_name);
  $new_name=str_replace("\\", "", $new_name);
  $new_name=str_replace("/", "", $new_name);

  /*We don't need the ../show part of the link*/
  $new_link=" pocket_link='{$new_name}', ";

  /*Add full path*/
  $old_name="../show/".$old_name;
  $new_name="../show/".$new_name;

  rename($old_name, $new_name);

}

$db_handle2 = new db_driver($host, $user, $password, $database);


$query="UPDATE pockets SET {$rename}amount={$_POST["amount"]}, type='{$_POST["type"]}', daydue={$_POST["daydue"]},
savedamount={$_POST["savedamount"]},{$new_link}";

    /*if capacity is 0 we want it to be NULL in the DB, representing unlimited capacity*/
    if($_POST["capacity"]==0) $query.="capacity=NULL, account='{$_POST["account"]}', accruetf={$_POST["accruetf"]} ";
    else $query.="capacity={$_POST["capacity"]}, account='{$_POST["account"]}', accruetf={$_POST["accruetf"]} ";
    $query.="WHERE pocket_id={$_POST["id"]}";

    $db_handle2->db_query($query);

    ?>
