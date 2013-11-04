<div class="container well">
    <!-- cut:editing -->
    <button id="delete" class="btn btn-danger pull-right">{Delete}</button>
    
    <!-- /cut:editing -->
    <!-- paste:editing -->
    <h1><span id="firstname" data-type="text" data-pk="#id#" data-original-title="{Firstname}" data-url="user/#id#" class="editable editable-click">#firstname#</span> <span id="lastname" data-type="text" data-pk="#id#" data-original-title="{Lastname}" data-url="user/#id#" class="editable editable-click">#lastname#</span></h1>
    <div class="col-md-4">
        <h5>{Email}</h5><p id="email" data-type="email" data-pk="#id#" data-original-title="{Email}" data-url="user/#id#" class="editable editable-click">#email#</p>
        <h5>{Class}</h5><p id="class" data-type="text" data-pk="#id#" data-original-title="{Class}" data-url="user/#id#" class="editable editable-click">#class#</p>
        <h5>{User id}</h5><p id="uid" data-type="text" data-pk="#id#" data-original-title="{User id}" data-url="user/#id#" class="editable editable-click"">#uid#</p>

    </div>
    <div class="col-md-4">
            <h5>{Level}</h5><p id="level" data-type="select" data-value="#level#" data-pk="#id#" data-original-title="{Level}" data-url="user/#id#" class="editable editable-click" data-source="[{value: 1, text: 'Låntagare'}, {value: 2, text: 'Circ'}, {value: 3, text: 'Personal'}, {value: 4, text: 'Administratör'}]">#level#</p>
        <h5>{Status}</h5><p id="status" data-type="select" data-value="#status#" data-pk="#id#" data-original-title="{Status}" data-url="user/#id#" class="editable editable-click" data-source="[{value: 1, text: 'Aktiv'}, {value: 0, text: 'Inaktiv'}]">#status#</p> 
    </div>
    <div class="col-md-4">
    
    </div>
</div>


<div class="container well">
<h2>{Borrowed books}</h2>
    <table class="tablesorter">
	<thead>
		<tr>
		    <!-- cut:hcell -->
			<th class="#class#">#name#</th>
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
    initBorrowed();
</script>
</div>