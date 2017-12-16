/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$('#name').on('input propertychange paste', function () {
    $('#name_title').text($('#name').val());
});

$('#password_protect').change(function () {
    if ($(this).prop('checked')) {
        $("#password").css('display', 'block');
    } else {
        $("#password").css('display', 'none');
    }
})