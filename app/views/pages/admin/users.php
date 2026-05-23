<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border shadow-sm">&larr; العودة إلى لوحة التحكم</a>
            <h2 class="fw-bold text-dark mb-1">👥 <?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">تحكم كامل في صلاحيات المستخدمين، أرصدتهم، وحماية المنصة من الحسابات الوهمية.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-white rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="py-3 px-4">رقم #</th>
                        <th class="py-3">المستخدم</th>
                        <th class="py-3">البريد الإلكتروني</th>
                        <th class="py-3">الرصيد المالي</th>
                        <th class="py-3">الصلاحية</th>
                        <th class="py-3">الإجراءات والتحكم</th>
                    </tr>
                </thead>
                <tbody class="small border-top-0">
                    <?php foreach($users as $user): ?>
                        <?php 
                            // التحقق هل هذا هو حساب المدير الذي يتصفح الآن؟
                            $isMe = ($user['userId'] == $_SESSION['user_id']); 
                        ?>
                        
                        <tr class="<?php echo $isMe ? 'bg-light' : ''; ?>">
                            <td class="fw-bold text-secondary px-4">#<?php echo $user['userId']; ?></td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="fs-5">👤</span>
                                    <strong class="text-dark"><?php echo $user['userName']; ?></strong>
                                    <?php if($isMe): ?>
                                        <span class="badge bg-primary ms-1 xsmall rounded-pill">أنت</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-muted"><?php echo $user['email']; ?></td> 
                            <td class="text-success fw-bold fs-6">$<?php echo number_format($user['balance'], 2); ?></td>
                            <td>
                                <?php if($user['role'] === 'admin'): ?>
                                    <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill shadow-sm">مدير نظام</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-gradient px-3 py-2 rounded-pill shadow-sm">مستخدم</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <?php if($user['role'] !== 'admin'): ?>
                                        <form action="<?php echo URLROOT; ?>/admin/make_admin/<?php echo $user['userId']; ?>" method="POST" onsubmit="return confirm('تأكيد: هل تريد ترقية هذا العضو إلى مدير نظام بصلاحيات كاملة؟');">
                                            <button type="submit" class="btn btn-sm btn-outline-success fw-bold shadow-sm">👑 ترقية لمدير</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if(!$isMe): ?>
                                        <form action="<?php echo URLROOT; ?>/admin/ban_user/<?php echo $user['userId']; ?>" method="POST" onsubmit="return confirm('تنبيه حرج: هل أنت متأكد من حظر وإقصاء هذا المستخدم نهائياً وحذف بياناته؟');">
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm">🚫 إقصاء نهائي</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light text-muted fw-bold border disabled" disabled>🛡️ حسابك (محمي)</button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>