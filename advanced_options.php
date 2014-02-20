<?php

echo '<div class="advanced_options" style="padding-left: 39%; text-align: center; float: left">';

echo '<b>Advanced Search Options</b>';

	echo '<br>';
	
	echo '<div style="text-align: center; float: left">';
	
	echo '<div style="float: left">';
	echo 'Case Folding:';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 7.7em">';
	echo 'On<input type="radio" name="Case_Folding" value="Case_Folding_On"/>';
	echo 'Off<input type="radio" name="Case_Folding" value="Case_Folding_Off" checked="yes"/>';
	echo '</div>';

	echo '<br>';
	
	echo '<div style="float: left">';
	echo 'Stop Word Removal:';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 5em">';
	echo 'On<input type="radio" name="Stop_Words" value="Stop_Words_On"/>';
	echo 'Off<input type="radio" name="Stop_Words" value="Stop_Words_Off" checked="yes"/>';
	echo '</div>';

	echo '<br>';

	echo '<div style="float: left">';
	echo 'Spelling Corrections:';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 5em">';
	echo 'On<input type="radio" name="Spelling_Corrections" value="Spelling_Corrections_On"/>';
	echo 'Off<input type="radio" name="Spelling_Corrections" value="Spelling_Corrections_Off" checked="yes"/>';
	echo '</div>';

	echo '<br>';
	
	echo '<div style="float: left;">';
	echo 'Synonyms: ';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 9em">';
	echo 'On<input type="radio" name="Synonyms" value="Synonyms_On"/>';
	echo 'Off<input type="radio" name="Synonyms" value="Synonyms_Off" checked="yes"/>';
	echo '</div>';
	
	echo '<br>';
	
	echo '<div style="float: left;">';
	echo 'Stemming: ';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 9em">';
	echo 'On<input type="radio" name="Stemming" value="Stemming_On"/>';
	echo 'Off<input type="radio" name="Stemming" value="Stemming_Off" checked="yes"/>';
	echo '</div>';
	
	echo '<br>';
	
	echo '<div style="float: left;">';
	echo 'Hyphen Expansion: ';
	echo '</div>';
	
	echo '<div style="float: left; text-indent: 5.5em">';
	echo 'On<input type="radio" name="Hyphen_Expansion" value="Hyphen_Expansion_On"/>';
	echo 'Off<input type="radio" name="Hyphen_Expansion" value="Hyphen_Expansion_Off" checked="yes"/>';
	echo '</div>';
	
	echo '</div>';
	
echo '</div>';


?>