<div class="container">
    <img src="img/duodes-liten.png" class="pull-right" style="opacity: 0.5; margin: 8px" height="20px" />
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Logga in</h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <h4>OpenID</h4>
                <p><a href="login/google" title="Google-account" class="btn btn-default">Google-konto</a></p>
            </fieldset>
        <form class="form-inline" role="form" action="login/local" method="post">
            <fieldset>
                <h4>Lokalt</h4><p>Använd i första hand OpenID ovan</p>
                <div class="form-group">
                    <label for="email">{Email address}/{User name}</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">{Password}</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">{Sign in}</button>
                </div>
                
            </fieldset>
        </div>
        <div class="panel-footer">
            
        </div>
        </form>
    </div>
</div>