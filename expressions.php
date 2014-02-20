<?php

//////////////////////////////////
/// EXPRESSIONS FOR TOKENIZATION
//////////////////////////////////

$phrase = '[\s]*(\"[^\"]+\")[\s]*'; 			   					//since this is greedy, needed [^\"] inside ""

//$paren = '[\s]*(\([^()]+\))[\s]*';								//need to keep parens for boolean queries

$concatenated = '[\s]*([\w]+\-[\w]+)[\s]*';							//keep hyphens between words

$boolean_not = '[\s]*(\-[\w])+[\s]*';								//keep a true 'not' with boolean - symbol

$paren = '[\s]*(\()[\s]*|[\s]*(\))[\s]*';

$punct = '[\s,?\.:;{}!@#\*\-^]+'; 				   					//removes spaces and punctuation (includes start of line (^)) //removed '&' for boolean

$space_comma = '[\s]*,[\s]*';					   					//allow for comma to be anywhere

$url = '[\s]*(http[s]?:\/\/www\.[\w]+\.[\w]+)[\s]*';

$url = $url.'|'.'[\s]*(www\.[\w]+\.[\w]+)[\s]*';

$ip = '[\s]*([\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3})[\s]*'; 	//ip can't have more than 3 digits per octet and must have min of 1

$date = '[\s]*([\w]{1,2}\/[\w]{1,2}\/[\w]{2,4})[\s]*';

$date = $date.'|'.'[\s]*([\d]+[sthrdn]+[\s]*,?[\s]*[\w]+[\s]*,?[\s]*[\d]+)[\s]*';

$ph_number = '[\s]*([\d]+[^\.]-?[\d]+[^\.]-?[\d]+)[\s]*';			//needed [^\.] so not confused with ip address

$wild_card = '[\s]*([\w]+\*[\w]*)[\s]*';							//preserve wild-card queries
		
$terms = $phrase.'|'.$paren.'|'.$url.'|'.$ph_number.'|'.$date.'|'.$ip.'|'.$wild_card.'|'.$punct.'|'.$space_comma.'|'.$concatenated.'|'.$boolean_not;

?>