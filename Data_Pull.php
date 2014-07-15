<?

include_once 'inc/connection.php';   /* DB Connection */
$Time_Check = mysqli_query($con,"SELECT `DataAsOf` FROM Traffic_Speed ORDER BY `DataAsOf` DESC LIMIT 1");
$Timed = mysqli_fetch_array($Time_Check);

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

$Data_Rows = explode("\n", $Raw_NYC_Traffic_Speed);
unset($Data_Rows[0]);
foreach ($Data_Rows as $New_Traffic_Data) {
	if($New_Traffic_Data) {

		$Individual_Columns = str_getcsv($New_Traffic_Data, "	", "\""); /* Breaks down data into individual columns separated by tabs between data in quotation. */
		
		if(($Individual_Columns[4] > $Timed['DataAsOf'])) { /* find out which ID is unique and add an extra check! */
			mysqli_query($con,"INSERT INTO Traffic_Speed (Id, Speed, TravelTime, Status, DataAsOf, linkId, linkPoints, EncodedPolyLine, EncodedPolyLineLvls, Owner, Transcom_id, Borough, linkName) VALUES ($Individual_Columns[0], $Individual_Columns[1], $Individual_Columns[2], $Individual_Columns[3], '$Individual_Columns[4]', $Individual_Columns[5], '$Individual_Columns[6]', '$Individual_Columns[7]', '$Individual_Columns[8]', '$Individual_Columns[9]', $Individual_Columns[10], '$Individual_Columns[11]', '$Individual_Columns[12]')"); /* INSERT New data into the DB. */			
		}
	}
}

?>