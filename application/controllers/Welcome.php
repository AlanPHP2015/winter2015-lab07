<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
	// Build a list of orders
	
	// Present the list to choose from
        $orders = $this->orders->getAllOrderNames();
        asort($orders);
        $this->data['orders'] = $orders;
        $this->data['pagebody'] = 'homepage';
	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($orderNum)
    {
	// Build a receipt for the chosen order
	$order = $this->orders->getOrder($orderNum);
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
        
        $this->data['ordernum'] = $orderNum;
        $this->data['ordertype'] = $order['ordertype'];
        $this->data['ordertotal'] = $order['ordertotal'];
        $this->data['customer'] = $order['customer'];
        $this->data['burgers'] = $order['burgers'];
//        var_dump($order['burgers']);
	$this->render();
    }
}
