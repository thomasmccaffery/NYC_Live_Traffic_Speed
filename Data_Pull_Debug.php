<?

include_once 'inc/connection.php';   /* DB Connection */
$Time_Check = mysqli_query($con,"SELECT `DataAsOf` FROM Traffic_Speed ORDER BY `DataAsOf` DESC LIMIT 1");
$Timed = mysqli_fetch_array($Time_Check); /* Most Recent Date Imported to the DB. */
$Date_Today = date("Y-m-d"); /* Today's Date. */

/* Pull Data From URL */
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$Raw_NYC_Traffic_Speed = get_data('http://207.251.86.229/nyc-links-cams/LinkSpeedQuery.txt');

/*print_r($Raw_NYC_Traffic_Speed);*/ /* Prints the entire txt file, for testing purposes. */

$Raw_NYC_Traffic_Speed = preg_replace('#\\n(?=[^"]*"[^"]*(?:"[^"]*"[^"]*)*$)#' , ' ', $Raw_NYC_Traffic_Speed); /* Removes Line-Breaks '\n' from raw feed, which splits some rows when imported due to poor API Feed Programming by NYCDOT */

$Data_Rows = explode("\n", $Raw_NYC_Traffic_Speed); /* Splits Feed into rows for each speed entry. */
unset($Data_Rows[0]); /* Removes the Feed Data Headers */

foreach ($Data_Rows as $New_Traffic_Data) {
	if($New_Traffic_Data) {
		// echo "$New_Traffic_Data <br>===========<br>"; /* Outputs Raw Row Data for each new entry. */

		$Individual_Columns = str_getcsv($New_Traffic_Data, "	", "\""); /* Breaks down data into individual columns separated by tabs between data in quotation. */		
		$timestamp = strtotime($Individual_Columns[4]);
		$timestamp = date("Y-m-d H:i:s", $timestamp); /* Convert Date & Time to TIMEDATE for MySQL ordering. */
		
		if(($Individual_Columns) && ($Date_Today<=$timestamp) && ($timestamp > $Timed['DataAsOf']) && ($Individual_Columns[3]==0)) { /* find out which ID is unique and add an extra check! */
			foreach ($Individual_Columns as $Data_Columns) {
				// echo $Data_Columns." | "; /* Outputs Each piece of data separated by a line for visual testing. */
			}
			echo $timestamp; /* Outputs Data of current row. (Used later to check if in DB) */
			echo ":::";
			echo $Timed['DataAsOf']; /* Outputs Most recent time which loop is checked against. */
			
			mysqli_query($con,"INSERT INTO Traffic_Speed (Id, Speed, TravelTime, Status, DataAsOf, linkId, linkPoints, EncodedPolyLine, EncodedPolyLineLvls, Owner, Transcom_id, Borough, linkName) VALUES ($Individual_Columns[0], $Individual_Columns[1], $Individual_Columns[2], $Individual_Columns[3], '$timestamp', $Individual_Columns[5], '$Individual_Columns[6]', '$Individual_Columns[7]', '$Individual_Columns[8]', '$Individual_Columns[9]', $Individual_Columns[10], '$Individual_Columns[11]', '$Individual_Columns[12]')"); /* INSERT New data into the DB. */
			
			echo "<br/>----- New Entry -----<br/>";
		}
	}
}

?>