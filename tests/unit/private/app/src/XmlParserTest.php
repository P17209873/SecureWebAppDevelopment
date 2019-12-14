<?php


use SecureWebAppCoursework\XmlParser;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    public function testXMLConstruct() {
        $XmlParser = new \SecureWebAppCoursework\XmlParser();
        $XmlParser -> parseTheXmlString();

        $this->assertEquals([], $XmlParser -> getParsedData());
    }

    public function testSetXmlStringToParseAndParsing() {
        $XmlParser = new \SecureWebAppCoursework\XmlParser();
        $stringMessage = "<messagerx><destinationmsisdn>447817814149</destinationmsisdn><receivedtime>09/12/2019 12:28:28</receivedtime><message>TestMessage</message></messagerx>";
        $XmlParser -> setXmlStringToParse($stringMessage);

        $parsedXml = array(
            'DESTINATIONMSISDN' => "447817814149",
            'RECEIVEDTIME' => "09/12/2019 12:28:28",
            'MESSAGE' => "TestMessage"
        );

        $XmlParser -> parseTheXmlString();

        $this->assertEquals($parsedXml, $XmlParser -> getParsedData());
    }

}
