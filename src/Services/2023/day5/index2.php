<?php

declare(strict_types=1);

include_once(__DIR__ . '/src/autoloader.php');

use AoC\InputConverter;
use AoC\SeedManager;

$inputs = [
    #'Test' => file_get_contents('testinput'),
    #'Real' => file_get_contents('input'),
    'Testtest' => file_get_contents('testtestinput')
];

foreach($inputs as $key => $input)
{
    $inputConverter = new InputConverter($input);
    $seedManager = new SeedManager($key, ...$inputConverter->getSeedManagerInput());
}

?>

<pre>
    <?php foreach($inputs as $key => $input): ?>
        <?php
            $inputConverter = new InputConverter($input);
            $seedManager = new SeedManager($key, ...$inputConverter->getSeedManagerInput());
        ?>
        <?php
            print($key.' Input:<br>');
            print('2.⭐ Answer: '.$seedManager->getLowestLocationNumber());
        ?>
    <?php endforeach ?>
</pre>
