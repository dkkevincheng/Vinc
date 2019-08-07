<?php

use \NoahBuscher\Macaw\Macaw;

Macaw::get('/', function () {
    echo 'Hello world!';
});
Macaw::any('/', function () {
    echo 'I can be both a GET and a POST request!';
});
Macaw::error(function () {
    echo '404 :: Not Found';
});
Macaw::dispatch();
