<?php
mkdir('employee');

$url = "https://imarasoft.net/";

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$returns = curl_exec($curl);


// employee image
$patternForImg = '/https:\/\/imarasoft\.net\/img\/team\/[a-z]*\.[jp][pn]g/i';
preg_match_all($patternForImg, $returns, $images);

// emloyee name
$patternForname = '/<h5\sclass="mt-sm mb-none">[\w\s]*?<\/h5>/i';
preg_match_all($patternForname, $returns, $names);

// emloyee position
$patternForpos = '/<p\sclass="mb-none">[\w\s\/]*?<\/p>/i';
preg_match_all($patternForpos, $returns, $position);


if (is_string($returns)) {

  for ($i = 0; $i < count($images[0]); $i++) {

    // employee name
    $empName = $names[0][$i];
    $patternForemp = "/>[\w\s]*?</i";
    preg_match_all($patternForemp, $empName, $employer);
    $emplName = preg_replace('/[><]/i', ' ', $employer[0][0]);
    // employee name
    
    // employee position
    $empPosition = $position[0][$i];
    $patternForpos = '/>[\w\s\/]*?</i';
    preg_match_all($patternForpos,$empPosition,$positionFil);
    $emplPos = preg_replace('/[><\/]/i', ' ',$positionFil[0][0]);
    // employee position

    $exten = $images[0][$i];
    $locate = dirname(__FILE__);
    $name = $locate."/employee/".$emplName."(".$emplPos.")".substr($exten, -4);

  
    file_put_contents($name,file_get_contents($images[0][$i]));
   

  }
}
$location = dirname(__FILE__);
echo '<center><h1>succeed!</h1></center><br />';
echo '<center><h4>'.$location.'</h4></center><br />';


curl_close($curl);
