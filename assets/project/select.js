const initWorkersSelect = () => {
    const $ = window.jQuery;
    if (!$ || !$.fn.select2) return;

    $('select[id$="_workers"][multiple]').each(function () {
        const $el = $(this);

        // Evite une double init si Turbo repasse sur la page
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        $el.select2();
    });
};

const destroyWorkersSelect = () => {
    const $ = window.jQuery;
    if (!$) return;

    $('select[id$="_workers"][multiple]').each(function () {
        const $el = $(this);
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
    });
};

document.addEventListener('turbo:load', initWorkersSelect);
document.addEventListener('turbo:before-cache', destroyWorkersSelect);
