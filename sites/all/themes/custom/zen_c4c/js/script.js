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
  Drupal.behaviors.ZenC4C = {
    attach: function (context, settings) {

      /*ARREGLAR LINKS DE DATOS ABIERTOS EN USOS*/
      jQuery(document).ready(function (){
        jQuery('.field-name-field-dataset-er .node-inventario > header > h2 a').each(function() {
          jQuery(this).contents().unwrap();
        });

        jQuery('.field-name-field-dataset-er .node-inventario > .field-name-field-endpoint > .field-items > .field-item').each(function() {
          var url = jQuery(this).text();
          jQuery(this).wrap('<a href="'+url+'" target="_blank"></a>');
        });
      });
      $('.page-c4c-group-user-add form#views-form-c4c-user-groups-block-1 .form-actions input[type="submit"]').click(function (event) {
        var selectedRoles = $('.page-c4c-group-user-add form#views-form-c4c-user-groups-block-1 .form-item-parameter-roles-settings-roles textarea').val();
        if (selectedRoles == '' || selectedRoles == 'undefined' || selectedRoles == null) {
          event.preventDefault();
          alert(Drupal.t('You must select at least one choice. If you want to add a single member, choose "Group member".'));
        }
      });

      // Separate all words in "Tools" page for each title:
      $('.split-words').each(function (index) {
        var text = $(this).find('.field-item').text();
        var split = text.split(' ');
        var content = '';
        $.each(split, function (key, value) {
          content += '<span class="part part' + key + '">' + value + '</span> ';
        });
        $(this).find('.field-item').html(content);
      });

	  $('.node-type-tools .view-display-id-block_4 .view-content').wrapInner('<div class="view-content-inner"><div class="scrolled-content scroll-pane horizontal-only"></div></div>');
	  //$('.scrolled-content').jScrollPane();
	  $('.scrolled-content').each(function() {
		$(this).jScrollPane({
			showArrows: $(this).is('.arrow'),
			verticalDragMinWidth: 130,
			verticalDragMaxWidth: 130,
			horizontalDragMinWidth: 130,
			horizontalDragMaxWidth: 130
		});
	  	var api = $(this).data('jsp');
	  	var throttleTimeout;
	  	$(window).bind('resize', function() {
			if (!throttleTimeout) {
		  		throttleTimeout = setTimeout(function() {
					api.reinitialise();
					throttleTimeout = null;
		  		}, 50);
			}
	    });
	  });

	  //$('<div class="categorias"></div>').('#c4c-faq-form #edit-category');

      /**
       * Search box placeholder.
       */
      $('.pane-header-standard .form-item-search-block-form input[type="text"]').attr('placeholder', Drupal.t('Search'));

      /**
       * Block to add users to a group: Hide role selector pane if field is
       * not present.
       */
      if (!$('.blockgroup--group-ad-user .form-item-parameter-roles-settings-roles').length) {
        $('.blockgroup--group-ad-user .select-roles').hide();
      }
      else {
        $('.blockgroup--group-ad-user .form-item-parameter-roles-settings-roles').hide();
        $('.blockgroup--group-ad-user .select-users .pane-title').hide();
      }

      /**
       * Translate content type: c4c/content/project/add/news
       * May be deprecated.
       */
      $('.zen-c4c--i18np').each(function () {
        var text = $(this).find('.pane-content').html();
        if ($(this).hasClass('rpl-dslsh')) {
          text = text.replace('_', ' ');
        }
        $(this).find('.pane-content').html(Drupal.t(text));
      });

      $(document).ready(function () {
        $('#mini-panel-header__standard .center-wrapper .col--middle').prepend('<div class="c4c-responsive-menu-wrapper"><ul class="menu"></ul></div>');
        $('.panel-pane.main-menu ul.menu li').each(function () {
          $('#mini-panel-header__standard .center-wrapper .col--middle .c4c-responsive-menu-wrapper ul.menu').append($(this).clone());
        });
        $('.login-register-menu ul.menu li').each(function () {
          $('#mini-panel-header__standard .center-wrapper .col--middle .c4c-responsive-menu-wrapper ul.menu').append($(this).clone());
        });
        $('.language-switcher ul li').each(function () {
          $('#mini-panel-header__standard .center-wrapper .col--middle .c4c-responsive-menu-wrapper ul.menu').append($(this).clone());
        });

        resizeowl();
      });

      // script que coloca los estilos de error a los input file y seletc en los formularios

      if ( $('.form-field-name-field-image .form-file').hasClass('error') ) {
        $('.form-field-name-field-image .form-file').siblings('.zen-c4c--file-upload').addClass('error');
      }

      // Chosen touch support.
      if ($('.chosen-container').length > 0) {
        $('.chosen-container').bind('touchstart', function (e) {
          e.stopPropagation(); e.preventDefault();
          // Trigger the mousedown event.
          $(this).trigger('mousedown');
        });
      }

      /**
       * VBO settings:
       */
      $('.vbo-views-form form input[type="submit"]').each(function () {
        if (!$(this).hasClass('i18n')) {
          var value = $(this).attr('value');
          $(this).attr('value', Drupal.t(value));
          $(this).addClass('i18n');
        }
      });

      function resizeowl() {
        if ($('.view-c4c-projects-std.view-display-id-block_2').length) {
          var owlwidth = $('.view-c4c-projects-std.view-display-id-block_2 .owl-item').width();
          $('.view-c4c-projects-std.view-display-id-block_3 .views-row').each(function () {
            $(this).width(owlwidth - 5);
            $(this).css('margin', 0);
          });
          $('.view-c4c-projects-std.view-display-id-block_1 .views-row').each(function () {
            $(this).width(owlwidth - 5);
            $(this).css('margin', 0);
          });
        }

        if ($('.view-c4c-applications-std.view-display-id-block_2').length) {
          var owlwidth2 = $('.view-c4c-applications-std.view-display-id-block_2 .owl-item').width();
          $('.view-c4c-applications-std.view-display-id-block_3 .views-row').each(function () {
            $(this).width(owlwidth2 - 5);
            $(this).css('margin', 0);
          });
          $('.view-c4c-applications-std.view-display-id-block_1 .views-row').each(function () {
            $(this).width(owlwidth2 - 5);
            $(this).css('margin', 0);
          });
        }
      }

      var rtime;
      var timeout = false;
      var delta = 200;
      $(window).resize(function () {
        rtime = new Date();
        if (timeout === false) {
          timeout = true;
          setTimeout(resizeend, delta);
        }
      });

      function resizeend() {
        if (new Date() - rtime < delta) {
          setTimeout(resizeend, delta);
        }
        else {
          timeout = false;
          resizeowl();
        }
      }

      // script para manipular el alto del modal de solicitar aplicacion
      var btnFinalizar = $(".page-group-add .layout-3col__full > .block-views");
      var btnAdd = $(".page-group-add .layout-3col__full > form .form-submit");


      if ( $(".page-group-add").find(".layout-3col__full > .block-views")) {
        btnAdd.after(btnFinalizar);
      }

      jQuery("#edit-status-wrapper").addClass("views-widget-filter-type");

      /**-- Script para reubicar el botón buscar en search--**/
      var textSearch = $('.pane-header-standard .col--middle .pane-search-form .form-item-search-block-form .form-text');
      var btnSearch2 = $('.pane-header-standard .col--middle .pane-search-form .form-actions');

      if ($(window).width() <= 480) {
        $(textSearch).focus(function() {
          $(btnSearch2).css('display','block');
        });
      }

      $(window).resize(function() {
        if ($(window).width() <= 480) {
          $(btnSearch2).css('display','none');

          $(textSearch).focus(function() {
            $(btnSearch2).css('display','block');
          });
        } else if ($(window).width() > 480) {
          $(btnSearch2).css('display','inline-block');

          $(textSearch).focus(function() {
            $(btnSearch2).css('display','inline-block');
          });
        }
      });
      /**-- End script --**/

      /**-- dotdotdot - 3 puntos auxiliares al final de cada parrafo (leer más)--**/
      var descripTools = $('.node-type-tools .views-field-field-tool-description .field-item'),
          titNovedades = $('.pane-c4c-news-events .view .views-row .content-title-description'),
          linkVideo = $('.page-videoc .scrolled-content .views-field-field-tool-title .field-content a');

      $(descripTools).dotdotdot({
        ellipsis: '...',
        wrap : 'letter',
        watch : window,
      });

      $(titNovedades).dotdotdot({
        ellipsis: '...',
        wrap : 'letter',
        watch : window,
      });

      $('.pane-c4c-news-events .first-title-description .views-field-title').dotdotdot({
        ellipsis: '...',
        wrap : 'letter',
        watch : window,
      });

      $('.pane-c4c-news-events .first-title-description .field-name-body').dotdotdot({
        ellipsis: '...',
        wrap : 'letter',
        watch : window,
      });

      $(linkVideo).dotdotdot({
        ellipsis: '...',
        wrap : 'letter',
        watch : window,
      });

      /**-- End script --**/

      /**-- Script para cuando no se encuentren resultados en la lista de preguntas frecuentes --**/
      var txtNoResultado = $('.node-type-tools .pane-c4c-faq-c4c-faq #ajax-reloaded-faq-list .questions-list li .position').length;

      if (txtNoResultado === 0) {
        $('.node-type-tools .pane-c4c-faq-c4c-faq #ajax-reloaded-faq-list .questions-list li').addClass('li-not');
      }

      /**-- End script --**/

      // Menu mobile
    jQuery('.pane-header-standard .pane-content .col--top').append('<div class="header-toggle"></div>');
      jQuery('.header-toggle').click(function() {
        jQuery('.pane-header-standard .pane-content .col--top .login-register-links').slideToggle('slow');
      });

      // Views page  datos.gov
      jQuery.getJSON("https://www.datos.gov.co/api/site_metrics.json?start=0&end=99999999999", function(data){
        jQuery('.view-c4c-access-log--std .views-row .field-content').text(data['page-views-total'])
      });

      /**-- Reubicar la cantidad de resultados en la sección aprender sobre datos --**/
      var cantResult = $('.page-data .view-header');
      $('.page-data .view-filters .views-widget-filter-combine').after(cantResult);
      /**-- End script --**/

      /** Script para agregar clase y diferenciar páginas de videos**/
      var dataUse = $('.page-videoc');

      $(dataUse).find('.data-use').closest('.pd-1col').addClass('page-data-use');
      $(dataUse).find('.data-publish').closest('.pd-1col').addClass('page-data-publish');
      $(dataUse).find('.data-develop').closest('.pd-1col').addClass('page-data-develop');
      /**-- End script --**/

      /**-- Script para reubicar redes sociales en interna de novedades --**/
      var redesSociales = $('.node-type-news .views-field-service-links');

      $(redesSociales).parent('div').find('.views-field-field-image').before(redesSociales);

      /**-- End script --**/

      $("#c4c-faq-form #edit-search").keypress(function (e) {
        if(e.which == 13) {
          $('#c4c-faq-form').submit();
        }
      });

	  	// Filtrar al seleccionar algún elemento de los selects
		$('.node-type-tools .chosen-processed.form-select').change(function(event) {
		$('.view-id-tools .views-submit-button input').trigger('click');
		});

	  	var location = window.location.href
		$('.page-faq #ajax-reloaded-faq-list > a').click(function(event){
			event.preventDefault()
			window.location.href = location
		})

		$('.node-type-news .field-name-body iframe').wrap('<div></div>"')
		$('.node-type-news .field-name-body iframe').parent().addClass('iframe-graph');
    }
  };

})(jQuery, Drupal, this, this.document);
