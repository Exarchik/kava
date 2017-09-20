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
    
	
    private function arrayize()
    {
	$res = [];
	foreach (func_get_args() as $kArg => $vArg)
	{
		if(gettype($vArg)==='array')
			$res = array_merge($res,$vArg);
		else
			array_push($res,$vArg);
	}
	return $res;
    }
    /**
     * House::addWalls()
     * 
     * @param mixed $newWalls
     * @return
     */
    public function addWalls($newWalls)
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
    public function addDoors($newDoors)
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
    public function addWindows($newWindows)
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
    public function addMixed($newMixed)
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
			->addDoors($some_doors)
            ->addWindows(['new-window2'])
            ->addMixed([
                    'doors'=>'mixed-door',
                    'walls'=>['mixed-wall','mixed-wall2'],
                    'windows'=>'mixed-windows'
                ])
			->addWindows('new-window3');



            
            
            
            
            
            


