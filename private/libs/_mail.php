<?php
/**
 */
class _mail
{
    #
	static public function send($to, $subject, $body)
	{
        mail($to, $subject, $body);
    }
}
