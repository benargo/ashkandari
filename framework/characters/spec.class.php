<?php
class spec extends application {
	
	/* Variables */
	public $name;
	private $icon;
	
	/* Construction function */
	public function __construct($application_id, $spec_id) {
		
		/* Create an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Get the spec we're after */
		$spec = $bnet_data->talents[$spec_id];
		
		/* Start setting variables */
		$this->name = $spec->name;
		$this->icon = $spec->icon;
		
	}
	
	/* Get Icon */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
}
?>