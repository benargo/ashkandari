<?php
class app_profession extends application {
	
	/* Variables */
	public $name;
	private $icon;
	public $skill;
	private $max;
	
	/* Construction function */
	public function __construct($application_id, $position = 0) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Get their profession based on the position */
		$profession = $bnet_data->professions->primary[$position];
		
		/* Set the variables */
		$this->name = $profession->name;
		$this->icon = $profession->icon;
		$this->skill = $profession->rank;
		$this->max = $profession->max;
		
		/* And return true */
		return true;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / $this->max)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}

class char_profession extends character {
	
	/* Variables */
	public $name;
	private $icon;
	public $skill;
	private $max;
	private $recipes;
	
	/* Construction function */
	public function __construct($character_id, $position = 0) {
		
		/* Construct an instance of the character */
		$character = new character($character_id);
		
		/* Get the battle.net data */
		$bnet_data = $character->getBattleNetData();
		
		/* Get their profession based on the position */
		$profession = $bnet_data->professions->primary[$position];
		
		/* Set the variables */
		$this->name = $profession->name;
		$this->icon = $profession->icon;
		$this->skill = $profession->rank;
		$this->max = $profession->max;
		$this->recipes = $profession->recipes;
		
		/* And return true */
		return true;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
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
	public function getRecipies() {
		
		return $this->recipes;
		
	}
	
}
?>