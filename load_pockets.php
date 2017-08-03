<?php

include("db.inc");
include("database_driver_enhanced.php");

/* Need some error-reporting?
$err_file_hndl = fopen("errors.txt", "a");
  $err_date=date(DATE_RFC2822);
  $errors="\n\nDate\\time: {$err_date}\nPage: show_or_mod_pocket.php\n WARNING: pocket_id wasn't set, so displaying the Spare Change pocket.";
  fwrite($err_file_hndl, $errors);
  fclose($err_file_hndl);
*/


$db_handle = new db_driver($host, $user, $password, $database);

/*Query: pocket_id, name, amount*/

$query="SELECT pocket_id, name, savedamount FROM pockets WHERE active_tf=1 ORDER BY pocket_id";


$db_handle->db_query($query);

echo json_encode($db_handle->col_values);

 ?>
