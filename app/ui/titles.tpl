<div class="container">
    <h1>#pagetitle#</h1>
    <form class="form-inline well" role="form" action="title/all" method="get">
        <div class="form-group">
            <label for="collection" class="control-label">Visa:</label>
        </div>
        <div class="form-group">
            <select name="collection" id="collection"><option value="all">Alla</option></select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-xs btn-default">Ok</button>
        </div>
    </form>
        
     
</div>

<div class="container">
<table class="tablesorter">
	<thead>
		<tr>
		    <!-- cut:hcell -->
			<th class="#class#">#name#</th>
			<!-- /cut:hcell -->
			<!-- paste:hcell -->
	</thead>
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
</div>
<script type="text/javascript">
    displayTitles(#ids#);
</script>