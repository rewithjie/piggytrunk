<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            // Create vw_batch_summary view
            DB::statement('
                CREATE OR REPLACE VIEW vw_batch_summary AS
                SELECT
                    b.id,
                    b.code,
                    r.name as raiser_name,
                    pt.name as pig_type,
                    b.initial_quantity,
                    b.current_quantity,
                    b.start_date,
                    b.end_date,
                    b.status,
                    b.total_investment,
                    b.expected_profit,
                    COUNT(DISTINCT bsh.id) as current_stage_count,
                    MAX(bsh.updated_at) as last_stage_update
                FROM batches b
                LEFT JOIN raisers r ON b.raiser_id = r.id
                LEFT JOIN pig_types pt ON b.pig_type_id = pt.id
                LEFT JOIN batch_stage_history bsh ON b.id = bsh.batch_id
                GROUP BY b.id, b.code, r.name, pt.name, b.initial_quantity, b.current_quantity, b.start_date, b.end_date, b.status, b.total_investment, b.expected_profit
            ');
        } catch (\Exception $e) {
            // View might already exist
        }

        try {
            // Create vw_dashboard_summary view
            DB::statement('
                CREATE OR REPLACE VIEW vw_dashboard_summary AS
                SELECT
                    (SELECT COUNT(*) FROM raisers WHERE status = "active") as active_raisers,
                    (SELECT COUNT(*) FROM batches WHERE status = "active") as active_batches,
                    (SELECT SUM(total_investment) FROM investments) as total_investments,
                    (SELECT COUNT(*) FROM investors WHERE status = "active") as active_investors,
                    (SELECT COUNT(*) FROM retail_products WHERE status = "active") as active_products,
                    (SELECT SUM(quantity_in_stock) FROM retail_products) as total_inventory_items
            ');
        } catch (\Exception $e) {
            // View might already exist
        }

        try {
            // Create vw_investment_summary view
            DB::statement('
                CREATE OR REPLACE VIEW vw_investment_summary AS
                SELECT
                    i.id,
                    i.code,
                    b.code as batch_code,
                    r.name as raiser_name,
                    pt.name as pig_type,
                    i.total_amount,
                    i.current_value,
                    i.expected_profit,
                    i.actual_profit,
                    i.investment_date,
                    i.expected_return_date,
                    i.actual_return_date,
                    i.status,
                    i.roi_percentage,
                    COUNT(DISTINCT ii.investor_id) as investor_count,
                    GROUP_CONCAT(inv.name SEPARATOR ", ") as investor_names
                FROM investments i
                LEFT JOIN batches b ON i.batch_id = b.id
                LEFT JOIN raisers r ON b.raiser_id = r.id
                LEFT JOIN pig_types pt ON b.pig_type_id = pt.id
                LEFT JOIN investment_investors ii ON i.id = ii.investment_id
                LEFT JOIN investors inv ON ii.investor_id = inv.id
                GROUP BY i.id, i.code, b.code, r.name, pt.name, i.total_amount, i.current_value, i.expected_profit, i.actual_profit, i.investment_date, i.expected_return_date, i.actual_return_date, i.status, i.roi_percentage
            ');
        } catch (\Exception $e) {
            // View might already exist
        }

        try {
            // Create vw_inventory_low_stock view
            DB::statement('
                CREATE OR REPLACE VIEW vw_inventory_low_stock AS
                SELECT
                    rp.id,
                    rp.code,
                    rp.name,
                    rp.quantity_in_stock,
                    rp.reorder_level,
                    ic.name as category_name,
                    (rp.reorder_level - rp.quantity_in_stock) as shortage_qty,
                    CASE WHEN rp.quantity_in_stock <= 0 THEN "Out of Stock"
                         WHEN rp.quantity_in_stock <= rp.reorder_level THEN "Low Stock"
                         ELSE "Available" END as stock_status
                FROM retail_products rp
                LEFT JOIN inventory_categories ic ON rp.id = ic.id
                WHERE rp.quantity_in_stock <= rp.reorder_level OR rp.quantity_in_stock <= 0
            ');
        } catch (\Exception $e) {
            // View might already exist
        }

        try {
            // Create vw_sales_summary view
            DB::statement('
                CREATE OR REPLACE VIEW vw_sales_summary AS
                SELECT
                    DATE(rt.created_at) as sale_date,
                    COUNT(DISTINCT rt.id) as transaction_count,
                    COUNT(DISTINCT rtd.retail_product_id) as items_sold,
                    SUM(rtd.quantity) as total_qty,
                    SUM(rt.total_amount) as total_sales,
                    SUM(rt.discount_amount) as total_discount,
                    SUM(rt.net_amount) as net_sales,
                    (SUM(rtd.quantity * (rtd.unit_price - rp.cost_price))) as gross_profit
                FROM retail_transactions rt
                LEFT JOIN retail_transaction_details rtd ON rt.id = rtd.retail_transaction_id
                LEFT JOIN retail_products rp ON rtd.retail_product_id = rp.id
                WHERE rt.transaction_type = "sale"
                GROUP BY DATE(rt.created_at)
            ');
        } catch (\Exception $e) {
            // View might already exist
        }

        try {
            // Create vw_investment_allocation_summary view
            DB::statement('
                CREATE OR REPLACE VIEW vw_investment_allocation_summary AS
                SELECT
                    inv.id,
                    inv.name,
                    SUM(ii.amount_invested) as total_invested,
                    SUM(ii.amount_returned) as total_returned,
                    COUNT(DISTINCT ii.investment_id) as investment_count,
                    (SUM(ii.amount_invested) - SUM(ii.amount_returned)) as outstanding_balance
                FROM investors inv
                LEFT JOIN investment_investors ii ON inv.id = ii.investor_id
                GROUP BY inv.id, inv.name
            ');
        } catch (\Exception $e) {
            // View might already exist
        }
    }

    public function down(): void
    {
        try {
            DB::statement('DROP VIEW IF EXISTS vw_batch_summary');
        } catch (\Exception $e) {
            // View might not exist
        }
        
        try {
            DB::statement('DROP VIEW IF EXISTS vw_dashboard_summary');
        } catch (\Exception $e) {
            // View might not exist
        }
        
        try {
            DB::statement('DROP VIEW IF EXISTS vw_investment_summary');
        } catch (\Exception $e) {
            // View might not exist
        }
        
        try {
            DB::statement('DROP VIEW IF EXISTS vw_inventory_low_stock');
        } catch (\Exception $e) {
            // View might not exist
        }
        
        try {
            DB::statement('DROP VIEW IF EXISTS vw_sales_summary');
        } catch (\Exception $e) {
            // View might not exist
        }
        
        try {
            DB::statement('DROP VIEW IF EXISTS vw_investment_allocation_summary');
        } catch (\Exception $e) {
            // View might not exist
        }
    }
};

