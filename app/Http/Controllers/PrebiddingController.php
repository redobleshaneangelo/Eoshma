<?php

namespace App\Http\Controllers;

use App\Models\Prebidding;
use App\Models\BiddingActivity;
use App\Models\BiddingDocument;
use App\Models\MaterialPlanning;
use App\Models\EquipmentPlanning;
use App\Models\SubcontractorPlanning;
use App\Models\MiscellaneousPlanning;
use App\Models\CategoryBreakdown;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PrebiddingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activePrebiddings = Prebidding::with(['client', 'biddingDocuments'])
            ->where('status', 'active')
            ->get()
            ->map(function ($prebidding) {
                $documents = $prebidding->biddingDocuments;
                
                // Format client name
                $clientName = null;
                if ($prebidding->client) {
                    $clientName = trim(
                        ($prebidding->client->last_name ?? '') . ', ' . 
                        ($prebidding->client->first_name ?? '') . ' ' . 
                        ($prebidding->client->middle_initial ?? '')
                    );
                }
                
                return [
                    'id' => Hashids::encode($prebidding->id),
                    'image' => $prebidding->image ? asset('storage/' . $prebidding->image) : null,
                    'name' => $prebidding->name,
                    'client_name' => $clientName,
                    'address' => $prebidding->address,
                    'status' => $prebidding->status,
                    'documents_count' => $documents->count(),
                    'uploaded_count' => $documents->where('completed', 1)->count(),
                ];
            });

        $draftPrebiddings = Prebidding::with(['client', 'biddingDocuments'])
            ->where('status', 'draft')
            ->get()
            ->map(function ($prebidding) {
                $documents = $prebidding->biddingDocuments;
                
                // Format client name
                $clientName = null;
                if ($prebidding->client) {
                    $clientName = trim(
                        ($prebidding->client->last_name ?? '') . ', ' . 
                        ($prebidding->client->first_name ?? '') . ' ' . 
                        ($prebidding->client->middle_initial ?? '')
                    );
                }
                
                return [
                    'id' => Hashids::encode($prebidding->id),
                    'image' => $prebidding->image ? asset('storage/' . $prebidding->image) : null,
                    'name' => $prebidding->name,
                    'client_name' => $clientName,
                    'address' => $prebidding->address,
                    'status' => $prebidding->status,
                    'documents_count' => $documents->count(),
                    'uploaded_count' => $documents->where('completed', 1)->count(),
                ];
            });
        
        $pendingPrebiddings = Prebidding::with(['client', 'biddingDocuments'])
            ->where('status', 'LIKE', '%_approval')
            ->get()
            ->map(function ($prebidding) {
                $documents = $prebidding->biddingDocuments;
                
                // Format client name
                $clientName = null;
                if ($prebidding->client) {
                    $clientName = trim(
                        ($prebidding->client->last_name ?? '') . ', ' . 
                        ($prebidding->client->first_name ?? '') . ' ' . 
                        ($prebidding->client->middle_initial ?? '')
                    );
                }
                
                return [
                    'id' => Hashids::encode($prebidding->id),
                    'image' => $prebidding->image ? asset('storage/' . $prebidding->image) : null,
                    'name' => $prebidding->name,
                    'client_name' => $clientName,
                    'address' => $prebidding->address,
                    'status' => $prebidding->status,
                    'documents_count' => $documents->count(),
                    'uploaded_count' => $documents->where('completed', 1)->count(),
                ];
            });

        $rejectedPrebiddings = Prebidding::with(['client', 'biddingDocuments'])
            ->where('status', 'rejected')
            ->get()
            ->map(function ($prebidding) {
                $documents = $prebidding->biddingDocuments;
                
                // Format client name
                $clientName = null;
                if ($prebidding->client) {
                    $clientName = trim(
                        ($prebidding->client->last_name ?? '') . ', ' . 
                        ($prebidding->client->first_name ?? '') . ' ' . 
                        ($prebidding->client->middle_initial ?? '')
                    );
                }
                
                return [
                    'id' => Hashids::encode($prebidding->id),
                    'image' => $prebidding->image ? asset('storage/' . $prebidding->image) : null,
                    'name' => $prebidding->name,
                    'client_name' => $clientName,
                    'address' => $prebidding->address,
                    'status' => $prebidding->status,
                    'documents_count' => $documents->count(),
                    'uploaded_count' => $documents->where('completed', 1)->count(),
                ];
            });

        return response()->json([
            'active' => $activePrebiddings,
            'draft' => $draftPrebiddings,
            'pending' => $pendingPrebiddings,
            'rejected' => $rejectedPrebiddings,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Decode JSON data from FormData
        $data = json_decode($request->input('data'), true);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('prebiddings', 'public');
        }

        // Handle uploaded document files
        $uploadedDocumentPaths = [];
        $files = $request->file('documents');
        if ($files) {
            // Handle if files is an array
            $filesArray = is_array($files) ? $files : [$files];
            foreach ($filesArray as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('prebiddings/documents', 'public');
                    $uploadedDocumentPaths[$index] = $path;
                }
            }
        }

        // Update uploaded documents with file paths
        if (!empty($uploadedDocumentPaths) && isset($data['documents']['uploaded'])) {
            foreach ($uploadedDocumentPaths as $index => $path) {
                if (isset($data['documents']['uploaded'][$index])) {
                    $data['documents']['uploaded'][$index]['file_path'] = $path;
                }
            }
        }
        
        // Decode client_id from Hashids
        $clientIdEncoded = $data['projectInfo']['client_id'] ?? null;
        $decodedClientId = null;
        
        if ($clientIdEncoded) {
            // If it's an object, extract the id property
            if (is_array($clientIdEncoded)) {
                $clientIdEncoded = $clientIdEncoded['id'] ?? null;
            }
            // Decode using Hashids
            if ($clientIdEncoded) {
                $decodedClientId = Hashids::decode($clientIdEncoded);
            }
        }

        // =====================================================
        // MAIN RECORD DATA (goes to 'prebiddings' table)
        // =====================================================
        
        $mainData = [
            // Project Information
            'name' => $data['projectInfo']['name'] ?? null,
            'code' => $data['projectInfo']['code'] ?? null,
            'type' => $data['projectInfo']['type'] ?? null,
            'address' => $data['projectInfo']['address'] ?? null,
            'client_id' => $decodedClientId[0] ?? null,
            'image' => $imagePath,
            'notes' => $data['projectInfo']['notes'] ?? null,

            // Bid Details
            'bid_deadline' => $data['bidDetails']['deadline'] ?? null,
            'bid_opening' => $data['bidDetails']['opening'] ?? null,
            'bid_type' => $data['bidDetails']['type'] ?? null,
            'bond_type' => $data['bidDetails']['bondType'] ?? null,
            'security_amount' => $data['bidDetails']['amount'] ?? 0,

            // Project Setup
            'supervisor_id' => $data['projectSetup']['supervisor_id'] ?? null,
            'foreman_id' => $data['projectSetup']['foreman_id'] ?? null,

            // Financial
            'total_budget' => $data['financial']['totalBudget'] ?? 0,
            'total_utilized' => $data['financial']['totalBudgetUtilized'] ?? 0,
            'contract_amount' => $data['financial']['contractAmount'] ?? 0,
            'billing_type' => $data['financial']['billingType'] ?? null,
            'retention' => $data['financial']['retention'] ?? 0,

            'status' => 'draft', // Default status on creation
        ];

        // =====================================================
        // RELATED DATA (needs separate storage - JSON or tables)
        // =====================================================
        $relatedData = [
            // Bidding Activities
            'bidding_activities' => $data['biddingActivities'] ?? [],

            // Documents
            'document_checklist' => $data['documents']['checklist'] ?? [],
            'uploaded_documents' => $data['documents']['uploaded'] ?? [],

            // Planning
            'material_planning' => $data['materialPlanning'] ?? [],
            'equipment_planning' => $data['equipmentPlanning'] ?? [],

            // Cost Plans
            'material_cost_plan' => $data['costPlans']['materials'] ?? [],
            'labor_cost_plan' => $data['costPlans']['labor'] ?? [],
            'equipment_cost_plan' => $data['costPlans']['equipment'] ?? [],
            'subcontractor_cost_plan' => $data['costPlans']['subcontractors'] ?? [],
            'miscellaneous_cost_plan' => $data['costPlans']['miscellaneous'] ?? [],

            // Category Breakdown
            'category_breakdown' => $data['categoryBreakdown'] ?? [],
            'category_breakdown_custom' => $data['categoryBreakdownCustom'] ?? [],
        ];

        // =====================================================
        // VALIDATION
        // =====================================================
        // First check if data field exists and is valid JSON
        if (!$request->has('data')) {
            return response()->json(['message' => 'Data field is required'], 422);
        }

        // Validate the decoded data array
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'projectInfo.name' => 'required|string|max:255',
            'projectInfo.code' => 'required|string|unique:prebiddings,code|max:100',
            'projectInfo.type' => 'required|string|max:100',
            'projectInfo.address' => 'required|string|max:1000',
            'projectInfo.client_id' => 'required',
            'projectInfo.notes' => 'nullable|string|max:1000',

            // Bid Details
            'bidDetails.deadline' => 'required|date|after:today',
            'bidDetails.opening' => 'required|date|after_or_equal:bidDetails.deadline',
            'bidDetails.type' => 'required|string|max:100',
            'bidDetails.bondType' => 'required|string|max:100',
            'bidDetails.amount' => 'required|numeric|min:0',

            // Project Setup
            // 'projectSetup.supervisor_id' => 'nullable|exists:users,id',
            'projectSetup.supervisor_id' => 'required', //users
            // 'projectSetup.foreman_id' => 'nullable|exists:users,id',
            'projectSetup.foreman_id' => 'required', //users

            // Financial
            'financial.totalBudget' => 'required|numeric|min:0.01',
            'financial.totalBudgetUtilized' => 'required|numeric|min:0',
            'financial.contractAmount' => 'required|numeric|min:0',
            'financial.billingType' => 'required|string|max:100',
            'financial.retention' => 'required|numeric|min:0',

            // Bidding Activities
            'biddingActivities' => 'required|array|min:1',
            'biddingActivities.*.name' => 'required|string|max:255',
            'biddingActivities.*.date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!empty($uploadedDocumentPaths)) {
                Storage::disk('public')->delete($uploadedDocumentPaths);
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate files in request
        $request->validate([
            'image' => 'nullable|image|max:5120',
            'documents.*' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            // =====================================================
            // SAVE TO DATABASE
            // =====================================================

            // Create main prebidding record
            $prebidding = Prebidding::create($mainData);

            // Store bidding activities
            if (!empty($relatedData['bidding_activities'])) {
                foreach ($relatedData['bidding_activities'] as $activity) {
                    BiddingActivity::create([
                        'name' => $activity['name'] ?? null,
                        'date' => $activity['date'] ?? null,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            // Store bidding documents (merge checklist and uploaded documents)
            $checklist = $relatedData['document_checklist'] ?? [];
            $uploaded = $relatedData['uploaded_documents'] ?? [];

            // Process and merge all documents
            $allDocuments = [];

            // Add checklist items
            foreach ($checklist as $doc) {
                $allDocuments[$doc['name']] = [
                    'name' => $doc['name'] ?? null,
                    'completed' => $doc['completed'] ?? 0,
                    'file_path' => null,
                    'uploaded_by' => null,
                    'uploaded_at' => null,
                ];
            }

            // Add/update uploaded items (prevent duplicates by document name)
            foreach ($uploaded as $doc) {
                $uploadedAt = $doc['uploadedAt'] ?? null;

                if ($uploadedAt) {
                    $uploadedAt = trim($uploadedAt);
                    try {
                        // Parse DD/MM/YYYY, HH:MM:SS format
                        $date = Carbon::createFromFormat('d/m/Y, H:i:s', $uploadedAt);
                        $uploadedAt = $date->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        $uploadedAt = null;
                        \Log::warning("Invalid uploaded_at date: {$doc['uploadedAt']}");
                    }
                }

                // Update existing checklist item or create new one
                $allDocuments[$doc['name']] = [
                    'name' => $doc['name'] ?? null,
                    'completed' => 1,
                    'file_path' => $doc['file_path'] ?? null,
                    'uploaded_by' => $doc['uploadedBy'] ?? null,
                    'uploaded_at' => $uploadedAt,
                ];
            }

            // Store all documents
            foreach ($allDocuments as $document) {
                BiddingDocument::create([
                    'prebidding_id' => $prebidding->id,
                    'name' => $document['name'],
                    'completed' => $document['completed'],
                    'file_path' => $document['file_path'],
                    'uploaded_by' => $document['uploaded_by'],
                    'uploaded_at' => $document['uploaded_at'],
                ]);
            }

            // Store material planning
            if (!empty($relatedData['material_planning'])) {
                foreach ($relatedData['material_planning'] as $material) {
                    MaterialPlanning::create([
                        'name' => $material['name'] ?? null,
                        'unit' => $material['unit'] ?? null,
                        'quantity' => $material['quantity'] ?? 0,
                        'available' => $material['available'] ?? 0,
                        'source' => $material['source'] ?? null,
                        'unit_cost' => $material['cost'] ?? 0,
                        'cost_code' => $material['code'] ?? null,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            // Store equipment planning
            if (!empty($relatedData['equipment_planning'])) {
                foreach ($relatedData['equipment_planning'] as $equipment) {
                    EquipmentPlanning::create([
                        'name' => $equipment['name'] ?? null,
                        'type' => $equipment['type'] ?? null,
                        'quantity' => $equipment['quantity'] ?? 0,
                        'available' => $equipment['available'] ?? 0,
                        'unit_cost' => $equipment['cost'] ?? 0,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            // Store subcontractor planning
            if (!empty($relatedData['subcontractor_cost_plan'])) {
                foreach ($relatedData['subcontractor_cost_plan'] as $subcontractor) {
                    SubcontractorPlanning::create([
                        'name' => $subcontractor['name'] ?? null,
                        'scope' => $subcontractor['scope'] ?? null,
                        'planned_cost' => $subcontractor['cost'] ?? 0,
                        'cost_code' => $subcontractor['code'] ?? null,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            // Store miscellaneous planning
            if (!empty($relatedData['miscellaneous_cost_plan'])) {
                foreach ($relatedData['miscellaneous_cost_plan'] as $miscellaneous) {
                    MiscellaneousPlanning::create([
                        'name' => $miscellaneous['name'] ?? null,
                        'planned_cost' => $miscellaneous['cost'] ?? 0,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            // Store category breakdown (merge auto-generated and custom)
            $allCategoryBreakdowns = array_merge(
                $relatedData['category_breakdown'] ?? [],
                $relatedData['category_breakdown_custom'] ?? []
            );

            if (!empty($allCategoryBreakdowns)) {
                foreach ($allCategoryBreakdowns as $category) {
                    CategoryBreakdown::create([
                        'name' => $category['name'] ?? null,
                        'description' => $category['description'] ?? null,
                        'amount' => $category['amount'] ?? 0,
                        'prebidding_id' => $prebidding->id,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Prebidding created successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating prebidding',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prebidding $prebidding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prebidding $prebidding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prebidding $prebidding)
    {
        //
    }
}
