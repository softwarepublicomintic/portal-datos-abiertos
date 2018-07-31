/**
 * @file
 * CKFinder file manager for Managed File.
 */

(function ($, fileManager) {
  'use strict';

  ManagedFile.implement(fileManager, function (manager, triggeringElement) {
    var ckfinder = new CKFinder();

    $.each(['basePath', 'language'], function () {
      ckfinder[this] = manager[this];
    });

    ckfinder.selectActionFunction = function (url) {
      manager.process(manager.schemeName + ':' + url.replace(new RegExp(manager.schemeURL), ''), triggeringElement);
    };

    ckfinder.popup();
  });
})(jQuery, 'ckfinder');
