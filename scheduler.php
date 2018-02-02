<?php

require_once __DIR__.'/vendor/autoload.php';

use GO\Scheduler;

// Create a new scheduler
$scheduler = new Scheduler();

$scheduler->php('worker.php')->everyMinute();

// Let the scheduler execute jobs which are due.
$scheduler->run();