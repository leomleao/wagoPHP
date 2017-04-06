<?php

	//header('Content-type: application/json');

if(isset($_POST['file'])){
$file =(string)$_POST['file'];
//print_r($file);
}
else
{$file=false;
}

// Arrays we'll use laterz
$keys = array();
$newArray = array();

//$file="/tmp/data/myData.csv";
// Function to convert CSV into associative array
		function csvToArray($file, $delimiter) { 
		  if (($handle = fopen($file, 'r')) !== FALSE) { 
			$i = 0; 
			while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
			  for ($j = 0; $j < count($lineArray); $j++) { 
				$arr[$i][$j] = $lineArray[$j]; 
			  } 
			  $i++; 
			} 
			fclose($handle); 
		  } 
		  return $arr; 
		} 

// Do it
$data = csvToArray($file, ';');

// Set number of elements (minus 1 because we shift off the first row)
$count = count($data)-1 ;
  
//Use first row for names  
$labels = array_shift($data);  


// Add Ids, just in case we want them later
$keys = array('label', 'data', 'mot');


for ($i = 0; $i < $count; $i++) {
 
  $data[$i][] = $i;
 
}

// Bring it all together

for ($j = 0; $j < $count; $j++) {
	//print_r($data[$j]);
  $d = array_combine($keys, $data[$j]);
  $newArray[$j] = $d;
}

// Print it out as JSON
print_r(json_encode($newArray));

?>

			
		