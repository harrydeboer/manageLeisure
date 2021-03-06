$(function() {
    $('.delete-modal-button').on('click', function () {
        $('.delete-modal').modal('show');
    });

    $('[name="contact"]').on('submit', function (event) {
        if ($('#contact_reCaptchaToken').val() === '') {
            // noinspection JSUnresolvedVariable
            grecaptcha.ready(function () {
                // noinspection JSUnresolvedVariable,JSUnresolvedFunction
                grecaptcha.execute($('#reCaptchaKey').data('key'), {action: 'contact'}).then(function (token) {
                    $('#contact_reCaptchaToken').val(token);
                    $('[name="contact"]').trigger('submit');
                });
            });
            event.preventDefault();
        }
    });

    let noHtmlTags = $('.no-html-tags');
    noHtmlTags.on('keypress', function (event) {
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

    noHtmlTags.on('input', function () {

        let errorMessage = $('#no-html-allowed');
        let pastedData = $(this).val();
        errorMessage.text('');

        if (
            pastedData.indexOf('<') !== -1
            || pastedData.indexOf('>') !== -1
            || pastedData.indexOf('[') !== -1
            || pastedData.indexOf(']') !== -1
        ) {
            errorMessage.text('No html allowed');

            $(this).val('');

            return false;
        }
    })
});
