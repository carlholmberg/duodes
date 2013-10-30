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
        
        <script src="js/bootbox.js"></script>
        
        <!-- cut:tablesorter -->
        <link rel="stylesheet" type="text/css" href="css/theme.bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tablesorter.css" />
        
        <script src="js/jquery.tablesorter.min.js"></script>
        <script src="js/jquery.tablesorter.widgets.min.js"></script>
        <script src="js/jquery.tablesorter.pager.min.js"></script>
        <script src="js/widget-grouping.js"></script>
        
        <script src="js/main.js"></script>
        <!-- /cut:tablesorter -->
        <!-- cut:xeditable -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap-editable.css" />
        <script src="js/bootstrap-editable.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/select2.css" />
        <link rel="stylesheet" type="text/css" href="css/select2-bootstrap.css" />
        <script src="js/select2.js"></script>
        
        <script>
$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    $('.editable').editable({
        emptytext: "Tom",
        success: function(response, newValue) {
            if(response.status == 'error') return response.msg;
        }
    });
    $('#keywords').editable({
        select2: {
            tags: ['html', 'javascript', 'css', 'ajax'],
            tokenSeparators: [",", " "]
        }
    }); 
    
    $('#refresh').click(function() {
        bootbox.dialog({
            message: "{Are you sure you want to refresh data for this title?}",
            title: "{Refresh title}",
            buttons: {
                success: {
                    label: "{No}",
                    className: "btn-default"
                },
                danger: {
                    label: "{Yes}",
                    className: "btn-danger",
                    callback: function() {
                        var loc = location.href.toString().replace('new', '#id#');
                        var isbn = $('#isbn').text();
                        $.post(loc, {action: 'refresh', isbn: isbn}, function(result) {
                            location.href = result;
                        });
                    }
                },
            }
        });

    });
    
    $('#delete').click(function() {
        var url = location.href.split('/');
        url.pop();
        var what = url.pop();
        var strs = {};
        strs['title'] = '{title}';
        strs['user'] = '{user}';
        
        bootbox.dialog({
            message: "{Are you sure you want to delete this} "+strs[what]+"?",
            title: "{Delete} "+strs[what],
            buttons: {
                success: {
                    label: "{No}",
                    className: "btn-default"
                },
                danger: {
                    label: "{Yes}",
                    className: "btn-danger",
                    callback: function() {
                        $.ajax({
                            url: location.href,
                            type: 'DELETE',
                            success: function(result) {
                                location.href = result;
                            }
                        });
                    }
                },
            }
        });

    });

});
        
        </script>
        <!-- /cut:xeditable -->
        
        <!-- paste:extrahead -->
        <link rel="stylesheet" type="text/css" href="css/app.css" />
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
                                <!--<li><a href="#">{About} Duodes</a></li>
                                <li><a href="#">Kontakta Talgdank</a></li>-->
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