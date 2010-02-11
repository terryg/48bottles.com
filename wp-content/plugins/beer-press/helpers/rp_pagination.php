<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Pagination.php - helper file for creating paged links.
 *
 * @package Recipe Press
 * @subpackage helpers
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class rp_Pagination {

    var $page;
    var $paged;
    var $limit;
    var $offset;
    var $foundRows;
    var $pages;
    var $start;
    var $end;
    var $showPageLinks = 7;

    /* Style settings */
    var $active_class = 'rp_active_page_link';
    var $inactive_class = 'rp_inactive_page_link';

    public function __construct($options = array() ) {
        extract ($options);

        if (!$paged) {
            $paged = 1;
        }

        $this->active_class === $active_class;
        $this->inactive_class === $inactive_class;
        $this->page = $page;
        $this->paged = $paged;
        $this->limit = $limit;
        $this->offset = $paged * $limit - $limit;
        $this->start = $this->offset + 1;
        $this->end = min($this->offset + $this->limit, $this->foundRows);
    }

    public function set_found_rows($rows) {
        $this->foundRows = $rows;
        $this->pages = ceil($this->foundRows / $this->limit);
        $this->start = $this->offset + 1;
        $this->end = min($this->offset + $this->limit, $this->foundRows);
    }

    private function get_page_number($number, $text = NULL) {
        if (!$text) {
            $text = $number;
        }

        if ($number == $this->paged) {
            $output.= '<span class="' . $this->inactive_class . '">' . $number . '</span>';
        } else {
            $output = '<a href="' . $this->page . '&show_page=' . $number . '&limit=' . $this->limit . '" class="' . $this->active_class . '">' . $text . '</a>';;
        }

        return $output;
    }

    /**
     * Builds the pagination display.
     *
     * @return <string>
     */
    public function get_render() {

        if ($this->foundRows > 1) {
            $output = '<span class="displaying-num">' . sprintf(__('Displaying %1$d-%2$d of %3$d'), $this->start, $this->end, $this->foundRows) . '</span>&nbsp;&nbsp;';
        }

        if ($this->foundRows >= $this->limit) {
            if ($this->pages <= $this->showPageLinks) {
                for ($ctr = 1; $ctr <= $this->pages; ++$ctr) {
                    $output.= $this->get_page_number($ctr);
                }
            } else {
                $pageStart = min($this->paged, $this->pages-$this->showPageLinks);
                $pageEnd = min($pageStart + $this->showPageLinks, $this->pages);

                if ($this->paged > 1) {
                    $output.= $this->get_page_number( max($pageStart - $this->showPageLinks, 1), '&laquo;');
                }

                for ($ctr = $pageStart; $ctr < $pageEnd; ++$ctr) {
                    $output.= $this->get_page_number($ctr);
                }

                if ($pageEnd < $this->pages) {
                    $output.= $this->get_page_number( min($pageEnd, $this->pages - $this->showPageLinks), '&raquo;');
                }

            }
        }

        return $output;
    }

    /**
     *  Prints the pagination display.
     */
    public function render() {
        print $this->get_render();
    }
}