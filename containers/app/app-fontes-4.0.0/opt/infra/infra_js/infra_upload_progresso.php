<?php

if (!function_exists("uploadprogress_get_info") || !isset($_GET['ID']) || $_GET['ID']==null){ 
  die;
}

$info = uploadprogress_get_info($_GET['ID']);

$percentual=100;
if ($info['bytes_total']>0) {
  $percentual=intval($info['bytes_uploaded']/$info['bytes_total']*100);
} else { 
 	if ($_GET['CNT']==0) {
 	  $percentual=0;
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="pt-br">
<head>
<script type="text/javascript">
parent.infraUploadProgresso.updateInfo();
</script>
</head>
<body style="margin: 3px; margin-right: 5px;">
<div id="divInfraUploadProgresso" style="width:100%; height:10px; border:1px solid grey; display:block;">
<div id="divInfraUploadProgressoInterna"  style="position:relative; height:10px; background-color:#8DB4E3; width:<?=$percentual?>%;"></div>
</div> 
</body>