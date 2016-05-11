{include 'header.tpl'}

<div class="well well-sm animated lightSpeedIn">

	<div class="alert alert-info" role="alert">
		<p>Game Over!</p>
	</div>

	{include 'status.tpl'}

	<div class="alert alert-info" role="alert">
		<p>In your 10-year term of office, {$game->totalPercentStarved|round:"1"} percent 
		of the population starved per year on the average, i.e. a total of 
		{$game->totalStarved|round:"2"} people died.
		</p>
	
		<p>You started with 10 acres per person and ended with 
		{($game->acresOwned / $game->population)|round:"1"} acres per person.
		</p>
		<p><strong>{$game->perfeval()}</strong></p>
	
		<p>So long for now.</p>
	</div>

</div>

{include 'footer.tpl'}
