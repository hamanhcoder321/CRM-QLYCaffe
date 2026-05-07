<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
        .status-pass { color: #28a745; font-weight: bold; }
        .status-fail { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>M&T Cafe Management</h2>
        </div>
        
        <p>Chào bạn <strong>{{ $application->name }}</strong>,</p>
        
        <p>Cảm ơn bạn đã quan tâm và ứng tuyển vào vị trí <strong>{{ $application->recruitment?->position?->name }}</strong> tại M&T Cafe.</p>
        
        <p>Chúng tôi xin thông báo kết quả ứng tuyển của bạn như sau:</p>
        
        @if($application->status == 1)
            <p class="status-pass">CHÚC MỪNG! Bạn đã ĐẠT trong đợt tuyển dụng này.</p>
            <p>Chúng tôi sẽ liên hệ với bạn qua số điện thoại <strong>{{ $application->phone }}</strong> để trao đổi chi tiết về công việc và thời gian bắt đầu.</p>
        @else
            <p class="status-fail">Rất tiếc, bạn KHÔNG ĐẠT trong đợt tuyển dụng này.</p>
            <p>Hồ sơ của bạn rất ấn tượng, tuy nhiên hiện tại chúng tôi đã tìm được ứng viên phù hợp hơn. Hy vọng có cơ hội hợp tác với bạn trong tương lai.</p>
        @endif
        
        <p>Trân trọng,<br>Ban quản trị M&T Cafe</p>
        
        <div class="footer">
            Đây là email tự động từ hệ thống quản lý M&T Cafe. Vui lòng không phản hồi email này.
        </div>
    </div>
</body>
</html>
