<?php

$url = "https://imarasoft.net/portfolio";
$locate = dirname(__FILE__) . "/";


$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$returns = curl_exec($curl);
// echo $returns ;
curl_close($curl);

// heading
$pattern_for_porfolio_head = "/<h5\sclass=[\w\W\/\.]*?<\/h5>/i";
preg_match_all($pattern_for_porfolio_head, $returns, $Por_Hea_Non_Filtered);

// thumbnail image
$patternFor_thumbnail_img = '/https:\/\/imarasoft\.net\/img_portfolio\/[\w\W\-]*?"/i';
preg_match_all($patternFor_thumbnail_img, $returns, $thumb_img_non_filter_link);


// short description
$patternFor_short_desc = "/<p\sclass=[\w\W\s\/\-]*?<\/p>/i";
preg_match_all($patternFor_short_desc, $returns, $short_desc_non_filtered);

// read more link
$patterFor_readmore_link = "/<a\sclass=\"mt\-[\w\W\S\-\"]*?<\/a>/i";
preg_match_all($patterFor_readmore_link, $returns, $readmore_links_non_filtered);
// var_dump($readmore_links_non_filtered);

// --------------------------------Loop Starts

for ($i = 0; $i < count($Por_Hea_Non_Filtered[0]); $i++) {

    // filter to string
    $patternFor_Hea_Non_Filter = "/>[\w\s\?\-]*</i";
    preg_match_all($patternFor_Hea_Non_Filter, $Por_Hea_Non_Filtered[0][$i], $Por_Hea_Non_Filtered_1);
    // final result
    $heading = preg_replace('/[<>\?\-\s]/i', "_", $Por_Hea_Non_Filtered_1[0][1]);



    // create folders
    if (!is_dir($i . $heading)) {
        mkdir($i . $heading);
    }

    // generate thumbnail images
    $image_link = preg_replace('/[\"\s]/i', "", $thumb_img_non_filter_link[0][$i]);
    $image_insert = $locate . "/$i" . "$heading/thumbnail" . substr($image_link, -4);
    if (!file_exists($image_insert)) {
        file_put_contents($image_insert, file_get_contents($image_link));
    }

    // filter to string
    $patternFor_short_desc_non_fil1 = '/>[\w\W\-\s]*?</i';
    preg_match_all($patternFor_short_desc_non_fil1, $short_desc_non_filtered[0][$i], $patternFor_short_desc_non_fil2);
   
    // final result
    $short_desc = preg_replace('/[<>]/i', "*", $patternFor_short_desc_non_fil2[0][0]);

    $short_desc_insert = $locate . "/$i" . "$heading/short_description.txt";
    if (!file_exists($short_desc_insert)) {
        file_put_contents($short_desc_insert, $short_desc);
    }


    // readmore link
    $patternFor_readmore_non_fil = '/https:\/\/[\w\W\/\-]*?"/i';
    preg_match_all($patternFor_readmore_non_fil, $readmore_links_non_filtered[0][$i], $patternFor_readmore_non_fil1);

    $readmore_link = preg_replace('/[\"]/i', " ", $patternFor_readmore_non_fil1[0][0]);



    $suBcurl = curl_init();

    curl_setopt($suBcurl, CURLOPT_URL, $readmore_link);
    curl_setopt($suBcurl, CURLOPT_RETURNTRANSFER, true);

    $suBreturns = curl_exec($suBcurl);

    curl_close($suBcurl);

    // long description
    $patternFor_long_desc_non_filtered = '/<\/h1>[\w\W\<\>\-\"]*?<ul\sclass\=\"portfolio\-details\">/i';
    preg_match_all($patternFor_long_desc_non_filtered, $suBreturns, $patternFor_long_desc_non_filtered1);

    $longDescription = preg_replace('/<[\w\W\"\s]*?>/i', "\n * \t", $patternFor_long_desc_non_filtered1[0][0]);

    $long_desc_insert = $locate . "/$i" . "$heading/long_description.txt";
    if (!file_exists($long_desc_insert)) {
        file_put_contents($long_desc_insert, $longDescription);
    }



    // video link
    $pattern_For_video_link = '/<iframe[\w\W\"\s]*?frameborder/i';
    preg_match_all($pattern_For_video_link, $suBreturns, $video_link_non_filtered);
    if (!empty($video_link_non_filtered[0][0])) {


        $pattern_For_video_link_non_filtered1 = '/https[\w\W\:\/]*?"/i';
        preg_match_all($pattern_For_video_link_non_filtered1, $video_link_non_filtered[0][0], $pattern_For_video_link_non_filtered2);

        $video_link = preg_replace('/[\"]/i', ' ', $pattern_For_video_link_non_filtered2[0][0]);


        $video_link_insert = $locate . "/$i" . "$heading/video_Link.txt";
        if (!file_exists($video_link_insert)) {
            file_put_contents($video_link_insert, $video_link);
        }
    }




    // readmore images
    $images_folder = $locate . "/$i" . "$heading/images";

    if (!file_exists($images_folder)) {
        mkdir($images_folder);
    }
    $pattern_for_images = '/<div\sclass\=\"col\-xs\-4\">[\w\W\/]*?<\/a>/i';
    preg_match_all($pattern_for_images, $suBreturns, $pattern_for_images_non_filtered);

    for ($d = 0; $d < count($pattern_for_images_non_filtered[0]); $d++) {

        $pattern_for_images_non_filtered1 = '/<a\s[\w\W\/\=\"]*?<img/i';
        preg_match_all($pattern_for_images_non_filtered1, $pattern_for_images_non_filtered[0][$d], $pattern_for_images_non_filtered2);


        $pattern_for_images_non_filtered3 = '/https\:[\W\w\/\_]*?"/i';
        preg_match_all($pattern_for_images_non_filtered3, $pattern_for_images_non_filtered2[0][0], $pattern_for_images_non_filtered4);

        $images_link = preg_replace('/[\"]/i', '\s', $pattern_for_images_non_filtered4[0][0]);
      
        $images_insert = $locate . "/$i" . "$heading/images" . "/image_$d" . substr($image_link, -4);
        if (!file_exists($images_insert)) {
        file_put_contents($images_insert, file_get_contents($image_link));
        }
    }
}


$location = dirname(__FILE__);
echo '<center><h1>succeed!</h1></center><br />';
echo '<center><h4>'.$location.'</h4></center><br />';

?>
