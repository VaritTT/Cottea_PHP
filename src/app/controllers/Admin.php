<?php
class Admin {
    use Controller;

    public function index() {
        $this->checkAdminAccess();
        $this->view('admin/dashboard', true);
    }
    public function order_all() {
        $this->checkAdminAccess();
        $this->view('admin/order_all', true);
    }
    public function order_cancel() {
        $this->checkAdminAccess();
        $this->view('admin/order_cancel', true);
    }
    public function order_delivered() {
        $this->checkAdminAccess();
        $this->view('admin/order_delivered', true);
    }
    public function order_detail() {
        $this->checkAdminAccess();
        $this->view('admin/order_detail', false);
    }
    public function order_paid() {
        $this->checkAdminAccess();
        $this->view('admin/order_paid', true);
    }
    public function order_shipping() {
        $this->checkAdminAccess();
        $this->view('admin/order_shipping', true);
    }
    public function order_unpaid() {
        $this->checkAdminAccess();
        $this->view('admin/order_unpaid', true);
    }
    public function product_edit() {
        $this->checkAdminAccess();
        $this->view('admin/product_edit', false);
    }
    public function product_lot() {
        $this->checkAdminAccess();
        $this->view('admin/product_lot', true);
    }
    public function product() {
        $this->checkAdminAccess();
        $this->view('admin/product', true);
    }
    public function report_profit_product() {
        $this->checkAdminAccess();
        $this->view('admin/report_profit_product', false);
    }
    public function report_sale_order() {
        $this->checkAdminAccess();
        $this->view('admin/report_sale_order', false);
    }
    public function report_sale_product() {
        $this->checkAdminAccess();
        $this->view('admin/report_sale_product', false);
    }
    public function report_stock_product() {
        $this->checkAdminAccess();
        $this->view('admin/report_stock_product', false);
    }
    public function user_edit() {
        $this->checkAdminAccess();
        $this->view('admin/user_edit', false);
    }
    public function user() {
        $this->checkAdminAccess();
        $this->view('admin/user', true);
    }
    public function invoice() {
        $this->checkAdminAccess();
        $this->view('admin/invoice', false);
    }
}
