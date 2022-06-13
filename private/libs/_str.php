<?php
/**
 */
class _str
{
    # ID no único
	static public function id($s='')
	{
        return substr(md5($s), 0, 11);
    }

    # ID único
	static public function aid()
	{
        return substr(md5(microtime()), 0, 11);
    }
    
    #
    /*public static function to_small_case($str) {
        $str = preg_replace('/([a-z])([A-Z])/', '$1_$2', $str);
        return mb_strtolower($str);    
    }

    #
    public static function test_to_small_case($str) {
        $tests = [
            'simpleTest' => 'simple_test',
            'easy' => 'easy',
            'HTML' => 'html',
            'simpleXML' => 'simple_xml',
            'PDFLoad' => 'pdf_load',
            'startMIDDLELast' => 'start_middle_last',
            'AString' => 'a_string',
            'Some4Numbers234' => 'some4_numbers234',
            'TEST123String' => 'test123_string',
        ];
        
        foreach ($tests as $test=>$result) {
            $output = self::to_small_case($test);
            if ($output === $result) {
                $results[] = "Pass: $test => $result";
            }
            else {
                $results[] = "Fail: $test => $result [$output]";
            }
        }
        _var::die($results);
    }*/
}
