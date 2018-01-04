<?php

/**
 * Francis - a simple message responder
 *
 * Francis responds as follows:
 * Francis answers 'Sure.' if you ask him a question
 * He answers 'Chill!' if you yell at him using all capital letters
 * He says 'See if I care!' if you address him without actually saying anything
 * He answers 'Whatevs.' to anything else
 *
 * @author     Matthew Speicher <matthewbspeicher@gmail.com>
 */

class Francis {

    /**
     * @var string $questionResponse Response to a question asked.
     */
    private $questionResponse = 'Sure.';

    /**
     * @var string $yellResponse Response to being yelled at.
     */
    private $yellResponse = 'Chill!';

    /**
     * @var string $blankResponse Response to blank address.
     */
    private $blankResponse = 'See if I care!';

    /**
     * @var string $miscResponse Miscellaneous response.
     */
    private $miscResponse = 'Whatevs.';

    /**
     * @var string $message Stores text of what is said to Francis.
     */
    private $message;

    /**
     * Function called to return a response when Francis is addressed
     *
     * @param string $msg String containing what is said to Francis.
     * @return string Response returned when Francis is addressed.
     */
    public function yo($msg) {
        $this->message = $msg;

        if (!isset($this->message) || trim($this->message) === '') {
            $this->responseMade = true;
            return $this->blankResponse;
        } elseif (strtoupper($this->message) == $this->message && preg_match('/[A-Za-z]/i', $this->message)) {
            $this->responseMade = true;
            return $this->yellResponse;
        } elseif (substr($this->message, -1) === '?') {
            $this->responseMade = true;
            return $this->questionResponse;
        } else {
            return $this->miscResponse;
        }

    }

}