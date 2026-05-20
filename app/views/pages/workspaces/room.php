<div class="container my-5">
    <!-- عنوان مساحة العمل وتفاصيل العقد -->
    <div class="card shadow-sm border-0 p-4 bg-white rounded-3 mb-4 border-top border-success border-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-success mb-2">مساحة عمل آمنة</span>
                    <h2 class="fw-bold text-dark mb-1">عقد مشروع: <?php echo $escrow['orderTitle']; ?></h2>
                    <small class="text-muted">مبلغ الخزنة المحجوز: <strong class="text-success">$<?php echo number_format($escrow['price'], 2); ?></strong></small>
                </div>
                
                <div class="d-flex gap-2">
                    <?php if($escrow['status'] === 'in_progress'): ?>
                        <?php if($_SESSION['user_id'] == $escrow['clientId']): ?>
                            <form action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['escrowId']; ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من استلام العمل؟ سيتم إطلاق الأموال المحجوزة لحساب المستقل فوراً ولا يمكن التراجع.');">
                                <button type="submit" name="complete_project" class="btn btn-success fw-bold px-4">✓ استلام المشروع وإطلاق الأموال</button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark p-2 fs-6">بانتظار تسليم العمل للعميل...</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge bg-secondary p-2 fs-6">حالة العقد الحالية: <?php echo strtoupper($escrow['status']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- شاشة المحادثة (الجانب الأيمن) -->
        <div class="col-12 col-md-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 d-flex flex-column" style="min-height: 500px;">
                <h4 class="fw-bold text-dark mb-4">محادثة ووثائق المشروع</h4>
                
                <!-- صندوق عرض الرسائل (أضفنا له معرف ID للجافاسكريبت) -->
                <div id="chat-box" class="flex-grow-1 overflow-auto p-3 mb-3 bg-light rounded-3 border" style="max-height: 400px; min-height: 300px;">
                    <!-- سيتم تعبئة الرسائل حياً بواسطة الجافاسكريبت والـ PHP معاً -->
                </div>

                <?php if($escrow['status'] === 'in_progress' || $escrow['status'] === 'disputed'): ?>
                    <!-- أضفنا معرف ID للفورم لمنع الريفرش عند الإرسال العادي للرسائل النصية -->
                    <form id="chat-form" action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['escrowId']; ?>" method="POST" enctype="multipart/form-data" class="mt-auto">
                        <div class="mb-3">
                            <textarea name="message" id="msg-input" class="form-control" rows="3" placeholder="اكتب رسالة أو أرفق ملف PDF بالأسفل..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <label for="pdf_file" class="form-label small fw-bold text-secondary mb-1">إرفاق مستند (PDF فقط):</label>
                                <input type="file" name="pdf_file" id="pdf_file" class="form-control form-control-sm" accept=".pdf">
                            </div>
                            <button type="submit" name="send_message" class="btn btn-primary fw-bold px-4">إرسال الرسالة</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger text-center mb-0 mt-3 border-0 shadow-sm" role="alert">
                        <strong>🔒 المحادثة مغلقة:</strong> تم إغلاق مساحة العمل هذه نظراً لانتهاء حالة المشروع واتخاذ قرار مالي نهائي بشأن الخزنة.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- معلومات العقد والوصف -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100">
                <h5 class="fw-bold text-secondary mb-3">تفاصيل المتطلبات المتفق عليها:</h5>
                <p class="text-muted small" style="line-height: 1.7; white-space: pre-line;"><?php echo $escrow['orderDesc']; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- سكريبت التحديث الفوري والتفاعل الحي (Real-time Simulation) -->
<script>
const chatBox = document.getElementById('chat-box');
const chatForm = document.getElementById('chat-form');
const msgInput = document.getElementById('msg-input');
const pdfInput = document.getElementById('pdf_file');
const escrowId = "<?php echo $escrow['escrowId']; ?>";
const urlRoot = "<?php echo URLROOT; ?>";

// 1. دالة جلب وتحديث الرسائل تلقائياً بدون ريفريش
function loadMessages() {
    fetch(`${urlRoot}/workspaces/get_messages_json/${escrowId}`)
        .then(response => response.json())
        .then(messages => {
            let chatHTML = '';
            if (messages.length === 0) {
                chatHTML = '<p class="text-center text-muted small my-5">لا توجد رسائل بعد. ابدأ التواصل مع الطرف الآخر الآن!</p>';
            } else {
                chatHTML = '<div class="d-flex flex-column gap-3">';
                messages.forEach(msg => {
                    const isMe = (msg.senderId == msg.current_user_id);
                    const cardClass = isMe ? 'bg-primary text-white ms-auto text-start' : 'bg-white text-dark me-auto text-start border shadow-sm';
                    const nameClass = isMe ? 'text-light' : 'text-primary';
                    const btnClass = isMe ? 'btn-light text-primary' : 'btn-outline-primary';
                    
                    // استخراج الوقت
                    const time = new Date(msg.created_at).toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});

                    chatHTML += `
                        <div class="p-3 rounded-3" style="max-width: 80%; ${isMe ? 'margin-left: auto;' : 'margin-right: auto;'}">
                            <div class="p-3 rounded-3 ${cardClass}">
                                <div class="d-flex justify-content-between align-items-center gap-4 mb-1">
                                    <small class="fw-bold ${nameClass}">${msg.userName}</small>
                                    <span class="xsmall opacity-75" style="font-size: 0.7rem;">${time}</span>
                                </div>
                                <p class="mb-2 small" style="white-space: pre-line;">${msg.message}</p>
                                ${msg.attachment ? `
                                    <div class="mt-2 pt-2 border-top border-light">
                                        <a href="${msg.url_root}/uploads/${msg.attachment}" target="_blank" class="btn btn-sm ${btnClass} fw-bold xsmall" style="font-size: 0.75rem;">
                                            📎 فتح مستند PDF المرفق
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                chatHTML += '</div>';
            }
            
            // تحديث الصندوق فقط إذا تغير المحتوى لتجنب وميض الصفحة العشوائي
            const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 50;
            chatBox.innerHTML = chatHTML;
            
            // إنزال السكرول لآخر رسالة تلقائياً عند فتح الصفحة أو استقبال رسالة جديدة
            if (isScrolledToBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
}

// تشغيل جلب الرسائل فور فتح الصفحة
loadMessages();

// تكرار الجلب تلقائياً كل 3 ثوانٍ لمراقبة الشات حياً للطرفين!
<?php if($escrow['status'] === 'in_progress' || $escrow['status'] === 'disputed'): ?>
    setInterval(loadMessages, 3000);
<?php endif; ?>

// 2. منع الريفرش الكامل عند إرسال رسائل نصية عادية لتسريع تجربة المستخدم
if(chatForm) {
    chatForm.addEventListener('submit', function(e) {
        // إذا كان هناك ملف PDF مرفوع، نترك الفورم يقوم بعمل ريفريش عادي لأن رفع الملفات يتطلب إعادة إرسال كاملة بطبيعة الـ PHP الـ Native
        if (pdfInput && pdfInput.files.length > 0) {
            return; 
        }
        
        e.preventDefault(); // منع الريفرش للرسائل النصية
        
        if(msgInput.value.trim() === '') return;

        const formData = new FormData();
        formData.append('send_message', true);
        formData.append('message', msgInput.value);

        fetch(chatForm.action, {
            method: 'POST',
            body: formData
        }).then(() => {
            msgInput.value = ''; // تصفير الحقل
            loadMessages(); // تحديث الشات فوراً دون انتظار الـ 3 ثوانٍ
        });
    });
}
</script>