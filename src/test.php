<?php

require_once 'vendor/autoload.php';

$stripe = new \Stripe\StripeClient();

try {
    $customers = $stripe->customers->all(['limit' => 1]);
    echo "✅ 接続成功！";
} catch (Exception $e) {
    echo "❌ 接続失敗: " . $e->getMessage();
}