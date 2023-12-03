<?php

class Scripts {

	private $list = [];

	function __construct($view) {

		//load framework scripts
		$files = glob(SERVER_ROOT . '/system/scripts/*.js');
		foreach ($files as $file) $this->add($file, 1);

		//load app scripts
		$files = glob(SERVER_ROOT . '/app/scripts/*.js');
		foreach ($files as $file) $this->add($file, 2);

		//load page scripts
		if (file_exists($file = SERVER_ROOT . '/pages/scripts/' . $view->name . '.js')) $this->add($file, 4);

		//load scripts in the page js folder, if present
		$files = glob(SERVER_ROOT . '/pages/scripts/' . $view->name . '/*.js');
		foreach ($files as $file) $this->add($file, 5);
	}

	//load a script
	public function add($file, $priority = 3) {

		$file .= '?v=' . filemtime($file);

		$this->list[] = $priority . '<script src="' . parse_html($file) . '"></script>' . PHP_EOL;
	}

	//make the string of styles
	public function make() {

		$list = [];

		asort($this->list);

		foreach ($this->list as $script) $list[] = substr($script, 1);

		return implode('', $list);
	}
}
