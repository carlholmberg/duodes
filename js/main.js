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
            $(".pagesize").append('<option value="'+ids+'">'+ids+'</option>');
            
            update('titles', ids, 40, 80);
        });
    }
}
 
function displayUsers(ids) {
    initUsers();
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
            $(".pagesize").append('<option value="'+ids+'">Alla</option>');
            
            update('users', ids, 40, 80);
        });
    }
}
   
function update(what, ids, from, to) {
    if (from > ids) return;
    $.get(what+"/"+from+"/"+to, function(html) {
        $("table tbody").append(html);
        var resort = true;
        $("table").trigger("update", [resort]);
        update(what, ids, from+40, to+40);
    });
}

function initCopies() {
  // call the tablesorter plugin and apply the uitheme widget
  var numrows = $("table tbody tr").length;
  var widgets = Array();
  if (numrows > 20) {
    widgets = [ "uitheme", "filter", "zebra", "group" ];
  } else {
    widgets = [ "uitheme", "zebra"];
    $("table tfoot tr").hide();
  }
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : widgets,
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset",
      group_collapsible : true,
      filter_hideFilters : true,
    },
    sortForce: [[0,0]]
  });
  $("table thead").find("th:eq(0)").trigger("sort");
}

function initBorrowed() {
  $("table tfoot tr").hide();
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : [ "uitheme", "zebra"],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset",
      group_collapsible : true,
      filter_hideFilters : true,
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
    widgets : [ "uitheme", "filter", "zebra"],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset",
    },
  });
}

function initUsers() {
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
  });

  $("table thead").find("th:eq(0)").trigger("sort");
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