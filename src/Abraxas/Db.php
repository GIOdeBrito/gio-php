<?php

namespace GioPHP\Abraxas;

use GioPHP\Services\Loader;

/* Database singleton */

class Db
{
	private static ?\PDO $pdo = NULL;
	private static ?Loader $loader = NULL;

	public static function constructor (Loader $loader): void
	{
		self::$loader = $loader;
	}

	public static function status (): void
	{
		if(is_null(self::$loader))
		{
			echo "Loader not set";
			return;
		}

		echo "Loader set";
	}

	public static function open (): bool
	{
		$connection = self::$loader->connectionString;
		$login = self::$loader->dbLogin;
		$pwd = self::$loader->dbPwd;

		try
		{
			self::$pdo = new \PDO($connection, $login, $pwd);
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return true;
		}
		catch(\PDOException $e)
		{
			//echo "Error: ".$e->getMessage();
			return false;
		}
	}

	public static function query (string $sql, array $params = [], bool $isObject = false): array|object
	{
		if(is_null(self::$pdo))
		{
			return NULL;
		}

		try
		{
			$res = self::$pdo->prepare($sql);

			// Set SQL bindings
			foreach($params as $i => $value)
			{
				$res->bindValue($i + 1, $value);
			}

			$res->execute();

			// Return data as objects
			if($isObject)
			{
				return $res->fetchAll(\PDO::FETCH_OBJ);
			}

			// Return as array
			return $res->fetchAll(\PDO::FETCH_ASSOC);
		}
		catch(\PDOException $ex)
		{
			return (object)[ 'err' => true, 'message' => $ex->getMessage() ];
		}
	}

	public static function exec (string $sql, array $params = []): object
	{
		if(is_null(self::$pdo))
		{
			return NULL;
		}
	}

	public static function commit (): void
	{
		$this->pdo->commit();
	}

	public static function rollback (): void
	{
		$this->pdo->rollback();
	}

	public static function close (): void
	{
		self::$pdo = NULL;
	}
}

?>