<?php

/**
 * Widget.php - a simple class to handle order processing of widgets
 *
 * @author     Matthew Speicher <matthewbspeicher@gmail.com>
 */

include 'MysqliDb.php';

class Widget {

    /**
     * @var object $order Stores the order object created from the JSON POST data
     */
    private $order;

    /**
     * @var string $order_id Stores the order_id of the order, which is created when
     * order is validated and stored in the db
     */
    public $order_id;

    /**
     * @var string $error_msg Stores the error message of the order, should there be
     * one found during order processing
     */
    public $error_msg;

    /**
     * @var \DateTime $needed_by Stores the DateTime object of the Delivery Date specified
     * by the user
     */
    public $needed_by;

    /**
     * @var string $email Stores the email address of the user who placed the order
     */
    public $email;

    /**
     * Widget constructor.
     * @param object $post_data JSON POST data
     */
    function __construct($post_data) {
        $this->order = json_decode($post_data);
    }

    /**
     * Process the widget order.
     */
    function process() {
        if ($this->validate_order()) {
            $this->store_order();
            if (isset($this->email) && $this->email != '') {
                $to = $this->email;
                $subject = "Your Widget Order";
                $txt = "Your widget order was successfully submitted! Order ID:" . $this->order_id;
                $headers = "From: matthewbspeicher@gmail.com";
                if (mail($to, $subject, $txt, $headers)) {
                    $this->success();
                } else {
                    $this->error_msg = 'There was an issue sending the Email!';
                    $this->error();
                }
            }
            $this->success();
        }
    }

    /**
     * Store validated widget order in the db.
     */
    function store_order() {
        $db = new MysqliDb('localhost', 'testdb', 'testdbpass', 'testdb');
        $result = $db->insert("INSERT INTO widget_orders (needed_by) VALUES ('".date_format($this->needed_by,"Y-m-d")."')");
        if ($result) {
            $this->order_id = str_pad($result, 12, '0', STR_PAD_LEFT);
            for ($x = 2; $x < count($this->order); $x++) {
                $result = $db->insert("INSERT INTO order_items (order_id, qty, type, color) VALUES ('".$this->order_id."','".$this->order[$x]->qty."','".$this->order[$x]->type."','".$this->order[$x]->color."')");
                if (!$result) {
                    $this->error_msg = 'There was an issue saving db records!';
                    $this->error();
                }
            }
        } else {
            $this->error_msg = 'There was an issue saving db records!';
            $this->error();
        }
    }

    /**
     * Validate the widget order.
     */
    function validate_order() {
        if (count($this->order) == 0) {
            $this->error_msg = 'The order posted contains no data!';
            $this->error();
            return false;
        } elseif (count($this->order) == 1) {
            $this->error_msg = 'The order posted only contains a Delivery Date!';
            $this->error();
            return false;
        } elseif (count($this->order) == 2) {
            $this->error_msg = 'The order posted only contains a Delivery Date and Email!';
            $this->error();
            return false;
        }
        if ($this->validate_date($this->order[0]->needed_by)) {
            $this->needed_by = date_create($this->order[0]->needed_by);
        } else {
            $this->error_msg = 'There was an issue validating the Delivery Date!';
            $this->error();
            return false;
        }
        if ($this->order[1]->email && $this->order[1]->email != '') {
            if ($this->validate_email($this->order[1]->email)) {
                $this->email = $this->order[1]->email;
            } else {
                $this->error_msg = 'There was an issue validating the Email!';
                $this->error();
                return false;
            }
        }
        for ($x = 2; $x < count($this->order); $x++) {
            if (!$this->validate_widget($this->order[$x])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validates the email portion of the widget order.
     * @param string $email
     * @return bool
     */
    function validate_email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Validates the widget portions of the order object.
     * @param object $widget
     * @return bool
     */
    function validate_widget($widget) {
        if (!is_numeric($widget->qty)) {
            $this->error_msg = 'The quantity on one of the widgets ordered is not numeric!';
            $this->error();
            return false;
        }
        if ($widget->qty < 1 || $widget->qty > 99) {
            $this->error_msg = 'The quantity on one of the widgets ordered is invalid!';
            $this->error();
            return false;
        }
        if (!is_string($widget->type)) {
            $this->error_msg = 'The type on one of the widgets ordered is not a valid string!';
            $this->error();
            return false;
        }
        if ($widget->type != 'Widget' && $widget->type != 'Widget Pro' && $widget->type != 'Widget Xtreme') {
            $this->error_msg = 'The type on one of the widgets ordered is invalid!';
            $this->error();
            return false;
        }
        if (!is_string($widget->color)) {
            $this->error_msg = 'The color on one of the widgets ordered is not a valid string!';
            $this->error();
            return false;
        }
        if ($widget->color != 'Red' && $widget->color != 'Blue' && $widget->color != 'Yellow') {
            $this->error_msg = 'The color on one of the widgets ordered is invalid!';
            $this->error();
            return false;
        }
        return true;
    }

    /**
     * Validates the needed_by date portion of the widget order.
     * @param \DateTime $date
     * @return bool
     */
    function validate_date($date) {
        $test_arr  = explode('-', $date);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
                $test_date = date('Y-m-d', strtotime("+7 days"));
                if ($date >= $test_date) {
                    return true;
                }else{
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Echoes JSON formatted SUCCESS message received by AJAX
     */
    function success() {
        $response = array();
        $response['status'] = 'success';
        $response['message'] = $this->order_id;
        echo json_encode($response);
        exit;
    }

    /**
     * Echoes JSON formatted ERROR message received by AJAX
     */
    function error() {
        $response = array();
        $response['status'] = 'error';
        $response['message'] = $this->error_msg;
        echo json_encode($response);
    }

}