<div class="container">
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
                            <button type="submit" class="btn btn-default">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>