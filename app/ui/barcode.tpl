<div class="container well">
    <h3>{Labels in queue}</h3>
    <form class="form-inline well" role="form" action="print/barcode" method="post">
        <table class="table">
            <thead>
                <tr><th></th><th>{What}</th><th>{No:s}</th><th>{Print}</th><th>{Clear}</th></tr>
            </thead>
            <tbody>
                <!-- cut:row -->
                <tr><td><span class="glyphicon glyphicon-#type#"></span></td><td>#title#</td><td>#n#</td><td><input id="#id#" name="#id#" type="checkbox" checked="checked"></td><td><a class="action_delete" href="barcode/clear/#id#" title="{Clear}"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                <!-- /cut:row -->
                <!-- paste:row -->
            </tbody>
        </table>
        
        <div class="pull-right"><a href="barcode/clear/all" class="action_delete btn btn-danger"><span class="glyphicon glyphicon-trash"></span> {Clear all}</a> <button id="print" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> {Print}</button></div>
    </form>

    <h3>{Label lists}</h3>
    <div class="well">
        <table class="table">
            <thead>
                <tr><th></th><th>{Type}</th><th>{Pick}</th><th>{Print}</th></tr>
            </thead>
            <tbody>
                <form class="form-inline well" role="form" action="print/userlabels" method="post">

                <tr><td><span class="glyphicon glyphicon-list-alt"></span></td><td>Etiketter för hel basgrupp </td><td><select id="userlabels" name="userlables" class="class_list"><option value="all">alla</option></select></td><td><button class="btn"><span class="glyphicon glyphicon-print"></span></button></td></tr></form>
                <form class="form-inline well" role="form" action="print/userlist" method="post">
                <tr><td><span class="glyphicon glyphicon-list-alt"></span></td><td>Ultåningsista för hel basgrupp </td><td><select id="userlist" name="userlist" class="class_list"><option value="all">alla</option></select></td><td><button class="btn"><span class="glyphicon glyphicon-print"></span></button></td></tr>
            
                </form>
            </tbody>
            
        </table>
    </div>


</div>