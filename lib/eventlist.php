<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

namespace Kit\Smsservices;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class EventlistTable extends Entity\DataManager
{
	public static $oldId = null;
	
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'kit_smsservices_eventlist';
	}
	
	public static function getMap()
	{
		return array(
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
				)
			),
			new Entity\StringField('SITE_ID', array(
				'required' => true,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 10),
					);
				}
				)
			),
			new Entity\StringField('SENDER', array(
				'required' => false,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 50),
					);
				}
				)
			),
			new Entity\StringField('EVENT', array(
				'required' => true,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 50),
					);
				}
				)
			),
			new Entity\StringField('NAME', array(
				'required' => true,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 255),
					);
				}
				)
			),
			new Entity\StringField('TEMPLATE', array(
				'required' => false,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 2500),
					);
				}
				)
			),
			new Entity\StringField('PARAMS', array(
				'required' => false,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 6255),
					);
				}
				)
			),
			new Entity\StringField('ACTIVE', array(
				'required' => false,
				'validation' => function(){
					return array(
						new Entity\Validator\Length(null, 1),
					);
				}
				)
			)
		);
	}
	
	
	public static function onAfterAdd(\Bitrix\Main\Entity\Event $event){
		$params = $event->getParameter('fields');
		\Kit\Smsservices\EventlistTable::addEvent($params['EVENT']);
	}
	
	public static function onAfterUpdate(\Bitrix\Main\Entity\Event $event){
		$params = $event->getParameter('fields');
		\Kit\Smsservices\EventlistTable::addEvent($params['EVENT']);
	}
	
	public static function onBeforeDelete(\Bitrix\Main\Entity\Event $event){
		$params = $event->getParameter('id');
		if($params['ID']){
			$ar = \Kit\Smsservices\EventlistTable::getRowById($params['ID']);
			self::$oldId = $ar['EVENT'];
		}
	}
	
	public static function onAfterDelete(\Bitrix\Main\Entity\Event $event){
		$params = $event->getParameter('id');
		if($params['ID']){
			$ar = \Kit\Smsservices\EventlistTable::getList(array(
				'select' => array('ID'),
				'filter' => array('EVENT'=>self::$oldId),
				'limit' => 1
			));
			if(!$ar->fetch()) \Kit\Smsservices\EventlistTable::removeEvent(self::$oldId);
		}
		self::$oldId = null;
	}
	
	public static function addEvent($eventCode) {
		
		$events = \Kit\Smsservices\Events::getList();
		
		if(is_array($events[$eventCode])) {
			
			$ev = $events[$eventCode];
			
			$eventManager = \Bitrix\Main\EventManager::getInstance();
			foreach($ev['BX_EVENT'] as $val){
				if($val[2] !== null){
					$new = ($val[5] == 'new') ? true : false;
					if($new) {
						$eventManager->registerEventHandler($val[0], $val[1], $val[2], $val[3], $val[4]);
					}else{
						$eventManager->registerEventHandlerCompatible($val[0], $val[1], $val[2], $val[3], $val[4]);
					}
				}
			}
			
		}
		
	}
	
	public static function removeEvent($eventCode) {
		
		$events = \Kit\Smsservices\Events::getList();
		
		$ev = $events[$eventCode];
		
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		
		foreach($ev['BX_EVENT'] as $val){
			if($val[2] !== null){
				UnRegisterModuleDependences($val[0], $val[1], $val[2], $val[3], $val[4]);
			}
		}
		
	}
	
	public static function removeAllEvent() {
		$events = \Kit\Smsservices\Events::getList();
		
		foreach($events as $evCode=>$ev){
			foreach($ev['BX_EVENT'] as $val){
				if($val[2] !== null){
					UnRegisterModuleDependences($val[0], $val[1], $val[2], $val[3], $val[4]);
				}
			}
		}
	}
}