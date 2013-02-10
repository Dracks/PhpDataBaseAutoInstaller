PhpDataBaseAutoInstaller
========================

Is a tool to create and maintenance a web application database describing that, when you have a new version, with a new database version, you only need to run the tool, and tool will be upgrade your database 

This should accept multiples languages, and will be too easy to configure. It create your config.php

Insert your language files in Texts/languagecode.json

Use only json to configure

Example file
============
database_description.json:
```json
{
	"fileConfig":"../config.php",
	"configFields":{
		"database_user":{
			"type":"plain",
			"map":"db_user"
		},
		"database_password":{
			"type":"password",
			"map":"db_password"
		},
		"database_host":{
			"type":"plain",
			"map":"db_host"
		},
		"database":{
			"type":"plain",
			"map":"db_database"
		},
		"database_type":{
			"type":"hidden",
			"value":"mysql",
			"map":"db_type"
		},

		"otherFieldInConfig":{
			"type":"enum",
			"accept":["true", "false"]
		}
	},
	"tables":{
		"table_1":{
			"scheme":{
				"fields":{
					"id": {"type":"int(11)"},
					"user":{"type":"varchar(256)"},
					"password": {"type":"char(40)"}
				}
			},
			"install":[
				{"id":1, "user":"hola", "password":"**.-..**"},
				{"id":2, "user":"hola2", "password":"**.-..**"}
			]
		}
	}
}
```
