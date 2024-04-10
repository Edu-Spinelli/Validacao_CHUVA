<?php

namespace Chuva\Php\WebScrapping;
require __DIR__ .'/../../vendor/autoload.php';
use \Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use \Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
/**
 * Runner for the Webscrapping exercise.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $conteudo = file_get_contents(__DIR__ . '/../../assets/origin.html');
    $conteudo_utf8 = mb_convert_encoding($conteudo, 'UTF-8', mb_detect_encoding(__DIR__ . '/../../assets/origin.html', 'UTF-8, ISO-8859-1, ISO-8859-15', true));
    libxml_use_internal_errors(true); // Ignora erros de HTML mal formado
    $html_chuva = new \DOMDocument();
    if ($html_chuva->loadHTML($conteudo_utf8) === false) {
        echo "Erro ao carregar o HTML.";
    } else {
        echo "HTML carregado com sucesso.";
    }
    $html_chuva->saveHTML();
    libxml_clear_errors();

    $data = (new Scrapper())->scrap($html_chuva); //recebe o vetor paper
    //print_r($data);
    // Write your logic to save the output file bellow.
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile('final.xlsx');
    $style_head = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(10)
           ->setFontName('Arial')
           ->build();

    $style_all = (new StyleBuilder())
                ->setFontSize(10)
                ->setFontName('Arial')
                ->build();


    $cells = [
        WriterEntityFactory::createCell('ID'),
        WriterEntityFactory::createCell('Title'),
        WriterEntityFactory::createCell('Type'),
        WriterEntityFactory::createCell('Author 1'),
        WriterEntityFactory::createCell('Author 1 Institution'),
        WriterEntityFactory::createCell('Author 2'),
        WriterEntityFactory::createCell('Author 2 Institution'),
        WriterEntityFactory::createCell('Author 3'),
        WriterEntityFactory::createCell('Author 3 Institution'),
        WriterEntityFactory::createCell('Author 4'),
        WriterEntityFactory::createCell('Author 4 Institution'),
        WriterEntityFactory::createCell('Author 5'),
        WriterEntityFactory::createCell('Author 5 Institution'),
        WriterEntityFactory::createCell('Author 6'),
        WriterEntityFactory::createCell('Author 6 Institution'),
        WriterEntityFactory::createCell('Author 7'),
        WriterEntityFactory::createCell('Author 7 Institution'),
        WriterEntityFactory::createCell('Author 8'),
        WriterEntityFactory::createCell('Author 8 Institution'),
        WriterEntityFactory::createCell('Author 9'),
        WriterEntityFactory::createCell('Author 9 Institution'),
        WriterEntityFactory::createCell('Author 10'),
        WriterEntityFactory::createCell('Author 10 Institution'),
        WriterEntityFactory::createCell('Author 11'),
        WriterEntityFactory::createCell('Author 11 Institution'),
        WriterEntityFactory::createCell('Author 12'),
        WriterEntityFactory::createCell('Author 12 Institution'),
        WriterEntityFactory::createCell('Author 13'),
        WriterEntityFactory::createCell('Author 13 Institution'),
        WriterEntityFactory::createCell('Author 14'),
        WriterEntityFactory::createCell('Author 14 Institution'),
        WriterEntityFactory::createCell('Author 15'),
        WriterEntityFactory::createCell('Author 15 Institution'),
        WriterEntityFactory::createCell('Author 16'),
        WriterEntityFactory::createCell('Author 16 Institution'),
        WriterEntityFactory::createCell('Author 17'),
        WriterEntityFactory::createCell('Author 17 Institution')
    ];


    $singleRow = WriterEntityFactory::createRow($cells, $style_head);
    $writer->addRow($singleRow);


    $maxAuthors=17;
    $teste = array();


    foreach ($data as $paper) {

        $teste= $paper->getAuthors();

        $row = [
            $paper->getId(),
            $paper->getTitle(),
            $paper->getType(),
            //$teste[0]->getName(),
            //$teste[0]->getInstitution(),
            // $teste[1]->getName(),
            // $teste[1]->getInstitution(),
            // $teste[2]->getName(),
            // $teste[2]->getInstitution(),
            // $teste[3]->getName(),
            // $teste[3]->getInstitution(),
            // // $teste[4]->getName(),
            // $teste[4]->getInstitution(),
            // $teste[5]->getName(),
            // $teste[5]->getInstitution(),
            // $teste[6]->getName(),
            // $teste[6]->getInstitution(),
            // $teste[7]->getName(),
            // $teste[7]->getInstitution(),
            // $teste[8]->getName(),
            // $teste[8]->getInstitution(),
            // $teste[9]->getName(),
            // $teste[9]->getInstitution(),
            // $teste[10]->getName(),
            // $teste[10]->getInstitution(),
            // $teste[11]->getName(),
            // $teste[11]->getInstitution(),
            // $teste[12]->getName(),
            // $teste[12]->getInstitution(),
            // $teste[13]->getName(),
            // $teste[13]->getInstitution(),
            // $teste[14]->getName(),
            // $teste[14]->getInstitution(),
            // $teste[15]->getName(),
            // $teste[15]->getInstitution(),
            // $teste[16]->getName(),
            // $teste[16]->getInstitution()
           
        ];

        
        $writer->addRow(WriterEntityFactory::createRowFromArray($row, $style_all));
    }

    $writer->close();

    
  }
}


    
  

