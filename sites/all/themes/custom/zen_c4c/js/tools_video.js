/**
 * @file
 * A JavaScript file for the theme.
 *
 * Method to add behaviour for the Video Carousel
 */

(function ($, Drupal, window, document) {

  'use strict';

  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.video_tools = {
    attach: function (context, settings) {
      if ($('.view-tools.view-display-id-block_4').length > 0) {

        // Detect item by default
        var img_src = $('.view-tools.view-display-id-block_4').find('.view-content .views-row:first-child img').attr('src');
        if ($('.view-tools.view-display-id-block_4').find('.view-content > .active').length > 0) {
          img_src = $('.view-tools.view-display-id-block_4').find('.view-content > .active img').attr('src');
        } else {
          $('.view-tools.view-display-id-block_4').find('.view-content .views-row:first-child').addClass('active');
        }

        var result = load_video_get_result(img_src);

        if (result > 0) {
          load_video_using_result_id(result);
        }


        // Detetc click action
        $('.view-tools.view-display-id-block_4').find('.view-content .views-row a').click(function() {
          img_src = $(this).parents('.views-row').find('img').attr('src');
          
          result = load_video_get_result(img_src);
         
          if (result > 0) {
            load_video_using_result_id(result);
          }

          return false;
        });
      }


      // Get img src 
      function load_video_get_result(img_src) {
        return img_src.split('=')[1];
      }

      // Load Video
      function load_video_using_result_id(id) {
        $.ajax({
          url: Drupal.settings.basePath + 'views/ajax',
          type: 'post',
          data: {
            view_name: 'tools',
            view_display_id: 'block_5', 
            view_args: id,
          },
          dataType: 'json',
          success: function (response) {
            if (response[1] !== undefined) {
              var viewHtml = response[1].data;
              $('.view-tools.view-display-id-block_4').find('.view-header .view-display-id-block_5').replaceWith(viewHtml);
            }
          }
        });
      }
    }
  };

})(jQuery, Drupal, this, this.document);
