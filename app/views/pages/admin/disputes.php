<div class="container my-5">
    <div class="mb-4">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border">&larr; العودة لملخص الإدارة</a>
        <h2 class="fw-bold text-dark"><?php echo $page_title; ?></h2>
        <p class="text-muted small">مراجعة العقود المالية العالقة والتي استدعى أطرافها تدخل الإدارة لحسمها</p>
    </div>

    <?php if(empty($disputes)): ?>
        <div class="alert alert-success text-center p-4 border-0 rounded-3 shadow-sm">
            🎉 لا توجد أي نزاعات مالية مفتوحة حالياً في المنصة، كل شيء يسير على ما يرام!
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-dark small">
                        <tr>
                            <th class="py-3">رقم النزاع</th>
                            <th class="py-3">المشروع</th>
                            <th class="py-3">العميل (صاحب المال)</th>
                            <th class="py-3">المستقل</th>
                            <th class="py-3">المبلغ المحجوز</th>
                            <th class="py-3">حالة النزاع</th>
                            <th class="py-3">القرار والتحكيم</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach($disputes as $dispute): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?php echo $dispute['disputeId']; ?></td>
                                <td class="text-start"><strong><?php echo $dispute['orderTitle']; ?></strong></td>
                                <td><?php echo $dispute['clientName']; ?></td>
                                <td><?php echo $dispute['freelancerName']; ?></td>
                                <td class="text-danger fw-bold">$<?php echo number_format($dispute['price'], 2); ?></td>
                                <td>
                                    <?php if($dispute['status'] === 'open'): ?>
                                        <span class="badge bg-danger px-2 py-1">🚨 مفتوح ومعلق</span>
                                    <?php else: ?>
                                        <span class="badge bg-success px-2 py-1">✓ تم الحسم وإغلاقه</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- زر الدخول لغرفة التحكيم وقراءة الشات لاتخاذ القرار -->
                                    <a href="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" class="btn btn-sm <?php echo ($dispute['status'] === 'open') ? 'btn-primary' : 'btn-outline-secondary'; ?> fw-bold xsmall" style="font-size: 0.75rem;">
                                        <?php echo ($dispute['status'] === 'open') ? '⚖️ دخول وغرفة التحكيم' : '👁️ معاينة القرار القديم'; ?>
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