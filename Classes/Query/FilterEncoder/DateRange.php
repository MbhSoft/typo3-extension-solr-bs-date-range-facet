<?php
/**
 *  Copyright notice
 *
 *  (c) 2010-2011 Markus Goldbach <markus.goldbach@dkd.de>
 *  (c) 2012 Ingo Renner <ingo@typo3.org>
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
namespace MbhSoftware\SolrBsDateRangeFacet\Query\FilterEncoder;

/**
 * Parser to build solr range queries from tx_solr[filter]
 *
 * @author	Markus Goldbach <markus.goldbach@dkd.de>
 */
class DateRange implements \ApacheSolrForTypo3\Solr\Query\FilterEncoder\FilterEncoder, \ApacheSolrForTypo3\Solr\Facet\FacetBuilder
{

    /**
     * Delimiter for date parts in the URL.
     *
     * @var	string
     */
    const DELIMITER = '-';


    /**
     * Parses the given date range from a GET parameter and returns a Solr
     * date range filter.
     *
     * @param	string	$rangeFilter The range filter query string from the query URL
     * @param array $configuration Facet configuration
     * @return	string	Lucene query language filter to be used for querying Solr
     */
    public function decodeFilter($dateRange, array $configuration = [])
    {
        list($dateRangeStart, $dateRangeEnd) = explode(self::DELIMITER, $dateRange);

        $dateRangeStart .= '000000';
        $dateRangeEnd  .= '235959';

        $dateRangeFilter  = '[' . \ApacheSolrForTypo3\Solr\Util::timestampToIso(strtotime($dateRangeStart));
        $dateRangeFilter .= ' TO ';
        $dateRangeFilter .= \ApacheSolrForTypo3\Solr\Util::timestampToIso(strtotime($dateRangeEnd)) . ']';

        return $dateRangeFilter;
    }

    /**
     * Takes a filter value and encodes it to a human readable format to be
     * used in an URL GET parameter.
     *
     * @param string $filterValue the filter value
     * @param array $configuration Facet configuration
     * @return string Value to be used in a URL GET parameter
     */
    public function encodeFilter($filterValue, array $configuration = [])
    {
        return $filterValue;
    }

    /**
     * Builds the facet parameters depending on a date range facet's configuration.
     *
     * @param string $facetName Facet name
     * @param array $facetConfiguration The facet's configuration
     */
    public function buildFacetParameters($facetName, array $facetConfiguration)
    {
        $facetParameters = [];

        $tag = '';
        if ($facetConfiguration['keepAllOptionsOnSelection'] == 1) {
            $tag = '{!ex=' . $facetConfiguration['field'] . '}';
        }
        $facetParameters['facet.range'][] = $tag . $facetConfiguration['field'];

        $start = 'NOW/DAY-1YEAR';
        if ($facetConfiguration['dateRange.']['start']) {
            $start = $facetConfiguration['dateRange.']['start'];
        }
        $facetParameters['f.' . $facetConfiguration['field'] . '.facet.range.start'] = $start;

        $end = 'NOW/DAY+1YEAR';
        if ($facetConfiguration['dateRange.']['end']) {
            $end = $facetConfiguration['dateRange.']['end'];
        }
        $facetParameters['f.' . $facetConfiguration['field'] . '.facet.range.end'] = $end;

        $gap = '+1DAY';
        if ($facetConfiguration['dateRange.']['gap']) {
            $gap = $facetConfiguration['dateRange.']['gap'];
        }
        $facetParameters['f.' . $facetConfiguration['field'] . '.facet.range.gap'] = $gap;

        return $facetParameters;
    }
}
