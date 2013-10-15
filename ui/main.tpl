<!DOCTYPE html>
<html>
    <head>
        <title>Duodes: #pagetitle#</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="description" content="Duodes Bibliotekssystem">
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
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#base_href#"><img src="img/duodes-liten.png" /></a>
                </div>

                <div class="collapse navbar-collapse">
                    
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></button>
                    </form>
                    
                    <ul class="nav navbar-nav">
                    
                        <!-- cut:menuitem -->
                        <li class="#active#"><a href="#link#" title="#title#"><span class="glyphicon glyphicon-#icon#"></span> #title#</a></li>
                        <!-- /cut:menuitem -->

                        <!-- cut:dropdown -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-#icon#"></span> #title#<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <!-- paste:submenu -->
                            </ul>
                        </li>
                        <!-- /cut:dropdown -->
                        
                        <!-- paste:menuitem -->
                    </ul>            
                </div>
            </div>
            
        </nav>
        
        <!-- paste:page -->
    
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