<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <a href="<?php echo URLROOT; ?>/admin/disputes" class="btn btn-light btn-sm mb-3 border shadow-sm">&larr; العودة لقائمة النزاعات</a>
            <h2 class="fw-bold text-dark mb-1">⚖️ <?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">أنت الآن في منطقة الرقابة العليا. افحص المستندات والمحادثات للحكم بالعدل بين الطرفين.</p>
        </div>
        <span class="badge bg-danger bg-gradient p-2 px-4 fw-bold shadow-sm rounded-pill fs-6">غرفة التحكيم المغلقة</span>
    </div>

    <div class="card shadow-sm border-0 p-4 bg-white rounded-4 mb-4 border-top border-danger border-4">
        <div class="card-body p-0">
            <h5 class="fw-bold text-dark mb-4">📋 تفاصيل القضية رقم #<?php echo $dispute['disputeId']; ?>:</h5>
            
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <p class="mb-1 text-muted small">المشروع المتنازع عليه:</p>
                        <h6 class="fw-bold text-dark mb-0"><?php echo $dispute['orderTitle']; ?></h6>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 h-100 text-center">
                        <p class="mb-1 text-primary small fw-bold">العميل (صاحب المال)</p>
                        <h6 class="fw-bold text-dark mb-0">👤 <?php echo $dispute['clientName']; ?></h6>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="p-3 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 h-100 text-center">
                        <p class="mb-1 text-info small fw-bold">المستقل (المنفذ)</p>
                        <h6 class="fw-bold text-dark mb-0">💼 <?php echo $dispute['freelancerName']; ?></h6>
                    </div>
                </div>
            </div>
            
            <div class="bg-danger bg-opacity-10 border border-danger border-opacity-25 p-4 rounded-3">
                <h6 class="fw-bold text-danger mb-2">🚨 الشكوى وسبب فتح النزاع:</h6>
                <p class="mb-0 text-dark fw-bold" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">"<?php echo $dispute['reason']; ?>"</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-4" style="min-height: 400px;">
                <h5 class="fw-bold text-dark mb-4">💬 السجل التاريخي للمحادثة والمرفقات</h5>
                
                <div class="overflow-auto p-4 bg-light rounded-3 border" style="max-height: 500px; min-height: 300px;">
                    <?php if(empty($messages)): ?>
                        <div class="text-center text-muted my-5">
                            <div class="fs-1 mb-3">📭</div>
                            <p class="small fw-bold">لم يدر أي تواصل أو رسائل نصية بين الطرفين داخل مساحة العمل.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach($messages as $msg): ?>
                                <?php 
                                    // التمييز المعماري اللوني بناءً على دور المرسل
                                    $isClient = ($msg['role'] === 'client');
                                    $bgClass = $isClient ? 'bg-primary bg-opacity-10 border-primary' : 'bg-info bg-opacity-10 border-info';
                                    $textClass = $isClient ? 'text-primary' : 'text-info';
                                ?>
                                <div class="p-3 rounded-3 border border-start border-4 shadow-sm <?php echo $bgClass; ?>" style="max-width: 90%;">
                                    <div class="d-flex justify-content-between align-items-center gap-4 mb-2 border-bottom border-secondary border-opacity-25 pb-2">
                                        <div class="fw-bold <?php echo $textClass; ?>">
                                            <?php echo ($isClient ? '👤 ' : '💼 ') . $msg['userName']; ?> 
                                            <span class="badge bg-secondary ms-2 xsmall rounded-pill" style="font-size: 0.65rem;"><?php echo ($isClient ? 'عميل' : 'مستقل'); ?></span>
                                        </div>
                                        <span class="xsmall text-muted fw-bold" style="font-size: 0.7rem;"><?php echo date('Y-m-d h:i A', strtotime($msg['created_at'])); ?></span>
                                    </div>
                                    <p class="mb-2 small text-dark fw-bold" style="white-space: pre-line; line-height: 1.6;"><?php echo $msg['message']; ?></p>
                                    
                                    <?php if(!empty($msg['attachment'])): ?>
                                        <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
                                            <a href="<?php echo URLROOT; ?>/uploads/<?php echo $msg['attachment']; ?>" target="_blank" class="btn btn-sm btn-light fw-bold xsmall shadow-sm border" style="font-size: 0.75rem;">
                                                📎 فتح مستند PDF المرفوع
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

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-4 sticky-top border-top border-4 border-dark" style="top: 20px;">
                <div class="card-body text-center p-0">
                    <h5 class="fw-bold text-dark mb-3">مطرقة القاضي ⚖️</h5>
                    <p class="text-muted small mb-3">المبلغ المتنازع عليه والمجمّد حالياً في خزنة الضمان الآمنة:</p>
                    <div class="bg-light p-3 rounded-3 mb-4 border border-dashed border-danger">
                        <h2 class="fw-bold text-danger mb-0">$<?php echo number_format($dispute['price'], 2); ?></h2>
                    </div>

                    <?php if($dispute['status'] === 'open'): ?>
                        <div class="d-flex flex-column gap-3">
                            <form action="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" method="POST" onsubmit="return confirm('قرار نهائي غير قابل للنقض: هل أنت متأكد من صرف كامل المبلغ المحجوز لحساب أرباح المستقل وإغلاق القضية؟');">
                                <input type="hidden" name="decision" value="pay">
                                <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow-sm">💰 صرف المستحقات للمستقل</button>
                            </form>

                            <form action="<?php echo URLROOT; ?>/admin/review_dispute/<?php echo $dispute['disputeId']; ?>" method="POST" onsubmit="return confirm('قرار نهائي غير قابل للنقض: هل أنت متأكد من إلغاء العقد وإعادة كامل المبلغ المحجوز لرصيد العميل وإغلاق القضية؟');">
                                <input type="hidden" name="decision" value="refund">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold py-3 shadow-sm">↩️ إعادة الأموال بالكامل للعميل</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success border-0 mb-0 p-4 shadow-sm rounded-3" role="alert">
                            <div class="fs-1 mb-2">✅</div>
                            <strong class="d-block mb-2">قضية مغلقة ومحسومة</strong>
                            <span class="small text-dark">
                                تم حسم هذا النزاع بإصدار قرار مالي نهائي يقضي بـ:<br>
                                <strong class="badge bg-success bg-gradient mt-2 fs-6 px-3 py-2">
                                    <?php echo ($dispute['admin_decision'] === 'refund_client') ? 'إعادة الأموال للعميل' : 'صرف المستحقات للمستقل'; ?>
                                </strong>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-dashed { border-style: dashed !important; }
</style>