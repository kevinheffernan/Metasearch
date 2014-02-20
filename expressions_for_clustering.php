<?php

////////////////////////////////////////////////
/// EXPRESSIONS FOR TOKENIZING CLUSTER SNIPPETS
////////////////////////////////////////////////

$punct = '[\s,?\.:;{}&\(\)!@#\*\-\"\'^]+'; 				   			//remove all punctuation

$space_comma = '[\s]*,[\s]*';					   					//allow for comma to be anywhere

$url = '[\s]*(http[s]?:\/\/www\.[\w]+\.[\w]+)[\s]*';				//url

$url = $url.'|'.'[\s]*(www\.[\w]+\.[\w]+)[\s]*';

$ip = '[\s]*([\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3})[\s]*'; 	//ip can't have more than 3 digits per octet and must have min of 1

$date = '[\s]*([\w]{1,2}\/[\w]{1,2}\/[\w]{2,4})[\s]*';				//dates can be flexible

$date = $date.'|'.'[\s]*([\d]+[sthrdn]+[\s]*,?[\s]*[\w]+[\s]*,?[\s]*[\d]+)[\s]*';

$ph_number = '[\s]*([\d]+[^\.]-?[\d]+[^\.]-?[\d]+)[\s]*';			//needed [^\.] so not confused with ip address
		
$terms = $punct.'|'.$url.'|'.$ph_number.'|'.$date.'|'.$ip.'|'.$space_comma;

?>