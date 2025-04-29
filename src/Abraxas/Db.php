<?php

namespace GioPHP\Abraxas;

use GioPHP\Services\Loader;
use GioPHP\Services\Logger;

/* Database singleton */

class Db
{
	private static ?\PDO $pdo = NULL;
	private static Loader $loader;
	private static Logger $logger;

	public static function constructor (Loader $loader, Logger $logger): void
	{
		self::$loader = $loader;
		self::$logger = $logger;
	}

	public static function open (): void
	{
		$connection = self::$loader->connectionString;
		$login = self::$loader->dbLogin;
		$pwd = self::$loader->dbPwd;

		try
		{
			self::$pdo = new \PDO($connection, $login, $pwd);
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			self::$logger->info("DB successfully connected.");
		}
		catch(\PDOException $ex)
		{
			self::$logger->error("Failed to open DB connection: {$ex->getMessage()}.");
		}
	}

	public static function isConnected (): bool
	{
		if(is_null(self::$pdo))
		{
			return false;
		}

		return true;
	}

	public static function query (string $sql, array $params = [], bool $isObject = false): array|object
	{
		if(!self::isConnected())
		{
			return [];
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

	public static function exec (string $sql, array $params = []): bool
	{
		if(!self::isConnected())
		{
			return false;
		}

		$res = self::$pdo->prepare($sql);

		// Set SQL bindings
		foreach($params as $i => $value)
		{
			$res->bindValue($i + 1, $value);
		}

		try
		{
			self::$pdo->beginTransaction();

			$result = $res->execute();

			return $result;
		}
		catch(\Exception $ex)
		{
			self::$logger->error($ex->getMessage());
			return false;
		}
	}

	public static function commit (): void
	{
		self::$pdo->commit();
	}

	public static function rollback (): void
	{
		self::$pdo->rollback();
	}

	public static function close (): void
	{
		self::$pdo = NULL;
	}
}

?>