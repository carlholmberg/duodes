<div class="container well">
    <!-- cut:editing -->
    <button id="delete" class="btn btn-danger pull-right">{Delete}</button>
    
    <form class="form-inline well" role="form" action="title/#id#/add" method="post">
        <div class="form-group">
            <label for="copies" class="control-label">{New copies}:</label>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="copies" placeholder="{Copies}">
        </div>
        <div class="form-group">
            <select id="collection" class="from-control'">
                <option>Bibliotek</option><option>Kurslitteratur</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default">{Add}</button>
    </form>
    <!-- /cut:editing -->
    <!-- paste:editing -->
    <h1 id="title" data-type="text" data-pk="#id#" data-original-title="{Title}" data-url="title/#id#" class="editable editable-click">#title#</h1>
    <div class="col-md-2">
        <img id="cover" src="image/#id#" alt="Title image" />
        <p><strong>Antal: #total#</strong><br><em>(varav #borrowed# utlånade)</em></p>
    </div>
    <div class="col-md-5">
        <h5>{Author}</h5><p id="author" data-type="text" data-pk="#id#" data-original-title="{Author}" data-url="title/#id#" class="editable editable-click">#author#</p>
        <h5>ISBN <span id="refresh" class="btn btn-xs btn-warning pull-right"  title="{Refresh title}"><span class="glyphicon glyphicon-refresh"></span></span></h5><p id="isbn" data-type="text" data-pk="#id#" data-original-title="ISBN" data-url="title/#id#" class="editable editable-click">#isbn#</p>
        <h5>{Language}</h5><p id="lang" data-type="select" data-value="#lang#" data-pk="#id#" data-original-title="{Language}" data-url="title/#id#" class="editable editable-click" data-source="[{value: 'se', text: 'Svenska'}, {value: 'en', text: 'Engelska'}, {value: 'de', text: 'Tyska'}, {value: 'fr', text: 'Franska'}, {value: 'sp', text: 'Spanska'}]"></p>
        <h5>{Description}</h5><p id="desc" data-type="textarea" data-pk="#id#" data-original-title="{Description}" data-url="title/#id#" class="editable editable-click">#desc#</p>
        <h5>{Keywords}</h5><p id="keywords" data-type="text" data-pk="#id#" data-original-title="{Keywords}" data-url="title/#id#" class="editable editable-click" >#keywords#</p>
            
    </div>
    <div class="col-md-5">
        <h5>{Publisher}</h5><p id="publisher" data-type="text" data-pk="#id#" data-original-title="{Publisher}" data-url="title/#id#" class="editable editable-click">#publisher#</p>
        <h5>{Date}</h5><p id="date" data-type="text" data-pk="#id#" data-original-title="{Year}" data-url="title/#id#" class="editable editable-click">#date#</p>
        <h5>{Code}</h5><p id="code" data-type="text" data-pk="#id#" data-original-title="{Code}" data-url="title/#id#" class="editable editable-click">#code#</p>
            
        <h5>Länk:</h5><p><a href="#url#" title="Länk till post om titel">#url#</a></p>
    </div>
</div>


<div class="container well">
<h2>{Copies}</h2>
    <table class="tablesorter">
	<thead>
		<tr>
		    <!-- cut:hcell -->
			<th class="#class#" data-placeholder="#placeholder#">#name#</th>
			<!-- /cut:hcell -->
			<!-- paste:hcell -->
	</thead>
	<!-- paste:copies -->
	<tfoot>
		<tr>
		    <!-- cut:fcell -->
			<th>#name#</th>
			<!-- /cut:fcell -->
			<!-- paste:fcell -->
        </tr>
	</tfoot>
	<tbody>
	    <!-- paste:tbody -->
	</tbody>
</table>
<script type="text/javascript">
    initCopies();
</script>
</div>