<?php
class StringParser {
    private $str;

    private $quoted_by = null;

    private $escaped = false;

    private $handlers = array(
        ' '  => 'Spaced',
        "\t" => 'Spaced',
        '"'  => 'Quoted',
        "'"  => 'Quoted',
        '\\' => 'Escape',
    );

    private $parts = array();
    private $cur = '';

    function __construct($str) {
        $this->str = $str;
    }

    public function parse() {
        for ($i = 0; isset($this->str[$i]); ++$i) {
            $c = $this->str[$i];
            if (isset($this->handlers[$c])) {
                $this->{'parse'.$this->handlers[$c]}($c);
            } else {
                $this->cur .= $c;
                $this->escaped = false;
            }
        }
        $this->doInsert();

        return $this->parts;
    }

    private function doInsert() {
        if (!$this->cur)
            return;

        $this->parts[] = $this->cur;

        $this->cur = '';
        $this->quoted_by = null;
    }

    private function parseSpaced($c) {
        if ($this->quoted_by) {
            $this->cur .= $c;
        } else {
            $this->doInsert();
        }
        $this->escaped = false;
    }

    private function parseQuoted($c) {
        if ($this->escaped) {
            $this->cur .= $c;
            $this->escaped = false;
            return;
        }

        switch ($this->quoted_by) {
            case $c:
                $this->quoted_by = null;
                break;

            case ($c == '"' ? "'" : '"'):
                $this->cur .= $c;
                break;

            case null:
                $this->quoted_by = $c;
                break;
        }
    }

    private function parseEscape() {
        if ($this->escaped) {
            $this->cur .= '\\';
        }
        $this->escaped = !$this->escaped;
    }

}