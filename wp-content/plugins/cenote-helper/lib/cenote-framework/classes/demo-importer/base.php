<?php
defined('ABSPATH') or die;

class Cenote_Demo_Importer_Base {
	protected static $data;

	public function __construct($data = null) {
		self::$data		= $data;
	}
}
