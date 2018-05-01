
<div class='row'>
		<div class='col-md-1'><label for='{name}'>{name}{requiredhtml}</label></div>
		<div class='col-md-4'>
			<a href="javascript:openPicker('{name}');"">choose</a>
						<div id="error{name}"></div>


		</div>
		<div class='col-md-8'>
		<img id="image{name}" name="image{name}" class="" src="{value}" ></img>
		<input type='hidden' class='formelement'  name='{name}' id='{name}' value='{value}' data-required='{required}'>
		<input type='hidden' class=''  name='imageurl' id='imageurl' value='{value}'>
		<input type='hidden' class=''  name='imagefile' id='imagefile' value='{value}'>
		<input type='hidden' class=''  name='imagehandle' id='imagehandle' value=''>
		<input type='hidden' class=''  name='imagelement' id='imagelement' value=''>


		</div>
	</div>
