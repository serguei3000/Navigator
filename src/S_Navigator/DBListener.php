<?php

/*
 main class for navigator

 */

namespace S_Navigator;

unset($cache);
session_start();


class DBListener extends Listener
{
    
    protected $pdo;
   
    protected $tablename;
   
    protected $where;
   
    protected $params;
    
    protected $order;
    
    public function __construct(
        View $view,
        $pdo,
        $tablename,
        $way = 1,
        $where = "",
        $params = [],
        $order = "",
        $items_per_page = 10,
        $links_count = 3,
        $get_params = null,
        $counter_param = 'page',
        $pages_cached = 10
        )
    {
      $this->pdo = $pdo;
      $this->tablename = $tablename;
      $this->where = $where;
      $this->params = $params;
      $this->order = $order;
     
      parent::__construct(
          $view,
          $items_per_page,
          $links_count,
          $get_params,
          $counter_param,
          $pages_cached,
          $way);
    }
    
    //DB query for item counts
    public function getItemsCount()
    {
        
        $query = "SELECT COUNT(*) AS total
                  FROM {$this->tablename}
                  {$this->where}";
        $tot = $this->pdo->prepare($query);
        $tot->execute($this->params);
        return $tot->fetch()['total'];
    }
    
    public function getItems()
    {
        
      $current_page = $this->getCurrentPage();
      
      $total_pages = $this->getPagesCount();
      
      if($current_page <= 0 || $current_page > $total_pages) {
          return 0;
      }
      
      
       return $this->getCachedData();
    }
    
    // query for DB with page caching via session
    private function getCachedData()
    {
        
        global $cache;
        $cache = $_SESSION['cache'];
                
        if (isset($cache[$this->getCurrentPage()])) {
          echo "<br> taken data from cache <br><br>"; 
          return $cache[$this->getCurrentPage()];
           }
        else {
            
        $cache = NULL;
        $current_page = $this->getCurrentPage();
        $total_pages = $this->getPagesCount();
        $first = ($current_page - 1) * $this->getItemsPerPage();
        $query = "";
        if($current_page + $this->getPageCached() < $total_pages) {
        $last = $this->getItemsPerPage() * $this->getPageCached();
        $query = "SELECT * FROM {$this->tablename}
        {$this->where}
        {$this->order}
        LIMIT $first, $last";
        }
        else {
        $last = $this->getItemsCount() - $first;
        $query = "SELECT * FROM {$this->tablename}
        {$this->where}
        {$this->order}
        LIMIT $first, $last";
        }
        
        $tbl = $this->pdo->prepare($query);
        $tbl->execute($this->params);
        $results = $tbl->fetchAll();
       
        
        $str = $current_page;     
       
        $massi = array_chunk($results, $this->getItemsPerPage());
        //print_r($massi);
            foreach($massi as $srng){
                foreach ($srng as $itm){
                    $cache[$str][] = $itm;}
                
                $str++;
                 
            }
        
            //print_r($cache);
          
            
         $_SESSION['cache'] = $cache;
         echo "<br>query have been renewed<br><br>";
         return $cache[$this->getCurrentPage()];
        } 
    }
    
    
    
    
}
