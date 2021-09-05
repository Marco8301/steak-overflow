$(document).ready(($) => {
    $('.js-button-delete').click(function (event) {
        event.preventDefault();
        const $form = $(event.currentTarget).closest('.js-form-delete-container').find('.js-form-delete');
        $form.submit();
    })
});
