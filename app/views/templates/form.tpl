<form action="index.php" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-4" for="acresBought">How many acres do you wish to buy?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresBought" id="acresBought" value="{if $view eq 'error'}{$game->turn->acresBought}{else}0{/if}">
                </div>
                <p class="help-block container">Land is wealth and power. Great leaders expand their empire.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="acresSold">How many acres do you wish to sell?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresSold" id="acresSold" value="{if $view eq 'error'}{$game->turn->acresSold}{else}0{/if}">
                </div>
                <p class="help-block container">Sell land when you are short on grain.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="bushelsFed">How many bushels do you wish to feed your people?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="grainFed" id="grainFed" value="{$game->population * 20}">
                </div>
                <p class="help-block container">20 bushels will feed 1 person. You need <strong>{$game->population * 20}</strong> bushels to feed everyone this year.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="acresPlanted">How many acres do you wish to plant with seed?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresPlanted" id="acresPlanted" value="{if $view eq 'error'}{$game->turn->acresPlanted}{else}0{/if}">
                </div>
                <p class="help-block container">2 bushels are needed to plant 1 acre. A person can work at most 10 acres. 
                {$game->population} people can plant <strong>{$game->population * 10}</strong> acres.
                </p>
            </div>            
            <button type="submit" name="submit" id="next" value="next" class="btn btn-success" style="margin-bottom: 2em;">So let it be written, so let it be done!</button>

</form>