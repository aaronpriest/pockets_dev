<?php
include("../db.inc");
include("../database_driver_enhanced.php");

$db_handle1 = new db_driver($host, $user, $password, $database);

$select_query="SELECT MAX(pocket_id)+1 AS max_id FROM pockets";

$db_handle1->db_query($select_query);

$next_id=$db_handle1->col_values[0]["max_id"];
$new_DIR_parent="../show/";

/*Clean for DIR creation*/
$bad_dir_name=str_replace("'", "", $_POST["name"]);
$bad_dir_name=str_replace("\\", "", $bad_dir_name);
$bad_dir_name=str_replace("/", "", $bad_dir_name);

$new_DIR_name=$new_DIR_parent.str_replace(" ", "-", $bad_dir_name)."-".$next_id;
mkdir($new_DIR_name);
$json_file_hndl = fopen($new_DIR_name."/defs.json", "w");
$this_json="[{\"id\":\"{$next_id}\"}]";
fwrite($json_file_hndl, $this_json);
fclose($json_file_hndl);

copy("pocket_dir_index.php", $new_DIR_name."/index.php");

/*Cleanup name*/
$pocket_name=str_replace("'", "\'", $_POST["name"]);
$pocket_name=str_replace("=", "", $pocket_name);
$pocket_name=str_replace(";", "", $pocket_name);

/*Query: name, amount, type, daydue, savedamount, capacity, account, accruetf*/
$db_handle2 = new db_driver($host, $user, $password, $database);
$query="INSERT INTO pockets (pocket_id, name, amount, type, daydue, savedamount, pocket_link, capacity, account, accruetf)
        VALUES($next_id, '{$pocket_name}',{$_POST["amount"]}, '{$_POST["type"]}',{$_POST["daydue"]},
          {$_POST["savedamount"]}, '{$new_DIR_name}', ";
    /*if capacity is 0 we want it to be NULL in the DB, representing unlimited capacity*/
    if($_POST["capacity"]==0) $query.="NULL, '{$_POST["account"]}', {$_POST["accruetf"]})";
    else $query.="{$_POST["capacity"]}, '{$_POST["account"]}', {$_POST["accruetf"]})";

$db_handle2->db_query($query);


 ?>
