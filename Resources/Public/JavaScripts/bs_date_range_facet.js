
function SolrBsDateRangeFacetController() {
	var _this = this;

	this.init = function() {

		jQuery('.date-range-selector').each(function () {
			var $dateRangeSelector = $(this);
			var $facetName = $dateRangeSelector.data('facetName');

			var options = {
				"locale": {
					"cancelLabel": "Remove"
				}
			};

			if (moment.locale() == 'de') {
				options['locale'] = {
					"applyLabel": "Übernehmen",
					"cancelLabel": "Löschen",
					"fromLabel": "Von",
					"toLabel": "Bis",
					"customRangeLabel": "Custom"
				};
			}

			var startDate = jQuery('#' + $facetName + '_start_date').val();
			var endDate = jQuery('#' + $facetName + '_end_date').val();

			if (startDate && endDate) {
				options['startDate'] = startDate;
				options['endDate'] = endDate;
			} else {
				options['autoUpdateInput'] = false;
			}

			$dateRangeSelector.daterangepicker(options, function(start, end, label) {
				_this.solrBsDateRangeRequest($facetName, start, end);
			});

			$dateRangeSelector.on('cancel.daterangepicker', function(ev, picker) {
				$(this).val('');
				url = jQuery('#' + $facetName + '_url_reset').val();
				window.location.href = url;
			});
		});
	};

	this.solrBsDateRangeRequest = function(facetName, start, end) {
		url = jQuery('#' + facetName + '_url').val();
		start_date = start.format('YYYYMMDD');
		end_date = end.format('YYYYMMDD');
		url = url.replace(encodeURI('___FROM___'), start_date + '0000');
		url = url.replace(encodeURI('___TO___'), end_date + '0000');
		window.location.href = url;
	};

}

jQuery(document).ready(function() {
	var solrBsDateRangeFacetController = new SolrBsDateRangeFacetController();
	solrBsDateRangeFacetController.init();

	jQuery('body').on('tx_solr_updated', function() {
		solrBsDateRangeFacetController.init();
	});
});

