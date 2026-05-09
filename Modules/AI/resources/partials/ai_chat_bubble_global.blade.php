{{-- ===== FLOATING AI CHAT BUBBLE (GLOBAL - xuất hiện mọi trang) ===== --}}
<style>
    /* === Nút mở chat === */
    #ai-chat-toggle {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-size: 24px;
        border: none;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        cursor: pointer;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    #ai-chat-toggle:hover { background: #0b5ed7; }

    /* === Hộp chat === */
    #ai-chat-widget {
        position: fixed;
        bottom: 88px;
        right: 24px;
        width: 380px;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        font-family: Arial, sans-serif;
        background: #fff;
        z-index: 9998;
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    #ai-chat-widget.open { display: flex; }

    .ai-chat-header {
        padding: 12px 14px;
        background: #0d6efd;
        color: #fff;
        font-weight: bold;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ai-chat-header .ai-close-btn {
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        opacity: 0.8;
    }
    .ai-chat-header .ai-close-btn:hover { opacity: 1; }

    /* === Hint bar === */
    #ai-hint-bar {
        padding: 6px 12px;
        background: #e8f4fd;
        font-size: 11px;
        color: #555;
        border-bottom: 1px solid #d0e8f8;
    }
    #ai-hint-bar kbd {
        background: #d0e8f8;
        border-radius: 3px;
        padding: 1px 4px;
        font-size: 11px;
    }

    /* === Context badge === */
    #ai-context-bar {
        padding: 6px 12px;
        background: #d4edda;
        font-size: 12px;
        color: #155724;
        border-bottom: 1px solid #c3e6cb;
        display: none;
        justify-content: space-between;
        align-items: center;
    }
    #ai-context-bar.show { display: flex; }
    #ai-ctx-clear {
        cursor: pointer;
        color: #888;
        font-size: 16px;
        line-height: 1;
        background: none;
        border: none;
        padding: 0;
    }

    #ai-chat-body {
        height: 300px;
        overflow-y: auto;
        padding: 12px;
        background: #f7f7f7;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ai-msg {
        padding: 8px 12px;
        border-radius: 8px;
        max-width: 88%;
        font-size: 13px;
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
    }
    .ai-msg.user {
        background: #d1e7dd;
        margin-left: auto;
        text-align: right;
    }
    .ai-msg.bot {
        background: #fff;
        border: 1px solid #ddd;
    }
    .ai-msg.summary {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        font-size: 12px;
    }
    .ai-msg.typing {
        color: #888;
        font-style: italic;
        background: #fff;
        border: 1px solid #ddd;
    }

    .ai-chat-footer {
        display: flex;
        align-items: flex-end;
        border-top: 1px solid #ddd;
        background: #fff;
        padding: 6px 8px;
        gap: 6px;
    }
    .ai-chat-footer textarea {
        flex: 1;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        font-family: Arial, sans-serif;
        resize: none;
        min-height: 36px;
        max-height: 120px;
        overflow-y: auto;
        line-height: 1.4;
        transition: border-color 0.2s;
    }
    .ai-chat-footer textarea:focus { border-color: #0d6efd; }
    .ai-chat-footer button {
        padding: 8px 14px;
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
        flex-shrink: 0;
        align-self: flex-end;
    }
    .ai-chat-footer button:hover { background: #0b5ed7; }

    /* === Speech bubble intro (bên ngoài nút toggle) === */
    #ai-intro-bubble {
        position: fixed;
        bottom: 32px;
        right: 88px;           /* ngay bên trái nút robot */
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 12px 12px 4px 12px; /* đuôi bong bóng về phía nút */
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 500;
        color: #222;
        box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        z-index: 9997;
        white-space: nowrap;
        display: none;         /* JS sẽ show */
        align-items: center;
        gap: 4px;
        pointer-events: none;  /* không chặn click */
    }
    #ai-intro-bubble.show { display: flex; }
    #ai-intro-bubble .ai-cursor {
        display: inline-block;
        width: 2px;
        height: 13px;
        background: #0d6efd;
        margin-left: 1px;
        animation: ai-blink 0.7s step-end infinite;
        vertical-align: middle;
    }
    @keyframes ai-blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0; }
    }
</style>

{{-- Admin ID để phân biệt history theo tài khoản --}}
<input type="hidden" id="ai-admin-id" value="{{ Auth::guard('admin')->id() }}">

{{-- Speech bubble intro bên ngoài nút --}}
<div id="ai-intro-bubble">
    <span id="ai-intro-text"></span>
    <span class="ai-cursor"></span>
</div>

{{-- Nút bong bóng --}}
<button id="ai-chat-toggle" title="Trợ lý AI" onclick="toggleAIChat()">🤖</button>

{{-- Cửa sổ chat --}}
<div id="ai-chat-widget">
    <div class="ai-chat-header">
        <span>🤖 Trợ lý AI</span>
        <div style="display:flex;gap:8px;align-items:center">
            <span title="Xóa lịch sử chat" onclick="aiClearContext()" style="cursor:pointer;opacity:0.8;font-size:14px" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">🗑️</span>
            <span class="ai-close-btn" onclick="toggleAIChat()">✕</span>
        </div>
    </div>

    <div id="ai-hint-bar">
        💡 Nhập <kbd>SĐT</kbd> để tra cứu thông tin khách hàng, hoặc nhập câu hỏi bất kỳ.
    </div>

    {{-- Context bar: hiện khi đã load khách hàng --}}
    <div id="ai-context-bar">
        <span id="ai-ctx-label">📋 Đang hỏi về: ...</span>
        <button id="ai-ctx-clear" title="Xóa ngữ cảnh" onclick="aiClearContext()">✕</button>
    </div>

    <div id="ai-chat-body">
        <div class="ai-msg bot" data-no-save="true">Xin chào 👋 Bạn cần tra cứu khách hàng hay hỏi điều gì? Tôi sẵn sàng hỗ trợ.</div>
    </div>
    <div class="ai-chat-footer">
        <textarea id="ai-question" placeholder="Nhập câu hỏi... (Enter gửi, Shift+Enter xuống dòng)" rows="1"
            onkeydown="aiHandleKey(event)"
            oninput="aiAutoResize(this)"></textarea>
        <button onclick="sendAI()">Gửi</button>
    </div>
</div>

<script>
    // ── Key localStorage theo tài khoản ──────────────────────────────────
    var _adminId = document.getElementById('ai-admin-id')?.value || 'guest';
    var _storageKey = 'ai_chat_msgs_'  + _adminId;
    var _ctxKey     = 'ai_chat_ctx_'   + _adminId;

    var aiPhoneContext = null;  // { tel }

    // ── Lưu / đọc localStorage ──────────────────────────────────────────
    function saveMsgs() {
        const body = document.getElementById('ai-chat-body');
        const msgs = [];
        body.querySelectorAll('.ai-msg').forEach(el => {
            // Bỏ qua: typing indicator, messages tạm thời, welcome message
            if (el.classList.contains('typing')) return;
            if (el.dataset.noSave) return;
            if (el.innerText === '...' || el.innerText.startsWith('🔍 Đang tra cứu')) return;
            msgs.push({ cls: el.className, text: el.innerText });
        });
        try { localStorage.setItem(_storageKey, JSON.stringify(msgs.slice(-60))); } catch(e){}
    }

    function loadMsgs() {
        try {
            const saved = JSON.parse(localStorage.getItem(_storageKey) || '[]');
            if (!saved.length) return;
            const body = document.getElementById('ai-chat-body');
            body.innerHTML = ''; // xóa welcome mặc định
            saved.forEach(m => {
                const div = document.createElement('div');
                div.className = m.cls;
                div.innerText = m.text;
                body.appendChild(div);
            });
            body.scrollTop = 99999;
        } catch(e){}
    }

    function saveCtx() {
        try {
            if (aiPhoneContext) localStorage.setItem(_ctxKey, JSON.stringify(aiPhoneContext));
            else localStorage.removeItem(_ctxKey);
        } catch(e){}
    }

    function loadCtx() {
        try {
            const c = JSON.parse(localStorage.getItem(_ctxKey) || 'null');
            if (c && c.tel) {
                aiPhoneContext = c;
                document.getElementById('ai-context-bar').classList.add('show');
                document.getElementById('ai-ctx-label').innerText = '📋 Đang hỏi về: ' + c.tel;
            }
        } catch(e){}
    }

    function toggleAIChat() {
        const w = document.getElementById('ai-chat-widget');
        w.classList.toggle('open');
        if (w.classList.contains('open')) {
            document.getElementById('ai-question').focus();
            // Ẩn speech bubble khi mở chat
            var bubble = document.getElementById('ai-intro-bubble');
            if (bubble) bubble.classList.remove('show');
        }
    }

    function aiAppendMsg(role, text) {
        const body = document.getElementById('ai-chat-body');
        const div  = document.createElement('div');
        div.className = 'ai-msg ' + role;
        div.innerText  = text;
        body.appendChild(div);
        body.scrollTop = 99999;
        // Lưu vào localStorage sau mỗi tin nhắn
        saveMsgs();
        return div;
    }

    function getLeadId() {
        const el = document.getElementById('lead_id');
        return el ? el.value : '';
    }

    // Tìm SĐT Việt Nam trong chuỗi bất kỳ
    function extractPhone(str) {
        // Regex nhận dạng số điện thoại cho phép dấu . hoặc - hoặc space làm phân cách
        // Nhóm 1: số 0 đầu (có thể thiếu) + số tiếp theo (3-9)
        // VD: 0981.263.469 | 981.263.469 | 0981-263-469 | 0981263469
        var sep = '[.\\-\\s]?';
        var pattern = new RegExp(
            '(0?)([3-9]\\d' + sep + '\\d{3}' + sep + '\\d{4}' +
            '|[3-9]\\d{2}' + sep + '\\d{3}' + sep + '\\d{3})'
        );
        var m = str.match(pattern);
        if (!m) return null;
        // Chuẩn hóa: xóa phân cách, thêm 0 nếu thiếu
        var clean = m[0].replace(/[.\-\s]/g, '');
        if (clean.length === 9) clean = '0' + clean;  // thiếu số 0 đầu
        if (clean.length !== 10) return null;
        return clean;
    }

    // Tách SĐT ra khỏi câu hỏi (xóa cả dạng gốc có dấu chấm/gạch)
    function extractQuestion(str, cleanPhone) {
        // Xóa dạng chuẩn hóa (toàn số)
        var q = str.replace(cleanPhone, '');
        // Xóa thêm dạng gốc có dấu chấm/gạch (vd: 981.263.469)
        q = q.replace(/0?[3-9][\d.\-\s]{9,13}/g, '');
        return q.replace(/^[\s\-,:.]+|[\s\-,:.]+$/g, '').trim();
    }

    function aiClearContext() {
        aiPhoneContext = null;
        saveCtx();
        // Xóa toàn bộ localStorage của user này
        localStorage.removeItem(_storageKey);
        localStorage.removeItem(_ctxKey);
        document.getElementById('ai-context-bar').classList.remove('show');
        // Reset DOM về welcome message (data-no-save → không được lưu)
        const body = document.getElementById('ai-chat-body');
        body.innerHTML = '<div class="ai-msg bot" data-no-save="true">Xin chào 👋 Bạn cần tra cứu khách hàng hay hỏi điều gì? Tôi sẵn sàng hỗ trợ.</div>';
    }

    function setAIContext(tel, summary) {
        aiPhoneContext = { tel: tel };
        saveCtx();
        document.getElementById('ai-ctx-label').innerText = '📋 Đang hỏi về: ' + tel;
        document.getElementById('ai-context-bar').classList.add('show');
    }

    // ── Khởi tạo: load lại history khi trang load ─────────────────
    loadMsgs();
    loadCtx();

    // ── Textarea tự giãn theo nội dung ────────────────────────────
    function aiAutoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    // ── Enter = gửi, Shift+Enter = xuống dòng ─────────────────────
    function aiHandleKey(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault(); // chặn xuống dòng
            sendAI();
        }
        // Shift+Enter → giữ nguyên hành vi mặc định (xuống dòng)
    }

    // ── Hiệu ứng gõ chữ bên ngoài nút toggle ────────────────────
    function aiTypingIntro() {
        var bubble   = document.getElementById('ai-intro-bubble');
        var textEl   = document.getElementById('ai-intro-text');
        if (!bubble || !textEl) return;

        var fullText = 'Tôi là trợ lý AI';
        var i = 0;
        textEl.textContent = '';
        bubble.classList.add('show');

        var timer = setInterval(function() {
            if (i < fullText.length) {
                textEl.textContent += fullText[i];
                i++;
            } else {
                clearInterval(timer);
                // Sau 2s → mờ dần và ẩn
                setTimeout(function() {
                    bubble.style.transition = 'opacity 0.5s';
                    bubble.style.opacity    = '0';
                    setTimeout(function() {
                        bubble.classList.remove('show');
                        bubble.style.opacity    = '';
                        bubble.style.transition = '';
                    }, 500);
                }, 2000);
            }
        }, 70);
    }

    // Gọi tự động khi trang load (delay 800ms để DOM ổn định)
    setTimeout(aiTypingIntro, 800);

    function sendAI() {
        const input    = document.getElementById('ai-question');
        const rawInput = input.value.trim();
        if (!rawInput) return;

        const cleanInput = rawInput.replace(/\s/g, '');

        // ── Trường hợp 1: Phát hiện SĐT trong câu nhập ────────────────
        // Hỗ trợ cả: chỉ nhập SĐT, hoặc nhập SĐT + câu hỏi cùng lúc
        // VD: "0981263469" hoặc "0981263469 khách cần tư vấn gì?"
        const detectedPhone = extractPhone(rawInput);
        if (detectedPhone) {
            const questionPart = extractQuestion(rawInput, detectedPhone);
            const displayQuestion = questionPart || 'Tóm tắt thông tin khách hàng này';

            aiAppendMsg('user', rawInput);
            input.value = '';
            const typingDiv = aiAppendMsg('typing', '🔍 Đang tra cứu SĐT ' + detectedPhone + '...');

            fetch('/api/ai/phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ tel: detectedPhone, question: displayQuestion })
            })
            .then(async r => {
                const data = await r.json().catch(() => ({}));
                typingDiv.remove();

                if (data.summary) {
                    setAIContext(detectedPhone, data.summary);
                    aiAppendMsg('summary', data.summary);
                }
                const reply = data.answer || data.error || 'Tôi chưa hiểu câu hỏi này, bạn có thể diễn đạt lại không?';
                aiAppendMsg('bot', reply);
                document.getElementById('ai-chat-body').scrollTop = 99999;
            })
            .catch(() => {
                typingDiv.className = 'ai-msg bot';
                typingDiv.innerText = '❌ Không kết nối được server.';
            });
            return;
        }

        // ── Trường hợp 2: Hỏi câu hỏi ──────────────────────────────────
        aiAppendMsg('user', rawInput);
        input.value = '';
        const typingDiv = aiAppendMsg('typing', '...');

        // Nếu đang có context SĐT → dùng /api/ai/phone
        if (aiPhoneContext && aiPhoneContext.tel) {
            fetch('/api/ai/phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ tel: aiPhoneContext.tel, question: rawInput })
            })
            .then(async r => {
                const data = await r.json().catch(() => ({}));
                typingDiv.className = 'ai-msg bot';
                typingDiv.innerText = data.answer || data.error || 'Tôi chưa hiểu câu hỏi này, bạn có thể diễn đạt lại không?';
                saveMsgs();
                document.getElementById('ai-chat-body').scrollTop = 99999;
            })
            .catch(() => {
                typingDiv.className = 'ai-msg bot';
                typingDiv.innerText = '❌ Không kết nối được server.';
                saveMsgs();
            });
            return;
        }

        // Không có context SĐT → kiểm tra lead_id
        const leadId = getLeadId();
        if (leadId) {
            // Đang ở trang lead/edit → dùng /api/ai/lead
            fetch('/api/ai/lead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ lead_id: leadId, question: rawInput })
            })
            .then(async r => {
                const text = await r.text();
                try {
                    const data = JSON.parse(text);
                    typingDiv.className = 'ai-msg bot';
                    typingDiv.innerText = data.answer || data.error || 'Câu hỏi này không có trong dữ liệu, vui lòng nhập số điện thoại để AI tra cứu và trả lời.';
                } catch (e) {
                    typingDiv.className = 'ai-msg bot';
                    typingDiv.innerText = '❌ Lỗi kết nối. Thử lại sau.';
                }
                saveMsgs();
                document.getElementById('ai-chat-body').scrollTop = 99999;
            })
            .catch(() => {
                typingDiv.className = 'ai-msg bot';
                typingDiv.innerText = '❌ Không kết nối được server.';
                saveMsgs();
            });
            return;
        }

        // Không có SĐT, không có lead_id, không có context
        // → Trả lời ngay, không gọi API
        typingDiv.className = 'ai-msg bot';
        typingDiv.innerText = '💡 Câu hỏi này không có trong dữ liệu. Vui lòng nhập số điện thoại khách hàng để Trợ lý AI tra cứu và trả lời.';
        saveMsgs();
        document.getElementById('ai-chat-body').scrollTop = 99999;
    }
</script>
