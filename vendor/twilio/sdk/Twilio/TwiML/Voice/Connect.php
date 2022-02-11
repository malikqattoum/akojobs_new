<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Connect extends TwiML {
    /**
     * Connect constructor.
     * 
     * @param array $attributes Optional attributes
     */
    public function __construct($attributes = array()) {
        parent::__construct('Connect', null, $attributes);
    }

    /**
     * Add Room child.
     * 
     * @param string $name Room name
     * @param array $attributes Optional attributes
     * @return TwiML Child element.
     */
    public function room($name, $attributes = array()) {
        return $this->nest(new Room($name, $attributes));
    }

    /**
     * Add Autopilot child.
     * 
     * @param string $name Autopilot assistant sid or unique name
     * @return TwiML Child element.
     */
    public function autopilot($name) {
        return $this->nest(new Autopilot($name));
    }

    /**
     * Add Action attribute.
     * 
     * @param url $action Action URL
     * @return TwiML $this.
     */
    public function setAction($action) {
        return $this->setAttribute('action', $action);
    }

    /**
     * Add Method attribute.
     * 
     * @param httpMethod $method Action URL method
     * @return TwiML $this.
     */
    public function setMethod($method) {
        return $this->setAttribute('method', $method);
    }
}