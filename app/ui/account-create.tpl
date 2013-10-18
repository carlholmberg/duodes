<div class="container well">
    <h1>Skapa konto</h1>
    <p>Ditt konto är ännu inte aktiverat. Fyll i följande information för att komplettera och aktivera det.</p>
    <form class="form-horizontal" role="form" action="account/create" method="post">
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">Email address</label>
            <div class="col-lg-10">
                <input type="email" class="form-control" name="email" id="email" value="#email#" >
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
                <input type="name" class="form-control" name="name" id="name" value="#name#">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Lösenord (för lokal inloggning)</label>
            <div class="col-lg-10">
                <input type="password" class="form-control" name="password" id="password"  placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="createuser" class="col-lg-2 control-label">Skapa lånekonto</label>
            <div class="col-lg-10">
                <input type="checkbox" name="createuser" id="createuser" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-default">Skapa</button>
            </div>
        </div>
    </form>
</div>