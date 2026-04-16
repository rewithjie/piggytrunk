<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Raiser;
use App\Models\PigType;
use App\Models\Batch;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\RetailProduct;
use App\Models\BatchLifecycleStage;
use App\Models\InventoryCategory;

class DatabaseReportController extends Controller
{
    public function show()
    {
        $users = User::all();
        $raisers = Raiser::all();
        $pigTypes = PigType::all();
        $batches = Batch::with('raiser', 'pigType')->get();
        $investments = Investment::with('batch')->get();
        $investors = Investor::all();
        $retailProducts = RetailProduct::all();
        $lifecycleStages = BatchLifecycleStage::all();
        $inventoryCategories = InventoryCategory::all();

        return view('database-report', [
            'users' => $users,
            'raisers' => $raisers,
            'pigTypes' => $pigTypes,
            'batches' => $batches,
            'investments' => $investments,
            'investors' => $investors,
            'retailProducts' => $retailProducts,
            'lifecycleStages' => $lifecycleStages,
            'inventoryCategories' => $inventoryCategories,
        ]);
    }
}
