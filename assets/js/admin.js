(function ($) {
	"use strict";

	$(document).ready(function () {
		corona_admin.ready();
	});

	$(window).load(function () {
		corona_admin.load();
	});

	var corona_admin = window.$corona_admin = {

		/**
		 * Call functions when document ready
		 */
		ready: function () {
			this.select_site();
		},

		/**
		 * Call functions when window load.
		 */
		load: function () {

		},

		// CUSTOM FUNCTION IN BELOW

		select_site: function () {
			$('select[name=corona_countries]').on('change', function (e) {
				var shortcode = '';
				var shortcodeBegin = '[corona';
				var shortcodeEnd = ']';
				var countryName = $(this).val();

				shortcode = shortcodeBegin;
				if (countryName) {
					shortcode += ' country="'+countryName+'" title="'+countryName+'"';
				}
				shortcode += shortcodeEnd;
				$('#corona_shortcode').html(shortcode);
			});
		},

	};

})(jQuery);