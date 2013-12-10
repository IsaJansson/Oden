<?php
/*
* Interface fro class that interacts with the database to encapsulate all the SQL requests.
* @package OdenCore
*/
interface IHasSQL {
	public static function SQL($key = null);
}