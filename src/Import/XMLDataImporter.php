<?php

namespace App\Import;

use App\Entity\ImportDetail;

class XMLDataImporter
{
    public function importData(ImportDetail $importDetail)
    {
        $xml = simplexml_load_file($importDetail->getUrl(), "SimpleXMLElement", LIBXML_NOCDATA);

        return json_decode(json_encode($xml),TRUE);

    }
}