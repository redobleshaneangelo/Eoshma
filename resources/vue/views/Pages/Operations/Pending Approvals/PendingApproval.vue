<template>
    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Total Pre-Biddings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ pendingApprovals.length }}</p>
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
                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Urgent</p>
                    <p class="text-2xl font-bold text-red-600">{{ urgentCount }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">This Week</p>
                    <p class="text-2xl font-bold text-amber-600">{{ thisWeekCount }}</p>
                </div>
                <div class="bg-amber-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Pending Approval</p>
                    <p class="text-2xl font-bold text-green-600">{{ pendingApprovalCount }}</p>
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
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200 shadow-sm">
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
                <label class="block text-xs font-medium text-gray-700 mb-2">Priority</label>
                <select 
                    v-model="filters.priority"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                >
                    <option value="">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="normal">Normal</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Sort By</label>
                <select 
                    v-model="filters.sortBy"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                >
                    <option value="deadline">Deadline (Nearest)</option>
                    <option value="created">Recently Created</option>
                    <option value="priority">Priority</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Request Cards Grid -->
    <div class="grid grid-cols-1 gap-6">
        <div 
            v-for="request in filteredRequests" 
            :key="request.id"
            class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden"
        >
            <!-- Card Header -->
            <div class="bg-linear-to-r from-[#0c8ce9] to-blue-600 px-6 py-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-white mb-1">{{ request.project_name }}</h3>
                        <p class="text-xs text-blue-100 font-semibold">{{ request.project_code }}</p>
                    </div>
                    <span 
                        :class="[
                            'px-3 py-1 rounded-full text-xs font-bold',
                            priorityStyles[request.priority]
                        ]"
                    >
                        {{ request.priority.toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-6 py-4">
                <!-- Client & Submitted Info -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ request.client_name }}
                    </span>
                    <span class="text-xs text-gray-500">Submitted {{ formatDate(request.submitted_at) }}</span>
                </div>

                <!-- Deadline -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-semibold text-gray-700">Bidding Deadline</span>
                    </div>
                    <p :class="[
                        'text-sm font-bold',
                        isDeadlineUrgent(request.deadline) ? 'text-red-600' : 'text-gray-900'
                    ]">
                        {{ formatDate(request.deadline) }}
                        <span v-if="isDeadlineUrgent(request.deadline)" class="ml-2 text-xs">({{ daysUntil(request.deadline) }} days left)</span>
                    </p>
                </div>

                <!-- Submitted By -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs font-semibold text-gray-700">Submitted By</span>
                    </div>
                    <p class="text-sm text-gray-900">{{ request.submitted_by }}</p>
                </div>

                <!-- Budget Breakdown -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-semibold text-gray-700">Budget Breakdown</span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Materials:</span>
                            <span class="font-semibold text-gray-900">₱{{ parseFloat(request.materials_cost).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Equipment:</span>
                            <span class="font-semibold text-gray-900">₱{{ parseFloat(request.equipment_cost).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Labor:</span>
                            <span class="font-semibold text-gray-900">₱{{ parseFloat(request.labor_cost).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Subcontractor:</span>
                            <span class="font-semibold text-gray-900">₱{{ parseFloat(request.subcontractor_cost).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Miscellaneous:</span>
                            <span class="font-semibold text-gray-900">₱{{ parseFloat(request.miscellaneous_cost).toLocaleString() }}</span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-sm font-bold text-gray-900">Total Budget:</span>
                                <span class="text-sm font-bold text-[#0c8ce9]">₱{{ parseFloat(request.total_budget).toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-xs font-semibold text-gray-700">{{ request.document_count }} Documents Attached</span>
                    </div>
                </div>

                <!-- Description -->
                <div v-if="request.description" class="mb-4">
                    <p class="text-xs text-gray-600 line-clamp-2">{{ request.description }}</p>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <button 
                    @click="viewDetails(request.id)"
                    class="w-full px-4 py-2 bg-[#0c8ce9] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all"
                >
                    View Full Details
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredRequests.length === 0" class="bg-white rounded-lg border border-gray-200 shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-lg font-semibold text-gray-900 mb-2">No Pending Pre-Biddings</p>
        <p class="text-sm text-gray-600">All pre-bidding requests have been approved or no requests match your filters.</p>
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
    client: '',
    priority: '',
    sortBy: 'deadline'
})

// Priority Styles
const priorityStyles = {
    'urgent': 'bg-red-100 text-red-800 border border-red-300',
    'high': 'bg-orange-100 text-orange-800 border border-orange-300',
    'normal': 'bg-blue-100 text-blue-800 border border-blue-300',
    'low': 'bg-gray-100 text-gray-800 border border-gray-300'
}

// Mock Data - Pre-Bidding Requests pending approval
const pendingApprovals = ref([
    {
        id: 1,
        project_name: 'Office Building Renovation',
        project_code: 'PRJ-2026-001',
        client_name: 'ABC Corporation',
        submitted_by: 'Maria Santos',
        deadline: '2026-02-15',
        priority: 'urgent',
        total_budget: 2500000,
        materials_cost: 800000,
        equipment_cost: 450000,
        labor_cost: 650000,
        subcontractor_cost: 400000,
        miscellaneous_cost: 200000,
        status: 'Pending Approval',
        description: 'Complete renovation of main office building including electrical, plumbing, and interior design updates.',
        submitted_at: '2026-02-01',
        document_count: 8
    },
    {
        id: 2,
        project_name: 'Infrastructure Development Project',
        project_code: 'PRJ-2026-002',
        client_name: 'XYZ Holdings',
        submitted_by: 'John Reyes',
        deadline: '2026-02-20',
        priority: 'high',
        total_budget: 5000000,
        materials_cost: 1800000,
        equipment_cost: 1200000,
        labor_cost: 1200000,
        subcontractor_cost: 600000,
        miscellaneous_cost: 200000,
        status: 'Pending Approval',
        description: 'Road expansion and drainage system improvement for the industrial park area.',
        submitted_at: '2026-02-02',
        document_count: 12
    },
    {
        id: 3,
        project_name: 'Warehouse Automation System',
        project_code: 'PRJ-2026-003',
        client_name: 'Global Construction Inc',
        submitted_by: 'Anna Cruz',
        deadline: '2026-02-18',
        priority: 'normal',
        total_budget: 1200000,
        materials_cost: 300000,
        equipment_cost: 500000,
        labor_cost: 250000,
        subcontractor_cost: 100000,
        miscellaneous_cost: 50000,
        status: 'Pending Approval',
        description: 'Warehouse automation and inventory management system implementation.',
        submitted_at: '2026-01-30',
        document_count: 6
    },
    {
        id: 4,
        project_name: 'Corporate Training Center Construction',
        project_code: 'PRJ-2026-004',
        client_name: 'Metro Development Corp',
        submitted_by: 'Carlos Mendoza',
        deadline: '2026-03-01',
        priority: 'normal',
        total_budget: 3500000,
        materials_cost: 1200000,
        equipment_cost: 600000,
        labor_cost: 1000000,
        subcontractor_cost: 500000,
        miscellaneous_cost: 200000,
        status: 'Pending Approval',
        description: 'Construction of new training facility with modern amenities and equipment.',
        submitted_at: '2026-02-03',
        document_count: 10
    },
    {
        id: 5,
        project_name: 'Data Center Expansion',
        project_code: 'PRJ-2026-005',
        client_name: 'ABC Corporation',
        submitted_by: 'Lisa Gomez',
        deadline: '2026-02-12',
        priority: 'urgent',
        total_budget: 4200000,
        materials_cost: 1000000,
        equipment_cost: 1800000,
        labor_cost: 800000,
        subcontractor_cost: 400000,
        miscellaneous_cost: 200000,
        status: 'Pending Approval',
        description: 'Server room expansion and cooling system upgrade for financial systems.',
        submitted_at: '2026-02-04',
        document_count: 9
    }
])

// Computed Properties
const filteredRequests = computed(() => {
    let results = pendingApprovals.value.filter(req => {
        const matchesSearch = req.project_name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
                             req.project_code.toLowerCase().includes(filters.value.search.toLowerCase())
        const matchesClient = !filters.value.client || req.client_name === filters.value.client
        const matchesPriority = !filters.value.priority || req.priority === filters.value.priority

        return matchesSearch && matchesClient && matchesPriority
    })

    // Sort
    if (filters.value.sortBy === 'deadline') {
        results.sort((a, b) => new Date(a.deadline) - new Date(b.deadline))
    } else if (filters.value.sortBy === 'created') {
        results.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    } else if (filters.value.sortBy === 'priority') {
        const priorityOrder = { urgent: 0, high: 1, normal: 2, low: 3 }
        results.sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority])
    }

    return results
})

const hasActiveFilters = computed(() => {
    return filters.value.search || filters.value.client || filters.value.priority
})

const urgentCount = computed(() => {
    return pendingApprovals.value.filter(req => req.priority === 'urgent').length
})

const thisWeekCount = computed(() => {
    const weekFromNow = new Date()
    weekFromNow.setDate(weekFromNow.getDate() + 7)
    return pendingApprovals.value.filter(req => new Date(req.deadline) <= weekFromNow).length
})

const pendingApprovalCount = computed(() => {
    return pendingApprovals.value.filter(req => req.status === 'Pending Approval').length
})

// Methods
const clearFilters = () => {
    filters.value = {
        search: '',
        client: '',
        priority: '',
        sortBy: 'deadline'
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const isDeadlineUrgent = (deadline) => {
    const daysLeft = daysUntil(deadline)
    return daysLeft <= 7
}

const daysUntil = (deadline) => {
    const today = new Date()
    const deadlineDate = new Date(deadline)
    const diffTime = deadlineDate - today
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    return diffDays
}

const viewDetails = (id) => {
    router.push(`/operations/pending-approvals/${id}`)
}
</script>