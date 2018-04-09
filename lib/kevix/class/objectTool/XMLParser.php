<?php
/**
 * Project:     KEVIX: PHP MVC Framework
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc.,
 * please send email to kevixli@users.sourceforge.net
 *
 * @link http://kevix.sourceforge.net
 * @copyright(C) 2006  Kevin
 * @author Kevin Lee (Lee Ka Chun)
 * @version 0.5 beta version
 */

class XMLParser {
	private $xml;
	private $data;

	function XMLParser($xml_file) {
		$this->parse($xml_file);
	}

	function parse($xml_file) {
		$this->data = array();
		$this->xml = xml_parser_create();
		xml_set_object($this->xml, $this);
		xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
		xml_set_character_data_handler($this->xml, 'dataHandler');

		if (!($fp = fopen($xml_file, 'r'))) {
			die('Cannot open XML data file: '.$xml_file);
			return false;
		}

		$bytes_to_parse = 512;

		while ($data = fread($fp, $bytes_to_parse)) {
			$parse = xml_parse($this->xml, $data, feof($fp));

			if (!$parse) {
				die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->xml)), xml_get_current_line_number($this->xml)));
				xml_parser_free($this->xml);
			}
		}
		
		xml_parser_free($this->xml);
		
		return true;
	}

	function startHandler($parser, $name, $attributes) {
		$data['name'] = $name;
		if ($attributes) {
			$data['attributes'] = $attributes;
		}
		$this->data[] = $data;
	}

	function dataHandler($parser, $data) {
		if ($data = trim($data)) {
			$index = count($this->data) - 1;
			$this->data[$index]['content'] .= $data;
		}
	}

	function endHandler($parser, $name) {
		if (count($this->data) > 1) {
			$data = array_pop($this->data);
			$index = count($this->data) - 1;
			$this->data[$index]['child'][] = $data;
		}
	}

	public function getData() {
		return $this->data;
	}
}
?>