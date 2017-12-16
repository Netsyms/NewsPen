/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

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

var tobesaved = 0;

function makeNewTileAfterWait(page, style, width, order) {
    setTimeout(function () {
        if (tobesaved > 0) {
            makeNewTileAfterWait(page, style, width, order);
        } else {
            $.post("action.php", {
                action: "savetile",
                pubid: pubid,
                page: page,
                styleid: style,
                width: width,
                order: order,
                content: ""
            }, function (d) {
                window.location.href = window.location.href;
            });
        }
    }, 100);
}

/**
 * Make a new tile after saving any tiles being edited.
 * @param {type} page
 * @param {type} style
 * @param {type} width
 * @param {type} order
 * @returns {undefined}
 */
function newTile(page, style, width, order) {
    // Make sure everything is saved before loading page
    tobesaved = 0;
    $(".tile").each(function (i) {
        var tileid = $(this).data("tileid");
        if (tileEditing(tileid)) {
            tobesaved++;
            saveTile(tileid, function () {
                tobesaved--;
            });
        }
    });
    makeNewTileAfterWait(page, style, width, order);
}

function reloadAfterSaveWait() {
    setTimeout(function () {
        if (tobesaved > 0) {
            reloadAfterWait();
        } else {
            window.location.href = window.location.href;
        }
    }, 100);
}

function safeReload() {
    tobesaved = 0;
    $(".tile").each(function (i) {
        var tileid = $(this).data("tileid");
        if (tileEditing(tileid)) {
            tobesaved++;
            saveTile(tileid, function () {
                tobesaved--;
            });
        }
    });
    reloadAfterSaveWait();
}

/**
 * Returns true if the tile is being edited, 
 * false otherwise.
 * 
 * @param number tileid
 * @returns boolean
 */
function tileEditing(tileid) {
    return $("#tile-" + tileid + "-content .tile-html").css("display") == "none";
}

$("#edit-tile-save-btn").click(function () {
    var tileid = $("#edit-tile-save-btn").data("tile");
    var oldstyle = $("#tile-" + tileid).data("styleid");
    var oldorder = $("#tile-" + tileid).data("order");
    var oldpage = $("#tile-" + tileid).data("page");
    var newstyle = $("#style").val();
    var width = $("#width").val();
    var order = $("#order").val();
    var page = $("#page").val();
    $("#tile-" + tileid).data("styleid", newstyle);
    $("#tile-" + tileid + "-content").removeClass("tile-style-" + oldstyle);
    $("#tile-" + tileid + "-content").addClass("tile-style-" + newstyle);
    $("#tile-" + tileid).data("width", width);
    $("#tile-" + tileid).css("width", ((width * 1.0) / (pubcolumns * 1.0) * 100) + "%");
    $("#tile-" + tileid).css("flex-basis", ((width * 1.0) / (pubcolumns * 1.0) * 100) + "%");
    $("#tile-" + tileid).data("order", order);
    $("#tile-" + tileid).css("order", order);
    $("#tile-" + tileid).data("page", page);
    saveTile(tileid);
    if (oldorder != order || oldpage != page) {
        // refresh page because the order might not look right
        safeReload();
    }
    $("#tile-options-modal").modal('hide');
});

$("#edit-tile-del-btn").click(function () {
    var tileid = $("#edit-tile-save-btn").data("tile");
    $("#tile-" + tileid).css("display", "none");
    $.post("action.php", {
        action: "deltile",
        tileid: tileid
    }, function (d) {
        safeReload();
    });
    $("#tile-options-modal").modal('hide');
});

$("#new-tile-save-btn").click(function () {
    var page = $("#newpage").val();
    var style = $("#newstyle").val();
    var width = $("#newwidth").val();
    var order = $("#neworder").val();
    newTile(page, style, width, order);
});

$('#tile-options-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var tileid = button.data('tile');
    var tile = $("#tile-" + tileid);
    var modal = $(this);
    modal.find('#width').val(tile.data("width"));
    modal.find('#order').val(tile.data("order"));
    modal.find('#style').val(tile.data("styleid"));
    modal.find('#page').val(tile.data("page"));
    modal.find('#edit-tile-save-btn').data("tile", tileid);
});

function saveTile(tileid, callback) {
    var tile = $("#tile-" + tileid);
    var tile_content = $("#tile-" + tileid + "-content .tile-html");
    var page = tile.data("page");
    var styleid = tile.data("styleid");
    var width = tile.data("width");
    var order = tile.data("order");
    var content = "";
    if (tileEditing(tileid)) {
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
    }, function (resp) {
        if (null != callback) {
            callback();
        }
    });
}