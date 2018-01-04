<?php

/**
 * WidgetTest.php - Unit testing of the Widget class
 *
 * @author     Matthew Speicher <matthewbspeicher@gmail.com>
 */

require_once './Widget.php';

class WidgetTest extends PHPUnit\Framework\TestCase {

    public function setUp () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest = new Widget($data);
        $this->order = json_decode($data);
    }

    public function testValidateEmail1 () {
        $this->assertEquals(true, $this->widgetTest->validate_email('matthewbspeicher@gmail.com'));
    }

    public function testValidateEmail2 () {
        $this->assertEquals(false, $this->widgetTest->validate_email('matthewbspeicher'));
    }

    public function testValidateDate1 () {
        $this->assertEquals(true, $this->widgetTest->validate_date('2017-12-25'));
    }

    public function testValidateDate2 () {
        $this->assertEquals(false, $this->widgetTest->validate_date('2015/13/32'));
    }

    public function testValidateOrder1 () {
        $this->assertEquals(true, $this->widgetTest->validate_order());
    }

    public function testValidateOrder2 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"E\",\"type\":\"Widge\",\"color\":\"Re\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest2 = new Widget($data);
        $this->assertEquals(false, $this->widgetTest2->validate_order());
    }

    public function testValidateOrder3 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"Widge\",\"color\":\"Re\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest2 = new Widget($data);
        $this->assertEquals(false, $this->widgetTest2->validate_order());
    }

    public function testValidateOrder4 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Re\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest2 = new Widget($data);
        $this->assertEquals(false, $this->widgetTest2->validate_order());
    }

    public function testValidateOrder5 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"456\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest2 = new Widget($data);
        $this->assertEquals(false, $this->widgetTest2->validate_order());
    }

    public function testValidateOrder6 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"123\",\"color\":\"Red\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest2 = new Widget($data);
        $this->assertEquals(false, $this->widgetTest2->validate_order());
    }

    public function testValidateWidget1 () {
        $this->assertEquals(true, $this->widgetTest->validate_widget($this->order[2]));
    }

    public function testValidateWidget2 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"1\",\"type\":\"123\",\"color\":\"Red\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest3 = new Widget($data);
        $this->order2 = json_decode($data);
        $this->assertEquals(false, $this->widgetTest3->validate_widget($this->order2[2]));
    }

    public function testValidateWidget3 () {
        $data = "[{\"needed_by\":\"2017-12-25\"},{\"email\":\"matthewbspeicher@gmail.com\"},{\"qty\":\"0\",\"type\":\"Widget\",\"color\":\"Red\"},{\"qty\":\"1\",\"type\":\"Widget\",\"color\":\"Red\"}]";
        $this->widgetTest3 = new Widget($data);
        $this->order2 = json_decode($data);
        $this->assertEquals(false, $this->widgetTest3->validate_widget($this->order2[2]));
    }

}
