<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

namespace Kit\Smsservices;

class Agent {
	
	public static function turnSms() {
		$ob = new \Kit\Smsservices\Sender();
		$ob->getTurnSms();
		return '\\Kit\\Smsservices\\Agent::turnSms();';
	}

	public static function statusSms() {
		$ob = new \Kit\Smsservices\Sender();
		$ob->getStatusSms();
		return '\\Kit\\Smsservices\\Agent::statusSms();';
	}
	
}