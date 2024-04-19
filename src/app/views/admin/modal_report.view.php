    <!-- Modal Report1 : รายงานยอดขายสินค้า-แยกตามออเดอร์ -->
    <div class="modal fade" id="salesReport1Modal" tabindex="-1" aria-labelledby="salesReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesReportModalLabel">รายงานยอดขายสินค้า-เรียงตามสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= ROOT ?>/admin/report_sale_order" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">ตั้งแต่วันที่</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">ถึงวันที่</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">แสดงรายงาน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Report2 : รายงานยอดขายสินค้า-แยกตามสินค้า -->
    <div class="modal fade" id="salesReport2Modal" tabindex="-1" aria-labelledby="salesReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesReportModalLabel">รายงานยอดขายสินค้า-เรียงตามสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= ROOT ?>/admin/report_sale_product" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">ตั้งแต่วันที่</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">ถึงวันที่</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">แสดงรายงาน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>