<div class="container well">
    <h1>#title#</h1>
    <div class="col-md-2">
        <img id="cover" src="image/#isbn#" alt="Title image" />
        <p><strong>Antal: #total#</strong><br><em>(varav #borrowed# utlånade)</em></p>
    </div>
    <div class="col-md-5">
        <h5>{Author}</h5><p>#author#</p>
        <h5>ISBN</h5><p>#isbn#</p>
        <h5>{Language}</h5><p>#lang#</p>
        <h5>{Description}</h5><p>#desc#</p>
        <h5>{Keywords}</h5><p>#keywords#</p>
            
    </div>
    <div class="col-md-5">
        <h5>{Publisher}</h5><p>#publisher#</p>
        <h5>{Year}</h5><p>#date#</p>
        <h5>{Code}</h5><p>#code#</p>
            
        <h5>Länk:</h5><p><a href="#url#" title="Länk till post om titel">#url#</a></p>
        <h5>{Registered}</h5><p>#registered#</p>
        
        
    </div>
</div>


<div class="container well">
<h2>{Copies}</h2>
    <table class="tablesorter">
	<thead>
		<tr>
		    <!-- cut:hcell -->
			<th class="#class#"><span class="glyphicon glyphicon-#icon#"></span>#name#</th>
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