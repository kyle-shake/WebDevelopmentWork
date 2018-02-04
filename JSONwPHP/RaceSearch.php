<?php
error_reporting(E_ALL);
/*
File: RaceSearch.php
Author: Kyle Shake
Date Created: 3/30
Last Modified: 3/30
Purpose: Script is a basic web application that will create a dynamic search form. 
Input: User will select from drop down select fields and one search field
	--Which race to search
	--Which demographic to limit candidates by
	--The value of above demographic (Search Field)
	--The statistic to find
	--The minimum or maximum of specified statistic
Output: A runner and their statistics based on the user decided criterion
*/

#Check if raceToSearch has been set first
#	--if yes, process form
#	--if not, display form
if (isset($_GET['raceToSearch'])){
	process_form();
} else {
	display_form();
}


/*
Function retrieves which race specified by user from website, determines
	if the race is valid. 
If race is not valid, function returns error message to user.
If race is valid, function retrieves array of runners for specified race from
JSON file. Sends aforementioned array to find_runner function.

After find_runner function returns, this function prints the results of search 

*/
function process_form(){
	$raceSelected = $_GET['raceToSearch'];
	$RacesArray = getRaces();
	if(in_array($raceSelected, $RacesArray, false)){
		$JSONObj = file_get_contents($raceSelected);
		$JSONArray = json_decode($JSONObj, true); 

#Debug		var_dump($JSONArray);

	}else{
		echo "<h1 style='color:red; text-align:center;'>That is not a valid race!!</h1>";
		return;
	}

	$RunnerArray = $JSONArray['runners'];

#Debug	var_dump($RunnerArray);

	do_header_found();
	echo "Searching $raceSelected for results...<br>";
	$Runner = find_runner($RunnerArray);

	#PRINTING RESULTS OF SEARCH
	foreach($Runner as $key=>$value){
		echo "$key: $value <br>";
	}
	
}
/*
Function retrieves remaining field values from website, then using
user specified criterion searches array passed into it for a runner
that matches the criteria

Param: $RArray is the array of runners from specified race

Returns an array with all of a runner's information

*/
function find_runner($RArray){
	#Confirming field value is set before assignment
	if(isset($_GET['whatToMatch'])){
		$w2match = $_GET['whatToMatch'];
	}
	if(isset($_GET['matchValue'])){
		$matchval = $_GET['matchValue'];
	}
	if(isset($_GET['statToFind'])){
		$stat = $_GET['statToFind'];
	}
	if(isset($_GET['maxOrmin'])){
		$maxormin = $_GET['maxOrmin'];
		if(($maxormin !== "Max") && ($maxormin !== "Min")){
			$maxormin = "Min";  #DEFAULT TO MINIMUM
#DEBUG			echo "You must select a max or minimum value!<br>";
		}
	}
#DEBUG	var_dump($matchValue);

	#Verifying that whatToMatch field is valid
	#If not, DEFAULT IS NOTHING and set matchval to NULL
	$MatchesArray = getMatches();
	if(!in_array($w2match, $MatchesArray, false)){
		$w2match = "Nothing";
		$matchval = NULL;
	}

	#If whatToMatch field is 'Nothing', set matchval to NULL
	if($w2match == "Nothing"){
		$matchval = NULL;
        }

	#Verifying that statToFind field is valid
	#If not, DEFAULT IS FINISHTIME
	$StatsArray = getStats();
	if(!in_array($stat, $StatsArray, false)){
		$stat = "FinishTime";
	}

	#Initializing the Runner array that we will return
	$Runner2Return = array( 'Place' => NULL,
				'Name' => NULL,
				'Gender' => NULL,
				'Hometown' => NULL,
				'FinishTime' => NULL,
				'Pace' => NULL,
				'Age' => NULL,
				'AgePlace' => NULL,
				'AgeCategory' => NULL,
				);

	#These two loops allow function to iterate through the runners
	foreach($RArray as $subarray){
	foreach($subarray as $key=>$value){

#Debug		echo "This is the $key and this is the $value";##############
		
		if($key == $w2match || $w2match == "Nothing"){
		if($value == $matchval || strpos($value, $matchval)!== false || $matchval == NULL){

			#if Runner not yet set
			if($Runner2Return[$stat] == NULL){
				$Runner2Return = $subarray;
			}

			if($maxormin == 'Max'){
				if(($stat == 'Pace') || ($stat == 'FinishTime')){
					if($subarray[$stat] == ""){ break;} #if the finishtime or pace missing
					if(convTimeToSeconds($Runner2Return[$stat]) < 
					convTimeToSeconds($subarray[$stat])){
						$Runner2Return = $subarray;
					}					
				}else{	
					if($Runner2Return[$stat] < $subarray[$stat]){
						$Runner2Return = $subarray;
					}
				}
			}
			if($maxormin == 'Min'){
				if($stat == 'Pace' || $stat == 'FinishTime'){
					if($subarray[$stat] == ""){ break;} #if the finishtime or pace missing

					if(convTimeToSeconds($Runner2Return[$stat]) > 
					convTimeToSeconds($subarray[$stat])){
						$Runner2Return = $subarray;
					}					
				}else{	
					if($Runner2Return[$stat] > $subarray[$stat]){
						$Runner2Return = $subarray;
					}
				}
			}
		}	
		}			
	}		
	}
	echo "
		Using search criteria:<br>
		$w2match: $matchval <br>
		$maxormin $stat <br><br><br>
	";
	return $Runner2Return;
}

/*
Function converts a time of a specific form to the number of
total seconds

Param: $Time is a time in the form of HH:MM:SS.FractionalS

Returns the passed in time in the form of total seconds

*/
function convTimeToSeconds($Time){
#DEBUG	var_dump($Time);
	$rc = strpos($Time, ".");
	if ($rc == false){
		$newstring = $Time;
	}else{
		$newstring = substr($Time, 0, $rc);
	}
	$tarray = explode(":", $newstring);
	if(count($tarray) > 3 || count($tarray) < 2){
		print "Error on string conversion: ".$newstring."\n";
		return -1;
	}
	if(count($tarray) == 3){
		$seconds = $tarray[0]*3600 + $tarray[1]*60 + $tarray[2];
		return $seconds;
	}
	if(count($tarray) == 2){
		$seconds = $tarray[0]*60 + $tarray[1];
		return $seconds;
	}
}

#Function provides header for webpage before search has been submitted
function do_header_search(){
	echo"
		<html>
		<title> Race Search </title>
		<h1> Search: </h1>
	";
}

#Function provides header for webpage after search has been submitted
function do_header_found(){
	echo"
		<html>
		<title> Race Search </title>
		<h1> Search Results: </h1>
	";
}

/*
Function creates and dynamically populates the select fields, creates the search bar, and the layout for the
search form.


*/
function display_form(){
	do_header_search();
?>
	<form action="RaceSearch.php" method="get">
        	Races:
		<select name='raceToSearch'>
		<?php
		$racesArray = getRaces();
		foreach($racesArray as $raceNames=>$races){
			echo "<option value='".$races."'>".$raceNames."</option>";
		}
		?></select><br><br>

		Demographic:
		<select name='whatToMatch'>
		<?php
		$matchArray = getMatches();
		foreach($matchArray as $index=>$demographic){
			echo "<option value='".$demographic."'>".$demographic."</option>";
		}
		?></select><br><br>

		Search: <input type="text" name="matchValue"><br><br>

		Statistic to Find:
		<select name='statToFind'>
		<?php
		$statsArray = getStats();
		foreach($statsArray as $index=>$statistic){
			echo "<option value='".$statistic."'>".$statistic."</option>";
		}
		?></select><br><br>

		Maximum or Minimum:<select name='maxOrmin'>
			<option value='Max'>Max</option>
			<option value='Min'>Min</option>
		</select><br><br>
		<input type="submit" value="Submit"><br>
	</form>
<?php 
	end_html();
}

#Simple function to provide closing html tags
function end_html(){
	echo"
		</body>
		</html>
	";
}

#Function retrieves the array of races from Races.json file
function getRaces(){
	$JSONObj = file_get_contents("Races.json");
	$JSONArray = json_decode($JSONObj, true);
	$RacesArray;
#	var_dump($JSONArray);
	$RacesArray = $JSONArray['races'];
	return $RacesArray;
}

#Function retrieves the array of demographics from Races.json file
function getMatches(){
	$JSONObj = file_get_contents("Races.json");
	$JSONArray = json_decode($JSONObj, true);
	$MatchesArray;
#	var_dump($JSONArray);
	$MatchesArray = $JSONArray['match'];
	return $MatchesArray;
}

#Function retrieves the array of statistics from Races.json file
function getStats(){
	$JSONObj = file_get_contents("Races.json");
	$JSONArray = json_decode($JSONObj, true);
	$StatsArray;
#	var_dump($JSONArray);
	$StatsArray = $JSONArray['stats'];
	return $StatsArray;
}

