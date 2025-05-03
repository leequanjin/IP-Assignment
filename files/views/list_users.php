<!-- Author     : keekeshen -->
<?php
$xml = new DOMDocument();
$xml->load('../../xml-files/users.xml');

$xsl = new DOMDocument();
$xsl->load('../xslt/users.xsl');

$proc = new XSLTProcessor();
$proc->importStylesheet($xsl);

echo $proc->transformToXML($xml);
?>
