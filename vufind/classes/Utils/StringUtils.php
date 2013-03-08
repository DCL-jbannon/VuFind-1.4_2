<?php

class StringUtils
{
	function convert_ascii($string)
	{
		// Replace Single Curly Quotes
		$search[] = chr(226).chr(128).chr(152);
		$replace[] = "'";
		$search[] = chr(226).chr(128).chr(153);
		$replace[] = "'";

		// Replace Smart Double Curly Quotes
		$search[] = chr(226).chr(128).chr(156);
		$replace[] = '"';
		$search[] = chr(226).chr(128).chr(157);
		$replace[] = '"';

		// Replace En Dash
		$search[] = chr(226).chr(128).chr(147);
		$replace[] = '--';

		// Replace Em Dash
		$search[] = chr(226).chr(128).chr(148);
		$replace[] = '---';

		// Replace Bullet
		$search[] = chr(226).chr(128).chr(162);
		$replace[] = '*';

		// Replace Middle Dot
		$search[] = chr(194).chr(183);
		$replace[] = '*';

		// Replace Ellipsis with three consecutive dots
		$search[] = chr(226).chr(128).chr(166);
		$replace[] = '...';

		// Apply Replacements
		$string = str_replace($search, $replace, $string);

		// Remove any non-ASCII Characters
		$string = preg_replace("/[^\x01-\x7F]/","", $string);

		return $string;
	}

}

?>