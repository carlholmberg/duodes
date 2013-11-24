<div class="container well">
    <h3>{Reports}</h3>
    <div class="well">
        <table class="table">
            <thead>
                <tr><th></th><th>{Type}</th><th>{Class}</th><th>{Collection}</th><th>{Subject}</th><th>{Order by}</th><th>{Print}</th></tr>
            </thead>
            <tbody>
                <form class="form-inline well" role="form" action="print/outreport" method="post">

                <tr><td><span class="glyphicon glyphicon-list-alt"></span></td><td>Utlånade böcker </td><td><select name="class" class="class_list"><option value="all">alla</option></select></td>
                <td><select name="collection" class="collection_list"><option value="all">alla</option></select></td>
                <td><select name="subject" class="subject_list"><option value="all">alla</option></select></td>
                
                <td><select name="order">
                    <option value="title">{Title}</option>
                    <option value="user">{User}</option>
                </select></td>
                
                <td><button class="btn"><span class="glyphicon glyphicon-print"></span></button></td></tr></form>
                
                
                <form class="form-inline well" role="form" action="print/expired" method="post">
                <tr><td><span class="glyphicon glyphicon-list-alt"></span></td><td>Utgångna lån </td><td><select name="class" class="class_list"><option value="all">alla</option></select></td>
                <td><select name="collection" class="collection_list"><option value="all">alla</option></select></td>
                <td><select name="subject" class="subject_list"><option value="all">alla</option></select></td>
                
                <td><select name="order">
                    <option value="title">{Title}</option>
                    <option value="user">{User}</option>
                </select></td>
                <td><button class="btn"><span class="glyphicon glyphicon-print"></span></button></td></tr></form>
            </tbody>
            
        </table>
    </div>


</div>