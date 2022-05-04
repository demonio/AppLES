<?php
/**
 */
class _mail
{
    #
	static public function send($to, $subject, $body)
	{
		if ($_SERVER['HTTP_HOST'] == 'localhost') {
			return;
		}

		$headers[] = 'From: webmaster@multisitio.es';
		$headers[] = 'Reply-To: webmaster@multisitio.es';

		mail($to, $subject, $body, implode("\r\n", $headers));

        _var::die([$to, $subject, $body, implode("\r\n", $headers)]);
    }
}
