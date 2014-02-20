<?php

//////////////////////////////////////////////
////////	CLUSTERING ALGORITHM	//////////
//////////////////////////////////////////////

//////////////////////////////////////////////
/// GET INITIAL DATA
//////////////////////////////////////////////

include('function_definitions.php');

get_initial_results(); 
calculate_collection_weights();		
calculate_engine_weights();
calculate_merged_pages_scores();

//////////////////////////////////////////////

//////////////////////////////////////////////
/// PRE-SORT ARRAY
//////////////////////////////////////////////

//this function will sort the $url_array by weight

function cmp($a, $b)
{
   	return $a["weight"] < $b["weight"];
}

usort($url_array,"cmp"); //sort array from high to low

//unset any document past the 100 mark
foreach($url_array as $key => $val)
{
	if($key > 99)
	{
		unset($url_array[$key]);
	}
}

//////////////////////////////////////////////

//////////////////////////////////
/// GLOBAL VARIABLES FOR CLUSTERS
//////////////////////////////////

$N = 100; 							//total number of documents
$k = 5; 							//number of clusters
$collection_word_array = array(); 	//initialize global array to hold 'bag of words'
$total_collection_words = 0; 		//total number of words in the collection word array
$df = array(); 						//array to count how many docs a word appears in (used later for tf-idf weight)
$seeds = array();					//array to hold initial seeds
$seed_index = array();				//indexes of documents chosen as seeds
$centroids = array();				//array to hold centroids
$clusters = array(); 				//array to hold clusters
$iterations = 10;					//max number RSS is bound to
$df_cluster = array();				//holds document term frequency for each cluster

//////////////////////////////////

//////////////////////////////////////////////
///// RSS (Residual Sum of Squares)
//////////////////////////////////////////////
		
//calculate RSS to find decrease in each iteration.
//when there is no change in the RSS, convergence
//has occured so there is no longer a reason to 
//continue in the loop ( break; )
		
//in case of very long iterations, or a case where
//a continuous loop map occur, I have bound this with
//a set number of iterations. If convergence has not
//happened within a set number of iterations, I break
//out of the loop

function RSS()
{
	global $clusters;
	global $centroids;

	//total RSS value for all clusters
	$RSS = 0;
	foreach($clusters as $key1 => $members)
	{
		//RSS for a particular cluster
		$rss_cluster = 0;
		foreach($members as $key2 => $doc)
		{
			foreach($doc["tf_idf"] as $key3 => $number)
			{
				//rss = the vector value minus the centroid value squared
				$temp += (($number - $centroids[$key1][$key3])*($number - $centroids[$key1][$key3]));
				$rss_cluster += abs($temp);
				//echo 'rss_cluster = ('.$number.' - '.$centroids[$key1][$key3].')*2<br>';
			}
		}
		//add RSS value from each cluster
		//echo 'RSS CLUSTER = '.$rss_cluster.'<br>';
		$RSS += $rss_cluster;
	}
	//return RSS value
	return $RSS;
}

////////////////////////////////////////////
///// Top Word Calculation
////////////////////////////////////////////

//this function will calculate the top 10 scoring words
//from each cluster and store them in the $top_words array
//this will be used for evaluation in MI(mutual information)

//it also gives me the document frequencies for each term in
//each cluster which will again, be used in MI (N_00, N_01 etc)

function TOP_WORDS()
{
	//array which holds the document frequencies of each term
	//in each particular cluster
	global $df_cluster;
	global $clusters;
	global $collection_word_array;
	global $processed_query;

	//array to hold all the top words in each cluster
	$top_words = array();
	
	foreach($clusters as $key1 => $members)
	{
		//this array will temp hold values to calculate top words
		$temp_words = array();
		
		$df_cluster[$key1] = array();
		$top_words[$key1] = array();
		
		foreach($members as $key2 => $doc) 
		{
			//note: using term frequency, NOT tf_idf
			foreach($doc["term_frequency"] as $key3 => $number) 
			{
				if(array_key_exists($collection_word_array[$key3],$df_cluster[$key1])==False)
				{
					$df_cluster[$key1][$collection_word_array[$key3]] = 0; //initialize
				}
				if(array_key_exists($key3,$temp_words)==False)
				{
					$temp_words[$key3] = 0; //initialize
				}
				//add up all the frequencies
				$temp_words[$key3] += $number;
				
				if($number > 0) //if a word is present in the document
				{
					$df_cluster[$key1][$collection_word_array[$key3]]++; 
				}
			}
		}
		//sort the array in reverse order while keeping
		//key associations
		arsort($temp_words);

		$count = 0; //variable to keep count of word collection
		$num_top_words = 10; //number of top words that I want
		
		foreach($temp_words as $key4 => $temp_val)
		{
			//if word is not in the processed query itself, include it
			//since each word from the query is bolded, needed to remove <b> tags ('strip_tags')
			if(in_array(strip_tags($collection_word_array[$key4]),$processed_query)==false)
			{
				$top_words[$key1][] = $collection_word_array[$key4];
				$count++;
			}
			if($count == $num_top_words)
			{
				break; //leave when I've found the top three words
			}
		}
	}
	return $top_words;
}

/////////////////////////////////////////
/// MI (mutual information)
/////////////////////////////////////////

//this function acts as my 'feature selection'
//for choosing possible words in each cluster
//as labels. It works by weighing how often a
//word appears in a particular cluster (by how
//often, I mean how many documents it makes
//an appearance in) compared to how often it
//appears on other clusters. 

//so, for I(t,c), where t is a term, instead of 
//having C as a class, such as using reuters 21578 etc
//it will be the cluster in question

//this helps in making a good choice of label
//for a cluster that shouldn't describe content
//from another
function MI($top_words)
{
	 global $df_cluster;
	 global $df;
	 global $clusters;
	 global $N;

	$MI_words = array();
	
	foreach($top_words as $cluster => $words)
	{
		$MI_words[$cluster] = array();
		foreach($words as $word_key => $word)
		{
			$temp = 0;
			$N1 = $df[$word];							//total documents containing the word
			$N0 = $N - $N1;								//total documents that don't contain the word
			$N_11 = $df_cluster[$cluster][$word];		//number of documents in cluster that contain word
			$N_10 = $N1 - $N_11;						//number of documents containing the word that aren't in the cluster
			$N_01 = count($clusters[$cluster]) - $N_11;	//number of docs in cluster that don't contain the word
			$N_00 = $N0 - $N_01;						//number of documents in other clusters that don't contain the word
			$t1 = ($N*$N_11)/($N1*$N1);
			$t2 = ($N*$N_01)/($N0*$N1);
			$t3 = ($N*$N_10)/($N1*$N0);
			$t4 = ($N*$N_00)/($N0*$N0);
			//can't have division by 0 in log, need to convert to 1
			if($t1 == 0)
			{
				$t1 = 1;
			}
			if($t2 == 0)
			{
				$t2 = 1;
			}
			if($t3 == 0)
			{
				$t3 = 1;
			}
			if($t4 == 0)
			{
				$t4 = 1;
			}
			//log base 2 is used here (as specified in the formula)
			$temp =  ($N_11/$N)*(log($t1,2)) + ($N_01/$N)*(log($t2,2));
			$temp += ($N_10/$N)*(log($t3,2)) + ($N_00/$N)*(log($t4,2));
			////echo 'TEMP = '.$temp.'<br>';
			$MI_words[$cluster][$word] = $temp;
		}
	}
	
	echo '<div class="mi_results">';
		echo 'Clusters';
		echo '<br><br>';
		$count_cluster = 1;
		foreach($MI_words as $member)
		{
			echo '<a href="#cluster_'.$count_cluster.'">'.'Cluster '.$count_cluster.'</a><br><br>';
			arsort($member);
			foreach($member as $word => $val)
			{
				echo $word.'<br>';
			}
			//print_r($member);
			echo '<br><br>';
			$count_cluster++;
		}
		echo '<br><br>';
	echo '</div>';
}

function BAG_OF_WORDS($url_array)
{
	include('expressions_for_clustering.php');
	include('stop_words_clustering.php');
	
	global $collection_word_array;
	global $total_collection_words;
	
	//new array to hold the processed result. I need to leave the original array untouched 
	//as when I remove stop words etc, this would give incorrect descriptions to the user
	$processed_array = $url_array;
	
	$count=0;
	foreach($processed_array as $key => $val)
	{
		//echo $val["description"].'<br>';
		
		//make all lower case (case folding)
		$val["description"] = strtolower($val["description"]);		

		$processed_array[$key]["description"] = $val["description"];

		//keep original key, will need original placement in $url_array when
		//displaying the relevant descriptions in each cluster
		$val["original_key"] = $count;
		$processed_array[$key]["original_key"] = $val["original_key"];

		$val["description"] = preg_split('/'.$terms.'/i',$val["description"],NULL,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		
		//remove stop words from the description
		foreach($val["description"] as $key2=>$val2)
		{
			foreach($stop_words as $stop)
			{
				if($val2 == $stop)
				{
					unset($val["description"][$key2]);
				}
			}
		}
		//this updates new indexes from deleted elements
		$val["description"] = array_values($val["description"]);
		
		foreach($val["description"] as $temp)
		{
			//check if word is already in dictionary
			if(in_array($temp,$collection_word_array)==False) 
			{
				//add new word to 'bag of words'
				$collection_word_array[] = $temp;
			}
		}
		//need to update changes (else, goes out of scope)
		$processed_array[$key] = $val;
		
		$count++;
	}
	$total_collection_words = count($collection_word_array);
	
	return $processed_array;
}

/////////////////////////////////
/// CALCULATE TERM FREQUENCY
/////////////////////////////////

function TERM_FREQUENCY($processed_array)
{
	//array to hold document frequency of a word
	global $df;
	
	//bag of words
	global $collection_word_array;
	
	global $total_collection_words;
	
	foreach($processed_array as $url_key => $val)
	{
		//initialize vector array to be the same size as the bag of words
		$val["term_frequency"] = array_fill(0,$total_collection_words,0);
		
		////echo '<br>COUNT STUFF<br>';
		$count = array_count_values($val["description"]);
		////print_r($count);
		$total_document_words = count($val["description"]);
		
		$total_words = 0;	   //initialize
		$euclidean_length = 0; //initialize
		
		foreach($count as $val2)
		{
			$total_words = $total_words + ($val2 * $val2);
		}
		$euclidean_length = sqrt($total_words);
		
		////echo 'euclidean_length = '.$euclidean_length.'<br>';
		
		foreach($collection_word_array as $key => $word)
		{
			if(in_array($word,$val["description"])==True)
			{
				//normalize the tf by dividing by euclidean length (snippets can be of different sizes)
				$val["term_frequency"][$key] = ($count[$word]/$euclidean_length);
				
				if(array_key_exists($word,$df)==False)
				{
					//initialize
					$df[$word] = 0;
				}
				//this will be the 'df' value when calculating tf-idf
				$df[$word]++;
			}
			else
			{
				$val["term_frequency"][$key] = 0;
			}
		}
		$processed_array[$url_key] = $val; //need to made change (else, goes out of scope)
	}
	return $processed_array;
}

function TF_IDF_WEIGHTS($processed_array)
{
	global $collection_word_array;
	global $df;
	global $N;

	foreach($processed_array as $key => $val)
	{
		$temp_array = array();

		foreach($val["term_frequency"] as $key2 => $TF)
		{
			$word = $collection_word_array[$key2];
			
			////echo 'ZE WORD = '.$word.'<br>';
			
			$df_t = $df[$word]; //document frequency of term
			
			$TF_IDF = $TF * log(($N/$df_t),10); //base 10 used
			
			////echo 'TF-IDF = '.$TF.'*log('.$N.'/'.$df_t.')<br>';
			
			$temp_array[] = $TF_IDF; //append the TF_IDF weight to the temp array
		}
		$val["tf_idf"] = $temp_array;

		$processed_array[$key] = $val; //don't allow to go out of scope
	}
	return $processed_array;
}

function INITIAL_TWO_SEEDS_SELECTION($processed_array)
{
	global $seeds;
	global $seed_index;
	
	//choose a random number
	$seed_index[0] = rand(0,$N-1);
	
	//echo 'SEED INDEX: '.$seed_index[0].'<br><br>';
	
	//let this doc be the first seed
	$seeds[0] = $processed_array[$seed_index[0]]["tf_idf"];
	
	//index of doc which is furthest from first seed
	$furthest_doc = 0;
	
	//keep track of furthest distance (using 10 instead of 0)
	$temp_dist = 10000;
	
	//choose second seed as doc furthest away from first seed
	foreach($processed_array as $key => $doc)
	{
		//keep track of each doc's distance from initial seed
		$product = 0;
		
		//don't bother evaluating first seed
		if($key == $seed_index)
		{
			continue; 
		}
		foreach($doc["tf_idf"] as $key2 => $number)
		{
			$product = $product + ($seeds[0][$key2] * $doc["tf_idf"][$key2]);
		}
		//remember: '<' used for furthest, not closest ('least similar')
		//if product == 0, found a completely different doc so leave loop
		if($product == 0)
		{
			$furthest_doc = $key;
			break;
		}
		else if($product < $temp_dist)
		{
			$temp_dist = $product;
			$furthest_doc = $key;
		}
	}
	
	//append the second seed to the seeds array
	$seeds[] = $processed_array[$furthest_doc]["tf_idf"];
	$seed_index[] = $furthest_doc;
}

//for additional seeds, compute the average distance of each doc
//from all the pre-existing seeds, and then choose the one with the 
//furthest average distance to make sure seeds are not close together
//close docs happened with my previous approach and produced poor results
	
function REST_OF_SEEDS_SECTION($processed_array)
{
	global $k;
	global $seeds;
	global $seed_index;
	
	//keep gathering $c amount of seeds until I reach pre-selected $k
	//amount of clusters
	for($c = 2; $c < $k ; $c++)
	{
		//highest average distance from all seeds
		$highest_average_distance = 1000000000;
	
		//look through each document
		foreach($processed_array as $key => $doc)
		{
			//keep track of total distance from each seed
			$total_doc_distance = 0;
			
			//calculate distance from each seed
			foreach($seeds as $key2 => $seed)
			{
				foreach($seed as $key3 => $seed_val)
				{
					$total_doc_distance += ($seed_val * $doc["tf_idf"][$key3]);
				}
			}
			//find average doc distance by dividing the total distances
			//from each seed by the total number of seeds
			//echo 'AVERAGE DOC DISTANCE = '.$average_doc_distance.'<br>';
			$average_doc_distance = $total_doc_distance/count($seeds);
			
			//note to self: '<' used
			if($average_doc_distance !=0 and $average_doc_distance < $highest_average_distance)
			{
				$highest_average_distance = $average_doc_distance;
				$furthest_doc = $key;
			}
		}
		//append newly found seed to seeds array
		$seeds[] = $processed_array[$furthest_doc]["tf_idf"];
		$seed_index[] = $furthest_doc;
		
		//echo '<br><br>SEEDS = '.count($seeds).'<br>';
	}
}

function FILL_INITIAL_CENROIDS($processed_array)
{
	global $clusters;
	global $centroids;
	global $k;
	global $seeds;
	global $seed_index;

	for($i=0 ; $i < $k ; $i++)
	{
		//initial centroids will be the first seeds
		$centroids[$i] = $seeds[$i];
	}
	
	for($i=0 ; $i<$k ;$i++)
	{
		//initialize each cluster to hold array depending on size
		$clusters[$i] = array();
		
		//the seeds will also be the first docs in each cluster
		array_push($clusters[$i],$processed_array[$seed_index[$i]]);
	}
}

function K_MEANS($processed_array)
{
	global $iterations;
	global $centroids;
	global $clusters;

	//this variable ($previous_RSS_value) is compared to the RSS value before it
	//if the new RSS value is not less than the previous one,
	//convergence has occured and so I leave the loop before 
	//the rest of the iterations take place
	//I chose '1M' to be extra safe as the first RSS value should always
	//be lower than this number to work
	$previous_RSS_value = 1000000; 
	
	for($temp_index=0 ; $temp_index<$iterations ; $temp_index++)
	{
		//echo '<br><br>ITERATION '.$temp_index.'<br><br>';
		$closest_cluster = 0; //initialize
		
		foreach($processed_array as $doc)
		{	
			if(in_array($doc["tf_idf"],$centroids) and $temp_index == 0) //if current doc is a seed, continue
			{
				continue;
			}
			$temp_dist = 0; //distance calculated to each centroid
			foreach($centroids as $centroid_key => $val)
			{
				$product = 0;
				foreach($val as $key2 => $number)
				{
					$product = $product + ($val[$key2] * $doc["tf_idf"][$key2]);
				}
				if($product > $temp_dist)
				{
					$temp_dist = $product;
					$closest_cluster = $centroid_key;
				}
				
			}
			array_push($clusters[$closest_cluster] , $doc);
		}
	
		//get new RSS value
		$RSS = RSS();
		//echo '<br><br>RSS = '.$RSS.'<br><br>';
		
		if($RSS == $previous_RSS_value)
		{
			//convergence has occured
			//echo '<br><br>CONVERGENCE at '.$temp_index.'<br><br>';
			break;
		}
		else
		{
			//else, update previous RSS value
			$previous_RSS_value = $RSS;
		}
		
		RE_CALCULATE_CENTROIDS($temp_index);
	}
}

///////////////////////////////////////////////
///// RE-CALCULATE CENTROID
///////////////////////////////////////////////

function RE_CALCULATE_CENTROIDS($temp_index)
{
	global $centroids;
	global $clusters;
	global $iterations;
	global $k;

	//reset centroid values
	foreach($centroids as $key => $val)
	{
		foreach($val as $key2 => $number)
		{
			$centroids[$key][$key2] = 0; //reset to 0
		}
	}
	
	//get summation
	foreach($clusters as $cluster_key => $member)
	{
		foreach($member as $key2 => $val)
		{
			foreach($val["tf_idf"] as $number_key => $number)
			{
				$centroids[$cluster_key][$number_key] += $number;
			}
		}
	}
		
	foreach($centroids as $key2 => $values)
	{
		$c = count($clusters[$key2]); //the number of docs in each cluster
				
		foreach($values as $number_key => $number)
		{
			$values[$number_key] /= $c;
		}
		$centroids[$key2] = $values;
	}
		
	if($temp_index < ($iterations - 1))//don't empty clusters on last iteration
	{
		//reset clusters array
		$clusters = array();
		
		for($i=0 ; $i<$k ;$i++) 
		{
			//empty clusters
			$clusters[$i] = array();
		}
	}
}

function DISPLAY_CLUSTERS()
{
	global $clusters;
	global $url_array;
	
	echo '<div class="cluster_results">';
	
	$count = 1;
	$c = 1;
	foreach($clusters as $key => $member)
	{
		//put in anchor for reference
		echo '<a name = "cluster_'.$c.'"></a>';
		
		echo '---------------------------------'.'<br>';
		echo 'CLUSTER '.$c.'<br>';
		echo '---------------------------------'.'<br>';

		
		foreach($member as $key2 => $val)
		{
			echo $count.'<br>';
			////print_r($val["tf_idf"]);
			//echo $val["weight"].'<br>';
			echo('<a href="'.$val["url"].'">'.$val["title"].'</a>'.'<br>');
			//echo $val["title"].'<br>';
			
			//display un-processed description from $url_array
			echo $url_array[$val["original_key"]]["description"].'<br>';
			
			echo $val["url"].'<br><br>';
			
			//print_r($val["description"]);
			//print_r(implode($val["description"],' '));
			////print_r($val["term_frequency"]);
			//echo '<br>';
			$count++;
		}
		$c++;
	}
	
	echo '</div>';
}

//////////////////////////
/// MAIN CLUSTER FUNCTION
//////////////////////////

function CLUSTER()
{
	global $url_array;

	//get bag of words
	$processed_array = BAG_OF_WORDS($url_array);
	
	//adds "term_frequency" var to each document in the array and 
	//updates global document frequency array ($df)
	$processed_array = TERM_FREQUENCY($processed_array);
	
	//adds "tf_idf" var to each document in the array
	$processed_array = TF_IDF_WEIGHTS($processed_array);
	
	//get intial two furthest seeds
	INITIAL_TWO_SEEDS_SELECTION($processed_array);
	
	//get the rest of the required seeds
	REST_OF_SEEDS_SECTION($processed_array);
	
	//set docs of initial seeds as intial centroids
	FILL_INITIAL_CENROIDS($processed_array);
	
	//begin clustering using k-means
	K_MEANS($processed_array);
	
	//display clusters to user
	DISPLAY_CLUSTERS();
	
	//get the top scoring words in each cluster
	$top_words = TOP_WORDS();
	
	//use mutual information as feature selection
	MI($top_words);
}

//////////////////////////////////

CLUSTER(); //call main cluster function

//////////////////////////////////

?>