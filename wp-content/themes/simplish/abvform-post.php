<?php
/*
Template Name: ABV Form - POST
*/
?>

<?php 
  $temp_orig = $_POST["OGTemp"];
  $temp_final = $_POST["FinalTemp"];

  if ($_POST["TempScale"] == "C") {
    $temp_orig = (9/5)*$temp_orig+32;
    $temp_final = (9/5)*$temp_final+32;
  }

  $correction_orig = 1.313454 - 0.132674*$temp_orig + 0.002057793*$temp_orig*$temp_orig - 0.000002627634*$temp_orig*$temp_orig*$temp_org;
  $correction_final = 1.313454 - 0.132674*$temp_final + 0.002057793*$temp_final*$temp_final - 0.000002627636*$temp_final*$temp_final*$temp_final;

  $og_uncorrected = $_POST["OG"];
  $fg_uncorrected = $_POST["FG"];

  if ($_POST["OGScale"] == "sg") {
    $scale = "Specific Gravity";
  } else {
    $scale = "Degrees Plato";

    $og_uncorrected = round(($og_uncorrected/(258.6-(($og_uncorrected/258.2)*227.1))+1), 3);
    $fg_uncorrected = round(($fg_uncorrected/(258.6-(($fg_uncorrected/258.2)*227.1))+1), 3);
  }

  $sog = (1000 * $og_uncorrected) + $correction_orig;
  $sfg = (1000 * $fg_uncorrected) + $correction_final;

  $abv = round(($sog - $sfg)/7.46 + 0.5, 2);
  $abw = round(($sog - $sfg) / 1000 * 105, 2);

  if ($scale == "Degrees Plato") {
    $sog = round(1000 * ($sog/1000 - 1) / 4, 0);
    $sfg = round(1000 * ($sfg/1000 - 1) / 4, 0);
  } else {
    $sog = round($sog / 1000, 3);
    $sfg = round($sfg / 1000, 3);
  }
?>

<fieldset>
 <table>
    <thead>
    <tr>
      <th>&nbsp;</th>
      <th>Corrected</th>
      <th>System</th>
      <th>Uncorrected</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><strong>Original:</strong></td>
      <td><?php echo htmlspecialchars($sog) ?></td>
      <td><?php echo htmlspecialchars($scale) ?></td>
      <td><?php echo htmlspecialchars($_POST["OG"]) ?></td>
    </tr>
    <tr>
      <td><strong>Final:</strong></td>
      <td><?php echo htmlspecialchars($sfg) ?></td>
      <td>&nbsp;</td>
      <td><?php echo htmlspecialchars($_POST["FG"]) ?></td>
    </tr>
    <tr>
      <td><strong>Alcohol By Weight:</strong></td>
      <td><?php echo htmlspecialchars($abw) ?>%</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Alcohol By Volume:</strong></td>
      <td><?php echo htmlspecialchars($abv) ?>%</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>

<form>
  <input name="button" type="button" onclick="history.back()" value="Back" />
</form>
</fieldset>