<div class="container well">
    <h1>Skapa konto</h1>
    <p>Ditt konto är ännu inte aktiverat. Fyll i följande information för att komplettera och aktivera det.</p>
    <form class="form-horizontal" role="form" action="user/create" method="post">
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">Email address</label>
            <div class="col-lg-10">
                <input type="email" class="form-control" name="email" id="email" value="#email#" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-lg-2 control-label">{Firstname}</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" name="firstname" id="firstname" value="#firstname#">
            </div>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-lg-2 control-label">{Lastname}</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" name="lastname" id="lastname" value="#lastname#">
            </div>
        </div>
        <div class="form-group">
            <label for="uid" class="col-lg-2 control-label">Födelsedag</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" name="uid" id="uid"  placeholder="Födelsedag" autocomplete="off">
                 <p>(används för att kontrollera identitet)</p>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Lösenord</label>
            <div class="col-lg-10">
                <input type="password" class="form-control" name="password" id="password"  placeholder="Password" autocomplete="off">
                <p>(för lokal inloggning)</p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-default">Skapa</button>
            </div>
        </div>
    </form>
</div>