<?php

/*
 base class for navigator
 */

namespace S_Navigator;


abstract class Listener
{
    
    protected $view; //view (View class)
    
    protected $parameters; //query parameters
    
    protected $counter_param; //quantity of query parameters
    
    protected $links_count;  //number of links to the right and left
    
    protected $items_per_page; //number of items per page
    
    protected $pages_cached; //number of cached pages
    
    protected $way; // way to draw navigator

   
    public function __construct(
        View $view,
        $items_per_page = 10,
        $links_count = 3,
        $get_params = null,
        $counter_param = 'page',
        $pages_cached = 10,
        $way  = 1)
    {
        $this->view           = $view;
        $this->parameters     = $get_params;
        $this->counter_param  = $counter_param;
        $this->items_per_page = $items_per_page;
        $this->links_count    = $links_count;
        $this->pages_cached   = $pages_cached;
        $this->way            = $way;
    }

    
    abstract public function getItemsCount();
    
    abstract public function getItems();

   
    public function getVisibleLinkCount()
    {
        return $this->links_count;
    }
  
    public function getParameters()
    {
        return $this->parameters;
    }
   
    public function getCounterParam()
    {
        return $this->counter_param;
    }
  
    public function getItemsPerPage()
    {
        return $this->items_per_page;
    }
  
    public function getCurrentPagePath()
    {
        return $_SERVER['PHP_SELF'];
    }
    
    public function getPageCached()
    {
        return $this->pages_cached;
    }
    
    public function getWay()
    {
        return $this->way;
    }
     
  
    public function getCurrentPage()
    {
        if(isset($_GET[$this->getCounterParam()])) {
            return intval($_GET[$this->getCounterParam()]);
        } else {
            return 1;
        }
    }
   
    public function getPagesCount()
    {
      
      $total = $this->getItemsCount();
     
      $result = (int)($total / $this->getItemsPerPage());
      if((float)($total / $this->getItemsPerPage()) - $result != 0) $result++;

      return $result;
    }
    
    
    // choosing way to draw navigator
    public function navigator()
    {
       if ($this->way == 1){
           return $this->view->navigator($this);
       }
       else{
           return $this->view->navigator2Way($this);
       }
    }
  
    public function SetNavigator()
    {
        return $this->navigator();
    }
}
