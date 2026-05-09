<?php
namespace App\Modules\AI\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseAIService
{
    protected $ai;

    public function __construct()
    {
        $this->ai = new OpenAIService();
    }

    /**
     * Mô tả cấu trúc Database cho AI
     */
    public function getDatabaseSchema()
    {
        // Lấy dữ liệu thực tế
        $branchNames = DB::table('branches')->pluck('name')->toArray();
        $positionNames = DB::table('positions')->pluck('name')->toArray();
        $partNames = DB::table('parts')->pluck('name')->toArray();

        $tables = [
            'users' => 'Nhân viên. Cột: name, email, branch_id, part_id, position_id, status(0:làm).',
            'branches' => 'Chi nhánh. Cột: id, name.',
            'parts' => 'Bộ phận (Ví dụ: Pha chế, Phục vụ). Cột: id, name.',
            'positions' => 'Chức vụ (Ví dụ: Quản lý, Admin). Cột: id, name.',
            'sells' => 'Doanh thu. Cột: shipment_revenue, sell_day, branch_id, status(1).',
            'total_fees' => 'Chi phí. Cột: money, day, branch_id.',
            'products' => 'Sản phẩm. Cột: name, number_in, number_out, shipment_id.',
            'storages' => 'Kho. Cột: shipment_id, branch_id.',
        ];

        $schemaText = "DANH SÁCH CHI NHÁNH: " . implode(', ', $branchNames) . "\n";
        $schemaText .= "DANH SÁCH BỘ PHẬN (Parts): " . implode(', ', $partNames) . "\n";
        $schemaText .= "DANH SÁCH CHỨC VỤ (Positions): " . implode(', ', $positionNames) . "\n\n";
        
        $schemaText .= "MỐI QUAN HỆ CHUẨN:\n";
        $schemaText .= "- users JOIN parts ON users.part_id = parts.id\n";
        $schemaText .= "- users JOIN positions ON users.position_id = positions.id\n";
        $schemaText .= "- users JOIN branches ON users.branch_id = branches.id\n";
        $schemaText .= "- Xem tồn kho: products JOIN storages ON products.shipment_id = storages.shipment_id JOIN branches ON storages.branch_id = branches.id\n\n";

        foreach ($tables as $table => $desc) {
            $columns = Schema::getColumnListing($table);
            $schemaText .= "- Bảng `{$table}` ({$desc}): " . implode(', ', $columns) . "\n";
        }

        return $schemaText;
    }

    /**
     * Nhận câu hỏi và thực thi SQL trả về kết quả
     */
    public function askDatabase(string $question)
    {
        $schema = $this->getDatabaseSchema();
        
        $systemPrompt = "Bạn là trợ lý dữ liệu CRM Café. 
NHIỆM VỤ: Chuyển câu hỏi người dùng thành 1 câu lệnh SQL SELECT.

QUY TẮC BẮT BUỘC:
1. Nếu dùng subquery trong SELECT, phải có từ khóa SELECT: (SELECT SUM(...) FROM ...).
2. Doanh thu: SUM(shipment_revenue) FROM sells WHERE status = 1.
3. Chi phí: SUM(money) FROM total_fees.
4. Thời gian 'tháng này': WHERE MONTH(column) = MONTH(CURRENT_DATE) AND YEAR(column) = YEAR(CURRENT_DATE).
5. Luôn dùng JOIN branches ON ... = branches.id để lọc hoặc hiển thị tên chi nhánh.
6. Nếu người dùng hỏi 'chi tiết', hãy hiển thị danh sách các hàng dữ liệu (ví dụ: SELECT * FROM branches).
7. Trả về SQL thuần, không markdown, không giải thích.";

        $prompt = "DATABASE SCHEMA:\n{$schema}\n\nCÂU HỎI: {$question}\n\nSQL:";
        
        $response = $this->ai->chat($prompt, $systemPrompt);
        
        // Trích xuất SQL
        preg_match('/SELECT(.*)/is', $response, $matches);
        $sql = $matches[0] ?? $response;
        $sql = str_replace(['```sql', '```', ';'], '', trim($sql));
        
        // Sửa lỗi phổ biến: AI quên SELECT trong ngoặc
        $sql = preg_replace('/\((SUM|COUNT|AVG)/i', '(SELECT $1', $sql);

        if (strtoupper(substr($sql, 0, 6)) !== 'SELECT') {
            return "Tôi không tìm thấy thông tin này. Bạn hãy thử hỏi cụ thể hơn nhé.";
        }

        try {
            if (stripos($sql, 'LIMIT') === false && stripos($sql, 'COUNT') === false && stripos($sql, 'SUM') === false) {
                $sql .= " LIMIT 50";
            }

            $results = DB::select($sql);
            
            if (empty($results) || (count($results) == 1 && array_values((array)$results[0])[0] === null)) {
                return "Hiện tại chưa có dữ liệu cho yêu cầu này của bạn.";
            }

            $resultJson = json_encode($results, JSON_UNESCAPED_UNICODE);
            $answerPrompt = "Dựa trên dữ liệu SQL sau, hãy trả lời câu hỏi của người dùng. 
Nếu là danh sách -> Hãy trình bày dạng bảng hoặc liệt kê rõ ràng.
CÂU HỎI: {$question}
DỮ LIỆU: {$resultJson}";

            return $this->ai->chat($answerPrompt, "Bạn là trợ lý AI thông minh.");

        } catch (\Throwable $e) {
            return "Lỗi hệ thống: " . $e->getMessage() . "\n(Lệnh SQL lỗi: " . $sql . ")";
        }
    }
}
