<?php


/**
 * City
 * 
 * @package git-test
 * @author admin
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class City
{
	public $houses = [];
	
	static private $_instance;
	
	/**
	 * City::__construct()
	 * 
	 * @return void
	 */
	protected function __construct(){}
	
	/**
	 * City::getInstance()
	 * 
	 * @return
	 */
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * City::addHouse()
	 * 
	 * @param mixed $_houses
	 * @return
	 */
	public function addHouse($_houses)
	{
		array_push($this->houses,$_houses);
		return $this;
	}
	
	/**
     * House::__toString()
     * 
     * @return
     */
    public function __toString()
    {
        return '<pre>'.print_r([$this->houses],1).'</pre>';
    }
}



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
    
    private $elementsCount = 0;
	
    /**
     * House::arrayize()
     * 
     * @return
     */
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
     * House::getElementCount()
     * 
     * @return
     */
    public function getElementCount()
    {
        return $this->elementsCount = count($this->walls)+count($this->doors)+count($this->windows);
    }
    
    /**
     * House::endHouse()
     * 
     * @return
     */
    public function endHouse()
    {
        $this->getElementCount();
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
$houseOfTheir = new House();
$city = City::getInstance();

$houseOfMine->addDoors($some_doors)
		->addWalls($some_walls)
		->addWindows($some_windows)
		->addDoors($some_doors)
		->addWindows(['new-window2'])
		->addMixed([
				'doors'=>'mixed-door',
				'walls'=>['mixed-wall','mixed-wall2'],
				'windows'=>'mixed-windows'
			])
		->addWindows('new-window3')
        ->endHouse();

$houseOfTheir->addWindows(['new-window2'])
		->addMixed([
				'doors'=>'mixed-door',
				'walls'=>['mixed-wall','mixed-wall2'],
				'windows'=>'mixed-windows'
			])
		->addWindows('new-window3')
        ->endHouse();

$city->addHouse($houseOfMine);


$city2 = City::getInstance();
$city2->addHouse($houseOfTheir);

echo $city2;

            
            
            
            
            
            


