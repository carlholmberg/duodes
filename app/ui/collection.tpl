<div class="container well">
    <h3>{Collections}</h3>
    <div class="well">
        <table class="table">
            <thead>
                <tr><th>{Name}</th><th>Utlån</th><th>Period</th><th></th></tr>
            </thead>
            <tbody>
                <!-- cut:row -->
                <tr>
                    <td><p data-type="text" data-name="name" data-pk="#id#" data-original-title="{Name}" data-url="collection/#id#" class="editable editable-click">#name#</p></td>
                    <td><p data-type="select" data-name="type" data-pk="#id#" data-value="#type#" data-source="#type_source#" data-original-title="Utlån" data-url="collection/#id#" class="editable editable-click"></p></td>
                    <td><p data-type="textarea" data-name="value" data-pk="#id#" data-original-title="{Value}" data-url="collection/#id#" data-rows="3" class="editable editable-click">#value#</p></td>
                    <td>
                    <a href="collection/#id#" class="action_delete" title="{Delete}"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
                <!-- /cut:row -->
                <!-- paste:row -->
            </tbody>
            
        </table>
        <a href="collection/new" title="Ny samling" class="btn btn-success"><span class="glyphicon glyphicon-star"></span> Ny samling</a>
    </div>


</div>