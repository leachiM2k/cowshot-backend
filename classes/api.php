<?php
include 'PasswordGen.php';

class ShotpicAPI
{
	protected $config;
	const CONFIG_FILE = '../config/application.ini';

	public function __construct()
	{
		if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::CONFIG_FILE))
		{
			$this->config = parse_ini_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::CONFIG_FILE, true);
		} else {
			throw new Exception("No config file found");
		}
	}

	/**
	 * @publicAPI
	 */
	public function getToken()
	{
		$generator = new PasswordGen();
		return $generator->PWGen(32, true, true, true);
	}

	public function putFile($token)
	{
		/* PUT Daten kommen in den stdin Stream */
		$putdata = fopen("php://input", "r");

		/* Eine Datei zum Schreiben öffnen */
		$fp = fopen($this->getFilePath($token), "w");

		/*
		 * Jeweils 1kB Daten lesen und in die Datei schreiben
		 */
		while ($data = fread($putdata, 1024))
			fwrite($fp, $data);

		/* Die Streams schließen */
		fclose($fp);
		fclose($putdata);

		$this->setFileMetadata($token, $info);

		header("HTTP/1.1 201 Created $c");
	}

	public function getFilePath($token)
	{
		return $this->config["files"]["folder"] . DIRECTORY_SEPARATOR . $this->config["files"]["prefix"] . "_" . $token .".png";
	}

	public function getFileMetadata($token)
	{
		if(!file_exists($this->getFilePath($token).'.json'))
		{
			return null;
		}

		return json_decode(file_get_contents($this->getFilePath($token).'.json'), true);
	}

	public function setFileMetadata($token, $data)
	{
		file_put_contents($this->getFilePath($token).'.json', json_encode($data));
	}

	public function getPublicMethods()
	{
		$accessibleMethods = array();

		$rfl = new ReflectionClass($this);
		$methods = $rfl->getMethods(ReflectionMethod::IS_PUBLIC);

		foreach($methods as $method) {
			$comment = $method->getDocComment();
			$tag = "@publicAPI";
			if(strpos($comment, $tag) !== FALSE)
			{
				$accessibleMethods[] = $method->getName();
			}
		}

		return $accessibleMethods;
	}
}
