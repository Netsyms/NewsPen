/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$('#name').on('input propertychange paste', function () {
    $('#name_title').text($('#name').val());
});

$("#emails").tagsInput({
    height: "100%",
    width: "100%",
    defaultText: "Click to add",
    onAddTag: function (tag) {
        if (!
/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(tag)) {
            $("#emails").removeTag(tag);
            alert(tag + " does not appear to be a valid email address.");
        } else if (tag.length > 255) {
            $("#emails").removeTag(tag);
            alert(tag + " is too long.  Email addresses must be less than 256 characters in length.");
        }
    }
});