<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-600">My Attendance</p>
                <p class="text-xs text-gray-500">Records for {{ userLabel }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="openQRScanner('in')"
                    class="px-5 py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Time In
                </button>
                <button
                    @click="openQRScanner('out')"
                    class="px-5 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m11-11a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Time Out
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Days</p>
                <p class="text-3xl font-bold text-[#333333]">{{ records.length }}</p>
                <p class="text-xs text-gray-500 mt-2">Logged Records</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Time In Only</p>
                <p class="text-3xl font-bold text-blue-600">{{ timeInOnlyCount }}</p>
                <p class="text-xs text-gray-500 mt-2">Needs Time Out</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Clocked Out</p>
                <p class="text-3xl font-bold text-green-600">{{ clockedOutCount }}</p>
                <p class="text-xs text-gray-500 mt-2">Complete</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Absent</p>
                <p class="text-3xl font-bold text-red-600">{{ absentCount }}</p>
                <p class="text-xs text-gray-500 mt-2">No Time In/Out</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search by Date or Day</label>
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search attendance..."
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

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input
                        v-model="selectedDate"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0c8ce9]"
                    >
                        <option value="">All Status</option>
                        <option value="time-in">Time In Only</option>
                        <option value="clocked-out">Clocked Out</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
            </div>

            <div v-if="hasActiveFilters" class="mt-3 pt-3 border-t border-gray-200">
                <button
                    @click="clearFilters"
                    class="text-sm text-[#0c8ce9] font-semibold hover:underline"
                >
                    Clear All Filters
                </button>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700">
                    Attendance Records
                    <span class="text-gray-500">{{ filteredRecords.length }} records</span>
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

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <button
                                    @click="toggleSort('date')"
                                    class="text-xs font-semibold text-gray-700 uppercase tracking-wide hover:text-[#0c8ce9] transition-colors flex items-center gap-1"
                                >
                                    Date
                                    <svg
                                        v-if="sortField === 'date'"
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
                                Day
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
                            v-for="record in paginatedRecords"
                            :key="record.date"
                            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ record.date }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ record.dayOfWeek }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                <span
                                    v-if="record.timeIn"
                                    class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"
                                >
                                    {{ record.timeIn }}
                                </span>
                                <span v-else class="text-gray-400 text-xs">—</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                <span
                                    v-if="record.timeOut"
                                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"
                                >
                                    {{ record.timeOut }}
                                </span>
                                <span v-else class="text-gray-400 text-xs">—</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    :class="getStatusBadgeClass(record.status)"
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                >
                                    {{ formatStatus(record.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    @click="editRecord(record)"
                                    class="text-[#0c8ce9] hover:text-blue-700 font-semibold text-sm transition-colors"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div
                    v-if="filteredRecords.length === 0"
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
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <p class="text-gray-600 font-semibold">No attendance records found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="filteredRecords.length > 0" class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-600">
                    Showing {{ (currentPage - 1) * pageSize + 1 }} to
                    {{ Math.min(currentPage * pageSize, filteredRecords.length) }} of
                    {{ filteredRecords.length }}
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
            :currentUser="currentUser"
            :todayRecord="todayRecord"
            :scanMode="scanMode"
            @close="closeQRScanner"
            @update-attendance="handleAttendanceUpdate"
        />
    </div>
</template>

<script setup>
    import { ref, computed, onMounted, watch } from 'vue'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import QRScannerModal from './QRScannerModal.vue'
    import { useAuthStore } from '@/stores/auth'

    const auth = useAuthStore()
    const records = ref([])
    const searchQuery = ref('')
    const selectedStatus = ref('')
    const selectedDate = ref('')
    const sortField = ref('date')
    const sortDirection = ref('desc')
    const currentPage = ref(1)
    const pageSize = ref(10)
    const showQRScanner = ref(false)
    const scanMode = ref('in')

    const userLabel = computed(() => {
        const user = auth.user
        if (!user) return 'Unknown User'
        const last = user.last_name || ''
        const first = user.first_name || ''
        const middle = user.middle_name ? `${user.middle_name.charAt(0)}.` : ''
        return `${last}, ${first} ${middle}`.trim()
    })

    const currentUser = computed(() => {
        const user = auth.user
        return {
            name: userLabel.value,
            id: user?.id || null,
            position: user?.role || 'Employee'
        }
    })

    const filteredRecords = computed(() => {
        let filtered = records.value.filter(record => {
            const search = searchQuery.value.toLowerCase()
            const matchSearch =
                record.date.toLowerCase().includes(search) ||
                record.dayOfWeek.toLowerCase().includes(search)

            const matchStatus = !selectedStatus.value || record.status === selectedStatus.value
            const matchDate = !selectedDate.value || record.date === selectedDate.value

            return matchSearch && matchStatus && matchDate
        })

        filtered.sort((a, b) => {
            const aVal = a[sortField.value]
            const bVal = b[sortField.value]

            if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1
            if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1
            return 0
        })

        return filtered
    })

    const timeInOnlyCount = computed(() => {
        return records.value.filter(record => record.status === 'time-in').length
    })

    const absentCount = computed(() => {
        return records.value.filter(record => record.status === 'absent').length
    })

    const clockedOutCount = computed(() => {
        return records.value.filter(record => record.status === 'clocked-out').length
    })

    const hasActiveFilters = computed(() => {
        return searchQuery.value || selectedStatus.value || selectedDate.value
    })

    const totalPages = computed(() => {
        return Math.ceil(filteredRecords.value.length / pageSize.value)
    })

    const paginatedRecords = computed(() => {
        const start = (currentPage.value - 1) * pageSize.value
        const end = start + pageSize.value
        return filteredRecords.value.slice(start, end)
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

    const openQRScanner = (mode) => {
        scanMode.value = mode
        showQRScanner.value = true
    }

    const closeQRScanner = () => {
        showQRScanner.value = false
    }

    const handleAttendanceUpdate = (data) => {
        updateAttendance(data.date, data.timeIn || null, data.timeOut || null)
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
        selectedStatus.value = ''
        selectedDate.value = ''
        currentPage.value = 1
    }

    const editRecord = (record) => {
        Swal.fire({
            title: `Edit Attendance - ${record.date}`,
            html: `
                <div style="text-align: left;">
                    <label style="display: block; margin: 12px 0 6px 0; font-weight: 600; font-size: 14px;">Time In</label>
                    <input id="timeIn" type="time" value="${record.timeIn || ''}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    
                    <label style="display: block; margin: 12px 0 6px 0; font-weight: 600; font-size: 14px;">Time Out</label>
                    <input id="timeOut" type="time" value="${record.timeOut || ''}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
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
                updateAttendance(record.date, result.value.timeIn || null, result.value.timeOut || null)
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

    const fetchRecords = async () => {
        try {
            const response = await axios.get('/api/attendance/records', {
                params: selectedDate.value ? { date: selectedDate.value } : {}
            })
            records.value = response.data?.data || []
            currentPage.value = 1
        } catch (error) {
            console.error('Failed to load attendance records', error)
        }
    }

    const updateAttendance = async (date, timeIn, timeOut) => {
        try {
            await axios.patch(`/api/attendance/records/${date}`, {
                time_in: timeIn,
                time_out: timeOut
            })
            await fetchRecords()
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Attendance updated',
                showConfirmButton: false,
                timer: 2000
            })
        } catch (error) {
            console.error('Failed to update attendance', error)
            Swal.fire({
                icon: 'error',
                title: 'Update failed',
                text: 'Please try again.',
                confirmButtonColor: '#ef4444'
            })
        }
    }

    const todayRecord = computed(() => {
        const today = new Date().toISOString().split('T')[0]
        return records.value.find(record => record.date === today) || null
    })

    onMounted(() => {
        fetchRecords()
    })

    watch([selectedDate], () => {
        fetchRecords()
    })
</script>
