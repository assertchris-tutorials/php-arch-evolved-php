<?php

require __DIR__ . "/../vendor/autoload.php";

[$billable1, $mapped1] = require __DIR__ . "/non-functional.php";
[$billable2, $mapped2] = require __DIR__ . "/functional.php";
[$billable3, $mapped3] = \Pre\Plugin\Process(__DIR__ . "/preprocessed.pre");

assert(
    // match non-functional to functional
    serialize($billable1) === serialize($billable2)
    && serialize($mapped1) === serialize($mapped2)

    // match functional to preprocessed
    && serialize($billable2) === serialize($billable3)
    && serialize($mapped2) === serialize($mapped3)
);

print "outputs match!\n";
