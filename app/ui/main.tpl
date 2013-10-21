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
        
        <!-- cut:tablesorter -->
        <link rel="stylesheet" type="text/css" href="css/theme.bootstrap.css" />
        <script src="js/jquery.tablesorter.min.js"></script>
        <script src="js/jquery.tablesorter.widgets.min.js"></script>
        <script src="js/jquery.tablesorter.pager.min.js"></script>
        <script src="js/main.js"></script>
        <!-- /cut:tablesorter -->
        <!-- cut:xeditable -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap-editable.css" />
        <script src="js/bootstrap-editable.min.js"></script>
        <!-- /cut:xeditable -->
        
        <!-- cut:ajaxupdate -->
        <script>
$(document).ready(function() {
    var num_ids = #ids#;
    function update(from, to) {
        if (from > num_ids) return;
        $.get("titles/"+from+"/"+to, function(html) {
            $("table tbody").append(html);
            var resort = true;
            $("table").trigger("update", [resort]);
            update(from+40, to+40);
        });
    }
    update(40, 80);
}); 
        </script>
        <!-- /cut:ajaxupdate -->
        
        <!-- paste:extrahead -->
  
    </head>
  
    <body>
        
        <!-- cut:menu -->
        <nav class="navbar-wrapper navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#base_href#"><img alt="branding" src="img/duodes-liten.png" /></a>
                </div>

                <div class="collapse navbar-collapse">
                    
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
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
        <!-- /cut:menu -->
        <!-- paste:menu -->
        
        <!-- paste:page -->
    
        <!-- cut:footer -->
        <footer class="well">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">Duodes &copy; 2013 Talgank</div>
                        <div class="col-md-4">
                            <ul class="pager">
                                <li><a href="#">{About} Duodes</a></li>
                                <li><a href="#">Kontakta Talgdank</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4"><img src="img/duodes.png" alt="branding" width="300" />
                    </div>
                </div>
            </div>
        </footer>
        <!-- /cut:footer -->
        <!-- paste:footer -->

    </body>
</html>