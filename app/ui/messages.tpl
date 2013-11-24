        <!-- cut:circ_b_ok -->
        <div class="container alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-check"></span> #name# lånade #title#</p>
        </div>
        <!-- /cut:circ_b_ok -->
        
        <!-- cut:circ_b_wrong -->
        <div class="container alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Felaktig streckkod</p>
        </div>
        <!-- /cut:circ_b_wrong -->
        
        
        <!-- cut:circ_b_out -->
        <div class="container alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Boken är redan utlånad</p>
        </div>
        <!-- /cut:circ_b_out -->
        
        <!-- cut:circ_b_ref -->
        <div class="container alert alert-warning fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Boken får inte lånas (WS-litteratur)</p>
        </div>
        <!-- /cut:circ_b_ref -->
        
        <!-- cut:circ_r_ok -->
        <div class="container alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-check"></span> #fname# #lname# återlämnade <strong>#title#</strong> (#bc#)</p>
            <p><em>#coll#</em></p>
        </div>
        <!-- /cut:circ_r_ok -->

        <!-- cut:circ_r_in -->
        <div class="container alert alert-warning fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Försökte återlämna <strong>#title#</strong> (#bc#) som inte är utlånad</p>
        </div>
        <!-- /cut:circ_r_in -->
        
        <!-- cut:copy_del -->
        <div class="container alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Raderade kopia</p>
        </div>
        <!-- /cut:copy_del -->
        
        
        <!-- cut:report_none -->
        <div class="container alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><span class="glyphicon glyphicon-exclamation-sign"></span> Inga utlån matchade kriterierna</p>
        </div>
        <!-- /cut:report_none -->