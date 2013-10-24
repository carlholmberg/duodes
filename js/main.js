$(document).ready(function() {
    
    function update(from, to) {
        if (from > num_ids) return;
        $.get("titles/"+from+"/"+to, function(html) {
            $("table tbody").append(html);
            var resort = true;
            $("table").trigger("update", [resort]);
            update(from+40, to+40);
        });
    }

    var num_ids = 0;
    $.get("ajax/titleids", function(ids) {
        num_ids = ids;
        if (num_ids > 40) {
            $.get("ajax/tablesorter-nav", function(html) {
                $("table tfoot").append(html);
                $('table').tablesorterPager({
                    container: $(".pager"),
                    cssGoto  : ".pagenum",
                    removeRows: false,
                    output: 'Visar {startRow} - {endRow} av {filteredRows} rader (totalt: {totalRows})'
                });
                update(40, 80);
            });
        }
    });

  $.extend($.tablesorter.themes.bootstrap, {
    // these classes are added to the table. To see other table classes available,
    // look here: http://twitter.github.com/bootstrap/base-css.html#tables
    table      : 'table table-bordered',
    header     : 'bootstrap-header', // give the header a gradient background
    footerRow  : '',
    footerCells: '',
    icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
    sortNone   : 'bootstrap-icon-unsorted',
    sortAsc    : 'icon-chevron-up',
    sortDesc   : 'icon-chevron-down',
    active     : '', // applied when column is sorted
    filterRow  : '', // filter row class
  });

  // call the tablesorter plugin and apply the uitheme widget
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : [ "uitheme", "filter", "zebra" ],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset"
    }
  });

});