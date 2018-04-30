<?php
  // print_r($lookupdata);
 ?>
<div class='row'>
		<div class='col-md-1'><label for='{name}'>{name}:</label></div>
		<div class='col-md-4'>
			<?php
			//loop through the look up data to create the select
			//todo (chris) we should really use the parsing so this is due for a refactor
			if ($lookupdata != '')
			{
				echo "<select class='form-control picker' data-live-search='true' name='{name}' id='{name}' >";
				echo "<option value=''>Please  select</option>";
				foreach ($lookupdata as $lookupitem)
				{
					$selected = '';
					if ($lookupitem->id == $value)
							$selected = 'selected';
					echo "<option value='$foreignitem->id' $selected>$lookupitem->name</option>";
				}
				echo "</select>";
			}
			?>
		</div>
</div>