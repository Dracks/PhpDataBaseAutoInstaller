<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dracks
 * Date: 2/10/13
 * Time: 12:55 PM
 * To change this template use File | Settings | File Templates.
 */

define("textsFolder", "Texts/");
$languageMap=Array(
	"en"=>"English",
	"sp"=>"Spanish",
	"ca"=>"Catalan"
);
function map($value){
	global $languageMap;
	$key=str_replace(".json", "", $value);
	return array("id"=>$key, "name"=>$languageMap[$key]);
}

function filter($value){
	return strpos($value, ".json")!==false;
}
class Texts
{
	private $contents;

	static private $instance;

	function __construct($iso){
		$this->contents=json_decode(file_get_contents(textsFolder."$iso.json"));
		Texts::$instance=$this;
	}

	/**
	 * @return Texts
	 */
	static function getShared(){
		return Texts::$instance;
	}

	static function getLanguages(){
		return array_map(map,array_filter(filter, scandir(textsFolder)));
	}

	function get($section, $text){
		if (isset($this->contents[$section]) && isset($this->contents[$section][$text])){
			return $this->contents[$section][$text];
		} else {
			return "<b>$section</b> / <b>$text</b> not found";
		}
	}
}
