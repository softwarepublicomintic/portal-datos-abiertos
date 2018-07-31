(function($) {
    Drupal.behaviors.BetterJumpMenu = {
        attach: function(context) {
            $('.ctools-jump-menu-hide')
                .once('ctools-jump-menu')
                .hide();

            $('.ctools-jump-menu-change')
                .once('ctools-jump-menu')
                .change(function() {
                    var external = $(this).data('external');
                    var loc = $(this).val();
                    var urlArray = loc.split('::');
                    var url;

                    if (urlArray[1]) {
                        url = urlArray[1];
                    } else {
                        url = loc;
                    }

                    if (typeof external != 'undefined') {
                        window.open(url, '_blank');
                    } else {
                        location.href = url;
                    }

                    return false;
                });

            $('.ctools-jump-menu-button')
                .once('ctools-jump-menu')
                .click(function() {
                    // Instead of submitting the form, just perform the redirect.

                    // Find our sibling value.
                    var $select = $(this).parents('form').find('.ctools-jump-menu-select');
                    var external = $select.data('external');

                    var loc = $select.val();
                    var urlArray = loc.split('::');
                    var url;

                    if (urlArray[1]) {
                        url = urlArray[1];
                    }
                    else {
                        url = loc;
                    }

                    if (typeof external != 'undefined') {
                        window.open(url, '_blank');
                    } else {
                        location.href = url;
                    }

                    return false;
                });
        }
    }
})(jQuery);