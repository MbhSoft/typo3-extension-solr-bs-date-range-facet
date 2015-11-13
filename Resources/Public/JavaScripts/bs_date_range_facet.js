function solrBsDateRangeRequest(facetName, delimiter) {
	if (jQuery('#start_date_'+facetName).val() != '' &&  jQuery('#end_date_'+facetName).val() != '' ) {
		url = jQuery('#' + facetName + '_url').val();
		start_date = convertToDateString(jQuery('#start_date_'+facetName).datepicker('getDate'));
		end_date = convertToDateString(jQuery('#end_date_'+facetName).datepicker('getDate'));
		requestUrls[facetName] = url + encodeURI(start_date + delimiter + end_date);
		facetsOptionChanged(requestUrls[facetName]);
	};
}

function convertToDateString (date) {
	return $.prototype.datepicker.DPGlobal.formatDate(date, 'yyyymmdd', 'de');
}