<?php /* Country class */

/* Geolocate user */
function geolocate() {
	
	/* Get the API key */
	require(PATH.'framework/country_api_key.php');
	
	/* Locate their country using their IP address */
	$json = file_get_contents("http://api.ipinfodb.com/v3/ip-country/?key=$key&ip=". $_SERVER['REMOTE_ADDR'] ."&format=json");
	
	/* Decode the returned JSON */
	$data = json_decode($json);
	
	/* Create a new country */
	$country = new country($data->countryCode);
	
}

class country {
	
	/* Variables */
	public $id;
	public $name;
	private $eu;
	
	/* Construction function */
	public function __construct($id) {
		
		/* Open a database connection */
		$db = db();
		
		/* Get the country from the database */
		$result = $db->query("SELECT * FROM `countries` WHERE `id` = '$id' LIMIT 0, 1");
		
		/* Fetch an object */
		$country = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Close the database connection */
		$db->close();
		
		/* Set variables */
		$this->id = $country->id;
		$this->name = $country->name;
		$this->eu = $country->eu;
		
	}
	
}


?>