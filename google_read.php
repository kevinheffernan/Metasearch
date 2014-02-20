<?php

///////////////////////////////////
$query = 'wilson_antenna';
//////////////////////////////////

////////////////
$suffix = 'google_results/'.$query.'/'.$query;

$count = 1;
///////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_1.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_2.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_3.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_4.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_5.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_6.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_7.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_8.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_9.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

//////////////////////////////////
$results = file_get_contents($suffix.'_10.txt');
$results = json_decode($results);

foreach($results->items as $item)
	{
		echo $count.'<br>';
		echo $item->title.'<br>';
		echo $item->snippet.'<br>';
		echo $item->link.'<br>';

		echo '<br><br>';
		$count++;
	}
/////////////////////////////////

?>