<?php

namespace Flits\Contlo\API;

use Flits\Contlo\ContloProvider;

class Track extends ContloProvider {

    public $URL = "/track";
    public $METHOD = "POST";

    function __construct($config) {
        parent::__construct($config);
    }
}