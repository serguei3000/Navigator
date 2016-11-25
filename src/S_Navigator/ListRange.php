<?php
/*
  navigator() и navigator2Way - 2 ways to implement navigator
  $way = 1 for navigator() and any other figure for navigator2Way 

*/

namespace S_Navigator;


class ListRange extends View
{
    
    public function range($first, $last)
    {
        return "<<{$first}-{$last}>>";
    }
    
    
    
    public function navigator(Listener $listener) {

        $this->listener = $listener;

        $return_page = "";

        $current_page = $this->listener->getCurrentPage();
        $total_pages = $this->listener->getPagesCount();
        
        
        //-------- ссылки слева от текущей ---------------------------------------------------
        // ------- если до текущей умещаются все getVisibleLinkCount() ссылок-----------------
        if($current_page - $this->listener->getVisibleLinkCount() > 1) {
            //-------------начальная ссылка, с №1, выводится всегда---------------------------
            $range = $this->range(1, $this->listener->getItemsPerPage());
            $return_page .= $this->link($range, 1)." ... ";
            //-------------начальная страничка------------------------------------------------
            
            //----странички до текущей? начиная c current - getVisibleLinkCount() от нее -----
            $init = $current_page - $this->listener->getVisibleLinkCount();
            for($i = $init; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        } else { //---если от начала до текущей меньше чем getVisibleLinkCount() ссылок-------- 
                 //---просто выводим их все до нее, начиная с №1 ------------------------------
            for($i = 1; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        }
        
        // ----------- теперь ссылки справа от текущей -------------------------------------------
        //------------- если справа от текущей уместятся все getVisibleLinkCount() --------------- 
        if($current_page + $this->listener->getVisibleLinkCount() < $total_pages) {
           
            $cond = $current_page + $this->listener->getVisibleLinkCount();
            for($i = $current_page; $i <= $cond; $i++) {
                if($current_page == $i) { //---если текущая, то отображение ссылки без гиперссылки-
                    $return_page .= " ".$this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage())." ";
                } else {//---------на все прочие - ссылки -----------------------------------------
                    $range = $this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage());
                    $return_page .= " ".$this->link($range, $i)." ";
                }
            }
            //----------крайняя справа от текущей---------------------------------------------------
            $range = $this->range(
                (($total_pages - 1) * $this->listener->getItemsPerPage() + 1),
                $this->listener->getItemsCount());
            $return_page .= " ... ".$this->link($range, $total_pages)." ";
            //----------крайняя справа от текущей---------------------------------------------------
            
        //------------- если справа от текущей не уместятся все getVisibleLinkCount() --------------  
        } else {
                 //--------идем от текущей до последней---------------------------------------------
            for($i = $current_page; $i <= $total_pages; $i++) {
                
                if($total_pages == $i) {//--- если дошли до конца данных на крайней странице может быть не больше getItemsCount()
                    if($current_page == $i) {//---если текущая, то отображение ссылки без гиперссылки-
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount())." ";    //данных может быть не больше getItemsCount()
                    } else {//---------на все прочие - ссылки -----------------------------------------
                        $range = $this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount());
                        $return_page .= " ".$this->link($range, $i)." "; //данных может быть не больше getItemsCount()
                    }
                } else {//--- если еще не дошли до конца все как обычно-------------------------------------
                    if($current_page == $i) {//---если текущая, то отображение ссылки без гиперссылки-
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $i * $this->listener->getItemsPerPage())." "; 
                    } else {//---------на все прочие - ссылки -----------------------------------------
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
    
    
    // другой способ отображения страниц, для упрощения их все время 10 в области видимости -------------
    public function navigator2Way(Listener $listener) {
        
        $this->listener = $listener;
        $idx = 1;
        $links = 10; //10 Страниц
        $current_page = $this->listener->getCurrentPage();
        $total_pages = $this->listener->getPagesCount();
        
        
        $idx = $current_page;
        
        if (abs($idx/$links) == floor(abs($idx/$links))) $idx--;
         
        $Rez = array();
        $Rez[] = $idx;
        for($i=$idx+1;$i!=0;$i++)
        {
            $Rez[] = $i;
            if (abs($i/$links) == floor(abs($i/$links)))
            {
            break;
            }
        }
        for($i=$idx-1;$i!=0;$i--)
        {
            $Rez[] = $i;
            if (abs($i/$links) == floor(abs($i/$links)) ||$i == 1)
            {
             break;
            }
        }
        
        sort($Rez);
        $prev = $Rez[0] -1;
        if ($prev < 1) $prev = 1;
        $next = $prev + ($links +2);
        
        if ($next > $total_pages){
            $next = $total_pages;
            }
            
        
        $return_page = "<td><a href=".$this->listener->getCurrentPagePath()."?".$this->listener->getCounterParam()."=$prev>Prev<< </a></td>";
        
        foreach($Rez as $k=>$v)
        {
            $return_page .= "<td><a href=".$this->listener->getCurrentPagePath()."?".$this->listener->getCounterParam()."=$v>$v>> </a></td>";
        }
        
        $return_page .= "<td><a href=".$this->listener->getCurrentPagePath()."?".$this->listener->getCounterParam()."=$next>Next>> </a></td>";
         
        
        return $return_page;
        
       
    }
    
    
    
}
