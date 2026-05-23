<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border shadow-sm">&larr; العودة إلى لوحة التحكم</a>
            <h2 class="fw-bold text-dark mb-1">⚖️ <?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">مراجعة العقود المالية العالقة والتي استدعى أطرافها تدخل الإدارة لحسمها (محكمة المنصة).</p>
        </div>
        <span class="badge bg-danger bg-gradient p-2 px-4 fw-bold shadow-sm rounded-pill fs-6">التحكيم المالي</span>
    </div>

    <?php if(empty($disputes)): ?>
        <div class="alert alert-success text-center p-5 border-0 rounded-4 shadow-sm">
            <h4 class="fw-bold mb-2">🎉 لا توجد نزاعات!</h4>
            <p class="text-muted mb-0">لا توجد أي نزاعات مالية مفتوحة حالياً في المنصة، كل شيء يسير على ما يرام.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 bg-white rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">رقم #</th>
                            <th class="py-3">المشروع المتنازع عليه</th>
                            <th class="py-3">العميل (صاحب المال)</th>
                            <th class="py-3">المستقل (المنفذ)</th>
                            <th class="py-3">المبلغ المحجوز</th>
                            <th class="py-3">حالة النزاع</th>
                            <th class="py-3">القرار والتحكيم</th>
                        </tr>
                    </thead>
                    <tbody class="small border-top-0">
                        <?php foreach($disputes as $dispute): ?>
                            <tr class="<?php echo ($dispute['status'] === 'open') ? 'bg-danger bg-opacity-10' : ''; ?>">
                                
                                <td class="fw-bold text-secondary px-4">#<?php echo $dispute['disputeId']; ?></td>
                                
                                <td class="text-center" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <strong><?php echo $dispute['orderTitle']; ?></strong>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <span class="text-primary fw-bold">👤 <?php echo $dispute['clientName']; ?></span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <span class="text-info fw-bold">💼 <?php echo $dispute['freelancerName']; ?></span>
                                    </div>
                                </td>
                                
                                <td class="text-danger fw-bold">
                                    <span class="fs-6">$<?php echo number_format($dispute['price'], 2); ?></span>
                                </td>
                                
                                <td>
                                    <?php if($dispute['status'] === 'open'): ?>
                                        <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill shadow-sm">🚨 بانتظار تحكيمك</span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill shadow-sm">✅ تم الحسم</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <a href="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" 
                                       class="btn btn-sm <?php echo ($dispute['status'] === 'open') ? 'btn-danger shadow-sm' : 'btn-outline-secondary'; ?> fw-bold px-3">
                                        <?php echo ($dispute['status'] === 'open') ? '⚖️ دخول غرفة التحكيم' : '👁️ معاينة السجل'; ?>
                                    </a>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>