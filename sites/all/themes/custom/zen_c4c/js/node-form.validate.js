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
  Drupal.behaviors.ZenC4CReplaceFileWidgetExtN = {
    attach: function (context, settings) {
      /**
       * Place file name in created label
       */
      /*
      $('.node-form .form-managed-file input[type="file"]').change(function () {
        var value = $(this).val().split('\\').pop();
        $(this).parent().find('.zen-c4c-filename').text(value);
      });
      */
      
      // Error fields:
      $('.error').removeClass('error'); // Part of a workaround...
      if (Drupal.settings.ZenC4CNodeForm) {
        if (Drupal.settings.ZenC4CNodeForm.ErrorFields) {
          console.dir(Drupal.settings.ZenC4CNodeForm.ErrorFields);
          $(Drupal.settings.ZenC4CNodeForm.ErrorFields).each(function( index, value ) {
            $(value).addClass('error');
          });
        }
      }
      
      // THIS IS STUPID AND SO UGLY... PLEASE, FIX THIS AS SOON AS POSSIBLE. I DID IT, BECAUSE
      // WE WERE IN HURRY...
      // ===== Project form:
      // --- Project type:
      var selectedProjectType = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormProject) {
        if (Drupal.settings.ZenC4CNodeFormProject.ErrorFieldType) {
          selectedProjectType = Drupal.settings.ZenC4CNodeFormProject.ErrorFieldType;
        }
      }
      if (selectedProjectType != 'NONE') {
        $('.form-field-name-field-project-type .chosen-container').removeClass('error');
      }
      // --- Project category:
      var selectedProjectCategory = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormProject) {
        if (Drupal.settings.ZenC4CNodeFormProject.ErrorFieldCategory) {
          selectedProjectCategory = Drupal.settings.ZenC4CNodeFormProject.ErrorFieldCategory;
        }
      }
      if (selectedProjectCategory != 'NONE') {
        $('.form-field-name-field-category .chosen-container').removeClass('error');
      }
      // --- Project year:
      var selectedProjectYear = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormProject) {
        if (Drupal.settings.ZenC4CNodeFormProject.ErrorFieldYear) {
          selectedProjectYear = Drupal.settings.ZenC4CNodeFormProject.ErrorFieldYear;
        }
      }
      if (selectedProjectYear != 'NONE') {
        $('.form-field-name-field-project-year input').removeClass('error');
      }
      // --- Project status:
      var selectedProjectStatus = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormProject) {
        if (Drupal.settings.ZenC4CNodeFormProject.ErrorFieldStatus) {
          selectedProjectStatus = Drupal.settings.ZenC4CNodeFormProject.ErrorFieldStatus;
        }
      }
      if (selectedProjectStatus != 'NONE') {
        $('#edit-field-project-status .chosen-container').removeClass('error');
      }
      
      // ===== Application form
      // --- Application type:
      var selectedAppType = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormApplication) {
        if (Drupal.settings.ZenC4CNodeFormApplication.ErrorFieldAppType) {
          selectedAppType = Drupal.settings.ZenC4CNodeFormApplication.ErrorFieldAppType;
        }
      }
      if (selectedAppType != 'NONE') {
        $('.form-field-name-field-application-type .chosen-container').removeClass('error');
      }
      // --- Application year:
      var selectedAppYear = 'NONE';
      if (Drupal.settings.ZenC4CNodeFormApplication) {
        if (Drupal.settings.ZenC4CNodeFormApplication.ErrorFieldAppYear) {
          selectedAppYear = Drupal.settings.ZenC4CNodeFormApplication.ErrorFieldAppYear;
        }
      }
      if (selectedAppYear != 'NONE') {
        $('.form-field-name-field-application-year .input').removeClass('error');
      }
      
      /*
      $('select').each(function() {
        if ($(this).hasClass('error')) {
          $(this).parent().find('.chosen-container').addClass('error');
        }
        else {
          $(this).parent().find('.chosen-container').removeClass('error');
        }
      });
      */
      
    }
  };

})(jQuery, Drupal, this, this.document);
