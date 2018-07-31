/**
 * @file
 * Managed File.
 */

(function ($, moduleName) {
  'use strict';

  /**
   * @constructor
   */
  function ManagedFile() {
    /**
     * File manager name, prefixed with module name.
     *
     * @type {String}
     */
    this.fileManagerID = '';
    /**
     * Stream wrapper scheme name.
     *
     * @type {String}
     */
    this.schemeName = '';
    /**
     * URL representation of stream wrapper scheme.
     *
     * @type {String}
     */
    this.schemeURL = '';
    /**
     * Path to the file manager library.
     *
     * @type {String}
     */
    this.basePath = '';
    /**
     * Uploading location (stream wrapper).
     *
     * @type {String}
     */
    this.location = '';
    /**
     * UI language.
     *
     * @type {String}
     */
    this.language = '';
  }

  ManagedFile.moduleName = moduleName;
  ManagedFile.prototype = Object.create(null);

  /**
   * Process uploaded file.
   *
   * @param {String} uri
   *   Uploaded file URI.
   * @param {String} element
   *   CSS selector for container with file field.
   * @param {Function} [success]
   *   Callback for execution when all were done.
   */
  ManagedFile.prototype.process = function (uri, element, success) {
    this.getFIDByURI(uri, function (fid) {
      var $container = $($(element).data('target'));

      if ($container.length > 0) {
        $container.children('[type=hidden]').val(fid);
        $container.children('[type=submit]').mousedown();

        success instanceof Function && success(fid);
      }
    });
  };

  /**
   * Convert file URI to ID.
   *
   * @param {String} uri
   * @param {Function} callback
   */
  ManagedFile.prototype.getFIDByURI = function (uri, callback) {
    $.get(Drupal.settings.basePath + moduleName + '/get_fid_by_uri', {uri: uri}, function (fid) {
      if (fid > 0) {
        callback(fid);
      }
    });
  };

  /**
   * Add an implementation of a file manager.
   *
   * @param {String} fileManager
   *   Machine name of file manager.
   * @param {Function} callback
   *   Processing callback.
   */
  ManagedFile.implement = function (fileManager, callback) {
    // Generate unique name for this implementation depending on manager name.
    var implementation = moduleName + '_' + fileManager;
    // CSS selector of fields that need to be extended by file manager.
    var className = fileManager + '-file-manager';
    // Each file manager could have its own settings. They are passed from
    // a backend and can be distinguished by hash, added after class name.
    var regexp = new RegExp('.*' + className + ' (\\w+).*', 'g');

    Drupal.behaviors[implementation] = {
      attach: function (context, settings) {
        $(context).find('.' + className).once().each(function () {
          var $element = $(this);

          if (!$element.find('.file').length) {
            var manager = new ManagedFile();

            manager = $.extend(manager, settings[this.className.replace(regexp, '$1')]);
            manager.fileManagerID = implementation;

            $element.before($('<span class="pseudo-link" data-target="#' + this.id + '">' + Drupal.t('Browse files') + '</span>').bind('click', function () {
              callback(manager, this);
            }));
          }
        });
      }
    };
  };

  window.ManagedFile = ManagedFile;
})(jQuery, 'managed_file');
