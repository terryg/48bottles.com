<?php
/*
Template Name: ABV
*/
?>
<?php get_header(); ?>

<div id="content">

<?php
  if (!empty($_POST)) {
    include (TEMPLATEPATH . '/abvform-post.php'); 
  } else {
    include (TEMPLATEPATH . '/abvform-get.php'); 
  }

?>

<div class="spacer">&nbsp;</div>
<div class="formulas">
Temperature conversion: <code>F = (9/5) * C + 32</code>
<br/>
Temperature correction: <code>1.313454 - 0.132674*T + 0.002057793*T^2 - 0.000002627634*T^3</code>
<br/>
Plato to SG: <code>SG = P / (258.6 - ((P/258.2)*227.1)) + 1</code>
<br/>
ABV: <code>(OG - FG)/7.46 + 0.5</code>
<br/>
ABW: <code>(OG - FG) * 105</code>
</div>

<div class="spacer">&nbsp;</div>
<div><a href="http://www.rooftopbrew.net/abv.php">Hat Tip</a></div>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>