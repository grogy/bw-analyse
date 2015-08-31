<?php

class InfoBox
{
	public $name;

	public $properties;


	public function __construct($name, array $properties)
	{
		$this->name = $name;
		$this->properties = $properties;
	}


	public function getName()
	{
		return $this->name;
	}


	public function getProperty($key)
	{
		if (!isset($this->properties[$key])) {
			throw new InvalidArgumentException;
		}
		return $this->properties[$key];
	}
}
