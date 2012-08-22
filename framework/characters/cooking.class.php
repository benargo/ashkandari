<?php
class app_cooking extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	private $grill;
	private $great_grill;
	private $oven;
	private $great_oven;
	private $pot;
	private $great_pot;
	private $steamer;
	private $great_steamer;
	private $wok;
	private $great_wok;
	private $pandaren;
	private $great_pandaren;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the cooking skill */
		$this->skill = $bnet_data->professions->secondary[3]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[3]->icon;
	
		/* Get the array of recipes */
		$recipes = $bnet_data->professions->secondary[3]->recipes;
	
		/* Now check if they can make each of the banquets */
		/* Banquet of the Grill */
		if(in_array(125141, $recipes)) {
			
			$this->grill = 125141;
			
		}
		
		/* Great Banquet of the Grill */
		if(in_array(125142, $recipes)) {
			
			$this->great_grill = 125142;
			
		}
		
		/* Banquet of the Oven */
		if(in_array(125600, $recipes)) {
			
			$this->oven = 125600;
			
		}
		
		/* Great Banquet of the Oven */
		if(in_array(125601, $recipes)) {
			
			$this->great_oven = 125601;
			
		}
		
		/* Banquet of the Pot */
		if(in_array(125596, $recipes)) {
			
			$this->pot = 125596;
			
		}
		
		/* Great Banquet of the Pot */
		if(in_array(125597, $recipes)) {
			
			$this->great_pot = 125597;
			
		}
		
		/* Banquet of the Steamer */
		if(in_array(125598, $recipes)) {
			
			$this->steamer = 125598;
			
		}
		
		/* Great Banquet of the Steamer */
		if(in_array(125599, $recipes)) {
			
			$this->great_steamer = 125599;
			
		}
		
		/* Banquet of the Wok */
		if(in_array(125594, $recipes)) {
			
			$this->wok = 125594;
			
		}
		
		/* Great Banquet of the Wok */
		if(in_array(125595, $recipes)) {
			
			$this->great_wok = 125595;
			
		}
		
		/* Pandaren Banquet */
		if(in_array(105190, $recipes)) {
			
			$this->pandaren = 105190;
			
		}
		
		/* Great Pandaren Banquet */
		if(in_array(105194, $recipes)) {
			
			$this->great_pandaren = 105194;
			
		}
		
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
	
	/* Check if they have a banquet */
	public function hasBanquet($banquet_name) {
		
		/* If they do have this banquet */
		if(isset($this->$banquet_name)) {
			
			/* Return the banquet recipe ID */
			return $this->$banquet_name;
			
		}
		
		/* No feast, return false */
		return false;
		
	}
	
}

class char_cooking extends character {
	
	/* Variables */
	public $skill;
	private $icon;
	private $grill;
	private $great_grill;
	private $oven;
	private $great_oven;
	private $pot;
	private $great_pot;
	private $steamer;
	private $great_steamer;
	private $wok;
	private $great_wok;
	private $pandaren;
	private $great_pandaren;
	
	/* Construction function */
	public function __construct($character_id) {
		
		/* Construct an instance of the character */
		$character = new character($character_id);
		
		/* Get the battle.net data */
		$bnet_data = $character->getBattleNetData();
		
		/* Set the cooking skill */
		$this->skill = $bnet_data->professions->secondary[3]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[3]->icon;
	
		/* Get the array of recipes */
		$recipes = $bnet_data->professions->secondary[3]->recipes;
	
		/* Now check if they can make each of the banquets */
		/* Banquet of the Grill */
		if(in_array(125141, $recipes)) {
			
			$this->grill = 125141;
			
		}
		
		/* Great Banquet of the Grill */
		if(in_array(125142, $recipes)) {
			
			$this->great_grill = 125142;
			
		}
		
		/* Banquet of the Oven */
		if(in_array(125600, $recipes)) {
			
			$this->oven = 125600;
			
		}
		
		/* Great Banquet of the Oven */
		if(in_array(125601, $recipes)) {
			
			$this->great_oven = 125601;
			
		}
		
		/* Banquet of the Pot */
		if(in_array(125596, $recipes)) {
			
			$this->pot = 125596;
			
		}
		
		/* Great Banquet of the Pot */
		if(in_array(125597, $recipes)) {
			
			$this->great_pot = 125597;
			
		}
		
		/* Banquet of the Steamer */
		if(in_array(125598, $recipes)) {
			
			$this->steamer = 125598;
			
		}
		
		/* Great Banquet of the Steamer */
		if(in_array(125599, $recipes)) {
			
			$this->great_steamer = 125599;
			
		}
		
		/* Banquet of the Wok */
		if(in_array(125594, $recipes)) {
			
			$this->wok = 125594;
			
		}
		
		/* Great Banquet of the Wok */
		if(in_array(125595, $recipes)) {
			
			$this->great_wok = 125595;
			
		}
		
		/* Pandaren Banquet */
		if(in_array(105190, $recipes)) {
			
			$this->pandaren = 105190;
			
		}
		
		/* Great Pandaren Banquet */
		if(in_array(105194, $recipes)) {
			
			$this->great_pandaren = 105194;
			
		}
		
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
	
	/* Check if they have a banquet */
	public function hasBanquet($banquet_name) {
		
		/* If they do have this banquet */
		if(isset($this->$banquet_name)) {
			
			/* Return the banquet recipe ID */
			return $this->$banquet_name;
			
		}
		
		/* No feast, return false */
		return false;
		
	}
	
}
?>