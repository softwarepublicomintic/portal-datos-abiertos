/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document) {

  'use strict';

  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.ZenC4CReplaceFileWidget = {
    attach: function (context, settings) {
      
      /**
       * Hide non-wanted fields:
       */
      $('.zen-c4c--replace-widget input[type="file"], .zen-c4c--replace-widget input[type="submit"]').hide();
      
      /**
       * Theme user picture widget field at user_register form.
       */
      $('.page-user-register .form-managed-file .zen-c4c--file-upload .trigger').on('click',function(event){
        var op = $(this).attr('op');
        switch(op) {
          case 'choose':
            var button = $(this).parent().attr('choose');
            break;
          case 'upload':
            var button = $(this).parent().attr('upload');
            break;
          case 'remove':
            var button = $(this).parent().attr('remove');
            break;
        }
        $('input[name="' + button + '"]').mousedown().trigger('click');
        event.preventDefault();
      });
      $('.page-user-register .form-managed-file input[type="file"]').change(function () {
        var value = $(this).val().split('\\').pop();
        $(this).parent().find('.zen-c4c--file-upload .current').text(value);
      });
     

      

      
    }
  };

})(jQuery, Drupal, this, this.document);
