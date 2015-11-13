<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Solr_Facet_FacetRendererFactory::registerFacetType(
	'dateRangeBootstrap',
	'MbhSoftware\\SolrBsDateRangeFacet\\Facet\\DateRangeFacetRenderer',
	'MbhSoftware\\SolrBsDateRangeFacet\\Query\\FilterEncoder\\DateRange',
	'MbhSoftware\\SolrBsDateRangeFacet\\Query\\FilterEncoder\\DateRange'
);