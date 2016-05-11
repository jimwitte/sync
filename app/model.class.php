<?php

//======================================================================
// MODEL objects
//======================================================================


class Turn {
	//player inputs
	public $acresBought = 0;
	public $acresSold = 0;
	public $grainFed = 0;
	public $acresPlanted = 0;
	
	//player input errors
	public $errors = [];
	
	//turn events
	public $impeach = FALSE;
	public $harvest = 0;
	public $yield =0;
	public $ratLoss = 0;
	public $peopleStarved = 0;
	public $plagueDeaths = 0;
	public $immigration = 0;
	
	function __construct() {
		// get player inputs from form if available
		$this->acresBought = intval(filter_input(INPUT_POST, 'acresBought', FILTER_VALIDATE_INT));
		$this->acresSold = intval(filter_input(INPUT_POST, 'acresSold', FILTER_VALIDATE_INT));
        $this->grainFed = abs(intval(filter_input(INPUT_POST, 'grainFed', FILTER_VALIDATE_INT)));
        $this->acresPlanted = abs(intval(filter_input(INPUT_POST, 'acresPlanted', FILTER_VALIDATE_INT)));
	}
	
}

class Game {
    public $year = 0;
    public $landValue = 0;
    public $acresOwned = 0;
    public $population = 0;
    public $grainStored = 0;
    public $totalStarved = 0;
    public $totalPercentStarved = 0;
    
    public $turn;

    function __construct() {
    
    	session_start();
    	$submitButton = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);
    	if ($submitButton == 'reset' or $submitButton !== 'next') {
    		session_unset(); // clear out session
    	}
    	
    	// create a new turn for the game
        $this->turn = new Turn();
    	
    	// if prior game data available, create game object with game in progress
		if(isset($_SESSION['year'])) {
        	$this->year = $_SESSION['year'];
        	$this->landValue = $_SESSION['landValue'];
        	$this->acresOwned = $_SESSION['acresOwned'];
        	$this->population = $_SESSION['population'];
        	$this->grainStored = $_SESSION['grainStored'];
        	$this->totalStarved = $_SESSION['totalStarved'];
        	$this->totalPercentStarved = $_SESSION['totalPercentStarved'] ;

			if ($this->year < 11) {
        		$this->advanceTurn($this->turn); // game in progress, advance turn
        	}
        	
        } else {
        	// default values for new game 
            $this->year = 1;
        	$this->landValue = 19;
        	$this->acresOwned = 1000;
        	$this->population = 100;
        	$this->grainStored = 2800;
        	$this->totalStarved = 0;
        	$this->totalPercentStarved = 0;
        	// do not advance game turn, we are starting a new game
        	$this->writeGameState(); // write starting values to game state	 
        }
    }

	function getGameState($field) {
		$status = $this->$field;
		return $status;
	}
	
	function setGameState($field,$value) {
		$this->$field = $value;
	}
	
	function validateTurn($thisTurn) {
		$errors = [];
		// check > 0
		if ($thisTurn->acresSold < 0 or $thisTurn->acresBought < 0 or $thisTurn->grainFed < 0 or $thisTurn->acresPlanted < 0) {$errors[]="Values must be greater than 0.";}
		
		$landSaleProceeds = intval($thisTurn->acresSold * $this->landValue);
		$landPurchaseCost = intval($thisTurn->acresBought * $this->landValue);
		$bushelsPlanted = intval($thisTurn->acresPlanted * 2);
		$totalBushelsUsed = intval($bushelsPlanted + $landPurchaseCost + $thisTurn->grainFed - $landSaleProceeds);
		$shortfall = $totalBushelsUsed - $this->grainStored;
		
		// can't use more bushels than available
		if ($shortfall > 0) {$errors[]="O Great One, your edict requires $shortfall additonal bushels. You have $this->grainStored in storage. You ordered $bushelsPlanted used to plant, $landPurchaseCost to buy land, $landSaleProceeds gained from selling land, $thisTurn->grainFed used for feeding people.";}
		
		// only unplanted acres are available for sale
		$acresAvailableForSale = $this->acresOwned - $thisTurn->acresPlanted + $thisTurn->acresBought;
		if ($acresAvailableForSale < 0) {$acresAvailableForSale = 0;}

		if ($thisTurn->acresSold > $acresAvailableForSale){$errors[]="O Great One, I am unable to sell as you request. Only unplanted acres can be sold. You have $acresAvailableForSale acres available to sell.";}	
		
		// check that enough people are available to plant
		$peopleRequired = intval($thisTurn->acresPlanted / 10);
		if ($peopleRequired >  $this->population) {$errors[]="O Great One, not enough people to plant as you request. $peopleRequired people are needed to plant $thisTurn->acresPlanted acres.";}
		return $errors;
	}
	
	function advanceTurn($turn) {
		//validate game turn inputs
		$turn->errors = $this->validateTurn($turn);

		if (!empty($turn->errors)) {
		} else {
		
			// harvest is 1-6 bushels per acre
			$turn->yield = rand(1,6);
			$turn->harvest = intval($turn->acresPlanted * $turn->yield); 
		
			// 50/50 chance of losing 10%-14% of stored grain
			$turn->ratLoss = intval(rand(0,1) * $this->grainStored / rand(7,10)); 
		
			// 20 bushels required to feed each person. Can't feed more people than current population
			$peopleFed = intval(min(($turn->grainFed / 20),$this->population));
			$turn->peopleStarved = intval($this->population - $peopleFed);

			$landPurchaseCost = intval($turn->acresBought * $this->landValue);
			$landSaleProceeds = intval($turn->acresSold * $this->landValue);
			$bushelsPlanted = intval($turn->acresPlanted * 2);
			$totalBushelsUsed = intval($bushelsPlanted + $landPurchaseCost + $turn->grainFed - $landSaleProceeds);
		
			//15% chance of plague, kills half the population that didn't starve first
			if (rand(1, 20) < 4) {
				$turn->plagueDeaths = intval(($this->population - $turn->peopleStarved) / 2);
			} else {
				$turn->plagueDeaths = 0;
			}
		
			$totalDeaths = $turn->plagueDeaths + $turn->peopleStarved;
		
			//chance of immigration if nobody died
			if ($totalDeaths < 1 ) {
				$turn->immigration = intval((20 * $this->acresOwned + $this->grainStored) / (100 * $this->population) + 1);
			} else {
				$turn->immigration = 0;
			}
		
			//impeachment if more than 45% of the people starve in a single Turn
			if ($turn->peopleStarved > .45 * $this->population) {
				$turn->impeach = TRUE;
			} else {
				$turn->impeach = FALSE;
			}
			
				
			// update game state 
			$this->totalStarved = $this->totalStarved + $turn->peopleStarved;
			$this->totalPercentStarved = ((($this->year - 1) * $this->totalPercentStarved) + ($turn->peopleStarved * 100/$this->population))/$this->year;
			$this->population = intval($this->population - $totalDeaths + $turn->immigration);
			$this->grainStored = intval($this->grainStored - $totalBushelsUsed - $turn->ratLoss + $turn->harvest);
			$this->year++; //advance year by 1
			$this->landValue = rand(17, 26);
			$this->acresOwned = $this->acresOwned + $turn->acresBought - $turn->acresSold;
			$this->writeGameState(); // write game state to session
		}
	}

	
	function perfEval() {
		$eval = '';
		$acresPerPerson = $this->acresOwned / $this->population;
		if ($this->totalPercentStarved > 33 OR $acresPerPerson < 7) {
			$eval = "Due to this extreme mismanagement you have not only been impeached and thrown out of office but you have also been declared National Fink!";
		} elseif ($this->totalPercentStarved > 10 OR $acresPerPerson < 9) {
			$eval = "Your heavy-handed performance smacks of Nero and Ivan IV. The people (remaining) find you an unpleasant ruler, and, frankly, hate your guts!";
		} elseif ($this->totalPercentStarved > 3 OR $acresPerPerson < 10) {
			$eval = "Your performance could have been somewhat better, but really wasn't too bad at all. ".rand(1,intval($this->population * .8))." people would dearly like to see you assassinated but we all have our trivial problems.";
		} else {
			$eval = "A fantastic performance! Charlemange, Disraeli, and Jefferson combined could not have done better!";
		}
		return $eval;
	}
	
	function writeGameState() {
		$_SESSION['year'] = $this->year;
		$_SESSION['landValue'] = $this->landValue;
		$_SESSION['acresOwned'] = $this->acresOwned;
		$_SESSION['population'] = $this->population;
		$_SESSION['grainStored'] = $this->grainStored;
		$_SESSION['totalStarved'] = $this->totalStarved;
		$_SESSION['totalPercentStarved'] = $this->totalPercentStarved;
	}
		
}

?>