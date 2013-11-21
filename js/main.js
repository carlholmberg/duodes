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
            
            update('titles', ids, 40, 160);
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
            
            update('users', ids, 40, 160);
        });
    }
}
   
function update(what, ids, from, to) {
    if (from > ids) {
        $('.editable').editable({
            emptytext: "Tom",
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg;
            }
        });
        return;
    };
    $.get(what+"/"+from+"/"+to, function(html) {
        $("table tbody").append(html);
        var resort = true;
        $("table").trigger("update", [resort]);
        update(what, ids, from+120, to+120);
    });
}

function initCopies() {
  // call the tablesorter plugin and apply the uitheme widget
  var numrows = $("table tbody tr").length;
  var widgets = Array();
  if (numrows > 20) {
    widgets = [ "uitheme", "filter", "group" ];
  } else {
    widgets = [ "uitheme"];
    $("table tfoot tr").hide();
  }
  $("table").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
    widgets : widgets,
    widgetOptions : {
      filter_reset : ".reset",
      widthFixed: true,
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
    widgets : [ "uitheme"],
    widgetOptions : {
      filter_reset : ".reset",
      widthFixed: true,
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
    widgets : [ "uitheme", "filter", "resizable" ],
    widgetOptions : {
      widthFixed: true,
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
    widgets : [ "uitheme", "filter", "group" ],
    widgetOptions : {
      widthFixed: true,
      filter_reset : ".reset",
      group_collapsible : true,
    },
  });

  $("table thead").find("th:eq(0)").trigger("sort");
}


$(document).ready(function() {
    if($.tablesorter) {
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
    }
    
    if ($('#collection').length) {
        $.get("ajax/collections-opt", function(html) {
            $("#collection").append(html);
        });
    }
    
    if ($('.class_list').length) {
        $.get("ajax/class", function(html) {
            $(".class_list").append(html);
        });
    }
    
    if ($('#fixedcoll').length) {
        $.get("ajax/collections-fixed", function(html) {
            $("#fixedcoll").append(html);
        });
        $("#fixedcoll").on('change', function(ev) {
            $.post("ajax/setcollfixed", { val: $("#fixedcoll").val() });
        });
    }
    
    $('#uid').datepicker({
        format: "yymmdd",
        startView: 2,
        endDate: "today",
        language: "sv"
    });
    
    if($('.action_delete').length) {
        $('.action_delete').on('click', function(ev) {
            var todelete = $(this);
            ev.preventDefault();
            bootbox.dialog({
                message: "Är du säker på att du vill radera?",
                title: "Radera",
                buttons: {
                    success: {
                        label: "Nej",
                        className: "btn-default"
                    },
                    danger: {
                        label: "Ja",
                        className: "btn-danger",
                        callback: function() {
                            $.ajax({
                                type: "POST",
                                url: todelete.attr('href')+"/delete",
                                success: function(href) {
                                    location.reload();  
                                }
                            });
                        }
                    },
                }
            });
            
        });
    }
    
    if($('.action_return').length) {
        $('.action_return').on('click', function(ev) {
            ev.preventDefault();
            $.post($(this).attr('href'), {action: 'return'}, function(href) {
                location.reload();  
            });
        });
    }

});