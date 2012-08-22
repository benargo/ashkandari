<?php
class app_first_aid extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the fishing skill */
		$this->skill = $bnet_data->professions->secondary[0]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[0]->icon;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}

class char_first_aid extends character {
	
	/* Variables */
	public $skill;
	private $icon;
	
	/* Construction function */
	public function __construct($character_id) {
		
		/* Construct an instance of the character */
		$character = new character($character_id);
		
		/* Get the battle.net data */
		$bnet_data = $character->getBattleNetData();
		
		/* Set the fishing skill */
		$this->skill = $bnet_data->professions->secondary[0]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[0]->icon;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}
?>