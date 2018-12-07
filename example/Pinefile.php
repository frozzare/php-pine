<?php

// pine build [argv]
function build($data) {
    echo 'Building...';

    echo sprintf("\nHello, %s!\n", $data->flag('n'));
    echo sprintf("Hello, %s!", $data->flag('name'));
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
