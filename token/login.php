
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token System Login - Morning Brew</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange-500
                            600: '#ea580c', // Orange-600
                            700: '#c2410c', // Orange-700
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-50 flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Background Decoration (Abstract Coffee Beans/Leaves) -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[50%] bg-brand-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] bg-yellow-100 rounded-full blur-3xl opacity-50"></div>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row m-4">
        
        <!-- Left Side: Image & Welcome -->
        <div class="md:w-1/2 relative bg-brand-900 flex flex-col justify-center items-center p-8 text-white">
            <!-- Background Image Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1000&auto=format&fit=crop" 
                     alt="Coffee and Tea" 
                     class="w-full h-full object-cover opacity-40 mix-blend-overlay">
            </div>
            
            <!-- Content -->
            <div class="relative z-10 text-center">
                <!-- Logo Icon -->
                <div class="mx-auto bg-white/20 backdrop-blur-sm w-16 h-16 rounded-full flex items-center justify-center mb-4 border border-white/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-bold mb-2">Rajasthan Mishthan Bhandar</h1>
                <p class="text-brand-100 font-medium">Taste the Royal Sweetness</p>
                <div class="mt-8 px-4 py-2 bg-white/10 rounded-lg backdrop-blur-sm border border-white/20">
                    <p class="text-sm text-brand-50">Token Generation System </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Staff Login</h2>
                <p class="text-gray-500 mt-2">Please enter your credentials to generate tokens.</p>
            </div>

            <form id="loginForm" class="space-y-6">
                <!-- Staff ID Input -->
                <div>
                    <label for="staff-id" class="block text-sm font-medium text-gray-700 mb-1">Staff ID / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" id="staff-id" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition duration-200" placeholder="e.g. STAFF-001" required>
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition duration-200" placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between">
                   
                    <div class="text-sm">
                        <a href="#" class="font-medium text-brand-600 hover:text-brand-700">Forgot password?</a>
                    </div>
                </div>

               <!-- Submit Button with Brown-Orange Gradient -->
<button type="submit" 
    class="w-full p-2 text-white font-semibold rounded-lg 
           bg-gradient-to-r from-[#6F4E37] to-[#F97316] 
           hover:from-[#7c4a2e] hover:to-[#ea580c] 
           transition-all duration-300">
    Login
</button>
            </form>

           
        </div>
    </div>
    <!-- ADD THIS BEFORE </body> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$("#loginForm").submit(function(e){
    e.preventDefault();

    let username = $("#staff-id").val();
    let password = $("#password").val();

    $.ajax({
        url: "lorus_includes/class/ajax.php",
        type: "POST",
        data: {
            action: "login",
            username: username,
            password: password
        },
        success: function(response){
            let res = JSON.parse(response);

            if(res.status === "success"){
                alert("Login Successful!");
                window.location.href = "admin/index.php";
            } else {
                alert(res.message);
            }
        }
    });
});
</script>

</body>
</html>