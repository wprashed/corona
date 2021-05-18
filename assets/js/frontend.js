(function ($) {
	"use strict";

	$(document).ready(function () {
		corona_frontend.ready();
	});

	$(window).load(function () {
		corona_frontend.load();
	});

	var corona_frontend = window.$corona_frontend = {

		/**
		 * Call functions when document ready
		 */
		ready: function () {
			this.map();
			this.chart();
		},

		/**
		 * Call functions when window load.
		 */
		load: function () {

		},

		// CUSTOM FUNCTION IN BELOW

		map: function () {
			var data = corona.countries;
			for (var i = 0; i < data.length; i++) {
				var $country = $('.corona-map [title="' + data[i].country + '"]');
				var level = 0
				if ($country.length){
					var cases = data[i].cases;
					var deaths = data[i].deaths;
					if (cases < 10) {
						level = 1;
					}
					if (cases > 100) {
						level = 2;
					}
					if (cases > 1000) {
						level = 3;
					}
					if (cases > 10000) {
						level = 6;
					}
					if (cases > 20000) {
						level = 6;
					}
					if (cases > 50000) {
						level = 6;
					}
					$country.attr('data-level', level);
					$country.attr('data-cases', cases);
					$country.attr('data-deaths', deaths);
				}
			}

			var $description = $(".corona-map .corona-tooltip");
			var confirmed_label = $description.attr('data-confirmed');
			var deaths_label = $description.attr('data-deaths');

			$('.corona-map path').mouseenter(function() {
				var cases = $(this).attr('data-cases') ? $(this).attr('data-cases') : 0;
				var deaths = $(this).attr('data-deaths') ? $(this).attr('data-deaths') : 0;
				$description.addClass('active');
				$description.html(
					'<span class="tt-title">' + $(this).attr('title') + '</span>' +
					'<br/><span class="tt-number">'+confirmed_label+': ' + cases +'</span>' +
					' - <span class="tt-number">'+deaths_label+': ' + deaths +'</span>'
				);
			}).mouseleave(function() {
				$description.removeClass('active');
			});

			$('.corona-map').on('mousemove', function(e){
				$description.css({
					left:  e.offsetX + 20,
					top:   e.offsetY - 40
				});
			
			});
		},

		chart: function () {
			$('.corona-chart canvas').each(function(index, value) {
				var data = corona.history;
				var labels = Object.keys(data.cases);
				var cases = Object.values(data.cases);
				var deaths = Object.values(data.deaths);
				var recovered = Object.values(data.recovered);
				var label_confirmed = $(this).data('confirmed');
				var label_deaths = $(this).data('deaths');
				var label_recovered = $(this).data('recovered');
				var country = $(this).data('country');
				if (country) {
					var thisCountry = $(this).data('json');
					cases = Object.values(thisCountry.timeline.cases);
					deaths = Object.values(thisCountry.timeline.deaths);
					recovered = Object.values(thisCountry.timeline.recovered);
					labels = Object.keys(thisCountry.timeline.cases);
				}

				var pointRadius = new Array();
				var i, n = labels.length;
				for (i = 0; i < n; ++i) {
					pointRadius[i] = 0;
					if (i == n-1) {
						pointRadius[i] = 4;
					}
				}

				new Chart($(this), {
					type: 'line',
					data: {
						labels: labels,
						datasets: [
							{
								label: label_confirmed,
								borderColor: '#EF5350',
								backgroundColor: '#EF5350',
								data: cases,
								fill: false,
								pointRadius: pointRadius,
								pointHoverRadius: 5
							},
							{
								label: label_deaths,
								borderColor: '#515A5A',
								backgroundColor: '#515A5A',
								data: deaths,
								fill: false,
								pointRadius: pointRadius,
								pointHoverRadius: 5
							},
							{
								label: label_recovered,
								borderColor: '#2ECC71',
								backgroundColor: '#2ECC71',
								data: recovered,
								fill: false,
								pointRadius: pointRadius,
								pointHoverRadius: 5
							}
						]
					},
					options: {
						responsive: true,
						tooltips: {
							position: 'nearest',
							mode: 'index',
							intersect: false,
						},
						scales: {
							xAxes: [{
								ticks: {
									autoSkip: true,
								}
							}]
						}
					}
				});
			});
		}

	};

})(jQuery);