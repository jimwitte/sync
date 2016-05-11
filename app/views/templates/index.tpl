{include 'header.tpl'}

{if $view eq 'start'}
	<p>Try your hand at governing ancient Sumeria for a ten-year term of office.</p>
{/if}

<div class="well well-sm {if $view eq 'midgame'}animated rollIn{/if}{if $view eq 'error'}animated shake{/if}">

	{if !empty($game->turn->errors)}
		<div class="alert alert-danger" role="alert">
			{foreach from=$game->turn->errors item=thisError}
    			<p>{$thisError}</p>
			{/foreach}
		</div>
	{/if}

	<p class="lead">Hammurabi: I beg to report to you, in <strong>Year {$game->year}</strong> of your glorious reign:

	{include 'status.tpl'}
	
</div>
   
{include 'form.tpl'}

{include 'footer.tpl'}
