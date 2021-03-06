--TEST--
Stackdriver Debugger: Snapshot should be able to set sourceRoot
--FILE--
<?php

// set a snapshot for line 7 in loop.php ($sum += $i)
var_dump(stackdriver_debugger_add_snapshot('snapshots/loop.php', 7, [
    'sourceRoot' => realpath(__DIR__ . '/../')
]));

require_once(__DIR__ . '/loop.php');

$sum = loop(10);

echo "Sum is {$sum}\n";

$list = stackdriver_debugger_list_snapshots();

echo "Number of breakpoints: " . count($list) . PHP_EOL;

$breakpoint = $list[0];
echo "Number of stackframes: " . count($breakpoint['stackframes']) . PHP_EOL;

foreach ($breakpoint['stackframes'] as $sf) {
    echo basename($sf['filename']) . ":" . $sf['line'] . PHP_EOL;
}

$loopStackframe = $breakpoint['stackframes'][0];
var_dump($loopStackframe['locals']);
?>
--EXPECTF--
bool(true)
Sum is 45
Number of breakpoints: 1
Number of stackframes: 2
loop.php:7
source_root.php:%d
array(4) {
  [0]=>
  array(2) {
    ["name"]=>
    string(5) "times"
    ["value"]=>
    int(10)
  }
  [1]=>
  array(2) {
    ["name"]=>
    string(3) "sum"
    ["value"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["name"]=>
    string(1) "i"
    ["value"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["name"]=>
    string(1) "j"
    ["value"]=>
    NULL
  }
}
