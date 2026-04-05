<x-guest-layout>

@push('style')
<style>
:root {
  --primary: #6f4e37;
  --primary-hover: #5a3f2d;
  --bg: #f5f5f5;
  --text: #2c2c2c;
}

/* RESET */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: var(--bg);
}

/* LAYOUT */
.container-login {
  display: flex;
  min-height: 100vh;
}

/* LEFT SIDE */
.left {
  flex: 1;
  background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93') center/cover no-repeat;
  position: relative;
}

.left::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.6);
}

.left-content {
  position: absolute;
  bottom: 50px;
  left: 50px;
  color: white;
  max-width: 400px;
}

.left h2 {
  margin: 0;
  font-size: 32px;
}

.left p {
  margin-top: 10px;
  font-size: 15px;
  opacity: 0.9;
}

/* RIGHT SIDE */
.right {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #ffffff;
}

/* CARD */
.card {
  width: 380px;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* TITLE */
h1 {
  text-align: center;
  margin-bottom: 20px;
  color: var(--text);
}

/* INPUT */
.input {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  margin-bottom: 10px;
  font-size: 14px;
}

.input:focus {
  border-color: var(--primary);
  outline: none;
}

/* ERROR */
.text-red-600 {
  color: red;
  font-size: 13px;
  margin-bottom: 10px;
}

/* ERROR BOX */
.input-error-wrapper {
  margin-bottom: 8px;
}

@keyframes slideInError {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.input-error-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fff1f1;
  border-left: 3px solid #e53e3e;
  border-radius: 6px;
  padding: 8px 12px;
  margin-top: 6px;
  font-size: 13px;
  color: #c53030;
  font-weight: 500;
  animation: slideInError 0.25s ease;
  box-shadow: 0 1px 4px rgba(229, 62, 62, 0.12);
}

/* CHECKBOX */
.check {
  margin: 10px 0 15px;
  font-size: 14px;
}

/* BUTTON */
.btn {
  width: 100%;
  padding: 12px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.2s;
}

.btn:hover {
  background: var(--primary-hover);
}


.alert-error-wrap {
  margin-bottom: 14px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.alert-error {
  background: #fff1f1;
  border-left: 3px solid #e53e3e;
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 13px;
  color: #c53030;
  font-weight: 500;
  animation: slideInError 0.25s ease;
  box-shadow: 0 1px 4px rgba(229, 62, 62, 0.12);
}


.help {
  margin-top: 10px;
  text-align: center;
  font-size: 13px;
  color: #777;
}


@media(max-width: 768px) {
  .left {
    display: none;
  }
}
</style>
@endpush

<div class="container-login">

    <!-- LEFT -->
    <div class="left">
        <div class="left-content">
            <h2>Hệ thống quản lý quán cafe</h2>
           
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="card">

            <h1>Đăng nhập hệ thống</h1>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Một block lỗi duy nhất: hiện tất cả lỗi không trùng --}}
                @if ($errors->any())
                    <div class="alert-error-wrap">
                        @foreach (array_unique($errors->all()) as $error)
                            <div class="alert-error">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div style="margin-top: 4px; margin-left: 2px;">

                <!-- Email -->
                <x-text-input 
                    id="email" 
                    class="input" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    autofocus 
                    autocomplete="username" 
                    placeholder="Nhập email..."
                />

                <!-- Password -->
                <x-text-input 
                    id="password" 
                    class="input" 
                    type="password" 
                    name="password"
                    autocomplete="current-password" 
                    placeholder="Nhập mật khẩu..."
                />

                <!-- Remember -->
                <div class="check">
                    <input id="remember" type="checkbox" name="remember" />
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <!-- Button -->
                <button type="submit" class="btn">
                    Đăng nhập
                </button>

                <p class="help">
                    Hỗ trợ kỹ thuật: 0123456689 (Zalo)
                </p>

            </div>
            </form>

        </div>
    </div>

</div>

</x-guest-layout>
