<?php

echo '<div class="button_options">';

	echo '<div class="button_options_inner_wrapper">';
	
			echo '<b>Aggregation and Cluster Options</b>';

			echo '<br>';
	
			echo 'Aggregated: <input type="radio" name="aggregation_choice" value="aggregated" />';
			echo 'Non-aggregated: <input type="radio" name="aggregation_choice" value="non_aggregated"/>';
			echo 'Clustered: <input type="radio" name="aggregation_choice" value="clustered"/>';
	/*
		echo '<input type="submit" class="button" value="aggregated" name="aggregation_choice"/>';
		echo '    <input type="submit" class="button" value="non-aggregated" name="aggregation_choice"/>';
		echo '    <input type="submit" class="expansion_button" value="query expansion" name="aggregation_choice"/>';
		echo '<br>';
		echo '<input type="submit" class="button_cluster" value="clustered" name="aggregation_choice"/>';
		echo '    <input type="submit" class="button_cluster" value="non-clustered" name="aggregation_choice"/>';
	*/
	echo '</div>';
echo '</div>';

echo '<div class="search_bar_wrapper">';
	echo '<div class="search_bar_inner_wrapper">';
		echo '<input class="search_bar" type="text" name="query"/>';
	echo '</div>';
echo '</div>';

echo '<div class="search_button_wrapper">';
	echo '<input class="search_button" type="submit" name="submit" value="Go!"/>';
echo '</div>';

?>
