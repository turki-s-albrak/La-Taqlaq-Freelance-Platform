<div class="container my-5">
    <div class="card shadow-sm border-0 p-4 bg-white rounded-3 mb-4 border-top border-success border-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-primary bg-gradient mb-2 px-3 py-2 rounded-pill shadow-sm">🚀 مساحة العمل المشتركة</span>
                    <h3 class="fw-bold text-dark mb-1"><?php echo $escrow['orderTitle']; ?></h3>
                    <div class="text-muted small mt-2">
                        <span class="me-3">💰 قيمة التعاقد: <strong class="text-success fs-6">$<?php echo number_format($escrow['escrowPrice'], 2); ?></strong> (في الخزنة الآمنة)</span>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <?php if($escrow['status'] === 'in_progress'): ?>
                        <?php if($_SESSION['user_id'] == $escrow['clientId']): ?>
                            <form action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['orderId']; ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من استلام العمل؟ سيتم إطلاق الأموال المحجوزة لحساب المستقل فوراً ولا يمكن التراجع.');" class="d-inline">
                                <button type="submit" name="complete_project" class="btn btn-success fw-bold btn-sm px-4 py-2 shadow-sm">✓ استلام المشروع وإطلاق الأموال</button>
                            </form>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline-danger fw-bold btn-sm px-3 py-2" onclick="document.getElementById('dispute-section').classList.toggle('d-none');">
                            🚨 فتح نزاع
                        </button>
                    <?php else: ?>
                        <?php 
                            if($escrow['status'] === 'completed') {
                                echo '<span class="badge bg-success px-4 py-2 fs-6 shadow-sm rounded-pill">✅ تم تسليم المشروع بنجاح</span>';
                            } elseif($escrow['status'] === 'disputed') {
                                echo '<span class="badge bg-danger px-4 py-2 fs-6 shadow-sm rounded-pill">🚨 المشروع تحت النزاع والمراجعة</span>';
                            } elseif($escrow['status'] === 'cancelled') {
                                echo '<span class="badge bg-secondary px-4 py-2 fs-6 shadow-sm rounded-pill">❌ تم إلغاء التعاقد</span>';
                            } 
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="dispute-section" class="d-none mt-4 p-3 bg-light rounded-3 border border-danger">
                <h5 class="fw-bold text-danger mb-2">تقديم شكوى ونزاع رسمي للإدارة</h5>
                <p class="text-muted small mb-3">يرجى كتابة المشكلة بالتفصيل (تأخر التسليم، جودة العمل، إلخ). سيقوم الأدمن بقراءة هذه الرسالة ومراجعة الشات الحالي لحسم المعاملة.</p>
                <form action="<?php echo URLROOT; ?>/workspaces/raise_dispute/<?php echo $escrow['orderId']; ?>" method="POST">
                    <div class="mb-3">
                        <textarea name="dispute_reason" class="form-control form-control-sm" rows="3" placeholder="اكتب تفاصيل الشكوى هنا..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm fw-bold px-4">إرسال النزاع وتجميد الأموال فوراً</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 d-flex flex-column" style="min-height: 500px;">
                <h4 class="fw-bold text-dark mb-4">محادثة ووثائق المشروع</h4>
                
                <div id="chat-box" class="flex-grow-1 overflow-auto p-3 mb-3 bg-light rounded-3 border" style="max-height: 400px; min-height: 300px;">
                    </div>

                <?php if($escrow['status'] === 'in_progress' || $escrow['status'] === 'disputed'): ?>
                    <form id="chat-form" action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['orderId']; ?>" method="POST" enctype="multipart/form-data" class="mt-auto">
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

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100">
                <h5 class="fw-bold text-secondary mb-3">تفاصيل المتطلبات المتفق عليها:</h5>
                <p class="text-muted small" style="line-height: 1.7; white-space: pre-line;"><?php echo $escrow['orderDesc']; ?></p>
            </div>
        </div>
    </div>
</div>

<script>
const chatBox = document.getElementById('chat-box');
const chatForm = document.getElementById('chat-form');
const msgInput = document.getElementById('msg-input');
const pdfInput = document.getElementById('pdf_file');
// التعديل المعماري الأهم: إرسال orderId للجافاسكريبت بدلاً من escrowId
const orderId = "<?php echo $escrow['orderId']; ?>";
const urlRoot = "<?php echo URLROOT; ?>";

// 1. دالة جلب وتحديث الرسائل تلقائياً بدون ريفريش
function loadMessages() {
    fetch(`${urlRoot}/workspaces/get_messages_json/${orderId}`)
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
        // إذا كان هناك ملف PDF مرفوع، نترك الفورم يقوم بعمل ريفريش عادي لأن رفع الملفات يتطلب إعادة إرسال كاملة
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