<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dracks
 * Date: 2/10/13
 * Time: 5:18 PM
 * To change this template use File | Settings | File Templates.
 */
class MySQL implements DataBaseInterface
{
	private $con;

	function __construct($con){
		$this->con=$con;
	}

	private function getRowDefinition($name, $schema){
		return "`$name` ".$schema['type'].' '.(!isset($schema['default'])? 'default "'.$schema['default'].'" ': '').(isset($schema['null']) && $schema['null']? '': 'NOT NULL ');
	}

	function createTable($name, $scheme)
	{
		$sql="Create table '$name'(\n";
		foreach($scheme['fields'] as $field=>$data){
			$sql.="\t".$this->getRowDefinition($field, $data).",\n";
		}
		if (isset($scheme['keys'])){
			foreach($scheme['keys'] as $listFields){
				$sql.="\tkey(`".implode('`, `', $listFields)."`)\n";
			}
		}

		if (isset($scheme['primaryKey'])){
			$sql.="\tprimary key(`".implode('`, `', $scheme['primaryKey'])."`)\n";
		}

		$sql.=");";
		mysql_query($sql, $this->con);
	}

	function alterTable($table, $fields, $afters){

	}

	function getTables()
	{
		// TODO: Implement getTables() method.
	}

	function getTableScheme($table)
	{
		// TODO: Implement getTableScheme() method.
	}

	function insertDataInTable($table, $row)
	{

	}
}
