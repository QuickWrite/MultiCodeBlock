<?php
require_once __DIR__ . '/../vendor/autoload.php';

function getOptions() {
    $options = new \PHPHtmlParser\Options();
    $options->setPreserveLineBreaks(true);
    $options->isHtmlSpecialCharsDecode(true);

    return $options;
}