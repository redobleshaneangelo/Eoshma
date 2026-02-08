<template>
    <auth-layout />
    <div class="ps-60 pe-4 bg-[#F3F4F6] min-h-screen">
        <div class="px-8 py-4">
            <!-- Back Button & Header -->
            <div class="mb-8">
                <router-link
                    to="/prebiddings"
                    class="inline-flex items-center gap-2 text-[#0c8ce9] font-semibold hover:underline mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Pre-Biddings
                </router-link>

                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ prebidding.project_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ prebidding.project_code }} • Client: {{ prebidding.client_name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span :class="statusStyles[prebidding.status]" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold">
                            {{ formatStatus(prebidding.status) }}
                        </span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            {{ prebidding.progress }}% Complete
                        </span>
                        <button
                            @click="showConstructionDrawer = true"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-[#0a7bc7] transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Set as Construction Project
                        </button>
                    </div>
                </div>
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

                <div class="p-8">
                    <!-- Overview Tab -->
                    <div v-if="activeTab === 'overview'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Name</label>
                                <input
                                    type="text"
                                    :value="prebidding.project_name"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Code</label>
                                <input
                                    type="text"
                                    :value="prebidding.project_code"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Type</label>
                                <input
                                    type="text"
                                    :value="prebidding.project_type"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Client</label>
                                <input
                                    type="text"
                                    :value="prebidding.client_name"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Site Address</label>
                                <textarea
                                    :value="prebidding.address"
                                    rows="3"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm resize-none"
                                ></textarea>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Notes</label>
                                <textarea
                                    :value="prebidding.notes"
                                    rows="3"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bid Details Tab -->
                    <div v-if="activeTab === 'bid_details'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bid Submission Deadline</label>
                                <input
                                    type="date"
                                    :value="prebidding.bid_details.deadline"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bid Opening Date</label>
                                <input
                                    type="date"
                                    :value="prebidding.bid_details.opening"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bid Type</label>
                                <input
                                    type="text"
                                    :value="prebidding.bid_details.type"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bond Type</label>
                                <input
                                    type="text"
                                    :value="prebidding.bid_details.bondType"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bid Security Amount</label>
                                <input
                                    type="text"
                                    :value="formatCurrency(prebidding.bid_details.amount)"
                                    readonly
                                    class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm"
                                />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Bidding Activities</h3>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Activity Name</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(activity, index) in prebidding.bidding_activities" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ activity.name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(activity.date) }}</td>
                                        </tr>
                                        <tr v-if="!prebidding.bidding_activities.length">
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No bidding activities recorded.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div v-if="activeTab === 'documents'" class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Document Checklist</h3>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Document Name</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(doc, index) in documentChecklist" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ doc.name }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full"
                                                    :class="doc.completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                                >
                                                    {{ doc.completed ? 'Completed' : 'Pending' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="!documentChecklist.length">
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No documents in checklist.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Upload Documents</h3>
                            <div class="max-w-lg mb-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Choose Document to Upload</label>
                                <select
                                    v-model="selectedDocument"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white outline-none text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">Select document</option>
                                    <option
                                        v-for="doc in documentChecklist.filter(d => !d.completed)"
                                        :key="doc.name"
                                        :value="doc.name"
                                    >
                                        {{ doc.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    accept=".pdf"
                                    class="hidden"
                                    id="fileUploadView"
                                />
                                <label
                                    for="fileUploadView"
                                    @drop.prevent="handleFileDrop"
                                    @dragover.prevent
                                    class="border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 p-12 flex flex-col items-center justify-center transition-colors hover:border-blue-500 hover:bg-blue-50 cursor-pointer"
                                >
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-gray-700 font-medium mb-1">Click to upload or drag and drop</p>
                                    <p class="text-gray-500 text-sm">PDF files only</p>
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Uploaded Documents</h3>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Document Name</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Uploaded By</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Upload Date</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(doc, index) in documentUploaded" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ doc.name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ doc.by }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ doc.uploadedAt }}</td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <button
                                                    @click="viewDocument(doc)"
                                                    class="text-blue-600 hover:text-blue-800 font-medium"
                                                >
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!documentUploaded.length">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No documents uploaded yet.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Project Setup Tab -->
                    <div v-if="activeTab === 'project_setup'" class="space-y-6">
                        <!-- Required Workforce -->
                        <div class="bg-white">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Required Workforce</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddWorkforceModal = true"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-[#0a7bc7] transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Workforce
                                    </button>
                                    <button
                                        @click="isEditingWorkforce = !isEditingWorkforce"
                                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        {{ isEditingWorkforce ? 'Done' : 'Edit' }}
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Position</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Headcount</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(worker, index) in workforce" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <input
                                                    v-if="isEditingWorkforce"
                                                    v-model="worker.position"
                                                    type="text"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ worker.position }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <input
                                                    v-if="isEditingWorkforce"
                                                    v-model.number="worker.headcount"
                                                    type="number"
                                                    min="0"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ worker.headcount }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <button @click="removeWorkforce(index)" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                            </td>
                                        </tr>
                                        <tr v-if="!workforce.length">
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No workers added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Materials -->
                        <div class="bg-white">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Materials</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddMaterialModal = true"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-[#0a7bc7] transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Material
                                    </button>
                                    <button
                                        @click="isEditingMaterials = !isEditingMaterials"
                                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        {{ isEditingMaterials ? 'Done' : 'Edit' }}
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(material, index) in projectMaterials" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <input
                                                    v-if="isEditingMaterials"
                                                    v-model="material.name"
                                                    type="text"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ material.name }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <input
                                                    v-if="isEditingMaterials"
                                                    v-model.number="material.quantity"
                                                    type="number"
                                                    min="0"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ material.quantity }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <input
                                                    v-if="isEditingMaterials"
                                                    v-model="material.unit"
                                                    type="text"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ material.unit }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <button @click="removeMaterial(index)" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                            </td>
                                        </tr>
                                        <tr v-if="!projectMaterials.length">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No materials added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Equipment -->
                        <div class="bg-white">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Equipment</h3>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showAddEquipmentModal = true"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-[#0a7bc7] transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Equipment
                                    </button>
                                    <button
                                        @click="isEditingEquipment = !isEditingEquipment"
                                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 10v2m-7-6h14" />
                                        </svg>
                                        {{ isEditingEquipment ? 'Done' : 'Edit' }}
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Equipment</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(equip, index) in projectEquipment" :key="index" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <input
                                                    v-if="isEditingEquipment"
                                                    v-model="equip.name"
                                                    type="text"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ equip.name }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <input
                                                    v-if="isEditingEquipment"
                                                    v-model.number="equip.quantity"
                                                    type="number"
                                                    min="0"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ equip.quantity }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <input
                                                    v-if="isEditingEquipment"
                                                    v-model="equip.type"
                                                    type="text"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                />
                                                <span v-else>{{ equip.type }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <button @click="removeEquipment(index)" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                            </td>
                                        </tr>
                                        <tr v-if="!projectEquipment.length">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                No equipment added yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Subcontractors Tab -->
                    <div v-if="activeTab === 'subcontractors'">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Subcontractor Planning</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Subcontractor</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Scope</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(sub, index) in prebidding.subcontractors" :key="index" class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ sub.name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ sub.scope }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">₱ {{ formatCurrency(sub.cost) }}</td>
                                    </tr>
                                    <tr v-if="!prebidding.subcontractors.length">
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            No subcontractors recorded.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Financial Review Tab -->
                    <div v-if="activeTab === 'financial'" class="space-y-6">
                        <div class="grid grid-cols-4 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-600 text-xs font-semibold uppercase">Total Budget</div>
                                <div class="text-lg font-bold text-gray-900 mt-2">₱ {{ formatCurrency(prebidding.financial.total_budget) }}</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-600 text-xs font-semibold uppercase">Contract Amount</div>
                                <div class="text-lg font-bold text-gray-900 mt-2">₱ {{ formatCurrency(prebidding.financial.contract_amount) }}</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-600 text-xs font-semibold uppercase">Billing Type</div>
                                <div class="text-lg font-bold text-gray-900 mt-2">{{ prebidding.financial.billing_type }}</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-600 text-xs font-semibold uppercase">Retention (%)</div>
                                <div class="text-lg font-bold text-gray-900 mt-2">{{ prebidding.financial.retention }}</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold text-gray-800 text-base">Budget Utilization</h4>
                                <span class="text-sm font-bold text-blue-600">
                                    {{ budgetUtilizationPercentage.toFixed(1) }}%
                                </span>
                            </div>
                            <div class="mb-2">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>₱ {{ prebidding.financial.total_budget_utilized.toLocaleString() }}</span>
                                    <span>₱ {{ prebidding.financial.total_budget_set.toLocaleString() }}</span>
                                </div>
                                <div class="relative w-full bg-gray-200 h-3 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-linear-to-r from-blue-500 to-blue-600"
                                        :style="{ width: `${Math.min(budgetUtilizationPercentage, 100)}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scope Changes Tab -->
                    <div v-if="activeTab === 'scope_changes'">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Scope Changes</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Change ID</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Impact</th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(change, index) in scopeChanges" :key="index" class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ change.code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(change.date) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ change.description }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">₱ {{ formatCurrency(change.impact_cost) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" :class="scopeStatusStyles[change.status]">
                                                {{ change.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!scopeChanges.length">
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            No scope changes recorded.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Set as Construction Project Drawer -->
            <div v-if="showConstructionDrawer" class="fixed inset-0 z-50 overflow-hidden">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 transition-opacity" @click="showConstructionDrawer = false"></div>
                
                <!-- Drawer -->
                <div class="absolute inset-y-0 right-0 max-w-md w-full bg-white shadow-xl flex flex-col">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Set as Construction Project</h3>
                            <button @click="showConstructionDrawer = false" class="text-gray-400 hover:text-gray-600">
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
                                <p class="text-base font-bold text-gray-900">{{ prebidding.project_name }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ prebidding.project_code }}</p>
                            </div>

                            <!-- Notice of Award Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Notice of Award
                                    <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- File Upload Area -->
                                <div
                                    @drop.prevent="handleNoticeOfAwardDrop"
                                    @dragover.prevent
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0c8ce9] transition-colors cursor-pointer"
                                    @click="$refs.noticeFileInput.click()"
                                >
                                    <div v-if="!noticeOfAwardFile">
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
                                            <p class="text-sm font-semibold text-gray-900">{{ noticeOfAwardFile.name }}</p>
                                            <p class="text-xs text-gray-500">{{ (noticeOfAwardFile.size / 1024).toFixed(2) }} KB</p>
                                        </div>
                                        <button
                                            @click.stop="noticeOfAwardFile = null"
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
                                    @change="handleNoticeOfAwardUpload"
                                />
                            </div>

                            <!-- Info Message -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-800 mb-1">Important</p>
                                        <p class="text-xs text-yellow-700">
                                            By sending this to Operations, you are confirming that this pre-bidding project has been awarded and should be converted to an active construction project.
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
                                @click="showConstructionDrawer = false"
                                class="flex-1 px-4 py-2.5 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                @click="sendToOperations"
                                :disabled="!noticeOfAwardFile || sendingToOperations"
                                class="flex-1 px-4 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-[#0a7bc7] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="!sendingToOperations">Send to Operations</span>
                                <span v-else class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Workforce Modal -->
            <div v-if="showAddWorkforceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Add Workforce</h3>
                            <button @click="closeAddWorkforceModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Input Fields -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Role / Position</label>
                                    <select v-model="newWorkforce.role" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select role</option>
                                        <option value="Project Manager">Project Manager</option>
                                        <option value="Site Engineer">Site Engineer</option>
                                        <option value="Foreman">Foreman</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Safety Officer">Safety Officer</option>
                                        <option value="Quality Control">Quality Control</option>
                                        <option value="Laborer">Laborer</option>
                                        <option value="Operator">Operator</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-2" v-if="newWorkforce.role === 'Others'">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Specify Custom Role</label>
                                    <input v-model="newWorkforce.customRole" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter custom role">
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Headcount</label>
                                    <input v-model.number="newWorkforce.headcount" type="number" min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter headcount">
                                </div>
                            </div>
                            
                            <button @click="addToWorkforceList" class="w-full px-4 py-3 bg-[#0c8ce9] text-white rounded font-semibold text-sm hover:bg-[#0a7bc7] transition-colors">
                                Add to List
                            </button>
                        </div>

                        <!-- Temporary Workforce List -->
                        <div class="border-t pt-6">
                            <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Workforce List</h4>
                            <div class="border border-gray-200 rounded overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Role</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Headcount</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(item, index) in tempWorkforceList" :key="index">
                                            <td class="px-4 py-3 text-gray-900">{{ item.role === 'Others' ? item.customRole : item.role }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ item.headcount }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button @click="removeFromWorkforceList(index)" class="text-red-600 hover:text-red-800 font-semibold text-xs">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!tempWorkforceList.length">
                                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                                No workers added yet. Add workers to the list above.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                        <button @click="closeAddWorkforceModal" class="flex-1 px-4 py-2.5 border border-gray-300 bg-white text-gray-700 rounded font-semibold text-sm hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="submitWorkforceList" :disabled="!tempWorkforceList.length" class="flex-1 px-4 py-2.5 bg-gray-400 text-white rounded font-semibold text-sm hover:bg-gray-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :class="tempWorkforceList.length ? 'bg-[#0c8ce9] hover:bg-[#0a7bc7]' : ''">
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Input Fields -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Material Name</label>
                                    <select v-model="newMaterial.name" @change="updateMaterialUnit" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select material</option>
                                        <option value="Cement">Cement</option>
                                        <option value="Steel Bars">Steel Bars</option>
                                        <option value="Bricks">Bricks</option>
                                        <option value="Sand">Sand</option>
                                        <option value="Gravel">Gravel</option>
                                        <option value="Concrete">Concrete</option>
                                        <option value="Paint">Paint</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-2" v-if="newMaterial.name === 'Others'">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Specify Custom Material</label>
                                    <input v-model="newMaterial.customName" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter custom material name">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Quantity</label>
                                    <input v-model.number="newMaterial.quantity" type="number" min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter quantity">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Unit</label>
                                    <input v-model="newMaterial.unit" type="text" :readonly="newMaterial.name !== 'Others'" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" :class="newMaterial.name !== 'Others' ? 'bg-gray-50' : ''" placeholder="Unit">
                                </div>
                            </div>
                            
                            <button @click="addToMaterialList" class="w-full px-4 py-3 bg-[#0c8ce9] text-white rounded font-semibold text-sm hover:bg-[#0a7bc7] transition-colors">
                                Add to List
                            </button>
                        </div>

                        <!-- Temporary Material List -->
                        <div class="border-t pt-6">
                            <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Material List</h4>
                            <div class="border border-gray-200 rounded overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Material</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Unit</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(item, index) in tempMaterialsList" :key="index">
                                            <td class="px-4 py-3 text-gray-900">{{ item.name }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ item.quantity }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ item.unit }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button @click="removeFromMaterialList(index)" class="text-red-600 hover:text-red-800 font-semibold text-xs">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!tempMaterialsList.length">
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                No materials added yet. Add materials to the list above.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                        <button @click="closeAddMaterialModal" class="flex-1 px-4 py-2.5 border border-gray-300 bg-white text-gray-700 rounded font-semibold text-sm hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="submitMaterialList" :disabled="!tempMaterialsList.length" class="flex-1 px-4 py-2.5 bg-gray-400 text-white rounded font-semibold text-sm hover:bg-gray-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :class="tempMaterialsList.length ? 'bg-[#0c8ce9] hover:bg-[#0a7bc7]' : ''">
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Input Fields -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Equipment Name</label>
                                    <select v-model="newEquipment.name" @change="updateEquipmentType" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select equipment</option>
                                        <option value="Excavator">Excavator</option>
                                        <option value="Concrete Mixer">Concrete Mixer</option>
                                        <option value="Bulldozer">Bulldozer</option>
                                        <option value="Crane">Crane</option>
                                        <option value="Compactor">Compactor</option>
                                        <option value="Scaffolding">Scaffolding</option>
                                        <option value="Power Tools">Power Tools</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-2" v-if="newEquipment.name === 'Others'">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Specify Custom Equipment</label>
                                    <input v-model="newEquipment.customName" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter custom equipment name">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Quantity</label>
                                    <input v-model.number="newEquipment.quantity" type="number" min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter quantity">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Type</label>
                                    <select v-model="newEquipment.type" :disabled="newEquipment.name !== 'Others'" class="w-full px-4 py-2.5 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" :class="newEquipment.name !== 'Others' ? 'bg-gray-50' : ''">
                                        <option value="">Select type</option>
                                        <option value="Heavy">Heavy</option>
                                        <option value="Light">Light</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button @click="addToEquipmentList" class="w-full px-4 py-3 bg-[#0c8ce9] text-white rounded font-semibold text-sm hover:bg-[#0a7bc7] transition-colors">
                                Add to List
                            </button>
                        </div>

                        <!-- Temporary Equipment List -->
                        <div class="border-t pt-6">
                            <h4 class="text-sm font-bold text-gray-900 mb-3">Temporary Equipment List</h4>
                            <div class="border border-gray-200 rounded overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Equipment</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(item, index) in tempEquipmentList" :key="index">
                                            <td class="px-4 py-3 text-gray-900">{{ item.name === 'Others' ? item.customName : item.name }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ item.quantity }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ item.type }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button @click="removeFromEquipmentList(index)" class="text-red-600 hover:text-red-800 font-semibold text-xs">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!tempEquipmentList.length">
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                No equipment added yet. Add equipment to the list above.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3 sticky bottom-0 bg-white">
                        <button @click="closeAddEquipmentModal" class="flex-1 px-4 py-2.5 border border-gray-300 bg-white text-gray-700 rounded font-semibold text-sm hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="submitEquipmentList" :disabled="!tempEquipmentList.length" class="flex-1 px-4 py-2.5 bg-gray-400 text-white rounded font-semibold text-sm hover:bg-gray-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :class="tempEquipmentList.length ? 'bg-[#0c8ce9] hover:bg-[#0a7bc7]' : ''">
                            Submit Equipment
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="fixed bottom-6 right-6 z-50" v-if="loading">
                <div class="bg-white rounded-full shadow-lg p-3">
                    <svg class="animate-spin h-6 w-6 text-[#0c8ce9]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import AuthLayout from '@/views/Layouts/AuthLayout.vue'

const route = useRoute()
const router = useRouter()

const activeTab = ref('overview')
const loading = ref(false)
const selectedDocument = ref('')
const showConstructionDrawer = ref(false)
const noticeOfAwardFile = ref(null)
const sendingToOperations = ref(false)

// Project Setup modals and data
const showAddWorkforceModal = ref(false)
const showAddMaterialModal = ref(false)
const showAddEquipmentModal = ref(false)

const isEditingWorkforce = ref(false)
const isEditingMaterials = ref(false)
const isEditingEquipment = ref(false)

const workforce = ref([])
const projectMaterials = ref([
    { name: 'Cement', quantity: 500, unit: 'Bags' },
    { name: 'Steel Bars', quantity: 100, unit: 'pcs' }
])
const projectEquipment = ref([
    { name: 'Excavator', quantity: 2, type: 'Heavy' },
    { name: 'Concrete Mixer', quantity: 1, type: 'Light' }
])

const newWorkforce = ref({
    role: '',
    customRole: '',
    headcount: 1
})

const newMaterial = ref({
    name: '',
    customName: '',
    quantity: '',
    unit: ''
})

const newEquipment = ref({
    name: '',
    customName: '',
    quantity: '',
    type: ''
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

const equipmentTypes = {
    'Excavator': 'Heavy',
    'Concrete Mixer': 'Light',
    'Bulldozer': 'Heavy',
    'Crane': 'Heavy',
    'Compactor': 'Light',
    'Scaffolding': 'Light',
    'Power Tools': 'Light'
}

// Temporary lists for batch adding
const tempWorkforceList = ref([])
const tempMaterialsList = ref([])
const tempEquipmentList = ref([])

const tabs = [
    { key: 'overview', label: 'Overview' },
    { key: 'bid_details', label: 'Bid Details' },
    { key: 'documents', label: 'Documents' },
    { key: 'project_setup', label: 'Project Setup' },
    { key: 'subcontractors', label: 'Subcontractors' },
    { key: 'financial', label: 'Financial Review' },
    { key: 'scope_changes', label: 'Scope Changes' }
]

const statusStyles = {
    active: 'bg-green-100 text-green-800 border border-green-300',
    draft: 'bg-gray-100 text-gray-800 border border-gray-300',
    pending: 'bg-yellow-100 text-yellow-800 border border-yellow-300',
    rejected: 'bg-red-100 text-red-800 border border-red-300'
}

const scopeStatusStyles = {
    Approved: 'bg-green-100 text-green-800',
    Pending: 'bg-yellow-100 text-yellow-800',
    Rejected: 'bg-red-100 text-red-800'
}

const prebidding = ref({
    id: 1,
    project_name: 'Office Building Renovation',
    project_code: 'PRJ-2026-001',
    project_type: 'Commercial',
    client_name: 'Acme Corporation',
    address: '123 Business Ave, Metro City',
    notes: 'Renovation of existing office building with modern finishes.',
    status: 'active',
    progress: 75,
    bid_details: {
        deadline: '2026-02-15',
        opening: '2026-02-18',
        type: 'Open',
        bondType: 'Bid Bond',
        amount: 150000
    },
    bidding_activities: [
        { name: 'Pre-Bid Conference', date: '2026-02-05' },
        { name: 'Site Visit', date: '2026-02-07' }
    ],
    materials: [
        { name: 'Cement', quantity: 500, unit: 'Bags', source: 'Stock' },
        { name: 'Steel Bars', quantity: 100, unit: 'pcs', source: 'Purchase' }
    ],
    equipment: [
        { name: 'Excavator', quantity: 2, type: 'Heavy' },
        { name: 'Concrete Mixer', quantity: 1, type: 'Light' }
    ],
    subcontractors: [
        { name: 'Electrical Works Inc.', scope: 'Electrical', cost: 250000 },
        { name: 'Plumbing Specialists', scope: 'Plumbing', cost: 180000 }
    ],
    team: {
        supervisor: 'Carlos Santos',
        foreman: 'Luis Gomez'
    },
    financial: {
        total_budget: 2500000,
        contract_amount: 2800000,
        billing_type: 'Progress',
        retention: 10,
        total_budget_set: 2500000,
        total_budget_utilized: 1950000
    }
})

const documentChecklist = ref([
    { name: 'Company Profile', completed: true },
    { name: 'Tax Clearance', completed: true },
    { name: 'Bid Bond', completed: false },
    { name: 'Technical Proposal', completed: false }
])

const documentUploaded = ref([
    { name: 'Company Profile', by: 'Operations', uploadedAt: 'Feb 01, 2026', fileUrl: '' },
    { name: 'Tax Clearance', by: 'Operations', uploadedAt: 'Feb 02, 2026', fileUrl: '' }
])

const scopeChanges = ref([
    {
        code: 'SC-001',
        date: '2026-02-01',
        description: 'Additional electrical outlets per floor',
        impact_cost: 50000,
        status: 'Approved'
    },
    {
        code: 'SC-002',
        date: '2026-02-03',
        description: 'Change of flooring material to premium tiles',
        impact_cost: 120000,
        status: 'Pending'
    }
])

const budgetUtilizationPercentage = computed(() => {
    if (!prebidding.value.financial.total_budget_set) return 0
    return (prebidding.value.financial.total_budget_utilized / prebidding.value.financial.total_budget_set) * 100
})

const formatCurrency = (value) => {
    return parseFloat(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const formatStatus = (status) => {
    const map = {
        active: 'Active',
        draft: 'Draft',
        pending: 'Pending',
        rejected: 'Rejected'
    }
    return map[status] || status
}

const handleFileUpload = (event) => {
    if (!selectedDocument.value) return
    const file = event.target.files[0]
    if (!file) return

    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        event.target.value = ''
        return
    }

    const documentIndex = documentChecklist.value.findIndex(doc => doc.name === selectedDocument.value)
    if (documentIndex !== -1) {
        documentChecklist.value[documentIndex].completed = true
    }

    documentUploaded.value.push({
        name: selectedDocument.value,
        by: 'Operations',
        uploadedAt: new Date().toLocaleDateString('en-PH'),
        fileUrl: URL.createObjectURL(file)
    })

    selectedDocument.value = ''
    event.target.value = ''
}

const handleFileDrop = (event) => {
    if (!selectedDocument.value) return
    const file = event.dataTransfer.files[0]
    if (!file) return

    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        return
    }

    const documentIndex = documentChecklist.value.findIndex(doc => doc.name === selectedDocument.value)
    if (documentIndex !== -1) {
        documentChecklist.value[documentIndex].completed = true
    }

    documentUploaded.value.push({
        name: selectedDocument.value,
        by: 'Operations',
        uploadedAt: new Date().toLocaleDateString('en-PH'),
        fileUrl: URL.createObjectURL(file)
    })

    selectedDocument.value = ''
}

const viewDocument = (doc) => {
    if (doc.fileUrl) {
        window.open(doc.fileUrl, '_blank')
    }
}

const handleNoticeOfAwardUpload = (event) => {
    const file = event.target.files[0]
    if (!file) return

    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File',
            text: 'Please upload a PDF file only.',
            confirmButtonColor: '#0c8ce9'
        })
        event.target.value = ''
        return
    }

    noticeOfAwardFile.value = file
    event.target.value = ''
}

const handleNoticeOfAwardDrop = (event) => {
    const file = event.dataTransfer.files[0]
    if (!file) return

    if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File',
            text: 'Please upload a PDF file only.',
            confirmButtonColor: '#0c8ce9'
        })
        return
    }

    noticeOfAwardFile.value = file
}

const sendToOperations = async () => {
    if (!noticeOfAwardFile.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Document',
            text: 'Please upload the Notice of Award document.',
            confirmButtonColor: '#0c8ce9'
        })
        return
    }

    try {
        sendingToOperations.value = true

        const formData = new FormData()
        formData.append('notice_of_award', noticeOfAwardFile.value)
        formData.append('prebidding_id', prebidding.value.id)

        // Uncomment when API is ready
        // const response = await axios.post(`/api/prebiddings/${prebidding.value.id}/convert-to-project`, formData, {
        //     headers: {
        //         'Content-Type': 'multipart/form-data'
        //     }
        // })

        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1500))

        showConstructionDrawer.value = false
        noticeOfAwardFile.value = null

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Pre-bidding project has been sent to Operations for construction project conversion.',
            confirmButtonColor: '#0c8ce9'
        }).then(() => {
            // Optionally redirect to projects list
            // router.push('/prebiddings')
        })

    } catch (error) {
        console.error('Failed to send to operations:', error)
        Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: 'Failed to send to Operations. Please try again.',
            confirmButtonColor: '#0c8ce9'
        })
    } finally {
        sendingToOperations.value = false
    }
}

// Workforce functions
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

    tempWorkforceList.value.forEach((worker, index) => {
        const workerId = Math.max(...workforce.value.map(w => w.id || 0), 0) + index + 1
        workforce.value.push({
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

const removeWorkforce = (index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to remove this workforce?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0c8ce9',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            workforce.value.splice(index, 1)
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'Workforce has been removed.',
                confirmButtonColor: '#0c8ce9',
                timer: 1500,
                showConfirmButton: false
            })
        }
    })
}

// Material functions
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

    tempMaterialsList.value.forEach((material, index) => {
        const materialId = Math.max(...projectMaterials.value.map(m => m.id || 0), 0) + index + 1
        projectMaterials.value.push({
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

const removeMaterial = (index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to remove this material?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0c8ce9',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            projectMaterials.value.splice(index, 1)
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'Material has been removed.',
                confirmButtonColor: '#0c8ce9',
                timer: 1500,
                showConfirmButton: false
            })
        }
    })
}

// Equipment functions
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

    tempEquipmentList.value.forEach((equipment, index) => {
        const equipmentId = Math.max(...projectEquipment.value.map(e => e.id || 0), 0) + index + 1
        projectEquipment.value.push({
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

const removeEquipment = (index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to remove this equipment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0c8ce9',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            projectEquipment.value.splice(index, 1)
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'Equipment has been removed.',
                confirmButtonColor: '#0c8ce9',
                timer: 1500,
                showConfirmButton: false
            })
        }
    })
}

const fetchPrebidding = async () => {
    try {
        loading.value = true
        const id = route.params.id
        // const response = await axios.get(`/api/prebiddings/${id}`)
        // prebidding.value = response.data.prebidding
        // documentChecklist.value = response.data.documents?.checklist || []
        // documentUploaded.value = response.data.documents?.uploaded || []
        // scopeChanges.value = response.data.scope_changes || []
    } catch (error) {
        console.error('Failed to fetch pre-bidding:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchPrebidding()
})
</script>
