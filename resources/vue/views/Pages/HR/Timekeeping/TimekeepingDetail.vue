<template>
    <auth-layout />
        <div class="ps-60 bg-[#F3F4F6] min-h-screen">
            <div class="px-10 py-10">
                    <!-- Back Button -->
                    <button
                        @click="goBack"
                        class="flex items-center text-[#0c8ce9] hover:text-blue-700 font-semibold mb-4 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Timekeeping
                    </button>

                    <!-- Header Card -->
                    <div class="bg-white rounded-lg border border-gray-100 p-6 mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-[#333333]">{{ dateTitle }}</h1>
                                <p class="text-sm text-gray-600 mt-1">Timekeeping Records</p>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    @click="exportToPDF"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-4 gap-4 mb-8">
                        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Total Records</p>
                            <p class="text-4xl font-bold text-[#0c8ce9]">{{ dayData.employees.length }}</p>
                            <p class="text-xs text-gray-500 mt-2">Employees</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Present</p>
                            <p class="text-4xl font-bold text-green-500">{{ presentCount }}</p>
                            <p class="text-xs text-gray-500 mt-2">On Time</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Late</p>
                            <p class="text-4xl font-bold text-yellow-500">{{ lateCount }}</p>
                            <p class="text-xs text-gray-500 mt-2">Delayed</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">AWOL</p>
                            <p class="text-4xl font-bold text-red-500">{{ awolCount }}</p>
                            <p class="text-xs text-gray-500 mt-2">Absent</p>
                        </div>
                    </div>

                    <!-- Filter and Search -->
                    <div class="bg-white rounded-lg p-6 border border-gray-100 mb-8">
                        <div class="grid grid-cols-2 gap-4">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search by employee name or ID..."
                                class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                            />
                            <select
                                v-model="statusFilter"
                                class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#0c8ce9] focus:border-transparent"
                            >
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="awol">AWOL</option>
                            </select>
                        </div>
                    </div>

                    <!-- Records Table -->
                    <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Employee ID
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Name
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Time In
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Time Out
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Work Hours
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr
                                        v-for="employee in filteredEmployees"
                                        :key="employee.id"
                                        class="hover:bg-gray-50 transition-colors"
                                    >
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">#{{ String(employee.id).padStart(4, '0') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 font-medium">{{ employee.name }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="employee.timeIn" class="text-gray-700">{{ employee.timeIn }}</span>
                                            <span v-else class="text-gray-400 italic">—</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="employee.timeOut" class="text-gray-700">{{ employee.timeOut }}</span>
                                            <span v-else class="text-gray-400 italic">—</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="employee.timeIn && employee.timeOut" class="text-gray-700">
                                                {{ calculateWorkHours(employee.timeIn, employee.timeOut) }}h
                                            </span>
                                            <span v-else class="text-gray-400 italic">—</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                :class="getStatusBadgeClass(employee.status)"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold"
                                            >
                                                <svg v-if="employee.status === 'present'" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                                <svg v-else-if="employee.status === 'late'" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                                                <svg v-else-if="employee.status === 'awol'" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
                                                {{ capitalizeStatus(employee.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button
                                                @click="editRecord(employee)"
                                                class="text-[#0c8ce9] hover:text-blue-700 font-semibold text-sm transition-colors"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="filteredEmployees.length === 0" class="p-12 text-center">
                            <div class="text-gray-400 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-600 font-medium">No records found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your filters</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 right-0 p-2 z-50">
                <img :src="agnes" class="h-auto w-auto" v-if="loading" />
            </div>
    
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import axios from 'axios'
    import { useAuthStore } from '@/stores/auth'
    import AuthLayout from '@/views/Layouts/AuthLayout.vue'
    import agnes from '@/assets/agnes.gif'
    import Swal from 'sweetalert2'

    const auth = useAuthStore()
    const router = useRouter()
    const route = useRoute()

    const loading = ref(false)
    const searchQuery = ref('')
    const statusFilter = ref('')

    // Mock data - Replace with API call
    const dayData = ref({
        date: '2026-01-31',
        dayOfWeek: 'Monday',
        employees: [
            { id: 1, name: 'John Smith', timeIn: '08:00', timeOut: '17:00', status: 'present' },
            { id: 2, name: 'Jane Doe', timeIn: '08:05', timeOut: '17:30', status: 'late' },
            { id: 3, name: 'Bob Johnson', timeIn: null, timeOut: null, status: 'awol' },
            { id: 4, name: 'Alice Brown', timeIn: '08:00', timeOut: '17:00', status: 'present' },
            { id: 5, name: 'Charlie Wilson', timeIn: '08:15', timeOut: '17:00', status: 'late' },
            { id: 6, name: 'Diana Garcia', timeIn: '08:00', timeOut: '16:45', status: 'present' }
        ]
    })

    const dateTitle = computed(() => {
        const routeTitle = route.query.dateTitle
        return routeTitle || 'Timekeeping Detail'
    })

    const presentCount = computed(() => {
        return dayData.value.employees.filter(e => e.status === 'present').length
    })

    const lateCount = computed(() => {
        return dayData.value.employees.filter(e => e.status === 'late').length
    })

    const awolCount = computed(() => {
        return dayData.value.employees.filter(e => e.status === 'awol').length
    })

    const filteredEmployees = computed(() => {
        return dayData.value.employees.filter(emp => {
            const matchesSearch = emp.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                                  String(emp.id).includes(searchQuery.value)
            const matchesStatus = !statusFilter.value || emp.status === statusFilter.value
            return matchesSearch && matchesStatus
        })
    })

    // Helper: Calculate work hours
    const calculateWorkHours = (timeIn, timeOut) => {
        if (!timeIn || !timeOut) return '0'
        const [inH, inM] = timeIn.split(':').map(Number)
        const [outH, outM] = timeOut.split(':').map(Number)
        const inMinutes = inH * 60 + inM
        const outMinutes = outH * 60 + outM
        const diffMinutes = outMinutes - inMinutes
        const hours = Math.floor(diffMinutes / 60)
        const minutes = diffMinutes % 60
        return minutes > 0 ? `${hours}.${String(Math.round(minutes / 6)).padStart(2, '0')}` : String(hours)
    }

    // Helper: Get status badge class
    const getStatusBadgeClass = (status) => {
        const classes = {
            present: 'bg-green-50 text-green-700',
            late: 'bg-yellow-50 text-yellow-700',
            awol: 'bg-red-50 text-red-700'
        }
        return classes[status] || 'bg-gray-50 text-gray-700'
    }

    // Helper: Capitalize status
    const capitalizeStatus = (status) => {
        return status.charAt(0).toUpperCase() + status.slice(1)
    }

    // Actions
    const goBack = () => {
        router.back()
    }

    const editRecord = (employee) => {
        Swal.fire({
            title: `Edit Record - ${employee.name}`,
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Time In</label>
                        <input type="time" id="timeIn" value="${employee.timeIn || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Time Out</label>
                        <input type="time" id="timeOut" value="${employee.timeOut || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="present" ${employee.status === 'present' ? 'selected' : ''}>Present</option>
                            <option value="late" ${employee.status === 'late' ? 'selected' : ''}>Late</option>
                            <option value="awol" ${employee.status === 'awol' ? 'selected' : ''}>AWOL</option>
                        </select>
                    </div>
                </div>
            `,
            confirmButtonText: 'Save Changes',
            confirmButtonColor: '#0c8ce9',
            cancelButtonText: 'Cancel',
            showCancelButton: true,
            didOpen: () => {
                document.getElementById('timeIn').focus()
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const timeIn = document.getElementById('timeIn').value
                const timeOut = document.getElementById('timeOut').value
                const status = document.getElementById('status').value

                const emp = dayData.value.employees.find(e => e.id === employee.id)
                if (emp) {
                    emp.timeIn = timeIn || null
                    emp.timeOut = timeOut || null
                    emp.status = status
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Record Updated',
                    text: `${employee.name}'s timekeeping record has been updated.`,
                    confirmButtonColor: '#0c8ce9'
                })
            }
        })
    }

    const exportToPDF = async () => {
        try {
            loading.value = true
            
            // Create print window with all data
            const printWindow = window.open('', '', 'height=800,width=1000')
            
            let htmlContent = `
                <html>
                    <head>
                        <title>Timekeeping Report - ${dayData.value.date}</title>
                        <style>
                            * { margin: 0; padding: 0; box-sizing: border-box; }
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; }
                            h1 { font-size: 28px; font-weight: bold; margin-bottom: 10px; color: #1f2937; }
                            .date-info { color: #666; margin-bottom: 20px; font-size: 14px; }
                            .summary-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
                            .summary-card { border: 1px solid #ddd; padding: 15px; border-radius: 5px; text-align: center; background: #f9fafb; }
                            .summary-card-label { font-size: 12px; color: #999; margin-bottom: 10px; text-transform: uppercase; font-weight: bold; }
                            .summary-card-value { font-size: 32px; font-weight: bold; }
                            .table-section { margin-top: 30px; }
                            .table-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #1f2937; }
                            table { width: 100%; border-collapse: collapse; }
                            th { background-color: #f3f4f6; border-bottom: 2px solid #d1d5db; padding: 12px; text-align: left; font-size: 12px; font-weight: bold; color: #374151; }
                            td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
                            tr:nth-child(even) { background-color: #f9fafb; }
                            .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; text-align: right; }
                            @media print {
                                body { margin: 0; padding: 0; }
                                .table-section { page-break-inside: avoid; }
                            }
                        </style>
                    </head>
                    <body>
                        <h1>${dateTitle.value}</h1>
                        <div class="date-info">Date: ${dayData.value.date} (${dayData.value.dayOfWeek})</div>
                        
                        <div class="summary-cards">
                            <div class="summary-card">
                                <div class="summary-card-label">Total Records</div>
                                <div class="summary-card-value" style="color: #0c8ce9;">${dayData.value.employees.length}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">Present</div>
                                <div class="summary-card-value" style="color: #10b981;">${presentCount.value}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">Late</div>
                                <div class="summary-card-value" style="color: #f59e0b;">${lateCount.value}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">AWOL</div>
                                <div class="summary-card-value" style="color: #ef4444;">${awolCount.value}</div>
                            </div>
                        </div>
                        
                        <div class="table-section">
                            <div class="table-title">Timekeeping Records</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Work Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${filteredEmployees.value.map(emp => `
                                        <tr>
                                            <td>#${String(emp.id).padStart(4, '0')}</td>
                                            <td>${emp.name}</td>
                                            <td>${emp.timeIn || '—'}</td>
                                            <td>${emp.timeOut || '—'}</td>
                                            <td>${(emp.timeIn && emp.timeOut) ? calculateWorkHours(emp.timeIn, emp.timeOut) + 'h' : '—'}</td>
                                            <td>${emp.status.charAt(0).toUpperCase() + emp.status.slice(1)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="footer">Generated on ${new Date().toLocaleString()}</div>
                    </body>
                </html>
            `
            
            printWindow.document.write(htmlContent)
            printWindow.document.close()
            
            // Trigger download dialog
            setTimeout(() => {
                printWindow.focus()
                printWindow.print()
                // Close after print
                setTimeout(() => printWindow.close(), 1000)
            }, 250)
            
            Swal.fire({
                icon: 'success',
                title: 'PDF Ready',
                text: `Timekeeping report for ${dayData.value.date} is ready for download.`,
                confirmButtonColor: '#0c8ce9'
            })
        } catch (error) {
            console.error('Error exporting PDF:', error)
            Swal.fire({
                icon: 'error',
                title: 'Export Failed',
                text: 'Failed to export PDF. Please try again.',
                confirmButtonColor: '#ef4444'
            })
        } finally {
            loading.value = false
        }
    }

    const printRecords = () => {
        try {
            // Create a new window for printing
            const printWindow = window.open('', '', 'height=800,width=1000')
            
            let htmlContent = `
                <html>
                    <head>
                        <title>Timekeeping Report - ${dayData.value.date}</title>
                        <style>
                            * { margin: 0; padding: 0; box-sizing: border-box; }
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; }
                            h1 { font-size: 28px; font-weight: bold; margin-bottom: 10px; color: #1f2937; }
                            .date-info { color: #666; margin-bottom: 20px; font-size: 14px; }
                            .summary-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
                            .summary-card { border: 1px solid #ddd; padding: 15px; border-radius: 5px; text-align: center; background: #f9fafb; }
                            .summary-card-label { font-size: 12px; color: #999; margin-bottom: 10px; text-transform: uppercase; font-weight: bold; }
                            .summary-card-value { font-size: 32px; font-weight: bold; }
                            .table-section { margin-top: 30px; }
                            .table-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #1f2937; }
                            table { width: 100%; border-collapse: collapse; }
                            th { background-color: #f3f4f6; border-bottom: 2px solid #d1d5db; padding: 12px; text-align: left; font-size: 12px; font-weight: bold; color: #374151; }
                            td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
                            tr:nth-child(even) { background-color: #f9fafb; }
                            .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; text-align: right; }
                            @media print {
                                body { margin: 0; padding: 20px; }
                                .table-section { page-break-inside: avoid; }
                            }
                        </style>
                    </head>
                    <body>
                        <h1>${dateTitle.value}</h1>
                        <div class="date-info">Date: ${dayData.value.date} (${dayData.value.dayOfWeek})</div>
                        
                        <div class="summary-cards">
                            <div class="summary-card">
                                <div class="summary-card-label">Total Records</div>
                                <div class="summary-card-value" style="color: #0c8ce9;">${dayData.value.employees.length}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">Present</div>
                                <div class="summary-card-value" style="color: #10b981;">${presentCount.value}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">Late</div>
                                <div class="summary-card-value" style="color: #f59e0b;">${lateCount.value}</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-card-label">AWOL</div>
                                <div class="summary-card-value" style="color: #ef4444;">${awolCount.value}</div>
                            </div>
                        </div>
                        
                        <div class="table-section">
                            <div class="table-title">Timekeeping Records</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Work Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${filteredEmployees.value.map(emp => `
                                        <tr>
                                            <td>#${String(emp.id).padStart(4, '0')}</td>
                                            <td>${emp.name}</td>
                                            <td>${emp.timeIn || '—'}</td>
                                            <td>${emp.timeOut || '—'}</td>
                                            <td>${(emp.timeIn && emp.timeOut) ? calculateWorkHours(emp.timeIn, emp.timeOut) + 'h' : '—'}</td>
                                            <td>${emp.status.charAt(0).toUpperCase() + emp.status.slice(1)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="footer">Generated on ${new Date().toLocaleString()}</div>
                    </body>
                </html>
            `
            
            printWindow.document.write(htmlContent)
            printWindow.document.close()
            
            setTimeout(() => {
                printWindow.focus()
                printWindow.print()
            }, 250)
        } catch (error) {
            console.error('Error printing:', error)
            Swal.fire({
                icon: 'error',
                title: 'Print Failed',
                text: 'Failed to print records. Please try again.',
                confirmButtonColor: '#ef4444'
            })
        }
    }

    onMounted(() => {
        // Fetch data from API based on route param
        // const date = route.params.date
        // TODO: Replace mock data with API call
        // fetchTimekeepingDetail(date)
    })
</script>

<style scoped>
    @media print {
        .ps-56\.25 {
            padding-left: 0;
        }
    }
</style>
