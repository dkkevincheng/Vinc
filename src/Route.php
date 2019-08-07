<?php

use \NoahBuscher\Macaw\Macaw;

Macaw::get('/', 'HomeController@index');

Macaw::error(function () {
    echo '404 :: Not Found';
});
Macaw::dispatch();
