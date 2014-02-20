<?php

///////////////////////////
$query = 'wilson antenna';
////////////////////////////
$suffix = 'wilson_antenna';
///////////////////////////
$suffix = 'google_results/'.$suffix.'/'.$suffix;
////////////////////////////

$query = urlencode($query);

/////////////////////////////////////////////////
$fp1 = fopen($suffix."_1.txt","w");
$fp2 = fopen($suffix."_2.txt","w");
$fp3 = fopen($suffix."_3.txt","w");
$fp4 = fopen($suffix."_4.txt","w");
$fp5 = fopen($suffix."_5.txt","w");
$fp6 = fopen($suffix."_6.txt","w");
$fp7 = fopen($suffix."_7.txt","w");
$fp8 = fopen($suffix."_8.txt","w");
$fp9 = fopen($suffix."_9.txt","w");
$fp10 = fopen($suffix."_10.txt","w");
/////////////////////////////////////////////////


/////////////////////////////////////////////////
$root = 'https://www.googleapis.com/customsearch/v1?key=AIzaSyCHLkAeaRXxsMeROXKJmGML6jUovqD5GSE&cx=015730256962797043152:d8c3i6ienxg&q=';
/////////////////////////////////////////////////

$key1 = 'AIzaSyCsb7GAlA2khCndv4GqFXlbv3naP9q_f7s';
$engine1 = '&cx=010876647666235091412:lauemy_c3vo&q=';
	
$key2 = 'AIzaSyCHLkAeaRXxsMeROXKJmGML6jUovqD5GSE';
$engine2 = '&cx=015730256962797043152:d8c3i6ienxg&q=';

//////////////////////////////////////////////////
$google_curl_request1 = $root.$query.'&num=10&alt=json';
$google_curl_request2 = $root.$query.'&num=10&start=11&alt=json';
$google_curl_request3 = $root.$query.'&num=10&start=21&alt=json';
$google_curl_request4 = $root.$query.'&num=10&start=31&alt=json';
$google_curl_request5 = $root.$query.'&num=10&start=41&alt=json';
$google_curl_request6 = $root.$query.'&num=10&start=51&alt=json';
$google_curl_request7 = $root.$query.'&num=10&start=61&alt=json';
$google_curl_request8 = $root.$query.'&num=10&start=71&alt=json';
$google_curl_request9 = $root.$query.'&num=10&start=81&alt=json';
$google_curl_request10 = $root.$query.'&num=10&start=91&alt=json';
//////////////////////////////////////////////////


//////////////////////////////////////////////////

$google_curl_session1 = curl_init($google_curl_request1);
curl_setopt($google_curl_session1, CURLOPT_RETURNTRANSFER, TRUE);
$data_1 = curl_exec($google_curl_session1);
fwrite($fp1,$data_1);

$google_curl_session2 = curl_init($google_curl_request2);
curl_setopt($google_curl_session2, CURLOPT_RETURNTRANSFER, TRUE);
$data_2 = curl_exec($google_curl_session2);
fwrite($fp2,$data_2);

$google_curl_session3 = curl_init($google_curl_request3);
curl_setopt($google_curl_session3, CURLOPT_RETURNTRANSFER, TRUE);
$data_3 = curl_exec($google_curl_session3);
fwrite($fp3,$data_3);

$google_curl_session4 = curl_init($google_curl_request4);
curl_setopt($google_curl_session4, CURLOPT_RETURNTRANSFER, TRUE);
$data_4 = curl_exec($google_curl_session4);
fwrite($fp4,$data_4);

$google_curl_session5 = curl_init($google_curl_request5);
curl_setopt($google_curl_session5, CURLOPT_RETURNTRANSFER, TRUE);
$data_5 = curl_exec($google_curl_session5);
fwrite($fp5,$data_5);

$google_curl_session6 = curl_init($google_curl_request6);
curl_setopt($google_curl_session6, CURLOPT_RETURNTRANSFER, TRUE);
$data_6 = curl_exec($google_curl_session6);
fwrite($fp6,$data_6);

$google_curl_session7 = curl_init($google_curl_request7);
curl_setopt($google_curl_session7, CURLOPT_RETURNTRANSFER, TRUE);
$data_7 = curl_exec($google_curl_session7);
fwrite($fp7,$data_7);

$google_curl_session8 = curl_init($google_curl_request8);
curl_setopt($google_curl_session8, CURLOPT_RETURNTRANSFER, TRUE);
$data_8 = curl_exec($google_curl_session8);
fwrite($fp8,$data_8);

$google_curl_session9 = curl_init($google_curl_request9);
curl_setopt($google_curl_session9, CURLOPT_RETURNTRANSFER, TRUE);
$data_9 = curl_exec($google_curl_session9);
fwrite($fp9,$data_9);

$google_curl_session10 = curl_init($google_curl_request10);
curl_setopt($google_curl_session10, CURLOPT_RETURNTRANSFER, TRUE);
$data_10 = curl_exec($google_curl_session10);
fwrite($fp10,$data_10);
////////////////////////////////////////////////////


/////////////////////////////////
fclose($fp1);
fclose($fp2);
fclose($fp3);
fclose($fp4);
fclose($fp5);
fclose($fp6);
fclose($fp7);
fclose($fp8);
fclose($fp9);
fclose($fp10);
//////////////////////////////////


?>
