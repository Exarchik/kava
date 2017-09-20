<?php

/**
 * House
 * 
 * @package   
 * @author git-test
 * @copyright admin
 * @version 2017
 * @access public
 */
class House
{
    private $walls = [];
    private $doors = [];
    private $windows = [];
    
    /**
     * House::addWalls()
     * 
     * @param mixed $newWalls
     * @return
     */
    public function addWalls(array $newWalls)
    {
        $this->walls = array_merge($this->walls,$newWalls);
        return $this;
    }
    
    /**
     * House::addDoors()
     * 
     * @param mixed $newDoors
     * @return
     */
    public function addDoors(array $newDoors)
    {
        $this->doors = array_merge($this->doors,$newDoors);
        return $this;
    }
    
    /**
     * House::addWindows()
     * 
     * @param mixed $newWindows
     * @return
     */
    public function addWindows(array $newWindows)
    {
        $this->windows = array_merge($this->windows,$newWindows);
        return $this;
    }
    
    /**
     * House::addMixed()
     * 
     * @param mixed $newMixed
     * @return
     */
    public function addMixed(array $newMixed)
    {
        foreach ($newMixed as $elementType => $elementValue)
        {
            $funcName = "add".ucfirst(strtolower($elementType));
            if (method_exists($this,$funcName) && ($funcName!=='addMixed'))
            {
                if(gettype($elementValue)==='array')
                    $this->$funcName($elementValue);
                else
                    $this->$funcName([$elementValue]);
            }
        }
        return $this;
    }
    
    /**
     * House::__toString()
     * 
     * @return
     */
    public function __toString()
    {
        return '<pre>'.print_r([$this->walls,$this->doors,$this->windows],1).'</pre>';
    }
    
}

$some_walls = ['wall1','wall2','wall3'];
$some_doors = ['door1','door2','door3'];
$some_windows = ['window1'];

$houseOfMine = new House();

echo $houseOfMine->addDoors($some_doors)
            ->addWalls($some_walls)
            ->addWindows($some_windows)
            ->addMixed([
                    'doors'=>'mixed-door',
                    'walls'=>['mixed-wall','mixed-wall2'],
                    'windows'=>'mixed-windows'
                ])
            ->addWindows(['new-window1'])
            ->addWindows(['new-window2']);


            
            
            
            
            
            


