<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "solr_bs_date_range_facet".
 *
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Solr Bootstrap Date Range Facet',
    'description' => '',
    'category' => '',
    'author' => 'Marc Bastian Heinrichs',
    'author_email' => 'mbh@mbh-software.de',
    'shy' => '',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'author_company' => '',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'solr' => '7.0.0-8.0.0'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
