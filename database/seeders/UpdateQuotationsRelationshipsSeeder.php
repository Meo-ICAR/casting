<?php

namespace Database\Seeders;

use App\Models\ProjectService;
use App\Models\Quotation;
use Illuminate\Database\Seeder;

class UpdateQuotationsRelationshipsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all quotations
        $quotations = Quotation::withTrashed()->get();

        foreach ($quotations as $quotation) {
            // Skip if already has project_service_id
            if ($quotation->project_service_id) {
                continue;
            }

            // Try to find a matching project service
            $projectService = ProjectService::where('project_id', $quotation->project_id)
                ->where('service_type_id', $quotation->service_id)
                ->first();

            if ($projectService) {
                $quotation->update([
                    'project_service_id' => $projectService->id,
                    'service_id' => $projectService->service_type_id
                ]);
            } else {
                // If no matching project service, create one
                $projectService = ProjectService::create([
                    'project_id' => $quotation->project_id,
                    'service_type_id' => $quotation->service_id,
                    'name' => 'Servizio ' . ($quotation->service?->name ?? 'Sconosciuto'),
                    'description' => 'Creato automaticamente durante la migrazione',
                    'status' => 'confirmed'
                ]);

                $quotation->update([
                    'project_service_id' => $projectService->id
                ]);
            }
        }
    }
}
