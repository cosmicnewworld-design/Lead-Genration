<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\PipelineStage;
use Livewire\Component;

class CrmPipeline extends Component
{
    public $pipelineStages;

    public function mount()
    {
        $this->pipelineStages = PipelineStage::with('leads')->orderBy('order')->get();
    }

    public function onStageDrop($leadId, $newStageId, $fromStageId, $toStageId, $order)
    {
        $lead = Lead::find($leadId);
        if ($lead) {
            $lead->pipeline_stage_id = $newStageId;
            $lead->save();
        }

        $this->pipelineStages = PipelineStage::with('leads')->orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.crm-pipeline');
    }
}
