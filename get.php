<?php


$opts1 = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Referer: \vieon\vtv1-hd" .
		"User-Agent: VieON/20.0.1)"       
  )
);

$return=file_get_contents("https://vieon.vn/truyen-hinh-truc-tuyen/vtv1-hd/", false, stream_context_create($opts1));
preg_match('/iframe.*?src="(.*?)"/', $return, $iframe);

$return=file_get_contents($iframe[1], false, stream_context_create($opts1));

preg_match('/fid="(.*?)"/', $return, $fid);

$opts2 = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Referer: $iframe[1]\vieon\vtv1-hd" .
		"User-Agent: VieON/20.0.1)"       
  )
);

$return=file_get_contents("https://coodertv.w3spaces.com/get.md?u=$fid[1]", false, stream_context_create($opts2));

preg_match('/allowtransparency="true" src=(.*?)\&/', $return, $embed);


$opts3 = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Referer: https://coodertv.w3spaces.com/get.md?u=$fid[1]\vieon\vtv1-hd" .
		"User-Agent: VieON/20.0.1)"       
  )
);

$return=file_get_contents("$embed[1]", false, stream_context_create($opts3));
preg_match('/file:"(.*?)"/', $return, $m3u8);

echo "$m3u8[1]\n\n";

$hls=str_replace("http","hls://http",$m3u8[1]);

$date = date("H-i_d-m-Y");
$outputfile= $date . "-mama.ts";
echo "$outputfile\n\n";
echo "Starting livestreamer...\vieon\vtv1-hd";
echo shell_exec("livestreamer \"$hls\" best -o \"$outputfile\" &");
echo "Done.\n";
?>
