function displayTitles(ids) {
    initTitles();
    if (ids > 40) {
        $.get("ajax/tablesorter-nav", function(html) {
            $("table tfoot").append(html);
            $("table tfoot th.pager").attr('colspan', $("table thead th").length);
            $('table').tablesorterPager({
                container: $(".pager"),
                cssGoto  : ".pagenum",
                removeRows: false,
                output: 'Visar {startRow} - {endRow} av {filteredRows} rader (totalt: {totalRows})'
            });
            update(ids, 40, 80);
        });
    }
}
    
function update(ids, from, to) {
    if (from > ids) return;
    $.get("titles/"+from+"/"+to, function(html) {
        $("table tbody").append(html);
        var resort = true;
        $("table").trigger("update", [resort]);
        update(ids, from+40, to+40);
    });
}

function initCopies() {
  // call the tablesorter plugin and apply the uitheme widget
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : [ "uitheme", "filter", "zebra", "group" ],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset",
      group_collapsible : true,
    },
    sortForce: [[0,0]]
  });
  $("table thead").find("th:eq(0)").trigger("sort");
}

function initTitles() {
  // call the tablesorter plugin and apply the uitheme widget
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : [ "uitheme", "filter", "zebra" ],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset",
      
    },
  });

}

$(document).ready(function() {

  $.extend($.tablesorter.themes.bootstrap, {
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

});