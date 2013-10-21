<div class="container">
    <h1>{Title}(#id#): #title#</h1>
    <div class="well">
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
	<tfoot>
		<tr>
		    <!-- cut:fcell -->
			<th>#name#</th>
			<!-- /cut:fcell -->
			<!-- paste:fcell -->
        </tr>
		<tr>
			<th colspan="5" class="pager form-horizontal">
				<a class="btn first"><span class="glyphicon glyphicon-fast-backward"></span></a>
				<a class="btn prev"><span class="glyphicon glyphicon-backward"></span></a>
				<span class="pagedisplay"></span>
				<a class="btn next"><span class="glyphicon glyphicon-forward"></span></a>
				<a class="btn last"><span class="glyphicon glyphicon-fast-forward"></span></a>
				<select class="pagesize input-mini" title="Välj sidstorlek">
					<option selected="selected" value="20">20</option>
					<option value="40">40</option>
					<option value="60">60</option>
					<option value="80">80</option>
					<option value="100">100</option>
					<option value="120">120</option>
				</select>
				<select class="pagenum input-mini" title="Välj sidnummer"></select>
			</th>
		</tr>
	</tfoot>
	<tbody>
	    <!-- paste:tbody -->
	</tbody>
</table>
</div>