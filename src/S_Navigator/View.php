<?php


namespace S_Navigator;


abstract class View
{
  
    protected $listener;

   
    public function link($title, $current_page = 1)
    {
        return "<a href='{$this->listener->getCurrentPagePath()}?".
               "{$this->listener->getCounterParam()}={$current_page}".
               "{$this->listener->getParameters()}'>{$title}</a>";
    }

   
    abstract public function navigator(Listener $listener);
}
