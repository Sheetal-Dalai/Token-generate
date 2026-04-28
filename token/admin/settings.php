<?php include("include/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Morning Brew</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed', 100: '#ffedd5', 500: '#f97316', 
                            600: '#ea580c', 700: '#c2410c', 900: '#7c2d12',
                        },
                        stone: {
                            50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4',
                            800: '#292524', 900: '#1c1917',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-50 text-stone-800 flex h-screen overflow-hidden">

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Mobile Header -->
        <header class="bg-white border-b border-stone-200 h-20 flex items-center justify-between px-6 md:hidden">
            <span class="text-xl font-bold text-stone-800">Morning Brew</span>
            <button class="text-stone-500 hover:text-brand-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </header>

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-6 md:p-10 scrollbar-hide">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-stone-800">System Settings</h1>
                <p class="text-stone-500 mt-1">Configure your shop settings and preferences.</p>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Settings Forms -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Shop Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-semibold text-stone-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Shop Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-700 mb-1">Shop Name</label>
                                <input type="text" value="Morning Brew" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-700 mb-1">Shop Address</label>
                                <textarea rows="2" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">123 Main Street, Downtown, City, State 12345</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Phone Number</label>
                                <input type="tel" value="+1 (555) 123-4567" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Email Address</label>
                                <input type="email" value="contact@morningbrew.com" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Timezone</label>
                                <select class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white">
                                    <option>Eastern Time (ET)</option>
                                    <option>Central Time (CT)</option>
                                    <option>Mountain Time (MT)</option>
                                    <option>Pacific Time (PT)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Currency</label>
                                <select class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white">
                                    <option>USD ($)</option>
                                    <option>EUR (€)</option>
                                    <option>GBP (£)</option>
                                    <option>CAD ($)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-semibold text-stone-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Business Hours
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Monday</span>
                                <input type="time" value="06:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="18:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Tuesday</span>
                                <input type="time" value="06:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="18:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Wednesday</span>
                                <input type="time" value="06:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="18:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Thursday</span>
                                <input type="time" value="06:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="18:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Friday</span>
                                <input type="time" value="06:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="20:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Saturday</span>
                                <input type="time" value="07:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="20:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-24 text-sm font-medium text-stone-700">Sunday</span>
                                <input type="time" value="08:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span class="text-stone-400">to</span>
                                <input type="time" value="16:00" class="flex-1 border border-stone-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="h-4 w-4 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                                    <span class="text-sm text-stone-600">Open</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tax & Invoice Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-semibold text-stone-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Tax & Invoice Settings
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Tax Rate (%)</label>
                                <input type="number" value="8.5" step="0.1" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Invoice Prefix</label>
                                <input type="text" value="INV-" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-700 mb-1">Invoice Footer Note</label>
                                <textarea rows="2" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">Thank you for visiting Morning Brew! Please come again.</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Quick Settings -->
                <div class="space-y-8">
                    
                    <!-- Notifications -->
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-semibold text-stone-800 mb-4">Notifications</h3>
                        <div class="space-y-4">
                            <label class="flex items-center justify-between">
                                <span class="text-sm text-stone-700">Low Stock Alerts</span>
                                <input type="checkbox" checked class="h-5 w-5 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm text-stone-700">Daily Sales Report</span>
                                <input type="checkbox" checked class="h-5 w-5 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm text-stone-700">New Order Notifications</span>
                                <input type="checkbox" checked class="h-5 w-5 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm text-stone-700">Staff Clock-in Alerts</span>
                                <input type="checkbox" class="h-5 w-5 text-brand-600 border-stone-300 rounded focus:ring-brand-500">
                            </label>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-semibold text-stone-800 mb-4">Security</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Current Password</label>
                                <input type="password" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">New Password</label>
                                <input type="password" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Confirm Password</label>
                                <input type="password" class="w-full border border-stone-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <button class="w-full bg-stone-800 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-stone-900 transition">
                                Update Password
                            </button>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="bg-brand-900 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-semibold mb-4">System Information</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-brand-200">Version</span>
                                <span class="font-medium">2.0.1</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-200">Last Backup</span>
                                <span class="font-medium">Today, 3:00 AM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-200">Storage Used</span>
                                <span class="font-medium">2.4 GB / 10 GB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-200">Active Users</span>
                                <span class="font-medium">8</span>
                            </div>
                            <div class="pt-4 border-t border-brand-700">
                                <button class="w-full bg-brand-700 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-brand-600 transition">
                                    Backup Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <button class="w-full bg-brand-600 text-white py-3 rounded-lg font-medium hover:bg-brand-700 transition shadow-md shadow-brand-200">
                        Save All Settings
                    </button>

                </div>
            </div>

        </div>
    </main>

</body>
</html>