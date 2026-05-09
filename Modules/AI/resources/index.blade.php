@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold"><i class="fas fa-robot text-primary mr-2"></i>Trợ lý AI Phân tích dữ liệu</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h3 class="card-title text-muted"><i class="fas fa-comment-dots mr-1"></i>Hỏi bất cứ điều gì về dữ liệu hệ thống</h3>
                        </div>
                        <div class="card-body" id="chat-box" style="height: 450px; overflow-y: auto; background: #f8f9fa;">
                            <div class="message system-message mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary text-white rounded-circle p-2 mr-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div class="bg-white p-3 rounded shadow-sm" style="max-width: 80%;">
                                        Chào bạn! Tôi là Trợ lý AI của hệ thống Café CRM. Tôi có thể giúp bạn tra cứu dữ liệu nhân sự, chi nhánh, chấm công và lương. 
                                        <br><br>
                                        <strong>Ví dụ bạn có thể hỏi:</strong>
                                        <ul>
                                            <li>Chi nhánh Mỹ Đình có bao nhiêu nhân viên?</li>
                                            <li>Tổng lương tháng này của cả hệ thống là bao nhiêu?</li>
                                            <li>Liệt kê danh sách nhân viên chi nhánh Tây Mỗ.</li>
                                            <li>Ai là người có lương cao nhất?</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <form id="chat-form">
                                <div class="input-group">
                                    <input type="text" id="question" class="form-control border-0 bg-light p-4" placeholder="Nhập câu hỏi của bạn tại đây..." required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane mr-1"></i> Gửi</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        let question = $('#question').val().trim();
        if (question === '') return;

        // Add user message
        appendMessage('user', question);
        $('#question').val('');

        // Show typing indicator
        let typingId = 'typing-' + Date.now();
        appendTypingIndicator(typingId);

        $.ajax({
            url: '/api/ai/ask',
            method: 'POST',
            data: { question: question },
            success: function(res) {
                removeTypingIndicator(typingId);
                if (res.answer) {
                    appendMessage('bot', res.answer);
                } else if (res.error) {
                    appendMessage('bot', 'Lỗi: ' + res.error);
                }
            },
            error: function(xhr) {
                removeTypingIndicator(typingId);
                appendMessage('bot', 'Xin lỗi, đã có lỗi xảy ra khi kết nối với máy chủ AI.');
            }
        });
    });

    function appendMessage(role, text) {
        let align = role === 'user' ? 'justify-content-end' : 'justify-content-start';
        let bg = role === 'user' ? 'bg-primary text-white' : 'bg-white text-dark shadow-sm';
        let icon = role === 'user' ? 'fa-user' : 'fa-robot';
        let iconBg = role === 'user' ? 'bg-secondary' : 'bg-primary';
        
        let html = `
            <div class="message mb-3 d-flex ${align}">
                <div class="d-flex align-items-start ${role === 'user' ? 'flex-row-reverse' : ''}">
                    <div class="${iconBg} text-white rounded-circle p-2 ${role === 'user' ? 'ml-2' : 'mr-2'}" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="${bg} p-3 rounded" style="max-width: 85%; white-space: pre-wrap;">${text}</div>
                </div>
            </div>
        `;
        $('#chat-box').append(html);
        scrollToBottom();
    }

    function appendTypingIndicator(id) {
        let html = `
            <div class="message mb-3 d-flex justify-content-start" id="${id}">
                <div class="d-flex align-items-start">
                    <div class="bg-primary text-white rounded-circle p-2 mr-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="bg-white p-3 rounded shadow-sm">
                        <i class="fas fa-spinner fa-spin"></i> Đang phân tích dữ liệu...
                    </div>
                </div>
            </div>
        `;
        $('#chat-box').append(html);
        scrollToBottom();
    }

    function removeTypingIndicator(id) {
        $('#' + id).remove();
    }

    function scrollToBottom() {
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
    }
});
</script>

<style>
#chat-box::-webkit-scrollbar {
    width: 6px;
}
#chat-box::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
.message div {
    line-height: 1.5;
}
</style>

@include('layouts/parts/footer')
