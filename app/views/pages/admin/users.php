<div class="container my-5">
    <div class="mb-4">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-light btn-sm mb-3 border">&larr; العودة لملخص الإدارة</a>
        <h2 class="fw-bold text-dark"><?php echo $page_title; ?></h2>
        <p class="text-muted small">التحكم في صلاحيات المستخدمين وتطهير المنصة من الحسابات الوهمية</p>
    </div>

    <div class="card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-dark small">
                    <tr>
                        <th class="py-3">رقم المستخدم</th>
                        <th class="py-3">الاسم</th>
                        <th class="py-3">البريد الإلكتروني</th>
                        <th class="py-3">الرصيد المالي</th>
                        <th class="py-3">الصلاحية الحالية</th>
                        <th class="py-3">الإجراءات والتحكم</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td class="fw-bold text-secondary">#<?php echo $user['userId']; ?></td>
                            <td><strong><?php echo $user['userName']; ?></strong></td>
                            <td class="text-muted"><?php echo $user['email']; ?></td> 
                            <td class="text-success fw-bold">$<?php echo number_format($user['balance'], 2); ?></td>
                            <td>
                                <span class="badge <?php echo ($user['role'] === 'admin') ? 'bg-danger' : 'bg-secondary'; ?> px-2 py-1">
                                    <?php echo strtoupper($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- زر الترقية إلى أدمن: يظهر فقط إذا كان المستخدم حالياً عضواً عادياً -->
                                    <?php if($user['role'] !== 'admin'): ?>
                                        <form action="<?php echo URLROOT; ?>/admin/make_admin/<?php echo $user['userId']; ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من ترقية هذا العضو إلى مدير نظام وصاحب صلاحيات عليا؟');">
                                            <button type="submit" class="btn btn-sm btn-outline-warning fw-bold text-dark xsmall" style="font-size: 0.75rem;">ترقية لأدمن</button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- زر الحظر والإقصاء النهائي: محمي ضد حظر الحساب الشخصي للأدمن الحالي -->
                                    <form action="<?php echo URLROOT; ?>/admin/ban_user/<?php echo $user['userId']; ?>" method="POST" onsubmit="return confirm('تنبيه حرج: هل أنت متأكد من حظر وإقصاء هذا المستخدم تماماً وحذف بياناته من النظام؟');">
                                        <button type="submit" class="btn btn-sm btn-danger fw-bold xsmall <?php echo ($user['userId'] == $_SESSION['user_id']) ? 'disabled' : ''; ?>" style="font-size: 0.75rem;">🚫 حظر نهائي</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>