<?php
/**
 *  Copyright notice
 *
 *  (c) 2015 Marc Bastian Heinrichs <mbh@mbh-software.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */
namespace MbhSoftware\SolrBsDateRangeFacet\Facet;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Date range facet renderer.
 */
class DateRangeFacetRenderer extends \ApacheSolrForTypo3\Solr\Facet\AbstractFacetRenderer {

	/**
	 * Provides the internal type of facets the renderer handles.
	 * The type is one of field, range, or query.
	 *
	 * @return string Facet internal type
	 */
	public static function getFacetInternalType() {
		return \ApacheSolrForTypo3\Solr\Facet\Facet::TYPE_RANGE;
	}

	protected function renderFacetGeneralOptions() {
		$this->loadJavaScriptFiles();
		$this->loadStylesheets();
	}

	/**
	 * Renders a date renage facet by providing two input fields, enhanced with
	 * date pickers.
	 *
	 * @see ApacheSolrForTypo3\Solr\Facet\SimpleFacetRenderer::render()
	 */
	public function renderFacetOptions() {

		$dateEnds = $this->getDateEnds();

		// the option's value will be appended by javascript after the slide event
		$incompleteFacetOption = GeneralUtility::makeInstance('ApacheSolrForTypo3\\Solr\\Facet\\FacetOption',
			$this->facetName,
			''
		);

		$facetLinkBuilder = GeneralUtility::makeInstance('ApacheSolrForTypo3\\Solr\\Facet\\LinkBuilder',
			$this->search->getQuery(),
			$this->facetName,
			$incompleteFacetOption
		);

		$content = '
			<input type="hidden" id="' . $this->facetName . '_url" value="' . $facetLinkBuilder->getReplaceFacetOptionUrl($this->facetName) . '" />
			<div class="input-daterange input-group" id="' . $this->facetName . '-datepicker">
				<input id="start_date_' . $this->facetName . '" type="text" class="input-sm form-control" name="start" value="' . $dateEnds['start'] . '" />
				<span class="input-group-addon">###LLL:rangebarrier###</span>
    			<input id="end_date_' . $this->facetName . '" type="text" class="input-sm form-control" name="end" value="' . $dateEnds['end'] . '" />
			</div>
		';

		return $content;
	}


	/**
	 * Gets the handle positions for the slider.
	 *
	 * @return array Array with keys start and end
	 */
	protected function getDateEnds() {
		// not used
		//$facetOptions    = $this->getFacetOptions();

		$start = '';
		$end = '';

		$filters = $this->search->getQuery()->getFilters();
		foreach ($filters as $filter) {
			if (preg_match("/\(" . $this->facetConfiguration['field'] . ":\[(.*)\]\)/", $filter, $matches) ){
				$range = explode('TO', $matches[1]);
				$range = array_map('trim', $range);

				$start = date('d.m.Y', strtotime($range[0]));
				$end = date('d.m.Y', strtotime($range[1]));
				break;
			}
		}

		return array('start' => $start, 'end' => $end);
	}

	/**
	 * Loads jQuery libraries for the date pickers.
	 *
	 */
	protected function loadJavaScriptFiles() {
		$javascriptManager = GeneralUtility::makeInstance('ApacheSolrForTypo3\\Solr\\JavascriptManager');

		$javascriptManager->loadFile('library');
		$javascriptManager->loadFile('bs.datepicker');

		$language = $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
		if ($language != 'en') {
				// load date picker translation
			$javascriptManager->loadFile('bs.datepicker.' . $language);
		}

		$javascriptManager->loadFile('faceting.bsDateRangeHelper');

		$datepickerInitialization = $this->getDatepickerJavaScript();
		$javascriptManager->addJavascript($this->facetName . '-datepickerInitialization', $datepickerInitialization);

		$javascriptManager->addJavascriptToPage();
	}

	protected function getDatepickerJavaScript() {
		$datepickerJavaScript = '
			jQuery(document).ready(function() {
				$(\'body\').bind(\'AjaxSearchLoaded\', function(event, params) {
					jQuery("#' . $this->facetName . '-datepicker").datepicker({
						weekStart: 1,
						format: "dd.mm.yyyy",
						language: "de",
						startDate: "-1y",
						endDate: "+1y",
						todayBtn: "linked",
						autoclose: true,
						todayHighlight: true
					}).on(\'hide\', function() {
						solrBsDateRangeRequest("'
						. $this->facetName
						. '", "'
						. \ApacheSolrForTypo3\Solr\Query\FilterEncoder\DateRange::DELIMITER
						. '")
					});
				});
			});';
		return $datepickerJavaScript;
	}


	/**
	 * Adds the stylesheets necessary for the slider
	 *
	 */
	protected function loadStylesheets() {
		if ($this->solrConfiguration['cssFiles.']['bs.']['datepicker'] && !$GLOBALS['TSFE']->additionalHeaderData['tx_solr-bs-datepicker-css']) {
			$cssFile = GeneralUtility::createVersionNumberedFilename($GLOBALS['TSFE']->tmpl->getFileName($this->solrConfiguration['cssFiles.']['bs.']['datepicker']));
			$GLOBALS['TSFE']->additionalHeaderData['tx_solr-bs-datepicker-css'] =
				'<link href="' . $cssFile . '" rel="stylesheet" type="text/css" media="all" />';
		}
	}

}
