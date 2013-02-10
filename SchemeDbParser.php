<?php


class SchemeDbParser
{
	private $md5;
	private $configuration;
	private $fileExists;
	private $isNewMd5;
	private $isValid;

	/**
	 * @param index $program
	 * @param string $schemaDbConfigFile
	 * @param string $md5
	 */
	function __construct($program, $schemaDbConfigFile, $md5){
		if (file_exists($schemaDbConfigFile)){
			$this->md5=md5_file($schemaDbConfigFile);
			if ($md5!=$this->md5){
				$this->isValid=$this->validate($program, $schemaDbConfigFile);
				$this->isNewMd5=true;
			} else {
				$this->configuration=json_decode($schemaDbConfigFile);
				$this->isNewMd5=false;
				$this->isValid=true;
			}
		} else {
			$program->showErrorPage("File configuration not found!", "Please check the autoinstaller configuration is: $schemaDbConfigFile");
		}
	}

	function getMd5(){
		return $this->md5;
	}

	/**
	 * @param index $program
	 * @param string $schemaDbConfigFile
	 */
	private function validate($program, $schemaDbConfigFile){
		$texts=Texts::getShared();
		$errorTitle=$texts->get("Errors", "JSON title");
		$listErrors=array();
		$this->configuration=json_decode($schemaDbConfigFile);
		if ($this->configuration==null){
			$program->showErrorPage($errorTitle, json_last_error());
			return false;
		}
		if (!isset($this->configuration['fileConfig'])){
			$listErrors[]=$texts->get("Error", "fileConfig");
		}
		if (!isset($this->configuration['configFields'])){
			$listErrors[]=$texts->get("Error", "configFields");
		} else {
			$listFieldsMap=array('db_host', 'db_user', 'db_password', 'db_type', 'db_database');
			foreach ($this->configuration['configFields'] as $config=>$data){
				if (!isset($data['type'])){
					$listErrors[]=str_replace('{field}', $config, $texts->get('Error', 'config_type'));
				} else if (!in_array($data['type'],array('plain', 'password', 'enum', 'hidden'))){
					$listErrors[]=str_replace('{field}', $config, $texts->get('Error', 'config_type_value'));
				} else if ($data['type']=='enum' && !isset($data['accept'])){
					$listErrors[]=str_replace('{field}', $config, $texts->get('Error', 'config_enum'));
				}

				if (isset($data['map'])){
					if(($key = array_search($data['map'], $listFieldsMap)) !== false) {
						unset($listFieldsMap[$key]);
					} else {
						$listErrors[]=str_replace('{field}', $data['map'], $texts->get('Error', 'config_field_map'));
					}
				}
			}

			if (count($listFieldsMap)>0){
				$listErrors[]=$texts->get('Error', 'config_map');
			}
		}

		if (!isset($this->configuration['tables'])){
			$listErrors[]=$texts->get("Error", "tables");
		} else {
			foreach ($this->configuration['tables'] as $name=>$contents){
				if (!isset($contents['scheme'])){
					$listErrors[]=str_replace("{table}", $name,$texts->get("Error", "table_scheme"));
				} else if (!isset($contents['scheme']['fields'])){
					$listErrors[]=str_replace("{table}", $name,$texts->get("Error", "table_fields"));
				} else {
					$fieldList=$contents['scheme']['fields'];
					foreach ($fieldList as $field=>$description){
						if (!isset($description['type'])){
							$listErrors[]=str_replace(array('{table}', '{field}'), array($name, $field), $name,$texts->get("Error", "table_field_type"));
						}
					}

					/*
					 * check all primary keys are correct fields
					 */
					if (isset($contents['scheme']['primaryKey'])){
						foreach($contents['scheme']['primaryKey'] as $field){
							if (!isset($fieldList[$field])){
								$listErrors[]=str_replace(array('{table}', '{field}'), array($name, $field), $name,$texts->get("Error", "table_data_field"));
							}
						}
					}

					/*
					 * check all keys has correct fields
					 */
					if (isset($contents['scheme']['keys'])){
						foreach($contents['scheme']['keys'] as $rowToInstall){
							foreach ($rowToInstall as $field=>$value){
								if (!isset($fieldList[$field])){
									$listErrors[]=str_replace(array('{table}', '{field}'), array($name, $field), $name,$texts->get("Error", "table_data_field"));
								}
							}
						}
					}

					/*
					 * Check installation data
					 */
					if (isset($contents['install'])){
						foreach($contents['install'] as $rowToInstall){
							foreach ($rowToInstall as $field=>$value){
								if (!isset($fieldList[$field])){
									$listErrors[]=str_replace(array('{table}', '{field}'), array($name, $field), $name,$texts->get("Error", "table_data_field"));
								} else {
									/// @todo check the contents inserted
								}
							}
						}
					}


				}
			}
		}
		if (count($listErrors)>0){
			$program->showErrorPage($errorTitle, $listErrors);
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param string $section
	 * @return mixed
	 */
	function getSection($section){
		return $this->configuration[$section];
	}
}
