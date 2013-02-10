<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dracks
 * Date: 2/10/13
 * Time: 1:00 PM
 * To change this template use File | Settings | File Templates.
 */

function mapFunction($value){
	return '$this->listVariables[\''.$value.'\']';

}
class Template
{
	private $listVariables;
	private $templateFile;

	function __construct(){
		$this->listVariables=array();
	}

	function set($name, $value){
		$this->listVariables[$name]=$value;
	}

	function setTemplate($templateFile){
		$this->templateFile=$templateFile;
	}

	function render(){
		$contents=file_get_contents($this->templateFile);
		$phpCode=str_replace(array_keys($this->listVariables), array_map(mapFunction, array_values($this->listVariables)), $contents);
		ob_start();
		eval("?>".$phpCode."<?");
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
