<?
header('Content-Type: application/json');
require ("settings.php");
include ("function.database.php");
include ("function.genpass.php");

$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);
$list = $listf = "";
$c_i = empty($_POST['currimg']) ? 0 : $_POST['currimg'];
$t_i = empty($_POST['totimg']) ? 0 : $_POST['totimg'];
$i = empty($_POST['picids']) ? 0 : $_POST['picids'];

$m_i = ceil($t_i/15);

$c_i++;

if($c_i<=$m_i) {
	$active = true;
	$i++;
	$lim1 = ($c_i*10)+1;
	$lim2 = $lim1+10;

	$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10 AND $database_gallery_mason.meta_value != '320x320' LIMIT ".$lim1.",".$lim2);
	
	$dinfo = "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10 LIMIT ".$lim1.",".$lim2;
	$rec = $sql_command->results($imgres);
	shuffle($rec);
	$list = "<ul id=\"gallery-".$c_i."\" class=\"gallery ui-helper-reset ui-helper-clearfix is-scrollable\">";
	foreach($rec as $row) {
		
		list($width,$height) = explode("x",$row[0]);
	
		$width = $width/320;
		$height = $height/320;
		$dimension = array($width,$height);
		$dimension = implode("x",$dimension);
		
		
		$list .= "<li id=\"pic".$i."\" data-imgid=\"".$row[1]."\" class=\"block-".$c_i." item tile".$dimension."\">	
	    	<div class=\"box-container\">
	        	<img id=\"".$row[1]."\" src=\"http://www.ionianweddings.co.uk/images/gallery/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
		        <a href=\"#\" title=\"Love\" class=\"ui-icon ui-icon-trash\">Love</a> 
			</div>
	    </li>";
		$i++;
	}
	$list .= "</ul>";
	
	 $imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10 AND ($database_gallery_mason.meta_value = '320x320' OR $database_gallery_mason.meta_value = '640x640')");
	$rec = $sql_command->results($imgres);
	

	shuffle($rec);
	$p=0;
	foreach($rec as $row) {
		if($p<=$recnum) {
			$p++;
			list($width,$height) = explode("x",$row[0]);
	
			$width = $width/320;
			$height = $height/320;
			if ($width==$height) {
				$dimension = array($width,$height);
				$dimension = implode("x",$dimension);
				
				$listf .= "<li id=\"pic".$i."\" data-imgid=\"".$row[1]."\" class=\"filler item ui-draggable ui-droppable tile".$dimension."\">	
	    	<div class=\"box-container\">
	        	<img id=\"".$row[1]."\" src=\"http://www.ionianweddings.co.uk/images/gallery/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
		        <a href=\"#\" title=\"Love\" class=\"ui-icon ui-icon-trash\">Love</a> 
			</div>
	    </li>";
			$i++;
			}
		}
	}	
}
else {
	$list = "<li>Reached end of images.</li>";
	$active = false;
}
$dinfo = empty($dinfo) ? "nothing wrong" : $dinfo;
$js_value = array("currimg" => $c_i, "picid" => $i, "listi" => $list, "listf" => $listf, "active" => $active, "debug" => $dinfo);
echo json_encode($js_value);

	?>