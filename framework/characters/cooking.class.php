<?php
class app_cooking extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	private $max;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the cooking skill */
		$this->skill = $bnet_data->professions->secondary[3]->rank;
		$this->max = $bnet_data->professions->secondary[3]->max;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[3]->icon;
	
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
		$percentage = ($this->skill / $this->max)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
	public function getRecipes() {
		
		
		
	}	
}

class char_cooking extends character {
	
	/* Variables */
	public $skill;
	private $icon;
	private $max;
	private $recipes;	
	
	/* Construction function */
	public function __construct($character_id) {
		
		/* Construct an instance of the character */
		$character = new character($character_id);
		
		/* Get the battle.net data */
		$bnet_data = $character->getBattleNetData();
		
		/* Set the cooking skill */
		$this->skill = $bnet_data->professions->secondary[3]->rank;
		$this->max = $bnet_data->professions->secondary[3]->max;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[3]->icon;
		
		/* Set the recipes */
		$this->recipes = $bnet_data->professions->secondary[3]->recipes;
		
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
		$percentage = ($this->skill / $this->max)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
	/* Has recipes */
	public function hasRecipes() {
		
		if(count($this->recipes) > 0) {
		
			return true;
		}
		
		return false;		
	}
	
	/* Get the recipes */
	public function getRecipes() {
		
		return $this->recipes;
		
	}
	
}
?>