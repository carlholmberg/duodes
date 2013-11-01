<div class="container">  
    <ul class="nav nav-tabs" id="tabs">
        <li><a href="#return" data-toggle="tab"><span class="glyphicon glyphicon-repeat"></span> {Return}</a></li>
        <li><a href="#borrow" data-toggle="tab"><span class="glyphicon glyphicon-share-alt"></span> {Borrow}</a></li>
    </ul>
    <form class="form" id="circ" method="post" action="circ" >
        <div class="tab-content">
            <div class="tab-pane container well" id="return">
                <h4>{Scan or enter the barcode for the book}</h4>
                <div class="input-append">
                    <input type="text" class="span2" id="barcode" name="barcode" />
                    <button type="submit" class="btn btn-primary">{Return}</button>
                </div>
            </div>
        
            <div class="tab-pane container well" id="borrow">
                <h4>{Scan or enter the barcode for the book and user}</h4>
	            <div class="input-append">
	                <input type="text" id="bc1" class="span2" name="bc1" />
	                <input type="text" id="bc2" class="span2" name="bc2" />
	                <button type="submit" class="btn btn-primary">{Borrow}</button>
	            </div>    
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if (e.target) {
            var pieces = e.target.toString().split('/');
            $(pieces.pop()).find('input').first().focus();
        }
        if (e.relatedTarget) {
            var pieces = e.relatedTarget.toString().split('/');
            $(pieces.pop()).find('input').val('');
        }
    });
    $('#tabs a[href="##active#"]').tab('show');
    
      $('#circ').on('submit', function(e) {
        var bc1 = $('#bc1');
        var bc2 = $('#bc2');
        var bc = $('#barcode');
        if (bc.val() !== '') return;
        if (bc1.val() !== '' && bc2.val() !== '') return;
        if (bc1.val() !== '' && bc2.val() == '') bc2.focus();
        if (bc2.val() !== '' && bc1.val() == '') bc1.focus();
        e.preventDefault();
    });
</script>