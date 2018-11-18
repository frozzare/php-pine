<?php

// pine build [argv]
function build($argv) {
    echo 'Building...';
    echo "\n";
    print_r($argv);
}

// pine build:site
function build_site() {
    echo 'Build site...';
}

before('build', 'build:site');
after('build_site', 'done');

function done() {
    echo 'All done';
}