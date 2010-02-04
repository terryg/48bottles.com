<?php
/*
Template Name: ABV Form - GET
*/
?>

<fieldset>
<legend>ABV Calculator</legend>

<form action="/abv/" method="post" name="form1" id="form1">
<table>
  <tr>
    <td><strong>Original</strong></td>
    <td><input name="OG" type="text" id="OG" value="1.050" size="6" maxlength="6" />
    </td>
    <td><select name="OGScale" size="1" id="OGScale">
    <option value="sg" selected="selected">Specific Gravity</option>
    <option value="plato">Degrees Plato</option>
    </select>
    </td>
    <td><input name="OGTemp" type="text" id="OGTemp" value="60" size="5" maxlength="5" />
    </td>
    <td><select name="TempScale" size="1" id="temp1scale">
    <option value="F" selected="selected">F</option>
    <option value="C">C</option>
    </select>
    </td>
  </tr>
  <tr>
    <td><strong>Final</strong></td>
    <td><input name="FG" type="text" id="FG" value="1.010" size="6" maxlength="6" />
    </td>
    <td>&nbsp;</td>
     <td><input name="FGTemp" type="text" id="FGTemp" value="60" size="5" maxlength="5" />
    </td>
    <td>&nbsp; </td>
  </tr>
</table>
<input type="submit" name="Submit" value="Submit" />
<input type="reset" name="Reset" value="Reset" />
</form>
</fieldset>