<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class DocService
{
    /**
     * @param string $filename
     * @param $createDate
     * @return bool
     * @throws Exception
     */
    public function generate($filename, $createDate = null)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText(
            "Создан документ $filename.docx",
            array('name' => 'Inter', 'size' => 16, 'bold' => true, 'italic' => true),
            array("alignment" => Jc::CENTER)
        );

        if ($createDate) {
            $createDate = Carbon::parse(strtotime($createDate))->format('d.m.Y');
            $section->addText(
                "от $createDate",
                array('name' => 'Tahoma', 'size' => 10, 'italic' => true),
                array("alignment" => Jc::END)
            );
        }

        $tmpFile = tmpfile();
        $metadata = stream_get_meta_data($tmpFile);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($metadata['uri']);

        return chunk_split(base64_encode(file_get_contents($metadata['uri'])));
    }
}
