<?php
require './string_parser.php';
function check($a, $b, $message = '') {
  if ($a != $b) {
    echo "Expected '".print_r($a, true)."', Got '".print_r($b, true)."' in $message\n";
  } else {
    echo "OK: $message\n";
  }
}

function parse($str) {
  return (new StringParser($str))->parse();
}

check(array(), parse(''), 'empty!'); // not sure about this one...
check(array('abc'), parse('abc'), 'basic parse');
check(array('abc', 'def'), parse('abc def'), 'spaced parse');
check(array('abc', 'def'), parse("abc\tdef"), 'tab-spaced parse');
check(array('abc', 'def'), parse('abc     def'), 'multi-spaced parse');
check(array('abc def'), parse("'abc def'"), 'single-quoted parses');
check(array('abc def'), parse('"abc def"'), 'double-quoted parses');
check(array('abc', ''), parse('abc ""'), 'empty quoted');
check(array('abcdefghi'), parse("abc'def'ghi"), 'mixed single-quoted parse');
check(array('abcdefghi'), parse('abc"def"ghi'), 'mixed double-quoted parse');
check(array("abc'def"), parse("abc\'def"), 'escaped single-quoted parse');
check(array('abc"def'), parse('abc\"def'), 'escaped double-quoted parse');
check(array('abc\\def'), parse('abc\\\\def'), 'escaped escape parse');
check(array('abc def'), parse('abc\ def'), 'escaped space parse');
