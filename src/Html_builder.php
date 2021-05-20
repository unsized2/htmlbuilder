<?php namespace unsized\htmlbuilder;

//require svg_templates.php

/***Componentisation of Html pages
/***Pages are designed as components and this class loads and compiles the html

// It is a class to speed up development by preventing spagetti.
// Not loaded in production as production uses cached html

//components are grouped 'below the fold (belowTF)' and 'above the fold (aboveTF)'
//class reshapes the order of the html for loading speed.

/***Tools for building high performance production html.
Tools to clean & minify the html and output a cached production file for every page.
The cached production page has placeholders for dynamic content. ***/

/***SVG icons to be made square and scale to 24px x 24px  ****/

class Html_builder
{
public $svg_symbols=array();

function __construct ($svg_dir='' )
{
$this->svg_dir=$svg_dir;
$this->svg_symbols=array();
$this->belowTF();
}

//function to avoid duplication. SVG can be loaded only once. Register is a list of those already loaded.
//Echo warnings of svg loading svg of the same name [duplicate]

function svgSymbols( $svg_array=array(), $group='below_TF', $svg_dir = '')
{
//creates an array of svg and associated files grouped by priority _gp
//no duplicate checking [just overwrites].
//advise to check and remove below_TF if isset in aboveTF

if (empty($svg_dir)){
    $svg_dir=$this->svg_dir;
    }

foreach ($svg_array as $k => $svg_name)
{
  $this->svg_symbols[$group][$svg_name]=$svg_dir.$svg_name.'.svg';
    }
//print_r ($this->svg_symbols);
}

//https://css-tricks.com/svg-symbol-good-choice-icons/
//all svgs need ot be wrapped in symbol with viewport set
//markup for displaying the icon <svg class="icon"><use xlink:href="#beaker" /></svg>
function outputSvgSymbols($group='')
{
if (empty($group)){
  $group=$this->getFold();
  }

if (isset ( $this->svg_symbols[$group] )){

  ob_start();
  //<svg style="position: absolute; width: 0; height: 0; overflow: hidden" version="1.1" xmlns="http://www.w3.org/2000/svg">
  ?>
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <?php
    foreach ($this->svg_symbols[$group] AS $svg => $svg_file)
    {?>
      <?= file_get_contents($svg_file);
    }?>
  </svg>
  <?php
  return ob_get_clean();
  }//end if
} //end fn


function aboveTF(){
  $this->fold = 'aboveTF';
}

function belowTF(){
  $this->fold = 'belowTF';
}


function getFold(){
  return $this->fold;
}


}//end class
