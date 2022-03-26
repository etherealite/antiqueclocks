<?php

namespace Curios\App\Model;

class Sale {

    /** one of Auction|Ebay|Other */
    private $type;
    /** date the sale was made */
    private $date;
    /** if the sale was an auction an optional estimate */
    private $estimate;
    /** realized price of the sale or auction */
    private $price;
}