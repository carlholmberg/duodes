<div class="container well">
    <button id="delete" class="btn btn-danger pull-right">{Delete}</button>
    
    <form class="form-inline well" role="form" action="title/#id#/add" method="post">
        <div class="form-group">
            <label for="copies" class="control-label">{New copies}:</label>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="copies" id="copies" placeholder="{Copies}">
        </div>
        <div class="form-group">
            <select id="collection" name="collection" class="from-control'">
            </select>
        </div>
        <button type="submit" class="btn btn-default">{Add}</button>
    </form>
    <h1 id="title" data-type="text" data-pk="#id#" data-original-title="{Title}" data-url="title/#id#" class="editable editable-click">#title#</h1>
    <div class="col-md-2">
        <img id="cover" src="image/#image#" alt="Title image" />
        <p><strong>Antal: #total#</strong><br><em>(varav #borrowed# utlånade)</em></p>
    </div>
    <div class="col-md-5">
        <h5>{Author}</h5><p id="author" data-type="text" data-pk="#id#" data-original-title="{Author}" data-url="title/#id#" class="editable editable-click">#author#</p>
        <h5>ISBN <span id="refresh" class="btn btn-xs btn-warning pull-right"  title="{Refresh title}"><span class="glyphicon glyphicon-refresh"></span></span></h5><p id="isbn" data-type="text" data-pk="#id#" data-original-title="ISBN" data-url="title/#id#" class="editable editable-click">#isbn#</p>
        <h5>{Language}</h5><p id="lang" data-type="select" data-value="#lang#" data-pk="#id#" data-original-title="{Language}" data-url="title/#id#" class="editable editable-click" data-source="[{value: 'se', text: 'Svenska'}, {value: 'en', text: 'Engelska'}, {value: 'de', text: 'Tyska'}, {value: 'fr', text: 'Franska'}, {value: 'sp', text: 'Spanska'}]"></p>
        <h5>{Description}</h5><p id="desc" data-type="textarea" data-pk="#id#" data-original-title="{Description}" data-url="title/#id#" class="editable editable-click">#desc#</p>
        <h5>{Keywords}</h5><p id="keywords" data-type="select2" data-pk="#id#" data-original-title="{Keywords}" data-url="title/#id#" class="editable editable-click" >#keywords#</p>
            
    </div>
    <div class="col-md-5">
        <h5>{Publisher}</h5><p id="publisher" data-type="text" data-pk="#id#" data-original-title="{Publisher}" data-url="title/#id#" class="editable editable-click">#publisher#</p>
        <h5>{Year}</h5><p id="date" data-type="text" data-pk="#id#" data-original-title="{Year}" data-url="title/#id#" class="editable editable-click">#date#</p>
        <h5>{Code}</h5><p id="code" data-type="typeaheadjs" data-pk="#id#" data-original-title="{Code}" data-url="title/#id#" data-typeahead="{name: 'code', prefetch: 'ajax/codes_dataset'}" data-name="code" class="editable editable-click" data-value="#code#"></p>
            
        <h5>Länk:</h5><p><a href="#url#" title="Länk till post om titel">#url#</a></p>
        <h5>{Registered}</h5><p id="registered" data-type="date" data-pk="#id#" data-original-title="{Registered}" data-url="title/#id#" class="editable editable-click">#registered#</p>
        
        
    </div>
</div>


<div class="container well">
<h2>{Units}</h2>
    <table class="tablesorter">
	<thead>
		<tr>
		    <!-- cut:hcell -->
			<th class="#class#">#name#</th>
			<!-- /cut:hcell -->
			<!-- cut:hicell -->
			<th class="#class#"><span class="glyphicon glyphicon-#icon#"></span>#name#</th>
			<!-- /cut:hicell -->
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