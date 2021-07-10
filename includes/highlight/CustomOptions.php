<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Returns specific options for the HTML-Parser. 
 * 
 * @return \PHPHtmlParser\Options The options as a PHPHtmlParser class.
 */
function getOptions() {
    $options = new \PHPHtmlParser\Options();
    $options->setPreserveLineBreaks(true);
    $options->isHtmlSpecialCharsDecode(true);

    return $options;
}