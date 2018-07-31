/**
 * @file
 * Plugin jQuery Tablesorter.
 */

(function ($) {
  Drupal.behaviors.tablesorter = {
    attach: function (context, settings) {
      var widgets = [];
      var widgetsZebra = [];

      if (settings.tablesorter) {
        if (settings.tablesorter.zebra == 1) {
          widgets.push('zebra');
        }
        widgetsZebra.push(settings.tablesorter.odd);
        widgetsZebra.push(settings.tablesorter.even);
      }

      $('.tablesorter').each(function (idx, table) {
        $(table).once('tablesorter', function () {
          $(table).tablesorter({
            widgets: widgets,
            widgetsZebra: {
              css: widgetsZebra
            }
          });
          if ($("#tablesorter_pager").length != 0) {
            $(table).tablesorterPager({
              container: $("#tablesorter_pager")
            });
          }
        });
      });
    }
  };
})(jQuery);
