<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Admin OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind with dark mode support -->
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800 dark:text-white">Enter OTP</h2>

        @if (session('status'))
            <div class="mb-4 text-green-600 dark:text-green-400 text-sm">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-4 text-red-500 dark:text-red-400 text-sm">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-500 dark:text-red-400 text-sm">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.otp.verify') }}" class="mb-4">
            @csrf
            <input type="text" name="otp_code" placeholder="6-digit OTP"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded mb-4" required autofocus>
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition">
                Verify OTP
            </button>
        </form>

        <form method="POST" action="{{ route('admin.otp.resend') }}">
            @csrf
            <button id="resendBtn" type="submit"
                    class="w-full bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white py-2 rounded transition">
                Resend OTP
            </button>
        </form>

        <span id="timerText" class="text-sm text-center text-gray-600 dark:text-gray-400 mt-2 block"></span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resendBtn = document.getElementById('resendBtn');
            const timerText = document.getElementById('timerText');
            const cooldownSeconds = 60;

            // Start timer immediately when page loads
            startCountdown(cooldownSeconds);

            function startCountdown(seconds) {
                resendBtn.disabled = true;
                let remaining = seconds;

                timerText.textContent = `Please wait ${remaining}s before resending`;

                const interval = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(interval);
                        resendBtn.disabled = false;
                        timerText.textContent = '';
                    } else {
                        timerText.textContent = `Please wait ${remaining}s before resending`;
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>
