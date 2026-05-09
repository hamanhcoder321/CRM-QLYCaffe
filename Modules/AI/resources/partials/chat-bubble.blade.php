<!-- AI Chat Bubble -->
<div id="ai-chat-wrapper" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: 'Source Sans Pro', sans-serif;">
    <!-- Bubble Icon -->
    <div id="ai-chat-bubble" style="width: 60px; height: 60px; background: #007bff; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,123,255,0.4);">
        <i class="fas fa-robot text-white" style="font-size: 28px;"></i>
    </div>

    <!-- Chat Window (Hidden by default) -->
    <div id="ai-chat-window" style="display: none; width: 380px; height: 500px; background: white; border-radius: 15px; position: absolute; bottom: 75px; right: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.15); flex-direction: column; overflow: hidden; border: 1px solid #eee;">
        <!-- Header -->
        <div style="background: #007bff; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: bold;"><i class="fas fa-robot mr-2"></i> Trợ lý AI (Data)</div>
            <div id="close-ai-chat" style="cursor: pointer;"><i class="fas fa-times"></i></div>
        </div>

        <!-- Messages -->
        <div id="ai-chat-messages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa;">
            <div class="mb-3 d-flex justify-content-start">
                <div class="bg-white p-2 rounded shadow-sm border" style="max-width: 85%; font-size: 14px;">
                    Chào Giám đốc! Tôi có thể giúp gì cho bạn về dữ liệu hệ thống hôm nay?
                </div>
            </div>
        </div>

        <!-- Footer / Input -->
        <div style="padding: 10px; border-top: 1px solid #eee;">
            <form id="ai-chat-bubble-form" style="display: flex;">
                <input type="text" id="ai-chat-input" placeholder="Hỏi về doanh thu, nhân sự..." style="flex: 1; border: 1px solid #ddd; border-radius: 20px; padding: 8px 15px; outline: none; font-size: 14px;">
                <button type="submit" style="background: #007bff; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; margin-left: 8px; cursor: pointer;">
                    <i class="fas fa-paper-plane" style="font-size: 14px;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bubble = document.getElementById('ai-chat-bubble');
    const window = document.getElementById('ai-chat-window');
    const closeBtn = document.getElementById('close-ai-chat');
    const form = document.getElementById('ai-chat-bubble-form');
    const input = document.getElementById('ai-chat-input');
    const messages = document.getElementById('ai-chat-messages');

    // Toggle window
    bubble.addEventListener('click', () => {
        window.style.display = window.style.display === 'none' ? 'flex' : 'none';
        if(window.style.display === 'flex') input.focus();
    });

    closeBtn.addEventListener('click', () => {
        window.style.display = 'none';
    });

    // Handle chat
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = input.value.trim();
        if(!text) return;

        appendMsg('user', text);
        input.value = '';

        const typingId = 'typing-' + Date.now();
        appendTyping(typingId);

        fetch('/api/ai/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: 'question=' + encodeURIComponent(text)
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById(typingId).remove();
            appendMsg('bot', data.answer || 'Lỗi kết nối.');
        })
        .catch(() => {
            document.getElementById(typingId).remove();
            appendMsg('bot', 'Lỗi hệ thống.');
        });
    });

    function appendMsg(role, text) {
        const div = document.createElement('div');
        div.className = 'mb-3 d-flex ' + (role === 'user' ? 'justify-content-end' : 'justify-content-start');
        const bg = role === 'user' ? 'background: #007bff; color: white;' : 'background: white; color: #333; border: 1px solid #eee;';
        div.innerHTML = `<div style="max-width: 85%; padding: 8px 12px; border-radius: 12px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); ${bg}">${text}</div>`;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function appendTyping(id) {
        const div = document.createElement('div');
        div.id = id;
        div.className = 'mb-3 d-flex justify-content-start';
        div.innerHTML = `<div style="max-width: 85%; padding: 8px 12px; border-radius: 12px; font-size: 14px; background: white; color: #999; border: 1px solid #eee;"><i class="fas fa-spinner fa-spin mr-1"></i> Đang phân tích...</div>`;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }
});
</script>
