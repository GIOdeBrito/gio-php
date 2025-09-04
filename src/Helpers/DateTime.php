<?php

namespace GioPHP\Helpers;

function toDateTime (?string $date, string $format = 'Y-m-d'): DateTimeImmutable|null
{
	if(is_null($date) || empty($date))
    {
		return NULL;
    }

    $dateTime = DateTimeImmutable::createFromFormat($format, $date);

    if(!$dateTime)
    {
        return NULL;
    }

    return $dateTime;
}

?>