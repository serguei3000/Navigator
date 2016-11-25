<?php
/*
  navigator() � navigator2Way - 2 ways to implement navigator
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
        
        
        //-------- ������ ����� �� ������� ---------------------------------------------------
        // ------- ���� �� ������� ��������� ��� getVisibleLinkCount() ������-----------------
        if($current_page - $this->listener->getVisibleLinkCount() > 1) {
            //-------------��������� ������, � �1, ��������� ������---------------------------
            $range = $this->range(1, $this->listener->getItemsPerPage());
            $return_page .= $this->link($range, 1)." ... ";
            //-------------��������� ���������------------------------------------------------
            
            //----��������� �� �������? ������� c current - getVisibleLinkCount() �� ��� -----
            $init = $current_page - $this->listener->getVisibleLinkCount();
            for($i = $init; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        } else { //---���� �� ������ �� ������� ������ ��� getVisibleLinkCount() ������-------- 
                 //---������ ������� �� ��� �� ���, ������� � �1 ------------------------------
            for($i = 1; $i < $current_page; $i++) {
                $range = $this->range(
                    (($i - 1) * $this->listener->getItemsPerPage() + 1),
                    $i * $this->listener->getItemsPerPage());
                $return_page .= " ".$this->link($range, $i)." ";
            }
        }
        
        // ----------- ������ ������ ������ �� ������� -------------------------------------------
        //------------- ���� ������ �� ������� ��������� ��� getVisibleLinkCount() --------------- 
        if($current_page + $this->listener->getVisibleLinkCount() < $total_pages) {
           
            $cond = $current_page + $this->listener->getVisibleLinkCount();
            for($i = $current_page; $i <= $cond; $i++) {
                if($current_page == $i) { //---���� �������, �� ����������� ������ ��� �����������-
                    $return_page .= " ".$this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage())." ";
                } else {//---------�� ��� ������ - ������ -----------------------------------------
                    $range = $this->range(
                        (($i - 1) * $this->listener->getItemsPerPage() + 1),
                        $i * $this->listener->getItemsPerPage());
                    $return_page .= " ".$this->link($range, $i)." ";
                }
            }
            //----------������� ������ �� �������---------------------------------------------------
            $range = $this->range(
                (($total_pages - 1) * $this->listener->getItemsPerPage() + 1),
                $this->listener->getItemsCount());
            $return_page .= " ... ".$this->link($range, $total_pages)." ";
            //----------������� ������ �� �������---------------------------------------------------
            
        //------------- ���� ������ �� ������� �� ��������� ��� getVisibleLinkCount() --------------  
        } else {
                 //--------���� �� ������� �� ���������---------------------------------------------
            for($i = $current_page; $i <= $total_pages; $i++) {
                
                if($total_pages == $i) {//--- ���� ����� �� ����� ������ �� ������� �������� ����� ���� �� ������ getItemsCount()
                    if($current_page == $i) {//---���� �������, �� ����������� ������ ��� �����������-
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount())." ";    //������ ����� ���� �� ������ getItemsCount()
                    } else {//---------�� ��� ������ - ������ -----------------------------------------
                        $range = $this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $this->listener->getItemsCount());
                        $return_page .= " ".$this->link($range, $i)." "; //������ ����� ���� �� ������ getItemsCount()
                    }
                } else {//--- ���� ��� �� ����� �� ����� ��� ��� ������-------------------------------------
                    if($current_page == $i) {//---���� �������, �� ����������� ������ ��� �����������-
                        $return_page .= " ".$this->range(
                            (($i - 1) * $this->listener->getItemsPerPage() + 1),
                            $i * $this->listener->getItemsPerPage())." "; 
                    } else {//---------�� ��� ������ - ������ -----------------------------------------
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
    
    
    // ������ ������ ����������� �������, ��� ��������� �� ��� ����� 10 � ������� ��������� -------------
    public function navigator2Way(Listener $listener) {
        
        $this->listener = $listener;
        $idx = 1;
        $links = 10; //10 �������
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
