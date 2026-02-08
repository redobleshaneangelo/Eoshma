<template>
    <div class="space-y-6">
        <!-- Page Header with Scan Button -->
        <div class="flex items-center justify-between">
            <button
                @click="openQRScanner"
                class="ms-auto px-6 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Scan QR Code
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Employees</p>
                <p class="text-3xl font-bold text-[#333333]">{{ employees.length }}</p>
                <p class="text-xs text-gray-500 mt-2">Scanned Today</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Present</p>
                <p class="text-3xl font-bold text-green-600">{{ presentCount }}</p>
                <p class="text-xs text-gray-500 mt-2">Time In Recorded</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Not Yet Scanned</p>
                <p class="text-3xl font-bold text-orange-600">{{ notScannedCount }}</p>
                <p class="text-xs text-gray-500 mt-2">Pending</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Clocked Out</p>
                <p class="text-3xl font-bold text-blue-600">{{ clockedOutCount }}</p>
                <p class="text-xs text-gray-500 mt-2">Time Out Recorded</p>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search by Name/ID -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search by Name or ID</label>
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search employees..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                        />
                        <svg
                            class="absolute right-3 top-3 w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                    <select
                        v-model="selectedDepartment"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                    >
                        <option value="">All Departments</option>
                        <option value="IT">IT</option>
                        <option value="HR">HR</option>
                        <option value="Finance">Finance</option>
                        <option value="Sales">Sales</option>
                        <option value="Operations">Operations</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                    >
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="time-in">Time In Only</option>
                        <option value="clocked-out">Clocked Out</option>
                        <option value="absent">Not Scanned</option>
                    </select>
                </div>
            </div>

            <!-- Clear Filters -->
            <div v-if="hasActiveFilters" class="mt-3 pt-3 border-t border-gray-200">
                <button
                    @click="clearFilters"
                    class="text-sm text-[#0c8ce9] font-semibold hover:underline"
                >
                    Clear All Filters
                </button>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <!-- Table Header with Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700">
                    Employees
                    <span class="text-gray-500">{{ filteredEmployees.length }} records</span>
                </h3>
                <div class="flex items-center gap-3">
                    <select
                        v-model="pageSize"
                        class="px-3 py-1.5 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                    >
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>

            <!-- Scrollable Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <button
                                    @click="toggleSort('id')"
                                    class="text-xs font-semibold text-gray-700 uppercase tracking-wide hover:text-[#0c8ce9] transition-colors flex items-center gap-1"
                                >
                                    Employee ID
                                    <svg
                                        v-if="sortField === 'id'"
                                        class="w-3 h-3"
                                        :class="sortDirection === 'asc' ? 'rotate-180' : ''"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button
                                    @click="toggleSort('name')"
                                    class="text-xs font-semibold text-gray-700 uppercase tracking-wide hover:text-[#0c8ce9] transition-colors flex items-center gap-1"
                                >
                                    Full Name
                                    <svg
                                        v-if="sortField === 'name'"
                                        class="w-3 h-3"
                                        :class="sortDirection === 'asc' ? 'rotate-180' : ''"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button
                                    @click="toggleSort('department')"
                                    class="text-xs font-semibold text-gray-700 uppercase tracking-wide hover:text-[#0c8ce9] transition-colors flex items-center gap-1"
                                >
                                    Department
                                    <svg
                                        v-if="sortField === 'department'"
                                        class="w-3 h-3"
                                        :class="sortDirection === 'asc' ? 'rotate-180' : ''"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Position
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Time In
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Time Out
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="employee in paginatedEmployees"
                            :key="employee.id"
                            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ employee.id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ employee.name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ employee.department }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ employee.position }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                <span
                                    v-if="employee.timeIn"
                                    class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"
                                >
                                    {{ employee.timeIn }}
                                </span>
                                <span v-else class="text-gray-400 text-xs">—</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                <span
                                    v-if="employee.timeOut"
                                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"
                                >
                                    {{ employee.timeOut }}
                                </span>
                                <span v-else class="text-gray-400 text-xs">—</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    :class="getStatusBadgeClass(employee.status)"
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                >
                                    {{ formatStatus(employee.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    @click="editEmployee(employee)"
                                    class="text-[#0c8ce9] hover:text-blue-700 font-semibold text-sm transition-colors"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div
                    v-if="filteredEmployees.length === 0"
                    class="flex flex-col items-center justify-center py-16 px-6"
                >
                    <svg
                        class="w-16 h-16 text-gray-300 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p class="text-gray-600 font-semibold">No employees found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="filteredEmployees.length > 0" class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-600">
                    Showing {{ (currentPage - 1) * pageSize + 1 }} to
                    {{ Math.min(currentPage * pageSize, filteredEmployees.length) }} of
                    {{ filteredEmployees.length }}
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="previousPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1.5 border border-gray-300 rounded text-sm font-semibold text-gray-700 hover:bg-gray-100 disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400 transition-colors"
                    >
                        Previous
                    </button>
                    <div class="flex gap-1">
                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            @click="goToPage(page)"
                            :class="currentPage === page ? 'bg-[#0c8ce9] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-100'"
                            class="px-3 py-1.5 rounded text-sm font-semibold transition-colors"
                        >
                            {{ page }}
                        </button>
                    </div>
                    <button
                        @click="nextPage"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1.5 border border-gray-300 rounded text-sm font-semibold text-gray-700 hover:bg-gray-100 disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400 transition-colors"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- QR Scanner Modal -->
        <QRScannerModal
            :isOpen="showQRScanner"
            :employees="filteredEmployees"
            @close="closeQRScanner"
            @update-attendance="handleAttendanceUpdate"
        />
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue'
    import Swal from 'sweetalert2'
    import QRScannerModal from './QRScannerModal.vue'

    // Mock employee data
    const mockEmployees = [
        { id: 'EMP-00001', name: 'John Smith', department: 'IT', position: 'Software Engineer', timeIn: '08:00', timeOut: '17:30', status: 'present' },
        { id: 'EMP-00002', name: 'Jane Doe', department: 'HR', position: 'HR Manager', timeIn: '09:00', timeOut: null, status: 'time-in' },
        { id: 'EMP-00003', name: 'Bob Johnson', department: 'Finance', position: 'Accountant', timeIn: null, timeOut: null, status: 'absent' },
        { id: 'EMP-00004', name: 'Alice Brown', department: 'Sales', position: 'Sales Executive', timeIn: '08:30', timeOut: '18:00', status: 'present' },
        { id: 'EMP-00005', name: 'Charlie Wilson', department: 'IT', position: 'System Admin', timeIn: '08:15', timeOut: '17:45', status: 'present' },
        { id: 'EMP-00006', name: 'Diana Garcia', department: 'Operations', position: 'Operations Lead', timeIn: '08:00', timeOut: null, status: 'time-in' },
        { id: 'EMP-00007', name: 'Edward Martinez', department: 'Finance', position: 'Finance Manager', timeIn: null, timeOut: null, status: 'absent' },
        { id: 'EMP-00008', name: 'Fiona Anderson', department: 'Sales', position: 'Sales Manager', timeIn: '08:45', timeOut: '17:00', status: 'present' },
        { id: 'EMP-00009', name: 'George Thomas', department: 'IT', position: 'Web Developer', timeIn: '09:00', timeOut: null, status: 'time-in' },
        { id: 'EMP-00010', name: 'Helen White', department: 'HR', position: 'Recruiter', timeIn: '08:30', timeOut: '17:30', status: 'present' },
        { id: 'EMP-00011', name: 'Isaac Lee', department: 'Operations', position: 'Logistics Manager', timeIn: null, timeOut: null, status: 'absent' },
        { id: 'EMP-00012', name: 'Julia Roberts', department: 'Finance', position: 'Auditor', timeIn: '08:00', timeOut: '17:15', status: 'present' }
    ]

    const employees = ref(mockEmployees)
    const searchQuery = ref('')
    const selectedDepartment = ref('')
    const selectedStatus = ref('')
    const sortField = ref('name')
    const sortDirection = ref('asc')
    const currentPage = ref(1)
    const pageSize = ref(10)
    const showQRScanner = ref(false)

    const filteredEmployees = computed(() => {
        let filtered = employees.value.filter(emp => {
            const matchSearch =
                emp.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                emp.id.toLowerCase().includes(searchQuery.value.toLowerCase())

            const matchDept = !selectedDepartment.value || emp.department === selectedDepartment.value
            const matchStatus = !selectedStatus.value || emp.status === selectedStatus.value

            return matchSearch && matchDept && matchStatus
        })

        // Sort
        filtered.sort((a, b) => {
            let aVal = a[sortField.value]
            let bVal = b[sortField.value]

            if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1
            if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1
            return 0
        })

        return filtered
    })

    const presentCount = computed(() => {
        return employees.value.filter(emp => emp.timeIn !== null).length
    })

    const notScannedCount = computed(() => {
        return employees.value.filter(emp => emp.timeIn === null && emp.status === 'absent').length
    })

    const clockedOutCount = computed(() => {
        return employees.value.filter(emp => emp.timeOut !== null).length
    })

    const hasActiveFilters = computed(() => {
        return searchQuery.value || selectedDepartment.value || selectedStatus.value
    })

    const totalPages = computed(() => {
        return Math.ceil(filteredEmployees.value.length / pageSize.value)
    })

    const paginatedEmployees = computed(() => {
        const start = (currentPage.value - 1) * pageSize.value
        const end = start + pageSize.value
        return filteredEmployees.value.slice(start, end)
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

    const toggleSort = (field) => {
        if (sortField.value === field) {
            sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
        } else {
            sortField.value = field
            sortDirection.value = 'asc'
        }
    }

    const openQRScanner = () => {
        showQRScanner.value = true
    }

    const closeQRScanner = () => {
        showQRScanner.value = false
    }

    const handleAttendanceUpdate = (data) => {
        const employeeIndex = employees.value.findIndex(emp => emp.id === data.employeeId)
        if (employeeIndex >= 0) {
            employees.value[employeeIndex].timeIn = data.timeIn
            employees.value[employeeIndex].timeOut = data.timeOut

            // Update status
            if (data.timeOut) {
                employees.value[employeeIndex].status = 'present'
            } else if (data.timeIn) {
                employees.value[employeeIndex].status = 'time-in'
            }
        }
    }

    const getStatusBadgeClass = (status) => {
        const classes = {
            present: 'bg-green-100 text-green-700',
            'time-in': 'bg-blue-100 text-blue-700',
            'clocked-out': 'bg-purple-100 text-purple-700',
            absent: 'bg-red-100 text-red-700'
        }
        return classes[status] || 'bg-gray-100 text-gray-700'
    }

    const formatStatus = (status) => {
        const formats = {
            present: 'Present',
            'time-in': 'Time In Only',
            'clocked-out': 'Clocked Out',
            absent: 'Not Scanned'
        }
        return formats[status] || status
    }

    const clearFilters = () => {
        searchQuery.value = ''
        selectedDepartment.value = ''
        selectedStatus.value = ''
        currentPage.value = 1
    }

    const editEmployee = (employee) => {
        Swal.fire({
            title: `Edit Attendance - ${employee.name}`,
            html: `
                <div style="text-align: left;">
                    <label style="display: block; margin: 12px 0 6px 0; font-weight: 600; font-size: 14px;">Time In</label>
                    <input id="timeIn" type="time" value="${employee.timeIn || ''}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    
                    <label style="display: block; margin: 12px 0 6px 0; font-weight: 600; font-size: 14px;">Time Out</label>
                    <input id="timeOut" type="time" value="${employee.timeOut || ''}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            `,
            confirmButtonText: 'Save',
            confirmButtonColor: '#0c8ce9',
            showCancelButton: true,
            preConfirm: () => {
                const timeIn = document.getElementById('timeIn').value
                const timeOut = document.getElementById('timeOut').value
                return { timeIn, timeOut }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const employeeIndex = employees.value.findIndex(emp => emp.id === employee.id)
                if (employeeIndex >= 0) {
                    employees.value[employeeIndex].timeIn = result.value.timeIn || employee.timeIn
                    employees.value[employeeIndex].timeOut = result.value.timeOut || employee.timeOut

                    // Update status
                    if (result.value.timeOut) {
                        employees.value[employeeIndex].status = 'present'
                    } else if (result.value.timeIn) {
                        employees.value[employeeIndex].status = 'time-in'
                    }
                }

                Swal.fire('Updated', 'Attendance record updated successfully', 'success')
            }
        })
    }

    const previousPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--
        }
    }

    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++
        }
    }

    const goToPage = (page) => {
        currentPage.value = page
    }
</script>