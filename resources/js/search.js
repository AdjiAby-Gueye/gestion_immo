(function($) {
    "use strict";

    $(document).on('mouseenter hover focus click', $('.top-bar, .top-bar-boxed').find('.search').find('input'), function (e)
    {
        $('.top-bar, .top-bar-boxed').find('.search').find('input').each(function() {
            $(this).on('focus', function() {
                $('.top-bar, .top-bar-boxed').find('.search-result').addClass('show')
            })

            $(this).on('focusout', function() {
                $('.top-bar, .top-bar-boxed').find('.search-result').removeClass('show')
            })
        })
    });

})($)
