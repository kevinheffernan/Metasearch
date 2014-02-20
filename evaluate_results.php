<?php

///////////////////////////////////

//$bing_1 = fopen("bing_precision.txt","r");

$data_1 = file_get_contents("bing_precision.txt");

echo $data.'<br>';

$data_1 = preg_split('/[\s]*([\d]+\.[\d]+)[\s]*/',$data_1,0,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

print_r($data_1);

$total = 0;

foreach($data_1 as $val)
{
	$total += $val;
}

$total /= 50;

$total *= 100; //get percentage

echo '<br>AVE PRECISION VALUE = '.$total.'<br>';


$data_5 = file_get_contents("bing_rel_average_precision.txt");
$data_5 = preg_split('/[\s]*([\d]+\.[\d]+)[\s]*/',$data_5,0,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
print_r($data_5);

$total = 0;

foreach($data_5 as $val)
{
	$total += $val;
}

$total /= 50;

$total *= 100; //get percentage

echo '<br>MAP VALUE = '.$total.'<br>';



$data_6 = file_get_contents("agg_precision.txt");

echo $data.'<br>';

$data_6 = preg_split('/[\s]*([\d]+\.[\d]+)[\s]*/',$data_6,0,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

print_r($data_6);

$total = 0;

foreach($data_6 as $val)
{
	$total += $val;
}

$total /= 50;

$total *= 100; //get percentage

echo '<br>AGG AVE PRECISION VALUE = '.$total.'<br>';




$data_10 = file_get_contents("agg_rel_average_precision.txt");
$data_10 = preg_split('/[\s]*([\d]+\.[\d]+)[\s]*/',$data_10,0,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
print_r($data_10);

$total = 0;

foreach($data_10 as $val)
{
	$total += $val;
}

$total /= 50;

$total *= 100; //get percentage

echo '<br>AGG MAP VALUE = '.$total.'<br>';



/*
$bing_2 = fopen("bing_recall.txt","r");
$bing_3 = fopen("bing_precision_at_10.txt","r");
$bing_4 = fopen("bing_f_measure.txt","r");
$bing_5 = fopen("bing_rel_average_precision.txt","r");

///////////////////////////////////

///////////////////////////////////

$blekko_1 = fopen("blekko_precision.txt","r");
$blekko_2 = fopen("blekko_recall.txt","r");
$blekko_3 = fopen("blekko_precision_at_10.txt","r");
$blekko_4 = fopen("blekko_f_measure.txt","r");
$blekko_5 = fopen("blekko_rel_average_precision.txt","r");

///////////////////////////////////
*/
?>