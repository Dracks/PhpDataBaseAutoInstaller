<?php

class Install{

	/** @var Index */
	private $program;
	private $schemeDb;
	/** @var DataBaseInterface */
	private $db;
	/**
	 * @param Index $program
	 * @param array $configurationParser
	 * @param DataBaseInterface $db
	 */
	function __construct($program, $schemeDb, $db){
		$this->program=$program;
		$this->schemeDb=$schemeDb;
		$this->db=$db;
	}

	function dispatch($step){
		if ($step=='run'){
			$this->program->getTemplate()->setTemplate('templates/create_tables.html');
			foreach ($this->schemeDb['tables'] as $table=>$content){
				$this->db->createTable($table, $content['scheme']);
			}
		} else if ($step='install'){
			$this->program->getTemplate()->setTemplate('templates/inserted_data.html');
			foreach ($this->schemeDb['tables'] as $table=>$content){
				if (isset($content['install'])){
					foreach ($content['install'] as $row){
						$this->db->insertDataInTable($table, $row);
					}
				}
			}
		}
	}
}