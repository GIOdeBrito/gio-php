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
			self::$logger->info("Connected to database.");
		}
		catch(\PDOException $ex)
		{
			self::$logger->error("Failed to open database connection: {$ex->getMessage()}.");
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

			// Set param binds
			if(count($params) > 0)
			{
				self::setPDOBinds($res, $params);
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

		try
		{
			$res = self::$pdo->prepare($sql);

			self::$pdo->beginTransaction();

			// Set param binds
			if(count($params) > 0)
			{
				self::setPDOBinds($res, $params);
			}

			$result = $res->execute();

			return $result;
		}
		catch(\Exception $ex)
		{
			self::$logger->error($ex->getMessage());
			return false;
		}
	}

	private static function setPDOBinds (\PDOStatement $statement, array $params): void
	{
		// Sequential bindings
		if(array_is_list($params))
		{
			foreach($params as $i => $value)
			{
				$statement->bindValue($i + 1, $value);
			}

			return;
		}

		// Associative bindings
		foreach($params as $key => $value)
		{
			if(!str_starts_with($key, ':'))
			{
				$key = ':'.$key;
			}

			$statement->bindValue($key, $value);
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