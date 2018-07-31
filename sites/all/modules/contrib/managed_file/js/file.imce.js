/**
 * @file
 * IMCE file manager for Managed File.
 */

(function ($, fileManager) {
  'use strict';

  ManagedFile.implement(fileManager, function (manager, triggeringElement) {
    window[manager.fileManagerID] = function (imce_window) {
      var imce = imce_window.imce;

      // In case, when IMCE call this method, the "filename" parameter will
      // exist and "lastFid" method will return the "undefined" value, because
      // selected data will be cleared at this moment. Otherwise, when user
      // clicks on the "Insert file" button, the "filename" parameter will
      // have the "true" value and "lastFid" method returns necessary value.
      imce.send = function (filename) {
        filename = imce.lastFid() || filename;

        // The "filename" can be "true" here only if a user clicks
        // on the "Insert file" button without any selected file.
        if (true === filename) {
          imce.setMessage(Drupal.t('You have not selected any file.'), 'error');
        }
        else {
          manager.process(manager.schemeName + '://' + imce.fileGet(filename).relpath, triggeringElement);
          imce_window.close();
        }
      };

      return imce.opAdd({
        name: 'sendto',
        title: Drupal.t('Insert file'),
        func: imce.send
      });
    };

    window.open(
      Drupal.settings.basePath + fileManager + '/' + manager.schemeName + '?app=nomatter|imceload@' + manager.fileManagerID,
      '',
      'width=760,height=560,resizable=1'
    );
  });
})(jQuery, 'imce');
