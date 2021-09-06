$(document).ready(($) => {
    $('.js-form-button').click(function (event) {
        event.preventDefault();
        const $form = $(event.currentTarget).closest('.js-form-container').find('.js-form');
        $form.submit();
    })
});
