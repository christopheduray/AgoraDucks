<?php
use Fwk\Router;
session_start();

require 'autoload.php';
require 'app/routes.php';

DotEnv::load();

Router::dispatch();