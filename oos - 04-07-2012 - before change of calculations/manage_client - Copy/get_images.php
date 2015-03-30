<?


$gallery_name = addslashes($client_info_record[1])." ".addslashes($client_info_record[2])." ".addslashes($client_info_record[3]);

$result = $sql_command->select($database_gallery,"id,gallery_name","WHERE gallery_name='".$gallery_name."'");
$row = $sql_command->result($result);