<?php
class progression extends application {
	
	/* Variables */
	private $position;
	private $bnet_data;
	private $raid_id;
	public $raid_name;
	public $normal;
	public $heroic;

	/* Construction function */
	public function __construct($application_id, $raid_pos) {

		/* Create an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$this->bnet_data = $application->getBattleNetData();

		
		/* Set the raid position */
		$this->position = $raid_pos;
		
		/* Get the raid itself */
		$raid = $bnet_data->progression->raids[$race_pos];
		
		/* Start setting variables */		
		$this->raid_id = $raid->id;
		$this->raid_name = $raid->name;
		$this->normal = $raid->normal;
		$this->heroic = $raid->heroic;
		
	}
	
	/* Count the number of bosses */
	public function countBosses() {
		
		/* Get the battle.net data */
		
		/* Count the number of bosses */
		$bosses = count($this->bnet_data->progression->raids[$this->position]->bosses);
		
		/* And return it */
		return $bosses;
		
	}
	
	/* Get a specific boss */
	public function getBoss($position) {

		
		/* Get the boss */
		$boss = $this->bnet_data->progression->raids[$this->position]->bosses[$position];
		
		/* And return it */
		return $boss;
		
	}
	
}
?>