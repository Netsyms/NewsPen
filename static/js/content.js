$("#new_tile_btn").click(function () {
    // TODO
});

$(".edit-btn").click(function () {
   var tileid = $(this).data("tile");
   $("#tile-" + tileid + "-content .tile-html").summernote({
      focus: true
   });
});

$(".save-btn").click(function () {
   var tileid = $(this).data("tile");
   saveTile(tileid);
});

function saveTile(tileid) {
    var tile = $("#tile-" + tileid);
    var tile_content = $("#tile-" + tileid + "-content .tile-html");
    var page = tile.data("page");
    var styleid = tile.data("styleid");
    var width = tile.data("width");
    var order = tile.data("order");
    $.post("action.php", {
        action: "savetile",
        tileid: tileid,
        pubid: pubid,
        page: page,
        styleid: styleid,
        width: width,
        order: order,
        content: tile_content.summernote("code")
    }, function (d) {
        tile_content.summernote("destroy");
    });
    
}