<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Logo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      
    </ul>
     <?php
        //check if we require client management
        if ($this->config->item('search') == true)
        {
        ?>
          <form class="form-inline my-2 my-lg-0" id="searchform" name="searchform" action="<?php echo base_url();?>search">
            <input id="search" name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
         </form>
     <?php
      }
      ?>
        <?php
        //check if we require client management
        if ($this->config->item('clientaccount') == true)
        {
          //check if they have logged in
          if ($this->session->clientloggedin != 1)
          {
          ?>
          <button class="btn btn-outline-success" type="button"><a href="<?php echo base_url();?>signin">Sign In</a></button>
          <?php
          }
          else
          {
          ?>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav ml-auto">
               
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    My Profile
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url();?>myaccount">My Account</a>
                        <a class="dropdown-item" href="<?php echo base_url();?>logout">Logout</a>
                  </div>
                </li>
              </ul>
            </div>
          <?php
          }
        }
        ?>
  </div>
</nav>