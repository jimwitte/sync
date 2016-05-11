	<p>
		{$game->turn->peopleStarved} people starved. {$game->turn->immigration} people came to the city. <br />
		{if !empty($game->turn->plagueDeaths)}A horrible plague struck! {$game->turn->plagueDeaths} people became sick and died.<br />{/if}
		Population is now {$game->population} people.
	</p>
	<p>
		Last year you harvested {$game->turn->yield} bushels/acre for a total of {$game->turn->harvest} bushels. The rats ate {$game->turn->ratLoss} bushels.<br />
		You now have {$game->grainStored} bushels in store.<br />
	</p>
	<p>
		{if !empty($game->turn->acresSold)}You sold {$game->turn->acresSold} acres last year.<br />{/if}
		{if !empty($game->turn->acresBought)}You bought {$game->turn->acresBought} acres last year.<br />{/if}
		You own {$game->acresOwned} acres.<br />
		Land is trading at {$game->landValue} bushels per acre.<br />
	</p>
