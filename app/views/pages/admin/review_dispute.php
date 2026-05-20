<div class="container my-5">
    <div class="mb-4">
        <a href="<?php echo URLROOT; ?>/admin/disputes" class="btn btn-light btn-sm mb-3 border">&larr; العودة لجدول النزاعات</a>
        <h2 class="fw-bold text-dark"><?php echo $page_title; ?></h2>
        <p class="text-muted small">أنت الآن في منطقة الرقابة العليا، يمكنك فحص المستندات والمحادثة للحكم بالعدل بين الطرفين.</p>
    </div>

    <!-- بطاقة تفاصيل النزاع والخصوم -->
    <div class="card shadow-sm border-0 p-4 bg-white rounded-3 mb-4 border-top border-danger border-4">
        <div class="card-body">
            <h4 class="fw-bold text-dark mb-3">تفاصيل القضية الحالية:</h4>
            <div class="row g-3 small">
                <div class="col-12 col-md-6">
                    <p class="mb-1 text-muted">المشروع المتنازع عليه:</p>
                    <h5 class="fw-bold text-secondary"><?php echo $dispute['orderTitle']; ?></h5>
                </div>
                <div class="col-12 col-md-3">
                    <p class="mb-1 text-muted">العميل (صاحب العمل):</p>
                    <strong class="text-primary"><?php echo $dispute['clientName']; ?></strong>
                </div>
                <div class="col-12 col-md-3">
                    <p class="mb-1 text-muted">المستقل (منفذ العمل):</p>
                    <strong class="text-primary"><?php echo $dispute['freelancerName']; ?></strong>
                </div>
            </div>
            <hr>
            <div class="bg-light p-3 rounded-3 border">
                <h6 class="fw-bold text-danger mb-2">📌 السبب المكتوب لفتح النزاع:</h6>
                <p class="mb-0 text-secondary" style="white-space: pre-line; line-height: 1.6;"><?php echo $dispute['reason']; ?></p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- قسم قراءة سجل المحادثة بالكامل (ميزة الإبهار البرمجي للأدمن) -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3" style="min-height: 400px;">
                <h4 class="fw-bold text-dark mb-4">💬 سجل التدقيق والمحادثة التاريخي بين الطرفين</h4>
                
                <div class="overflow-auto p-3 bg-light rounded-3 border" style="max-height: 450px; min-height: 300px;">
                    <?php if(empty($messages)): ?>
                        <p class="text-center text-muted small my-5">لم يدر أي تواصل أو رسائل نصية بين الطرفين داخل مساحة العمل.</p>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach($messages as $msg): ?>
                                <div class="p-3 rounded-3 border bg-white shadow-sm" style="max-width: 90%;">
                                    <div class="d-flex justify-content-between align-items-center gap-4 mb-2 border-bottom pb-1">
                                        <small class="fw-bold text-primary">
                                            <?php echo $msg['userName']; ?> 
                                            <span class="badge bg-secondary xsmall opacity-75" style="font-size: 0.65rem;"><?php echo strtoupper($msg['role']); ?></span>
                                        </small>
                                        <span class="xsmall text-muted" style="font-size: 0.7rem;"><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></span>
                                    </div>
                                    <p class="mb-2 small text-dark" style="white-space: pre-line;"><?php echo $msg['message']; ?></p>
                                    
                                    <?php if(!empty($msg['attachment'])): ?>
                                        <div class="mt-2 pt-2 border-top border-light">
                                            <a href="<?php echo URLROOT; ?>/uploads/<?php echo $msg['attachment']; ?>" target="_blank" class="btn btn-sm btn-outline-danger fw-bold xsmall" style="font-size: 0.75rem;">
                                                📎 معاينة مستند PDF المرفوع بواسطة هذا الطرف
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- أزرار حسم القرار المالي الصارم للنزاع المفتوح -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    <h4 class="fw-bold text-dark mb-2">⚖️ اتخاذ القرار القضائي</h4>
                    <p class="text-muted small mb-4">المبلغ المتنازع عليه حالياً في خزنة الضمان الآمنة:</p>
                    <h2 class="fw-bold text-success mb-4">$<?php echo number_format($dispute['price'], 2); ?></h2>

                    <?php if($dispute['status'] === 'open'): ?>
                        <div class="d-flex flex-column gap-2">
                            <!-- القرار 1: إنصاف المستقل وصرف الأموال له -->
                            <form action="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" method="POST" onsubmit="return confirm('قرار نهائي: هل أنت متأكد من صرف كامل المبلغ المحجوز لحساب أرباح المستقل وإغلاق القضية؟');">
                                <input type="hidden" name="decision" value="pay">
                                <button type="submit" class="btn btn-success w-100 fw-bold py-2 small">💰 صرف المستحقات للمستقل</button>
                            </form>

                            <!-- القرار 2: إنصاف العميل وإعادة أمواله له -->
                            <form action="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" method="POST" onsubmit="return confirm('قرار نهائي: هل أنت متأكد من إلغاء العقد وإعادة كامل المبلغ المحجوز لرصيد العميل وإغلاق القضية؟');">
                                <input type="hidden" name="decision" value="refund">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold py-2 small">↩️ إعادة الأموال بالكامل للعميل</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- عرض القرار المتخذ سابقاً وحسمه -->
                        <div class="alert alert-success border-0 mb-0 small" role="alert">
                            <strong>🔒 قضية مغلقة ومحسومة:</strong><br>
                            تم حسم هذا النزاع مسبقاً باتخاذ قرار علوي مالي حاسم وهو: 
                            <span class="fw-bold text-decoration-underline">
                                <?php echo ($dispute['admin_decision'] === 'refund_client') ? 'إعادة الأموال للعميل' : 'صرف المستحقات للمستقل'; ?>
                            </span>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>