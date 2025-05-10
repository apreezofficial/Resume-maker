
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mad AI Resume Builder</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/framer-motion/dist/framer-motion.umd.js"></script>
  <style>
    .dot-loader div {
      width: 8px;
      height: 8px;
      background-color: #3b82f6;
      margin: 2px;
      border-radius: 50%;
      animation: bounce 0.6s infinite alternate;
    }

    .dot-loader div:nth-child(2) {
      animation-delay: 0.2s;
    }

    .dot-loader div:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes bounce {
      to {
        transform: translateY(-10px);
      }
    }
  </style>
</head>

<body class="bg-blue-50 min-h-screen flex flex-col items-center justify-center text-gray-800 p-6">
  <div class="max-w-3xl w-full bg-white p-6 rounded-2xl shadow-xl relative">
    <h1 class="text-3xl font-bold mb-4 text-blue-600">Mad AI Resume Builder</h1>
    <form id="resumeForm" class="space-y-4">
      <!-- Step-by-step input -->
      <div class="step">
        <label class="block text-sm font-medium">Full Name</label>
        <input type="text" name="name" id="name" class="w-full p-2 border rounded-xl" required>
      </div>

      <div class="step">
        <label class="block text-sm font-medium">Profession</label>
        <input type="text" name="profession" id="profession" class="w-full p-2 border rounded-xl">
      </div>

      <div class="step">
        <label class="block text-sm font-medium">Portfolio URL</label>
        <input type="url" name="portfolio" id="portfolio" class="w-full p-2 border rounded-xl">
      </div>

      <div class="step">
        <label class="block text-sm font-medium">Skills (comma separated)</label>
        <input type="text" name="skills" id="skills" class="w-full p-2 border rounded-xl">
      </div>

      <div class="step">
        <label class="block text-sm font-medium">Experience Summary</label>
        <textarea name="experience" id="experience" rows="4" class="w-full p-2 border rounded-xl"></textarea>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">Build Resume</button>
    </form>

    <!-- Loading state -->
    <div id="loadingState" class="hidden flex flex-col items-center justify-center mt-6">
      <img src="https://logo.image.pollinations.ai/prompt/resumemaker" class="w-16 h-16 animate-spin mb-2">
      <p class="text-sm text-gray-600">Crafting the resume with AI magic...</p>
      <div class="dot-loader flex mt-2">
        <div></div><div></div><div></div>
      </div>
    </div>

    <!-- Result + Popup -->
    <div id="resumeResult" class="hidden mt-6">
      <textarea id="resumeText" class="w-full p-4 border rounded-xl h-80"></textarea>
      <div class="flex mt-4 items-center space-x-2">
        <input type="text" id="fileName" placeholder="Filename (e.g. Dave)" class="p-2 border rounded-xl">
        <select id="fileFormat" class="p-2 border rounded-xl">
          <option value="txt">.txt</option>
          <option value="doc">.doc</option>
          <option value="pdf">.pdf</option>
        </select>
        <button onclick="downloadFile()" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">Download</button>
      </div>
    </div>
  </div>

  <script>
  // Replace with your actual Google API key and Custom Search Engine ID
const GOOGLE_API_KEY = '**'; // Replace with actual Google API key
const CSX_API_KEY = '**'; // 
const CSX_API_URL = 'https://www.googleapis.com/customsearch/v1'; // Google Custom Search API endpoint
    // Fill form with scraped data
    const scrapeData = {
      name: "",
      profession: "",
      portfolio: "",
      skills: "",
      experience: ""
    };

    document.getElementById('name').value = scrapeData.name;
    document.getElementById('profession').value = scrapeData.profession;
    document.getElementById('portfolio').value = scrapeData.portfolio;
    document.getElementById('skills').value = scrapeData.skills;
    document.getElementById('experience').value = scrapeData.experience;

    const form = document.getElementById('resumeForm');
    const loading = document.getElementById('loadingState');
    const result = document.getElementById('resumeResult');
    const resumeText = document.getElementById('resumeText');

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      loading.classList.remove('hidden');
      result.classList.add('hidden');

      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());
      let text = `Generate a resume in professional format. Name: ${data.name}, Profession: ${data.profession}. Skills: ${data.skills}. Experience: ${data.experience}. Check this portfolio too: ${data.portfolio}.`;

      const encoded = encodeURIComponent("System: You are a resume writing AI. Be professional, stylish, and straight to the point. \nUser: " + text);
      const aiUrl = `https://text.pollinations.ai/${encoded}`;

      try {
        const res = await fetch(aiUrl);
        const json = await res.json();
        const output = json.text || "Something went wrong generating the resume.";
        resumeText.value = output;
        loading.classList.add('hidden');
        result.classList.remove('hidden');
      } catch (err) {
        resumeText.value = "Failed to fetch resume. Check your network or API.";
        loading.classList.add('hidden');
        result.classList.remove('hidden');
      }
    });

    function downloadFile() {
      const name = document.getElementById('fileName').value || 'resume';
      const ext = document.getElementById('fileFormat').value;
      const blob = new Blob([resumeText.value], { type: 'text/plain;charset=utf-8' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = `${name}.${ext}`;
      link.click();
    }
  </script>
</body>

</html>
