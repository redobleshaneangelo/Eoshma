<template>
    <auth-layout />
    <div class="ps-60 bg-[#F3F4F6] min-h-screen">
        <div class="px-10 py-10">
            <!-- Back Button & Header -->
            <div class="mb-8">
                <router-link
                    :to="`/projects/${projectId}`"
                    class="inline-flex items-center gap-2 text-[#0c8ce9] font-semibold hover:underline mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Project
                </router-link>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50">
                    <nav class="flex gap-0 px-6">
                        <button
                            v-for="tab in tabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            class="py-4 px-4 border-b-2 font-semibold text-sm transition-colors"
                            :class="activeTab === tab.key
                                ? 'border-[#0c8ce9] text-[#0c8ce9]'
                                : 'border-transparent text-gray-600 hover:text-gray-900'"
                        >
                            {{ tab.label }}
                        </button>
                    </nav>
                </div>

                <div class="p-8 min-h-125">
                    <!-- Contract Tab -->
                    <div v-if="activeTab === 'contract'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contract Number</label>
                                <input
                                    v-model="contract.contract_number"
                                    type="text"
                                    placeholder="e.g., CON-2026-001"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contract Date</label>
                                <input
                                    v-model="contract.contract_date"
                                    type="date"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contract Amount</label>
                                <input
                                    v-model="contract.contract_amount"
                                    type="number"
                                    placeholder="0.00"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contract Period (Days)</label>
                                <input
                                    v-model="contract.contract_period"
                                    type="number"
                                    placeholder="0"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                        </div>

                        <!-- Notice of Award -->
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Notice of Award</label>
                            <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ project.notice_of_award_file }}</p>
                                    <p class="text-xs text-gray-600">File from CRM - Notice of Award</p>
                                </div>
                                <button class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Project Setup Tab -->
                    <div v-if="activeTab === 'project-setup'" class="space-y-8">
                        <!-- Workers/HR Section -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Required Workforce</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddWorkforceModal = true"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Workforce
                                    </button>
                                    <button
                                        @click="openEditWorkforceModal"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Position</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Headcount</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="worker in setupData.workers" :key="worker.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ worker.position }}</td>
                                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">{{ worker.headcount }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    @click="removeWorker(worker.id)"
                                                    class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                                >
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="setupData.workers.length === 0">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                No workers added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Materials Section -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Materials</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddMaterialModal = true"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Material
                                    </button>
                                    <button
                                        @click="openEditMaterialsModal"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Material</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Unit</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="material in setupData.materials" :key="material.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ material.name }}</td>
                                            <td class="px-6 py-4 text-center text-sm text-gray-900">{{ material.quantity }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ material.unit }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    @click="removeMaterial(material.id)"
                                                    class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                                >
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="setupData.materials.length === 0">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                No materials added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Equipment Section -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Equipment</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddEquipmentModal = true"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Equipment
                                    </button>
                                    <button
                                        @click="openEditEquipmentModal"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Equipment</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="equipment in setupData.equipment" :key="equipment.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ equipment.name }}</td>
                                            <td class="px-6 py-4 text-center text-sm text-gray-900">{{ equipment.quantity }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ equipment.type }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    @click="removeEquipment(equipment.id)"
                                                    class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                                >
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="setupData.equipment.length === 0">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                No equipment added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Tab (Gantt Chart) -->
                    <div v-if="activeTab === 'schedule'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Start Date</label>
                                <input
                                    v-model="setupData.schedule.start_date"
                                    @change="validateStartDate"
                                    :max="setupData.schedule.end_date"
                                    type="date"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project End Date</label>
                                <input
                                    v-model="setupData.schedule.end_date"
                                    @change="validateEndDate"
                                    :min="setupData.schedule.start_date"
                                    type="date"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Days)</label>
                                <input
                                    :value="calculateDuration"
                                    type="number"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm text-gray-600"
                                />
                            </div>
                        </div>

                        <!-- Gantt Chart -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Project Timeline - Gantt Chart</h3>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 overflow-x-auto">
                                <div class="min-w-max">
                                    <!-- Timeline Header -->
                                    <div class="flex gap-4 mb-4">
                                        <div class="w-40 shrink-0">
                                            <p class="text-xs font-semibold text-gray-700">Milestone</p>
                                        </div>
                                        <div class="flex-1 flex gap-2">
                                            <div v-for="month in getMonthRange()" :key="month" class="flex-1 text-center text-xs font-semibold text-gray-600">
                                                {{ month }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gantt Bars -->
                                    <div class="space-y-3">
                                        <!-- Project Duration Bar -->
                                        <div class="flex gap-4 items-center pb-4 border-b border-gray-200">
                                            <div class="w-40 shrink-0">
                                                <p class="text-sm font-semibold text-gray-900">Project Duration</p>
                                            </div>
                                            <div class="flex-1 relative h-8 bg-gray-100 rounded" :style="{ minWidth: '500px' }">
                                                <div class="absolute h-full bg-[#0c8ce9] rounded" :style="{ left: '0%', width: '100%' }"></div>
                                                <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-white">
                                                    {{ calculateDuration }} days
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Milestone Bars -->
                                        <div v-for="milestone in setupData.schedule.milestones" :key="milestone.id" class="flex gap-4 items-center">
                                            <div class="w-40 shrink-0">
                                                <p class="text-sm font-semibold text-gray-900">{{ milestone.name }}</p>
                                                <p class="text-xs text-gray-600">{{ formatDate(milestone.start_date) }} - {{ formatDate(milestone.end_date) }}</p>
                                            </div>
                                            <div class="flex-1 relative h-8 bg-gray-100 rounded" :style="{ minWidth: '500px' }">
                                                <div class="absolute h-full bg-green-500 rounded" :style="{ left: getMilestonePosition(milestone.start_date) + '%', width: getMilestoneBarWidth(milestone.start_date, milestone.end_date) + '%' }"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Major Milestones -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Major Milestones</h3>
                                <button
                                    @click="showAddMilestoneModal = true"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Milestone
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div v-for="milestone in setupData.schedule.milestones" :key="milestone.id" class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ milestone.name }}</p>
                                        <p class="text-xs text-gray-600">{{ formatDate(milestone.start_date) }} - {{ formatDate(milestone.end_date) }}</p>
                                    </div>
                                    <button
                                        @click="removeMilestone(milestone.id)"
                                        class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                    >
                                        Remove
                                    </button>
                                </div>
                                <div v-if="setupData.schedule.milestones.length === 0" class="p-4 text-center text-gray-500">
                                    No milestones added yet
                                </div>
                            </div>
                        </div>

                        <!-- Tasks -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Tasks</h3>
                                <button
                                    @click="showAddTaskModal = true"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Task
                                </button>
                            </div>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Task Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Milestone</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">End Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Assigned To</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="task in setupData.schedule.tasks" :key="task.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ task.name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ getMilestoneName(task.milestone_id) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ formatDate(task.end_date) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ task.assigned_to }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold" :class="getTaskStatusClasses(task.status)">
                                                    {{ task.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    @click="removeTask(task.id)"
                                                    class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                                >
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="setupData.schedule.tasks.length === 0">
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                No tasks added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Tab -->
                    <div v-if="activeTab === 'financial'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Budget</label>
                                <input
                                    v-model="setupData.financial.total_budget"
                                    type="number"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Retention (%)</label>
                                <input
                                    v-model="setupData.financial.retention"
                                    type="number"
                                    placeholder="10"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                                />
                            </div>
                        </div>

                        <!-- Budget Breakdown -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Budget Breakdown</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-700">Materials</p>
                                    <p class="text-sm font-bold text-gray-900">₱{{ formatCurrency(setupData.financial.materials_cost) }}</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-700">Equipment</p>
                                    <p class="text-sm font-bold text-gray-900">₱{{ formatCurrency(setupData.financial.equipment_cost) }}</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-700">Labor</p>
                                    <p class="text-sm font-bold text-gray-900">₱{{ formatCurrency(setupData.financial.labor_cost) }}</p>
                                </div>
                                <div class="flex items-center justify-between p-3 border border-[#0c8ce9] bg-blue-50 rounded-lg">
                                    <p class="text-sm font-bold text-gray-900">Estimated Total</p>
                                    <p class="text-lg font-bold text-[#0c8ce9]">₱{{ formatCurrency(setupData.financial.estimated_total) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div v-if="activeTab === 'documents'" class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Required Documents</h3>
                            <button
                                @click="showAddDocumentModal = true"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-white bg-[#0c8ce9] hover:bg-blue-700 rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Document
                            </button>
                        </div>
                        <div v-if="setupData.documents.length === 0" class="p-6 bg-gray-50 border border-gray-200 rounded-lg text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm font-semibold text-gray-700">No documents added yet</p>
                            <p class="text-xs text-gray-600 mt-1">Click "Add Document" to upload project documents</p>
                        </div>
                        <div v-else class="grid grid-cols-1 gap-6">
                            <div v-for="doc in setupData.documents" :key="doc.id" class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:shadow-lg transition-shadow" @click="doc.status === 'Pending' ? triggerFileInput(doc.id) : openDocument(doc)">
                                <div class="flex items-start gap-3 mb-3">
                                    <svg class="w-5 h-5 text-gray-600 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ doc.name }}</p>
                                        <p class="text-xs text-gray-600">{{ doc.status }}</p>
                                    </div>
                                    <button
                                        @click.stop="removeDocument(doc.id)"
                                        class="text-red-600 hover:text-red-700 text-sm font-semibold shrink-0"
                                    >
                                        Remove
                                    </button>
                                </div>
                                <div v-if="doc.status === 'Pending'" class="relative">
                                    <input
                                        type="file"
                                        @change="uploadDocument(doc.id, $event)"
                                        class="hidden"
                                        :data-doc-id="doc.id"
                                        accept=".pdf"
                                    />
                                    <div class="px-3 py-2 border border-[#0c8ce9] bg-blue-50 text-[#0c8ce9] rounded-lg text-sm font-semibold text-center">
                                        Click to upload PDF
                                    </div>
                                </div>
                                <div v-else class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-green-600 font-semibold">Uploaded</span>
                                    <button
                                        @click.stop="openDocument(doc)"
                                        class="ml-auto px-3 py-1 bg-[#0c8ce9] text-white rounded text-xs font-semibold hover:bg-blue-700 transition-colors"
                                    >
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Tab -->
                    <div v-if="activeTab === 'summary'" class="space-y-6">
                        <!-- Project Overview -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Project Summary</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Project</p>
                                    <p class="text-base font-semibold text-gray-900">{{ project.project_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Client</p>
                                    <p class="text-base font-semibold text-gray-900">{{ project.client_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Total Budget</p>
                                    <p class="text-base font-semibold text-gray-900">₱{{ formatCurrency(project.total_budget) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Duration</p>
                                    <p class="text-base font-semibold text-gray-900">{{ calculateDuration }} days</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contract Summary -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="text-base font-bold text-gray-900 mb-4">Contract Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Contract Number</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ setupData.contract?.number || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Contract Date</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ setupData.contract?.date ? formatDate(setupData.contract.date) : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Contract Amount</p>
                                    <p class="text-sm font-semibold text-gray-900">₱{{ formatCurrency(setupData.contract?.amount || 0) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Notice of Award</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ setupData.contract?.notice_of_award || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Project Setup Summary -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="text-base font-bold text-gray-900 mb-4">Project Setup</h4>
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-[#0c8ce9]">{{ setupData.workers.length }}</p>
                                    <p class="text-xs text-gray-600 font-semibold mt-1">Workers Assigned</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-[#0c8ce9]">{{ setupData.materials.length }}</p>
                                    <p class="text-xs text-gray-600 font-semibold mt-1">Materials</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-[#0c8ce9]">{{ setupData.equipment.length }}</p>
                                    <p class="text-xs text-gray-600 font-semibold mt-1">Equipment</p>
                                </div>
                            </div>

                            <!-- Workers Detail -->
                            <div v-if="setupData.workers.length > 0" class="mb-4">
                                <p class="text-xs font-bold text-gray-700 uppercase mb-2">Workers</p>
                                <div class="space-y-1 bg-gray-50 p-3 rounded">
                                    <div v-for="worker in setupData.workers" :key="worker.id" class="text-sm text-gray-700">
                                        {{ worker.position }} - <span class="font-semibold">{{ worker.headcount }}</span> headcount
                                    </div>
                                </div>
                            </div>

                            <!-- Materials Detail -->
                            <div v-if="setupData.materials.length > 0" class="mb-4">
                                <p class="text-xs font-bold text-gray-700 uppercase mb-2">Materials</p>
                                <div class="space-y-1 bg-gray-50 p-3 rounded">
                                    <div v-for="material in setupData.materials" :key="material.id" class="text-sm text-gray-700">
                                        {{ material.name }} - <span class="font-semibold">{{ material.quantity }}</span> {{ material.unit }}
                                    </div>
                                </div>
                            </div>

                            <!-- Equipment Detail -->
                            <div v-if="setupData.equipment.length > 0">
                                <p class="text-xs font-bold text-gray-700 uppercase mb-2">Equipment</p>
                                <div class="space-y-1 bg-gray-50 p-3 rounded">
                                    <div v-for="equipment in setupData.equipment" :key="equipment.id" class="text-sm text-gray-700">
                                        {{ equipment.name }} - <span class="font-semibold">{{ equipment.quantity }}</span> ({{ equipment.type }})
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Summary -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="text-base font-bold text-gray-900 mb-4">Schedule</h4>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Start Date</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ formatDate(setupData.schedule.start_date) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">End Date</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ formatDate(setupData.schedule.end_date) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Duration</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ calculateDuration }} days</p>
                                </div>
                            </div>

                            <div v-if="setupData.schedule.milestones.length > 0">
                                <p class="text-xs font-bold text-gray-700 uppercase mb-2">Milestones</p>
                                <div class="space-y-2">
                                    <div v-for="milestone in setupData.schedule.milestones" :key="milestone.id" class="bg-gray-50 p-3 rounded">
                                        <p class="text-sm font-semibold text-gray-900">{{ milestone.name }}</p>
                                        <p class="text-xs text-gray-600">{{ formatDate(milestone.start_date) }} - {{ formatDate(milestone.end_date) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="text-base font-bold text-gray-900 mb-4">Financial Details</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Materials Cost</span>
                                    <span class="font-semibold text-gray-900">₱{{ formatCurrency(setupData.financial.materials_cost) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Equipment Cost</span>
                                    <span class="font-semibold text-gray-900">₱{{ formatCurrency(setupData.financial.equipment_cost) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Labor Cost</span>
                                    <span class="font-semibold text-gray-900">₱{{ formatCurrency(setupData.financial.labor_cost) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Retention</span>
                                    <span class="font-semibold text-gray-900">{{ setupData.financial.retention }}%</span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-sm font-bold text-gray-900">Estimated Total</span>
                                    <span class="text-lg font-bold text-[#0c8ce9]">₱{{ formatCurrency(setupData.financial.estimated_total) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Summary -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="text-base font-bold text-gray-900 mb-4">Documents</h4>
                            <div class="grid grid-cols-1 gap-2">
                                <div v-if="setupData.documents.length === 0" class="text-center py-4">
                                    <p class="text-sm text-gray-600">No documents uploaded</p>
                                </div>
                                <div v-for="doc in setupData.documents" :key="doc.id" class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900">{{ doc.name }}</span>
                                    </div>
                                    <span :class="['text-xs font-semibold px-2 py-1 rounded', doc.status === 'Uploaded' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">
                                        {{ doc.status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Buttons -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button
                                @click="rejectApproval"
                                class="flex-1 px-6 py-3 border border-red-300 text-red-600 rounded-lg font-semibold hover:bg-red-50 transition-colors"
                            >
                                Reject
                            </button>
                            <button
                                @click="showNoticeDrawer = true"
                                class="flex-1 px-6 py-3 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                            >
                                Send for Approval
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex gap-4 mt-6" v-if="activeTab !== 'summary'">
                <button
                    @click="previousTab"
                    v-if="activeTab !== 'contract'"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Previous
                </button>
                <button
                    @click="nextTab"
                    class="flex-1 px-6 py-3 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Notice to Proceed Drawer -->
    <div v-if="showNoticeDrawer" class="fixed inset-0 z-50 overflow-hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" @click="showNoticeDrawer = false"></div>
        
        <!-- Drawer -->
        <div class="absolute inset-y-0 right-0 max-w-md w-full bg-white shadow-xl flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Send for Approval</h3>
                    <button @click="showNoticeDrawer = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="space-y-6">
                    <!-- Project Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Project</p>
                        <p class="text-base font-bold text-gray-900">{{ project.project_name }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ project.project_code }}</p>
                    </div>

                    <!-- Notice to Proceed Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Notice to Proceed
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- File Upload Area -->
                        <div
                            @drop.prevent="handleNoticeDrop"
                            @dragover.prevent
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0c8ce9] transition-colors cursor-pointer"
                            @click="$refs.noticeFileInput.click()"
                        >
                            <div v-if="!noticeToProccedFile">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    Click to upload or drag and drop
                                </p>
                                <p class="mt-1 text-xs text-gray-500">PDF files only</p>
                            </div>
                            <div v-else class="flex items-center justify-center gap-3">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-900">{{ noticeToProccedFile.name }}</p>
                                    <p class="text-xs text-gray-500">{{ (noticeToProccedFile.size / 1024).toFixed(2) }} KB</p>
                                </div>
                                <button
                                    @click.stop="noticeToProccedFile = null"
                                    class="ml-auto text-red-500 hover:text-red-700"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <input
                            ref="noticeFileInput"
                            type="file"
                            accept=".pdf,application/pdf"
                            class="hidden"
                            @change="handleNoticeUpload"
                        />
                    </div>

                    <!-- Info Message -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-800 mb-1">Ready to Proceed</p>
                                <p class="text-xs text-green-700">
                                    Upload the Notice to Proceed document to finalize the project setup and send it for approval.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex gap-3">
                    <button
                        @click="showNoticeDrawer = false"
                        class="flex-1 px-4 py-2.5 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitForApproval"
                        :disabled="!noticeToProccedFile || submittingApproval"
                        class="flex-1 px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="!submittingApproval">Submit</span>
                        <span v-else class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Milestone Modal -->
    <div v-if="showAddMilestoneModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Milestone</h3>
                    <button @click="showAddMilestoneModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Milestone Name</label>
                    <input
                        v-model="newMilestone.name"
                        type="text"
                        placeholder="e.g., Foundation Complete"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                    <input
                        v-model="newMilestone.start_date"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                    <input
                        v-model="newMilestone.end_date"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button
                    @click="showAddMilestoneModal = false"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="addMilestone"
                    class="flex-1 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Add Milestone
                </button>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div v-if="showAddTaskModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-xl w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Task</h3>
                    <button @click="closeAddTaskModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Task Name</label>
                    <input
                        v-model="newTask.name"
                        type="text"
                        placeholder="Enter task name"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Milestone</label>
                    <select
                        v-model="newTask.milestone_id"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    >
                        <option value="">Select milestone</option>
                        <option v-for="milestone in setupData.schedule.milestones" :key="milestone.id" :value="milestone.id">
                            {{ milestone.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                    <input
                        v-model="newTask.end_date"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned To</label>
                    <input
                        v-model="newTask.assigned_to"
                        type="text"
                        placeholder="Enter assignee"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select
                        v-model="newTask.status"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                    >
                        <option v-for="status in taskStatusOptions" :key="status" :value="status">{{ status }}</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button
                    @click="closeAddTaskModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="addTask"
                    class="flex-1 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Add Task
                </button>
            </div>
        </div>
    </div>

    <div v-if="showAddWorkforceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Workforce</h3>
                    <button @click="closeAddWorkforceModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Input Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Position/Role</label>
                        <select
                            v-model="newWorkforce.role"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">Select a role</option>
                            <option value="Site Engineer">Site Engineer</option>
                            <option value="Safety Officer">Safety Officer</option>
                            <option value="Foreman">Foreman</option>
                            <option value="Laborer">Laborer</option>
                            <option value="Electrician">Electrician</option>
                            <option value="Plumber">Plumber</option>
                            <option value="Carpenter">Carpenter</option>
                            <option value="Others">Others (Specify)</option>
                        </select>
                    </div>

                    <!-- Custom Role Input (appears when "Others" is selected) -->
                    <div v-if="newWorkforce.role === 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Role</label>
                        <input
                            v-model="newWorkforce.customRole"
                            type="text"
                            placeholder="Enter custom role"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Headcount</label>
                        <input
                            v-model.number="newWorkforce.headcount"
                            type="number"
                            placeholder="1"
                            min="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <button
                        @click="addToWorkforceList"
                        class="w-full px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Add to List
                    </button>
                </div>

                <!-- Temporary Workforce List -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Workforce List</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Position</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Headcount</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="tempWorkforceList.length === 0">
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No workers added yet. Add a worker above to get started.
                                    </td>
                                </tr>
                                <tr v-for="(worker, idx) in tempWorkforceList" :key="idx" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ worker.role === 'Others' ? worker.customRole : worker.role }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900">{{ worker.headcount }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button
                                            @click="removeFromWorkforceList(idx)"
                                            class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeAddWorkforceModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="submitWorkforceList"
                    :disabled="tempWorkforceList.length === 0"
                    :class="[
                        'flex-1 px-4 py-2 rounded-lg font-semibold transition-colors',
                        tempWorkforceList.length === 0
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'bg-green-600 text-white hover:bg-green-700'
                    ]"
                >
                    Submit Workforce
                </button>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div v-if="showAddMaterialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Material</h3>
                    <button @click="closeAddMaterialModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Input Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Material Name</label>
                        <select
                            v-model="newMaterial.name"
                            @change="updateMaterialUnit"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">Select material</option>
                            <option value="Cement">Cement</option>
                            <option value="Steel Bars">Steel Bars</option>
                            <option value="Bricks">Bricks</option>
                            <option value="Sand">Sand</option>
                            <option value="Gravel">Gravel</option>
                            <option value="Concrete">Concrete</option>
                            <option value="Paint">Paint</option>
                            <option value="Others">Others (Specify)</option>
                        </select>
                    </div>

                    <!-- Custom Material Input (appears when "Others" is selected) -->
                    <div v-if="newMaterial.name === 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Material</label>
                        <input
                            v-model="newMaterial.customName"
                            type="text"
                            placeholder="Enter custom material name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                        <input
                            v-model.number="newMaterial.quantity"
                            type="number"
                            placeholder="0"
                            min="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div v-if="newMaterial.name !== 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                        <input
                            v-model="newMaterial.unit"
                            type="text"
                            readonly
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm text-gray-600"
                        />
                    </div>

                    <!-- Custom Unit Input (appears when "Others" is selected for material) -->
                    <div v-if="newMaterial.name === 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                        <input
                            v-model="newMaterial.unit"
                            type="text"
                            placeholder="Enter unit"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <button
                        @click="addToMaterialList"
                        class="w-full px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Add to List
                    </button>
                </div>

                <!-- Temporary Material List -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Material List</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Material</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Unit</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="tempMaterialsList.length === 0">
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No materials added yet. Add a material above to get started.
                                    </td>
                                </tr>
                                <tr v-for="(material, idx) in tempMaterialsList" :key="idx" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ material.name }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900">{{ material.quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ material.unit }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button
                                            @click="removeFromMaterialList(idx)"
                                            class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeAddMaterialModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="submitMaterialList"
                    :disabled="tempMaterialsList.length === 0"
                    :class="[
                        'flex-1 px-4 py-2 rounded-lg font-semibold transition-colors',
                        tempMaterialsList.length === 0
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'bg-green-600 text-white hover:bg-green-700'
                    ]"
                >
                    Submit Materials
                </button>
            </div>
        </div>
    </div>

    <!-- Add Equipment Modal -->
    <div v-if="showAddEquipmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Equipment</h3>
                    <button @click="closeAddEquipmentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Input Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Equipment Name</label>
                        <select
                            v-model="newEquipment.name"
                            @change="updateEquipmentType"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">Select equipment</option>
                            <option value="Excavator">Excavator</option>
                            <option value="Concrete Mixer">Concrete Mixer</option>
                            <option value="Bulldozer">Bulldozer</option>
                            <option value="Crane">Crane</option>
                            <option value="Compactor">Compactor</option>
                            <option value="Scaffolding">Scaffolding</option>
                            <option value="Power Tools">Power Tools</option>
                            <option value="Others">Others (Specify)</option>
                        </select>
                    </div>

                    <!-- Custom Equipment Input (appears when "Others" is selected) -->
                    <div v-if="newEquipment.name === 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Equipment</label>
                        <input
                            v-model="newEquipment.customName"
                            type="text"
                            placeholder="Enter custom equipment name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                        <input
                            v-model.number="newEquipment.quantity"
                            type="number"
                            placeholder="0"
                            min="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div v-if="newEquipment.name !== 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                        <input
                            v-model="newEquipment.type"
                            type="text"
                            readonly
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm text-gray-600"
                        />
                    </div>

                    <!-- Type Select (appears when "Others" is selected for equipment) -->
                    <div v-if="newEquipment.name === 'Others'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                        <select
                            v-model="newEquipment.type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        >
                            <option value="">Select type</option>
                            <option value="Heavy">Heavy</option>
                            <option value="Light">Light</option>
                            <option value="Specialized">Specialized</option>
                        </select>
                    </div>

                    <button
                        @click="addToEquipmentList"
                        class="w-full px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Add to List
                    </button>
                </div>

                <!-- Temporary Equipment List -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Equipment List</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Equipment</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="tempEquipmentList.length === 0">
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No equipment added yet. Add equipment above to get started.
                                    </td>
                                </tr>
                                <tr v-for="(equipment, idx) in tempEquipmentList" :key="idx" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ equipment.name }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900">{{ equipment.quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ equipment.type }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button
                                            @click="removeFromEquipmentList(idx)"
                                            class="text-red-600 hover:text-red-700 text-sm font-semibold"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeAddEquipmentModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="submitEquipmentList"
                    :disabled="tempEquipmentList.length === 0"
                    :class="[
                        'flex-1 px-4 py-2 rounded-lg font-semibold transition-colors',
                        tempEquipmentList.length === 0
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'bg-green-600 text-white hover:bg-green-700'
                    ]"
                >
                    Submit Equipment
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Workforce Modal -->
    <div v-if="showEditWorkforceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Edit Workforce</h3>
                    <button @click="closeEditWorkforceModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Position</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Headcount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="editWorkforceList.length === 0">
                                <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No workers available to edit.
                                </td>
                            </tr>
                            <tr v-for="(worker, idx) in editWorkforceList" :key="idx" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <input
                                        v-model="worker.position"
                                        type="text"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                    />
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    <input
                                        v-model.number="worker.headcount"
                                        type="number"
                                        min="0"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeEditWorkforceModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="saveEditWorkforce"
                    class="flex-1 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Materials Modal -->
    <div v-if="showEditMaterialsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Edit Materials</h3>
                    <button @click="closeEditMaterialsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Material</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="editMaterialsList.length === 0">
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No materials available to edit.
                                </td>
                            </tr>
                            <tr v-for="(material, idx) in editMaterialsList" :key="idx" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <input
                                        v-model="material.name"
                                        type="text"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                    />
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    <input
                                        v-model.number="material.quantity"
                                        type="number"
                                        min="0"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                                    />
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <input
                                        v-model="material.unit"
                                        type="text"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeEditMaterialsModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="saveEditMaterials"
                    class="flex-1 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Equipment Modal -->
    <div v-if="showEditEquipmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Edit Equipment</h3>
                    <button @click="closeEditEquipmentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Equipment</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="editEquipmentList.length === 0">
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No equipment available to edit.
                                </td>
                            </tr>
                            <tr v-for="(equipment, idx) in editEquipmentList" :key="idx" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <input
                                        v-model="equipment.name"
                                        type="text"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                    />
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    <input
                                        v-model.number="equipment.quantity"
                                        type="number"
                                        min="0"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                                    />
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <input
                                        v-model="equipment.type"
                                        type="text"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeEditEquipmentModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="saveEditEquipment"
                    class="flex-1 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Add Document Modal -->
    <div v-if="showAddDocumentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Add Document</h3>
                    <button @click="closeAddDocumentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Input Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Document Name</label>
                        <input
                            v-model="newDocument.name"
                            type="text"
                            placeholder="Enter document name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0c8ce9] transition-colors cursor-pointer" @click="triggerDocumentFileInput" @drop="handleDocumentDrop" @dragover.prevent @dragenter.prevent>
                            <input
                                ref="documentFileInput"
                                type="file"
                                @change="handleDocumentFileUpload"
                                class="hidden"
                                accept=".pdf"
                            />
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            <p class="text-sm font-semibold text-gray-700">Drag and drop your file here or click to browse</p>
                            <p class="text-xs text-gray-600 mt-1">PDF files only</p>
                            <div v-if="newDocument.file" class="mt-3">
                                <p class="text-sm text-green-600 font-semibold">✓ {{ newDocument.file.name }}</p>
                            </div>
                        </div>
                    </div>

                    <button
                        @click="addDocument"
                        class="w-full px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Add Document
                    </button>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                <button
                    @click="closeAddDocumentModal"
                    class="flex-1 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Swal from 'sweetalert2'

const auth = useAuthStore()
const route = useRoute()
const router = useRouter()

const projectId = ref(route.params.id)
const activeTab = ref('contract')
const showHRRequestModal = ref(false)
const showAddWorkforceModal = ref(false)
const showAddMaterialModal = ref(false)
const showAddEquipmentModal = ref(false)
const showAddMilestoneModal = ref(false)
const showAddTaskModal = ref(false)
const showAddDocumentModal = ref(false)
const showNoticeDrawer = ref(false)
const noticeToProccedFile = ref(null)
const submittingApproval = ref(false)
const showEditWorkforceModal = ref(false)
const showEditMaterialsModal = ref(false)
const showEditEquipmentModal = ref(false)

const editWorkforceList = ref([])
const editMaterialsList = ref([])
const editEquipmentList = ref([])

const newWorkforce = ref({
    role: '',
    customRole: '',
    headcount: 1
})

const tempWorkforceList = ref([])

const newMilestone = ref({
    name: '',
    start_date: '',
    end_date: ''
})

const newTask = ref({
    name: '',
    milestone_id: '',
    end_date: '',
    assigned_to: '',
    status: 'In Progress'
})

const taskStatusOptions = ['In Progress', 'On Hold', 'Completed']

const newDocument = ref({
    name: '',
    file: null
})

const newMaterial = ref({
    name: '',
    customName: '',
    quantity: '',
    unit: ''
})

const materialUnits = {
    'Cement': 'Bags',
    'Steel Bars': 'pcs',
    'Bricks': 'pcs',
    'Sand': 'kg',
    'Gravel': 'kg',
    'Concrete': 'm3',
    'Paint': 'L'
}

const tempMaterialsList = ref([])

const newEquipment = ref({
    name: '',
    customName: '',
    quantity: '',
    type: ''
})

const equipmentTypes = {
    'Excavator': 'Heavy',
    'Concrete Mixer': 'Light',
    'Bulldozer': 'Heavy',
    'Crane': 'Heavy',
    'Compactor': 'Light',
    'Scaffolding': 'Light',
    'Power Tools': 'Light'
}

const tempEquipmentList = ref([])

const tabs = [
    { key: 'contract', label: 'Contract' },
    { key: 'project-setup', label: 'Project Setup' },
    { key: 'schedule', label: 'Schedule' },
    { key: 'financial', label: 'Financial' },
    { key: 'documents', label: 'Documents' },
    { key: 'summary', label: 'Summary' }
]

// Mock data
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
    }
]

const project = ref(projects[0])

const contract = ref({
    contract_number: '',
    contract_date: new Date().toISOString().split('T')[0],
    contract_amount: 0,
    contract_period: 0,
    terms_conditions: ''
})

const setupData = ref({
    workers: [],
    materials: [
        { id: 1, name: 'Cement', quantity: 500, unit: 'Bags' },
        { id: 2, name: 'Steel Bars', quantity: 100, unit: 'pcs' }
    ],
    equipment: [
        { id: 1, name: 'Excavator', quantity: 2, type: 'Heavy' },
        { id: 2, name: 'Concrete Mixer', quantity: 1, type: 'Light' }
    ],
    schedule: {
        start_date: project.value.start_date,
        end_date: project.value.end_date,
        duration: 180,
        milestones: [
            { id: 1, name: 'Foundation Complete', start_date: '2026-03-15', end_date: '2026-03-20' },
            { id: 2, name: 'Structural Work Complete', start_date: '2026-05-15', end_date: '2026-05-25' }
        ],
        tasks: [
            { id: 1, name: 'Site Clearing', milestone_id: 1, end_date: '2026-03-10', assigned_to: 'Luis Gomez', status: 'In Progress' },
            { id: 2, name: 'Rebar Installation', milestone_id: 1, end_date: '2026-03-18', assigned_to: 'Carlos Santos', status: 'On Hold' }
        ]
    },
    financial: {
        total_budget: project.value.total_budget,
        retention: 10,
        materials_cost: 800000,
        equipment_cost: 450000,
        labor_cost: 650000,
        estimated_total: 1900000
    },
    documents: []
})

const availableHRRequests = ref([
    { id: 1, position: 'Site Engineer', headcount: 2, status: 'Filled', requestNumber: 'HR-REC-001' },
    { id: 2, position: 'Safety Officer', headcount: 1, status: 'Filled', requestNumber: 'HR-REC-002' },
    { id: 3, position: 'Foreman', headcount: 3, status: 'In Progress', requestNumber: 'HR-REC-003' },
    { id: 4, position: 'Laborers', headcount: 20, status: 'In Progress', requestNumber: 'HR-REC-004' }
])

const calculateDuration = computed(() => {
    const start = new Date(setupData.value.schedule.start_date)
    const end = new Date(setupData.value.schedule.end_date)
    const diffTime = end - start
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    return diffDays
})

const documentsComplete = computed(() => {
    return setupData.value.documents.every(doc => doc.status === 'Uploaded')
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

const getMonthRange = () => {
    const months = []
    const startDate = new Date(setupData.value.schedule.start_date)
    const endDate = new Date(setupData.value.schedule.end_date)
    
    const start = new Date(startDate.getFullYear(), startDate.getMonth(), 1)
    const end = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0)
    
    let current = new Date(start)
    while (current <= end) {
        months.push(current.toLocaleDateString('en-US', { month: 'short', year: '2-digit' }))
        current.setMonth(current.getMonth() + 1)
    }
    
    return months
}

const getMilestonePosition = (milestoneDate) => {
    const startDate = new Date(setupData.value.schedule.start_date)
    const endDate = new Date(setupData.value.schedule.end_date)
    const milestone = new Date(milestoneDate)
    
    const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24)
    const daysFromStart = (milestone - startDate) / (1000 * 60 * 60 * 24)
    
    return (daysFromStart / totalDays) * 100
}

const getMilestoneBarWidth = (startDate, endDate) => {
    const projectStart = new Date(setupData.value.schedule.start_date)
    const projectEnd = new Date(setupData.value.schedule.end_date)
    const milestoneStart = new Date(startDate)
    const milestoneEnd = new Date(endDate)
    
    const totalDays = (projectEnd - projectStart) / (1000 * 60 * 60 * 24)
    const milestoneDays = (milestoneEnd - milestoneStart) / (1000 * 60 * 60 * 24)
    
    return (milestoneDays / totalDays) * 100
}

const validateEndDate = () => {
    const startDate = new Date(setupData.value.schedule.start_date)
    const endDate = new Date(setupData.value.schedule.end_date)
    
    if (endDate < startDate) {
        Swal.fire({
            toast: true,
            icon: 'error',
            title: 'Invalid End Date',
            text: 'End date cannot be before start date',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        setupData.value.schedule.end_date = setupData.value.schedule.start_date
    }
}

const validateStartDate = () => {
    const startDate = new Date(setupData.value.schedule.start_date)
    const endDate = new Date(setupData.value.schedule.end_date)
    
    if (startDate > endDate) {
        Swal.fire({
            toast: true,
            icon: 'error',
            title: 'Invalid Start Date',
            text: 'Start date cannot be after end date',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        setupData.value.schedule.start_date = setupData.value.schedule.end_date
    }
}

const nextTab = () => {
    const currentIndex = tabs.findIndex(t => t.key === activeTab.value)
    if (currentIndex < tabs.length - 1) {
        activeTab.value = tabs[currentIndex + 1].key
        window.scrollTo(0, 0)
    }
}

const previousTab = () => {
    const currentIndex = tabs.findIndex(t => t.key === activeTab.value)
    if (currentIndex > 0) {
        activeTab.value = tabs[currentIndex - 1].key
        window.scrollTo(0, 0)
    }
}

const addWorkerToProject = (worker) => {
    const exists = setupData.value.workers.some(w => w.id === worker.id)
    if (!exists) {
        setupData.value.workers.push({
            id: worker.id,
            position: worker.position,
            headcount: worker.headcount,
            status: worker.status
        })
    }
    showHRRequestModal.value = false
}

const removeWorker = (id) => {
    setupData.value.workers = setupData.value.workers.filter(w => w.id !== id)
}

const removeMaterial = (id) => {
    setupData.value.materials = setupData.value.materials.filter(m => m.id !== id)
}

const removeEquipment = (id) => {
    setupData.value.equipment = setupData.value.equipment.filter(e => e.id !== id)
}

const openEditWorkforceModal = () => {
    editWorkforceList.value = JSON.parse(JSON.stringify(setupData.value.workers))
    showEditWorkforceModal.value = true
}

const closeEditWorkforceModal = () => {
    showEditWorkforceModal.value = false
    editWorkforceList.value = []
}

const saveEditWorkforce = () => {
    setupData.value.workers = JSON.parse(JSON.stringify(editWorkforceList.value))
    closeEditWorkforceModal()
}

const openEditMaterialsModal = () => {
    editMaterialsList.value = JSON.parse(JSON.stringify(setupData.value.materials))
    showEditMaterialsModal.value = true
}

const closeEditMaterialsModal = () => {
    showEditMaterialsModal.value = false
    editMaterialsList.value = []
}

const saveEditMaterials = () => {
    setupData.value.materials = JSON.parse(JSON.stringify(editMaterialsList.value))
    closeEditMaterialsModal()
}

const openEditEquipmentModal = () => {
    editEquipmentList.value = JSON.parse(JSON.stringify(setupData.value.equipment))
    showEditEquipmentModal.value = true
}

const closeEditEquipmentModal = () => {
    showEditEquipmentModal.value = false
    editEquipmentList.value = []
}

const saveEditEquipment = () => {
    setupData.value.equipment = JSON.parse(JSON.stringify(editEquipmentList.value))
    closeEditEquipmentModal()
}

const removeMilestone = (id) => {
    setupData.value.schedule.milestones = setupData.value.schedule.milestones.filter(m => m.id !== id)
}

const getMilestoneName = (milestoneId) => {
    const normalizedId = Number(milestoneId)
    const milestone = setupData.value.schedule.milestones.find(m => m.id === normalizedId)
    return milestone ? milestone.name : 'Unassigned'
}

const getTaskStatusClasses = (status) => {
    const statusClasses = {
        Completed: 'bg-green-100 text-green-800',
        'In Progress': 'bg-blue-100 text-blue-800',
        'On Hold': 'bg-yellow-100 text-yellow-800'
    }
    return statusClasses[status] || 'bg-gray-100 text-gray-700'
}

const closeAddTaskModal = () => {
    showAddTaskModal.value = false
    newTask.value = {
        name: '',
        milestone_id: '',
        end_date: '',
        assigned_to: '',
        status: 'In Progress'
    }
}

const addTask = async () => {
    if (!newTask.value.name || !newTask.value.milestone_id || !newTask.value.end_date || !newTask.value.assigned_to) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill in all task fields.'
        })
        return
    }

    try {
        const taskId = Math.max(...setupData.value.schedule.tasks.map(t => t.id || 0), 0) + 1
        const normalizedMilestoneId = Number(newTask.value.milestone_id)
        const payload = {
            id: taskId,
            name: newTask.value.name,
            milestone_id: normalizedMilestoneId,
            end_date: newTask.value.end_date,
            assigned_to: newTask.value.assigned_to,
            status: newTask.value.status
        }

        // API-ready stub
        // const response = await fetch(`/api/projects/${route.params.id}/tasks`, {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(payload)
        // })

        setupData.value.schedule.tasks.push(payload)

        Swal.fire({
            icon: 'success',
            title: 'Task Added',
            text: 'The task has been added successfully.',
            timer: 1500,
            showConfirmButton: false
        })

        closeAddTaskModal()
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Failed to Add Task',
            text: 'There was an error adding the task. Please try again.'
        })
    }
}

const removeTask = (taskId) => {
    Swal.fire({
        title: 'Remove Task?',
        text: 'Do you want to remove this task?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0c8ce9',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            setupData.value.schedule.tasks = setupData.value.schedule.tasks.filter(task => task.id !== taskId)
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'Task has been removed.',
                confirmButtonColor: '#0c8ce9',
                timer: 1500,
                showConfirmButton: false
            })
        }
    })
}

const triggerFileInput = (docId) => {
    const input = document.querySelector(`input[data-doc-id="${docId}"]`)
    if (input) {
        input.click()
    }
}

const uploadDocument = (docId, event) => {
    const file = event.target.files[0]
    if (file) {
        const doc = setupData.value.documents.find(d => d.id === docId)
        if (doc) {
            doc.status = 'Uploaded'
        }
    }
}

const approveAndSend = () => {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Project setup sent for approval. The project will be assigned to the operations team.',
        confirmButtonColor: '#0c8ce9'
    }).then(() => {
        router.push('/projects')
    })
}

const rejectApproval = () => {
    Swal.fire({
        title: 'Reject Setup?',
        text: 'Are you sure you want to reject this project setup?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'info',
                title: 'Setup Rejected',
                text: 'The project setup has been rejected and returned to the Operations team for review.',
                confirmButtonColor: '#0c8ce9'
            }).then(() => {
                router.push('/projects')
            })
        }
    })
}

const handleNoticeUpload = (event) => {
    const file = event.target.files?.[0]
    if (!file) return

    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File',
            text: 'Please upload a PDF file only.'
        })
        event.target.value = ''
        return
    }

    noticeToProccedFile.value = file
}

const handleNoticeDrop = (event) => {
    const files = event.dataTransfer.files
    if (files.length === 0) return

    const file = files[0]
    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File',
            text: 'Please upload a PDF file only.'
        })
        return
    }

    noticeToProccedFile.value = file
}

const submitForApproval = async () => {
    if (!noticeToProccedFile.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Document',
            text: 'Please upload the Notice to Proceed document.'
        })
        return
    }

    submittingApproval.value = true

    try {
        // Simulate API call with FormData
        const formData = new FormData()
        formData.append('notice_to_proceed', noticeToProccedFile.value)
        
        // In a real scenario, you'd do:
        // const response = await fetch(`/api/projects/${route.params.id}/setup/approve`, {
        //     method: 'POST',
        //     headers: { 'Authorization': `Bearer ${token}` },
        //     body: formData
        // })

        // Mock delay
        await new Promise(resolve => setTimeout(resolve, 1500))

        Swal.fire({
            icon: 'success',
            title: 'Submitted for Approval',
            text: 'Your Notice to Proceed has been submitted successfully.',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            showNoticeDrawer.value = false
            noticeToProccedFile.value = null
            router.push('/projects')
        })
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'There was an error submitting your document. Please try again.'
        })
    } finally {
        submittingApproval.value = false
    }
}

const addMilestone = () => {
    if (!newMilestone.value.name || !newMilestone.value.start_date || !newMilestone.value.end_date) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill in all milestone fields.'
        })
        return
    }

    const startDate = new Date(newMilestone.value.start_date)
    const endDate = new Date(newMilestone.value.end_date)
    
    if (startDate > endDate) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Dates',
            text: 'Start date must be before end date.'
        })
        return
    }

    const milestoneId = Math.max(...setupData.value.schedule.milestones.map(m => m.id), 0) + 1
    setupData.value.schedule.milestones.push({
        id: milestoneId,
        name: newMilestone.value.name,
        start_date: newMilestone.value.start_date,
        end_date: newMilestone.value.end_date
    })

    showAddMilestoneModal.value = false
    newMilestone.value = { name: '', start_date: '', end_date: '' }
    
    Swal.fire({
        icon: 'success',
        title: 'Milestone Added',
        text: 'The milestone has been added to the project timeline.',
        timer: 1500,
        showConfirmButton: false
    })
}

// Add Workforce Modal Methods
const addToWorkforceList = () => {
    if (!newWorkforce.value.role || !newWorkforce.value.headcount) {
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: 'Missing Fields',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        return
    }

    if (newWorkforce.value.role === 'Others' && !newWorkforce.value.customRole) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Custom Role',
            text: 'Please specify a custom role.'
        })
        return
    }

    tempWorkforceList.value.push({
        role: newWorkforce.value.role,
        customRole: newWorkforce.value.customRole,
        headcount: newWorkforce.value.headcount
    })

    // Reset form
    newWorkforce.value = {
        role: '',
        customRole: '',
        headcount: 1
    }

    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Added to List',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })
}

const removeFromWorkforceList = (index) => {
    tempWorkforceList.value.splice(index, 1)
}

const closeAddWorkforceModal = () => {
    showAddWorkforceModal.value = false
    tempWorkforceList.value = []
    newWorkforce.value = {
        role: '',
        customRole: '',
        headcount: 1
    }
}

const submitWorkforceList = () => {
    if (tempWorkforceList.value.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Workers',
            text: 'Please add at least one worker to submit.'
        })
        return
    }

    // Add all temp workers to the main workers list
    tempWorkforceList.value.forEach((worker, index) => {
        const workerId = Math.max(...setupData.value.workers.map(w => w.id || 0), 0) + index + 1
        setupData.value.workers.push({
            id: workerId,
            position: worker.role === 'Others' ? worker.customRole : worker.role,
            headcount: worker.headcount,
            status: 'Assigned'
        })
    })

    Swal.fire({
        toast: true,
        icon: 'success',
        title: `${tempWorkforceList.value.length} worker(s) added to workforce`,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    closeAddWorkforceModal()
}

// Add Material Modal Methods
const addToMaterialList = () => {
    const materialName = newMaterial.value.name === 'Others' ? newMaterial.value.customName : newMaterial.value.name
    
    if (!materialName || !newMaterial.value.quantity || !newMaterial.value.unit) {
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: 'Missing Fields',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        return
    }

    tempMaterialsList.value.push({
        name: materialName,
        quantity: newMaterial.value.quantity,
        unit: newMaterial.value.unit
    })

    // Reset form
    newMaterial.value = {
        name: '',
        customName: '',
        quantity: '',
        unit: ''
    }

    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Added to List',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })
}

const updateMaterialUnit = () => {
    if (newMaterial.value.name !== 'Others') {
        newMaterial.value.unit = materialUnits[newMaterial.value.name] || ''
    } else {
        newMaterial.value.unit = ''
    }
}

const removeFromMaterialList = (index) => {
    tempMaterialsList.value.splice(index, 1)
}

const closeAddMaterialModal = () => {
    showAddMaterialModal.value = false
    tempMaterialsList.value = []
    newMaterial.value = {
        name: '',
        customName: '',
        quantity: '',
        unit: ''
    }
}

const submitMaterialList = () => {
    if (tempMaterialsList.value.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Materials',
            text: 'Please add at least one material to submit.'
        })
        return
    }

    // Add all temp materials to the main materials list
    tempMaterialsList.value.forEach((material, index) => {
        const materialId = Math.max(...setupData.value.materials.map(m => m.id || 0), 0) + index + 1
        setupData.value.materials.push({
            id: materialId,
            name: material.name,
            quantity: material.quantity,
            unit: material.unit
        })
    })

    Swal.fire({
        toast: true,
        icon: 'success',
        title: `${tempMaterialsList.value.length} material(s) added`,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    closeAddMaterialModal()
}

// Add Equipment Modal Methods
const addToEquipmentList = () => {
    const equipmentName = newEquipment.value.name === 'Others' ? newEquipment.value.customName : newEquipment.value.name
    
    if (!equipmentName || !newEquipment.value.quantity || !newEquipment.value.type) {
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: 'Missing Fields',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        return
    }

    tempEquipmentList.value.push({
        name: equipmentName,
        quantity: newEquipment.value.quantity,
        type: newEquipment.value.type
    })

    // Reset form
    newEquipment.value = {
        name: '',
        customName: '',
        quantity: '',
        type: ''
    }

    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Added to List',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })
}

const updateEquipmentType = () => {
    if (newEquipment.value.name !== 'Others') {
        newEquipment.value.type = equipmentTypes[newEquipment.value.name] || ''
    } else {
        newEquipment.value.type = ''
    }
}

const removeFromEquipmentList = (index) => {
    tempEquipmentList.value.splice(index, 1)
}

const closeAddEquipmentModal = () => {
    showAddEquipmentModal.value = false
    tempEquipmentList.value = []
    newEquipment.value = {
        name: '',
        customName: '',
        quantity: '',
        type: ''
    }
}

const submitEquipmentList = () => {
    if (tempEquipmentList.value.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Equipment',
            text: 'Please add at least one equipment to submit.'
        })
        return
    }

    // Add all temp equipment to the main equipment list
    tempEquipmentList.value.forEach((equipment, index) => {
        const equipmentId = Math.max(...setupData.value.equipment.map(e => e.id || 0), 0) + index + 1
        setupData.value.equipment.push({
            id: equipmentId,
            name: equipment.name,
            quantity: equipment.quantity,
            type: equipment.type
        })
    })

    Swal.fire({
        toast: true,
        icon: 'success',
        title: `${tempEquipmentList.value.length} equipment item(s) added`,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    closeAddEquipmentModal()
}

// Add Document Modal Methods
const triggerDocumentFileInput = () => {
    const input = document.querySelector('input[ref="documentFileInput"]')
    if (input) {
        input.click()
    }
}

const handleDocumentFileUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        newDocument.value.file = file
    }
}

const handleDocumentDrop = (event) => {
    const files = event.dataTransfer.files
    if (files.length > 0) {
        newDocument.value.file = files[0]
    }
}

const addDocument = () => {
    if (!newDocument.value.name || !newDocument.value.file) {
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please enter document name and upload a file',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        return
    }

    const docId = Math.max(...setupData.value.documents.map(d => d.id || 0), 0) + 1
    const fileUrl = URL.createObjectURL(newDocument.value.file)
    setupData.value.documents.push({
        id: docId,
        name: newDocument.value.name,
        status: 'Uploaded',
        fileName: newDocument.value.file.name,
        fileUrl: fileUrl
    })

    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Document Added',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    closeAddDocumentModal()
}

const closeAddDocumentModal = () => {
    showAddDocumentModal.value = false
    newDocument.value = {
        name: '',
        file: null
    }
}

const removeDocument = (docId) => {
    setupData.value.documents = setupData.value.documents.filter(d => d.id !== docId)
}

const openDocument = (doc) => {
    if (doc.fileUrl) {
        window.open(doc.fileUrl, '_blank')
    }
}

onMounted(() => {
    // In a real scenario, you'd fetch project and setup data from the API here
    auth.pageTitle = 'Project Setup & Contract'
})
</script>
