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
        <!-- Appartements Card -->
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
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

        <!-- Immeubles Card -->
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
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

        <!-- Locataires Card -->
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
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

        <!-- Contrats Card -->
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
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

        <!-- Utilisateurs Card -->
        <div class="stat-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
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
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .stat-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1);
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