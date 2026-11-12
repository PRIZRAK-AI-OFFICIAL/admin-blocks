<?php

$root = dirname(__DIR__);
$failures = [];

$expect = static function (bool $condition, string $message) use (&$failures): void {
    if (!$condition) {
        $failures[] = $message;
    }
};

$routes = file_get_contents($root . '/src/routes/admin_blocks.php');
$blockView = file_get_contents($root . '/src/resources/views/blocks/list.blade.php');
$positionView = file_get_contents($root . '/src/resources/views/positions/index.blade.php');

$expect(strpos($routes, 'Route::delete("/positions/delete') !== false, 'position deletion must use DELETE');
$expect(strpos($routes, 'Route::delete("/blocks/delete') !== false, 'block deletion must use DELETE');
$expect(strpos($routes, 'Route::patch("/blocks/toogle') !== false, 'block status change must use PATCH');
$expect(strpos($routes, 'Route::get("/positions/delete') === false, 'position-delete GET route must not exist');
$expect(strpos($routes, 'Route::get("/blocks/delete') === false, 'block-delete GET route must not exist');
$expect(strpos($routes, 'Route::get("/blocks/toogle') === false, 'block-toggle GET route must not exist');
$expect(substr_count($blockView, "@method('DELETE')") >= 1, 'block-delete form must spoof DELETE');
$expect(substr_count($blockView, "@method('PATCH')") >= 1, 'block-toggle form must spoof PATCH');
$expect(substr_count($blockView, '@csrf') >= 2, 'block mutation forms must contain CSRF fields');
$expect(substr_count($positionView, "@method('DELETE')") >= 1, 'position-delete form must spoof DELETE');
$expect(substr_count($positionView, '@csrf') >= 1, 'position-delete form must contain a CSRF field');

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

fwrite(STDOUT, "admin-blocks security contract: ok\n");
