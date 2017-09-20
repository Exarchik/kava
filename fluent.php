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
    
    private function arrayize($data)
    {
        if(gettype($data)==='array')
            return $data;
        else
            return [$data];
    }
    /**
     * House::addWalls()
     * 
     * @param mixed $newWalls
     * @return
     */
    public function addWalls(array $newWalls)
    {
        $this->walls = array_merge($this->walls,$this->arrayize($newWalls));
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
        $this->doors = array_merge($this->doors,$this->arrayize($newDoors));
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
        $this->windows = array_merge($this->windows,$this->arrayize($newWindows));
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
                    $this->$funcName($this->arrayize($elementValue));
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
            ->addWindows(['new-window2'])
            ->addMixed(
                [
                    'doors'=>'mixed-door',
                    'walls'=>['mixed-wall','mixed-wall2'],
                    'windows'=>'mixed-windows'
                ]
            );


            
            
            
            
            
            


