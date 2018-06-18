<?php

use phpFastCache\CacheManager;
use phpFastCache\Core\phpFastCache;

class CacheHandler {
	public static function Manager(){
		static $InstanceCache = null;
		if($InstanceCache == null){
			$InstanceCache = CacheManager::getInstance("files");
		}

		return $InstanceCache;
	}

	public static function setToCache($name,$value,$expiry){
		if(CacheHandler::existsInCache($name)) CacheHandler::deleteFromCache($name);
		
		$c = CacheHandler::Manager()->getItem($name);
		if(is_null($c->get())){
			$c->set($value)->expiresAfter($expiry);
			CacheHandler::Manager()->save($c);
		}
	}
	
	public static function getFromCache($name){
		if(CacheHandler::existsInCache($name)){
			$c = CacheHandler::Manager()->getItem($name);
			
			return $c->get();
		} else {
			return null;
		}
	}
	
	public static function existsInCache($name){
		return CacheHandler::Manager()->hasItem($name);
		//return getFromCache($name) != null;
	}
	
	public static function deleteFromCache($name){
		$r = false;
		
		if(CacheHandler::existsInCache($name)){
			CacheHandler::Manager()->deleteItem($name);
			$r = true;
		}
		
		return $r;
	}
}