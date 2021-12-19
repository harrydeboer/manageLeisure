$('.delete-modal-button').on('click', function() {
    $('.delete-modal').modal('show');
});

$('#contact_send').on('click', function (event) {
    event.preventDefault();
    grecaptcha.ready(function() {
        grecaptcha.execute($('#reCaptchaKey').data('key'), {action: 'contact'}).then(function(token) {
            $('#contact_reCaptchaToken').val(token);
            $('[name="contact"]').trigger('submit');
        });
    });
});
