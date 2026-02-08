<template>
    <auth-layout />
    <div class="ps-60 pe-4 bg-[#F3F4F6] min-h-screen">
        <div class="px-8 py-4">

            <!-- Filters Section -->
            <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Search & Filters</h3>
                    <div class="flex items-center gap-2">
                        <router-link 
                            to="/prebiddings/create"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Pre-Bidding
                        </router-link>
                        <button 
                            v-if="hasActiveFilters"
                            @click="clearFilters"
                            class="text-sm text-[#0c8ce9] hover:underline font-semibold"
                        >
                            Clear All
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Project Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                v-model="filters.search"
                                type="text"
                                placeholder="Search by project..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent transition-all"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            v-model="filters.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Client</label>
                        <select 
                            v-model="filters.client"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">All Clients</option>
                            <option value="acme">Acme Corporation</option>
                            <option value="buildtech">BuildTech Solutions</option>
                            <option value="cid">City Infrastructure Development</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date Range</label>
                        <input 
                            v-model="filters.dateRange"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Items per Page</label>
                        <select 
                            v-model.number="pageSize"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Project Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bid Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr 
                                v-for="prebid in paginatedPrebiddings" 
                                :key="prebid.id"
                                class="hover:bg-blue-50 transition-all duration-200"
                            >
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-semibold text-gray-900">{{ prebid.project_name }}</div>
                                    <div class="text-xs text-gray-500">{{ prebid.project_code }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ prebid.client_name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" :class="statusStyles[prebid.status]">
                                        {{ formatStatus(prebid.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(prebid.bid_date) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div 
                                                class="h-full bg-linear-to-r from-[#0c8ce9] to-blue-600 rounded-full"
                                                :style="{ width: prebid.progress + '%' }"
                                            ></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 w-8 text-right">{{ prebid.progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            v-if="prebid.status === 'active'"
                                            @click="viewPrebid(prebid.id)"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold text-[#0c8ce9] hover:bg-blue-100 transition-all"
                                            title="View Details"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </button>
                                        <span v-else class="text-xs text-gray-400">View</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-semibold">{{ startIndex + 1 }}</span> to <span class="font-semibold">{{ Math.min(endIndex, filteredPrebiddings.length) }}</span> of <span class="font-semibold">{{ filteredPrebiddings.length }}</span> pre-biddings
                    </div>
                    <div class="flex gap-2">
                        <button 
                            @click="currentPage--" 
                            :disabled="currentPage === 1"
                            class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            Previous
                        </button>
                        <div class="flex items-center gap-1">
                            <button 
                                v-for="page in visiblePages" 
                                :key="page"
                                @click="currentPage = page"
                                :class="currentPage === page ? 'bg-[#0c8ce9] text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'"
                                class="px-3 py-2 rounded-lg text-sm font-semibold transition-all"
                            >
                                {{ page }}
                            </button>
                        </div>
                        <button 
                            @click="currentPage++" 
                            :disabled="currentPage === totalPages"
                            class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import AuthLayout from '@/views/Layouts/AuthLayout.vue'

const router = useRouter()

// Filters
const filters = ref({
    search: '',
    status: '',
    client: '',
    dateRange: ''
})

const currentPage = ref(1)
const pageSize = ref(10)

// Status Styles
const statusStyles = {
    'active': 'bg-green-100 text-green-800',
    'draft': 'bg-gray-100 text-gray-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'rejected': 'bg-red-100 text-red-800'
}

// Mock data
const prebiddings = ref([
    {
        id: 1,
        project_name: 'Office Building Renovation',
        project_code: 'PRJ-001',
        client_name: 'Acme Corporation',
        status: 'active',
        bid_date: '2024-02-01',
        progress: 75
    },
    {
        id: 2,
        project_name: 'Infrastructure Development',
        project_code: 'PRJ-002',
        client_name: 'BuildTech Solutions',
        status: 'pending',
        bid_date: '2024-01-15',
        progress: 45
    },
    {
        id: 3,
        project_name: 'Supply Chain Optimization',
        project_code: 'PRJ-003',
        client_name: 'City Infrastructure Development',
        status: 'draft',
        bid_date: '2024-02-10',
        progress: 20
    },
    {
        id: 4,
        project_name: 'Facility Upgrade',
        project_code: 'PRJ-004',
        client_name: 'Acme Corporation',
        status: 'active',
        bid_date: '2024-01-20',
        progress: 90
    },
    {
        id: 5,
        project_name: 'Risk Assessment',
        project_code: 'PRJ-005',
        client_name: 'BuildTech Solutions',
        status: 'rejected',
        bid_date: '2023-12-15',
        progress: 100
    }
])

// Computed Properties
const filteredPrebiddings = computed(() => {
    return prebiddings.value.filter(pb => {
        const matchesSearch = pb.project_name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
                             pb.project_code.toLowerCase().includes(filters.value.search.toLowerCase())
        const matchesStatus = !filters.value.status || pb.status === filters.value.status
        const matchesClient = !filters.value.client || pb.client_name.toLowerCase().includes(filters.value.client.toLowerCase())

        return matchesSearch && matchesStatus && matchesClient
    })
})

const hasActiveFilters = computed(() => {
    return filters.value.search || filters.value.status || filters.value.client || filters.value.dateRange
})

const totalPages = computed(() => Math.ceil(filteredPrebiddings.value.length / pageSize.value))

const startIndex = computed(() => (currentPage.value - 1) * pageSize.value)
const endIndex = computed(() => startIndex.value + pageSize.value)

const paginatedPrebiddings = computed(() => {
    return filteredPrebiddings.value.slice(startIndex.value, endIndex.value)
})

const visiblePages = computed(() => {
    const pages = []
    const maxVisible = 5
    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
    let end = Math.min(totalPages.value, start + maxVisible - 1)
    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1)
    }
    for (let i = start; i <= end; i++) {
        pages.push(i)
    }
    return pages
})

// Methods
const clearFilters = () => {
    filters.value = {
        search: '',
        status: '',
        client: '',
        dateRange: ''
    }
    currentPage.value = 1
}

const formatStatus = (status) => {
    const map = {
        'active': 'Active',
        'draft': 'Draft',
        'pending': 'Pending',
        'rejected': 'Rejected'
    }
    return map[status] || status
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const viewPrebid = (id) => {
    router.push(`/prebiddings/${id}`)
}
</script>
