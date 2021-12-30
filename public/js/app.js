$('.delete-modal-button').on('click', function() {
    $('.delete-modal').modal('show');
});

$('[name="contact"]').on('submit', function (event) {
    if ($('#contact_reCaptchaToken').val() === '') {
        grecaptcha.ready(function() {
            grecaptcha.execute($('#reCaptchaKey').data('key'), {action: 'contact'}).then(function(token) {
                $('#contact_reCaptchaToken').val(token);
                $('[name="contact"]').trigger('submit');
            });
        });
        event.preventDefault();
    }
});

$('.no-html-tags').on('keypress', function(event) {
    let errorMessage = $('#no-html-allowed');
    errorMessage.text('');

    /** No <>[] characters allowed. */
    if (
        event.key === '<'
        || event.key === '>'
        || event.key === '['
        || event.key === ']'
    ) {
        errorMessage.text('No html allowed');

        return false;
    }
});
