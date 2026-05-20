<div class="container my-5">
    <div class="mb-4">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border">&larr; العودة لملخص الإدارة</a>
        <h2 class="fw-bold text-dark"><?php echo $page_title; ?></h2>
        <p class="text-muted small">مراجعة المحتوى المنشور ومراقبة المشاريع القائمة وحالات عقودها المالية</p>
    </div>

    <?php if(empty($projects)): ?>
        <div class="alert alert-info text-center p-4 border-0 rounded-3 shadow-sm">
            لا توجد مشاريع منشورة في المنصة حالياً لمراجعتها.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-dark small">
                        <tr>
                            <th class="py-3">رقم المشروع</th>
                            <th class="py-3">العنوان</th>
                            <th class="py-3">الناشر (العميل)</th>
                            <th class="py-3">الميزانية المقترحة</th>
                            <th class="py-3">حالة التعاقد</th>
                            <th class="py-3">الرقابة والإشراف</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach($projects as $project): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?php echo $project['orderId']; ?></td>
                                <td class="text-start"><strong><?php echo $project['title']; ?></strong></td>
                                <td><span class="text-primary fw-bold"><?php echo $project['userName']; ?></span></td>
                                <td class="text-success fw-bold">$<?php echo number_format($project['price'], 2); ?></td>
                                <td>
                                    <?php if(empty($project['escrow_status'])): ?>
                                        <span class="badge bg-warning text-dark px-2 py-1">⏳ مفتوح للعروض</span>
                                    <?php elseif($project['escrow_status'] === 'in_progress'): ?>
                                        <span class="badge bg-primary px-2 py-1">🔒 عقد قائم (قيد التنفيذ)</span>
                                    <?php elseif($project['escrow_status'] === 'completed'): ?>
                                        <span class="badge bg-success px-2 py-1">✓ مكتمل ومسلم</span>
                                    <?php elseif($project['escrow_status'] === 'cancelled'): ?>
                                        <span class="badge bg-secondary px-2 py-1">❌ ملغى بالتراضي</span>
                                    <?php elseif($project['escrow_status'] === 'disputed'): ?>
                                        <span class="badge bg-danger px-2 py-1">🚨 متنازع عليه</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- زر الحذف الفوري للمشاريع غير اللائقة أو المخالفة -->
                                    <form action="<?php echo URLROOT; ?>/admin/delete_project/<?php echo $project['orderId']; ?>" method="POST" onsubmit="return confirm('تحذير: هل أنت متأكد من حذف هذا المشروع نهائياً لمخالفته شروط المحتوى؟ سيتم تدمير كافة السجلات المرتبطة به.');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold xsmall" style="font-size: 0.75rem;">🗑️ حذف كمحتوى مخالف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>