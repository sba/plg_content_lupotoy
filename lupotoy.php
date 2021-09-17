<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class plgContentLupotoy extends JPlugin
{
    public function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {
	    if (!class_exists( 'LupoModelLupo' )){
		    JLoader::import( 'lupo', JPATH_BASE . '/components/com_lupo/models' );
	    }

		$pattern = '/(\[spiel|spiele|jeu|jeux|gioco|giochi)[\s]{0,4}([0-9]+\.?[0-9]*|\.[0-9]+)(\])/i';
	    preg_match_all($pattern, $article->text, $matches);

		if(count($matches[0])>0) {
			$toy_numbers = $matches[2];
			foreach ($toy_numbers as $toy_number) {
				$model = new LupoModelLupo();
				$toy   = $model->getGamesByNumber($toy_number);
				if ($toy !== false) {
					$replacement   = '<a href="' . $toy[0]['link'] . '"><img src="' . $toy[0]['image'] . '" title="' . $toy[0]['title'] . '" style="max-width: 200px" ></a>';
					$pattern = '/(\[spiel|jeux|gioco)[ ]{0,1}('.$toy_number.')(\])/';
					$article->text = preg_replace($pattern, $replacement, $article->text);
					//$article->text .= '<pre>'. print_r($toy[0],true).'</pre>';
				}
			}
		}
        return true;
    }
}
