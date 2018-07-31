/**
 * @file
 * A JavaScript file for the c4c_faq module.
 *
 */

(function ($, Drupal, window, document) {

  'use strict';

  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.c4c_faq = {
    attach: function (context, settings) {
      // Show Answer
      c4c_faq_show_answer();

      // Hide Answer
      c4c_faq_hide_answer();

      // Show Answer
      function c4c_faq_show_answer() {
        $('.questions-list').find('a.read-more').click(function() {
          // Add class to open
          $(this).parents('li').addClass('open');

          // Hide the Show more Link
          $(this).parents('li').find('.read-more').css('display', 'none');

          // Show the Hide Link
          $(this).parents('li').find('.hide-answer').css('display', 'inline-block');

          // Show Answer
          $(this).parents('li').find('.answer').show('fast');

          return false;
        });
      }

      // Hide Answer
      function c4c_faq_hide_answer() {
        $('.questions-list').find('a.hide-answer').click(function() {
          // Remove class to open
          $(this).parents('li').removeClass('open');

          // Hide the Hide answer Link
          $(this).parents('li').find('.hide-answer').css('display', 'none');

          // Show the Read more Link
          $(this).parents('li').find('.read-more').css('display', 'inline-block');

          // Show Answer
          $(this).parents('li').find('.answer').hide('fast');

          return false;
        });
      }

      function check_navigation_display(el) {
          //accepts a jQuery object of the containing div as a parameter
          if ($(el).children('li').first().is(':visible')) {
              $(el).parent('div').find('.pager-previous').hide();
          } else {
              $(el).parent('div').find('.pager-previous').show();
          }

          if ($(el).children('li').last().is(':visible')) {
              $(el).parent('div').find('.pager-next').hide();
          } else {
              $(el).parent('div').find('.pager-next').show();
          }
      }
    }
  };

})(jQuery, Drupal, this, this.document);
