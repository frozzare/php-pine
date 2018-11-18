<?php

// pine build
function build() {
    echo 'Building...';
}

// pine build:site
function build_site() {
    echo 'Building site...';
}

before('build:site', 'build');

function build_done() {
    echo 'Building done...';
}

after('build_site', 'build:done');