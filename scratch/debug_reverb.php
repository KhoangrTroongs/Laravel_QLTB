<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Broadcast;

echo '--- BẮT ĐẦU KIỂM TRA ---'.PHP_EOL;

$connection = config('broadcasting.default');
echo 'Broadcast Connection: '.$connection.PHP_EOL;

$config = config("broadcasting.connections.{$connection}");
echo 'Host: '.($config['options']['host'] ?? 'N/A').PHP_EOL;
echo 'Port: '.($config['options']['port'] ?? 'N/A').PHP_EOL;
echo 'Scheme: '.($config['options']['scheme'] ?? 'N/A').PHP_EOL;

try {
    echo 'Đang thử gửi sự kiện trực tiếp...'.PHP_EOL;

    // Sử dụng Broadcaster trực tiếp để bắt lỗi
    $broadcaster = Broadcast::driver();
    $broadcaster->broadcast(['test-channel'], 'TestBroadcast', ['message' => 'Debug message']);

    echo '✅ Gửi lệnh thành công (Kiểm tra terminal Reverb xem có hiện log không)'.PHP_EOL;
} catch (Exception $e) {
    echo '❌ LỖI RỒI: '.$e->getMessage().PHP_EOL;
    echo 'Chi tiết: '.get_class($e).PHP_EOL;
}

echo '--- KẾT THÚC ---'.PHP_EOL;
