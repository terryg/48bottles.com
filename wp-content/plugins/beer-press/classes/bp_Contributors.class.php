<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Contributors.class.php - Class to dislay contributors
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
 
class bp_Contributors extends bp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-contributors';
    protected $view = 'contributors';
    protected $xmlURL = 'http://wordpress.grandslambert.com/xml/recipe-press/';
    protected $makelink = false;
    protected $showFields;

    public function theList($type = 'contributors') {
        $this->showFields = array('NAME', 'LOCATION' , 'COUNTRY');
        echo '<ul>';

        $xml_parser = xml_parser_create();
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($xml_parser, array($this,"startElement"), array($this, "endElement") );
        xml_set_character_data_handler($xml_parser, array($this, "characterData") );

        if (!($fp = @fopen($this->xmlURL . $type . '.xml', "r"))) {
            /* translators: Message displayed when the contributors list cannot be accessed. */
            _e('There was an error getting the list. Try again later.', 'recipe-press');
            return;
        }

        while ($data = fread($fp, 4096)) {
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            }
        }

        xml_parser_free($xml_parser);
        echo '</ul>';
    }

    function startElement($parser, $name, $attrs) {
        if ($name == 'NAME') {
            echo '<li class="rp-contributor">';
        } elseif ($name == 'ITEM') {
            /* translators: Used on the Contributors list to denote what a person contributed. */
            echo '<br><span class="bp_contributor_notes">' . __('Contributed: ', 'recipe-press');
        }

        if ($name == 'URL') {
            $this->makeLink = true;
        }
    }

    function endElement($parser, $name) {
        if ($name == 'ITEM') {
            echo '</li>';
        }
        elseif ($name == 'ITEM') {
            echo '</span>';
        }
        elseif ( in_array($name, $this->showFields)) {
            echo ', ';
        }
    }

    function characterData($parser, $data) {
        if ($this->makeLink) {
            echo '<a href="http://' . $data . '" target="_blank">' . $data . '</a>';
            $this->makeLink = false;
        } else {
            echo $data;
        }
    }
}
