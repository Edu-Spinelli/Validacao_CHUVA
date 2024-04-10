<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use DOMDocument;
use DOMXPath;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper
{
  public function scrap(DOMDocument $html_chuvaa): array
  {
    $div_webscrapi = new DOMDocument;
    $titulos = array(); //feito
    $id = array(); //feito
    $autores = array(); //feito
    $tipo = array(); //feitocd
    
    $divs = $html_chuvaa->getElementsByTagName('div');

    if ($divs->length === 0) {
      return [];
    }

    
    foreach ($divs as $div) {
      if ($div->getAttribute('class') === 'col-sm-12 col-md-8 col-lg-8 col-md-pull-4 col-lg-pull-4') {
        // Aqui você pode manipular os elementos dentro da div conforme necessário
        $div_webscrapi = $div;
        break;
      } 
    }

    $html_div_webscrap = $html_chuvaa->saveHTML($div_webscrapi); // Até aqui funfando
    $hyperlinks = new DOMDocument('1.0', 'utf-8');
    @$hyperlinks->loadHTML(mb_encode_numericentity($html_div_webscrap, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'));

    foreach ($hyperlinks->getElementsByTagName('h4') as $link) {
      array_push($titulos, $link->textContent);
      mb_convert_encoding($titulos, 'UTF-8');
    }
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

      if ($div->getAttribute('class') === 'tags mr-sm') {
        array_push($tipo, $div->textContent);
      }

      if ($div->getAttribute('class') === 'volume-info') {
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


     
        

        $papers[] = new Paper(
          $id[$i],
          $titulos[$i],
          $tipo[$i],
          [
            new Person($authorName[$i][0], $authorInstitution[$i][0]),
            new Person($authorName[$i][1], $authorInstitution[$i][1]),
            new Person($authorName[$i][2], $authorInstitution[$i][2]),
            new Person($authorName[$i][3], $authorInstitution[$i][3]),
            new Person($authorName[$i][4], $authorInstitution[$i][4]),
            new Person($authorName[$i][5], $authorInstitution[$i][5]),
            new Person($authorName[$i][6], $authorInstitution[$i][6]),
            new Person($authorName[$i][7], $authorInstitution[$i][7]),
            new Person($authorName[$i][8], $authorInstitution[$i][8]),
            new Person($authorName[$i][9], $authorInstitution[$i][9]),
            new Person($authorName[$i][10], $authorInstitution[$i][10]),
            new Person($authorName[$i][11], $authorInstitution[$i][11]),
            new Person($authorName[$i][12], $authorInstitution[$i][12]),
            new Person($authorName[$i][13], $authorInstitution[$i][13]),
            new Person($authorName[$i][14], $authorInstitution[$i][14]),
            new Person($authorName[$i][15], $authorInstitution[$i][15]),
            new Person($authorName[$i][16], $authorInstitution[$i][16])
          ]
        );
      
    }

    return $papers;
  }
}
