<div class='row'>
		<div class='col-md-1'><label for='{name}'>{name}{requiredhtml}</label></div>
		<div class='col-md-4'>
			<?php
				echo "<select class='form-control formelement' data-live-search='true' name='{name}' id='{name}' data-required='{required}'>";
				echo "<option value=''>Please  select</option>";
				$i = 1;
				while ($i <= 100)
				{
					$selected = '';
					if ($i == $value)
							$selected = 'selected';
					echo "<option value='$i' $selected>$i</option>";

					$i++;
				}
				echo "</select>";
			?>
			<div id="error{name}"></div>
		</div>
</div>