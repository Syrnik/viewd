(function($){
    const ns = '.viewd';
    const section = '#viewd-section';

    // The prices section is (re)rendered by Vue after this script runs, so handlers are delegated from document
    $(document).off(ns)
        .on(`click${ns}`, `${section} .viewd-counter`, function () {
        const $span = $(this);
        const $wrap = $span.closest('p').find('.viewd-edit-wrap');
        $wrap.find('.viewd-counter-input').val($span.text().trim());
        $span.hide();
        $wrap.show();
        $wrap.find('.viewd-counter-input').trigger('focus');
    }).on(`click${ns}`, `${section} .viewd-cancel`, function () {
        const $wrap = $(this).closest('.viewd-edit-wrap');
        $wrap.hide();
        $wrap.closest('p').find('.viewd-counter').show();
    }).on(`click${ns}`, `${section} .viewd-save`, function () {
        const $save = $(this);
        const $wrap = $save.closest('.viewd-edit-wrap');
        const $cancel = $wrap.find('.viewd-cancel');
        const $input = $wrap.find('.viewd-counter-input');
        const $span = $wrap.closest('p').find('.viewd-counter');
        $save.prop('disabled', true);
        $cancel.prop('disabled', true);
        const $viewd_section = $save.closest(section);
        $.post('?plugin=viewd&action=set', {product_id: $viewd_section.data('product-id'), views: $input.val()})
            .done(function (r) {
                if (r && r.status === 'ok') {
                    const v = parseInt(r.data.views, 10);
                    $span.text(v);
                    $input.val(v);
                }
            })
            .always(function () {
                $save.prop('disabled', false);
                $cancel.prop('disabled', false);
                $wrap.hide();
                $span.show();
            });
    }
    );
})(jQuery)
