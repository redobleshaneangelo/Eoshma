<template>
    <auth-layout />
    <div class="ps-60 bg-[#F3F4F6] min-h-screen">
        <div class="px-10 py-10 space-y-6">
            <div>
                <router-link
                    to="/payroll"
                    class="inline-flex items-center gap-2 text-[#0c8ce9] font-semibold hover:underline mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Payroll
                </router-link>

                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ run.name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ run.frequency }} • {{ run.group }} group</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" :class="getStatusClasses(run.status)">
                        {{ run.status }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Payroll Period</p>
                        <p class="text-sm font-semibold text-gray-900 mt-2">{{ formatDate(run.startDate) }} - {{ formatDate(run.endDate) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Pay Date</p>
                        <p class="text-sm font-semibold text-gray-900 mt-2">{{ formatDate(run.payDate) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Description</p>
                        <p class="text-sm text-gray-700 mt-2">{{ run.description }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-wrap">
                        <button
                            v-for="tab in availableTabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            :class="[
                                'px-6 py-4 text-sm font-semibold transition-colors border-b-2',
                                activeTab === tab.key
                                    ? 'text-[#0c8ce9] border-[#0c8ce9] bg-white'
                                    : 'text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            {{ tab.label }}
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div v-if="activeTab === 'attendance'" class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Attendance Summary</h2>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Days Logged</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Total Hours</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="employee in attendanceSummary" :key="employee.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <p class="font-semibold">{{ employee.name }}</p>
                                            <p class="text-xs text-gray-500">{{ employee.role }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ employee.daysLogged }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ employee.totalHours }}</td>
                                        <td class="px-4 py-3">
                                            <button
                                                @click="openEmployeeAttendance(employee)"
                                                class="px-3 py-1 text-xs font-semibold text-white bg-[#0c8ce9] rounded hover:bg-blue-700"
                                            >
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="attendanceSummary.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No attendance summary available.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeTab === 'allowances'" class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Allowances</h2>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Employee</label>
                                <select v-model="newAllowance.employeeId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">Select employee</option>
                                    <option v-for="employee in attendanceSummary" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Allowance Type</label>
                                <input v-model="newAllowance.type" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Meal, transport" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Amount</label>
                                <input v-model.number="newAllowance.amount" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Notes</label>
                                <input v-model="newAllowance.notes" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Optional" />
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="addAllowance"
                                    class="w-full px-4 py-2 text-sm font-semibold text-white bg-[#0c8ce9] rounded-lg hover:bg-blue-700"
                                >
                                    Assign Allowance
                                </button>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in allowanceAssignments" :key="item.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ item.employeeName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ item.type }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(item.amount) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ item.notes }}</td>
                                    </tr>
                                    <tr v-if="allowanceAssignments.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No allowances assigned.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeTab === 'deductions'" class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Deductions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Employee</label>
                                <select v-model="newDeduction.employeeId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">Select employee</option>
                                    <option v-for="employee in attendanceSummary" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Deduction Type</label>
                                <input v-model="newDeduction.type" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="SSS, Loan" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Amount</label>
                                <input v-model.number="newDeduction.amount" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Notes</label>
                                <input v-model="newDeduction.notes" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Optional" />
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="addDeduction"
                                    class="w-full px-4 py-2 text-sm font-semibold text-white bg-[#0c8ce9] rounded-lg hover:bg-blue-700"
                                >
                                    Assign Deduction
                                </button>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in deductionAssignments" :key="item.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ item.employeeName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ item.type }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(item.amount) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ item.notes }}</td>
                                    </tr>
                                    <tr v-if="deductionAssignments.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No deductions assigned.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeTab === 'statutory'" class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Statutory Compliance</h2>
                        <p class="text-sm text-gray-600">Review statutory computations and compliance requirements for this payroll period.</p>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Contribution</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Coverage Period</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employee Share</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employer Share</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Total Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in statutoryCompliance" :key="item.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ item.name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ item.coverage }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(item.employeeShare) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(item.employerShare) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(item.total) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(item.dueDate) }}</td>
                                    </tr>
                                    <tr v-if="statutoryCompliance.length === 0">
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No statutory compliance data yet.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeTab === 'computed'" class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Computed Payroll</h2>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Basic Pay</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Allowance</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Deductions</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Net Pay</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="row in computedPayrollRows" :key="row.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <p class="font-semibold">{{ row.employee }}</p>
                                            <p class="text-xs text-gray-500">{{ row.role }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(row.basicPay) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(row.allowance) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ formatNumber(row.deductions) }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">₱{{ formatNumber(row.netPay) }}</td>
                                    </tr>
                                    <tr v-if="computedPayrollRows.length === 0">
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No computed payroll data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end" v-if="showPrimaryAction">
                <button
                    @click="handlePrimaryAction"
                    class="px-4 py-2 text-sm font-semibold text-white bg-[#0c8ce9] rounded-lg hover:bg-blue-700 transition-colors"
                >
                    {{ primaryActionLabel }}
                </button>
            </div>
        </div>
    </div>

    <!-- Employee Attendance Modal -->
    <div v-if="showEmployeeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ selectedEmployee.name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ selectedEmployee.role }}</p>
                    </div>
                    <button @click="closeEmployeeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="border-b border-gray-200 bg-gray-50">
                <div class="flex flex-wrap">
                    <button
                        v-for="tab in employeeTabs"
                        :key="tab.key"
                        @click="employeeModalTab = tab.key"
                        :class="[
                            'px-6 py-4 text-sm font-semibold transition-colors border-b-2',
                            employeeModalTab === tab.key
                                ? 'text-[#0c8ce9] border-[#0c8ce9] bg-white'
                                : 'text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-100'
                        ]"
                    >
                        {{ tab.label }}
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div v-if="employeeModalTab === 'attendance'" class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Attendance Records</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Time In</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Time Out</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Payroll Start</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Payroll End</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="record in selectedEmployee.records" :key="record.date" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(record.date) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ record.timeIn || '--' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ record.timeOut || '--' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(run.startDate) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(run.endDate) }}</td>
                                    <td class="px-4 py-3">
                                        <button
                                            @click="openAttendanceRecord(record)"
                                            class="px-3 py-1 text-xs font-semibold text-white bg-[#0c8ce9] rounded hover:bg-blue-700"
                                        >
                                            View
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="selectedEmployee.records.length === 0">
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No attendance records.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="employeeModalTab === 'incidents'" class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Incident Reports</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div v-for="incident in selectedEmployee.incidents" :key="incident.id" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-900">{{ incident.name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ formatDate(incident.date) }}</p>
                        </div>
                        <div v-if="selectedEmployee.incidents.length === 0" class="text-sm text-gray-500">No incident reports.</div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200">
                <button
                    @click="closeEmployeeModal"
                    class="w-full px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Record Modal -->
    <div v-if="showAttendanceRecordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Attendance Record</h3>
                    <button @click="closeAttendanceRecord" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Time In</p>
                        <p class="text-sm text-gray-900 mt-1">{{ selectedAttendanceRecord.timeIn || '--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Time Out</p>
                        <p class="text-sm text-gray-900 mt-1">{{ selectedAttendanceRecord.timeOut || '--' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Time In Photo</p>
                        <div class="w-full h-36 border border-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">
                            {{ selectedAttendanceRecord.timeInPhoto || 'No photo' }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Time Out Photo</p>
                        <div class="w-full h-36 border border-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">
                            {{ selectedAttendanceRecord.timeOutPhoto || 'No photo' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200">
                <button
                    @click="closeAttendanceRecord"
                    class="w-full px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AuthLayout from '@/views/Layouts/AuthLayout.vue'
import Swal from 'sweetalert2'

const auth = useAuthStore()
const route = useRoute()
const router = useRouter()
const runId = Number(route.params.id)

onMounted(() => {
    auth.pageTitle = 'Payroll Run'
})

const tabs = [
    { key: 'attendance', label: 'Attendance Summary' },
    { key: 'allowances', label: 'Allowances' },
    { key: 'deductions', label: 'Deductions' },
    { key: 'statutory', label: 'Statutory Compliance' },
    { key: 'computed', label: 'Computed Payroll' }
]

const activeTab = ref('attendance')

const attendanceSummary = ref([
    {
        id: 1,
        name: 'John Davis',
        role: 'Project Manager',
        daysLogged: 10,
        totalHours: 80,
        records: [
            { date: '2026-01-03', timeIn: '08:10 AM', timeOut: '05:12 PM', timeInPhoto: 'In Photo', timeOutPhoto: 'Out Photo' },
            { date: '2026-01-04', timeIn: '08:08 AM', timeOut: '05:05 PM', timeInPhoto: 'In Photo', timeOutPhoto: 'Out Photo' }
        ],
        incidents: [
            { id: 1, name: 'Late arrival notice', date: '2026-01-05' }
        ]
    },
    {
        id: 2,
        name: 'Sarah Anderson',
        role: 'Lead Engineer',
        daysLogged: 9,
        totalHours: 72,
        records: [
            { date: '2026-01-03', timeIn: '08:40 AM', timeOut: '05:20 PM', timeInPhoto: 'In Photo', timeOutPhoto: 'Out Photo' }
        ],
        incidents: []
    }
])

const employeeTabs = [
    { key: 'attendance', label: 'Attendance' },
    { key: 'incidents', label: 'Incident Reports' }
]

const showEmployeeModal = ref(false)
const employeeModalTab = ref('attendance')
const selectedEmployee = ref({ records: [], incidents: [] })
const showAttendanceRecordModal = ref(false)
const selectedAttendanceRecord = ref({})

const openEmployeeAttendance = (employee) => {
    selectedEmployee.value = employee
    employeeModalTab.value = 'attendance'
    showEmployeeModal.value = true
}

const closeEmployeeModal = () => {
    showEmployeeModal.value = false
    selectedEmployee.value = { records: [], incidents: [] }
}

const openAttendanceRecord = (record) => {
    selectedAttendanceRecord.value = record
    showAttendanceRecordModal.value = true
}

const closeAttendanceRecord = () => {
    showAttendanceRecordModal.value = false
    selectedAttendanceRecord.value = {}
}

const goToComputedPayroll = () => {
    router.push({ name: 'computed_payroll', params: { id: runId } })
}

const handlePrimaryAction = () => {
    if (isApproved.value) {
        Swal.fire({
            title: 'Release payslip?',
            text: 'This will release payslips for the approved payroll.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0c8ce9',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, release'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Payslips released',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        })
        return
    }
    goToComputedPayroll()
}

const newAllowance = ref({
    employeeId: '',
    type: '',
    amount: 0,
    notes: ''
})

const allowanceAssignments = ref([])

const addAllowance = () => {
    const employee = attendanceSummary.value.find(item => item.id === newAllowance.value.employeeId)
    if (!employee || !newAllowance.value.type || !newAllowance.value.amount) return
    allowanceAssignments.value.push({
        id: Date.now(),
        employeeName: employee.name,
        type: newAllowance.value.type,
        amount: newAllowance.value.amount,
        notes: newAllowance.value.notes || '-'
    })
    newAllowance.value = { employeeId: '', type: '', amount: 0, notes: '' }
}

const newDeduction = ref({
    employeeId: '',
    type: '',
    amount: 0,
    notes: ''
})

const deductionAssignments = ref([])

const addDeduction = () => {
    const employee = attendanceSummary.value.find(item => item.id === newDeduction.value.employeeId)
    if (!employee || !newDeduction.value.type || !newDeduction.value.amount) return
    deductionAssignments.value.push({
        id: Date.now(),
        employeeName: employee.name,
        type: newDeduction.value.type,
        amount: newDeduction.value.amount,
        notes: newDeduction.value.notes || '-'
    })
    newDeduction.value = { employeeId: '', type: '', amount: 0, notes: '' }
}

const statutoryCompliance = ref([
    {
        id: 1,
        name: 'SSS Contribution',
        coverage: 'Jan 01 - Jan 15, 2026',
        employeeShare: 18500,
        employerShare: 27500,
        total: 46000,
        dueDate: '2026-02-15',
        status: 'Pending'
    },
    {
        id: 2,
        name: 'PhilHealth Premium',
        coverage: 'Jan 01 - Jan 15, 2026',
        employeeShare: 9800,
        employerShare: 9800,
        total: 19600,
        dueDate: '2026-02-15',
        status: 'Pending'
    },
    {
        id: 3,
        name: 'Pag-IBIG Fund',
        coverage: 'Jan 01 - Jan 15, 2026',
        employeeShare: 4800,
        employerShare: 4800,
        total: 9600,
        dueDate: '2026-02-15',
        status: 'Pending'
    },
    {
        id: 4,
        name: 'Withholding Tax',
        coverage: 'Jan 01 - Jan 15, 2026',
        employeeShare: 6200,
        employerShare: 0,
        total: 6200,
        dueDate: '2026-02-10',
        status: 'Scheduled'
    }
])

const payrollRuns = [
    {
        id: 1,
        name: 'January Bi-Weekly Payroll',
        frequency: 'Bi-Weekly',
        startDate: '2026-01-01',
        endDate: '2026-01-15',
        group: 'fixed',
        status: 'Approved',
        payDate: '2026-01-20',
        description: 'Payroll run for first half of January.'
    },
    {
        id: 2,
        name: 'Site Crew Weekly Payroll',
        frequency: 'Weekly',
        startDate: '2026-01-08',
        endDate: '2026-01-14',
        group: 'hour',
        status: 'Pending',
        payDate: '2026-01-18',
        description: 'Weekly payroll for site crew.'
    },
    {
        id: 3,
        name: 'Project Support Payroll',
        frequency: 'Monthly',
        startDate: '2026-01-01',
        endDate: '2026-01-31',
        group: 'day',
        status: 'Draft',
        payDate: '2026-02-05',
        description: 'Draft payroll for support team.'
    }
]

const queryRun = computed(() => {
    const query = route.query
    if (!query || !query.name) return null
    return {
        id: Number(route.params.id) || 0,
        name: String(query.name || 'Draft Payroll Run'),
        frequency: String(query.frequency || 'Weekly'),
        startDate: String(query.startDate || ''),
        endDate: String(query.endDate || ''),
        group: String(query.group || 'fixed'),
        status: String(query.status || 'Draft'),
        payDate: String(query.payDate || ''),
        description: String(query.description || 'Draft payroll run.')
    }
})

const run = computed(() => {
    return payrollRuns.find(item => item.id === runId) || queryRun.value || payrollRuns[0]
})

const availableTabs = computed(() => {
    if (run.value.status === 'Approved' || run.value.status === 'Pending') {
        return tabs
    }
    return tabs.filter(tab => tab.key !== 'computed')
})

const isApproved = computed(() => run.value.status === 'Approved')
const isPending = computed(() => run.value.status === 'Pending')
const showPrimaryAction = computed(() => !isPending.value)

const primaryActionLabel = computed(() => {
    return isApproved.value ? 'Release Payslip' : 'Compute Payroll'
})

const computedPayrollRows = computed(() => {
    const inputs = [
        { id: 1, employee: 'John Davis', role: 'Project Manager', basicPay: 35000, allowance: 2000, deductions: 1500 },
        { id: 2, employee: 'Sarah Anderson', role: 'Lead Engineer', basicPay: 28000, allowance: 1500, deductions: 1200 },
        { id: 3, employee: 'Michael Chen', role: 'QA Lead', basicPay: 42000, allowance: 2500, deductions: 1800 }
    ]
    return inputs.map(item => ({
        ...item,
        netPay: item.basicPay + item.allowance - item.deductions
    }))
})

const getStatusClasses = (status) => {
    const map = {
        Draft: 'bg-gray-200 text-gray-700',
        Pending: 'bg-yellow-100 text-yellow-800',
        Approved: 'bg-green-100 text-green-800'
    }
    return map[status] || 'bg-gray-100 text-gray-700'
}

const formatDate = (date) => {
    if (!date) return '--'
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const formatNumber = (value) => {
    return Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>
