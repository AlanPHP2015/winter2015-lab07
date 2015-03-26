<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Menu extends CI_Model {

    protected $xml = null;
    protected $patties = array();
    protected $cheeses = array();
    protected $toppings = array();
    protected $sauces = array();

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->xml = simplexml_load_file(DATAPATH . 'menu.xml');

        // build a full list of patties - approach 2
        foreach ($this->xml->patties->patty as $patty) {
            $record = new stdClass();
            $record->code = (string) $patty['code'];
            $record->name = (string) $patty;
            $record->price = (float) $patty['price'];
            $this->patties[$record->code] = $record;
        }
        
        // Processe cheeses
        foreach($this->xml->cheeses->cheese as $cheese) {
            $record = new stdClass();
            $record->code = (string) $cheese['code'];
            $record->price = (string) $cheese['price'];
            $record->name = $cheese;
            $this->cheeses[$record->code] = $record;
        }
        
        // Process toppings
        foreach($this->xml->toppings->topping as $topping) {
            $record = new stdClass();
            $record->code = (string) $topping['code'];
            $record->price = (string) $topping['price'];
            $record->name = $topping;
            $this->toppings[$record->code] = $record;
        }
        
        // Process sauces
        foreach($this->xml->sauces->sauce as $sauce) {
            $record = new stdClass();
            $record->code = (string) $sauce['code'];
            $record->name = $sauce;
            $this->sauces[$record->code] = $record;
        }
//        var_dump($this->patties);
//        var_dump($this->cheeses);
//        var_dump($this->toppings);
//        var_dump($this->sauces);
//        die();
    }

    // retrieve a patty record, perhaps for pricing
    public function getPatty($code) {
        if (isset($this->patties[$code])) {
            return $this->patties[$code];
        }
        return null;
    }

    public function getCheese($code) {
        if (isset($this->cheeses[$code])) {
            return $this->cheeses[$code];
        }
        return null;
    }
    
    public function getTopping($code) {
        if (isset($this->toppings[$code])) {
            return $this->toppings[$code];
        }
        return null;
    }
    
    public function getSauce($code) {
        if (isset($this->sauces[$code])) {
            return $this->sauces[$code];
        }
        return null;
    }
}
