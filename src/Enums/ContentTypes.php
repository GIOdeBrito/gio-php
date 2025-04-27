<?php

namespace GioPHP\Enums;

enum ContentTypes: string
{
	case HTML 	= "text/html";
	case PLAIN 	= "text/plain";
	case JSON 	= "application/json";
	case FILE 	= "application/octet-stream";
}

?>