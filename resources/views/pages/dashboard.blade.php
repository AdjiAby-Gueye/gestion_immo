<div class="dashboard-container px-4 py-6">
    <!-- Header -->
    <div class="dashboard-header flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">@{{ titlePage }}</h2>
        <div class="date-display text-sm text-gray-500 mt-2 sm:mt-0">
            {{ "now"|date("d M Y") }}
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <!-- Card template -->
       
<a href="/list" class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
     <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="stat-icon mr-4 p-3 rounded-full bg-blue-50 text-blue-600">
                    <i class="fas fa-home text-xl"></i>
                </div>
                <div>
                    <div class="stat-title text-gray-500 text-sm font-medium">Appartements</div>
                    <div class="stat-value text-2xl font-bold text-gray-800">@{{ dataPage['appartements'].length || 0 }}</div>
                </div>
            </div>
        </div>
</a>
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="stat-icon mr-4 p-3 rounded-full bg-green-50 text-green-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div>
                    <div class="stat-title text-gray-500 text-sm font-medium">Immeubles</div>
                    <div class="stat-value text-2xl font-bold text-gray-800">@{{ dataPage['immeubles'].length || 0 }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="stat-icon mr-4 p-3 rounded-full bg-purple-50 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <div class="stat-title text-gray-500 text-sm font-medium">Locataires</div>
                    <div class="stat-value text-2xl font-bold text-gray-800">@{{ dataPage['locataires'].length || 0 }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="stat-icon mr-4 p-3 rounded-full bg-yellow-50 text-yellow-600">
                    <i class="fas fa-file-contract text-xl"></i>
                </div>
                <div>
                    <div class="stat-title text-gray-500 text-sm font-medium">Contrats</div>
                    <div class="stat-value text-2xl font-bold text-gray-800">@{{ dataPage['contrats'].length || 0 }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="stat-icon mr-4 p-3 rounded-full bg-red-50 text-red-600">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
                <div>
                    <div class="stat-title text-gray-500 text-sm font-medium">Utilisateurs</div>
                    <div class="stat-value text-2xl font-bold text-gray-800">@{{ dataPage['users'].length || 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>











<style>
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
}

.stat-card {
  border: 1px solid #f0f0f0;
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.6s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0s; }
.stat-card:nth-child(2) { animation-delay: 0.1s; }
.stat-card:nth-child(3) { animation-delay: 0.2s; }
.stat-card:nth-child(4) { animation-delay: 0.3s; }
.stat-card:nth-child(5) { animation-delay: 0.4s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card:hover {
  transform: translateY(-5px) scale(1.02);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon i {
  transform: rotate(10deg);
}

.stat-value {
  font-family: 'Inter', sans-serif;
}




@media (max-width: 768px) {
  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
  }
  .date-display {
    margin-top: 0.5rem;
  }
}

</style>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-card').forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
      card.classList.add('fade-in-up');
    });
  });
</script>
