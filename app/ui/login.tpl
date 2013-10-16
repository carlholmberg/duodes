<!DOCTYPE html>
<html>
    <head>
        <title>Duodes: Login</title>
        
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
        
        <div class="container" style="margin-top: 10%">
            <img src="img/duodes-liten.png" class="pull-right" style="opacity: 0.5; margin: 8px" height="20px" />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Logga in</h3>
                </div>
                <ul class="nav nav-tabs">
                    <li><a href="#openid" data-toggle="tab">OpenID</a></li>
                    <li class="active"><a href="#local" data-toggle="tab">Lokalt</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade" id="openid">
                        <div class="panel-body">
                        <label>Använd</label><br><a href="login/google" title="Google-account" class="btn btn-default">Google-konto</a></div>
                    </div>
                    <div class="tab-pane fade active in" id="local">
                    <form class="form-inline" role="form" action="login/local" method="post">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="checkbox col-md-3">
                        <label>
                            <input type="checkbox" name="remember" id="remember" /> Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn btn-default">Sign in</button>
                </div>
            </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>