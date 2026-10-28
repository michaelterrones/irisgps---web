<?php

class JMUserAgent {

	public static function isAndroidRequest()
	{
		return (Request::header('User-Agent') == 'androidjmhttp') ? true : false;
	}	

	public static function isWebRequest()
	{
		return ((Request::header('User-Agent') == 'androidjmhttp') || Request::ajax()) ? false : true;
	}

}