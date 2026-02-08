<template>
    <auth-layout />
    <div class="ps-60 bg-[#F3F4F6] min-h-screen">
        <div class="px-10 py-10">

            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-white">
                    <div class="flex">
                        <button
                            @click="activeTab = 'biddings-won'"
                            :class="[
                                'px-6 py-4 text-sm font-semibold transition-colors border-b-2',
                                activeTab === 'biddings-won'
                                    ? 'text-[#0c8ce9] border-[#0c8ce9] bg-white'
                                    : 'text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            Biddings Won
                        </button>
                        <button
                            @click="activeTab = 'ongoing'"
                            :class="[
                                'px-6 py-4 text-sm font-semibold transition-colors border-b-2',
                                activeTab === 'ongoing'
                                    ? 'text-[#0c8ce9] border-[#0c8ce9] bg-white'
                                    : 'text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            Ongoing Projects
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Total Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ upcomingProjects.length }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-[#0c8ce9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">In Progress</p>
                            <p class="text-2xl font-bold text-blue-600">{{ inProgressCount }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Pending Setup</p>
                            <p class="text-2xl font-bold text-amber-600">{{ pendingSetupCount }}</p>
                        </div>
                        <div class="bg-amber-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Completed</p>
                            <p class="text-2xl font-bold text-green-600">{{ completedCount }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div v-if="activeTab === 'biddings-won'" class="bg-white rounded-lg p-6 mb-6 border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Filters</h3>
                    <button 
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="text-sm text-[#0c8ce9] hover:underline font-semibold"
                    >
                        Clear All
                    </button>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Search</label>
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
                        <label class="block text-xs font-medium text-gray-700 mb-2">Client</label>
                        <select 
                            v-model="filters.client"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">All Clients</option>
                            <option value="ABC Corporation">ABC Corporation</option>
                            <option value="XYZ Holdings">XYZ Holdings</option>
                            <option value="Global Construction Inc">Global Construction Inc</option>
                            <option value="Metro Development Corp">Metro Development Corp</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            v-model="filters.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">All Status</option>
                            <option value="pending-setup">Pending Setup</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Sort By</label>
                        <select 
                            v-model="filters.sortBy"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="start-date">Start Date</option>
                            <option value="end-date">End Date</option>
                            <option value="budget">Budget</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Biddings Won Tab -->
            <div v-if="activeTab === 'biddings-won'" class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Project</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Client</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Budget</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Date Bidding Won</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Start Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="project in filteredProjects" :key="project.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ project.project_name }}</p>
                                    <p class="text-xs text-gray-600">{{ project.project_code }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ project.client_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-semibold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ formatDate(project.bidding_won_date) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ formatDate(project.start_date) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
                                    approvalStatusStyles[project.approval_status]
                                ]">
                                    {{ formatApprovalStatus(project.approval_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    @click="viewProject(project.id)"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-[#0c8ce9] hover:bg-blue-50 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="filteredProjects.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-900 mb-2">No Projects</p>
                    <p class="text-sm text-gray-600">No projects match your filters.</p>
                </div>
            </div>

            <!-- Ongoing Projects Tab -->
            <div v-if="activeTab === 'ongoing'" class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Project</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Client</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Budget</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Milestone</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="project in approvedOngoingProjects" :key="project.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ project.project_name }}</p>
                                    <p class="text-xs text-gray-600">{{ project.project_code }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ project.client_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-semibold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-[#0c8ce9] h-2 rounded-full" :style="{ width: project.progress + '%' }"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600">{{ project.progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 font-medium">{{ project.current_milestone }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    @click="viewProject(project.id)"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-[#0c8ce9] hover:bg-blue-50 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="approvedOngoingProjects.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-900 mb-2">No Ongoing Projects</p>
                    <p class="text-sm text-gray-600">No approved ongoing projects at the moment.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

const router = useRouter()

const activeTab = ref('biddings-won')

const filters = ref({
    search: '',
    client: '',
    status: '',
    sortBy: 'start-date'
})

const statusStyles = {
    'pending-setup': 'bg-amber-100 text-amber-800 border border-amber-300',
    'in-progress': 'bg-blue-100 text-blue-800 border border-blue-300',
    'completed': 'bg-green-100 text-green-800 border border-green-300'
}

const approvalStatusStyles = {
    'pending': 'bg-amber-100 text-amber-800 border border-amber-300',
    'approved': 'bg-green-100 text-green-800 border border-green-300',
    'rejected': 'bg-red-100 text-red-800 border border-red-300'
}

const upcomingProjects = ref([
    {
        id: 1,
        project_name: 'Office Building Renovation',
        project_code: 'PRJ-2026-001',
        client_name: 'ABC Corporation',
        status: 'pending-setup',
        total_budget: 2500000,
        progress: 5,
        start_date: '2026-02-15',
        end_date: '2026-08-15',
        notice_of_award_file: 'noa_project_001.pdf',
        current_milestone: 'Site Preparation',
        bidding_won_date: '2026-02-01',
        approval_status: 'pending'
    },
    {
        id: 2,
        project_name: 'Infrastructure Development Project',
        project_code: 'PRJ-2026-002',
        client_name: 'XYZ Holdings',
        status: 'in-progress',
        total_budget: 5000000,
        progress: 45,
        start_date: '2026-01-15',
        end_date: '2026-12-31',
        notice_of_award_file: 'noa_project_002.pdf',
        current_milestone: 'Foundation Work',
        bidding_won_date: '2025-12-15',
        approval_status: 'approved'
    },
    {
        id: 3,
        project_name: 'Warehouse Automation System',
        project_code: 'PRJ-2026-003',
        client_name: 'Global Construction Inc',
        status: 'pending-setup',
        total_budget: 1200000,
        progress: 2,
        start_date: '2026-03-01',
        end_date: '2026-09-30',
        notice_of_award_file: 'noa_project_003.pdf',
        current_milestone: 'Planning Phase',
        bidding_won_date: '2026-01-20',
        approval_status: 'pending'
    },
    {
        id: 4,
        project_name: 'Corporate Training Center Construction',
        project_code: 'PRJ-2026-004',
        client_name: 'Metro Development Corp',
        status: 'in-progress',
        total_budget: 3500000,
        progress: 60,
        start_date: '2026-01-01',
        end_date: '2026-10-30',
        notice_of_award_file: 'noa_project_004.pdf',
        current_milestone: 'Structural Framework',
        bidding_won_date: '2025-11-15',
        approval_status: 'approved'
    },
    {
        id: 5,
        project_name: 'Data Center Expansion',
        project_code: 'PRJ-2026-005',
        client_name: 'ABC Corporation',
        status: 'completed',
        total_budget: 4200000,
        progress: 100,
        start_date: '2025-08-01',
        end_date: '2026-01-31',
        notice_of_award_file: 'noa_project_005.pdf',
        current_milestone: 'Completed',
        bidding_won_date: '2025-07-10',
        approval_status: 'approved'
    }
])

const filteredProjects = computed(() => {
    let results = upcomingProjects.value.filter(proj => {
        const matchesSearch = proj.project_name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
                             proj.project_code.toLowerCase().includes(filters.value.search.toLowerCase())
        const matchesClient = !filters.value.client || proj.client_name === filters.value.client
        const matchesStatus = !filters.value.status || proj.status === filters.value.status

        return matchesSearch && matchesClient && matchesStatus
    })

    // Sort
    if (filters.value.sortBy === 'start-date') {
        results.sort((a, b) => new Date(a.start_date) - new Date(b.start_date))
    } else if (filters.value.sortBy === 'end-date') {
        results.sort((a, b) => new Date(a.end_date) - new Date(b.end_date))
    } else if (filters.value.sortBy === 'budget') {
        results.sort((a, b) => b.total_budget - a.total_budget)
    }

    return results
})

const hasActiveFilters = computed(() => {
    return filters.value.search || filters.value.client || filters.value.status
})

const inProgressCount = computed(() => {
    return upcomingProjects.value.filter(proj => proj.status === 'in-progress').length
})

const pendingSetupCount = computed(() => {
    return upcomingProjects.value.filter(proj => proj.status === 'pending-setup').length
})

const completedCount = computed(() => {
    return upcomingProjects.value.filter(proj => proj.status === 'completed').length
})

const ongoingProjects = computed(() => {
    return upcomingProjects.value.filter(proj => proj.status === 'in-progress')
})

const approvedOngoingProjects = computed(() => {
    return upcomingProjects.value.filter(proj => proj.status === 'in-progress' && proj.approval_status === 'approved')
})

const clearFilters = () => {
    filters.value = {
        search: '',
        client: '',
        status: '',
        sortBy: 'start-date'
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const formatCurrency = (value) => {
    return parseFloat(value).toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
}

const formatStatus = (status) => {
    const map = {
        'pending-setup': 'Pending Setup',
        'in-progress': 'In Progress',
        'completed': 'Completed'
    }
    return map[status] || status
}

const formatApprovalStatus = (status) => {
    const map = {
        'pending': 'Pending',
        'approved': 'Approved',
        'rejected': 'Rejected'
    }
    return map[status] || status
}

const viewProject = (id) => {
    if (activeTab.value === 'ongoing') {
        router.push(`/projects/${id}/monitoring`)
        return
    }

    router.push(`/projects/${id}`)
}

onMounted(() => {
    auth.pageTitle = 'Projects'
})
</script>