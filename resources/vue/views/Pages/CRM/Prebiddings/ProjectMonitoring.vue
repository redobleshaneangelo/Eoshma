<template>
    <auth-layout />
    <div class="ps-60 pe-4 bg-[#F3F4F6] min-h-screen">
        <div class="px-8 py-4">
            <!-- Back Button & Header -->
            <div class="mb-8">
                <router-link
                    to="/projects-crm"
                    class="inline-flex items-center gap-2 text-[#0c8ce9] font-semibold hover:underline mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Projects
                </router-link>

                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ project.project_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ project.project_code }} • Client: {{ project.client_name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-300">
                            {{ project.progress }}% Complete
                        </span>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <div class="mb-2">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Total Budget</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Allocated for project</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <div class="mb-2">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Duration</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ daysUntilCompletion }} days</p>
                    <p class="text-xs text-gray-500 mt-2">Remaining time</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <div class="mb-2">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Start Date</p>
                    </div>
                    <p class="text-xl font-bold text-gray-900">{{ formatDate(project.start_date) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Project start</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <div class="mb-2">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">End Date</p>
                    </div>
                    <p class="text-xl font-bold text-gray-900">{{ formatDate(project.end_date) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Expected completion</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="col-span-2 space-y-6">
                    <!-- Progress Overview -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Project Progress</h2>
                        
                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700">Overall Progress</span>
                                <span class="text-lg font-bold text-[#0c8ce9]">{{ project.progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-[#0c8ce9] h-4 rounded-full transition-all duration-500" :style="{ width: project.progress + '%' }"></div>
                            </div>
                        </div>

                        <!-- Milestones -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Current Milestone</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-lg font-bold text-gray-900">{{ project.current_milestone }}</p>
                                <p class="text-xs text-gray-600 mt-1">Ongoing phase of the project</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Project Timeline</h2>
                        
                        <div class="space-y-4">
                            <!-- Timeline Item -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 bg-[#0c8ce9] rounded-full"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 my-2"></div>
                                </div>
                                <div class="pb-4">
                                    <p class="font-semibold text-gray-900">Project Started</p>
                                    <p class="text-sm text-gray-600">{{ formatDate(project.start_date) }}</p>
                                </div>
                            </div>

                            <!-- Timeline Item -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 bg-[#0c8ce9] rounded-full"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 my-2"></div>
                                </div>
                                <div class="pb-4">
                                    <p class="font-semibold text-gray-900">Currently in Progress</p>
                                    <p class="text-sm text-gray-600">{{ project.current_milestone }} - {{ project.progress }}% complete</p>
                                </div>
                            </div>

                            <!-- Timeline Item -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Expected Completion</p>
                                    <p class="text-sm text-gray-600">{{ formatDate(project.end_date) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Details -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Project Details</h2>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Project Code</p>
                                <p class="text-base font-semibold text-gray-900">{{ project.project_code }}</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Client</p>
                                <p class="text-base font-semibold text-gray-900">{{ project.client_name }}</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Total Budget</p>
                                <p class="text-base font-semibold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Project Duration</p>
                                <p class="text-base font-semibold text-gray-900">{{ daysUntilCompletion }} days</p>
                            </div>
                        </div>

                        <!-- Notice of Award -->
                        <div class="pt-6 border-t border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-3">Notice of Award</p>
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ project.notice_of_award_file }}</p>
                                    <p class="text-xs text-gray-600">Document available</p>
                                </div>
                                <button class="text-[#0c8ce9] hover:text-blue-700 font-semibold text-sm whitespace-nowrap">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Budget Breakdown -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Budget Tracking</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Spent</span>
                                    <span class="text-sm font-bold text-gray-900">₱{{ formatCurrency(Math.floor(project.total_budget * project.progress / 100)) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" :style="{ width: project.progress + '%' }"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Remaining</span>
                                    <span class="text-sm font-bold text-gray-900">₱{{ formatCurrency(Math.floor(project.total_budget * (100 - project.progress) / 100)) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" :style="{ width: (100 - project.progress) + '%' }"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Budget Utilization</p>
                            <p class="text-2xl font-bold text-gray-900">{{ project.progress }}%</p>
                        </div>
                    </div>

                    <!-- Team Members -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Team Members</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-bold">JD</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">John Davis</p>
                                    <p class="text-xs text-gray-600">Project Manager</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold">SA</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Sarah Anderson</p>
                                    <p class="text-xs text-gray-600">Lead Engineer</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-500 text-white flex items-center justify-center text-sm font-bold">MC</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Michael Chen</p>
                                    <p class="text-xs text-gray-600">QA Lead</p>
                                </div>
                            </div>
                        </div>

                        <button class="w-full mt-4 px-4 py-2 text-center text-sm font-semibold text-[#0c8ce9] hover:bg-blue-50 rounded-lg transition-colors border border-blue-200">
                            View All Team Members
                        </button>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Recent Activities</h2>
                        
                        <div class="space-y-4">
                            <div class="flex gap-3 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                <div class="w-2 h-2 bg-[#0c8ce9] rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Progress Update</p>
                                    <p class="text-xs text-gray-600 mt-1">Updated to {{ project.progress }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex gap-3 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Milestone Reached</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ project.current_milestone }}</p>
                                    <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                                </div>
                            </div>
                            <div class="flex gap-3 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Budget Check</p>
                                    <p class="text-xs text-gray-600 mt-1">Budget spending reviewed</p>
                                    <p class="text-xs text-gray-500 mt-1">3 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AuthLayout from '@/views/Layouts/AuthLayout.vue'

const route = useRoute()
const router = useRouter()

// Mock project data - in real app would be fetched via API
const projects = [
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
        current_milestone: 'Site Preparation'
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
        current_milestone: 'Foundation Work'
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
        current_milestone: 'Planning Phase'
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
        current_milestone: 'Structural Framework'
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
        current_milestone: 'Completed'
    }
]

const project = ref(projects.find(p => p.id === parseInt(route.params.id)) || projects[0])

const daysUntilCompletion = computed(() => {
    const start = new Date(project.value.start_date)
    const end = new Date(project.value.end_date)
    const diffTime = end - start
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    return diffDays
})

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
</script>
