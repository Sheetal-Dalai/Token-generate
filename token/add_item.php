!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Item - Restaurant</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Add New Item</h2>
    
    <form id="addItemForm" class="flex flex-col gap-4">
      <!-- Item Name -->
      <input type="text" placeholder="Item Name" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>

      <!-- Item Image -->
      <input type="file" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>

  

      <!-- Price -->
      <input type="number" placeholder="Price per unit" step="0.01" min="0" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>

      <!-- Submit Button -->
      <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded transition">Add Item</button>
    </form>

    <!-- Dummy Response -->
    <p id="responseMsg" class="text-center mt-4 text-green-600 font-medium hidden">Item added successfully!</p>
  </div>

  <script>
    // Dummy working submission
    const form = document.getElementById('addItemForm');
    const responseMsg = document.getElementById('responseMsg');

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      responseMsg.classList.remove('hidden');

      // Reset form after submission
      setTimeout(() => {
        form.reset();
        responseMsg.classList.add('hidden');
      }, 2000);
    });
  </script>

</body>
</html>