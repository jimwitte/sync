
<?php 
if ($population > 0) {
  $score = intval($acresOwned / $population);
} else {
$score = 0;    
}
 ?>
<p>Sire, your term of office has come to an end.</p>
<p>
    During your 10-year term of office, <?php echo($totalStarved); ?> people starved.
    You started with 10 acres per person, and ended up with <?php echo($score); ?> acres per person.
</p>

<?php
if ($totalStarved === 0 and $score > 10) {
    echo ("<div class=\"alert alert-success\" role=\"alert\">A fantastic performance! Charlemagne, Disraeli, and Jefferson combined could not have done better!</div>");
} elseif ($totalStarved > 300) {
    echo ("<div class=\"alert alert-danger\" role=\"alert\">Your heavy-handed performance smacks of Nero and Ivan IV. The people (remaining) find you an unpleasant ruler, and, frankly, hate your guts.</div>");
} else {
    echo("<div class=\"alert alert-warning\" role=\"alert\">Your performance could have been somewhat better, but really wasn't too bad at all. " . intval($population * .8) . " people would dearly like to see you assassinated, but we all have our trivial problems.</div>");
}
?>
<p>So long for now.</p>