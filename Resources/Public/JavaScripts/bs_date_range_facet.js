function solrBsDateRangeRequest(facetName, start, end) {
	url = jQuery('#' + facetName + '_url').val();
	start_date = start.format('YYYYMMDD');
	end_date = end.format('YYYYMMDD');
	url = url.replace(encodeURI('___FROM___'), start_date + '0000');
	url = url.replace(encodeURI('___TO___'), end_date + '0000');
	window.location.href = url;
}
