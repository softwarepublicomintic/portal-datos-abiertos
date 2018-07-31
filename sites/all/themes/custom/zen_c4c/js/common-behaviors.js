/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */
(function ($, Drupal, window, document) {
  'use strict';

  Drupal.behaviors.C4C__CommonBehaviors = {
    attach: function (context, settings) {

      /*
       * Clickers: Hide referenced elements.
       */
      $('.js--clicker').each(function (index) {
        var element = $(this).attr('data-click');
        if ((element) && ($(element).length)) {
          $(element).hide();
        }
        else {
          $(this).hide();
        }
      });

      /**
       * Clickers: Click on a clicker.
       */
      $('.js--clicker').click(function () {
        var element = $(this).attr('data-click');
        if (element) {
          $(element).trigger('click');
        }
      });

      /*
       * Feature to fill textareas or textfields:
       */
      $('.form-item--filler').click(function () {
        var dataValue = $(this).attr('data-value');
        var dataDestination = $(this).attr('data-destination');
        if ($(dataDestination).length) {
          var processed = appendValue(dataValue, dataDestination, ',');
          switch (processed) {
            case 'ADDED':
              $(this).addClass('added');
              break;
            case 'REMOVED':
              $(this).removeClass('added');
              break;
          }
        }
      });


      /*
       * Function to add or remove an item to a field. This feature works with textareas
       * and textfields.
       */
      var appendValue = function (value, field, separator) {
        var currentFieldValue = $(field).val();
        if (!currentFieldValue) {
          $(field).val(value);
          return 'ADDED';
        }
        var currentValues = currentFieldValue.split(separator);
        var values = '';
        if (($.inArray(value, currentValues) < 0)) {
          currentValues.push(value);
          values = currentValues.join(separator);
          $(field).val(values);
          return 'ADDED';
        }
        else {
          currentValues = $.grep(currentValues, function (item) {
            return item !== value;
          });
          values = currentValues.join(separator);
          $(field).val(values);
          return 'REMOVED';
        }
        // return 'UNPROCESSED';
      };


    }
  };

})(jQuery, Drupal, this, this.document);
