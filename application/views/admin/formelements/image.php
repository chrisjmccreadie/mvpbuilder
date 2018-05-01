
<div class='row'>
		<div class='col-md-1'><label for='{name}'>{name}{requiredhtml}</label></div>
		<div class='col-md-4'>
			<a href="javascript:openPicker('{name}');" id="chooseimage" name="chooseimage" {chooseimage} >choose</a>
			<a href="javascript:removeImage('{name}');" id="removeimage" name="removeimage" {removeimage} > remove</a>

			
			<div id="error{name}"></div>
		</div>
</div>
<div class='row'>

		<div class='col-md-1'>
		</div>
		<div class='col-md-4'>
		<div id="imagepreview" name="imagepreview">
			<?php
			if ($imageurl != '')
			{
			?>
			<img src={imageurl}></img>
			<?php
			}
			?>
		</div>
		<input type='hidden' class='formelement'  name='{name}' id='{name}' value='{value}' data-required='{required}'>
		<input type='hidden' class=''  name='imageurl' id='imageurl' value='{value}'>
		<input type='hidden' class=''  name='imagefile' id='imagefile' value='{value}'>
		<input type='hidden' class=''  name='imagehandle' id='imagehandle' value=''>
		<input type='hidden' class=''  name='imagelement' id='imagelement' value=''>


		</div>
	</div>
