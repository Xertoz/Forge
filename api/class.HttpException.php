<?php
    /**
    * class.HttpException.php
    * Copyright 2009-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge;

    /**
    * HTTP exception class. Error codes given should be any of the constants provided.
    * Throwing an exception of this class will alter the HTTP error code
    */
    class HttpException extends \Exception {
    	const HTTP_MULTIPLE_CHOICES = 300;
        const HTTP_MOVED_PERMANENTLY = 301;
        const HTTP_FOUND = 302;
        const HTTP_SEE_OTHER = 303;
        const HTTP_NOT_MODIFIED = 304;
        const HTTP_USE_PROXY = 305;
        const HTTP_SWITCH_PROXY = 306;
        const HTTP_TEMPORARY_REDIRECT = 307;
        const HTTP_BAD_REQUEST = 400;
        const HTTP_UNAUTHORIZED = 401;
        const HTTP_PAYMENT_REQUIRED = 402;
        const HTTP_FORBIDDEN = 403;
        const HTTP_NOT_FOUND = 404;
        const HTTP_METHOD_NOT_ALLOWED = 405;
        const HTTP_NOT_ACCEPTABLE = 406;
        const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
        const HTTP_REQUEST_TIMEOUT = 408;
        const HTTP_CONFLICT = 409;
        const HTTP_GONE = 410;
        const HTTP_LENGTH_REQUIRED = 411;
        const HTTP_PRECONDITION_FAILED = 412;
        const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
        const HTTP_REQUEST_URI_TOO_LONG = 414;
        const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
        const HTTP_REQUEST_RANGE_NOT_SATISFIABLE = 416;
        const HTTP_EXPECTATION_FAILED = 417;
        const HTTP_INTERNAL_SERVER_ERROR = 500;
        const HTTP_NOT_IMPLEMENTED = 501;
        const HTTP_BAD_GATEWAY = 502;
        const HTTP_SERVICE_UNAVAILABLE = 503;
        const HTTP_GATEWAY_TIMEOUT = 504;
        const HTTP_VERSION_NOT_SUPPORTED = 505;

        public function getHttpHeader() {
        	$headers = [
        		self::HTTP_MULTIPLE_CHOICES => 'Multiple Choices',
        		self::HTTP_MOVED_PERMANENTLY => 'Moved Permanently',
        		self::HTTP_FOUND => 'Found',
        		self::HTTP_SEE_OTHER => 'See Other',
        		self::HTTP_NOT_MODIFIED => 'Not Modified',
        		self::HTTP_USE_PROXY => 'Use Proxy',
        		self::HTTP_SWITCH_PROXY => 'Switch Proxy',
        		self::HTTP_TEMPORARY_REDIRECT => 'Temporary Redirect',
        		self::HTTP_BAD_REQUEST => 'Bad Request',
        		self::HTTP_UNAUTHORIZED => 'Unauthorized',
        		self::HTTP_PAYMENT_REQUIRED => 'Payment Required',
        		self::HTTP_FORBIDDEN => 'Forbidden',
        		self::HTTP_NOT_FOUND => 'Not Found',
        		self::HTTP_METHOD_NOT_ALLOWED => 'Method Not Allowed',
        		self::HTTP_NOT_ACCEPTABLE => 'Not Acceptable',
        		self::HTTP_PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
        		self::HTTP_REQUEST_TIMEOUT => 'Request Timeout',
        		self::HTTP_CONFLICT => 'Conflict',
        		self::HTTP_GONE => 'Gone',
        		self::HTTP_LENGTH_REQUIRED => 'Length Required',
        		self::HTTP_PRECONDITION_FAILED => 'Precondition Failed',
        		self::HTTP_REQUEST_ENTITY_TOO_LARGE => 'Request Entity Too Large',
        		self::HTTP_REQUEST_URI_TOO_LONG => 'Request-URI Too Long',
        		self::HTTP_UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
        		self::HTTP_REQUEST_RANGE_NOT_SATISFIABLE => 'Requested Range Not Satisfiable',
        		self::HTTP_EXPECTATION_FAILED => 'Expectation Failed',
        		self::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error',
        		self::HTTP_NOT_IMPLEMENTED => 'Not Implemented',
        		self::HTTP_BAD_GATEWAY => 'Bad Gateway',
        		self::HTTP_SERVICE_UNAVAILABLE => 'Service Unavailable',
        		self::HTTP_GATEWAY_TIMEOUT => 'Gateway Timeout',
        		self::HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported'
        	];
        	
        	$code = isset($headers[$this->getCode()]) ? $this->getCode() : self::HTTP_INTERNAL_SERVER_ERROR;
        	
        	return 'HTTP/1.1 '.$code.' '.$headers[$code];
        }
    }