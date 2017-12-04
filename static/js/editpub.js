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