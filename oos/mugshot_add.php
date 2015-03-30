<?
require ("../_includes/settings.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");
// filename: mugshot_add.php 
// first let's set some variables 
// make a note of the current working directory, relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $base_directory."/images/imageuploads/mugshot/";
// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://www.ionianweddings.co.uk/oos/manage-client.php?a=view&id='.$_POST['client_id']; 

// make a note of the location of the success page 
$uploadSuccess = $uploadForm;

// fieldname used within the file <input> of the HTML form 
$fieldname = 'file'; 
/*
echo $i."<br/>"; $i++;
// Now let's deal with the upload 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 
isset($_POST['submit']) 
    or error('the upload form is neaded', $uploadForm); 
echo $i."<br/>"; $i++;
// check for PHP's built-in uploading errors 
($_FILES[$fieldname]['error'] == 0) 
    or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 
     echo $i."<br/>"; $i++;
// check that the file we are working on really was the subject of an HTTP upload 
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
    or error('not an HTTP upload', $uploadForm); 
     echo $i."<br/>"; $i++;
// validation... since this is an image upload script we should run a check   
// to make sure the uploaded file is in fact an image. Here is a simple check: 
// getimagesize() returns false if the file tested is not an image. 
@getimagesize($_FILES[$fieldname]['tmp_name']) 
    or error('only image uploads are allowed', $uploadForm); 
     echo $i."<br/>"; $i++;
// make a unique filename for the uploaded file and check it is not already 
// taken... if it is already taken keep trying until we find a vacant one 
// sample filename: 1140732936-filename.jpg 
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name'])) 
{ 
    $now++; 
} 
echo $i."<br/>"; $i++;
// now let's move the file to its final location and allocate the new filename to it 
@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
    or error('receiving directory insufficient permission', $uploadForm); 
 echo $i."<br/>"; $i++;    
// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 

echo $i."<br/>"; $i++;


*/ 
$newimage_name = ereg_replace("[^A-Za-z0-9\.-]", "", strtolower($_FILES[$fieldname]["name"]));
$image_filename = $_FILES[$fieldname]["tmp_name"];

$savefolder = "mugshot";

$random = mt_rand(100000,999999);   
$generate_filename = strtolower(trim($newimage_name));
$generate_filename = str_replace(" - ", " ", $generate_filename);
$generate_filename = str_replace("-", " ", $generate_filename);
$generate_filename = str_replace(" ", "-", $generate_filename);
$generate_filename = ereg_replace("[^A-Za-z0-9\.-]", "", $generate_filename);
$generate_filename = str_replace("--", "-", $generate_filename);
$generate_filename = str_replace("----", "-", $generate_filename);
$generate_filename = str_replace("-----", "-", $generate_filename);
$generate_filename = str_replace("------", "-", $generate_filename);
$generate_filename = $random."-".$generate_filename;

$min_width = "150";	
 
$i= 0;
if($newimage_name) {
$filelocation2 = $base_directory."/images/imageuploads/mugshot-o/".$generate_filename;
@move_uploaded_file($image_filename, $filelocation2);
echo $i."hi"; $i++;
}
elseif(!$newimage_name) {
@unlink($filelocation2);
echo $i."hi"; $i++;
} 
elseif(!preg_match('/.+\.(jpeg|jpg|gif|png)/', $newimage_name)) {	
@unlink($filelocation2);
echo $i."hi"; $i++;
}
else {
$filelocation = $base_directory."/images/imageuploads/mugshot/".$generate_filename;
@copy($filelocation2, $filelocation);
echo $i."hi"; $i++;
$sql_command->insert("clients_options","client_id,client_option,option_value","'".addslashes($_POST['client_id'])."','mugshot','".addslashes($generate_filename)."'");

}
echo $i."hi"; $i++;
header('Location: ' . $uploadSuccess); 
?>