<?php


namespace S_Navigator;


class ListRange extends View
{
   
    public function range($first, $last)
    {
        return "<<{$first}-{$last}>";
    }
    
    public function navigator(Listener $listener) {

       
        $this->listener = $listener;

       
        $return_page = "";

        
        $current_page = $this->listener->getCurrentPage();
        
        $total_pages = $this->listener->getPagesCount();

      
        if($current_page - $this->listener->getVisibleLinkCount() > 1) {
            $range = $this->range(1, $this->listener->getItemsPerPage());
            $return_page .= $this->link($range, 1)." ... ";
           
            $init = $current_page - $this->listener->getVisibleLinkCount();
            for($i = $init; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        } else {
            
            for($i = 1; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        }
       
        if($current_page + $this->listener->getVisibleLinkCount() < $total_pages) {
           
            $cond = $current_page + $this->listener->getVisibleLinkCount();
            for($i = $current_page; $i <= $cond; $i++) {
                if($current_page == $i) {
                    $return_page .= " ".$this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage())." ";
                } else {
                    $range = $this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage());
                    $return_page .= " ".$this->link($range, $i)." ";
                }
            }
            $range = $this->range(
                (($total_pages - 1) * $this->listener->getItemsPerPage() + 1),
                $this->listener->getItemsCount());
            $return_page .= " ... ".$this->link($range, $total_pages)." ";
        } else {
            
            for($i = $current_page; $i <= $total_pages; $i++) {
                if($total_pages == $i) {
                    if($current_page == $i) {
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount())." ";
                    } else {
                        $range = $this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount());
                        $return_page .= " ".$this->link($range, $i)." ";
                    }
                } else {
                    if($current_page == $i) {
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $i * $this->listener->getItemsPerPage())." ";
                    } else {
                        $range = $this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            ($i * $this->listener->getItemsPerPage()));
                        $return_page .= " ".$this->link($range, $i)." ";
                    }
                }
            }
        }
        return $return_page;
    }
}
