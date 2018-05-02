
<div class='row'>
		<div class='col-md-1'><label for='{name}'>{name}{requiredhtml}</label></div>
		<div class='col-md-4'>
			<a href="javascript:openPicker('{name}');" id="chooseimage" name="chooseimage" {chooseimage} >choose</a>
			<a href="javascript:removeImage('{name}');" id="removeimage" name="removeimage" {removeimage} > remove</a>

			
			<div id="errorimage"></div>
		</div>
</div>
<div class='row'>

		<div class='col-md-1'>
		</div>
		<div class='col-md-4'>
		<div id="imagepreview" name="imagepreview">
			<?php
			if ($imagecdn != '')
			{
			?>
			<img src='https://process.filestackapi.com/resize=width:100,height:100,fit:scale/{imagehandle}'></img>
			<?php
			}
			?>
		</div>
		<input type='hidden' class='formelement'  name='imageurl' id='imageurl' value='{imagecdn}' data-required='{required}' >
		<input type='hidden' class=''  name='imagefile' id='imagefile' value='{imagefilename}'>
		<input type='hidden' class=''  name='imagehandle' id='imagehandle' value='{imagehandle}'>
		<input type='hidden' class=''  name='imagelement' id='imagelement' value='{name}'>


		</div>
	</div>
