<!DOCTYPE html>
<html>
  <head>
    <title>Duodes: #pagetitle#</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Levertin Bibliotekssystem">
    <meta name="author" content="Carl Holmberg">
    
    <base href="#base_href#">
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css" />

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  
  </head>
  
  <body>
    <nav class="navbar-wrapper navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="img/duodes-liten.png" /></a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
              <li><a href="#"><span class="glyphicon glyphicon-star"></span> Top Destinations</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> About Us<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Company Details</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </li>
            </ul>
            <form class="navbar-form navbar-right" role="search">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#"><span class="glyphicon glyphicon-asterisk"></span> Book Tickets</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-info-sign"></span> Reservation<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Cancel</a></li>
                  <li><a href="#">Confirm</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        
        </div>
    </nav>
        
        
    <div class="container-fluid">
        <div class="jumbotron">
            <h1>Best Vacation Rentals</h1>
            <p>Sed placerat fringilla quam et.</p>
            <p><a class="btn btn-primary btn-lg">Start Now!</a></p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            {Library}
        </div>
    </div>
    
    <footer>
        <div class="container">
          <div class="row">
            <div class="col-md-4">Duodes &copy; 2013 Talgank</div>
            <div class="col-md-4">
              <ul class="nav nav-pills">
                <li class="active"><a href="#">{About} Duodes</a></li>
                <li><a href="#">Kontakta Talgdank</a></li>
              </ul>
            </div>
            <div class="col-md-4"><img src="img/duodes.png" width="300px" />
            </div>
          </div>
        </div>
    </footer>

  </body>
</html>