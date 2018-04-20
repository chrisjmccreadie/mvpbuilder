<ul class="sidebar-nav">
	<li class="sidebar-brand active" >
	    <!-- hold the title here -->
	 	  <a href='/admin/dashboard' >Admin</a>
	</li>
	<?php
		//check the user is logged in

		if ($this->session->loggedin == 1)
		{
	?>
	<li>
		<?php
		//check the user can view the tables
		if ($this->session->canviewtables == 1)
		{
		?>

		<a href="#">Table Admin</a>
		<ul>
			
				<?php 

					foreach ($this->session->tables as $item)
					{
						echo "<a href='".base_url()."admin/table/$item''>$item</a>";
					}
				?>
			
		</ul>
		<?php
		}
		?>
	<li>
	<li>
		<a href='/admin/logout' >Logout</a>
	</li>
	<?php
		}
	?>
	
</ul>
               
