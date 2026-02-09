<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-[#333333]">Scan QR Code - {{ scanLabel }}</h2>
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
                        <div class="bg-black rounded-lg w-80 h-60 mx-auto flex items-center justify-center relative overflow-hidden">
                            <div
                                v-show="scannerActive"
                                id="qr-reader"
                                class="w-full h-full rounded-lg overflow-hidden"
                            ></div>
                            <div v-if="!scannerActive" class="text-center text-white">
                                <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-sm opacity-75">Waiting to start scanner...</p>
                            </div>

                            <!-- QR Code Overlay -->
                            <div v-if="scannerActive" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div :style="qrBoxStyle"></div>
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
                        <button
                            @click="simulateScan"
                            :disabled="!scannerActive"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                        >
                            Simulate Scan
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

                    <div v-if="scannedEmployee" class="text-sm text-gray-600">
                        {{ resultHint }}
                    </div>

                    <div v-if="scannedEmployee" class="mt-4">
                        <button
                            v-if="!photoCaptureActive"
                            @click="startPhotoCapture"
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors"
                        >
                            Capture Photo Proof
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Required for {{ scanLabel.toLowerCase() }}.</p>

                        <div v-if="photoCaptureActive" class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <div class="bg-black rounded-lg overflow-hidden">
                                <video ref="photoVideo" class="w-full h-80 object-cover" autoplay muted playsinline></video>
                                <canvas ref="photoCanvas" class="hidden"></canvas>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <button
                                    @click="capturePhoto"
                                    class="flex-1 px-3 py-2 bg-[#0c8ce9] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"
                                >
                                    Take Photo
                                </button>
                                <button
                                    @click="cancelPhotoCapture"
                                    class="flex-1 px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>

                        <div v-if="capturedPhoto" class="mt-3">
                            <p class="text-xs text-gray-600 mb-2">Captured photo preview</p>
                            <img
                                :src="capturedPhoto"
                                alt="Captured photo"
                                class="w-full max-h-80 object-contain rounded-lg border border-gray-200"
                            />
                            <button
                                @click="retakePhoto"
                                class="mt-3 px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors"
                            >
                                Retake Photo
                            </button>
                        </div>
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
    import { ref, computed, onMounted, onUnmounted } from 'vue'
    import Swal from 'sweetalert2'
    import { Html5Qrcode } from 'html5-qrcode'

    const props = defineProps({
        isOpen: Boolean,
        currentUser: {
            type: Object,
            default: () => ({})
        },
        todayRecord: {
            type: Object,
            default: null
        },
        scanMode: {
            type: String,
            default: 'in'
        }
    })

    const emit = defineEmits(['close', 'update-attendance'])

    const qrScanner = ref(null)
    const scannerActive = ref(false)
    const scannedEmployee = ref(null)
    const scanHistory = ref([])
    const photoVideo = ref(null)
    const photoCanvas = ref(null)
    const photoStream = ref(null)
    const photoCaptureActive = ref(false)
    const capturedPhoto = ref(null)
    const pendingPhotoType = ref(null)
    const lastQrPayload = ref(null)
    const pendingAttendance = ref(null)

    const scanLabel = computed(() => (props.scanMode === 'out' ? 'Time Out' : 'Time In'))
    const qrBoxSize = 240
    const qrBoxStyle = computed(() => ({
        width: `${qrBoxSize}px`,
        height: `${qrBoxSize}px`,
        border: '4px solid #22c55e',
        borderRadius: '8px',
        opacity: 0.5
    }))
    const resultHint = computed(() => `Recorded ${scanLabel.value.toLowerCase()} for today.`)

    const getTodayDate = () => {
        return new Date().toLocaleDateString('en-CA')
    }

    const formatTime = (date) => {
        return date.toTimeString().slice(0, 8)
    }

    const startScanner = async () => {
        if (scannerActive.value) return
        scannerActive.value = true

        if (!qrScanner.value) {
            qrScanner.value = new Html5Qrcode('qr-reader')
        }

        try {
            await qrScanner.value.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: qrBoxSize, height: qrBoxSize } },
                (decodedText) => {
                    handleScanSuccess(decodedText)
                },
                () => {}
            )
        } catch (error) {
            scannerActive.value = false
            Swal.fire({
                icon: 'error',
                title: 'Camera error',
                text: 'Unable to start the camera scanner.',
                confirmButtonColor: '#ef4444'
            })
        }
    }

    const stopScanner = async () => {
        scannerActive.value = false
        if (qrScanner.value) {
            try {
                await qrScanner.value.stop()
                await qrScanner.value.clear()
            } catch (error) {
                const message = error?.name || error?.message || ''
                if (String(message).includes('AbortError')) {
                    return
                }
            }
        }
    }

    const simulateScan = () => {
        if (!scannerActive.value) return

        const employeeData = props.currentUser
        if (!employeeData || !employeeData.name) {
            Swal.fire({
                icon: 'info',
                title: 'No user',
                text: 'No user record available to scan.',
                timer: 1500,
                showConfirmButton: false
            })
            return
        }

        const payload = JSON.stringify({
            type: 'employee_attendance',
            employee_id: employeeData.id,
            date: getTodayDate()
        })

        handleScanSuccess(payload)
    }

    const handleScanSuccess = (decodedText) => {
        const employeeData = props.currentUser
        if (!employeeData || !employeeData.name) {
            return
        }

        let payload = null
        try {
            payload = JSON.parse(decodedText)
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid QR',
                text: 'QR payload is not valid JSON.',
                confirmButtonColor: '#ef4444'
            })
            return
        }

        if (payload?.type !== 'employee_attendance') {
            Swal.fire({
                icon: 'error',
                title: 'Invalid QR',
                text: 'QR code is not for attendance.',
                confirmButtonColor: '#ef4444'
            })
            return
        }

        if (payload.employee_id && employeeData.employeeId && String(payload.employee_id) !== String(employeeData.employeeId)) {
            Swal.fire({
                icon: 'error',
                title: 'Mismatch',
                text: 'This QR code does not belong to you.',
                confirmButtonColor: '#ef4444'
            })
            return
        }

        lastQrPayload.value = decodedText

        const record = props.todayRecord || {}
        scannedEmployee.value = {
            id: employeeData.id || 'me',
            name: employeeData.name,
            department: '-',
            position: employeeData.position,
            timeIn: record.timeIn || null,
            timeOut: record.timeOut || null
        }

        Swal.fire({
            icon: 'success',
            title: 'QR Scanned',
            text: `Scanned: ${employeeData.name}`,
            timer: 1500,
            showConfirmButton: false
        })

        if (props.scanMode === 'out') {
            recordTimeOut()
        } else {
            recordTimeIn()
        }
    }

    const recordTimeIn = () => {
        if (!scannedEmployee.value) return

        const now = new Date()
        const timeInString = formatTime(now)
        
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

        pendingAttendance.value = {
            employeeId: scannedEmployee.value.id,
            mode: 'in',
            date: getTodayDate(),
            timeIn: timeInString,
            timeOut: null,
            qrPayload: lastQrPayload.value
        }
        pendingPhotoType.value = 'in'

        Swal.fire({
            icon: 'success',
            title: 'Time In Recorded',
            text: 'Please capture a photo proof to save.',
            timer: 1500,
            showConfirmButton: false
        })

        // Continue scanning
        stopScanner()
    }

    const recordTimeOut = () => {
        if (!scannedEmployee.value) return

        const now = new Date()
        const timeOutString = formatTime(now)
        
        scannedEmployee.value.timeOut = timeOutString

        // Update history
        const historyIndex = scanHistory.value.findIndex(h => h.id === scannedEmployee.value.id)
        if (historyIndex >= 0) {
            scanHistory.value[historyIndex].timeOut = timeOutString
        } else {
            scanHistory.value.unshift({
                id: scannedEmployee.value.id,
                name: scannedEmployee.value.name,
                timeIn: scannedEmployee.value.timeIn,
                timeOut: timeOutString
            })
        }

        pendingAttendance.value = {
            employeeId: scannedEmployee.value.id,
            mode: 'out',
            date: getTodayDate(),
            timeIn: scannedEmployee.value.timeIn,
            timeOut: timeOutString,
            qrPayload: lastQrPayload.value
        }
        pendingPhotoType.value = 'out'

        Swal.fire({
            icon: 'success',
            title: 'Time Out Recorded',
            text: 'Please capture a photo proof to save.',
            timer: 1500,
            showConfirmButton: false
        })

        // Continue scanning
        stopScanner()
    }

    const clearScanned = () => {
        scannedEmployee.value = null
    }

    const discardPendingAttendance = () => {
        const pending = pendingAttendance.value
        if (!pending) return

        const historyIndex = scanHistory.value.findIndex(h => h.id === pending.employeeId)
        if (pending.mode === 'in') {
            if (historyIndex >= 0) {
                scanHistory.value.splice(historyIndex, 1)
            }
            if (scannedEmployee.value && scannedEmployee.value.id === pending.employeeId) {
                scannedEmployee.value.timeIn = null
            }
        }

        if (pending.mode === 'out') {
            if (historyIndex >= 0) {
                scanHistory.value[historyIndex].timeOut = null
            }
            if (scannedEmployee.value && scannedEmployee.value.id === pending.employeeId) {
                scannedEmployee.value.timeOut = null
            }
        }

        pendingAttendance.value = null
        pendingPhotoType.value = null
        lastQrPayload.value = null
        capturedPhoto.value = null
    }

    const closeModal = async () => {
        if (pendingAttendance.value) {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Discard pending attendance?',
                text: 'This will cancel the scanned attendance without a photo proof.',
                showCancelButton: true,
                confirmButtonText: 'Yes, discard',
                cancelButtonText: 'No, keep',
                confirmButtonColor: '#ef4444'
            })

            if (!result.isConfirmed) {
                return
            }

            discardPendingAttendance()
        }
        await stopScanner()
        if (qrScanner.value) {
            try {
                await qrScanner.value.clear()
            } catch (error) {
            }
            qrScanner.value = null
        }
        clearScanned()
        emit('close')
    }

    const submitAndClose = async () => {
        if (pendingAttendance.value) {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Discard pending attendance?',
                text: 'This will cancel the scanned attendance without a photo proof.',
                showCancelButton: true,
                confirmButtonText: 'Yes, discard',
                cancelButtonText: 'No, keep',
                confirmButtonColor: '#ef4444'
            })

            if (!result.isConfirmed) {
                return
            }

            discardPendingAttendance()
        }
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

    const startPhotoCapture = async () => {
        if (pendingAttendance.value) {
            pendingPhotoType.value = pendingAttendance.value.timeOut ? 'out' : 'in'
        } else {
            pendingPhotoType.value = props.scanMode
        }

        capturedPhoto.value = null

        try {
            await stopScanner()
            let stream = null
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false
                })
            } catch (error) {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'user' } },
                    audio: false
                })
            }
            photoStream.value = stream
            photoCaptureActive.value = true
            if (photoVideo.value) {
                photoVideo.value.srcObject = stream
                photoVideo.value.onloadedmetadata = () => {
                    const playResult = photoVideo.value.play()
                    if (playResult && typeof playResult.catch === 'function') {
                        playResult.catch(() => {})
                    }
                }
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Camera error',
                text: 'Unable to access the camera for photo proof.',
                confirmButtonColor: '#ef4444'
            })
        }
    }

    const stopPhotoCapture = () => {
        if (photoStream.value) {
            photoStream.value.getTracks().forEach(track => track.stop())
            photoStream.value = null
        }
        photoCaptureActive.value = false
    }

    const cancelPhotoCapture = () => {
        stopPhotoCapture()
    }

    const retakePhoto = () => {
        capturedPhoto.value = null
        startPhotoCapture()
    }

    const capturePhoto = () => {
        if (!photoVideo.value || !photoCanvas.value || !scannedEmployee.value) {
            return
        }

        const video = photoVideo.value
        const canvas = photoCanvas.value
        const width = video.videoWidth || 640
        const height = video.videoHeight || 480
        canvas.width = width
        canvas.height = height

        const context = canvas.getContext('2d')
        if (!context) {
            return
        }

        context.drawImage(video, 0, 0, width, height)
        const base64 = canvas.toDataURL('image/jpeg', 0.9)
        capturedPhoto.value = base64

        const payload = pendingAttendance.value || {
            date: getTodayDate(),
            timeIn: scannedEmployee.value.timeIn,
            timeOut: scannedEmployee.value.timeOut,
            qrPayload: lastQrPayload.value
        }

        emit('update-attendance', {
            date: payload.date,
            timeIn: payload.timeIn,
            timeOut: payload.timeOut,
            timeInPhoto: pendingPhotoType.value === 'in' ? base64 : null,
            timeOutPhoto: pendingPhotoType.value === 'out' ? base64 : null,
            qrPayload: payload.qrPayload
        })

        pendingAttendance.value = null
        stopPhotoCapture()

        Swal.fire({
            icon: 'success',
            title: 'Photo saved',
            text: 'Photo proof captured successfully.',
            timer: 1500,
            showConfirmButton: false
        })
    }

    onUnmounted(() => {
        stopScanner()
        stopPhotoCapture()
        qrScanner.value = null
    })

</script>

<style scoped>
#qr-reader {
    width: 100% !important;
    height: 100% !important;
    border-radius: 0.5rem;
    overflow: hidden;
    background: #000;
    object-fit: cover;
}

#qr-reader video,
#qr-reader canvas {
    width: 100% !important;
    height: 100% !important;
    border-radius: 0.5rem;
    object-fit: cover;
    overflow: hidden;
}

#qr-reader__scan_region {
    width: 100% !important;
    height: 100% !important;
    border-radius: 0.5rem;
    overflow: hidden;
}

#qr-reader__dashboard {
    display: none !important;
}

#qr-reader .qr-code-outline,
#qr-reader .qr-shaded-region,
#qr-reader__scan_region svg {
    display: none !important;
}
</style>
