<?php

//////////////////////////////////////////////////////////////
include('global_variables.php'); //first and only include of global variables
//////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////
//// 				  QUERY EXPANSION
/////////////////////////////////////////////////////////////

//NOTE: Bing azure spelling suggestion was my first choice, but currently is down (not working)

function QUERY_EXPANSION($tokens)
{ 
	if($_POST['Stop_Words']=='Stop_Words_On')
	{
		//removal of remaining stop words
		$tokens = STOP_WORD_REMOVAL($tokens);
	}

	$thesaurus_key = '6c8539cf26065c0e0121bb0f25c135b5';
	$root_url = 'http://words.bighugelabs.com/api/2/'.$thesaurus_key;
	
	if($_POST['Spelling_Corrections']=='Spelling_Corrections_On')
	{
		require("OAuth.php"); 
	
		//run spell check on tokens first. If I find a suggestion I replace that word with the suggestion
		foreach($tokens as $token_key => $word)
		{
			$cc_key  = "dj0yJmk9RDlrYWFpSHF2cE05JmQ9WVdrOWJXdE1OVTluTnpBbWNHbzlPRGsyTlRnd09UWXkmcz1jb25zdW1lcnNlY3JldCZ4PTQy";  
			$cc_secret = "814c4600b92636821fdbb6491af3db280df1ce21";  
			$url = "http://yboss.yahooapis.com/ysearch/spelling"; 

			$args = array();  
			$args["q"] = urlencode(''.$word.'');  
			$args["format"] = "json";
    
   			$yahoo_session = curl_init();
			$consumer = new OAuthConsumer($cc_key, $cc_secret);  
			$request = OAuthRequest::from_consumer_and_token($consumer, NULL,"GET", $url, $args);  
			$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);  
			$url = sprintf("%s?%s", $url, OAuthUtil::build_http_query($args));  

			$headers = array($request->to_header());  
			curl_setopt($yahoo_session, CURLOPT_HTTPHEADER, $headers);  
			curl_setopt($yahoo_session, CURLOPT_URL, $url);  
			curl_setopt($yahoo_session, CURLOPT_RETURNTRANSFER, TRUE); 
	
			$spelling_suggestion = curl_exec($yahoo_session);
			$spelling_suggestion = json_decode($spelling_suggestion);

			//if a spelling suggestion was returned, replace the word with the suggestion
			if($spelling_suggestion->bossresponse->spelling->count == '1')
			{
				echo 'Corrected the word: '.$word.' -- to: '.$spelling_suggestion->bossresponse->spelling->results[0]->suggestion.'<br>';
				$word = $spelling_suggestion->bossresponse->spelling->results[0]->suggestion;
			}
			$tokens[$token_key] = $word; //update the tokens array
		}
	}
	
	if($_POST['Synonyms']=='Synonyms_On')
	{
		foreach($tokens as $word)
		{
			$request = $root_url.'/'.$word.'/json';
			$thesaurus_session = curl_init($request);	
			curl_setopt($thesaurus_session, CURLOPT_RETURNTRANSFER, TRUE);
			$data = curl_exec($thesaurus_session);
			$data = json_decode($data);
		
			//can't add the first synonym as this is the word itself
			$synonym = $data->noun->syn[1];
		
			//don't add empty synonym to tokens
			if($synonym != NULL)
			{
				echo 'Synonym Added: '.$synonym.'<br>';
				//$tokens[] = 'OR';
				$tokens[] = $synonym;
			}
		}
	}
	
	if($_POST['Stemming']=='Stemming_On')
	{
		$tokens = STEMMING($tokens);
	}
	
	if($_POST['Hyphen_Expansion']=='Hyphen_Expansion_On')
	{
		foreach($tokens as $word)
		{
			//if a hyphen was found between two words
			if(preg_match('/[\w]+[\s]*\-[\s]*[\w]+/',$word))
			{
				echo 'Hyphen Expanded to:'.'<br>';
				
				$temp = preg_split('/([\w]+)[\s]*\-[\s]*([\w]+)/i',$word,NULL,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
				
				$tokens[] = $temp[0];
				$tokens[] = $temp[0].$temp[1];
				$tokens[] = $temp[1];
				
				echo $temp[0].'<br>';
				echo $temp[0].$temp[1].'<br>';
				echo $temp[1].'<br>';
			}
		}
	}
	
	return $tokens;
}

////////////////////////////////////////////////////////////////
/// 	REPLACE \" OR \' WITH " (PRESERVE PHRASE QUERIES)
////////////////////////////////////////////////////////////////

function INITIAL_SCAN()
{
	$replaced = preg_replace('/(\\\")/','"',$_POST["query"]); //replace \" with "
	$replaced = preg_replace('/(\\\\\')/','',$replaced);	 //remove all \'

	return $replaced;	
}

////////////////////////////////////////////////////////////////
///// REMOVAL OF PUNCTATION AND PRESERVATION OF DATE-IP-WEBSITE etc
////////////////////////////////////////////////////////////////

function TOKENIZE($replaced)
{
	include('expressions.php');
		
	$tokens = preg_split('/'.$terms.'/i',$replaced,NULL,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE); //ignore case '/..../i'
		
	//print_r($tokens);
	echo '<br>';
	
	return $tokens;
}

///////////////////////////////////////////////////////////////
//// 			BOOLEAN HANDLING AND CASE-FOLDING
///////////////////////////////////////////////////////////////

function CASE_FOLD_BOOLEAN($tokens)
{
	global $query_expansion;

	//array used to convert different boolean approaches to one standard
	$boolean = array();
	$boolean["AND"] = array("AND","&&","&","+");
	$boolean["-"]   = array("NOT"); //'-' omitted
	$boolean["OR"]  = array("OR","||","|");		

	//convert all to lower case except any given boolean arguments 
	//also converts boolean to set standard i.e. && = AND, | = OR, 
	//NOT = '-'

	foreach($tokens as $token_key => $token_val)
	{
		//if an associated value for AND,OR,NOT was entered, convert it to the standard
		foreach($boolean as $bool_key => $bool_value) 
		{
			//if the token is found to be boolean
			if(in_array($token_val,$bool_value))
			{
				//convert the token to standard boolean
				$token_val = $bool_key;
				
				//don't do a query expansion on boolean statements
				$query_expansion = 'OFF';
				
				break; //leave loop
			}
		}
		if($_POST['Case_Folding'] == 'Case_Folding_On')
		{
			if(array_key_exists($token_val,$boolean)==False)
			{
				//convert the token to lower case if not boolean
				$token_val = strtolower($token_val);
			}
			//update the tokens
			$tokens[$token_key] = $token_val;
		}
	}
	foreach($tokens as $token_key => $token_val)
	{	
		if($token_val == "-")
		{
			//special case for '-', paste this token with the
			//next token so: '-','blah' = '-blah'
			$token_val = $token_val.$tokens[$token_key+1];
			$tokens[$token_key] = $token_val;
			
			//remove the pasted token
			unset($tokens[$token_key+1]);
		}
	}
	return $tokens;
}

///////////////////////////////////////////////////////////////
//// 				REMOVAL OF STOP WORDS
///////////////////////////////////////////////////////////////

function STOP_WORD_REMOVAL($tokens)
{
	include('stop_words.php');
	
	//var to keep count of stop words in sentence
	$stop_word_count = 0;
	foreach($tokens as $key=>$val)
	{
		foreach($stop_words as $stop)
		{
			if($val == $stop)
			{
				$stop_word_count+=1;
			}
		}
	}
	
	//only omit stop words if they make up 50% or less of the sentence
	if($stop_word_count <= (count($tokens)/2))
	{
		foreach($tokens as $key=>$val)
		{
			foreach($stop_words as $stop)
			{
				if($val == $stop)
				{
					//remove the token if a stop word
					unset($tokens[$key]);
				}
			}
		}
		//this updates new indexes from deleted elements
		$tokens = array_values($tokens);
	}
	return $tokens;
}

///////////////////////////////////////////////////////////////
//// 			STEMMING OF RESULTING TOKENS
///////////////////////////////////////////////////////////////

function STEMMING($tokens)
{
	include('porter_stemmer.php');

	foreach($tokens as $key => $word)
	{
		$tokens[$key] = PorterStemmer::Stem($word);
	}

	return $tokens;	
}

///////////////////////////////////////////////////////////////
//// 			 MAIN PRE-PROCESSOR FUNCTION
///////////////////////////////////////////////////////////////

function PRE_PROCESSOR()
{
	global $bing_query;
	global $blekko_query;
	global $entireweb_query;
	global $processed_query;

	//this performs an intial scan and preserves phrase queries
	$line = INITIAL_SCAN();
	
	//this will tokenize the query
	$tokens = TOKENIZE($line);

	//this converts all to lower case and translates boolean args
	$tokens = CASE_FOLD_BOOLEAN($tokens);
	
	/*
	//removal of remaining stop words
	$tokens = STOP_WORD_REMOVAL($tokens);
	*/
	
	//pass through query expander
	$tokens = QUERY_EXPANSION($tokens);
	
	//this will be used by the clustering algorithm
	$processed_query = $tokens;

	$bing_query = implode($tokens,' ');
	$entireweb_query = implode($tokens,' ');
	
	//modify tokens for blekko
	foreach($tokens as $key => $word)
	{
		if($word == 'OR')
		{
			$word = '|';
		}
		//can't have parens in search
		else if($word == ")" or $word == "(")
		{
			unset($tokens[$key]);
			continue;
		}
		$tokens[$key] = $word;
	}
	
	$blekko_query = implode($tokens,' ');
	
	//print_r($tokens);
}

////////////////////////////

PRE_PROCESSOR(); //call the pre-processor

////////////////////////////

?>