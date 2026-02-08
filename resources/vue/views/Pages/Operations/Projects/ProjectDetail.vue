<template>
    <auth-layout />
    <div class="ps-60 bg-[#F3F4F6] min-h-screen">
        <div class="px-10 py-10">
            <!-- Back Button & Header -->
            <div class="mb-8">
                <router-link
                    to="/projects"
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
                        <span :class="statusStyles[project.status]" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold">
                            {{ formatStatus(project.status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Project Summary Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
                <div class="p-8 space-y-6">
                    <!-- Project Overview -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Project Code</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.project_code }}</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Client</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.client_name }}</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Total Budget</p>
                            <p class="text-lg font-semibold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Project Duration</p>
                            <p class="text-lg font-semibold text-gray-900">{{ daysUntilCompletion }} days</p>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="grid grid-cols-2 gap-6 pt-4">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Start Date</p>
                            <p class="text-base font-semibold text-gray-900">{{ formatDate(project.start_date) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">End Date</p>
                            <p class="text-base font-semibold text-gray-900">{{ formatDate(project.end_date) }}</p>
                        </div>
                    </div>

                    <!-- Notice of Award -->
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Notice of Award</p>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ project.notice_of_award_file }}</p>
                                <p class="text-xs text-gray-600">Uploaded from CRM</p>
                            </div>
                            <button class="text-[#0c8ce9] hover:text-blue-700 font-semibold text-sm">
                                Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button
                    v-if="project.status === 'pending-setup'"
                    @click="proceedToSetup"
                    class="flex-1 px-6 py-3 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Proceed to Next Step
                </button>
                <button
                    v-else
                    @click="viewSetup"
                    class="flex-1 px-6 py-3 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Setup
                </button>
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

const statusStyles = {
    'pending-setup': 'bg-amber-100 text-amber-800 border border-amber-300',
    'in-progress': 'bg-blue-100 text-blue-800 border border-blue-300',
    'completed': 'bg-green-100 text-green-800 border border-green-300'
}

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
        notice_of_award_file: 'noa_project_001.pdf'
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
        notice_of_award_file: 'noa_project_002.pdf'
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
        month: 'long',
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

const proceedToSetup = () => {
    router.push(`/projects/${project.value.id}/setup`)
}

const viewSetup = () => {
    router.push(`/projects/${project.value.id}/setup`)
}
</script>
