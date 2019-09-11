<?php

namespace Modules\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Controller
{

  protected $logger;
  protected $tapdw;
  protected $tapflow;
  protected $query_helper;

  function __construct($container)
  {
    $this->logger = $container->logger;
    $this->tapdw = $container->tapdw;
    $this->tapflow = $container->tapflow;
    $this->query_helper = $container->query_helper;
  }

  function __get($property) {
    if($this->container->{$property}) {
      return $this->container->{$property};
    }
  }
}