<html>

	<head>
	
		<!-- needed UTF-8 encoding -->
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	
		<!-- include external css stylesheet -->
		<link rel="stylesheet" type="text/css" href="style_sheet.css" />
	
		<div class="main_headers" style="color: red"><h1><b>BRAHMS</h1> META-SEARCH ENGINE</b></div>
		
	</head>
	
	<body>
	
	<?php include('header.php'); ?>
	
	<div class="main_wrapper">
	
	<div class="query_form">
	
	<form method="POST">
		<?php include('advanced_options.php'); ?>
	
		<?php include('search_menu.php'); ?>
	</form>
	
	<!--
	<div class="query_form">

		this will invoke the query preprocessor, 'preprocessor.php'
		
		<form method="POST">
			AGGREGATED: <input type="radio" name="aggregation_choice" value="aggregated" />
			NON-AGGREGATED: <input type="radio" name="aggregation_choice" value="non_aggregated"/>
			CLUSTERED: <input type="radio" name="aggregation_choice" value="clustered"/>
			
			<br>
			
			<input type="text" name="query" size="100"/>
			<input type="submit" name="submit" value="TI search"/>
		</form>
	-->
		
		<?php
		
		if(isset($_POST['submit']))
		{
			include('preprocessor.php'); //run query through preprocessor
			
			echo '<div style="width: 100%">';
			echo 'Bing Query = '.$bing_query.'<br>';
			echo 'Blekko Query = '.$blekko_query.'<br>';
			echo 'Entireweb Query = '.$entireweb_query.'<br>';
			echo '</div>';
			
			$choice = $_POST['aggregation_choice'];
			
			if(isset($choice))
			{
				if($choice == 'aggregated')
				{
				 	include('display_aggregated_results.php');
				}
				else if($choice == 'non_aggregated')
				{
					include('display_non_aggregated.php');
				}
				else if($choice == 'clustered')
				{
					include('cluster.php');
				}
			}
			else
			{
				//else display a javascript error pop-up box
				echo '<script type="text/javascript">
						alert("You did not choose whether you wanted an aggregated/non-aggregated search result" + "\n\n" +
						"\t\t\t\t\t\t   Please choose one");
					 </script>';
			}
		}
		
		?>
		
	</div>
	</div>
	
	<?php include('footer.php'); ?>
	
	</body>

</html>