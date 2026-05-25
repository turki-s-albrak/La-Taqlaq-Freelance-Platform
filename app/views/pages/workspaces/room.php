<div class="container my-5">
    
    <div class="card shadow-sm border-0 p-4 p-md-5 bg-white rounded-4 mb-4 border-top border-success border-4">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div>
                    <span class="badge bg-primary bg-gradient mb-3 px-3 py-2 rounded-pill shadow-sm fs-6">🚀 مساحة العمل المشتركة</span>
                    <h2 class="fw-bold text-dark mb-2"><?php echo $escrow['orderTitle']; ?></h2>
                    <div class="d-flex align-items-center gap-2 mt-2 bg-success bg-opacity-10 d-inline-block px-3 py-2 rounded-3 border border-success border-opacity-25">
                        <span class="text-success fw-bold">💰 قيمة التعاقد في الخزنة: </span>
                        <strong class="text-success fs-5">$<?php echo number_format($escrow['escrowPrice'], 2); ?></strong>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <?php if($escrow['status'] === 'in_progress'): ?>
                        <?php if($_SESSION['user_id'] == $escrow['clientId']): ?>
                            <form action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['orderId']; ?>" method="POST" onsubmit="return confirm('تأكيد مالي نهائي: هل أنت متأكد من استلام العمل بالكامل؟ سيتم إطلاق الأموال المحجوزة لحساب المستقل فوراً ولا يمكن التراجع عن هذا القرار.');" class="d-inline">
                                <button type="submit" name="complete_project" class="btn btn-success fw-bold px-4 py-3 shadow-sm rounded-pill btn-hover-effect">
                                    ✅ استلام المشروع وإطلاق الأموال
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline-danger fw-bold px-4 py-3 rounded-pill btn-hover-effect" onclick="document.getElementById('dispute-section').classList.toggle('d-none');">
                            🚨 فتح نزاع
                        </button>
                    <?php else: ?>
                        <?php 
                            if($escrow['status'] === 'completed') {
                                echo '<span class="badge bg-success bg-gradient px-4 py-3 fs-6 shadow-sm rounded-pill">✅ تم تسليم المشروع وإطلاق الأموال</span>';
                            } elseif($escrow['status'] === 'disputed') {
                                echo '<span class="badge bg-danger bg-gradient px-4 py-3 fs-6 shadow-sm rounded-pill">🚨 المشروع تحت النزاع ومراجعة الإدارة</span>';
                            } elseif($escrow['status'] === 'cancelled') {
                                echo '<span class="badge bg-secondary bg-gradient px-4 py-3 fs-6 shadow-sm rounded-pill">❌ تم إلغاء التعاقد وإعادة الأموال</span>';
                            } 
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="dispute-section" class="d-none mt-4 p-4 bg-danger bg-opacity-10 rounded-4 border border-danger border-opacity-25 transition-fade">
                <h5 class="fw-bold text-danger mb-2">تقديم شكوى ونزاع رسمي للإدارة</h5>
                <p class="text-dark small mb-3 fw-bold">يرجى كتابة المشكلة بالتفصيل. سيقوم مشرفو المنصة بقراءة هذه الرسالة ومراجعة السجل الكامل للمحادثات أدناه لحسم المعاملة المالية.</p>
                <form action="<?php echo URLROOT; ?>/workspaces/raise_dispute/<?php echo $escrow['orderId']; ?>" method="POST">
                    <div class="mb-3">
                        <textarea name="dispute_reason" class="form-control bg-white border-0 shadow-sm p-3 custom-input rounded-3" rows="3" placeholder="اكتب تفاصيل الشكوى وأسباب النزاع هنا..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger fw-bold px-4 py-2 shadow-sm rounded-pill btn-hover-effect">
                        إرسال النزاع وتجميد الخزنة فوراً
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-4 d-flex flex-column h-100">
                <h5 class="fw-bold text-dark mb-4 border-bottom border-light pb-3">💬 المحادثة ومشاركة الملفات</h5>
                
                <div id="chat-box" class="flex-grow-1 overflow-auto p-3 mb-4 bg-light rounded-4 border border-secondary border-opacity-10 custom-scrollbar" style="max-height: 500px; min-height: 400px;">
                    </div>

                <?php if($escrow['status'] === 'in_progress' || $escrow['status'] === 'disputed'): ?>
                    <div class="mt-auto bg-light p-3 rounded-4 border border-secondary border-opacity-10">
                        <form id="chat-form" action="<?php echo URLROOT; ?>/workspaces/room/<?php echo $escrow['orderId']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <textarea name="message" id="msg-input" class="form-control border-0 bg-white custom-input p-3 rounded-3 shadow-sm" rows="3" placeholder="اكتب رسالتك هنا... (يمكنك إرفاق ملف PDF بالأسفل)"></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                                <div class="flex-grow-1" style="max-width: 300px;">
                                    <label for="pdf_file" class="form-label small fw-bold text-secondary mb-1">📎 إرفاق مستند (PDF فقط):</label>
                                    <input type="file" name="pdf_file" id="pdf_file" class="form-control bg-white border-0 shadow-sm" accept=".pdf">
                                </div>
                                <button type="submit" name="send_message" class="btn btn-primary fw-bold px-5 py-2 shadow-sm rounded-pill btn-hover-effect">
                                    إرسال 🚀
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary text-center mb-0 mt-auto border-0 shadow-sm p-4 rounded-4" role="alert">
                        <div class="fs-2 mb-2">🔒</div>
                        <strong class="d-block mb-1 text-dark">المحادثة مغلقة للأرشفة</strong>
                        <span class="small text-muted">تم إغلاق مساحة العمل هذه نظراً لانتهاء حالة المشروع واتخاذ قرار مالي نهائي بشأن الخزنة.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-lg border-0 p-4 bg-white rounded-4 sticky-top border-top border-4 border-dark" style="top: 2rem;">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-dark mb-4">📋 تفاصيل المتطلبات المتفق عليها:</h5>
                    <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                        <p class="text-dark small fw-bold mb-0" style="line-height: 1.8; white-space: pre-line;"><?php echo $escrow['orderDesc']; ?></p>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top border-light">
                        <p class="xsmall text-muted mb-0" style="font-size: 0.75rem; line-height: 1.6;">
                            <strong class="text-danger">تنبيه أمني:</strong> يرجى إبقاء كافة المراسلات وتسليم الملفات داخل هذه الغرفة فقط لضمان حقوقك المالية. لن يعتد بأي تواصل يتم خارج منصة "لا تقلق".
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* تأثيرات SaaS UI الخاصة بمساحة العمل */
.custom-input {
    box-shadow: none !important;
    transition: all 0.3s ease;
}
.custom-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
}
.btn-hover-effect {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.transition-fade {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
/* تخصيص السكرول بار ليناسب التصميم الناعم */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8; 
}
</style>

<script>
// --- لم يتم المساس بأي ID أو منطق برمجي للحفاظ على استقرار النظام ---
const chatBox = document.getElementById('chat-box');
const chatForm = document.getElementById('chat-form');
const msgInput = document.getElementById('msg-input');
const pdfInput = document.getElementById('pdf_file');
const orderId = "<?php echo $escrow['orderId']; ?>";
const urlRoot = "<?php echo URLROOT; ?>";

// 1. دالة جلب وتحديث الرسائل
function loadMessages() {
    fetch(`${urlRoot}/workspaces/get_messages_json/${orderId}`)
        .then(response => response.json())
        .then(messages => {
            let chatHTML = '';
            if (messages.length === 0) {
                chatHTML = `
                    <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted">
                        <div class="fs-1 mb-2">👋</div>
                        <p class="small fw-bold">لا توجد رسائل بعد. ابدأ التواصل مع الطرف الآخر الآن!</p>
                    </div>`;
            } else {
                chatHTML = '<div class="d-flex flex-column gap-3">';
                messages.forEach(msg => {
                    const isMe = (msg.senderId == msg.current_user_id);
                    
                    // اللمسة المعمارية: تصميم فقاعات الشات (Chat Bubbles) الحديثة
                    const bubbleAlign = isMe ? 'ms-auto text-start' : 'me-auto text-start';
                    const bubbleStyle = isMe ? 'bg-primary text-white shadow-sm' : 'bg-white text-dark border border-secondary border-opacity-10 shadow-sm';
                    const nameColor = isMe ? 'text-white opacity-75' : 'text-primary';
                    const timeColor = isMe ? 'text-white opacity-75' : 'text-muted';
                    const borderRadius = isMe ? 'border-radius: 1rem 1rem 0 1rem;' : 'border-radius: 1rem 1rem 1rem 0;';
                    const btnClass = isMe ? 'btn-light text-primary' : 'btn-outline-primary';

                    const time = new Date(msg.created_at).toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});

                    chatHTML += `
                        <div class="d-flex w-100 mb-2">
                            <div class="p-3 ${bubbleAlign} ${bubbleStyle}" style="max-width: 85%; ${borderRadius}">
                                <div class="d-flex justify-content-between align-items-center gap-4 mb-2">
                                    <small class="fw-bold ${nameColor}" style="font-size: 0.75rem;">${msg.userName}</small>
                                    <span class="xsmall ${timeColor}" style="font-size: 0.7rem;">${time}</span>
                                </div>
                                <p class="mb-1 small fw-bold" style="white-space: pre-line; line-height: 1.6;">${msg.message}</p>
                                ${msg.attachment ? `
                                    <div class="mt-3 pt-3 border-top ${isMe ? 'border-light border-opacity-25' : 'border-secondary border-opacity-10'}">
                                        <a href="${msg.url_root}/uploads/${msg.attachment}" target="_blank" class="btn btn-sm ${btnClass} fw-bold rounded-pill shadow-sm px-3 py-1" style="font-size: 0.75rem;">
                                            📎 فتح مستند المرفق (PDF)
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                chatHTML += '</div>';
            }
            
            const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 50;
            chatBox.innerHTML = chatHTML;
            
            if (isScrolledToBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
}

loadMessages();

<?php if($escrow['status'] === 'in_progress' || $escrow['status'] === 'disputed'): ?>
    setInterval(loadMessages, 3000);
<?php endif; ?>

if(chatForm) {
    chatForm.addEventListener('submit', function(e) {
        if (pdfInput && pdfInput.files.length > 0) {
            return; 
        }
        
        e.preventDefault(); 
        
        if(msgInput.value.trim() === '') return;

        const formData = new FormData();
        formData.append('send_message', true);
        formData.append('message', msgInput.value);

        fetch(chatForm.action, {
            method: 'POST',
            body: formData
        }).then(() => {
            msgInput.value = ''; 
            loadMessages(); 
        });
    });
}
</script>