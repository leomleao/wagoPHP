<?php 
header('content-type:text/html;charset=utf-8');


if(isset($_POST['folder'])){
$folder =(string)$_POST['folder'];
//print_r($file);
}
else
{$folder='/tmp/data';
}

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  return (count(scandir($dir)) == 2);
}


function find_all_files($dir) 
{ 

if (! is_dir($dir)) { return false;}

 if (is_dir_empty($dir)) {return false;}
 
 else
  {  $root = scandir($dir); 
    foreach($root as $value) 
    { 
        if($value === '.' || $value === '..' || (!is_file("$dir/$value"))) {continue;} 
        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;} 
        foreach(find_all_files("$dir/$value") as $value) 
        { 
            $result[]=$value; 
        } 
    } 
    return $result; 
	}
}
if ($myfiles =find_all_files($folder)) {print_r(json_encode($myfiles));}



else { print_r('no files');};
			
			

			
			
?>