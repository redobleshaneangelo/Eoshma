<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-[#333333]">Scan QR Code</h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Scanner Section -->
                <div class="mb-6">
                    <div class="bg-gray-100 rounded-lg p-4 mb-4">
                        <div class="bg-black rounded-lg aspect-square flex items-center justify-center relative overflow-hidden">
                            <!-- Camera Feed or Placeholder -->
                            <video
                                v-if="scannerActive"
                                ref="videoElement"
                                class="w-full h-full object-cover"
                            ></video>
                            <div v-else class="text-center text-white">
                                <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-sm opacity-75">Click "Start Scanner" to begin scanning</p>
                            </div>

                            <!-- QR Code Overlay -->
                            <div v-if="scannerActive" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-64 h-64 border-4 border-green-400 rounded-lg opacity-50"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Scanner Controls -->
                    <div class="flex gap-3 mb-4">
                        <button
                            @click="startScanner"
                            :disabled="scannerActive"
                            class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            Start Scanner
                        </button>
                        <button
                            @click="stopScanner"
                            :disabled="!scannerActive"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z" clip-rule="evenodd" />
                            </svg>
                            Stop Scanner
                        </button>
                    </div>

                    <!-- Scanned QR Info -->
                    <div v-if="scannedEmployee" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Scanned Employee</p>
                                <p class="text-lg font-bold text-[#0c8ce9] mt-1">{{ scannedEmployee.name }}</p>
                                <p class="text-sm text-gray-600 mt-1">ID: {{ scannedEmployee.id }}</p>
                                <p class="text-sm text-gray-600">{{ scannedEmployee.department }} • {{ scannedEmployee.position }}</p>
                            </div>
                            <button
                                @click="clearScanned"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Current Status -->
                        <div class="mt-3 flex gap-2">
                            <div
                                v-if="scannedEmployee.timeIn"
                                class="flex-1 bg-green-100 border border-green-300 rounded px-3 py-2"
                            >
                                <p class="text-xs text-gray-600">Time In</p>
                                <p class="text-sm font-semibold text-green-700">{{ scannedEmployee.timeIn }}</p>
                            </div>
                            <div
                                v-if="scannedEmployee.timeOut"
                                class="flex-1 bg-purple-100 border border-purple-300 rounded px-3 py-2"
                            >
                                <p class="text-xs text-gray-600">Time Out</p>
                                <p class="text-sm font-semibold text-purple-700">{{ scannedEmployee.timeOut }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Time In / Time Out Actions -->
                    <div v-if="scannedEmployee" class="flex gap-3">
                        <button
                            @click="recordTimeIn"
                            :disabled="scannedEmployee.timeIn && !scannedEmployee.timeOut"
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Time In
                        </button>
                        <button
                            @click="recordTimeOut"
                            :disabled="!scannedEmployee.timeIn || scannedEmployee.timeOut"
                            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m11-11a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Time Out
                        </button>
                    </div>
                </div>

                <!-- Scanned History -->
                <div v-if="scanHistory.length > 0" class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Scanned This Session</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <div
                            v-for="record in scanHistory"
                            :key="record.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200"
                        >
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ record.name }}</p>
                                <p class="text-xs text-gray-600">{{ record.id }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span v-if="record.timeIn" class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                    ✓ IN: {{ record.timeIn }}
                                </span>
                                <span v-if="record.timeOut" class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                    ✓ OUT: {{ record.timeOut }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button
                    @click="closeModal"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors"
                >
                    Close
                </button>
                <button
                    @click="submitAndClose"
                    class="px-6 py-2.5 bg-[#0c8ce9] text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors"
                >
                    Done
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted } from 'vue'
    import Swal from 'sweetalert2'

    const props = defineProps({
        isOpen: Boolean,
        employees: {
            type: Array,
            default: () => []
        }
    })

    const emit = defineEmits(['close', 'update-attendance'])

    const videoElement = ref(null)
    const scannerActive = ref(false)
    const scannedEmployee = ref(null)
    const scanHistory = ref([])

    const getRandomEmployee = () => {
        const list = props.employees || []
        if (!list.length) return null
        return list[Math.floor(Math.random() * list.length)]
    }

    const startScanner = () => {
        scannerActive.value = true
        // TODO: Integrate with actual QR scanning library (e.g., jsQR, html5-qrcode)
        // For now, simulate scanning by detecting key press
        setTimeout(() => {
            simulateScan()
        }, 1000)
    }

    const stopScanner = () => {
        scannerActive.value = false
        if (videoElement.value && videoElement.value.srcObject) {
            videoElement.value.srcObject.getTracks().forEach(track => track.stop())
        }
    }

    const simulateScan = () => {
        if (!scannerActive.value) return

        // Simulate random QR code scan
        const employeeData = getRandomEmployee()
        if (!employeeData) {
            Swal.fire({
                icon: 'info',
                title: 'No employees',
                text: 'No employee records available to scan.',
                timer: 1500,
                showConfirmButton: false
            })
            return
        }

        // Find employee in list and merge with existing data
        scannedEmployee.value = {
            id: employeeData.id,
            name: employeeData.name,
            department: '-',
            position: employeeData.position,
            timeIn: employeeData.timeIn || null,
            timeOut: employeeData.timeOut || null
        }

        Swal.fire({
            icon: 'success',
            title: 'QR Scanned',
            text: `Scanned: ${employeeData.name}`,
            timer: 1500,
            showConfirmButton: false
        })
    }

    const recordTimeIn = () => {
        if (!scannedEmployee.value) return

        const now = new Date()
        const timeInString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false })
        
        scannedEmployee.value.timeIn = timeInString

        // Add to history
        const historyRecord = {
            id: scannedEmployee.value.id,
            name: scannedEmployee.value.name,
            timeIn: timeInString,
            timeOut: null
        }

        const existingIndex = scanHistory.value.findIndex(h => h.id === scannedEmployee.value.id)
        if (existingIndex >= 0) {
            scanHistory.value[existingIndex].timeIn = timeInString
        } else {
            scanHistory.value.unshift(historyRecord)
        }

        // Emit update to parent
        emit('update-attendance', {
            employeeId: scannedEmployee.value.id,
            timeIn: timeInString,
            timeOut: null
        })

        Swal.fire({
            icon: 'success',
            title: 'Time In Recorded',
            text: `${scannedEmployee.value.name} - ${timeInString}`,
            timer: 1500,
            showConfirmButton: false
        })

        // Continue scanning
        if (scannerActive.value) {
            setTimeout(() => {
                simulateScan()
            }, 2000)
        }
    }

    const recordTimeOut = () => {
        if (!scannedEmployee.value) return

        const now = new Date()
        const timeOutString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false })
        
        scannedEmployee.value.timeOut = timeOutString

        // Update history
        const historyIndex = scanHistory.value.findIndex(h => h.id === scannedEmployee.value.id)
        if (historyIndex >= 0) {
            scanHistory.value[historyIndex].timeOut = timeOutString
        }

        // Emit update to parent
        emit('update-attendance', {
            employeeId: scannedEmployee.value.id,
            timeIn: scannedEmployee.value.timeIn,
            timeOut: timeOutString
        })

        Swal.fire({
            icon: 'success',
            title: 'Time Out Recorded',
            text: `${scannedEmployee.value.name} - ${timeOutString}`,
            timer: 1500,
            showConfirmButton: false
        })

        // Continue scanning
        if (scannerActive.value) {
            setTimeout(() => {
                simulateScan()
            }, 2000)
        }
    }

    const clearScanned = () => {
        scannedEmployee.value = null
    }

    const closeModal = () => {
        stopScanner()
        clearScanned()
        emit('close')
    }

    const submitAndClose = () => {
        if (scanHistory.value.length === 0) {
            Swal.fire('No Records', 'No attendance records were scanned', 'info')
            return
        }

        Swal.fire({
            icon: 'success',
            title: 'Attendance Submitted',
            text: `${scanHistory.value.length} record(s) submitted successfully`,
            confirmButtonColor: '#0c8ce9'
        }).then(() => {
            closeModal()
        })
    }

    onUnmounted(() => {
        stopScanner()
    })
</script>
