<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Orders extends CI_Model {

    protected $xml = null;
//    protected $patty_names = array();
    protected $parsedOrders = array();
    protected $orderNames = array();

    // Constructor
    public function __construct() {
        parent::__construct();
        $listOfOrderFiles = $this->getAllOrders();
//        die(var_dump($listOfOrderFiles));
        foreach ($listOfOrderFiles as $order) {
            $this->xml = simplexml_load_file(DATAPATH . $order);
            $this->orderNames[] = array('ordernum' => pathinfo($order)['filename'],
                                    'customer' => (string) $this->xml->customer);
            
            $orderTotal = 0.0;
            $rec = array();
            $burgerCount = 0;
            
            $rec['ordertype'] = (string) $this->xml['type'];
            $rec['customer'] = (string) $this->xml->customer;
            foreach($this->xml->burger as $burger) {
                
                $newburger = array();
                $burgertotal = 0.0;
                $newburger['burgernum'] = ++$burgerCount;
                // Parse patty info
                $pattyObj = $this->menu->getPatty((string)$burger->patty['type']);
                $newburger['patty'] = (string)$pattyObj->name;
                $burgertotal += (float)$pattyObj->price;
                $orderTotal += (float)$pattyObj->price;
                // Parse cheese info
                $cheeses = '';
                if(isset($burger->cheeses)) {
                    // Top
                    if(isset($burger->cheeses['top'])) {
                        $cheeseObj = $this->menu->getCheese((string)$burger->cheeses['top']);
                        $cheeses =  (string)$cheeseObj->name . ' (top)';
                        $burgertotal += (float)$cheeseObj->price;
                        $orderTotal += (float)$cheeseObj->price;
                    }
                    // Bottom
                    if(isset($burger->cheeses['bottom'])) {
                        $cheeseObj = $this->menu->getCheese((string)$burger->cheeses['bottom']);
    //                    $recBurger->cheeses[] = (string)$cheeseObj->name;
                        
                        $cheeses = $cheeses . ' ' . (string)$cheeseObj->name . ' (bottom)';
                        $burgertotal += (float)$cheeseObj->price;
                        $orderTotal += (float)$cheeseObj->price;
                    }
                }
                $newburger['cheeses'] = trim($cheeses);
                
                // Parse toppings info
                $toppings = '';
                
                if(!isset($burger->topping)) {
                    $toppings = 'none';
                    $newburger['toppings'] = $toppings;
                } else {
                    foreach($burger->topping as $topping) {
                        $toppingObj = $this->menu->getTopping((string) $topping['type']);
                        $toppings = $toppings . $toppingObj->name . ', ';
                        $burgertotal += (float) $toppingObj->price;
                        $orderTotal += (float) $toppingObj->price;
                    }
                    $newburger['toppings'] = substr($toppings, 0, strlen($toppings) - 2);
                }
                
                // Parse sauces
                $sauces = '';
                if(!isset($burger->sauce)) {
                    $sauces = 'none';
                    $newburger['sauces'] = $sauces;
                } else {
                    foreach($burger->sauce as $sauce) {
                        $sauceObj = $this->menu->getSauce((string) $sauce['type']); 
                        $sauces = $sauces . (string)$sauceObj->name . ', ';
                    }    
                    $newburger['sauces'] = substr($sauces, 0, strlen($sauces) - 2);
                }
                $newburger['instructions'] = (string)$this->xml->burger->instructions;
                $newburger['total'] = $burgertotal;
                $rec['burgers'][] = $newburger;
            }
            $rec['ordertotal'] = $orderTotal;
            $this->parsedOrders[pathinfo($order)['filename']] = $rec;
        }
    }
    
    function getAllOrderNames() {
        return $this->orderNames;
    }
    
    function getOrder($orderNum) {
        if(isset($this->parsedOrders[$orderNum])) {
            return $this->parsedOrders[$orderNum];
        } else {
            return null;
        }
    }

    private function getAllOrders() {
        $path = 'data';
        $this->load->helper('directory');
        $map = directory_map($path, 1, TRUE);
        return $this->filterOrders($map);
    }
    
    private function filterOrders($dataInArray) {
        $filteredFiles = [];
        foreach($dataInArray as $file) {
            if(preg_match('#^order.*.xml$#', $file) == 1) {
                $filteredFiles[] = $file;
            }
        }
        return $filteredFiles;
    }
    
    private function removeXMLExtension($filename) {
        $pathparts = pathinfo($file);
        return $pathparts['filename'];
    }
}
