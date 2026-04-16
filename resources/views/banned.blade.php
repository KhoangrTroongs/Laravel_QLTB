<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tài khoản bị vô hiệu hoá</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background: #f1f5f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .banned-card { background: #fff; border-radius: 24px; padding: 3rem; text-align: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 500px; width: 90%; }
        .icon-box { font-size: 5rem; color: #ef4444; margin-bottom: 1.5rem; }
        h2 { font-weight: 800; color: #0f172a; margin-bottom: 1rem; }
        p { color: #64748b; font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem; }
    </style>
</head>
<body>
    <div class="banned-card">
        <div class="icon-box">
            <i class="fas fa-user-slash"></i>
        </div>
        <h2>Tài khoản đã bị vô hiệu hoá</h2>
        <p>Xin chào <strong>{{ Auth::user()->name }}</strong>,<br>Tài khoản của bạn hiện đã bị vô hiệu hoá hoặc đã nghỉ việc. Vui lòng liên hệ với Quản lý hệ thống để được hỗ trợ thêm.</p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-block py-3 font-weight-bold" style="border-radius: 12px;">
                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất ngay
            </button>
        </form>
    </div>
</body>
</html>
