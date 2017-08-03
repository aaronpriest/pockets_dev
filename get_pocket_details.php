<?php
include("db.inc");
include("database_driver_enhanced.php");

if(!isset($_GET["pocket_id"])) {
  $this_pocket_id=1;//The "Spare Change" pocket
  $err_file_hndl = fopen("pocket_errors.txt", "a");
  $err_date=date(DATE_RFC2822);
  $errors="\n\nDate\\time: {$err_date}\nPage: get_pocket_details.php\n WARNING: pocket_id wasn't set, so displaying the Spare Change pocket.";
  fwrite($err_file_hndl, $errors);
  fclose($err_file_hndl);
}
else {
  $this_pocket_id=$_GET["pocket_id"];
  $success_date=date(DATE_RFC2822);
  $success_file_hndl = fopen("success.txt", "a");
  fwrite($success_file_hndl, "Success @".$success_date);
  fclose($success_file_hndl);

}

$db_handle = new db_driver($host, $user, $password, $database);

/*Query: name, amount, type, daydue, savedamount, capacity, account, accruetf*/

$query="SELECT pocket_id, name, amount, type, daydue, savedamount, pocket_link, capacity, account, accruetf ";
$query.="FROM pockets WHERE pocket_id={$this_pocket_id} LIMIT 1";

$db_handle->db_query($query);

echo json_encode($db_handle->col_values);

 ?>
