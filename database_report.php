<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\{User, Raiser, PigType, Batch, BatchLifecycleStage, Investment, Investor, RetailProduct, InventoryCategory};

echo "\n═══════════════════════════════════════════════════════════════════════════════\n";
echo "                    PIGGYTRUNK DATABASE REPORT\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n\n";

echo "1️⃣  USERS\n";
echo str_repeat("─", 79) . "\n";
$users = User::all();
foreach ($users as $u) {
    echo "  ID: {$u->id} | Name: {$u->name} | Email: {$u->email}\n";
}
echo "  Total: {$users->count()}\n\n";

echo "2️⃣  RAISERS\n";
echo str_repeat("─", 79) . "\n";
$raisers = Raiser::with('pigType')->get();
foreach ($raisers as $r) {
    $type = $r->pigType?->name ?? 'N/A';
    echo "  ID: {$r->id} | Name: {$r->name} | Code: {$r->code} | Type: {$type} | Status: {$r->status}\n";
}
echo "  Total: {$raisers->count()}\n\n";

echo "3️⃣  PIG TYPES\n";
echo str_repeat("─", 79) . "\n";
$types = PigType::all();
foreach ($types as $t) {
    echo "  ID: {$t->id} | Type: {$t->name} | Code: {$t->code}\n";
}
echo "  Total: {$types->count()}\n\n";

echo "4️⃣  BATCHES\n";
echo str_repeat("─", 79) . "\n";
$batches = Batch::with('raiser', 'pigType')->get();
foreach ($batches as $b) {
    $raiser = $b->raiser?->name ?? 'N/A';
    $pigType = $b->pigType?->name ?? 'N/A';
    $investment = number_format($b->total_investment, 2);
    echo "  ID: {$b->id} | Code: {$b->code} | Raiser: {$raiser} | Type: {$pigType}\n";
    echo "    Qty: {$b->current_quantity} | Status: {$b->status} | Investment: ₱{$investment}\n";
}
echo "  Total: {$batches->count()}\n\n";

echo "5️⃣  INVESTMENTS\n";
echo str_repeat("─", 79) . "\n";
$investments = Investment::with('batch')->get();
foreach ($investments as $i) {
    $batch = $i->batch?->code ?? 'N/A';
    $amount = number_format($i->total_amount, 2);
    $profit = number_format($i->expected_profit, 2);
    echo "  ID: {$i->id} | Code: {$i->code} | Batch: {$batch}\n";
    echo "    Amount: ₱{$amount} | Status: {$i->status} | ROI: {$i->roi_percentage}% | Profit: ₱{$profit}\n";
}
echo "  Total: {$investments->count()}\n\n";

echo "6️⃣  INVESTORS\n";
echo str_repeat("─", 79) . "\n";
$investors = Investor::all();
if ($investors->count() == 0) {
    echo "  ✗ No investors found\n";
} else {
    foreach ($investors as $inv) {
        $invested = number_format($inv->total_invested, 2);
        echo "  ID: {$inv->id} | Name: {$inv->name} | Type: {$inv->type}\n";
        echo "    Status: {$inv->status} | Total Invested: ₱{$invested}\n";
    }
}
echo "  Total: {$investors->count()}\n\n";

echo "7️⃣  RETAIL PRODUCTS\n";
echo str_repeat("─", 79) . "\n";
$products = RetailProduct::all();
if ($products->count() == 0) {
    echo "  ✓ No retail products (cleaned up successfully)\n";
} else {
    foreach ($products as $p) {
        $price = number_format($p->selling_price, 2);
        echo "  ID: {$p->id} | Name: {$p->name} | Code: {$p->code}\n";
        echo "    Price: ₱{$price} | Stock: {$p->quantity_in_stock} | Supplier: {$p->supplier} | Status: {$p->status}\n";
    }
}
echo "  Total: {$products->count()}\n\n";

echo "8️⃣  LIFECYCLE STAGES\n";
echo str_repeat("─", 79) . "\n";
$stages = BatchLifecycleStage::all();
foreach ($stages as $s) {
    echo "  ID: {$s->id} | Seq: {$s->sequence} | Name: {$s->name} | Duration: {$s->duration_days} days\n";
}
echo "  Total: {$stages->count()}\n\n";

echo "9️⃣  INVENTORY CATEGORIES\n";
echo str_repeat("─", 79) . "\n";
$categories = InventoryCategory::all();
foreach ($categories as $c) {
    echo "  ID: {$c->id} | Name: {$c->name} | Code: {$c->code} | Status: {$c->status}\n";
}
echo "  Total: {$categories->count()}\n\n";

echo "═══════════════════════════════════════════════════════════════════════════════\n";
echo "                         SUMMARY STATISTICS\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n";
printf("  %-35s : %6d\n", "Total Users", User::count());
printf("  %-35s : %6d\n", "Total Raisers", Raiser::count());
printf("  %-35s : %6d\n", "Total Batches", Batch::count());
printf("  %-35s : %6d\n", "Total Investments", Investment::count());
printf("  %-35s : %6d\n", "Total Investors", Investor::count());
printf("  %-35s : %6d\n", "Total Retail Products", RetailProduct::count());
printf("  %-35s : %6d\n", "Total Lifecycle Stages", BatchLifecycleStage::count());
printf("  %-35s : %6d\n", "Total Inventory Categories", InventoryCategory::count());
echo "═══════════════════════════════════════════════════════════════════════════════\n\n";
echo "✅ Database report generated successfully!\n\n";
