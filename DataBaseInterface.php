<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dracks
 * Date: 2/10/13
 * Time: 5:15 PM
 * To change this template use File | Settings | File Templates.
 */
interface DataBaseInterface
{
	function createTable($name, $scheme);

	function alterTable($table, $fields, $afters);

	function getTables();

	function getTableScheme($table);

	function insertDataInTable($table, $row);

}
