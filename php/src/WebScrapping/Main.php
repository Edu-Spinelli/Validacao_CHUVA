<?php

require __DIR__ .'/../../vendor/autoload.php';

use \Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use \Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;


$writer = WriterEntityFactory::createXLSXWriter();

$writer->openToFile('teste.xlsx');



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





$conteudo = file_get_contents(__DIR__ . '/../../assets/origin.html');
print_r($conteudo);
$conteudo_utf8 = mb_convert_encoding($conteudo, 'UTF-8', mb_detect_encoding(__DIR__ . '/../../assets/origin.html', 'UTF-8, ISO-8859-1, ISO-8859-15', true));
libxml_use_internal_errors(true); // Ignora erros de HTML mal formado

$html_chuva = new DOMDocument();

if ($html_chuva->loadHTML($conteudo_utf8) === false) {
    echo "Erro ao carregar o HTML.";
} else {
    echo "HTML carregado com sucesso.";
}


$html_chuva->saveHTML();
libxml_clear_errors();

$titulos = array(); //feito
$id = array(); //feito
$nome_inst = array(); //feito
$autores = array(); //feito
$tipo = array(); //feito


$divs = $html_chuva->getElementsByTagName('div');
foreach ($divs as $div) {
    if ($div->getAttribute('class') === 'col-sm-12 col-md-8 col-lg-8 col-md-pull-4 col-lg-pull-4') {
        // Aqui você pode manipular os elementos dentro da div conforme necessário
        $div_webscrap = $div;
        break;
    }
  }

$html_div_webscrap = $html_chuva->saveHTML($div_webscrap); // Até aqui funfando



$hyperlinks = new DOMDocument('1.0', 'utf-8');
@$hyperlinks->loadHTML(mb_encode_numericentity($html_div_webscrap, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'));


// Pega os titulos e armazena no array $titulos
foreach ($hyperlinks->getElementsByTagName('h4') as $link) { 
  array_push($titulos, $link->textContent); 
  mb_convert_encoding($titulos, 'UTF-8');
}

//pega os ids e armazena no array $id
 // Inicializa o vetor para armazenar os autores

foreach ($hyperlinks->getElementsByTagName('div') as $div) {
    // Verifica se a classe da div é 'authors'

    
    if ($div->getAttribute('class') === 'authors') {
        // Redefine o vetor de autores para o novo conjunto de autores
        $autores_artigo = array(); // Inicializa o vetor para os autores deste artigo
        
        // Itera sobre os spans dentro da div 'authors'
        $spans = $div->getElementsByTagName('span');
        foreach ($spans as $span) {
            // Obtém o título e o valor do nó do span
            $instituicao = $span->getAttribute('title');
            mb_convert_encoding($instituicao, 'UTF-8');
            $nome_autor = $span->nodeValue;
            mb_convert_encoding($nome_autor, 'UTF-8');
            
            // Concatena o título e o nome do autor em uma única string
            $info_autor = $nome_autor . ',' . $instituicao;
            
            // Adiciona as informações do autor ao vetor de autores deste artigo
            $autores_artigo[] = $info_autor;
        }
        
        // Adiciona o vetor de autores deste artigo ao vetor de autores geral
        $autores[] = $autores_artigo;
    }

    if ($div->getAttribute('class') === 'tags mr-sm')
    {
        array_push($tipo, $div->textContent);    
    }

    if ($div->getAttribute('class') === 'volume-info')
    {
        array_push($id, $div->textContent);
    }

   
}

$authorName = array(array());
for ($i = 0; $i < 62; $i++) {
    // Extrai o nome do autor e a instituição do array de autores
    for ($j = 0; $j < 17; $j++) {
        $authorInfo = explode(';', $autores[$i][$j]);
        $authorName[$i][$j] = $authorInfo[0];
        
        $authorInstitution[$i][$j] = $authorInfo[1];
        // Extrair uma parte da string começando do segundo caractere
        $authorInstitution[$i][$j] = substr($authorInstitution[$i][$j], 1);

    }

    

    // Cria uma nova linha com as informações do autor
    $rowData = [
        $id[$i], // Adiciona ID
        $titulos[$i], // Adiciona Título
        $tipo[$i], // Adiciona Tipo
        $authorName[$i][0], // Adiciona Nome do Autor
        $authorInstitution[$i][0], // Adiciona Instituição do Autor
        $authorName[$i][1], // Adiciona Nome do Autor
        $authorInstitution[$i][1], // Adiciona Instituição do Autor
        $authorName[$i][2], // Adiciona Nome do Autor
        $authorInstitution[$i][2], // Adiciona Instituição do Autor
        $authorName[$i][3], // Adiciona Nome do Autor
        $authorInstitution[$i][3],// Adiciona Instituição do Autor
        $authorName[$i][4], // Adiciona Nome do Autor
        $authorInstitution[$i][4], // Adiciona Instituição do Autor
        $authorName[$i][5], // Adiciona Nome do Autor
        $authorInstitution[$i][5], // Adiciona Instituição do Autor
        $authorName[$i][6], // Adiciona Nome do Autor
        $authorInstitution[$i][6], // Adiciona Instituição do Autor
        $authorName[$i][7], // Adiciona Nome do Autor
        $authorInstitution[$i][7], // Adiciona Instituição do Autor
        $authorName[$i][8], // Adiciona Nome do Autor
        $authorInstitution[$i][8], // Adiciona Instituição do Autor
        $authorName[$i][9], // Adiciona Nome do Autor
        $authorInstitution[$i][9], // Adiciona Instituição do Autor
        $authorName[$i][10], // Adiciona Nome do Autor
        $authorInstitution[$i][10], // Adiciona Instituição do Autor
        $authorName[$i][11], // Adiciona Nome do Autor
        $authorInstitution[$i][11], // Adiciona Instituição do Autor
        $authorName[$i][12], // Adiciona Nome do Autor
        $authorInstitution[$i][12], // Adiciona Instituição do Autor
        $authorName[$i][13], // Adiciona Nome do Autor
        $authorInstitution[$i][13], // Adiciona Instituição do Autor
        $authorName[$i][14], // Adiciona Nome do Autor
        $authorInstitution[$i][14], // Adiciona Instituição do Autor
        $authorName[$i][15], // Adiciona Nome do Autor
        $authorInstitution[$i][15], // Adiciona Instituição do Autor
        $authorName[$i][16], // Adiciona Nome do Autor
        $authorInstitution[$i][16] // Adiciona Instituição do Autor
    ];

    // Adiciona a linha à planilha
    $writer->addRow(WriterEntityFactory::createRowFromArray($rowData, $style_all));
}


$writer->close();




//print_r($titulos);
//print_r($tipo);
//print_r($id);


// foreach ($artigos as $art) {
//   print_r($art->nodeValue);
//   print_r('<br>');
// }

// Imprime o HTML
//print_r($html_string);
//print_r($html_div_webscrap);