<?php

/**
 * submit.php - accepts AJAX POST in JSON format of widget order,
 * then validates and submits widget order using the Widget class.
 * Order submission currently consists of two steps:
 *  1. Store the details of the order in a database.
 *  2. Display a confirmation message that shows the user the details
 *  of the order they placed along with a unique order ID.
 *
 * @author     Matthew Speicher <matthewbspeicher@gmail.com>
 */

include 'Widget.php';

$data = file_get_contents('php://input');
$order = new Widget($data);
$order->process();
