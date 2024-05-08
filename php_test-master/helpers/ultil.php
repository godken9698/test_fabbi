<?php

class Restaurant {
	public $meals;
	public $dishs;
	function __construct()
	{
		$root = dirname(__DIR__);
		$dishs = json_decode(file_get_contents($root . '/data/dishes.json'));
		$this->dishs = $dishs->dishes;
		$this->meals = $this->get_all_meals();
	}
	public function get_all_meals(){
		$meals = array();
		foreach ($this->dishs as $k => $v) {
			foreach ($v->availableMeals as $key => $meal) {
				if(array_search($meal, $meals) === false) $meals[] = $meal;
			}
		}
		return $meals;
	}
}