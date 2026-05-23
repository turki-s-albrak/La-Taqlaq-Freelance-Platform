<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border shadow-sm">&larr; العودة إلى لوحة التحكم</a>
            <h2 class="fw-bold text-dark mb-1">📁 <?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">مراجعة المحتوى المنشور، مراقبة حركة المشاريع، وحذف الطلبات المخالفة لشروط المنصة.</p>
        </div>
    </div>

    <?php if(empty($projects)): ?>
        <div class="alert alert-info text-center p-5 border-0 rounded-4 shadow-sm">
            <h4 class="fw-bold mb-2">السوق نظيف!</h4>
            <p class="text-muted mb-0">لا توجد مشاريع منشورة في المنصة حالياً لمراجعتها.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 bg-white rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">رقم #</th>
                            <th class="py-3">عنوان المشروع</th>
                            <th class="py-3">الناشر (العميل)</th>
                            <th class="py-3">القيمة المالية</th>
                            <th class="py-3">الحالة والتعاقد</th>
                            <th class="py-3">إجراءات الرقابة</th>
                        </tr>
                    </thead>
                    <tbody class="small border-top-0">
                        <?php foreach($projects as $project): ?>
                            <tr>
                                <td class="fw-bold text-secondary px-4">#<?php echo $project['orderId']; ?></td>
                                
                                <td class="text-start" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <strong><?php echo $project['title']; ?></strong>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-primary fw-bold">👤 <?php echo $project['userName']; ?></span>
                                    </div>
                                </td>
                                
                                <td class="text-success fw-bold">
                                    <?php if(!empty($project['escrowPrice'])): ?>
                                        <span class="fs-6">$<?php echo number_format($project['escrowPrice'], 2); ?></span>
                                        <span class="badge bg-success bg-opacity-10 text-success d-block mt-1 xsmall">سعر العقد</span>
                                    <?php else: ?>
                                        <span class="fs-6">$<?php echo number_format($project['price'], 2); ?></span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary d-block mt-1 xsmall">ميزانية مبدئية</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <?php if(empty($project['escrow_status'])): ?>
                                        <span class="badge bg-warning text-dark bg-gradient px-3 py-2 rounded-pill shadow-sm">⏳ بانتظار العروض</span>
                                    <?php elseif($project['escrow_status'] === 'in_progress'): ?>
                                        <span class="badge bg-primary bg-gradient px-3 py-2 rounded-pill shadow-sm">🚀 قيد التنفيذ</span>
                                    <?php elseif($project['escrow_status'] === 'completed'): ?>
                                        <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill shadow-sm">✅ مكتمل ومسلم</span>
                                    <?php elseif($project['escrow_status'] === 'cancelled'): ?>
                                        <span class="badge bg-secondary bg-gradient px-3 py-2 rounded-pill shadow-sm">❌ ملغى</span>
                                    <?php elseif($project['escrow_status'] === 'disputed'): ?>
                                        <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill shadow-sm">🚨 متنازع عليه</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="d-flex justify-content-center gap-2 align-items-center">
                                        <a href="<?php echo URLROOT; ?>/orders/show/<?php echo $project['orderId']; ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold shadow-sm">👀 معاينة</a>
                                        
                                        <?php 
                                            // منع حذف المشاريع التي بها أموال محجوزة أو نزاع
                                            $isProtected = in_array($project['escrow_status'], ['in_progress', 'disputed']); 
                                        ?>
                                        <?php if($isProtected): ?>
                                            <button class="btn btn-sm btn-light text-muted fw-bold border disabled shadow-sm" disabled title="لا يمكن حذف مشروع يحتوي على أموال محجوزة في الخزنة">🛡️ محمي مالياً</button>
                                        <?php else: ?>
                                            <form action="<?php echo URLROOT; ?>/admin/delete_project/<?php echo $project['orderId']; ?>" method="POST" onsubmit="return confirm('تحذير: هل أنت متأكد من حذف هذا المشروع نهائياً لمخالفته شروط المحتوى؟ سيتم تدمير كافة السجلات المرتبطة به.');" class="m-0">
                                                <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm">🗑️ حذف المخالفة</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>