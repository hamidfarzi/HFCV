<?php

/**
 * The css parser class
 * @package hfcv
 */

namespace HFCV;

class CssParser
{
    private $cssContent;
    private $rootObject;


    public function __construct(string $content)
    {
        $this->cssContent = $content;
    }

    //extract the root variables from css content
    public function extractRootObject()
    {
        $lines = explode("\n", $this->cssContent);
        $parsedData = [];

        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || strpos($line, '/*') === 0) {
                continue; // Skip empty lines and comments
            }

            if (strpos($line, ':root') === 0) {
                $currentSection = ':root';
                $parsedData[$currentSection] = [];
            } elseif (strpos($line, '--') === 0 && strpos($line, ':') !== false) {
                $parts = explode(':', $line, 2);
                $property = trim($parts[0]);
                $value = trim($parts[1]);
                $value = str_replace(';', '', $value);

                if ($currentSection === ':root') {
                    $parsedData[$currentSection][$property] = $value;
                }
            }
        }
        $this->rootObject = $parsedData;
        return $parsedData;
    }

    public function getRoot()
    {
        return $this->rootObject;
    }
}
