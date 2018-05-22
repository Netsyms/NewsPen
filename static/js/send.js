/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */


$("#subject").on("keyup", function () {
    $("#subjectpreview").text($("#subject").val());
});

$("#message").on("keyup", function () {
    $("#messagepreview").html(snarkdown($("#message").val()));
});

$("#sendform").submit(function () {
    $("#sendbtn").attr("disabled", true);
    $("#sendbtn").prop("disabled", true);
    $("#cancelbtn").attr("disabled", true);
    $("#cancelbtn").prop("disabled", true);
    $("#sendbtn").html("<i class=\"fas fa-spinner fa-spin\"></i> Sending...");
});