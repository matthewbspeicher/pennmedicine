<?php

require_once './Francis.php';

class FrancisTest extends PHPUnit\Framework\TestCase {

    public function setUp () {
        $this->teen = new Francis();
    }

    public function testStating () {
        $this->assertEquals('Whatevs.', $this->teen->yo('Oh blah di, oh blah da.'));
    }

    public function testYelling () {
        $this->assertEquals('Chill!', $this->teen->yo('GOOOAAAALLL!'));
    }
    
    public function testInquiring () {
        $this->assertEquals('Sure.', $this->teen->yo('Did you finish your homework?'));
    }

    public function testInquiringWithNumber () {
        $this->assertEquals('Sure.', $this->teen->yo('Was the score 1 to nil?'));
    }

    public function testStatingExcitedly () {
        $this->assertEquals('Whatevs.', $this->teen->yo("I love that book!"));
    }

    public function testStatingWithAcronyms () {
        $this->assertEquals('Whatevs.', $this->teen->yo("NPR just had a good piece on the BBC."));    
    }

    public function testYellingQuestions () {
        $this->assertEquals('Chill!', $this->teen->yo('WHAT IS YOUR PROBLEM?'));    
    }

    public function testYellingNumbers () {
        $this->assertEquals('Chill!', $this->teen->yo('3, 2, 1, CONTACT!'));    
    }

    public function testOnlyNumbers () {
        $this->assertEquals('Whatevs.', $this->teen->yo('3, 2, 1'));    
    }

    public function testQuestionWithJustNumbers () {
        $this->assertEquals('Sure.', $this->teen->yo('7?'));
    }

    public function testYellingWithSpecialChars () {
        $this->assertEquals('Chill!', $this->teen->yo('ZOMG WE 1 THE WORLD @*&#%$^ CUP!1!11!'));        
    }

    public function testYellingWithoutPunctuation () {
        $this->assertEquals('Chill!', $this->teen->yo('PAY ATTENTION'));            
    }

    public function testStatingWithOddPunctuation () {
        $this->assertEquals('Whatevs.', $this->teen->yo("Then I was like 'You good?'."));        
    }

    public function testInquiringEventually () {
        $this->assertEquals('Sure.', $this->teen->yo("Woah! Hold up. Who said that?"));        
    }

    public function testIgnoring () {
        $this->assertEquals('See if I care!', $this->teen->yo(''));            
    }
    
    public function testMoreIgnoring () {
        $this->assertEquals('See if I care!', $this->teen->yo(null));            
    }

    public function testProlongedIgnoring () {
        $this->assertEquals('See if I care!', $this->teen->yo('     '));            
    }

    public function testMultipleLines () {
        $this->assertEquals('Whatevs.', $this->teen->yo('
          Is this done yet?
          no
        '));
    }

}
