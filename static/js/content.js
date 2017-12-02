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

$("#modal-save-btn").click(function () {
    var tileid = $("#modal-save-btn").data("tile");
    var oldstyle = $("#tile-" + tileid).data("styleid");
    var newstyle = $("#style").val();
    var width = $("#width").val();
    var order = $("#order").val();
    $("#tile-" + tileid).data("styleid", newstyle);
    $("#tile-" + tileid + "-content").removeClass("tile-style-" + oldstyle);
    $("#tile-" + tileid + "-content").addClass("tile-style-" + newstyle);
    $("#tile-" + tileid).data("width", width);
    $("#tile-" + tileid).css("width", width);
    $("#tile-" + tileid).data("order", order);
    $("#tile-" + tileid).css("order", order);
    saveTile(tileid);
    $("#tile-options-modal").modal('hide');
});

$('#tile-options-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var tileid = button.data('tile');
    var tile = $("#tile-" + tileid);
    var modal = $(this);
    modal.find('#width').val(tile.data("width"));
    modal.find('#order').val(tile.data("order"));
    modal.find('#style').val(tile.data("styleid"));
    modal.find('#modal-save-btn').data("tile", tileid);
});

function saveTile(tileid) {
    var tile = $("#tile-" + tileid);
    var tile_content = $("#tile-" + tileid + "-content .tile-html");
    var page = tile.data("page");
    var styleid = tile.data("styleid");
    var width = tile.data("width");
    var order = tile.data("order");
    var content = "";
    if (tile_content.css("display") == "none") {
       content = tile_content.summernote("code");
       tile_content.summernote("destroy");
    } else {
        content = tile_content.html();
    }
    $.post("action.php", {
        action: "savetile",
        tileid: tileid,
        pubid: pubid,
        page: page,
        styleid: styleid,
        width: width,
        order: order,
        content: content
    });

}